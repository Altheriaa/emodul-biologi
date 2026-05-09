<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nuptk' => fake()->unique()->numberBetween(1000000000000000, 9999999999999999),
            'jabatan' => fake()->randomElement(['Profesor', 'Doktor', 'Lektor', 'Asisten Dosen']),
        ];
    }
}
