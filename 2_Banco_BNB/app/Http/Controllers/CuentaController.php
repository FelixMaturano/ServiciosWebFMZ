<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Get /api/cuentas (Listar todas las cuentas)
    public function index()
    {
        return response()->json(Cuenta::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // GET /api/cuentas/{id} (Buscar una cuenta especifica por su número de cuenta)
    public function show($id)
    {
        // 🌟 CORRECCIÓN: Buscamos explícitamente por la columna 'cuenta'
        $cuenta = Cuenta::where('cuenta', $id)->first();
        
        if (!$cuenta) {
            return response()->json(['error' => 'Cuenta no encontrada en el BNB'], 404);
        }
        return response()->json($cuenta, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 🌟 CORRECCIÓN: Buscamos explícitamente por la columna 'cuenta'
        $cuenta = Cuenta::where('cuenta', $id)->first();
        
        if (!$cuenta) {
            return response()->json(['error' => 'Cuenta no encontrada en el BNB para actualizar'], 404);
        }

        $request->validate([
            'saldo' => 'required|numeric|min:0'
        ]);

        // Actualizamos el saldo directamente en la base de datos
        $cuenta->update([
            'saldo' => $request->saldo
        ]);

        return response()->json($cuenta, 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cuenta $cuenta)
    {
        //
    }
}
