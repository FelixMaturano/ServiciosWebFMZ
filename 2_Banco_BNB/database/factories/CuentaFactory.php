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
            'cuenta' => $this->faker->unique()->numerify('111-######'),
            'ci' => $this->faker->randomNumber(8, true),
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'saldo' => $this->faker->randomFloat(2, 100, 5000), // Saldos entre 100 y 5000 bs
        ];
    }
}
