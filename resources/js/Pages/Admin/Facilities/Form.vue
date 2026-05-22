<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    facility: {
        type: Object,
        default: () => ({
            name: '',
            category: '',
            description: '',
            features: [],
        })
    }
});

const isEditing = !!props.facility.id;
const featuresArray = Array.isArray(props.facility.features) 
    ? props.facility.features 
    : (typeof props.facility.features === 'string' && props.facility.features.trim() !== '' 
        ? JSON.parse(props.facility.features) 
        : []);

const form = useForm({
    name: props.facility.name || '',
    category: props.facility.category || '',
    description: props.facility.description || '',
    features: featuresArray,
});

const addFeature = () => {
    form.features.push({ name: '' });
};

const removeFeature = (index) => {
    form.features.splice(index, 1);
};

const submit = () => {
    if (isEditing) {
        form.put(route('admin.facilities.update', props.facility.id));
    } else {
        form.post(route('admin.facilities.store'));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Fasilitas' : 'Tambah Fasilitas'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ isEditing ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}
                </h2>
                <Link
                    :href="route('admin.facilities.index')"
                    class="text-gray-600 hover:text-gray-900"
                >
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Fasilitas</label>
                                    <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                                </div>
    
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori Fasilitas</label>
                                    <select id="category" v-model="form.category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        <option disabled value="">Pilih Kategori</option>
                                        <option value="Rawat Inap">Rawat Inap</option>
                                        <option value="Diagnostik & Imaging">Diagnostik & Imaging</option>
                                        <option value="Laboratorium">Laboratorium</option>
                                        <option value="Layanan Penunjang">Layanan Penunjang</option>
                                    </select>
                                    <div v-if="form.errors.category" class="text-red-500 text-xs mt-1">{{ form.errors.category }}</div>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Fasilitas</label>
                                <textarea id="description" v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Fitur Fasilitas</label>
                                    <button type="button" @click="addFeature" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Tambah Fitur
                                    </button>
                                </div>
                                
                                <div v-if="form.features.length === 0" class="text-sm text-gray-500 italic py-4 text-center border-2 border-dashed rounded-lg bg-gray-50">
                                    Belum ada fitur yang ditambahkan.
                                </div>

                                <div v-else class="space-y-3">
                                    <div v-for="(feat, index) in form.features" :key="index" class="flex items-center gap-3">
                                        <input type="text" v-model="feat.name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="Contoh: AC Individual">
                                        <button type="button" @click="removeFeature(index)" class="flex-shrink-0 text-red-500 hover:text-red-700 p-2 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.errors.features" class="text-red-500 text-xs mt-1">{{ form.errors.features }}</div>
                            </div>

                            <div class="flex justify-end gap-4">
                                <Link :href="route('admin.facilities.index')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase shadow-sm hover:bg-gray-50">
                                    Batal
                                </Link>
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-700 disabled:opacity-50">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
