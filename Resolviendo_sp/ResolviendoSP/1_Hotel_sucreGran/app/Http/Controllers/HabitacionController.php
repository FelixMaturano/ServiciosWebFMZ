<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Habitacion::all(), 200);
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
    public function show($id)
    {
        $habitacion = Habitacion::where('codigoHabitacion', $id)->first();
        if (!$habitacion) {
            return response()->json(['error' => 'habitacion no encontrada en el Hotel'], 404);
        }
        return response()->json($habitacion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::where('codigoHabitacion', $id)->first();

        if (!$habitacion) {
            return response()->json(['error' => 'habitacion no encontrada para actualizarF'], 404);
        }
        $request->validate([
            'disponible'=>'required|string'
        ]);
        $habitacion->update([
            'disponible'=> $request->disponible
        ]);
        return response()->json($habitacion, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habitacion $habitacion)
    {
        //
    }
}
