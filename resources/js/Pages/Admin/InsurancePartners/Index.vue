<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SearchInput from '@/Components/SearchInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

defineProps({
    partners: Object,
    filters: Object,
});

const showModal = ref(false);
const selectedPartner = ref(null);
const previewMode = ref('desktop');

const openPreview = (partner) => {
    selectedPartner.value = partner;
    previewMode.value = 'desktop';
    showModal.value = true;
};

const closePreview = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedPartner.value = null;
    }, 200);
};
</script>

<template>
    <Head title="Manajemen Mitra Asuransi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manajemen Mitra Asuransi
                </h2>
                <Link
                    :href="route('admin.insurance-partners.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150 shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Mitra Asuransi
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <SearchInput :model-value="filters?.search" route-name="admin.insurance-partners.index" placeholder="Cari nama atau tipe asuransi..." />
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Logo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Asuransi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="partner in partners.data" :key="partner.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="h-12 w-24 bg-gray-50 rounded-lg flex items-center justify-center p-2 border">
                                                <img v-if="partner.logo_path" :src="'/storage/' + partner.logo_path" class="max-h-full max-w-full object-contain" />
                                                <span v-else class="text-xs text-gray-400">No Logo</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ partner.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200">
                                                {{ partner.type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="partner.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-200">
                                                Aktif
                                            </span>
                                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-200">
                                                Nonaktif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <button @click="openPreview(partner)" class="text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 p-2 rounded-lg transition-colors" title="Preview">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                                <Link :href="route('admin.insurance-partners.edit', partner.id)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </Link>
                                                <Link :href="route('admin.insurance-partners.destroy', partner.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus mitra ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="partners.data.length === 0">
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <p class="text-sm text-gray-500">Belum ada data mitra asuransi.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div v-if="partners.links" class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ partners.from }} - {{ partners.to }} dari {{ partners.total }} mitra
                            </div>
                            <div class="flex gap-1">
                                <Link v-for="(link, i) in partners.links" :key="i" 
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

        <Modal :show="showModal" @close="closePreview" maxWidth="2xl">
            <div v-if="selectedPartner" class="p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-4">
                    <h3 class="text-xl font-bold text-gray-900">Preview Mitra Asuransi</h3>
                    <button @click="closePreview" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-col items-center bg-gray-50 p-6 rounded-xl border border-gray-100">
                    <div class="h-24 w-48 bg-white rounded-xl flex items-center justify-center p-4 border border-gray-200 shadow-sm mb-6">
                        <img v-if="selectedPartner.logo_path" :src="'/storage/' + selectedPartner.logo_path" class="max-h-full max-w-full object-contain" />
                        <span v-else class="text-sm text-gray-400">No Logo</span>
                    </div>

                    <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ selectedPartner.name }}</h4>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mb-4">
                        {{ selectedPartner.type }}
                    </span>
                    <p class="text-gray-600 text-center max-w-md">
                        {{ selectedPartner.description || 'Tidak ada deskripsi.' }}
                    </p>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button @click="closePreview" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
