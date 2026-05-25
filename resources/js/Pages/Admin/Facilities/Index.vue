<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SearchInput from '@/Components/SearchInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

defineProps({
    facilities: Object,
    filters: Object,
});

const showModal = ref(false);
const selectedFacility = ref(null);
const previewMode = ref('desktop');

const openPreview = (facility) => {
    selectedFacility.value = facility;
    previewMode.value = 'desktop';
    showModal.value = true;
};

const closePreview = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedFacility.value = null;
    }, 200);
};
</script>

<template>
    <Head title="Manajemen Fasilitas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manajemen Fasilitas
                </h2>
                <Link
                    :href="route('admin.facilities.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Fasilitas
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <!-- Search Bar -->
                        <div class="mb-4">
                            <SearchInput :model-value="filters?.search" route-name="admin.facilities.index" placeholder="Cari nama, kategori, atau deskripsi fasilitas..." />
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Fasilitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fitur (Jumlah)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="facility in facilities.data" :key="facility.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ facility.name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ facility.facilityCategory?.name || 'Tidak ada kategori' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ Array.isArray(facility.features) ? facility.features.length : 0 }} Fitur
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-2">
                                        <button @click="openPreview(facility)" class="text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 p-2 rounded-md transition-colors" title="Preview">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                        <Link :href="route('admin.facilities.edit', facility.id)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </Link>
                                        <Link :href="route('admin.facilities.destroy', facility.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="facilities.data.length === 0">
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Belum ada data fasilitas.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div v-if="facilities.links" class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ facilities.from }} - {{ facilities.to }} dari {{ facilities.total }} fasilitas
                            </div>
                            <div class="flex gap-1">
                                <Link v-for="(link, i) in facilities.links" :key="i" 
                                    :href="link.url || '#'" 
                                    class="px-3 py-1 border rounded-md"
                                    :class="link.active ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white hover:bg-gray-50 text-gray-700'"
                                    v-html="link.label">
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showModal" @close="closePreview" maxWidth="4xl">
            <div v-if="selectedFacility" class="p-6 flex flex-col h-[90vh]">
                <div class="flex justify-between items-center mb-4 pb-4 shrink-0">
                    <h3 class="text-xl font-bold text-gray-900">Preview Fasilitas</h3>
                    
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
                            <div class="space-y-4">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mb-2">
                                        {{ selectedFacility.category?.name || 'Tidak ada kategori' }}
                                    </span>
                                    <h4 class="font-bold text-gray-900" :class="previewMode === 'mobile' ? 'text-xl' : 'text-2xl'">{{ selectedFacility.name }}</h4>
                                    <p class="mt-2 text-gray-600" :class="previewMode === 'mobile' ? 'text-sm' : 'text-base'">{{ selectedFacility.description }}</p>
                                </div>

                                <div class="grid gap-4 bg-blue-50/50 p-4 rounded-xl border border-blue-100" :class="previewMode === 'mobile' ? 'grid-cols-1' : 'grid-cols-2'">
                                    <div>
                                        <p class="text-xs font-medium text-blue-800 uppercase tracking-wider">Estimasi Harga</p>
                                        <p class="mt-1 font-semibold text-gray-900" :class="previewMode === 'mobile' ? 'text-base' : 'text-lg'">{{ selectedFacility.price_estimation || '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-blue-800 uppercase tracking-wider">Kapasitas (Bed)</p>
                                        <p class="mt-1 font-semibold text-gray-900" :class="previewMode === 'mobile' ? 'text-base' : 'text-lg'">{{ selectedFacility.bed_capacity || '-' }}</p>
                                    </div>
                                </div>

                                <div v-if="selectedFacility.features && selectedFacility.features.length > 0">
                                    <p class="font-semibold text-gray-900 mb-3">Fitur & Layanan</p>
                                    <ul class="grid gap-3" :class="previewMode === 'mobile' ? 'grid-cols-1' : 'grid-cols-2'">
                                        <li v-for="(feat, idx) in selectedFacility.features" :key="idx" class="flex items-start text-sm text-gray-600 bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                            <svg class="w-5 h-5 mr-3 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="leading-tight pt-0.5">{{ typeof feat === 'object' ? feat.name : feat }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <div v-if="selectedFacility.photos && selectedFacility.photos.length > 0">
                                    <p class="font-semibold text-gray-900 mb-3">Galeri Foto</p>
                                    <div class="grid gap-2" :class="previewMode === 'mobile' ? 'grid-cols-2' : 'grid-cols-3'">
                                        <img v-for="photo in selectedFacility.photos" :key="photo.id" :src="photo.url" class="w-full h-32 object-cover rounded-xl border border-gray-200 shadow-sm hover:opacity-90 transition-opacity" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-end shrink-0 pt-4 border-t">
                    <button @click="closePreview" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md text-sm font-medium hover:bg-gray-200">
                        Tutup
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
