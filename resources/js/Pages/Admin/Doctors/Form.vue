<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import TagSelect from '@/Components/TagSelect.vue';

const props = defineProps({
    doctor: {
        type: Object,
        default: () => ({
            front_title: '',
            name: '',
            back_title: '',
            title: '',
            specialties: [],
            start_year: new Date().getFullYear(),
            is_active: 1,
            education: [],
            schedules: [],
        })
    },
    specialties: Array,
    degrees: Array,
});

const isEditing = !!props.doctor.id;

// Parse education
const educationArray = Array.isArray(props.doctor.education) 
    ? props.doctor.education 
    : (typeof props.doctor.education === 'string' && props.doctor.education.trim() !== '' 
        ? JSON.parse(props.doctor.education) 
        : []);

const form = useForm({
    front_title: props.doctor.front_title ? props.doctor.front_title.split(', ') : [],
    name: props.doctor.name || '',
    back_title: props.doctor.back_title ? props.doctor.back_title.split(', ') : [],
    title: props.doctor.title || '',
    specialties: props.doctor.specialties ? props.doctor.specialties.map(s => s.id) : [],
    start_year: props.doctor.start_year || new Date().getFullYear(),
    is_active: props.doctor.is_active === 1 || props.doctor.is_active === true,
    education: educationArray,
    schedules: props.doctor.schedules || [],
    photo: null,
});

const photoPreview = ref(props.doctor.photo_path ? `/storage/${props.doctor.photo_path}` : null); // Fallback logic, but ideally we'd use Spatie Media Library URL
// Actually Spatie returns media, we can just use the url if available. Let's just create local object url.

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    } else {
        form.photo = null;
        photoPreview.value = null;
    }
};

const addEducation = () => {
    form.education.push({ degree: '', institution: '', year: new Date().getFullYear() });
};

const removeEducation = (index) => {
    form.education.splice(index, 1);
};

const addSchedule = () => {
    form.schedules.push({ day_of_week: 'Senin', start_time: '08:00', end_time: '12:00' });
};

const removeSchedule = (index) => {
    form.schedules.splice(index, 1);
};

const daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

const submit = () => {
    if (isEditing) {
        form.transform((data) => ({
            ...data,
            front_title: Array.isArray(data.front_title) ? data.front_title.join(', ') : data.front_title,
            back_title: Array.isArray(data.back_title) ? data.back_title.join(', ') : data.back_title,
            _method: 'put',
        })).post(route('admin.doctors.update', props.doctor.id));
    } else {
        form.transform((data) => ({
            ...data,
            front_title: Array.isArray(data.front_title) ? data.front_title.join(', ') : data.front_title,
            back_title: Array.isArray(data.back_title) ? data.back_title.join(', ') : data.back_title,
        })).post(route('admin.doctors.store'));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Dokter' : 'Tambah Dokter'" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ isEditing ? 'Edit Dokter' : 'Tambah Dokter Baru' }}
                </h2>
                <Link
                    :href="route('admin.doctors.index')"
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
                        <form @submit.prevent="submit" class="space-y-8">
                            
                            <!-- Section: Data Diri -->
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informasi Dasar</h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Gelar Depan</label>
                                        <TagSelect 
                                            v-model="form.front_title" 
                                            :options="degrees" 
                                            label-key="name" 
                                            value-key="name" 
                                            placeholder="Pilih Gelar..."
                                        />
                                        <div v-if="form.errors.front_title" class="text-red-500 text-xs mt-1">{{ form.errors.front_title }}</div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap Asli</label>
                                        <input type="text" id="name" v-model="form.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required placeholder="Budi Santoso">
                                        <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                                    </div>

                                    <div class="md:col-span-1">
                                        <label class="block text-sm font-medium text-gray-700">Gelar Belakang</label>
                                        <TagSelect 
                                            v-model="form.back_title" 
                                            :options="degrees" 
                                            label-key="name" 
                                            value-key="name" 
                                            placeholder="Pilih Gelar..."
                                        />
                                        <div v-if="form.errors.back_title" class="text-red-500 text-xs mt-1">{{ form.errors.back_title }}</div>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                                        <TagSelect 
                                            v-model="form.specialties" 
                                            :options="specialties" 
                                            label-key="name" 
                                            value-key="id" 
                                            placeholder="Pilih Spesialisasi..."
                                        />
                                        <div v-if="form.errors.specialties" class="text-red-500 text-xs mt-1">{{ form.errors.specialties }}</div>
                                    </div>

                                    <div class="md:col-span-1">
                                        <label for="start_year" class="block text-sm font-medium text-gray-700">Tahun Mulai Praktik</label>
                                        <input type="number" id="start_year" v-model="form.start_year" min="1950" :max="new Date().getFullYear()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                        <div v-if="form.errors.start_year" class="text-red-500 text-xs mt-1">{{ form.errors.start_year }}</div>
                                    </div>

                                    <div class="md:col-span-1">
                                        <label for="title" class="block text-sm font-medium text-gray-700">Jabatan Khusus (Opsional)</label>
                                        <input type="text" id="title" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Contoh: Kepala IGD">
                                        <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Jadwal Praktik -->
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <div class="flex items-center justify-between mb-2 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-900">Jadwal Praktik</h3>
                                    <button type="button" @click="addSchedule" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Tambah Jadwal
                                    </button>
                                </div>
                                
                                <div v-if="form.schedules.length === 0" class="text-sm text-gray-500 italic py-4 text-center border-2 border-dashed rounded-lg bg-white mt-4">
                                    Belum ada jadwal praktik yang ditambahkan.
                                </div>

                                <div v-else class="space-y-4 mt-4">
                                    <div v-for="(schedule, index) in form.schedules" :key="index" class="p-4 border rounded-lg bg-white relative group">
                                        <button type="button" @click="removeSchedule(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Hari Praktik</label>
                                                <select v-model="schedule.day_of_week" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                    <option v-for="day in daysOfWeek" :key="day" :value="day">{{ day }}</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Jam Mulai</label>
                                                <input type="time" v-model="schedule.start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Jam Selesai</label>
                                                <input type="time" v-model="schedule.end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.errors.schedules" class="text-red-500 text-xs mt-1">{{ form.errors.schedules }}</div>
                            </div>

                            <!-- Section: Riwayat Pendidikan -->
                            <div class="bg-gray-50 p-4 rounded-lg border">
                                <div class="flex items-center justify-between mb-2 border-b pb-2">
                                    <h3 class="text-lg font-medium text-gray-900">Riwayat Pendidikan</h3>
                                    <button type="button" @click="addEducation" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        Tambah Pendidikan
                                    </button>
                                </div>
                                
                                <div v-if="form.education.length === 0" class="text-sm text-gray-500 italic py-4 text-center border-2 border-dashed rounded-lg bg-white mt-4">
                                    Belum ada riwayat pendidikan yang ditambahkan.
                                </div>

                                <div v-else class="space-y-4 mt-4">
                                    <div v-for="(edu, index) in form.education" :key="index" class="p-4 border rounded-lg bg-white relative group">
                                        <button type="button" @click="removeEducation(index)" class="absolute top-2 right-2 text-red-500 hover:text-red-700 p-1 rounded-full hover:bg-red-50 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-medium text-gray-700">Gelar</label>
                                                <select v-model="edu.degree" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                    <option disabled value="">Pilih Gelar</option>
                                                    <option v-for="deg in degrees" :key="deg.id" :value="deg.name">{{ deg.name }}</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-6">
                                                <label class="block text-xs font-medium text-gray-700">Institusi</label>
                                                <input type="text" v-model="edu.institution" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                            </div>
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-medium text-gray-700">Tahun Lulus</label>
                                                <input type="number" v-model="edu.year" min="1950" :max="new Date().getFullYear()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="form.errors.education" class="text-red-500 text-xs mt-1">{{ form.errors.education }}</div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start border-t pt-6">
                                <div>
                                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil Dokter</label>
                                    <div class="flex items-center gap-4">
                                        <div class="shrink-0 h-24 w-24 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-100 flex items-center justify-center">
                                            <img v-if="photoPreview" :src="photoPreview" class="h-full w-full object-cover" />
                                            <svg v-else class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" id="photo" @change="handleFileUpload" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            <p class="text-xs text-gray-500 mt-1">Gunakan foto rasio 1:1 (persegi) berukuran maks 2MB.</p>
                                        </div>
                                    </div>
                                    <div v-if="form.errors.photo" class="text-red-500 text-xs mt-1">{{ form.errors.photo }}</div>
                                </div>

                                <div class="flex items-center h-full pt-4">
                                    <div class="bg-gray-50 p-4 rounded-lg border w-full flex items-center">
                                        <input id="is_active" type="checkbox" v-model="form.is_active" class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <label for="is_active" class="ml-3 block text-base font-medium text-gray-900 cursor-pointer">
                                            Dokter Aktif
                                            <span class="block text-xs font-normal text-gray-500">Dokter ini akan ditampilkan di website publik.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end gap-4 border-t pt-4">
                                <Link :href="route('admin.doctors.index')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase shadow-sm hover:bg-gray-50">
                                    Batal
                                </Link>
                                <button type="submit" :disabled="form.processing" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-blue-700 disabled:opacity-50">
                                    Simpan Dokter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
