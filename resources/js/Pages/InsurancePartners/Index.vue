<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    partners: Array,
});
</script>

<template>
    <Head title="Mitra Asuransi & Perusahaan" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-indigo-900 via-blue-900 to-indigo-800 py-20 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-1/2 -right-1/4 w-[1000px] h-[1000px] rounded-full bg-blue-500/10 blur-[100px] mix-blend-overlay"></div>
                <div class="absolute -bottom-1/2 -left-1/4 w-[800px] h-[800px] rounded-full bg-indigo-500/10 blur-[80px] mix-blend-overlay"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvc3ZnPg==')] opacity-50"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-sm font-semibold tracking-wider mb-4 uppercase">Kerjasama & Layanan</span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">Mitra Asuransi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">& Perusahaan</span></h1>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                    Kami bekerja sama dengan berbagai penyedia asuransi kesehatan dan perusahaan untuk memberikan kemudahan akses layanan kesehatan yang terbaik bagi Anda.
                </p>
            </div>
            
            <!-- Bottom decorative edge -->
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
        </section>

        <!-- Main Content Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div v-if="partners.length === 0" class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-500 text-lg">Belum ada daftar mitra asuransi saat ini.</p>
                </div>
                
                <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 lg:gap-8">
                    <!-- Partner Card -->
                    <div 
                        v-for="partner in partners" 
                        :key="partner.id"
                        class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300 flex flex-col items-center justify-center text-center relative overflow-hidden h-64"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="h-28 w-full flex items-center justify-center mb-4 relative z-10">
                            <img v-if="partner.logo_path" :src="'/storage/' + partner.logo_path" :alt="partner.name" class="max-h-full max-w-full object-contain filter group-hover:scale-105 transition-transform duration-300" />
                            <div v-else class="h-20 w-20 rounded-full bg-blue-50 flex items-center justify-center text-blue-300 border border-blue-100">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        </div>
                        
                        <div class="relative z-10 w-full mt-auto">
                            <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors line-clamp-1 mb-1">{{ partner.name }}</h3>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase" 
                                :class="{
                                    'bg-green-100 text-green-700': partner.type.toLowerCase() === 'bpjs',
                                    'bg-blue-100 text-blue-700': partner.type.toLowerCase() !== 'bpjs'
                                }">
                                {{ partner.type }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Call to action -->
                <div class="mt-20 bg-blue-600 rounded-3xl p-8 md:p-12 text-center shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Ingin Menjadi Mitra Kami?</h2>
                        <p class="text-blue-100 mb-8 max-w-2xl mx-auto">Untuk informasi kerjasama perusahaan, asuransi, atau layanan korporasi lainnya, silakan hubungi tim marketing & kemitraan kami.</p>
                        <a href="mailto:info@rsnusantara.com" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-base font-bold rounded-xl text-blue-600 bg-white hover:bg-blue-50 hover:scale-105 transition-all shadow-lg">
                            Hubungi Marketing Kemitraan
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
