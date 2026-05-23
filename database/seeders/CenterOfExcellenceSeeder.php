<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CenterOfExcellenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\CenterOfExcellence::updateOrCreate(
            ['slug' => 'pusat-jantung-terpadu'],
            [
                'title' => 'Pusat Jantung Terpadu',
                'short_description' => 'Layanan kardiologi komprehensif dengan teknologi terkini dan dokter spesialis berpengalaman.',
                'full_content' => '<p>Pusat Jantung Terpadu kami menyediakan pelayanan komprehensif mulai dari diagnosis, penanganan medis, hingga rehabilitasi jantung. Kami dilengkapi dengan peralatan mutakhir untuk memastikan penanganan yang cepat dan akurat untuk setiap pasien.</p>',
                'technology_used' => [
                    ['name' => 'Cath Lab', 'description' => 'Fasilitas kateterisasi jantung berteknologi tinggi untuk intervensi koroner.'],
                    ['name' => 'Ekokardiografi 4D', 'description' => 'Pencitraan jantung 4 dimensi untuk diagnosis presisi.'],
                ],
                'show_slider' => true,
                'slider_images' => []
            ]
        );

        \App\Models\CenterOfExcellence::updateOrCreate(
            ['slug' => 'pusat-onkologi-kanker'],
            [
                'title' => 'Pusat Onkologi & Kanker',
                'short_description' => 'Penanganan kanker terpadu dengan pendekatan multidisiplin untuk hasil pengobatan optimal.',
                'full_content' => '<p>Layanan unggulan di bidang onkologi ini menggabungkan berbagai disiplin ilmu kedokteran (bedah, kemoterapi, radioterapi) guna memberikan perawatan komprehensif kepada pejuang kanker. Kami memiliki fasilitas kemoterapi yang nyaman dan didukung oleh tenaga medis yang welas asih.</p>',
                'technology_used' => [
                    ['name' => 'Linear Accelerator (LINAC)', 'description' => 'Terapi radiasi presisi tinggi untuk meminimalkan dampak pada jaringan sehat.'],
                    ['name' => 'PET-CT Scan', 'description' => 'Deteksi dini dan evaluasi penyebaran sel kanker.'],
                ],
                'show_slider' => true,
                'slider_images' => []
            ]
        );
    }
}
