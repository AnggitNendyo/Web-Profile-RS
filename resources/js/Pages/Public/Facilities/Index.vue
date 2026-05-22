<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    facilitiesGrouped: Array,
});
</script>

<template>
    <Head title="Fasilitas Medis">
        <meta name="description" content="Jelajahi fasilitas medis modern RS Nusantara Medika: kamar rawat inap premium, laboratorium canggih, dan peralatan diagnostik terkini." />
    </Head>
    <PublicLayout>
        <div class="bg-primary/5 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Fasilitas Medis & Teknologi</h1>
                    <p class="mt-4 text-xl text-gray-500">RS Nusantara Medika dilengkapi dengan fasilitas medis modern dan teknologi diagnostik terkini untuk memastikan akurasi dan kenyamanan perawatan Anda.</p>
                </div>

                <div v-for="(group, groupIndex) in facilitiesGrouped" :key="groupIndex" class="mb-16 last:mb-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 pb-4 border-b-2 border-primary/20 inline-block">{{ group.category }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div v-for="facility in group.items" :key="facility.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
                            <h3 class="text-xl font-bold text-gray-900 mb-3">{{ facility.name }}</h3>
                            <p class="text-gray-600 mb-6 flex-grow">{{ facility.description }}</p>
                            
                            <div v-if="facility.features && facility.features.length > 0" class="mt-auto pt-4 border-t border-gray-100">
                                <ul class="space-y-2">
                                    <li v-for="(feature, idx) in facility.features" :key="idx" class="flex items-start text-sm text-gray-600">
                                        <svg class="h-5 w-5 text-cyan-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        {{ feature.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
