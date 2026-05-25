<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        
        $kategoriBerita = ArticleCategory::where('slug', 'berita-rs')->first();
        $kategoriEdukasi = ArticleCategory::where('slug', 'edukasi-kesehatan')->first();
        $kategoriPromo = ArticleCategory::where('slug', 'promo-paket')->first();

        // 1. Data Artikel / Berita
        $articles = [
            [
                'title' => 'Pentingnya Cek Kesehatan Rutin di Usia 30-an',
                'category_id' => $kategoriEdukasi?->id,
                'content' => '<p>Pemeriksaan kesehatan rutin atau <i>medical check-up</i> (MCU) merupakan langkah pencegahan yang sangat penting...</p><p>Melalui deteksi dini, kita dapat mengantisipasi berbagai risiko penyakit degeneratif.</p>',
                'published_at' => now()->subDays(2),
                'is_promo' => false,
            ],
            [
                'title' => 'RS Nusantara Medika Resmikan Gedung Pusat Jantung Baru',
                'category_id' => $kategoriBerita?->id,
                'content' => '<p>Pada hari ini, RS Nusantara Medika meresmikan fasilitas Pusat Jantung Terpadu untuk meningkatkan layanan kardiovaskular bagi masyarakat.</p>',
                'published_at' => now()->subDays(5),
                'is_promo' => false,
            ],
            [
                'title' => 'Tips Menjaga Imunitas Tubuh di Musim Hujan',
                'category_id' => $kategoriEdukasi?->id,
                'content' => '<p>Musim hujan seringkali diikuti dengan peningkatan risiko penyakit seperti flu, batuk, dan demam berdarah...</p>',
                'published_at' => now()->subDays(10),
                'is_promo' => false,
            ],
            [
                'title' => 'Mengenal Gejala Awal Stroke dan Penanganannya',
                'category_id' => $kategoriEdukasi?->id,
                'content' => '<p>Stroke adalah kondisi gawat darurat medis yang memerlukan penanganan secepat mungkin. Metode FAST (Face, Arms, Speech, Time) sangat penting untuk diketahui.</p>',
                'published_at' => now()->subDays(15),
                'is_promo' => false,
            ],
            [
                'title' => 'Penghargaan RS Terbaik Nasional 2026',
                'category_id' => $kategoriBerita?->id,
                'content' => '<p>RS Nusantara Medika berhasil meraih penghargaan sebagai Rumah Sakit Terbaik tingkat Nasional 2026 berkat inovasi layanan digitalnya.</p>',
                'published_at' => now()->subDays(20),
                'is_promo' => false,
            ],
        ];

        foreach ($articles as $item) {
            Article::create(array_merge($item, [
                'slug' => Str::slug($item['title']),
                'author_id' => $admin->id,
            ]));
        }

        // 2. Data Promo / Paket Kesehatan
        $promos = [
            [
                'title' => 'Promo MCU Dasar Spesial Kemerdekaan',
                'category_id' => $kategoriPromo?->id,
                'content' => '<ul><li>Pemeriksaan Darah Lengkap</li><li>Rontgen Thorax</li><li>Konsultasi Dokter Umum</li><li>Pemeriksaan Gula Darah</li></ul><p>Berlaku hingga akhir bulan ini.</p>',
                'published_at' => now()->subDays(1),
                'is_promo' => true,
                'price' => 750000,
                'promo_price' => 500000,
            ],
            [
                'title' => 'Paket Persalinan Normal VIP',
                'category_id' => $kategoriPromo?->id,
                'content' => '<p>Menyambut kelahiran sang buah hati dengan fasilitas premium dan pelayanan terbaik dari tenaga ahli kebidanan kami.</p><ul><li>Kamar VIP 3 Hari 2 Malam</li><li>Visitasi Dokter Spesialis Kandungan & Anak</li><li>Sesi Foto Bayi</li></ul>',
                'published_at' => now()->subDays(3),
                'is_promo' => true,
                'price' => 15000000,
                'promo_price' => 12500000,
            ],
            [
                'title' => 'Vaksinasi Influenza untuk Keluarga',
                'category_id' => $kategoriPromo?->id,
                'content' => '<p>Lindungi keluarga Anda dari virus influenza di musim hujan. Paket hemat untuk 4 orang.</p>',
                'published_at' => now()->subDays(7),
                'is_promo' => true,
                'price' => 1200000,
                'promo_price' => 999000,
            ],
            [
                'title' => 'Screening Jantung Sehat Lanjut Usia',
                'category_id' => $kategoriPromo?->id,
                'content' => '<p>Deteksi dini masalah kardiovaskular untuk usia di atas 50 tahun. Termasuk EKG, Echocardiography, dan Konsultasi Spesialis Jantung.</p>',
                'published_at' => now()->subDays(12),
                'is_promo' => true,
                'price' => 2500000,
                'promo_price' => null, // Tidak ada diskon, hanya paket
            ],
            [
                'title' => 'Paket Perawatan Gigi Bersih Cemerlang',
                'category_id' => $kategoriPromo?->id,
                'content' => '<p>Dapatkan senyum lebih percaya diri dengan paket Scaling dan Konsultasi Dokter Gigi Spesialis kami.</p>',
                'published_at' => now()->subDays(14),
                'is_promo' => true,
                'price' => 500000,
                'promo_price' => 299000,
            ],
        ];

        foreach ($promos as $item) {
            Article::create(array_merge($item, [
                'slug' => Str::slug($item['title']),
                'author_id' => $admin->id,
            ]));
        }
    }
}
