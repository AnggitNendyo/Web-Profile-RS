<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori Fasilitas
        $facilityCategories = [
            ['name' => 'Rawat Inap', 'slug' => 'rawat-inap'],
            ['name' => 'Rawat Jalan', 'slug' => 'rawat-jalan'],
            ['name' => 'Laboratorium', 'slug' => 'laboratorium'],
            ['name' => 'Diagnostik & Imaging', 'slug' => 'diagnostik-imaging'],
            ['name' => 'Layanan Darurat', 'slug' => 'layanan-darurat'],
        ];

        foreach ($facilityCategories as $cat) {
            \App\Models\FacilityCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Kategori Artikel
        $articleCategories = [
            ['name' => 'Berita RS', 'slug' => 'berita-rs'],
            ['name' => 'Edukasi Kesehatan', 'slug' => 'edukasi-kesehatan'],
            ['name' => 'Promo & Paket', 'slug' => 'promo-paket'],
            ['name' => 'Event & Acara', 'slug' => 'event-acara'],
        ];

        foreach ($articleCategories as $cat) {
            \App\Models\ArticleCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Spesialisasi Dokter
        $specialties = [
            ['name' => 'Dokter Umum', 'slug' => 'dokter-umum'],
            ['name' => 'Spesialis Anak', 'slug' => 'spesialis-anak'],
            ['name' => 'Spesialis Penyakit Dalam', 'slug' => 'spesialis-penyakit-dalam'],
            ['name' => 'Spesialis Kebidanan & Kandungan', 'slug' => 'spesialis-kebidanan-kandungan'],
            ['name' => 'Spesialis Bedah Umum', 'slug' => 'spesialis-bedah-umum'],
            ['name' => 'Spesialis Jantung', 'slug' => 'spesialis-jantung'],
            ['name' => 'Spesialis Saraf', 'slug' => 'spesialis-saraf'],
            ['name' => 'Dokter Gigi', 'slug' => 'dokter-gigi'],
        ];

        foreach ($specialties as $spec) {
            \App\Models\Specialty::firstOrCreate(['slug' => $spec['slug']], $spec);
        }

        // 4. Gelar Dokter
        $degrees = [
            ['name' => 'dr.'],
            ['name' => 'drg.'],
            ['name' => 'Sp.A'],
            ['name' => 'Sp.PD'],
            ['name' => 'Sp.OG'],
            ['name' => 'Sp.B'],
            ['name' => 'Sp.JP'],
            ['name' => 'Sp.S'],
            ['name' => 'MARS'],
            ['name' => 'M.Kes'],
        ];

        foreach ($degrees as $deg) {
            \App\Models\Degree::firstOrCreate(['name' => $deg['name']], $deg);
        }
    }
}
