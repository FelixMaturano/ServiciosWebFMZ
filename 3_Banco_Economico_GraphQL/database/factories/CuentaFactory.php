<?php

namespace Database\Factories;

use App\Models\Cuenta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cuenta>
 */
class CuentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cuenta' => $this->faker->unique()->numerify('200-######'), // Formato simulado Banco Económico
            'ci' => $this->faker->randomNumber(8, true),
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'saldo' => $this->faker->randomFloat(2, 500, 1000), // Saldos de prueba iniciales
        ];
    }
}
