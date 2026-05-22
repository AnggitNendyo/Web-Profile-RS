<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    hospital_name: props.settings.hospital_name || '',
    hospital_description: props.settings.hospital_description || '',
    contact_phone: props.settings.contact_phone || '',
    contact_email: props.settings.contact_email || '',
    contact_address: props.settings.contact_address || '',
    contact_emergency: props.settings.contact_emergency || '',
    social_facebook: props.settings.social_facebook || '',
    social_instagram: props.settings.social_instagram || '',
    social_youtube: props.settings.social_youtube || '',
    hero_title: props.settings.hero_title || '',
    hero_subtitle: props.settings.hero_subtitle || '',
    stats_years: props.settings.stats_years || 0,
    stats_patients: props.settings.stats_patients || 0,
});

const submit = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Toast notification should be handled by global layout
        },
    });
};
</script>

<template>
    <Head title="Pengaturan Website" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Website</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-8">
                            
                            <!-- Section: Informasi Umum -->
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">Informasi Umum</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="hospital_name" value="Nama Rumah Sakit" />
                                        <TextInput
                                            id="hospital_name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.hospital_name"
                                            required
                                        />
                                        <InputError class="mt-2" :message="form.errors.hospital_name" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <InputLabel for="hospital_description" value="Deskripsi Singkat (Footer)" />
                                        <textarea
                                            id="hospital_description"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm"
                                            rows="3"
                                            v-model="form.hospital_description"
                                        ></textarea>
                                        <InputError class="mt-2" :message="form.errors.hospital_description" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Kontak -->
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">Kontak & Lokasi</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel for="contact_phone" value="No. Telepon" />
                                        <TextInput id="contact_phone" type="text" class="mt-1 block w-full" v-model="form.contact_phone" />
                                    </div>
                                    <div>
                                        <InputLabel for="contact_emergency" value="Kontak IGD / Darurat" />
                                        <TextInput id="contact_emergency" type="text" class="mt-1 block w-full" v-model="form.contact_emergency" />
                                    </div>
                                    <div>
                                        <InputLabel for="contact_email" value="Email" />
                                        <TextInput id="contact_email" type="email" class="mt-1 block w-full" v-model="form.contact_email" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="contact_address" value="Alamat Lengkap" />
                                        <TextInput id="contact_address" type="text" class="mt-1 block w-full" v-model="form.contact_address" />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Sosial Media -->
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">Sosial Media (URL)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <InputLabel for="social_facebook" value="Facebook" />
                                        <TextInput id="social_facebook" type="url" class="mt-1 block w-full" v-model="form.social_facebook" placeholder="https://facebook.com/..." />
                                    </div>
                                    <div>
                                        <InputLabel for="social_instagram" value="Instagram" />
                                        <TextInput id="social_instagram" type="url" class="mt-1 block w-full" v-model="form.social_instagram" placeholder="https://instagram.com/..." />
                                    </div>
                                    <div>
                                        <InputLabel for="social_youtube" value="YouTube" />
                                        <TextInput id="social_youtube" type="url" class="mt-1 block w-full" v-model="form.social_youtube" placeholder="https://youtube.com/..." />
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Konten Beranda -->
                            <div>
                                <h3 class="text-lg font-medium leading-6 text-gray-900 border-b pb-2 mb-4">Konten Beranda (Home)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <InputLabel for="hero_title" value="Judul Utama (Hero)" />
                                        <TextInput id="hero_title" type="text" class="mt-1 block w-full" v-model="form.hero_title" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <InputLabel for="hero_subtitle" value="Sub Judul (Hero Subtitle)" />
                                        <textarea
                                            id="hero_subtitle"
                                            class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm"
                                            rows="2"
                                            v-model="form.hero_subtitle"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <InputLabel for="stats_years" value="Statistik: Tahun Pengalaman" />
                                        <TextInput id="stats_years" type="number" class="mt-1 block w-full" v-model="form.stats_years" />
                                    </div>
                                    <div>
                                        <InputLabel for="stats_patients" value="Statistik: Pasien Terlayani" />
                                        <TextInput id="stats_patients" type="number" class="mt-1 block w-full" v-model="form.stats_patients" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4 pt-4 border-t">
                                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Simpan Pengaturan
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
