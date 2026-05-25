<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps({
    vacancy: {
        type: Object,
        default: null
    }
});

const isEditing = !!props.vacancy;

const form = useForm({
    title: props.vacancy?.title || '',
    department: props.vacancy?.department || 'Medis',
    location: props.vacancy?.location || 'On-site',
    type: props.vacancy?.type || 'Full-time',
    description: props.vacancy?.description || '',
    requirements: props.vacancy?.requirements || '',
    apply_link: props.vacancy?.apply_link || '',
    apply_email: props.vacancy?.apply_email || '',
    deadline: props.vacancy?.deadline || '',
    is_active: props.vacancy?.is_active ?? true,
});

const submit = () => {
    if (isEditing) {
        form.put(route('admin.job-vacancies.update', props.vacancy.id));
    } else {
        form.post(route('admin.job-vacancies.store'));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Lowongan' : 'Tambah Lowongan'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link :href="route('admin.job-vacancies.index')" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </Link>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ isEditing ? 'Edit Lowongan Pekerjaan' : 'Tambah Lowongan Pekerjaan' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-xl">
                    <form @submit.prevent="submit" class="p-6 sm:p-8 space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <InputLabel for="title" value="Judul Lowongan / Posisi" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    placeholder="Contoh: Dokter Umum, Perawat, dll"
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="department" value="Departemen" />
                                <TextInput
                                    id="department"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.department"
                                    required
                                    placeholder="Contoh: Medis, Keperawatan, IT, dll"
                                />
                                <InputError class="mt-2" :message="form.errors.department" />
                            </div>

                            <div>
                                <InputLabel for="type" value="Tipe Pekerjaan" />
                                <select 
                                    id="type"
                                    v-model="form.type"
                                    class="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                >
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Kontrak">Kontrak</option>
                                    <option value="Magang / Internship">Magang / Internship</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <div>
                                <InputLabel for="location" value="Lokasi Kerja" />
                                <TextInput
                                    id="location"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.location"
                                    required
                                    placeholder="Contoh: On-site (Solo), Remote, dll"
                                />
                                <InputError class="mt-2" :message="form.errors.location" />
                            </div>

                            <div>
                                <InputLabel for="deadline" value="Batas Akhir Lamaran (Deadline)" />
                                <TextInput
                                    id="deadline"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.deadline"
                                />
                                <InputError class="mt-2" :message="form.errors.deadline" />
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        <div>
                            <InputLabel value="Deskripsi Pekerjaan" class="mb-2" />
                            <div class="border rounded-md">
                                <QuillEditor 
                                    v-model:content="form.description" 
                                    contentType="html" 
                                    theme="snow" 
                                    style="height: 200px;"
                                />
                            </div>
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div>
                            <InputLabel value="Kualifikasi / Persyaratan" class="mb-2" />
                            <div class="border rounded-md">
                                <QuillEditor 
                                    v-model:content="form.requirements" 
                                    contentType="html" 
                                    theme="snow" 
                                    style="height: 200px;"
                                />
                            </div>
                            <InputError class="mt-2" :message="form.errors.requirements" />
                        </div>

                        <hr class="border-gray-200">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="apply_link" value="Link Form Lamaran (Opsional)" />
                                <TextInput
                                    id="apply_link"
                                    type="url"
                                    class="mt-1 block w-full"
                                    v-model="form.apply_link"
                                    placeholder="https://forms.gle/..."
                                />
                                <p class="mt-1 text-xs text-gray-500">Gunakan Google Form atau sistem eksternal lain jika ada.</p>
                                <InputError class="mt-2" :message="form.errors.apply_link" />
                            </div>

                            <div>
                                <InputLabel for="apply_email" value="Email Lamaran (Opsional)" />
                                <TextInput
                                    id="apply_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.apply_email"
                                    placeholder="hrd@rs-nusantara.com"
                                />
                                <p class="mt-1 text-xs text-gray-500">Isi jika pelamar harus mengirim CV via email.</p>
                                <InputError class="mt-2" :message="form.errors.apply_email" />
                            </div>
                        </div>

                        <div class="block pt-2">
                            <label class="flex items-center">
                                <input type="checkbox" v-model="form.is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ms-2 text-sm text-gray-600">Status Aktif (Tampilkan di website)</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t pt-6">
                            <Link :href="route('admin.job-vacancies.index')" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Batal
                            </Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                {{ isEditing ? 'Simpan Perubahan' : 'Simpan Lowongan' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
