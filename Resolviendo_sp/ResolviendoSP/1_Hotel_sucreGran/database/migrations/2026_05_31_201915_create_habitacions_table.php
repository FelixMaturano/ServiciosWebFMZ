<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habitacions', function (Blueprint $table) {
            $table->string('codigoHabitacion')->primary();
            $table->string('tipo');
            $table->string('capacidad');
            $table->decimal('tarifa', 12, 2)->default(0.00);
            //disponible como booleano, pero se puede usar un string para indicar el estado de la habitación
            $table->string('disponible')->default('si');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitacions');
    }
};
