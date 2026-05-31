<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    /** @use HasFactory<\Database\Factories\HabitacionFactory> */
    use HasFactory;

    protected $primaryKey = 'codigoHabitacion';
    public $incrementing = false;
    protected $keyType = 'string';




    protected $fillable = [
        'codigoHabitacion',
        'tipo',
        'capacidad',
        'tarifa',
        'disponible',
    ];
}
