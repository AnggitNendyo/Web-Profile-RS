<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\SiteSetting::create([
            'hospital_name' => 'RS Nusantara Medika',
            'hospital_description' => 'Melayani dengan hati, mengutamakan keselamatan pasien, dan memberikan perawatan medis terbaik dengan teknologi terkini.',
            'contact_phone' => '(021) 1500-123',
            'contact_email' => 'info@rsnusantaramedika.id',
            'contact_address' => 'Jl. Kesehatan No. 123, Jakarta Selatan',
            'contact_emergency' => '119',
            'social_facebook' => 'https://facebook.com',
            'social_instagram' => 'https://instagram.com',
            'social_youtube' => 'https://youtube.com',
            'hero_title' => 'Pelayanan Medis Berstandar Internasional',
            'hero_subtitle' => 'Kesembuhan Anda adalah prioritas utama kami. Didukung oleh teknologi medis terkini dan tim dokter spesialis berpengalaman.',
            'stats_years' => 15,
            'stats_patients' => 50000,
        ]);
    }
}
