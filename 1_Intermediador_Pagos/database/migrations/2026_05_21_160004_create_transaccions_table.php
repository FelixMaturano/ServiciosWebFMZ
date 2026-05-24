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
        Schema::create('transaccions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha');
            $table->string('cuenta_origen');
            $table->string('cuenta_destino');
            $table->decimal('monto', 12, 2);
            $table->string('estado')->default('PROCESADO'); // PROCESADO, RECHAZADO, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};
