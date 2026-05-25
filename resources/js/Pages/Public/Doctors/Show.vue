<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
    doctor: Object,
    relatedDoctors: Object,
});
</script>

<template>
    <Head :title="doctor.data.full_name" />
    <PublicLayout>
        <div class="bg-gray-50 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <nav class="flex mb-8" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <Link :href="route('home')" class="text-gray-400 hover:text-gray-500">
                                    <svg class="flex-shrink-0 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    <span class="sr-only">Home</span>
                                </Link>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <Link :href="route('doctors.index')" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Dokter Spesialis</Link>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-700" aria-current="page">{{ doctor.data.full_name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-12">
                    <div class="px-4 py-5 sm:px-6 bg-primary/5 flex flex-col md:flex-row items-center gap-6">
                        <div class="flex-shrink-0">
                            <div class="h-40 w-40 rounded-full border-4 border-white shadow-md overflow-hidden bg-white">
                                <img class="h-full w-full object-cover" :src="doctor.data.photo_url || 'https://ui-avatars.com/api/?name='+encodeURIComponent(doctor.data.name)+'&color=0891b2&background=cffafe&size=256'" alt="" />
                            </div>
                        </div>
                        <div class="text-center md:text-left flex-grow">
                            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">{{ doctor.data.full_name }}</h2>
                            <div v-if="doctor.data.specialties && doctor.data.specialties.length > 0" class="mt-2 flex flex-wrap justify-center md:justify-start gap-2">
                                <span v-for="spec in doctor.data.specialties" :key="spec.id" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                                    {{ spec.name }}
                                </span>
                            </div>
                            <p v-else class="mt-1 max-w-2xl text-lg text-gray-400 italic">Belum ada spesialisasi</p>
                            <p class="mt-2 text-sm text-gray-500 flex items-center justify-center md:justify-start">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ doctor.data.experience_years }} Tahun Pengalaman
                            </p>
                        </div>
                        <div class="flex-shrink-0 mt-4 md:mt-0">
                            <a href="#jadwal" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                Lihat Jadwal Praktek
                            </a>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Riwayat Pendidikan</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <ul v-if="doctor.data.education && doctor.data.education.length > 0" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                        <li v-for="(edu, index) in doctor.data.education" :key="index" class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                            <div class="w-0 flex-1 flex items-center">
                                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                                                <span class="ml-2 flex-1 w-0 truncate">
                                                    <span class="font-medium text-gray-900">{{ edu.degree }}</span> - {{ edu.institution }}
                                                </span>
                                            </div>
                                            <div class="ml-4 flex-shrink-0 text-gray-500">
                                                {{ edu.year }}
                                            </div>
                                        </li>
                                    </ul>
                                    <p v-else class="text-gray-500 italic">Informasi pendidikan belum tersedia.</p>
                                </dd>
                            </div>
                            
                            <div id="jadwal" class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                                <dt class="text-sm font-medium text-gray-500 pt-2">Jadwal Praktek</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div v-if="doctor.data.schedules && doctor.data.schedules.length > 0" class="bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="schedule in doctor.data.schedules" :key="schedule.id" class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ schedule.day_of_week }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ schedule.start_time }} - {{ schedule.end_time }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center justify-between">
                                                        <span>{{ schedule.location_clinic || 'Poli Reguler' }}</span>
                                                        <a 
                                                            :href="`https://wa.me/${($page.props.global?.contact?.phone || '6281234567890').replace(/[^0-9]/g, '')}?text=${encodeURIComponent(`Halo, saya ingin mendaftar ke ${doctor.data.full_name} untuk jadwal hari ${schedule.day_of_week} jam ${schedule.start_time}.`)}`" 
                                                            target="_blank"
                                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                                                        >
                                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564c.173.087.289.129.332.202.043.073.043.423-.101.827z"></path></svg>
                                                            Daftar Via WA
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div v-else class="bg-white border border-gray-200 rounded-md p-4 text-center text-gray-500 italic">
                                        Belum ada jadwal praktek yang tersedia saat ini.
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Related Doctors -->
                <div v-if="relatedDoctors.data && relatedDoctors.data.length > 0" class="mt-16">
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Dokter Spesialis Serupa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div v-for="rel in relatedDoctors.data" :key="rel.id" class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-100 overflow-hidden flex flex-col p-6 items-center text-center">
                            <div class="w-24 h-24 rounded-full overflow-hidden mb-4 border-2 border-cyan-50">
                                <img :src="rel.photo_url || 'https://ui-avatars.com/api/?name='+encodeURIComponent(rel.name)+'&color=0891b2&background=cffafe&size=256'" :alt="rel.name" class="w-full h-full object-cover" />
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ rel.full_name }}</h4>
                            <div v-if="rel.specialties && rel.specialties.length > 0" class="flex flex-wrap justify-center gap-1 mb-4">
                                <span v-for="spec in rel.specialties" :key="spec.id" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                    {{ spec.name }}
                                </span>
                            </div>
                            <p v-else class="text-sm text-gray-400 italic mb-4">Belum ada spesialisasi</p>
                            <Link :href="route('doctors.show', rel.id)" class="mt-auto inline-flex items-center px-4 py-2 border border-primary text-sm font-medium rounded-md text-primary bg-white hover:bg-primary hover:text-white transition-colors w-full justify-center">
                                Lihat Profil
                            </Link>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </PublicLayout>
</template>
