<?php

namespace Database\Factories;

use App\Models\DoctorSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DoctorSchedule>
 */
class DoctorScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $doctor = \App\Models\Doctor::inRandomOrder()->first() ?? \App\Models\Doctor::factory()->create();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $start = $this->faker->numberBetween(8, 14);
        return [
            'doctor_id' => $doctor->id,
            'day_of_week' => $this->faker->randomElement($days),
            'start_time' => sprintf('%02d:00:00', $start),
            'end_time' => sprintf('%02d:00:00', $start + $this->faker->numberBetween(2, 4)),
            'location_clinic' => 'Klinik ' . ucwords($this->faker->word()),
        ];
    }
}
