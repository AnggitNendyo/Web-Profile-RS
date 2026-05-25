<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    facility: {
        type: Object,
        default: () => ({
            name: '',
            facility_category_id: '',
            description: '',
            features: [],
        })
    },
    categories: {
        type: Array,
        default: () => []
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
    facility_category_id: props.facility.facility_category_id || '',
    description: props.facility.description || '',
    price_estimation: props.facility.price_estimation || '',
    bed_capacity: props.facility.bed_capacity || '',
    features: featuresArray,
    photos: [],
    deleted_photos: [],
});

const isDragging = ref(false);
const photoPreviews = ref(props.facility.photos || []);

const handleFileSelect = (event) => {
    addFiles(event.target.files);
};

const handleDrop = (event) => {
    isDragging.value = false;
    addFiles(event.dataTransfer.files);
};

const addFiles = (files) => {
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        if (file.type.startsWith('image/')) {
            form.photos.push(file);
            photoPreviews.value.push({
                url: URL.createObjectURL(file),
                isNew: true,
                fileIndex: form.photos.length - 1
            });
        }
    }
};

const removePhoto = (index) => {
    const preview = photoPreviews.value[index];
    if (preview.id) {
        // Existing photo from server
        form.deleted_photos.push(preview.id);
    } else if (preview.isNew) {
        // New photo just uploaded
        // We need to keep the form.photos array indices aligned, so we just set it to null
        // or recreate the array. The simplest is to filter it out before submitting, but Inertia doesn't like Sparse arrays.
        // Let's just remove it properly and re-map indices.
        form.photos.splice(preview.fileIndex, 1);
        // Re-adjust fileIndex for remaining new photos
        photoPreviews.value.forEach(p => {
            if (p.isNew && p.fileIndex > preview.fileIndex) {
                p.fileIndex--;
            }
        });
    }
    photoPreviews.value.splice(index, 1);
};

const addFeature = () => {
    form.features.push({ name: '' });
};

const removeFeature = (index) => {
    form.features.splice(index, 1);
};

const submit = () => {
    if (isEditing) {
        form.transform((data) => ({
            ...data,
            _method: 'put',
        })).post(route('admin.facilities.update', props.facility.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.facilities.store'), {
            forceFormData: true,
        });
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
                                    <label for="facility_category_id" class="block text-sm font-medium text-gray-700">Kategori Fasilitas</label>
                                    <select id="facility_category_id" v-model="form.facility_category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                        <option disabled value="">Pilih Kategori</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                    </select>
                                    <div v-if="form.errors.facility_category_id" class="text-red-500 text-xs mt-1">{{ form.errors.facility_category_id }}</div>
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Fasilitas</label>
                                <textarea id="description" v-model="form.description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="price_estimation" class="block text-sm font-medium text-gray-700">Estimasi Harga (Opsional)</label>
                                    <input type="text" id="price_estimation" v-model="form.price_estimation" placeholder="Contoh: Mulai Rp 500.000 / malam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.price_estimation" class="text-red-500 text-xs mt-1">{{ form.errors.price_estimation }}</div>
                                </div>
                                <div>
                                    <label for="bed_capacity" class="block text-sm font-medium text-gray-700">Kapasitas Tempat Tidur (Opsional)</label>
                                    <input type="number" id="bed_capacity" v-model="form.bed_capacity" min="0" placeholder="Contoh: 2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <div v-if="form.errors.bed_capacity" class="text-red-500 text-xs mt-1">{{ form.errors.bed_capacity }}</div>
                                </div>
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

                            <!-- Section Foto Fasilitas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Fasilitas (Galeri)</label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative" :class="{'bg-gray-50': isDragging}" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="handleDrop">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Pilih file</span>
                                                <input id="photos" type="file" multiple accept="image/*" class="sr-only" @change="handleFileSelect">
                                            </label>
                                            <p class="pl-1">atau tarik ke sini</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB per file</p>
                                    </div>
                                </div>
                                <div v-if="form.errors.photos" class="text-red-500 text-xs mt-1">{{ form.errors.photos }}</div>

                                <!-- Previews -->
                                <div v-if="photoPreviews.length > 0" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div v-for="(preview, index) in photoPreviews" :key="index" class="relative group rounded-lg overflow-hidden border border-gray-200 aspect-video">
                                        <img :src="preview.url" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button type="button" @click="removePhoto(index)" class="text-white bg-red-600 hover:bg-red-700 rounded-full p-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4 border-t pt-6">
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
