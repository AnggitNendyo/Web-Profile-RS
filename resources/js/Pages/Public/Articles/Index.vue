<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    articles: Object,
});
</script>

<template>
    <Head title="Artikel Kesehatan">
        <meta name="description" content="Baca artikel edukasi kesehatan, tips gaya hidup sehat, dan informasi medis terkini dari tim dokter RS Nusantara Medika." />
    </Head>
    <PublicLayout>
        <div class="bg-gray-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Artikel Edukasi Kesehatan</h1>
                    <p class="mt-4 text-xl text-gray-500">Temukan berbagai informasi kesehatan terpercaya, tips gaya hidup sehat, dan kabar medis terkini.</p>
                </div>

                <div v-if="articles.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="article in articles.data" :key="article.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
                        <Link :href="route('articles.show', article.slug)" class="block h-48 bg-gray-100 overflow-hidden">
                            <img 
                                v-if="article.cover_image" 
                                :src="article.cover_image" 
                                :alt="article.title" 
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" 
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                        </Link>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center text-sm text-gray-500 mb-3 space-x-3">
                                <!-- Category -->
                                <span v-if="article.category" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ article.category.name }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ article.published_at }}
                                </span>
                            </div>
                            <Link :href="route('articles.show', article.slug)" class="block group">
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary transition-colors">{{ article.title }}</h3>
                            </Link>
                            <!-- Use excerpt instead of raw content -->
                            <p class="text-gray-600 mb-6 line-clamp-3 text-sm">{{ article.excerpt || article.content?.replace(/<[^>]*>/g, '').substring(0, 160) + '...' }}</p>
                            <div class="mt-auto flex items-center justify-between border-t border-gray-100 pt-4">
                                <div class="flex items-center">
                                    <img :src="'https://ui-avatars.com/api/?name='+encodeURIComponent(article.author_name)+'&background=random'" class="w-8 h-8 rounded-full mr-2" alt="" />
                                    <span class="text-sm font-medium text-gray-900">{{ article.author_name }}</span>
                                </div>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ article.reading_time }} min
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="articles.meta && articles.meta.last_page > 1" class="mt-16 flex justify-center">
                    <div class="flex flex-wrap justify-center gap-2">
                        <template v-for="(link, p) in articles.meta.links" :key="p">
                            <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded bg-white" v-html="link.label" />
                            <Link v-else class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded focus:border-primary focus:text-primary transition-colors" :class="link.active ? 'bg-primary text-white border-primary hover:bg-secondary' : 'bg-white text-gray-700 hover:bg-gray-50'" :href="link.url" v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
