<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

defineProps({
    page: Object,
});
</script>

<template>
    <Head>
        <title>{{ page.title }}</title>
        <meta name="description" :content="`Halaman informasi ${page.title} di RS Nusantara Medika.`" />
    </Head>

    <PublicLayout>
        <!-- Header Page -->
        <div class="bg-primary/5 py-12 border-b border-primary/10">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    {{ page.title }}
                </h1>
                <div class="mt-4 w-24 h-1 bg-gradient-to-r from-primary to-secondary mx-auto rounded-full"></div>
            </div>
        </div>

        <!-- Content Page -->
        <div class="py-16 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="prose prose-lg prose-blue max-w-none prose-headings:text-primary prose-a:text-secondary hover:prose-a:text-primary" v-html="page.content"></div>
            </div>
        </div>
    </PublicLayout>
</template>
