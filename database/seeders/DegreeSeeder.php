<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $degrees = [
            ['name' => 'dr.', 'description' => 'Dokter Umum'],
            ['name' => 'drg.', 'description' => 'Dokter Gigi'],
            ['name' => 'Sp.A', 'description' => 'Spesialis Anak'],
            ['name' => 'Sp.PD', 'description' => 'Spesialis Penyakit Dalam'],
            ['name' => 'Sp.OG', 'description' => 'Spesialis Obstetri dan Ginekologi (Kandungan)'],
            ['name' => 'Sp.B', 'description' => 'Spesialis Bedah'],
            ['name' => 'Sp.M', 'description' => 'Spesialis Mata'],
            ['name' => 'Sp.THT-KL', 'description' => 'Spesialis Telinga, Hidung, Tenggorok, Bedah Kepala dan Leher'],
            ['name' => 'Sp.S', 'description' => 'Spesialis Saraf'],
            ['name' => 'Sp.KJ', 'description' => 'Spesialis Kedokteran Jiwa (Psikiater)'],
        ];

        foreach ($degrees as $degree) {
            \App\Models\Degree::updateOrCreate(
                ['name' => $degree['name']],
                $degree
            );
        }
    }
}
