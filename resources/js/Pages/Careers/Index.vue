<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    vacancies: Array,
});

const showModal = ref(false);
const selectedJob = ref(null);

const openJobDetails = (job) => {
    selectedJob.value = job;
    showModal.value = true;
};

const closeJobDetails = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedJob.value = null;
    }, 200);
};

const formatDate = (dateString) => {
    if (!dateString) return 'Tidak ada batas waktu';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
};
</script>

<template>
    <Head title="Karir & Lowongan Kerja" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-800 py-20 overflow-hidden">
            <!-- Background Elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 right-0 w-full h-full bg-[url('https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')] bg-cover bg-center opacity-10"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 to-transparent"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center pt-8">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-sm font-semibold tracking-wider mb-4 uppercase">Bergabung Bersama Kami</span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6">Karir <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">& Lowongan Kerja</span></h1>
                <p class="text-lg text-blue-100 max-w-2xl mx-auto leading-relaxed">
                    Kami selalu mencari individu-individu berbakat dan berdedikasi tinggi untuk bergabung dan berkembang bersama memajukan pelayanan kesehatan Indonesia.
                </p>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent"></div>
        </section>

        <!-- Main Content Section -->
        <section class="py-16 bg-gray-50 min-h-[50vh]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div v-if="vacancies.length === 0" class="text-center py-24 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="mx-auto h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Lowongan Tersedia</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Saat ini kami belum membuka lowongan baru. Silakan kunjungi kembali halaman ini secara berkala untuk update informasi karir selanjutnya.</p>
                </div>
                
                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Job Card -->
                    <div 
                        v-for="job in vacancies" 
                        :key="job.id"
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:border-blue-100 transition-all duration-300 flex flex-col cursor-pointer group"
                        @click="openJobDetails(job)"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-600 transition-colors duration-300">
                                <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ job.type }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">{{ job.title }}</h3>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-sm text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ job.department }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="text-sm text-gray-600 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ job.location }}
                            </span>
                        </div>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Buka s.d {{ formatDate(job.deadline) }}
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors translate-x-0 group-hover:translate-x-1 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Job Details Modal -->
        <Modal :show="showModal" @close="closeJobDetails" maxWidth="3xl">
            <div v-if="selectedJob" class="p-0 overflow-hidden flex flex-col h-[85vh] sm:h-auto sm:max-h-[85vh]">
                <!-- Modal Header (Sticky) -->
                <div class="p-6 bg-gradient-to-r from-slate-900 to-indigo-900 text-white shrink-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex gap-2 mb-3">
                                <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-xs font-semibold tracking-wider text-white border border-white/10 uppercase">{{ selectedJob.type }}</span>
                                <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded-lg text-xs font-semibold tracking-wider text-white border border-white/10 uppercase">{{ selectedJob.department }}</span>
                            </div>
                            <h3 class="text-2xl sm:text-3xl font-bold mb-2">{{ selectedJob.title }}</h3>
                            <div class="flex items-center text-indigo-100 text-sm">
                                <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ selectedJob.location }}
                            </div>
                        </div>
                        <button @click="closeJobDetails" class="text-white/60 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body (Scrollable) -->
                <div class="p-6 overflow-y-auto flex-1 bg-white">
                    <div class="flex items-center p-4 mb-8 bg-amber-50 border border-amber-100 rounded-xl text-amber-800">
                        <svg class="w-6 h-6 mr-3 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="font-bold text-sm uppercase tracking-wider opacity-80 mb-0.5">Batas Akhir Lamaran</p>
                            <p class="font-semibold">{{ formatDate(selectedJob.deadline) }}</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Deskripsi Pekerjaan</h4>
                            <div class="prose prose-blue max-w-none text-gray-600 prose-p:leading-relaxed" v-html="selectedJob.description"></div>
                        </div>

                        <div>
                            <h4 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Persyaratan</h4>
                            <div class="prose prose-blue max-w-none text-gray-600" v-html="selectedJob.requirements"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Footer (Sticky) -->
                <div class="p-6 border-t bg-gray-50 shrink-0 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500 hidden sm:block">
                        Apakah Anda memenuhi kualifikasi ini?
                    </div>
                    
                    <div class="w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                        <button @click="closeJobDetails" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors w-full sm:w-auto">
                            Tutup
                        </button>
                        
                        <!-- Apply Buttons -->
                        <a v-if="selectedJob.apply_link" :href="selectedJob.apply_link" target="_blank" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 hover:shadow-lg transition-all text-center flex items-center justify-center gap-2 w-full sm:w-auto">
                            Lamar Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                        <a v-else-if="selectedJob.apply_email" :href="'mailto:' + selectedJob.apply_email + '?subject=Lamaran Pekerjaan: ' + selectedJob.title" class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 hover:shadow-lg transition-all text-center flex items-center justify-center gap-2 w-full sm:w-auto">
                            Kirim CV via Email
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </a>
                        <button v-else disabled class="px-8 py-2.5 bg-gray-300 text-gray-500 rounded-xl font-bold cursor-not-allowed w-full sm:w-auto">
                            Lamaran Ditutup
                        </button>
                    </div>
                </div>
            </div>
        </Modal>
    </PublicLayout>
</template>
