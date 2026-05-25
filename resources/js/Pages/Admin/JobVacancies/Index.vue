<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SearchInput from '@/Components/SearchInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

defineProps({
    vacancies: Object,
    filters: Object,
});

const showModal = ref(false);
const selectedVacancy = ref(null);
const previewMode = ref('desktop');

const openPreview = (vacancy) => {
    selectedVacancy.value = vacancy;
    previewMode.value = 'desktop';
    showModal.value = true;
};

const closePreview = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedVacancy.value = null;
    }, 200);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric', month: 'long', day: 'numeric'
    });
};
</script>

<template>
    <Head title="Manajemen Karir & Lowongan" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Manajemen Karir & Lowongan Kerja
                </h2>
                <Link
                    :href="route('admin.job-vacancies.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition ease-in-out duration-150 shadow-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Lowongan
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <div class="p-6 text-gray-900">
                        <div class="mb-6">
                            <SearchInput :model-value="filters?.search" route-name="admin.job-vacancies.index" placeholder="Cari posisi atau departemen..." />
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Posisi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departemen</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Pekerjaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="vacancy in vacancies.data" :key="vacancy.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ vacancy.title }}</div>
                                            <div class="text-xs text-gray-500">{{ vacancy.location }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200">
                                                {{ vacancy.department }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ vacancy.type }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ formatDate(vacancy.deadline) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span v-if="vacancy.is_active" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-200">
                                                Buka
                                            </span>
                                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-200">
                                                Tutup
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-2">
                                                <button @click="openPreview(vacancy)" class="text-teal-600 hover:text-teal-900 bg-teal-50 hover:bg-teal-100 p-2 rounded-lg transition-colors" title="Preview">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                </button>
                                                <Link :href="route('admin.job-vacancies.edit', vacancy.id)" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </Link>
                                                <Link :href="route('admin.job-vacancies.destroy', vacancy.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Yakin ingin menghapus lowongan ini?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr v-if="vacancies.data.length === 0">
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <p class="text-sm text-gray-500">Belum ada data lowongan kerja.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div v-if="vacancies.links" class="mt-6 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ vacancies.from }} - {{ vacancies.to }} dari {{ vacancies.total }} data
                            </div>
                            <div class="flex gap-1">
                                <Link v-for="(link, i) in vacancies.links" :key="i" 
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
            <div v-if="selectedVacancy" class="p-6 flex flex-col h-[90vh]">
                <div class="flex justify-between items-center mb-4 pb-4 shrink-0 border-b">
                    <h3 class="text-xl font-bold text-gray-900">Preview Lowongan Pekerjaan</h3>
                    
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
                    <div class="transition-all duration-300 w-full" :class="previewMode === 'mobile' ? 'max-w-[375px] bg-white shadow-2xl rounded-[2.5rem] border-[12px] border-gray-900 overflow-hidden relative min-h-[667px]' : 'bg-white shadow-sm rounded-xl max-w-3xl'">
                        <!-- Mobile Notch -->
                        <div v-if="previewMode === 'mobile'" class="absolute top-0 inset-x-0 h-6 bg-gray-900 rounded-b-3xl w-40 mx-auto z-10 flex justify-center items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-gray-700"></div>
                            <div class="w-12 h-1.5 rounded-full bg-gray-700"></div>
                        </div>

                        <div class="p-6" :class="{'pt-10': previewMode === 'mobile'}">
                            <div class="space-y-6">
                                <div>
                                    <h4 class="font-bold text-gray-900" :class="previewMode === 'mobile' ? 'text-2xl' : 'text-3xl'">{{ selectedVacancy.title }}</h4>
                                    
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                            {{ selectedVacancy.department }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-700 border border-gray-200">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ selectedVacancy.location }}
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ selectedVacancy.type }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg text-sm border border-red-100 flex items-center">
                                        <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>Batas Lamaran: <strong>{{ formatDate(selectedVacancy.deadline) }}</strong></span>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <h5 class="font-bold text-gray-900 mb-2">Deskripsi Pekerjaan</h5>
                                    <div class="prose prose-sm max-w-none text-gray-600 prose-p:leading-relaxed" v-html="selectedVacancy.description"></div>
                                </div>

                                <div class="border-t pt-4">
                                    <h5 class="font-bold text-gray-900 mb-2">Kualifikasi / Persyaratan</h5>
                                    <div class="prose prose-sm max-w-none text-gray-600 prose-li:my-0" v-html="selectedVacancy.requirements"></div>
                                </div>
                                
                                <div class="border-t pt-6 pb-2">
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                        <h5 class="font-bold text-gray-900 mb-3 text-center">Cara Melamar</h5>
                                        <div class="flex flex-col gap-3">
                                            <a v-if="selectedVacancy.apply_link" :href="selectedVacancy.apply_link" target="_blank" class="w-full flex justify-center items-center px-4 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                                                Isi Form Lamaran
                                            </a>
                                            <a v-if="selectedVacancy.apply_email" :href="'mailto:' + selectedVacancy.apply_email" class="w-full flex justify-center items-center px-4 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                                                Kirim via Email
                                            </a>
                                            <p v-if="!selectedVacancy.apply_link && !selectedVacancy.apply_email" class="text-sm text-center text-gray-500">
                                                Informasi lamaran tidak tersedia.
                                            </p>
                                        </div>
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
