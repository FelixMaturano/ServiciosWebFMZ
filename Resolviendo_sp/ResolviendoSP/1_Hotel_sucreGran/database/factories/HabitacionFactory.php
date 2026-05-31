<?php

namespace Database\Factories;

use App\Models\Habitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Habitacion>
 */
class HabitacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigoHabitacion' => $this->faker->unique()->bothify('HAB-###'),
            'tipo' => $this->faker->randomElement(['individual', 'doble', 'suite']),
            'capacidad' => $this->faker->randomElement(['1', '2', '4']),
            'tarifa' => $this->faker->randomFloat(2, 50, 500),
            'disponible' => $this->faker->randomElement(['si', 'no']),
        ];
    }
}
