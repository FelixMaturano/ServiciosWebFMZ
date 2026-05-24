<?php

namespace App\Http\Controllers;

use App\Models\Transaccion;
use Illuminate\Http\Request;

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
        $request->validate([
            'cuenta_origen' => 'required|string',
            'cuenta_destino' => 'required|string',
            'monto' => 'required|numeric|min:1'
        ]);

        // Creamos el registro de la transacción
        $transaccion = Transaccion::create([
            'fecha' => now(),
            'cuenta_origen' => $request->cuenta_origen,
            'cuenta_destino' => $request->cuenta_destino,
            'monto' => $request->monto,
            'estado' => 'PROCESADO'
        ]);

        return response()->json([
            'mensaje' => 'Transacción registrada en el Intermediador exitosamente',
            'detalle' => $transaccion
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
    public function update(Request $request, Transaccion $transaccion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaccion $transaccion)
    {
        //
    }
}
