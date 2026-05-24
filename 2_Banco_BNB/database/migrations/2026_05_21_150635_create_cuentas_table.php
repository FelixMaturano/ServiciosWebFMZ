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
        Schema::create('cuentas', function (Blueprint $table) {
            $table->string('cuenta')->primary(); // Número de cuenta único
            $table->string('ci');
            $table->string('nombres');
            $table->string('apellidos');
            $table->decimal('saldo', 12, 2)->default(0.00); // Dinero de la cuenta
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
