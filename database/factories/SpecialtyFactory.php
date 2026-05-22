<?php

namespace Database\Factories;

use App\Models\Specialty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Specialty>
 */
class SpecialtyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucwords($this->faker->words(2, true)),
            'description' => $this->faker->paragraph(),
            'icon' => 'heroicon-o-heart',
            'slug' => $this->faker->unique()->slug(),
        ];
    }
}
