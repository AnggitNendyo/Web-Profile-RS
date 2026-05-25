<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SearchInput from '@/Components/SearchInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

defineProps({
    articles: Object,
    filters: Object,
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    if (isNaN(date)) return dateString;
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${day}-${month}-${year}, ${hours}.${minutes} WIB`;
};

const showModal = ref(false);
const selectedArticle = ref(null);
const previewMode = ref('desktop');

const openPreview = (article) => {
    selectedArticle.value = article;
    previewMode.value = 'desktop';
    showModal.value = true;
};

const closePreview = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedArticle.value = null;
    }, 200);
};
</script>

<template>
    <Head title="Manajemen Promo & Paket" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manajemen Promo & Paket
                </h2>
                <Link
                    :href="route('admin.promos.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Promo
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <div class="p-6 text-gray-900">
                        <!-- Search Bar -->
                        <div class="mb-6">
                            <SearchInput :model-value="filters?.search" route-name="admin.promos.index" placeholder="Cari judul atau isi promo..." />
                        </div>

                        <!-- Articles Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artikel</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="article in articles.data" :key="article.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <!-- Cover thumbnail -->
                                                <div class="flex-shrink-0 w-16 h-12 rounded-lg overflow-hidden bg-gray-100 shadow-sm">
                                                    <img 
                                                        v-if="article.cover_image" 
                                                        :src="article.cover_image" 
                                                        :alt="article.title"
                                                        class="w-full h-full object-cover"
                                                    />
                                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ article.title }}</div>
                                                    <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        {{ article.reading_time }} menit baca
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="article.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200">
                                                {{ article.category.name }}
                                            </span>
                                            <span v-else class="text-xs text-gray-400 italic">Tanpa Kategori</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600">{{ article.author?.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="article.published_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                                Public ({{ formatDate(article.published_at) }})
                                            </span>
                                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                                Draft
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <button @click="openPreview(article)" class="text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 p-2 rounded-lg transition-colors" title="Preview">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                                <Link :href="route('admin.promos.edit', article.id)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </Link>
                                                <Link :href="route('admin.articles.destroy', article.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="articles.data.length === 0">
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                            <p class="text-sm text-gray-500">Belum ada data artikel.</p>
                                            <Link :href="route('admin.articles.create')" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                Buat Artikel Pertama
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div v-if="articles.links" class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ articles.from }} - {{ articles.to }} dari {{ articles.total }} artikel
                            </div>
                            <div class="flex gap-1">
                                <Link v-for="(link, i) in articles.links" :key="i" 
                                    :href="link.url || '#'" 
                                    class="px-3 py-1.5 border rounded-lg text-sm transition-colors"
                                    :class="link.active ? 'bg-indigo-50 text-indigo-600 border-indigo-200 font-medium' : 'bg-white hover:bg-gray-50 text-gray-700 border-gray-200'"
                                    v-html="link.label">
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closePreview" maxWidth="4xl">
            <div v-if="selectedArticle" class="p-6 flex flex-col h-[90vh]">
                <div class="flex justify-between items-center mb-4 pb-4 shrink-0">
                    <h3 class="text-xl font-bold text-gray-900">Preview Promo & Paket</h3>
                    
                    <div class="flex items-center gap-1 bg-gray-100 p-1 rounded-lg">
                        <button @click="previewMode = 'desktop'" :class="{'bg-white shadow-sm text-gray-900': previewMode === 'desktop', 'text-gray-500 hover:text-gray-700': previewMode !== 'desktop'}" class="px-3 py-1.5 text-sm font-medium rounded-md flex items-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Desktop
                        </button>
                        <button @click="previewMode = 'mobile'" :class="{'bg-white shadow-sm text-gray-900': previewMode === 'mobile', 'text-gray-500 hover:text-gray-700': previewMode !== 'mobile'}" class="px-3 py-1.5 text-sm font-medium rounded-md flex items-center gap-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Mobile
                        </button>
                    </div>

                    <button @click="closePreview" class="text-gray-400 hover:text-gray-500 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto bg-gray-100/50 rounded-xl p-4 flex justify-center items-start">
                    <div class="transition-all duration-300 w-full" :class="previewMode === 'mobile' ? 'max-w-[375px] bg-white shadow-2xl rounded-[2.5rem] border-[12px] border-gray-900 overflow-hidden relative min-h-[667px]' : 'bg-white shadow-sm rounded-xl'">
                        <!-- Mobile Notch -->
                        <div v-if="previewMode === 'mobile'" class="absolute top-0 inset-x-0 h-6 bg-gray-900 rounded-b-3xl w-40 mx-auto z-10 flex justify-center items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-700"></div>
                            <div class="w-12 h-1.5 rounded-full bg-gray-700"></div>
                        </div>

                        <div class="p-6" :class="{'pt-10': previewMode === 'mobile'}">
                            <div class="prose max-w-none" :class="{'prose-sm': previewMode === 'mobile'}">
                                <h1 class="font-bold mb-4" :class="previewMode === 'mobile' ? 'text-2xl' : 'text-3xl'">{{ selectedArticle.title }}</h1>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-6 pb-4 border-b flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200">
                                        {{ selectedArticle.category?.name || 'Tanpa Kategori' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ selectedArticle.published_at ? formatDate(selectedArticle.published_at) : 'Draft' }}
                                    </span>
                                </div>

                                <div class="bg-amber-50 rounded-xl p-6 mb-8 border border-amber-100 shadow-sm" :class="{'p-4': previewMode === 'mobile'}">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div>
                                            <h3 class="text-amber-900 font-semibold mb-1">Informasi Harga</h3>
                                            <p class="text-amber-700 text-sm">Harga khusus untuk layanan ini</p>
                                        </div>
                                        <div :class="previewMode === 'mobile' ? 'text-left' : 'text-right'">
                                            <div v-if="selectedArticle.promo_price" class="flex flex-col" :class="previewMode === 'mobile' ? 'items-start' : 'items-end'">
                                                <span class="text-gray-400 line-through text-sm">Rp {{ new Intl.NumberFormat('id-ID').format(selectedArticle.price) }}</span>
                                                <span class="font-bold text-amber-600" :class="previewMode === 'mobile' ? 'text-2xl' : 'text-3xl'">Rp {{ new Intl.NumberFormat('id-ID').format(selectedArticle.promo_price) }}</span>
                                            </div>
                                            <div v-else-if="selectedArticle.price" class="font-bold text-amber-600" :class="previewMode === 'mobile' ? 'text-2xl' : 'text-3xl'">
                                                Rp {{ new Intl.NumberFormat('id-ID').format(selectedArticle.price) }}
                                            </div>
                                            <div v-else class="text-gray-500 italic">
                                                Harga tidak tersedia
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div v-if="selectedArticle.cover_image" class="mb-8">
                                    <img :src="selectedArticle.cover_image" :alt="selectedArticle.title" class="w-full h-auto object-cover rounded-xl shadow-md" :class="previewMode === 'mobile' ? 'max-h-[250px]' : 'max-h-[400px]'">
                                </div>

                                <div class="mt-8 bg-white p-6 rounded-xl border" :class="{'p-4': previewMode === 'mobile'}" v-html="selectedArticle.content"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end shrink-0 pt-4 border-t">
                    <button @click="closePreview" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
