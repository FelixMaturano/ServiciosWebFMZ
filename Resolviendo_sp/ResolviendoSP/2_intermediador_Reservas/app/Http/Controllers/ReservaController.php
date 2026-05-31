<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ReservaController extends Controller
{
    // URL base del hotel sucreGran
    private $hotelUrl = 'http://localhost:8001/api';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $reservas = Reserva::all();
            return response()->json($reservas, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener reservas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $validated = $request->validate([
                'codigoHabitacion' => 'required|string',
                'hotel_origen' => 'required|string',
                'cliente_cedula' => 'required|string',
                'cliente_nombre' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after:fecha_inicio',
                'monto' => 'required|numeric|min:0',
            ]);

            // Verificar que la habitación existe en el hotel
            $habitacionResponse = Http::get("{$this->hotelUrl}/habitaciones/{$validated['codigoHabitacion']}");
            
            if ($habitacionResponse->failed()) {
                return response()->json([
                    'error' => 'Habitación no encontrada en el hotel',
                    'codigo_habitacion' => $validated['codigoHabitacion']
                ], 404);
            }

            $habitacion = $habitacionResponse->json();

            // Verificar disponibilidad
            if ($habitacion['disponible'] !== 'si') {
                return response()->json([
                    'error' => 'Habitación no disponible',
                    'codigo_habitacion' => $validated['codigoHabitacion']
                ], 409);
            }

            // Crear la reserva
            $reserva = Reserva::create([
                'codigoHabitacion' => $validated['codigoHabitacion'],
                'hotel_origen' => $validated['hotel_origen'],
                'cliente_cedula' => $validated['cliente_cedula'],
                'cliente_nombre' => $validated['cliente_nombre'],
                'fecha_inicio' => $validated['fecha_inicio'],
                'fecha_final' => $validated['fecha_final'],
                'monto' => $validated['monto'],
                'estado' => 'confirmada',
            ]);

            // Actualizar disponibilidad en el hotel
            $updateResponse = Http::put("{$this->hotelUrl}/habitaciones/{$validated['codigoHabitacion']}", [
                'disponible' => 'no'
            ]);

            if ($updateResponse->failed()) {
                // Si falla la actualización, revertir la reserva
                $reserva->delete();
                return response()->json([
                    'error' => 'No se pudo actualizar la disponibilidad en el hotel'
                ], 500);
            }

            return response()->json($reserva, 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear reserva: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);
            return response()->json($reserva, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $reserva = Reserva::findOrFail($id);

            $validated = $request->validate([
                'estado' => 'sometimes|string|in:confirmada,cancelada,completada',
                'fecha_inicio' => 'sometimes|date',
                'fecha_final' => 'sometimes|date',
                'monto' => 'sometimes|numeric|min:0',
            ]);

            // Si se cancela la reserva, liberar la habitación en el hotel
            if (isset($validated['estado']) && $validated['estado'] === 'cancelada') {
                $updateResponse = Http::put("{$this->hotelUrl}/habitaciones/{$reserva->codigoHabitacion}", [
                    'disponible' => 'si'
                ]);

                if ($updateResponse->failed()) {
                    return response()->json([
                        'error' => 'No se pudo liberar la habitación en el hotel'
                    ], 500);
                }
            }

            $reserva->update($validated);
            return response()->json($reserva, 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $reserva = Reserva::findOrFail($id);

            // Liberar la habitación en el hotel
            $updateResponse = Http::put("{$this->hotelUrl}/habitaciones/{$reserva->codigoHabitacion}", [
                'disponible' => 'si'
            ]);

            if ($updateResponse->failed()) {
                return response()->json([
                    'error' => 'No se pudo liberar la habitación en el hotel'
                ], 500);
            }

            $reserva->delete();
            return response()->json(['mensaje' => 'Reserva eliminada correctamente'], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json(['error' => 'Reserva no encontrada'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener habitaciones disponibles del hotel
     */
    public function getHoteles()
    {
        try {
            $response = Http::get("{$this->hotelUrl}/habitaciones");
            
            if ($response->failed()) {
                return response()->json([
                    'error' => 'Error al conectar con el hotel'
                ], 500);
            }

            $habitaciones = $response->json();
            
            // Filtrar solo las disponibles
            $disponibles = array_filter($habitaciones, function ($hab) {
                return $hab['disponible'] === 'si';
            });

            return response()->json([
                'total' => count($habitaciones),
                'disponibles' => count($disponibles),
                'habitaciones' => $disponibles
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
