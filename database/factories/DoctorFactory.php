<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'front_title' => 'dr.',
            'back_title' => 'Sp.' . strtoupper($this->faker->lexify('??')),
            'title' => null, // Legacy column
            'education' => [
                ['degree' => 'S1 Kedokteran', 'institution' => 'Universitas Indonesia', 'year' => $this->faker->numberBetween(2000, 2010)],
                ['degree' => 'Spesialis', 'institution' => 'Universitas Gadjah Mada', 'year' => $this->faker->numberBetween(2011, 2020)],
            ],
            'start_year' => $this->faker->numberBetween(1990, 2020),
            'photo_path' => null,
            'is_active' => $this->faker->boolean(90),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Doctor $doctor) {
            $specialty = \App\Models\Specialty::inRandomOrder()->first() ?? \App\Models\Specialty::factory()->create();
            $doctor->specialties()->attach($specialty->id);
        });
    }
}
