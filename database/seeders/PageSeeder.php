<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Page;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Panduan Pasien',
                'slug' => 'panduan-pasien',
                'content' => '<h2>Panduan Pasien Rawat Inap & Jalan</h2><p>Selamat datang di RS Nusantara Medika. Untuk kenyamanan Anda, perhatikan panduan berikut...</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Hak & Kewajiban Pasien',
                'slug' => 'hak-dan-kewajiban-pasien',
                'content' => '<h2>Hak dan Kewajiban Pasien</h2><p>Setiap pasien berhak mendapatkan pelayanan medis terbaik...</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Jadwal Dokter',
                'slug' => 'jadwal-dokter',
                'content' => '<h2>Jadwal Praktik Dokter Poliklinik</h2><p>Untuk informasi jadwal dokter terbaru, silakan gunakan fitur pencarian pada menu Dokter.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Karir',
                'slug' => 'karir',
                'content' => '<h2>Bergabunglah Bersama Kami</h2><p>Saat ini belum ada lowongan pekerjaan yang tersedia. Silakan kunjungi halaman ini secara berkala.</p>',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                $page
            );
        }
    }
}
