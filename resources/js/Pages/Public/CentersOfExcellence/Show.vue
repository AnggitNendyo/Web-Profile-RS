<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    centerOfExcellence: Object,
    otherCoes: Object,
});

const defaultImage = "https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80";
const hasSlider = computed(() => props.centerOfExcellence.data.show_slider && 
                 props.centerOfExcellence.data.slider_images && 
                 props.centerOfExcellence.data.slider_images.length > 0);

const images = computed(() => hasSlider.value ? props.centerOfExcellence.data.slider_images.map(img => '/storage/' + img) : [defaultImage]);

const sliderContainer = ref(null);
const currentSlide = ref(0);
const slideDirection = ref('slide-left');
let autoPlayInterval = null;

const changeSlide = (idx, forceDirection = null) => {
    if (forceDirection) {
        slideDirection.value = forceDirection;
    } else if (idx > currentSlide.value) {
        slideDirection.value = 'slide-left'; // Move forward (new enters from right)
    } else if (idx < currentSlide.value) {
        slideDirection.value = 'slide-right'; // Move backward (new enters from left)
    }
    currentSlide.value = idx;
};

const startAutoPlay = () => {
    if (images.value.length > 1) {
        autoPlayInterval = setInterval(() => {
            scrollNext(true);
        }, 5000); // 5 seconds
    }
};

const stopAutoPlay = () => {
    if (autoPlayInterval) clearInterval(autoPlayInterval);
};

const scrollNext = (isAuto = false) => {
    if (!isAuto) stopAutoPlay();
    let nextIndex = currentSlide.value + 1;
    if (nextIndex >= images.value.length) nextIndex = 0;
    changeSlide(nextIndex, 'slide-left');
};

const scrollPrev = () => {
    stopAutoPlay();
    let prevIndex = currentSlide.value - 1;
    if (prevIndex < 0) prevIndex = images.value.length - 1;
    changeSlide(prevIndex, 'slide-right');
};

const scrollToSlide = (index) => {
    changeSlide(index);
};

const handleScroll = () => {
    if (sliderContainer.value) {
        const scrollLeft = sliderContainer.value.scrollLeft;
        const slideWidth = sliderContainer.value.clientWidth;
        currentSlide.value = Math.round(scrollLeft / slideWidth);
    }
};

onMounted(() => {
    startAutoPlay();
});

onUnmounted(() => {
    stopAutoPlay();
});
</script>

<template>
    <Head :title="centerOfExcellence.data.title" />
    <PublicLayout>
        <div class="bg-gray-50 pb-16 min-h-screen">
            <!-- Hero Image -->
            <div class="relative h-72 md:h-[32rem] bg-gray-900 overflow-hidden">
                <div class="w-full h-full relative">
                    <img class="absolute inset-0 w-full h-full object-cover opacity-60" :src="centerOfExcellence.data.banner_image ? '/storage/' + centerOfExcellence.data.banner_image : images[0]" :alt="centerOfExcellence.data.title" />
                </div>
                
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent pointer-events-none"></div>

                <div class="absolute bottom-0 w-full pointer-events-none">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-10 pointer-events-auto">
                        <!-- Breadcrumbs -->
                        <nav class="flex mb-4" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2">
                                <li><Link :href="route('home')" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Beranda</Link></li>
                                <li class="text-gray-500">/</li>
                                <li><Link :href="route('coes.index')" class="text-gray-300 hover:text-white text-sm font-medium transition-colors">Layanan Unggulan</Link></li>
                            </ol>
                        </nav>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight drop-shadow-md">{{ centerOfExcellence.data.title }}</h1>
                        <p class="mt-4 max-w-2xl text-lg md:text-xl text-gray-200 drop-shadow">{{ centerOfExcellence.data.short_description }}</p>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Tentang Layanan</h2>
                            <div class="prose prose-indigo prose-lg max-w-none text-gray-600 mb-8" v-html="centerOfExcellence.data.full_content"></div>
                            
                            <!-- Thumbnail Strip Slider Content -->
                            <div v-if="images.length > 1 && centerOfExcellence.data.show_slider" class="mb-12" @mouseenter="stopAutoPlay" @mouseleave="startAutoPlay">
                                <!-- Main Large Image -->
                                <div class="relative h-64 md:h-96 w-full rounded-2xl overflow-hidden shadow-sm border border-gray-100 group bg-gray-50 flex items-center justify-center">
                                    <!-- Directional slide transition -->
                                    <transition :name="slideDirection">
                                        <img :key="currentSlide" :src="images[currentSlide]" :alt="centerOfExcellence.data.title" class="absolute inset-0 w-full h-full object-contain" />
                                    </transition>
                                </div>
                                
                                <!-- Thumbnails Row -->
                                <div class="mt-4 flex gap-3 overflow-x-auto pb-2 hide-scrollbar snap-x">
                                    <button v-for="(img, idx) in images" :key="idx" 
                                        @click="changeSlide(idx); stopAutoPlay();"
                                        class="relative flex-shrink-0 w-24 h-16 md:w-32 md:h-20 rounded-xl overflow-hidden border-2 transition-all duration-300 snap-center"
                                        :class="currentSlide === idx ? 'border-indigo-600 shadow-md ring-2 ring-indigo-600/30 ring-offset-1' : 'border-transparent opacity-60 hover:opacity-100 hover:border-gray-300'">
                                        <img :src="img" :alt="'Thumbnail ' + (idx + 1)" class="w-full h-full object-cover" />
                                        <!-- Overlay for active state to make it look premium -->
                                        <div v-if="currentSlide !== idx" class="absolute inset-0 bg-black/10"></div>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-8" v-if="centerOfExcellence.data.technology_used && centerOfExcellence.data.technology_used.length > 0">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">Fasilitas & Teknologi Modern</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div v-for="(tech, index) in centerOfExcellence.data.technology_used" :key="index" class="bg-gray-50 hover:bg-indigo-50 rounded-2xl p-6 border border-gray-100 transition-colors duration-300 group">
                                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-500 mb-4 group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        </div>
                                        <h4 class="font-bold text-gray-900 mb-2">{{ tech.name }}</h4>
                                        <p class="text-sm text-gray-600 leading-relaxed">{{ tech.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-1 space-y-8">
                        <!-- Informasi Pendaftaran -->
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 sticky top-28">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Informasi Pendaftaran
                            </h3>
                            <p class="text-sm text-gray-600 mb-6 leading-relaxed">Untuk konsultasi dan pendaftaran pada {{ centerOfExcellence.data.title }}, silakan hubungi kami atau buat janji temu melalui portal pasien.</p>
                            
                            <ul class="space-y-4 mb-6">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-indigo-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    <span class="text-sm text-gray-700 font-medium">(021) 1500-123 Ext. 2</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-indigo-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-sm text-gray-700">Senin - Sabtu: 08:00 - 20:00 WIB</span>
                                </li>
                            </ul>
                            
                            <div class="space-y-3 hidden md:block">
                                <Link :href="route('login')" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                    Buat Janji Temu
                                </Link>
                                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="w-full flex justify-center items-center py-2.5 px-4 border border-green-600 rounded-lg shadow-sm text-sm font-semibold text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                    Hubungi WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horizontal Layanan Lainnya Section -->
            <div v-if="otherCoes.data && otherCoes.data.length > 0" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <div class="flex items-center justify-between mb-8 border-b pb-4">
                        <h3 class="text-2xl font-bold text-gray-900">Jelajahi Layanan Unggulan Lainnya</h3>
                        <Link :href="route('coes.index')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua &rarr;</Link>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <Link v-for="otherCoe in otherCoes.data" :key="otherCoe.id" :href="route('coes.show', otherCoe.slug)" class="group flex flex-col">
                            <div class="w-full h-40 rounded-xl overflow-hidden bg-gray-100 mb-4 shadow-sm">
                                <img v-if="otherCoe.slider_images && otherCoe.slider_images.length > 0" :src="'/storage/' + otherCoe.slider_images[0]" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                            </div>
                            <h4 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-2">{{ otherCoe.title }}</h4>
                            <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">{{ otherCoe.short_description }}</p>
                        </Link>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Sticky Bottom CTA -->
            <div class="md:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-50">
                <div class="flex gap-3">
                    <Link :href="route('login')" class="flex-1 flex justify-center items-center py-2.5 px-4 rounded-lg text-sm font-semibold text-white bg-indigo-600 active:bg-indigo-700 transition-colors">
                        Buat Janji
                    </Link>
                    <a href="https://wa.me/6281234567890" class="flex-none flex justify-center items-center w-12 h-10 rounded-lg bg-green-50 text-green-600 active:bg-green-100 transition-colors" aria-label="Hubungi WhatsApp">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

<style scoped>
/* slide-left: entering image comes from right (+100%), leaving image goes to left (-100%) */
.slide-left-enter-active,
.slide-left-leave-active {
    transition: transform 0.5s ease-in-out;
}
.slide-left-enter-from {
    transform: translateX(100%);
}
.slide-left-leave-to {
    transform: translateX(-100%);
}

/* slide-right: entering image comes from left (-100%), leaving image goes to right (+100%) */
.slide-right-enter-active,
.slide-right-leave-active {
    transition: transform 0.5s ease-in-out;
}
.slide-right-enter-from {
    transform: translateX(-100%);
}
.slide-right-leave-to {
    transform: translateX(100%);
}
</style>
