<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Facility>
 */
class FacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Rawat Inap', 'Pencitraan', 'Laboratorium', 'Fasilitas Umum'];
        return [
            'category' => $this->faker->randomElement($categories),
            'name' => ucwords($this->faker->words(2, true)),
            'description' => $this->faker->paragraph(),
            'features' => [
                ['name' => 'Fitur 1', 'icon' => 'check'],
                ['name' => 'Fitur 2', 'icon' => 'check'],
            ],
        ];
    }
}
