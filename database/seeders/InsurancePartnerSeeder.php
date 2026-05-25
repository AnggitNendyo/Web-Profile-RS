<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InsurancePartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = [
            [
                'name' => 'BPJS Kesehatan',
                'type' => 'BPJS',
                'description' => 'Badan Penyelenggara Jaminan Sosial Kesehatan.',
            ],
            [
                'name' => 'Mandiri Inhealth',
                'type' => 'Asuransi Perusahaan',
                'description' => 'Asuransi kesehatan kumpulan.',
            ],
            [
                'name' => 'Prudential',
                'type' => 'Asuransi Swasta',
                'description' => 'Asuransi kesehatan swasta terkemuka.',
            ],
            [
                'name' => 'Allianz',
                'type' => 'Asuransi Swasta',
                'description' => 'Perlindungan asuransi kesehatan menyeluruh.',
            ],
        ];

        foreach ($partners as $partner) {
            \App\Models\InsurancePartner::create($partner);
        }
    }
}
