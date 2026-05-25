<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Dokter Umum',
                'department' => 'Medis',
                'location' => 'On-site',
                'type' => 'Full-time',
                'description' => '<p>Kami mencari Dokter Umum yang berdedikasi tinggi...</p>',
                'requirements' => '<ul><li>Pendidikan profesi dokter.</li><li>Memiliki STR aktif.</li></ul>',
                'apply_link' => 'https://forms.gle/dummy',
                'deadline' => now()->addDays(30),
            ],
            [
                'title' => 'Perawat Rawat Inap',
                'department' => 'Keperawatan',
                'location' => 'On-site',
                'type' => 'Full-time',
                'description' => '<p>Dibutuhkan perawat rawat inap berpengalaman...</p>',
                'requirements' => '<ul><li>D3/S1 Keperawatan.</li><li>Memiliki STR aktif.</li><li>Bersedia kerja shifting.</li></ul>',
                'apply_email' => 'hrd@rs-nusantara.com',
                'deadline' => now()->addDays(15),
            ],
            [
                'title' => 'Staf IT Support',
                'department' => 'Non-Medis',
                'location' => 'On-site',
                'type' => 'Full-time',
                'description' => '<p>Mendukung operasional sistem informasi rumah sakit...</p>',
                'requirements' => '<ul><li>S1 Teknik Informatika / Sistem Informasi.</li><li>Pengalaman min 1 tahun.</li></ul>',
                'apply_email' => 'hrd@rs-nusantara.com',
                'deadline' => now()->addDays(10),
            ],
        ];

        foreach ($jobs as $job) {
            $job['slug'] = \Illuminate\Support\Str::slug($job['title']) . '-' . rand(100, 999);
            \App\Models\JobVacancy::create($job);
        }
    }
}
