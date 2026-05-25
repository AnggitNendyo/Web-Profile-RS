<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';

const props = defineProps({
    partner: {
        type: Object,
        default: null
    }
});

const isEditing = !!props.partner;

const form = useForm({
    name: props.partner?.name || '',
    type: props.partner?.type || 'BPJS',
    description: props.partner?.description || '',
    is_active: props.partner?.is_active ?? true,
    logo: null,
});

const photoPreview = ref(props.partner?.logo_path ? '/storage/' + props.partner.logo_path : null);

const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    if (isEditing) {
        form.post(route('admin.insurance-partners.update', props.partner.id), {
            preserveScroll: true,
            forceFormData: true,
            data: { _method: 'put' }
        });
    } else {
        form.post(route('admin.insurance-partners.store'));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Mitra Asuransi' : 'Tambah Mitra Asuransi'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.insurance-partners.index')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ isEditing ? 'Edit Mitra Asuransi' : 'Tambah Mitra Asuransi' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                        
                        <!-- Logo Upload -->
                        <div class="mb-6">
                            <InputLabel value="Logo Asuransi" />
                            <div class="mt-2 flex items-center gap-6">
                                <div class="h-24 w-48 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center overflow-hidden shrink-0">
                                    <img v-if="photoPreview" :src="photoPreview" class="h-full w-full object-contain p-2" />
                                    <svg v-else class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <input type="file" id="logo" @change="handlePhotoChange" class="hidden" accept="image/*" />
                                    <label for="logo" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        Pilih Gambar
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF up to 2MB.</p>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.logo" />
                        </div>

                        <div>
                            <InputLabel for="name" value="Nama Asuransi" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="type" value="Kategori / Tipe Asuransi" />
                            <select 
                                id="type"
                                v-model="form.type"
                                class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                            >
                                <option value="BPJS">BPJS</option>
                                <option value="Asuransi Swasta">Asuransi Swasta</option>
                                <option value="Asuransi Perusahaan">Asuransi Perusahaan</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.type" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Deskripsi Singkat" />
                            <textarea
                                id="description"
                                class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                v-model="form.description"
                                rows="3"
                            ></textarea>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="block">
                            <label class="flex items-center">
                                <input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-600">Status Aktif (Tampilkan di website)</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <Link :href="route('admin.insurance-partners.index')" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Batal
                            </Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ isEditing ? 'Simpan Perubahan' : 'Simpan Mitra Asuransi' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
