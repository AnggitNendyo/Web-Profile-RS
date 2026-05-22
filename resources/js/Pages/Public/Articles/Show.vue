<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, onMounted, onUnmounted, computed } from 'vue';

const props = defineProps({
    article: Object,
    relatedArticles: Object,
    categories: Array,
    latestArticles: Object,
});

const processedContent = ref('');
const tableOfContents = ref([]);
const readingProgress = ref(0);
const currentUrl = typeof window !== 'undefined' ? window.location.href : '';

const shareUrls = computed(() => ({
    facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`,
    twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl)}&text=${encodeURIComponent(props.article.data.title)}`,
    whatsapp: `https://api.whatsapp.com/send?text=${encodeURIComponent(props.article.data.title + ' ' + currentUrl)}`,
}));

const updateProgress = () => {
    const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
    if (totalHeight <= 0) return;
    const progress = (window.scrollY / totalHeight) * 100;
    readingProgress.value = Math.min(100, Math.max(0, progress));
};

const activeHeadingId = ref('');

const setupIntersectionObserver = () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                activeHeadingId.value = entry.target.id;
            }
        });
    }, { rootMargin: '0px 0px -80% 0px' });

    setTimeout(() => {
        const headings = document.querySelectorAll('.article-content h2, .article-content h3');
        headings.forEach(h => observer.observe(h));
    }, 100);
};

const scrollToHeading = (id) => {
    const el = document.getElementById(id);
    if (el) {
        // Offset for fixed header if any
        const y = el.getBoundingClientRect().top + window.scrollY - 100;
        window.scrollTo({ top: y, behavior: 'smooth' });
    }
};

onMounted(() => {
    if (props.article.data.content) {
        // Parse the HTML to extract headings and add IDs
        const parser = new DOMParser();
        const doc = parser.parseFromString(props.article.data.content, 'text/html');
        const headings = doc.querySelectorAll('h2, h3');
        
        const toc = [];
        headings.forEach((heading, index) => {
            // Generate a safe ID based on text, or fallback to index
            const safeId = heading.textContent.toLowerCase().replace(/[^a-z0-9]+/g, '-') || `heading-${index}`;
            // Ensure unique ID
            const id = toc.find(t => t.id === safeId) ? `${safeId}-${index}` : safeId;
            
            heading.id = id;
            
            toc.push({
                id: id,
                text: heading.textContent,
                level: heading.tagName.toLowerCase(),
            });
        });
        
        tableOfContents.value = toc;
        processedContent.value = doc.body.innerHTML;
        
        setupIntersectionObserver();
    }
    
    window.addEventListener('scroll', updateProgress);
    // Initial check
    updateProgress();
});

onUnmounted(() => {
    window.removeEventListener('scroll', updateProgress);
});
</script>

<template>
    <Head :title="article.data.meta_title || article.data.title">
        <meta v-if="article.data.meta_description" name="description" :content="article.data.meta_description" />
        <meta v-else-if="article.data.excerpt" name="description" :content="article.data.excerpt" />
    </Head>
    <PublicLayout>
        <!-- Reading Progress Bar -->
        <div class="fixed top-0 left-0 w-full h-1.5 bg-gray-200 z-50">
            <div class="h-full bg-indigo-600 transition-all duration-150 ease-out" :style="{ width: readingProgress + '%' }"></div>
        </div>

        <div class="bg-gray-50/50 py-12 lg:py-20 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Main Layout Grid -->
                <div class="flex flex-col lg:flex-row gap-12 relative items-start">
                    
                    <!-- Left Sidebar (Floating Share - Desktop) -->
                    <aside class="hidden lg:flex flex-col items-center gap-6 sticky top-32 w-16 shrink-0 z-10">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg);">Bagikan</span>
                        <div class="w-px h-12 bg-gray-200"></div>
                        <a :href="shareUrls.facebook" target="_blank" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-600 hover:shadow-md transition-all group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" /></svg>
                        </a>
                        <a :href="shareUrls.twitter" target="_blank" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-sky-500 hover:border-sky-500 hover:shadow-md transition-all group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a :href="shareUrls.whatsapp" target="_blank" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-green-500 hover:border-green-500 hover:shadow-md transition-all group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 21.122A9.956 9.956 0 016.924 19.5l-4.5 1.18 1.203-4.382A9.96 9.96 0 012 11c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10zm-3.23-4.51c.42.24 1.45.69 2.94.69 4.3 0 7.8-3.5 7.8-7.8s-3.5-7.8-7.8-7.8-7.8 3.5-7.8 7.8c0 1.58.5 2.76.78 3.23l-.7 2.54 2.6-.68c.45.24.96.42 1.58.42h.01c-.01 0-.01 0-.02 0zm5.63-6.52c-.17-.4-.36-.41-.53-.42-.16-.01-.34-.01-.52-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.3 0 1.35.98 2.66 1.12 2.84.14.19 1.94 2.96 4.7 4.08 1.83.74 2.52.8 3.51.68 1-.12 3.21-1.31 3.66-2.58.45-1.27.45-2.36.31-2.58-.13-.23-.5-.36-1.04-.63-.54-.27-3.21-1.58-3.71-1.76-.5-.18-.86-.27-1.22.27-.36.54-1.4 1.76-1.71 2.12-.31.36-.63.41-1.17.14-.54-.27-2.29-.85-4.36-2.7-.16-.14-.3-.3-.41-.47-.28-.43-.03-.66.24-.93.18-.18.36-.45.54-.68.18-.23.24-.36.36-.63.12-.27.06-.5-.03-.63-.09-.13-.71-1.72-1-2.36z"/></svg>
                        </a>
                    </aside>

                    <!-- Main Article Content -->
                    <article class="flex-1 min-w-0 bg-white rounded-3xl shadow-sm p-6 sm:p-10 lg:p-16">
                        
                        <!-- Breadcrumbs -->
                        <nav class="flex mb-8" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm">
                                <li><Link :href="route('home')" class="text-gray-500 hover:text-indigo-600 transition">Beranda</Link></li>
                                <li class="text-gray-300">/</li>
                                <li><Link :href="route('articles.index')" class="text-gray-500 hover:text-indigo-600 transition">Artikel</Link></li>
                                <li class="text-gray-300">/</li>
                                <li class="text-gray-900 font-medium truncate max-w-[150px] sm:max-w-[300px]">{{ article.data.title }}</li>
                            </ol>
                        </nav>

                        <!-- Header -->
                        <header class="mb-12">
                            <!-- Category badge -->
                            <div v-if="article.data.category" class="mb-6 flex gap-2 flex-wrap">
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200">
                                    {{ article.data.category.name }}
                                </span>
                            </div>
                            
                            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl md:text-5xl mb-8 leading-tight tracking-tight">{{ article.data.title }}</h1>
                            
                            <!-- Author & Meta info -->
                            <div class="flex items-center gap-4 text-sm text-gray-600 border-b border-gray-100 pb-8">
                                <img :src="'https://ui-avatars.com/api/?name='+encodeURIComponent(article.data.author_name)+'&background=random'" class="w-12 h-12 rounded-full ring-2 ring-white shadow-sm" alt="" />
                                <div>
                                    <p class="font-bold text-gray-900 text-base mb-1">{{ article.data.author_name }}</p>
                                    <div class="flex items-center gap-2 text-xs sm:text-sm">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ article.data.published_at }}
                                        </span>
                                        <span class="text-gray-300">•</span>
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ article.data.reading_time }} menit baca
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <!-- Cover Image from database -->
                        <div v-if="article.data.cover_image" class="rounded-2xl overflow-hidden mb-12 shadow-md">
                            <img :src="article.data.cover_image" :alt="article.data.title" class="w-full h-auto object-cover max-h-[600px] hover:scale-105 transition-transform duration-700 ease-out" />
                        </div>

                        <!-- Mobile Table of Contents (Collapsible) -->
                        <div v-if="tableOfContents.length > 0" class="lg:hidden mb-10 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                Daftar Isi
                            </h3>
                            <ul class="space-y-3">
                                <li v-for="item in tableOfContents" :key="item.id" :class="{ 'pl-4': item.level === 'h3' }">
                                    <button 
                                        @click="scrollToHeading(item.id)" 
                                        class="text-left text-sm hover:text-indigo-600 transition-colors"
                                        :class="activeHeadingId === item.id ? 'text-indigo-600 font-semibold' : 'text-gray-600'"
                                    >
                                        {{ item.text }}
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Article Content (with inline base64 images and typography) -->
                        <div class="prose prose-lg prose-indigo max-w-none prose-img:rounded-xl prose-img:shadow-sm prose-headings:scroll-mt-24 article-content" v-html="processedContent"></div>
                        
                        <!-- Author Footer Section -->
                        <div class="mt-16 bg-indigo-50/50 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start gap-6 border border-indigo-100">
                            <img :src="'https://ui-avatars.com/api/?name='+encodeURIComponent(article.data.author_name)+'&background=random&size=128'" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full ring-4 ring-white shadow-sm" alt="" />
                            <div class="text-center sm:text-left">
                                <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Ditulis oleh</h4>
                                <p class="text-xl font-bold text-gray-900 mb-2">{{ article.data.author_name }}</p>
                                <p class="text-gray-600 text-sm leading-relaxed">Tim medis dan redaksi RS Nusantara Medika berkomitmen untuk memberikan informasi kesehatan yang akurat, terpercaya, dan mudah dipahami oleh masyarakat.</p>
                            </div>
                        </div>

                    </article>

                    <!-- Right Sidebar (Desktop) -->
                    <aside class="hidden lg:flex flex-col gap-8 w-80 shrink-0">
                        <!-- Table of Contents -->
                        <div class="sticky top-32" v-if="tableOfContents.length > 0">
                            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                    Daftar Isi
                                </h3>
                                <nav class="max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                                    <ul class="space-y-3 relative before:absolute before:inset-y-0 before:left-0 before:w-0.5 before:bg-gray-100">
                                        <li v-for="item in tableOfContents" :key="item.id" 
                                            class="relative pl-4"
                                            :class="{ 'ml-4': item.level === 'h3' }">
                                            <!-- Indicator line for active item -->
                                            <div v-if="activeHeadingId === item.id" class="absolute left-[-2px] sm:left-0 top-0 bottom-0 w-0.5 bg-indigo-600 transition-all"></div>
                                            
                                            <button 
                                                @click="scrollToHeading(item.id)" 
                                                class="text-left text-sm leading-snug hover:text-indigo-600 transition-colors w-full"
                                                :class="activeHeadingId === item.id ? 'text-indigo-600 font-semibold' : 'text-gray-500'"
                                            >
                                                {{ item.text }}
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>

                        <!-- Top Categories -->
                        <div v-if="categories && categories.length > 0" class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100" :class="{ 'sticky top-32': tableOfContents.length === 0 }">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                Topik Populer
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <Link v-for="cat in categories" :key="cat.id" :href="route('articles.index', { category: cat.slug })" class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-50 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200 hover:border-indigo-200 transition-colors">
                                    {{ cat.name }}
                                    <span class="ml-1.5 bg-gray-200 text-gray-600 py-0.5 px-1.5 rounded-full text-[10px]">{{ cat.articles_count }}</span>
                                </Link>
                            </div>
                        </div>

                        <!-- Latest Articles Widget -->
                        <div v-if="latestArticles && latestArticles.data.length > 0" class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100" :class="{ 'sticky top-32': tableOfContents.length === 0 && (!categories || categories.length === 0) }">
                            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Artikel Terbaru
                            </h3>
                            <div class="space-y-4">
                                <Link v-for="latest in latestArticles.data" :key="latest.id" :href="route('articles.show', latest.slug)" class="group flex gap-3">
                                    <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0 bg-gray-100">
                                        <img v-if="latest.cover_image" :src="latest.cover_image" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                    </div>
                                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                                        <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-indigo-600 transition-colors leading-snug">{{ latest.title }}</h4>
                                        <p class="text-xs text-gray-400 mt-1">{{ latest.published_at }}</p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </aside>
                </div>

                <!-- Related Articles Section -->
                <div v-if="relatedArticles && relatedArticles.data.length > 0" class="mt-20 lg:mt-32 border-t border-gray-200 pt-16">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Artikel Terkait</h2>
                        <Link :href="route('articles.index')" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 text-sm transition">
                            Lihat semua <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </Link>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="related in relatedArticles.data" :key="related.id" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col group">
                            <Link :href="route('articles.show', related.slug)" class="block h-48 bg-gray-100 overflow-hidden">
                                <img 
                                    v-if="related.cover_image" 
                                    :src="related.cover_image" 
                                    :alt="related.title" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                />
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                </div>
                            </Link>
                            <div class="p-6 flex flex-col flex-grow">
                                <div class="flex items-center text-xs text-gray-500 mb-3 space-x-3">
                                    <span v-if="related.category" class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-indigo-50 text-indigo-700">
                                        {{ related.category.name }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ related.published_at }}
                                    </span>
                                </div>
                                <Link :href="route('articles.show', related.slug)" class="block">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors line-clamp-2">{{ related.title }}</h3>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Floating Share Bar -->
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-center gap-4 z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <a :href="shareUrls.facebook" target="_blank" class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" /></svg>
            </a>
            <a :href="shareUrls.twitter" target="_blank" class="w-10 h-10 rounded-full bg-sky-50 text-sky-500 flex items-center justify-center hover:bg-sky-100 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
            </a>
            <a :href="shareUrls.whatsapp" target="_blank" class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center hover:bg-green-100 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 21.122A9.956 9.956 0 016.924 19.5l-4.5 1.18 1.203-4.382A9.96 9.96 0 012 11c0-5.514 4.486-10 10-10s10 4.486 10 10-4.486 10-10 10zm-3.23-4.51c.42.24 1.45.69 2.94.69 4.3 0 7.8-3.5 7.8-7.8s-3.5-7.8-7.8-7.8-7.8 3.5-7.8 7.8c0 1.58.5 2.76.78 3.23l-.7 2.54 2.6-.68c.45.24.96.42 1.58.42h.01c-.01 0-.01 0-.02 0zm5.63-6.52c-.17-.4-.36-.41-.53-.42-.16-.01-.34-.01-.52-.01-.18 0-.48.07-.73.34-.25.27-.96.94-.96 2.3 0 1.35.98 2.66 1.12 2.84.14.19 1.94 2.96 4.7 4.08 1.83.74 2.52.8 3.51.68 1-.12 3.21-1.31 3.66-2.58.45-1.27.45-2.36.31-2.58-.13-.23-.5-.36-1.04-.63-.54-.27-3.21-1.58-3.71-1.76-.5-.18-.86-.27-1.22.27-.36.54-1.4 1.76-1.71 2.12-.31.36-.63.41-1.17.14-.54-.27-2.29-.85-4.36-2.7-.16-.14-.3-.3-.41-.47-.28-.43-.03-.66.24-.93.18-.18.36-.45.54-.68.18-.23.24-.36.36-.63.12-.27.06-.5-.03-.63-.09-.13-.71-1.72-1-2.36z"/></svg>
            </a>
        </div>
    </PublicLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 20px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background-color: #d1d5db;
}
/* Ensure mobile spacing for floating bottom bar */
@media (max-width: 1024px) {
    .bg-gray-50\/50 {
        padding-bottom: 6rem;
    }
}
</style>
