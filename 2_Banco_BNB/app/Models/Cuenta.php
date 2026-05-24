<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    /** @use HasFactory<\Database\Factories\CuentaFactory> */
    use HasFactory;

    protected $primaryKey = 'cuenta'; // Indicamos que la llave es el campo 'cuenta'
    public $incrementing = false;     // No es autoincremental
    protected $keyType = 'string';    // Es de tipo texto

    protected $fillable = [
        'cuenta', 
        'ci', 
        'nombres', 
        'apellidos', 
        'saldo'
    ];
}
