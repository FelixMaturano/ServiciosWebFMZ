<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    /** @use HasFactory<\Database\Factories\TransaccionFactory> */
    use HasFactory;
    protected $fillable = [
        'fecha',
        'cuenta_origen',
        'cuenta_destino',
        'monto',
        'estado'
    ];

}
