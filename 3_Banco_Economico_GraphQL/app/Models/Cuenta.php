<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    /** @use HasFactory<\Database\Factories\CuentaFactory> */
    use HasFactory;
    protected $primaryKey = 'cuenta';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'cuenta',
        'ci',
        'nombres',
        'apellidos',
        'saldo'
    ];
    // 🌟 NUEVO: Evento automático para el historial
    protected static function booted()
    {
        static::updating(function ($cuenta) {
            // Si el saldo cambió, calculamos la diferencia para el historial
            if ($cuenta->isDirty('saldo')) {
                $saldoOriginal = floatval($cuenta->getOriginal('saldo'));
                $saldoNuevo = floatval($cuenta->saldo);
                $diferencia = $saldoNuevo - $saldoOriginal;

                // Registramos el movimiento en la base de datos
                Movimiento::create([
                    'cuenta_id' => $cuenta->cuenta,
                    'fecha' => now(),
                    'monto' => abs($diferencia), // Guardamos el valor absoluto
                    'tipo' => $diferencia > 0 ? 'ABONO' : 'DEBITO'
                ]);
            }
        });
    }
}
