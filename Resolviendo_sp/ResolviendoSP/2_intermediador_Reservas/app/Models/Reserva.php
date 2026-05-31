<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    /** @use HasFactory<\Database\Factories\ReservaFactory> */
    use HasFactory;

    protected $fillable = [
        'codigoHabitacion',
        'hotel_origen',
        'cliente_nombre',
        'cliente_cedula',
        'fecha_inicio',
        'fecha_final',
        'monto',
        'estado',
    ];
}
