<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cuenta;

class TransaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'cuenta_origen' => 'required|string',
            'cuenta_destino' => 'required|string',
            'monto' => 'required|numeric|min:1'
        ]);

        $cuentaOrigen = $request->cuenta_origen;
        $cuentaDestino = $request->cuenta_destino;
        $montoTransferir = floatval($request->monto); // Aseguramos que sea flotante

        // =================================================================
        // PASO A: VALIDAR Y OBTENER DATOS DE LA CUENTA ORIGEN
        // =================================================================
        $saldoOrigen = 0;
        $nombreOrigen = "";

        if (!str_starts_with($cuentaOrigen, '200-')) {
            // Es BNB (REST)
            $responseBNB = Http::get("http://127.0.0.1:8001/api/cuentas/{$cuentaOrigen}");
            if ($responseBNB->failed()) {
                return response()->json(['error' => 'La cuenta de origen BNB no existe.'], 404);
            }
            $cuentaData = $responseBNB->json();
            $saldoOrigen = floatval($cuentaData['saldo']);
            $nombreOrigen = $cuentaData['nombres'] . " " . $cuentaData['apellidos'];
        } else {
            // Es Económico (GraphQL)
            $queryGraphQL = 'query($id: String!) { cuenta(cuenta: $id) { nombres apellidos saldo } }';
            $responseEco = Http::post("http://127.0.0.1:8002/graphql", [
                'query' => $queryGraphQL,
                'variables' => ['id' => $cuentaOrigen]
            ]);
            $resultado = $responseEco->json();
            if (!isset($resultado['data']['cuenta']) || $resultado['data']['cuenta'] == null) {
                return response()->json(['error' => 'La cuenta de origen Banco Económico no existe.'], 404);
            }
            $saldoOrigen = floatval($resultado['data']['cuenta']['saldo']);
            $nombreOrigen = $resultado['data']['cuenta']['nombres'] . " " . $resultado['data']['cuenta']['apellidos'];
        }

        // CONTROL: ¿Tiene dinero suficiente?
        if ($saldoOrigen < $montoTransferir) {
            return response()->json([
                'error' => 'Fondos insuficientes.',
                'saldo_actual' => $saldoOrigen,
                'solicitado' => $montoTransferir
            ], 400);
        }

        // =================================================================
        // PASO B: VALIDAR Y OBTENER DATOS DE LA CUENTA DESTINO
        // =================================================================
        $saldoDestino = 0;
        $nombreDestino = "";

        if (!str_starts_with($cuentaDestino, '200-')) {
            // Destino es BNB (REST)
            $responseBNB = Http::get("http://127.0.0.1:8001/api/cuentas/{$cuentaDestino}");
            if ($responseBNB->failed()) {
                return response()->json(['error' => 'La cuenta de destino BNB no existe.'], 404);
            }
            $cuentaData = $responseBNB->json();
            $saldoDestino = floatval($cuentaData['saldo']);
            $nombreDestino = $cuentaData['nombres'] . " " . $cuentaData['apellidos'];
        } else {
            // Destino es Económico (GraphQL)
            $queryGraphQL = 'query($id: String!) { cuenta(cuenta: $id) { nombres apellidos saldo } }';
            $responseEco = Http::post("http://127.0.0.1:8002/graphql", [
                'query' => $queryGraphQL,
                'variables' => ['id' => $cuentaDestino]
            ]);
            $resultado = $responseEco->json();
            if (!isset($resultado['data']['cuenta']) || $resultado['data']['cuenta'] == null) {
                return response()->json(['error' => 'La cuenta de destino Banco Económico no existe.'], 404);
            }
            $saldoDestino = floatval($resultado['data']['cuenta']['saldo']);
            $nombreDestino = $resultado['data']['cuenta']['nombres'] . " " . $resultado['data']['cuenta']['apellidos'];
        }


        // =================================================================
        // PASO C: EJECUTAR EL MOVIMIENTO (Restar al Origen y Sumar al Destino)
        // =================================================================
        
        // 1. RESTAR DINERO A LA CUENTA DE ORIGEN
        $nuevoSaldoOrigen = $saldoOrigen - $montoTransferir;
        if (!str_starts_with($cuentaOrigen, '200-')) {
            Http::put("http://127.0.0.1:8001/api/cuentas/{$cuentaOrigen}", ['saldo' => $nuevoSaldoOrigen]);
        } else {
            $mutation = 'mutation($id: String!, $saldo: Float!) { actualizarSaldo(cuenta: $id, saldo: $saldo) { cuenta } }';
            Http::post("http://127.0.0.1:8002/graphql", [
                'query' => $mutation,
                'variables' => ['id' => $cuentaOrigen, 'saldo' => $nuevoSaldoOrigen]
            ]);
        }

        // 2. SUMAR DINERO A LA CUENTA DE DESTINO 🌟 (AQUÍ ESTÁ LA CORRECCIÓN)
        $nuevoSaldoDestino = $saldoDestino + $montoTransferir;
        if (!str_starts_with($cuentaDestino, '200-')) {
            // BNB (REST)
            $respuestaSuma = Http::put("http://127.0.0.1:8001/api/cuentas/{$cuentaDestino}", ['saldo' => $nuevoSaldoDestino]);
            if ($respuestaSuma->failed()) {
                return response()->json(['error' => 'Error al sumar saldo en el BNB', 'detalles' => $respuestaSuma->json()], 500);
            }
        } else {
            // Económico (GraphQL) - 🌟 Forzamos el ID de la cuenta destino aquí
            $mutation = 'mutation($id: String!, $saldo: Float!) { actualizarSaldo(cuenta: $id, saldo: $saldo) { cuenta } }';
            $respuestaSuma = Http::post("http://127.0.0.1:8002/graphql", [
                'query' => $mutation,
                'variables' => ['id' => $cuentaDestino, 'saldo' => $nuevoSaldoDestino] // <- Corregido a cuentaDestino
            ]);
            
            // Si GraphQL devolvió errores internos en el JSON
            if (isset($respuestaSuma->json()['errors'])) {
                return response()->json(['error' => 'GraphQL rechazó la suma de saldo', 'detalles' => $respuestaSuma->json()['errors']], 500);
            }
        }

        // =================================================================
        // PASO D: REGISTRAR EL ÉXITO EN LA BITÁCORA LOCAL
        // =================================================================
        $transaccion = Transaccion::create([
            'fecha' => now(),
            'cuenta_origen' => $cuentaOrigen,
            'cuenta_destino' => $cuentaDestino,
            'monto' => $montoTransferir,
            'estado' => 'LIQUIDADO'
        ]);

        return response()->json([
            'mensaje' => '¡Transferencia Interbancaria procesada con éxito!',
            'origen' => ['cuenta' => $cuentaOrigen, 'titular' => $nombreOrigen, 'nuevo_saldo' => $nuevoSaldoOrigen],
            'destino' => ['cuenta' => $cuentaDestino, 'titular' => $nombreDestino, 'nuevo_saldo' => $nuevoSaldoDestino],
            'monto_transferido' => $montoTransferir,
            'comprobante_pasarela' => $transaccion->id
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaccion $transaccion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // PUT /api/cuentas/{id}
    public function update(Request $request, $id)
    {
        $cuenta = Cuenta::find($id);
        if (!$cuenta) {
            return response()->json(['error' => 'Cuenta no encontrada'], 404);
        }

        // Validar que manden el saldo
        $request->validate([
            'saldo' => 'required|numeric|min:0'
        ]);

        // Actualizar el saldo
        $cuenta->update([
            'saldo' => $request->saldo
        ]);

        return response()->json($cuenta, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaccion $transaccion)
    {
        //
    }
}
