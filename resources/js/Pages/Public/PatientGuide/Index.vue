<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Panduan Pasien">
        <meta name="description" content="Panduan rawat inap, jam besuk, tipe kamar, dan informasi penting bagi pasien RS Nusantara Medika." />
    </Head>
    <PublicLayout>
        <div class="bg-primary/5 py-16 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Panduan Pasien & Pengunjung</h1>
                    <p class="mt-4 text-xl text-gray-500">Informasi lengkap untuk memudahkan persiapan kunjungan dan perawatan Anda di RS Nusantara Medika.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                    <!-- Rawat Jalan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                        <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Panduan Rawat Jalan</h2>
                        <ul class="space-y-4 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Lakukan pendaftaran minimal 1 hari sebelum jadwal praktek dokter.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Bawa kartu identitas (KTP/SIM/Paspor) dan kartu asuransi yang berlaku.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Hadir 30 menit sebelum jadwal untuk proses administrasi.</span>
                            </li>
                        </ul>
                        <Link :href="route('login')" class="text-primary font-medium hover:text-secondary inline-flex items-center">
                            Daftar Rawat Jalan Online
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </Link>
                    </div>

                    <!-- Rawat Inap -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
                        <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Panduan Rawat Inap</h2>
                        <ul class="space-y-4 text-gray-600 mb-8">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Waktu besuk: 11:00 - 13:00 WIB dan 17:00 - 19:00 WIB.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Anak di bawah 12 tahun tidak diperkenankan memasuki area ruang perawatan.</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                <span>Maksimal pengunjung 2 orang secara bergantian untuk menjaga kenyamanan.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- FAQ or Rights -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Hak dan Kewajiban Pasien</h2>
                    <div class="prose prose-cyan max-w-none text-gray-600">
                        <h3>Hak Pasien</h3>
                        <ul>
                            <li>Memperoleh informasi mengenai tata tertib dan peraturan yang berlaku di Rumah Sakit.</li>
                            <li>Memperoleh layanan medis yang bermutu, aman, dan tanpa diskriminasi.</li>
                            <li>Mendapatkan privasi dan kerahasiaan penyakit yang diderita.</li>
                            <li>Memberikan persetujuan atau menolak tindakan medis setelah mendapat penjelasan.</li>
                        </ul>
                        <h3>Kewajiban Pasien</h3>
                        <ul>
                            <li>Mematuhi peraturan yang berlaku di Rumah Sakit.</li>
                            <li>Memberikan informasi yang jujur, lengkap, dan akurat tentang masalah kesehatannya.</li>
                            <li>Mematuhi rencana terapi yang direkomendasikan oleh Tenaga Medis.</li>
                            <li>Memenuhi kewajiban finansial atas layanan kesehatan yang diterima.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
