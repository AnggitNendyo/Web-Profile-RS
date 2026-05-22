<?php

namespace Database\Factories;

use App\Models\CenterOfExcellence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CenterOfExcellence>
 */
class CenterOfExcellenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->words(3, true);
        return [
            'title' => ucwords($title),
            'short_description' => $this->faker->sentence(),
            'full_content' => $this->faker->paragraphs(3, true),
            'technology_used' => [
                ['name' => 'Teknologi A', 'description' => $this->faker->sentence()],
                ['name' => 'Teknologi B', 'description' => $this->faker->sentence()],
            ],
            'slug' => \Illuminate\Support\Str::slug($title),
        ];
    }
}
