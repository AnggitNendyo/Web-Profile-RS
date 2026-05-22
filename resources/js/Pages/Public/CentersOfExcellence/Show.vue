<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    centerOfExcellence: Object,
});
</script>

<template>
    <Head :title="centerOfExcellence.data.title" />
    <PublicLayout>
        <div class="bg-gray-50 pb-16 min-h-screen">
            <!-- Hero Image -->
            <div class="relative h-64 md:h-96 bg-gray-900">
                <img class="absolute inset-0 w-full h-full object-cover opacity-50" src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" :alt="centerOfExcellence.data.title" />
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                <div class="absolute bottom-0 w-full">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
                        <!-- Breadcrumbs -->
                        <nav class="flex mb-4" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2">
                                <li><Link :href="route('home')" class="text-gray-300 hover:text-white text-sm">Beranda</Link></li>
                                <li class="text-gray-500">/</li>
                                <li><Link :href="route('coes.index')" class="text-gray-300 hover:text-white text-sm">Layanan Unggulan</Link></li>
                            </ol>
                        </nav>
                        <h1 class="text-3xl md:text-5xl font-extrabold text-white">{{ centerOfExcellence.data.title }}</h1>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tentang Layanan</h2>
                            <div class="prose max-w-none text-gray-600 space-y-4" v-html="centerOfExcellence.data.full_content.replace(/\n/g, '<br>')"></div>
                            
                            <div class="mt-10" v-if="centerOfExcellence.data.technology_used && centerOfExcellence.data.technology_used.length > 0">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Teknologi & Fasilitas Unggulan</h3>
                                <div class="space-y-4">
                                    <div v-for="(tech, index) in centerOfExcellence.data.technology_used" :key="index" class="bg-gray-50 rounded-lg p-5 border border-gray-100">
                                        <h4 class="font-bold text-primary mb-2">{{ tech.name }}</h4>
                                        <p class="text-sm text-gray-600">{{ tech.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-28">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pendaftaran</h3>
                            <p class="text-sm text-gray-600 mb-6">Untuk konsultasi dan pendaftaran pada {{ centerOfExcellence.data.title }}, silakan hubungi kami atau buat janji temu melalui portal pasien.</p>
                            
                            <ul class="space-y-4 mb-6">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-primary mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="text-sm text-gray-600">(021) 1500-123 Ext. 2</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-primary mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm text-gray-600">Senin - Sabtu: 08:00 - 20:00 WIB</span>
                                </li>
                            </ul>
                            
                            <Link :href="route('login')" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                Buat Janji Temu
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
