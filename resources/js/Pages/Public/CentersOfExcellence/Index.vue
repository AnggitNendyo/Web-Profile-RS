<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    centersOfExcellence: Object,
});
</script>

<template>
    <Head title="Layanan Unggulan">
        <meta name="description" content="Pusat layanan unggulan RS Nusantara Medika: Jantung, Kanker, Saraf, Ortopedi, dan Mata dengan teknologi medis terdepan." />
    </Head>
    <PublicLayout>
        <div class="bg-primary/5 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Layanan Unggulan</h1>
                    <p class="mt-4 text-xl text-gray-500">Pusat layanan spesialistik terpadu dengan fasilitas berteknologi tinggi dan tim medis multidisiplin.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="coe in centersOfExcellence.data" :key="coe.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
                        <div class="h-48 bg-gray-200 relative overflow-hidden group">
                            <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" :alt="coe.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <h3 class="absolute bottom-4 left-4 right-4 text-xl font-bold text-white">{{ coe.title }}</h3>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <p class="text-gray-600 mb-6 flex-grow">{{ coe.short_description }}</p>
                            <Link :href="route('coes.show', coe.slug)" class="inline-flex items-center text-primary font-medium hover:text-secondary group">
                                Selengkapnya
                                <svg class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
