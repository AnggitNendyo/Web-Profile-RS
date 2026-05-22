<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/RichTextEditor.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    article: {
        type: Object,
        default: () => ({
            title: '',
            content: '',
            published_at: '',
            cover_image: '',
            excerpt: '',
            category_id: null,
            meta_title: '',
            meta_description: '',
        })
    },
    categories: {
        type: Array,
        default: () => [],
    },
});

const isEditing = !!props.article.id;
const showPreview = ref(false);
const showSeoFields = ref(false);
const coverPreview = ref(props.article.cover_image || '');

const form = useForm({
    title: props.article.title || '',
    content: props.article.content || '',
    published_at: props.article.published_at ? props.article.published_at.split(' ')[0] : '',
    cover_image: props.article.cover_image || '',
    excerpt: props.article.excerpt || '',
    category_id: props.article.category_id || '',
    meta_title: props.article.meta_title || '',
    meta_description: props.article.meta_description || '',
});

// Cover image handling
const coverDragOver = ref(false);

const handleCoverUpload = (e) => {
    const file = e.target.files?.[0];
    if (file) processCoverFile(file);
};

const handleCoverDrop = (e) => {
    coverDragOver.value = false;
    const file = e.dataTransfer?.files?.[0];
    if (file && file.type.startsWith('image/')) processCoverFile(file);
};

const handleCoverPaste = (e) => {
    const items = e.clipboardData?.items;
    if (!items) return;
    for (const item of items) {
        if (item.type.startsWith('image/')) {
            const file = item.getAsFile();
            if (file) processCoverFile(file);
            break;
        }
    }
};

const processCoverFile = (file) => {
    const reader = new FileReader();
    reader.onload = (e) => {
        const img = new window.Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            let width = img.width;
            let height = img.height;
            const maxWidth = 1200;

            if (width > maxWidth) {
                height = Math.round((height * maxWidth) / width);
                width = maxWidth;
            }

            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
            form.cover_image = dataUrl;
            coverPreview.value = dataUrl;
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
};

const removeCover = () => {
    form.cover_image = '';
    coverPreview.value = '';
};

// Auto-excerpt from content
const autoExcerpt = computed(() => {
    if (form.excerpt) return form.excerpt;
    const text = form.content.replace(/<[^>]*>/g, '').trim();
    return text.substring(0, 160) + (text.length > 160 ? '...' : '');
});

// Reading time
const readingTime = computed(() => {
    const text = form.content.replace(/<[^>]*>/g, '').trim();
    const words = text ? text.split(/\s+/).length : 0;
    return Math.max(1, Math.ceil(words / 200));
});

const submit = () => {
    if (isEditing) {
        form.put(route('admin.articles.update', props.article.id));
    } else {
        form.post(route('admin.articles.store'));
    }
};

// New category inline creation
const showNewCategory = ref(false);
const newCategoryName = ref('');
const creatingCategory = ref(false);

const createCategory = async () => {
    if (!newCategoryName.value.trim()) return;
    creatingCategory.value = true;
    try {
        const response = await axios.post(route('admin.article-categories.store'), {
            name: newCategoryName.value.trim(),
        });
        props.categories.push(response.data);
        form.category_id = response.data.id;
        newCategoryName.value = '';
        showNewCategory.value = false;
    } catch (err) {
        alert('Gagal membuat kategori: ' + (err.response?.data?.message || err.message));
    } finally {
        creatingCategory.value = false;
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Artikel' : 'Tambah Artikel'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ isEditing ? 'Edit Artikel' : 'Tambah Artikel Baru' }}
                </h2>
                <Link
                    :href="route('admin.articles.index')"
                    class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <!-- Main Content Column -->
                        <div class="lg:col-span-2 space-y-6">
                            
                            <!-- Cover Image -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Gambar Cover
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <div v-if="coverPreview" class="relative group">
                                        <img :src="coverPreview" alt="Cover preview" class="w-full h-56 object-cover rounded-lg shadow-sm" />
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center gap-3">
                                            <label class="cursor-pointer bg-white text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-100 transition shadow-sm">
                                                Ganti
                                                <input type="file" accept="image/*" class="hidden" @change="handleCoverUpload" />
                                            </label>
                                            <button type="button" @click="removeCover" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition shadow-sm">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                    <div 
                                        v-else
                                        @dragover.prevent="coverDragOver = true"
                                        @dragleave="coverDragOver = false"
                                        @drop.prevent="handleCoverDrop"
                                        @paste="handleCoverPaste"
                                        :class="['border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer', coverDragOver ? 'border-indigo-400 bg-indigo-50' : 'border-gray-300 hover:border-indigo-300 hover:bg-gray-50']"
                                        @click="$refs.coverInput.click()"
                                        tabindex="0"
                                    >
                                        <input ref="coverInput" type="file" accept="image/*" class="hidden" @change="handleCoverUpload" />
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l8-8a4 4 0 015.656 0l2.344 2.344"></path></svg>
                                        <p class="mt-3 text-sm font-medium text-gray-600">Drag & drop gambar, atau klik untuk upload</p>
                                        <p class="mt-1 text-xs text-gray-400">Bisa juga paste (Ctrl+V) gambar dari clipboard</p>
                                        <p class="mt-1 text-xs text-gray-400">Maks. 2MB · JPG, PNG, WebP</p>
                                    </div>
                                    <div v-if="form.errors.cover_image" class="text-red-500 text-xs mt-2">{{ form.errors.cover_image }}</div>
                                </div>
                            </div>

                            <!-- Title -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <div class="p-6">
                                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Artikel</label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        v-model="form.title" 
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg font-medium placeholder-gray-400" 
                                        placeholder="Masukkan judul artikel yang menarik..."
                                        required
                                    >
                                    <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                                </div>
                            </div>

                            <!-- Rich Text Editor -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Konten Artikel
                                        <span class="text-xs font-normal text-gray-400 ml-2">• Paste gambar langsung ke editor (Ctrl+V)</span>
                                    </h3>
                                </div>
                                <div class="p-4">
                                    <RichTextEditor v-model="form.content" placeholder="Mulai menulis konten artikel di sini. Anda bisa paste gambar langsung dari clipboard..." />
                                    <div v-if="form.errors.content" class="text-red-500 text-xs mt-2">{{ form.errors.content }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Column -->
                        <div class="lg:col-span-1 space-y-6">
                            
                            <!-- Publish Settings -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Pengaturan Publish
                                    </h3>
                                </div>
                                <div class="p-6 space-y-4">
                                    <!-- Status badge -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Status:</span>
                                        <span v-if="form.published_at" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                            Published
                                        </span>
                                        <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                            Draft
                                        </span>
                                    </div>

                                    <div>
                                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Publish</label>
                                        <input type="date" id="published_at" v-model="form.published_at" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <p class="text-xs text-gray-400 mt-1">Kosongkan untuk menyimpan sebagai draft</p>
                                        <div v-if="form.errors.published_at" class="text-red-500 text-xs mt-1">{{ form.errors.published_at }}</div>
                                    </div>

                                    <!-- Reading time info -->
                                    <div class="bg-indigo-50 rounded-lg p-3 flex items-center gap-3">
                                        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <p class="text-sm font-medium text-indigo-700">~{{ readingTime }} menit baca</p>
                                            <p class="text-xs text-indigo-500">Estimasi waktu baca</p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="pt-2 space-y-2">
                                        <button type="submit" :disabled="form.processing" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ form.processing ? 'Menyimpan...' : 'Simpan Artikel' }}
                                        </button>
                                        <button type="button" @click="showPreview = true" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Preview
                                        </button>
                                        <Link :href="route('admin.articles.index')" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-500 hover:bg-gray-50 transition">
                                            Batal
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        Kategori
                                    </h3>
                                </div>
                                <div class="p-6 space-y-3">
                                    <select v-model="form.category_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">-- Tanpa Kategori --</option>
                                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                    </select>
                                    <div v-if="form.errors.category_id" class="text-red-500 text-xs">{{ form.errors.category_id }}</div>
                                    
                                    <div v-if="!showNewCategory">
                                        <button type="button" @click="showNewCategory = true" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            Buat Kategori Baru
                                        </button>
                                    </div>
                                    <div v-else class="space-y-2">
                                        <input 
                                            v-model="newCategoryName" 
                                            type="text" 
                                            placeholder="Nama kategori baru..." 
                                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            @keyup.enter="createCategory"
                                        />
                                        <div class="flex gap-2">
                                            <button type="button" @click="createCategory" :disabled="creatingCategory" class="flex-1 text-xs px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 transition">
                                                {{ creatingCategory ? 'Membuat...' : 'Buat' }}
                                            </button>
                                            <button type="button" @click="showNewCategory = false; newCategoryName = ''" class="flex-1 text-xs px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Excerpt -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                        Ringkasan
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <textarea 
                                        v-model="form.excerpt" 
                                        rows="3" 
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        placeholder="Ringkasan singkat artikel (akan auto-generate dari konten jika kosong)"
                                        maxlength="500"
                                    ></textarea>
                                    <div class="flex justify-between mt-1">
                                        <div v-if="form.errors.excerpt" class="text-red-500 text-xs">{{ form.errors.excerpt }}</div>
                                        <span class="text-xs text-gray-400 ml-auto">{{ (form.excerpt || '').length }}/500</span>
                                    </div>
                                    <div v-if="!form.excerpt && form.content" class="mt-2 bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-500 font-medium mb-1">Auto-generated:</p>
                                        <p class="text-xs text-gray-600">{{ autoExcerpt }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Fields (Collapsible) -->
                            <div class="bg-white shadow-sm sm:rounded-xl overflow-hidden">
                                <button type="button" @click="showSeoFields = !showSeoFields" class="w-full px-6 py-4 border-b border-gray-100 flex items-center justify-between hover:bg-gray-50 transition">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        SEO & Meta
                                    </h3>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': showSeoFields }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div v-show="showSeoFields" class="p-6 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                                        <input type="text" v-model="form.meta_title" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Override judul untuk SEO" maxlength="255">
                                        <p class="text-xs text-gray-400 mt-1">{{ (form.meta_title || form.title || '').length }}/60 karakter (rekomendasi)</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                                        <textarea v-model="form.meta_description" rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Deskripsi singkat untuk mesin pencari" maxlength="300"></textarea>
                                        <p class="text-xs text-gray-400 mt-1">{{ (form.meta_description || '').length }}/160 karakter (rekomendasi)</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Modal -->
        <Teleport to="body">
            <div v-if="showPreview" class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape="showPreview = false">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showPreview = false"></div>
                <div class="relative min-h-screen flex items-start justify-center p-4 pt-10">
                    <div class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                        <!-- Preview Header -->
                        <div class="sticky top-0 bg-white/90 backdrop-blur-md border-b border-gray-200 px-6 py-3 flex items-center justify-between z-10 rounded-t-2xl">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Preview Artikel
                            </div>
                            <button type="button" @click="showPreview = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <!-- Preview Content -->
                        <div class="p-8">
                            <div v-if="coverPreview" class="rounded-xl overflow-hidden mb-8 shadow-md -mx-2">
                                <img :src="coverPreview" :alt="form.title" class="w-full h-auto" />
                            </div>
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-4 leading-tight">{{ form.title || 'Judul Artikel' }}</h1>
                            <div class="flex items-center gap-3 text-sm text-gray-500 mb-8 pb-6 border-b border-gray-200">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ form.published_at || 'Draft' }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ readingTime }} menit baca
                                </span>
                            </div>
                            <div class="prose prose-lg max-w-none" v-html="form.content || '<p class=\'text-gray-400 italic\'>Belum ada konten...</p>'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </AuthenticatedLayout>
</template>
