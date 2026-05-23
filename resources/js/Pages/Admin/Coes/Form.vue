<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    coe: {
        type: Object,
        default: () => ({
            title: '',
            short_description: '',
            full_content: '',
            technology_used: [],
            slider_images: [],
            show_slider: true,
            banner_image: null,
        })
    }
});

const isEditing = !!props.coe.id;
const techArray = Array.isArray(props.coe.technology_used) 
    ? props.coe.technology_used 
    : (typeof props.coe.technology_used === 'string' && props.coe.technology_used.trim() !== '' 
        ? JSON.parse(props.coe.technology_used) 
        : []);

const form = useForm({
    title: props.coe.title || '',
    short_description: props.coe.short_description || '',
    full_content: props.coe.full_content || '',
    technology_used: techArray,
    slider_images: [],
    existing_images: props.coe.slider_images || [],
    show_slider: props.coe.show_slider ?? true,
    banner_image: null,
});

const bannerPreview = ref(props.coe.banner_image ? '/storage/' + props.coe.banner_image : null);
const bannerInput = ref(null);

const handleBannerUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.banner_image = file;
        bannerPreview.value = URL.createObjectURL(file);
    }
};

const removeBanner = () => {
    form.banner_image = null;
    bannerPreview.value = null;
    if (bannerInput.value) bannerInput.value.value = '';
};

const imagePreviews = ref([]);
const fileInput = ref(null);

onMounted(() => {
    if (isEditing && props.coe.slider_images && Array.isArray(props.coe.slider_images)) {
        imagePreviews.value = props.coe.slider_images.map(path => ({
            isExisting: true,
            path: path,
            url: '/storage/' + path
        }));
    }
});

const handleImageUpload = (event) => {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        form.slider_images.push(file);
        imagePreviews.value.push({
            isExisting: false,
            file: file,
            url: URL.createObjectURL(file)
        });
    });
    if (fileInput.value) fileInput.value.value = '';
};

const removeImage = (index) => {
    const preview = imagePreviews.value[index];
    if (preview.isExisting) {
        const pathIndex = form.existing_images.indexOf(preview.path);
        if (pathIndex !== -1) {
            form.existing_images.splice(pathIndex, 1);
        }
    } else {
        const fileIndex = form.slider_images.indexOf(preview.file);
        if (fileIndex !== -1) {
            form.slider_images.splice(fileIndex, 1);
        }
    }
    imagePreviews.value.splice(index, 1);
};

const addTechnology = () => {
    form.technology_used.push({ name: '', description: '' });
};

const removeTechnology = (index) => {
    form.technology_used.splice(index, 1);
};

const submit = () => {
    if (isEditing) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.coes.update', props.coe.id), {
            forceFormData: true,
        });
    } else {
        form.post(route('admin.coes.store'), {
            forceFormData: true,
        });
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Layanan' : 'Tambah Layanan'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ isEditing ? 'Edit Layanan Unggulan' : 'Tambah Layanan Unggulan' }}
                </h2>
                <Link
                    :href="route('admin.coes.index')"
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
                                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Layanan</label>
                                    <input type="text" id="title" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                                </div>

                                <div class="flex items-center mt-6">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" v-model="form.show_slider" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-700">Tampilkan Slider Gambar di Publik</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Banner Utama (Hero Image)</label>
                                <div class="flex items-center gap-4 mb-4">
                                    <div v-if="bannerPreview" class="relative group w-48 h-32 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img :src="bannerPreview" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" @click="removeBanner" class="bg-red-500 text-white px-2 py-1 text-xs rounded-md hover:bg-red-600">Ganti</button>
                                        </div>
                                    </div>
                                    
                                    <label v-else class="w-48 h-32 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-indigo-400 transition-colors">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="mt-2 text-xs text-gray-500 font-medium">Unggah Banner</span>
                                        <input type="file" ref="bannerInput" accept="image/*" @change="handleBannerUpload" class="hidden" />
                                    </label>
                                </div>
                                <div v-if="form.errors.banner_image" class="text-red-500 text-xs mt-1">{{ form.errors.banner_image }}</div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Slider</label>
                                <div class="flex flex-wrap gap-4 mb-4">
                                    <div v-for="(preview, index) in imagePreviews" :key="index" class="relative group w-32 h-32 rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                                        <img :src="preview.url" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" @click="removeImage(index)" class="bg-red-500 text-white p-1.5 rounded-full hover:bg-red-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <label class="w-32 h-32 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 hover:border-indigo-400 transition-colors">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        <span class="mt-2 text-xs text-gray-500 font-medium">Tambah Gambar</span>
                                        <input type="file" ref="fileInput" multiple accept="image/*" @change="handleImageUpload" class="hidden" />
                                    </label>
                                </div>
                                <div v-if="form.errors.slider_images" class="text-red-500 text-xs mt-1">{{ form.errors.slider_images }}</div>
                            </div>

                            <div>
                                <label for="short_description" class="block text-sm font-medium text-gray-700">Deskripsi Singkat</label>
                                <textarea id="short_description" v-model="form.short_description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required></textarea>
                                <div v-if="form.errors.short_description" class="text-red-500 text-xs mt-1">{{ form.errors.short_description }}</div>
                            </div>

                            <div>
                                <label for="full_content" class="block text-sm font-medium text-gray-700 mb-1">Isi Lengkap</label>
                                <RichTextEditor v-model="form.full_content" />
                                <div v-if="form.errors.full_content" class="text-red-500 text-xs mt-1">{{ form.errors.full_content }}</div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Teknologi yang Digunakan</label>
                                    <button type="button" @click="addTechnology" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Tambah Teknologi
                                    </button>
                                </div>
                                
                                <div v-if="form.technology_used.length === 0" class="text-sm text-gray-500 italic py-4 text-center border-2 border-dashed rounded-lg bg-gray-50">
                                    Belum ada teknologi yang ditambahkan.
                                </div>

                                <div v-else class="space-y-4">
                                    <div v-for="(tech, index) in form.technology_used" :key="index" class="p-4 border rounded-lg bg-gray-50 relative group">
                                        <button type="button" @click="removeTechnology(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <div class="md:col-span-4">
                                                <label class="block text-xs font-medium text-gray-700">Nama Teknologi (contoh: MRI 3 Tesla)</label>
                                                <input type="text" v-model="tech.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                            </div>
                                            <div class="md:col-span-8">
                                                <label class="block text-xs font-medium text-gray-700">Deskripsi Kegunaan</label>
                                                <input type="text" v-model="tech.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.errors.technology_used" class="text-red-500 text-xs mt-1">{{ form.errors.technology_used }}</div>
                            </div>

                            <div class="flex justify-end gap-4">
                                <Link :href="route('admin.coes.index')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase shadow-sm hover:bg-gray-50">
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
