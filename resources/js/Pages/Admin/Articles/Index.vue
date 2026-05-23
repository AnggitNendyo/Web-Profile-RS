<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SearchInput from '@/Components/SearchInput.vue';

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
</script>

<template>
    <Head title="Manajemen Artikel" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manajemen Artikel
                </h2>
                <Link
                    :href="route('admin.articles.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Artikel
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <div class="p-6 text-gray-900">
                        <!-- Search Bar -->
                        <div class="mb-6">
                            <SearchInput :model-value="filters?.search" route-name="admin.articles.index" placeholder="Cari judul atau isi artikel..." />
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
                                                <Link :href="route('admin.articles.edit', article.id)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit">
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
    </AuthenticatedLayout>
</template>
