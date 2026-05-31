<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('cuenta_id'); // Relación con el número de cuenta
            $table->dateTime('fecha');   // Campo solicitado en la práctica
            $table->decimal('monto', 12, 2); // Campo solicitado en la práctica
            $table->string('tipo');      // 'ABONO' (+) o 'DEBITO' (-) para control interno
            $table->timestamps();

            // Creamos la llave foránea de forma manual apuntando a 'cuenta' de la tabla 'cuentas'
            $table->foreign('cuenta_id')->references('cuenta')->on('cuentas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
