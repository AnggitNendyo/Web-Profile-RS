<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    packages: Object,
});

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};
</script>

<template>
    <Head title="Paket Kesehatan & Promo">
        <meta name="description" content="Temukan berbagai paket kesehatan, medical check-up, persalinan, dan promo terbaru dari RS Nusantara Medika." />
    </Head>
    <PublicLayout>
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-primary font-semibold tracking-wider uppercase text-sm mb-4 block">Penawaran Spesial</span>
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Paket Kesehatan & Promo</h1>
                    <p class="mt-4 text-xl text-gray-500">Pilih paket kesehatan yang sesuai dengan kebutuhan Anda dengan harga terbaik.</p>
                </div>

                <div v-if="packages.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="pkg in packages.data" :key="pkg.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group relative">
                        <!-- Discount Badge -->
                        <div v-if="pkg.promo_price && pkg.price" class="absolute top-4 right-4 z-10 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            HEMAT {{ Math.round((1 - pkg.promo_price / pkg.price) * 100) }}%
                        </div>
                        
                        <Link :href="route('packages.show', pkg.slug)" class="block h-56 bg-gray-100 overflow-hidden relative">
                            <img 
                                v-if="pkg.cover_image" 
                                :src="pkg.cover_image" 
                                :alt="pkg.title" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-200">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                        </Link>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-3">
                                <span v-if="pkg.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                    {{ pkg.category.name }}
                                </span>
                            </div>
                            
                            <Link :href="route('packages.show', pkg.slug)" class="block">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ pkg.title }}</h3>
                            </Link>
                            
                            <p class="text-gray-500 text-sm mb-6 line-clamp-2">{{ pkg.excerpt || pkg.content?.replace(/<[^>]*>/g, '').substring(0, 100) + '...' }}</p>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <div class="flex flex-col mb-4">
                                    <span v-if="pkg.price && pkg.promo_price" class="text-sm text-gray-400 line-through mb-1">
                                        {{ formatRupiah(pkg.price) }}
                                    </span>
                                    <span class="text-2xl font-bold text-gray-900">
                                        {{ formatRupiah(pkg.promo_price || pkg.price || 0) }}
                                    </span>
                                </div>
                                
                                <Link :href="route('packages.show', pkg.slug)" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-semibold rounded-lg text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                    Lihat Detail Paket
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada paket/promo</h3>
                    <p class="mt-2 text-gray-500">Saat ini belum ada paket kesehatan atau promo yang tersedia.</p>
                </div>

                <!-- Pagination -->
                <div v-if="packages.links" class="mt-16 flex justify-center">
                    <div class="flex flex-wrap justify-center gap-2">
                        <template v-for="(link, p) in packages.links" :key="p">
                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded bg-white" v-html="link.label" />
                            <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-primary focus:text-primary transition-colors" :class="link.active ? 'bg-primary text-white border-primary hover:bg-secondary' : 'bg-white text-gray-700 hover:bg-gray-50'" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
