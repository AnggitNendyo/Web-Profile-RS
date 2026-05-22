<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\CenterOfExcellence;
use App\Models\Facility;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin RS',
            'email' => 'admin@rsnusantaramedika.id',
            'password' => Hash::make('password'),
        ]);

        // Specialties
        $specialties = [
            'Kardiologi (Jantung)', 'Neurologi (Saraf)', 'Ortopedi (Tulang)', 
            'Onkologi (Kanker)', 'Pediatri (Anak)', 'Mata', 'THT', 'Penyakit Dalam'
        ];

        foreach ($specialties as $sp) {
            Specialty::factory()->create([
                'name' => $sp,
                'slug' => \Illuminate\Support\Str::slug($sp)
            ]);
        }

        // Doctors & Schedules
        Doctor::factory(24)->create()->each(function ($doctor) {
            DoctorSchedule::factory(3)->create(['doctor_id' => $doctor->id]);
        });

        // Center of Excellence
        $coes = ['Pusat Jantung Terpadu', 'Pusat Penanganan Kanker', 'Pusat Saraf & Otak', 'Pusat Ortopedi Komprehensif', 'Pusat Kesehatan Mata'];
        foreach ($coes as $coe) {
            CenterOfExcellence::factory()->create([
                'title' => $coe,
                'slug' => \Illuminate\Support\Str::slug($coe)
            ]);
        }

        // Facilities
        Facility::factory(12)->create();

        // Articles
        Article::factory(10)->create();
    }
}
