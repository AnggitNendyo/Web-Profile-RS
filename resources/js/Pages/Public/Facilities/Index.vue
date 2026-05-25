<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    facilitiesGrouped: Array,
});

// State untuk tab aktif
const activeTab = ref(props.facilitiesGrouped.length > 0 ? props.facilitiesGrouped[0].category : '');

// Mendapatkan fasilitas dari tab yang aktif
const activeFacilities = computed(() => {
    const group = props.facilitiesGrouped.find(g => g.category === activeTab.value);
    return group ? group.items : [];
});

// State untuk gambar aktif di slider (disimpan per facility id)
const activeImages = ref({});

const setActiveImage = (facilityId, index) => {
    activeImages.value[facilityId] = index;
};

const getActiveImage = (facility) => {
    const index = activeImages.value[facility.id] || 0;
    if (facility.photos && facility.photos.length > 0) {
        return facility.photos[index].url;
    }
    // Fallback placeholder
    return `https://ui-avatars.com/api/?name=${encodeURIComponent(facility.name)}&color=0891b2&background=cffafe&size=800`;
};
</script>

<template>
    <Head title="Fasilitas Medis & Rawat Inap">
        <meta name="description" content="Jelajahi fasilitas medis modern RS Nusantara Medika: kamar rawat inap premium, laboratorium canggih, dan peralatan diagnostik terkini." />
    </Head>
    <PublicLayout>
        <div class="bg-primary/5 py-16 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Tur Virtual & Fasilitas</h1>
                    <p class="mt-4 text-xl text-gray-500">
                        Jelajahi kenyamanan kamar rawat inap kami dan kecanggihan fasilitas medis RS Nusantara Medika.
                    </p>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex flex-wrap justify-center gap-2 mb-12 border-b border-gray-200 pb-1">
                    <button 
                        v-for="group in facilitiesGrouped" 
                        :key="group.category"
                        @click="activeTab = group.category"
                        class="px-6 py-3 text-sm font-semibold rounded-t-lg transition-colors border-b-2"
                        :class="activeTab === group.category 
                            ? 'text-primary border-primary bg-white' 
                            : 'text-gray-500 border-transparent hover:text-gray-700 hover:bg-white/50'"
                    >
                        {{ group.category }}
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" 
                            :class="activeTab === group.category ? 'bg-primary/10 text-primary' : 'bg-gray-100 text-gray-600'">
                            {{ group.items.length }}
                        </span>
                    </button>
                </div>

                <!-- Tab Content -->
                <div v-if="activeFacilities.length > 0" class="space-y-12">
                    <div v-for="facility in activeFacilities" :key="facility.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col lg:flex-row hover:shadow-lg transition-shadow duration-300">
                        
                        <!-- Left: Slider/Gallery -->
                        <div class="lg:w-5/12 bg-gray-50 flex flex-col border-b lg:border-b-0 lg:border-r border-gray-100">
                            <!-- Main Image -->
                            <div class="relative w-full aspect-video lg:aspect-square bg-gray-100 overflow-hidden">
                                <img :src="getActiveImage(facility)" :alt="facility.name" class="w-full h-full object-cover transition-all duration-500" />
                                
                                <!-- Category Badge Overlay -->
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/90 text-primary shadow-sm backdrop-blur-sm">
                                        {{ facility.category }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Thumbnails -->
                            <div v-if="facility.photos && facility.photos.length > 1" class="flex gap-2 p-4 overflow-x-auto snap-x hide-scrollbar bg-white">
                                <button 
                                    v-for="(photo, idx) in facility.photos" 
                                    :key="photo.id"
                                    @click="setActiveImage(facility.id, idx)"
                                    class="relative shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-all snap-start"
                                    :class="(activeImages[facility.id] || 0) === idx ? 'border-primary ring-2 ring-primary/20' : 'border-transparent opacity-70 hover:opacity-100'"
                                >
                                    <img :src="photo.url" class="w-full h-full object-cover" />
                                </button>
                            </div>
                        </div>

                        <!-- Right: Details -->
                        <div class="lg:w-7/12 p-8 lg:p-10 flex flex-col">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ facility.name }}</h2>
                            <p class="text-lg text-gray-600 mb-8 leading-relaxed">{{ facility.description }}</p>

                            <!-- Highlighted Specs (Price & Capacity) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8" v-if="facility.price_estimation || facility.bed_capacity">
                                <div v-if="facility.price_estimation" class="bg-cyan-50 rounded-xl p-4 flex items-center border border-cyan-100">
                                    <div class="bg-white p-2 rounded-lg shadow-sm text-cyan-600 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-cyan-800 font-semibold uppercase tracking-wider mb-0.5">Estimasi Biaya</p>
                                        <p class="text-lg font-bold text-gray-900">{{ facility.price_estimation }}</p>
                                    </div>
                                </div>
                                
                                <div v-if="facility.bed_capacity" class="bg-blue-50 rounded-xl p-4 flex items-center border border-blue-100">
                                    <div class="bg-white p-2 rounded-lg shadow-sm text-blue-600 mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-800 font-semibold uppercase tracking-wider mb-0.5">Kapasitas</p>
                                        <p class="text-lg font-bold text-gray-900">{{ facility.bed_capacity }} Tempat Tidur</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Features List -->
                            <div v-if="facility.features && facility.features.length > 0" class="mt-auto">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b pb-2">Fasilitas yang Didapat</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6">
                                    <div v-for="(feature, idx) in facility.features" :key="idx" class="flex items-start">
                                        <div class="flex-shrink-0 h-5 w-5 rounded-full bg-green-100 flex items-center justify-center mr-3 mt-0.5">
                                            <svg class="h-3.5 w-3.5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        </div>
                                        <span class="text-gray-700 text-sm font-medium">{{ feature.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada fasilitas</h3>
                    <p class="mt-1 text-gray-500">Belum ada data fasilitas untuk kategori ini.</p>
                </div>

            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
