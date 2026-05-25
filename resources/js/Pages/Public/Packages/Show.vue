<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    package: Object,
    relatedPackages: Array,
});

const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};

const page = usePage();
const whatsappNumber = computed(() => {
    return page.props.global?.contact?.whatsapp || '6281122334455';
});

const bookingUrl = computed(() => {
    const text = encodeURIComponent(`Halo RS Nusantara Medika, saya tertarik dengan paket layanan: *${props.package.title}*. Mohon informasi cara pendaftarannya.`);
    return `https://wa.me/${whatsappNumber.value}?text=${text}`;
});
</script>

<template>
    <Head :title="package.meta_title || package.title">
        <meta name="description" :content="package.meta_description || package.excerpt || package.title" />
    </Head>
    <PublicLayout>
        <div class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <nav class="flex mb-8 text-sm" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('home')" class="text-gray-500 hover:text-primary transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                                Beranda
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <Link :href="route('packages.index')" class="text-gray-500 hover:text-primary ml-1 md:ml-2 transition-colors">Paket & Promo</Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="text-gray-900 ml-1 md:ml-2 font-medium truncate max-w-xs">{{ package.title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="relative h-80 sm:h-[400px]">
                                <img 
                                    v-if="package.cover_image" 
                                    :src="package.cover_image" 
                                    :alt="package.title" 
                                    class="w-full h-full object-cover" 
                                />
                                <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                </div>
                            </div>
                            
                            <div class="p-8">
                                <div class="flex items-center gap-3 mb-4">
                                    <span v-if="package.category" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                        {{ package.category.name }}
                                    </span>
                                </div>
                                
                                <h1 class="text-3xl font-extrabold text-gray-900 mb-6">{{ package.title }}</h1>
                                
                                <!-- Content -->
                                <div class="prose prose-lg prose-indigo max-w-none text-gray-600">
                                    <div v-html="package.content"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Pricing Card -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Harga</h3>
                            
                            <div class="bg-gray-50 rounded-xl p-6 mb-6 text-center">
                                <p v-if="package.price && package.promo_price" class="text-gray-500 line-through text-lg mb-1">
                                    {{ formatRupiah(package.price) }}
                                </p>
                                <p class="text-4xl font-extrabold text-primary">
                                    {{ formatRupiah(package.promo_price || package.price || 0) }}
                                </p>
                                <div v-if="package.price && package.promo_price" class="mt-3 inline-block bg-red-100 text-red-700 font-bold px-3 py-1 rounded-full text-sm">
                                    Hemat {{ Math.round((1 - package.promo_price / package.price) * 100) }}%
                                </div>
                            </div>
                            
                            <a :href="bookingUrl" target="_blank" class="w-full flex items-center justify-center px-6 py-4 border border-transparent rounded-xl shadow-sm text-base font-bold text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Pesan Sekarang via WhatsApp
                            </a>
                            <p class="mt-4 text-xs text-center text-gray-500">
                                Anda akan diarahkan ke WhatsApp Admin Pendaftaran kami. Layanan pelanggan beroperasi 24 Jam.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Related Packages -->
                <div v-if="relatedPackages.length > 0" class="mt-20 pt-10 border-t border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">Paket Menarik Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="rel in relatedPackages" :key="rel.id" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                            <Link :href="route('packages.show', rel.slug)" class="block h-48 bg-gray-100 overflow-hidden">
                                <img 
                                    v-if="rel.cover_image" 
                                    :src="rel.cover_image" 
                                    :alt="rel.title" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                </div>
                            </Link>
                            <div class="p-5">
                                <Link :href="route('packages.show', rel.slug)">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ rel.title }}</h3>
                                </Link>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-lg font-bold text-primary">
                                        {{ formatRupiah(rel.promo_price || rel.price || 0) }}
                                    </span>
                                    <Link :href="route('packages.show', rel.slug)" class="text-sm font-medium text-primary hover:text-secondary">
                                        Detail &rarr;
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
