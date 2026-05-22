<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { watch } from 'vue';

const props = defineProps({
    doctors: Object,
    specialties: Object,
    filters: Object,
});

const form = useForm({
    search: props.filters.search || '',
    specialty: props.filters.specialty || '',
    day: props.filters.day || '',
});

const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

const submitSearch = () => {
    form.get(route('doctors.index'), {
        preserveState: true,
        preserveScroll: true,
    });
};

let searchTimeout;
watch(() => form.search, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        submitSearch();
    }, 500);
});

watch(() => form.specialty, submitSearch);
watch(() => form.day, submitSearch);
</script>

<template>
    <Head title="Cari Dokter">
        <meta name="description" content="Temukan dokter spesialis berpengalaman di RS Nusantara Medika. Filter berdasarkan spesialisasi, jadwal praktek, dan nama dokter." />
    </Head>
    <PublicLayout>
        <div class="bg-primary/5 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Cari Dokter Spesialis</h1>
                    <p class="mt-4 text-xl text-gray-500">Temukan dokter yang tepat untuk kebutuhan kesehatan Anda.</p>
                </div>

                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-6 mb-12 border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Nama Dokter</label>
                            <input type="text" id="search" v-model="form.search" placeholder="Cari nama..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                            <select id="specialty" v-model="form.specialty" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                <option value="">Semua Spesialisasi</option>
                                <option v-for="sp in specialties.data" :key="sp.id" :value="sp.slug">
                                    {{ sp.name }} ({{ sp.doctors_count }})
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="day" class="block text-sm font-medium text-gray-700 mb-1">Hari Praktek</label>
                            <select id="day" v-model="form.day" class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                <option value="">Semua Hari</option>
                                <option v-for="day in days" :key="day" :value="day">{{ day }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div v-if="doctors.data.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div v-for="doctor in doctors.data" :key="doctor.id" class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col sm:flex-row group">
                        <!-- Left: Photo & Basic Info -->
                        <div class="sm:w-56 flex-shrink-0 p-6 flex flex-col items-center text-center bg-gradient-to-b sm:bg-gradient-to-br from-cyan-50/80 to-white border-b sm:border-b-0 sm:border-r border-gray-100">
                            <div class="w-28 h-28 rounded-full overflow-hidden mb-4 ring-4 ring-white shadow-md group-hover:ring-cyan-100 transition-all">
                                <img :src="doctor.photo_url || 'https://ui-avatars.com/api/?name='+encodeURIComponent(doctor.name)+'&color=0891b2&background=cffafe&size=256'" :alt="doctor.full_name" class="w-full h-full object-cover" />
                            </div>
                            <h3 class="text-base font-bold text-gray-900 leading-snug mb-1">{{ doctor.full_name }}</h3>
                            <!-- Specialties as tags -->
                            <div class="flex flex-wrap justify-center gap-1 mt-1 mb-2">
                                <span v-if="doctor.specialties && doctor.specialties.length > 0" v-for="spec in doctor.specialties" :key="spec.id" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                    {{ spec.name }}
                                </span>
                                <span v-else class="text-xs text-gray-400 italic">Belum ada spesialisasi</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ doctor.experience_years }} Tahun Pengalaman
                            </div>
                        </div>

                        <!-- Right: Schedule & Action -->
                        <div class="flex-grow p-6 flex flex-col justify-between">
                            <!-- Schedule -->
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Jadwal Praktek
                                </p>
                                <div v-if="doctor.schedules && doctor.schedules.length > 0" class="space-y-1.5">
                                    <div v-for="schedule in doctor.schedules" :key="schedule.id" class="flex items-center justify-between text-sm bg-gray-50 rounded-lg px-3 py-2">
                                        <span class="font-medium text-gray-700">{{ schedule.day_of_week }}</span>
                                        <span class="text-gray-500 tabular-nums">{{ schedule.start_time }} – {{ schedule.end_time }}</span>
                                    </div>
                                </div>
                                <div v-else class="text-sm text-gray-400 italic bg-gray-50 rounded-lg px-3 py-3 text-center">
                                    Belum ada jadwal tersedia.
                                </div>
                            </div>

                            <!-- Action -->
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <Link :href="route('doctors.show', doctor.id)" class="block w-full text-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-primary hover:bg-secondary transition-colors">
                                    Lihat Profil Lengkap
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-100">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada dokter yang ditemukan</h3>
                    <p class="mt-1 text-sm text-gray-500">Coba ubah filter pencarian Anda.</p>
                    <div class="mt-6">
                        <button @click="form.reset(); submitSearch()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-primary bg-cyan-50 hover:bg-cyan-100 focus:outline-none">
                            Reset Filter
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="doctors.meta && doctors.meta.last_page > 1" class="mt-10 flex justify-center">
                    <div class="flex flex-wrap justify-center gap-2">
                        <template v-for="(link, p) in doctors.meta.links" :key="p">
                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded bg-white" v-html="link.label" />
                            <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-primary focus:text-primary transition-colors" :class="link.active ? 'bg-primary text-white border-primary hover:bg-secondary' : 'bg-white text-gray-700 hover:bg-gray-50'" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
