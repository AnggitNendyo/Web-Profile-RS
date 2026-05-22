<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const global = computed(() => page.props.global);

const isMobileMenuOpen = ref(false);

const navLinks = [
    { name: 'Beranda', routeName: 'home' },
    { name: 'Cari Dokter', routeName: 'doctors.index' },
    { name: 'Layanan Unggulan', routeName: 'coes.index' },
    { name: 'Fasilitas', routeName: 'facilities.index' },
    { name: 'Panduan Pasien', routeName: 'pages.show', routeParams: { page: 'panduan-pasien' } },
    { name: 'Artikel', routeName: 'articles.index' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col font-sans text-gray-800">
        <!-- Top Bar -->
        <div class="bg-primary text-white text-sm py-2 px-4 sm:px-6 lg:px-8 hidden md:block">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-6">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ global?.contact?.phone || '(021) 1500-123' }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ global?.contact?.email || 'info@rsnusantaramedika.id' }}
                    </span>
                </div>
                <div>
                    <span class="flex items-center font-bold text-red-100 bg-red-600 px-3 py-1 rounded-full text-xs">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        IGD 24 Jam: {{ global?.contact?.emergency || '119' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <Link :href="route('home')" class="flex items-center">
                            <svg class="w-10 h-10 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                            <span class="ml-3 text-xl font-bold text-gray-900 tracking-tight">{{ global?.hospital_name || 'RS Nusantara Medika' }}</span>
                        </Link>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <Link v-for="link in navLinks" :key="link.name" :href="route(link.routeName, link.routeParams || {})" 
                            class="text-sm font-medium transition-colors"
                            :class="route().current(link.routeName) || (link.routeName !== 'home' && route().current(link.routeName.split('.')[0] + '.*')) ? 'text-primary border-b-2 border-primary py-7' : 'text-gray-600 hover:text-primary py-7'">
                            {{ link.name }}
                        </Link>
                        <Link :href="route('login')" class="ml-4 px-5 py-2.5 rounded-lg bg-primary text-white text-sm font-medium hover:bg-secondary transition-colors shadow-sm">
                            Portal Pasien
                        </Link>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center lg:hidden">
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="!isMobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-show="isMobileMenuOpen" class="lg:hidden bg-white border-t border-gray-100 shadow-lg absolute w-full left-0 z-40">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <Link v-for="link in navLinks" :key="link.name" :href="route(link.routeName, link.routeParams || {})"
                        @click="isMobileMenuOpen = false"
                        class="block px-3 py-2 rounded-md text-base font-medium"
                        :class="route().current(link.routeName) || (link.routeName !== 'home' && route().current(link.routeName.split('.')[0] + '.*')) ? 'bg-primary/10 text-primary' : 'text-gray-700 hover:bg-gray-50 hover:text-primary'">
                        {{ link.name }}
                    </Link>
                    <Link :href="route('login')" class="block w-full text-center mt-4 px-3 py-3 rounded-md text-base font-medium bg-primary text-white hover:bg-secondary">
                        Portal Pasien
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col relative w-full">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white mt-auto">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-1">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 text-cyan-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg>
                            <span class="ml-2 text-xl font-bold tracking-tight">{{ global?.hospital_name || 'RS Nusantara Medika' }}</span>
                        </div>
                        <p class="text-gray-400 text-sm mb-4">
                            Melayani dengan hati, mengutamakan keselamatan pasien, dan memberikan perawatan medis terbaik dengan teknologi terkini.
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-semibold text-gray-200 tracking-wider uppercase mb-4">Layanan Medis</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link :href="route('doctors.index')" class="hover:text-white transition">Cari Dokter</Link></li>
                            <li><Link :href="route('coes.index')" class="hover:text-white transition">Layanan Unggulan</Link></li>
                            <li><Link :href="route('facilities.index')" class="hover:text-white transition">Fasilitas & Teknologi</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-200 tracking-wider uppercase mb-4">Informasi Pasien</h3>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li><Link :href="route('pages.show', 'panduan-pasien')" class="hover:text-white transition">Panduan Pasien</Link></li>
                            <li><Link :href="route('pages.show', 'hak-dan-kewajiban-pasien')" class="hover:text-white transition">Hak & Kewajiban Pasien</Link></li>
                            <li><Link :href="route('pages.show', 'jadwal-dokter')" class="hover:text-white transition">Jadwal Dokter</Link></li>
                            <li><Link :href="route('pages.show', 'karir')" class="hover:text-white transition">Karir</Link></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-200 tracking-wider uppercase mb-4">Hubungi Kami</h3>
                        <ul class="space-y-3 text-sm text-gray-400">
                            <li class="flex items-start">
                                <svg class="flex-shrink-0 h-5 w-5 text-cyan-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ global?.contact?.address || 'Jl. Kesehatan No. 123, Jakarta Selatan' }}</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-cyan-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>{{ global?.contact?.phone || '(021) 1500-123' }}</span>
                            </li>
                            <li class="flex items-center text-red-400 font-semibold">
                                <svg class="flex-shrink-0 h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <span>IGD: {{ global?.contact?.emergency || '119' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Copyright Bar -->
            <div class="border-t border-gray-800">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-500">&copy; {{ new Date().getFullYear() }} {{ global?.hospital_name || 'RS Nusantara Medika' }}. Semua hak dilindungi.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-500 hover:text-cyan-400 transition" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-cyan-400 transition" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                        </a>
                        <a href="#" class="text-gray-500 hover:text-cyan-400 transition" aria-label="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
