<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = \App\Models\FacilityCategory::all()->keyBy('slug');

        if ($categories->isEmpty()) {
            $this->call(MasterDataSeeder::class);
            $categories = \App\Models\FacilityCategory::all()->keyBy('slug');
        }

        $facilities = [
            [
                'name' => 'Kamar Rawat Inap VIP',
                'category' => 'Rawat Inap',
                'facility_category_id' => $categories['rawat-inap']->id ?? null,
                'description' => 'Kamar perawatan kelas VIP dengan fasilitas setara hotel berbintang. Dilengkapi dengan tempat tidur elektrik, sofa bed untuk penunggu, TV pintar, kulkas mini, dan kamar mandi dalam dengan air panas.',
                'price_estimation' => 'Mulai dari Rp 1.500.000 / malam',
                'bed_capacity' => 1,
                'features' => ['Tempat Tidur Elektrik', 'Sofa Bed', 'Smart TV 43 Inch', 'AC Central', 'Kulkas Mini', 'Kamar Mandi Dalam', 'Ners Call System'],
            ],
            [
                'name' => 'Kamar Rawat Inap Kelas 1',
                'category' => 'Rawat Inap',
                'facility_category_id' => $categories['rawat-inap']->id ?? null,
                'description' => 'Kamar perawatan Kelas 1 yang nyaman untuk proses penyembuhan pasien dengan kapasitas 2 tempat tidur per kamar.',
                'price_estimation' => 'Mulai dari Rp 800.000 / malam',
                'bed_capacity' => 2,
                'features' => ['Tempat Tidur Manual/Elektrik', 'Kursi Penunggu', 'TV Bersama', 'AC', 'Kamar Mandi Dalam'],
            ],
            [
                'name' => 'Poliklinik Eksekutif',
                'category' => 'Rawat Jalan',
                'facility_category_id' => $categories['rawat-jalan']->id ?? null,
                'description' => 'Layanan rawat jalan terpadu dengan sistem one-stop service. Pasien tidak perlu berpindah tempat untuk pendaftaran, kasir, dan pengambilan obat.',
                'price_estimation' => null,
                'bed_capacity' => null,
                'features' => ['Ruang Tunggu Nyaman', 'Snack & Minuman Gratis', 'One-Stop Service', 'Konsultasi Privat'],
            ],
            [
                'name' => 'Laboratorium Patologi Klinik',
                'category' => 'Laboratorium',
                'facility_category_id' => $categories['laboratorium']->id ?? null,
                'description' => 'Laboratorium modern yang melayani berbagai pemeriksaan darah, urine, dan cairan tubuh lainnya dengan peralatan hematologi dan kimia klinik otomatis.',
                'price_estimation' => null,
                'bed_capacity' => null,
                'features' => ['Peralatan Otomatis Modern', 'Hasil Cepat (CITO)', 'Sistem Informasi Laboratorium Terintegrasi'],
            ],
            [
                'name' => 'MRI 1.5 Tesla',
                'category' => 'Diagnostik & Imaging',
                'facility_category_id' => $categories['diagnostik-imaging']->id ?? null,
                'description' => 'Fasilitas Magnetic Resonance Imaging (MRI) 1.5 Tesla untuk menghasilkan gambaran organ dalam tubuh yang sangat detail tanpa menggunakan radiasi sinar-X.',
                'price_estimation' => 'Mulai dari Rp 2.500.000',
                'bed_capacity' => null,
                'features' => ['Non-Radiasi', 'Resolusi Tinggi', 'Deteksi Dini Kelainan Saraf & Sendi'],
            ],
            [
                'name' => 'CT Scan 128 Slices',
                'category' => 'Diagnostik & Imaging',
                'facility_category_id' => $categories['diagnostik-imaging']->id ?? null,
                'description' => 'CT Scan berkecepatan tinggi dengan 128 irisan yang mampu menghasilkan gambar 3D dari organ tubuh dengan dosis radiasi yang lebih rendah.',
                'price_estimation' => 'Mulai dari Rp 1.800.000',
                'bed_capacity' => null,
                'features' => ['Proses Cepat', 'Radiasi Rendah', 'Gambaran 3D Detail', 'Pemeriksaan Jantung & Pembuluh Darah'],
            ],
            [
                'name' => 'Instalasi Gawat Darurat (IGD) 24 Jam',
                'category' => 'Layanan Darurat',
                'facility_category_id' => $categories['layanan-darurat']->id ?? null,
                'description' => 'Layanan penanganan kegawatdaruratan medis yang beroperasi 24 jam sehari, 7 hari seminggu dengan sistem triase modern dan tim medis bersertifikat kegawatdaruratan.',
                'price_estimation' => null,
                'bed_capacity' => 15,
                'features' => ['Dokter Jaga 24 Jam', 'Ambulans Rescue', 'Ruang Resusitasi', 'Ruang Isolasi', 'Terhubung Langsung ke ICU & Ruang Operasi'],
            ],
        ];

        foreach ($facilities as $facility) {
            \App\Models\Facility::create($facility);
        }
    }
}
