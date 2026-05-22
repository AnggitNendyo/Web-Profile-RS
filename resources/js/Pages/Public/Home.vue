<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    featuredDoctors: Object,
    centersOfExcellence: Object,
    latestArticles: Object,
    stats: Object,
    hero: Object,
});

// Animated counter logic
const counterValues = ref({ doctors: 0, patients: 0, years: 0 });
const counterStarted = ref(false);

function animateCounter(target, key, duration = 2000) {
    const start = 0;
    const end = target;
    const startTime = performance.now();
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        // Ease-out cubic
        const eased = 1 - Math.pow(1 - progress, 3);
        counterValues.value[key] = Math.floor(eased * (end - start) + start);
        if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
}

// Intersection Observer directive-like behavior via mounted
onMounted(() => {
    // Stats counter observer
    const statsEl = document.getElementById('stats-section');
    if (statsEl) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !counterStarted.value) {
                    counterStarted.value = true;
                    animateCounter(props.stats.doctors, 'doctors', 2000);
                    animateCounter(props.stats.patients, 'patients', 2500);
                    animateCounter(props.stats.years, 'years', 1500);
                }
            });
        }, { threshold: 0.3 });
        statsObserver.observe(statsEl);
    }

    // Scroll-reveal observer
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-visible');
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.animate-on-scroll').forEach((el) => {
        revealObserver.observe(el);
    });
});

const coeIcons = [
    // Heart/Cardiology
    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />',
    // Brain/Neurology
    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />',
    // Shield/Orthopedics
    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />',
    // Eye/Ophthalmology
    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />',
    // Beaker/General
    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />',
];
</script>

<template>
    <Head title="Beranda — RS Nusantara Medika">
        <meta name="description" content="RS Nusantara Medika — Rumah sakit modern dengan layanan paripurna, dokter spesialis berpengalaman, dan teknologi medis terkini di Jakarta." />
    </Head>
    <PublicLayout>
        <!-- ==================== HERO SECTION ==================== -->
        <div class="relative min-h-[600px] lg:min-h-[700px] bg-gradient-to-br from-primary-950 via-primary to-secondary overflow-hidden">
            <!-- Decorative blobs -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-primary/30 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center min-h-[600px] lg:min-h-[700px]">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center w-full py-20">
                    <!-- Text -->
                    <div class="text-center lg:text-left z-10">
                        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm text-cyan-200 text-sm font-medium mb-6 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-pulse"></span>
                            IGD 24 Jam Buka
                        </div>
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                            <span class="block mb-2">{{ hero?.title || 'Pelayanan Medis Berstandar Internasional' }}</span>
                        </h1>
                        <p class="mt-6 text-lg sm:text-xl text-cyan-100/80 max-w-xl mx-auto lg:mx-0">
                            {{ hero?.subtitle || 'RS Nusantara Medika menghadirkan layanan kesehatan paripurna dengan fasilitas modern, dokter spesialis berpengalaman, dan pelayanan yang memanusiakan manusia.' }}
                        </p>
                        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <Link :href="route('doctors.index')" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white text-primary font-bold text-base shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all duration-300 group">
                                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari Dokter
                            </Link>
                            <Link :href="route('coes.index')" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm text-white font-bold text-base border border-white/20 hover:bg-white/20 transition-all duration-300">
                                Layanan Unggulan
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </Link>
                        </div>
                    </div>
                    <!-- Hero Image -->
                    <div class="relative hidden lg:block">
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10">
                            <img class="w-full h-[480px] object-cover" src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80" alt="RS Nusantara Medika — Fasilitas Modern" />
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/40 to-transparent"></div>
                        </div>
                        <!-- Floating card -->
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-xl p-4 flex items-center gap-3 animate-float">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">Terakreditasi</p>
                                <p class="text-xs text-gray-500">Paripurna ★★★★★</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STATS SECTION ==================== -->
        <div id="stats-section" class="relative -mt-16 z-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10 grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                    <div>
                        <p class="text-4xl sm:text-5xl font-extrabold text-primary">{{ counterStarted ? counterValues.years : 0 }}<span class="text-secondary">+</span></p>
                        <p class="mt-2 text-sm font-medium text-gray-500 uppercase tracking-wider">Tahun Pengalaman</p>
                    </div>
                    <div class="border-t sm:border-t-0 sm:border-l border-gray-200 pt-6 sm:pt-0 sm:pl-8">
                        <p class="text-4xl sm:text-5xl font-extrabold text-primary">{{ counterStarted ? counterValues.doctors : 0 }}<span class="text-secondary">+</span></p>
                        <p class="mt-2 text-sm font-medium text-gray-500 uppercase tracking-wider">Dokter Spesialis</p>
                    </div>
                    <div class="border-t sm:border-t-0 sm:border-l border-gray-200 pt-6 sm:pt-0 sm:pl-8">
                        <p class="text-4xl sm:text-5xl font-extrabold text-primary">{{ counterStarted ? counterValues.patients.toLocaleString('id-ID') : 0 }}<span class="text-secondary">+</span></p>
                        <p class="mt-2 text-sm font-medium text-gray-500 uppercase tracking-wider">Pasien Terlayani</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== CENTERS OF EXCELLENCE ==================== -->
        <div class="section-padding bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <p class="text-sm font-semibold text-secondary uppercase tracking-wider mb-2">Layanan Spesialis</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Layanan Unggulan Kami</h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Pusat layanan spesialistik terpadu dengan tim medis multidisiplin dan teknologi terkini.
                    </p>
                </div>

                <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                    <div v-for="(coe, index) in centersOfExcellence.data" :key="coe.id"
                         class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                        <div class="w-14 h-14 inline-flex items-center justify-center rounded-xl bg-gradient-to-br from-primary to-secondary text-white mb-5 group-hover:scale-110 transition-transform">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" v-html="coeIcons[index % coeIcons.length]"></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ coe.title }}</h3>
                        <p class="text-gray-500 mb-5 line-clamp-3 text-sm leading-relaxed flex-grow">{{ coe.short_description }}</p>
                        <Link :href="route('coes.show', coe.slug)" class="inline-flex items-center text-primary font-semibold text-sm hover:text-secondary transition-colors group/link">
                            Pelajari Lebih Lanjut
                            <svg class="ml-1.5 w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </Link>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <Link :href="route('coes.index')" class="inline-flex items-center text-base font-semibold text-primary hover:text-secondary transition-colors">
                        Lihat semua layanan unggulan <span class="ml-1" aria-hidden="true">&rarr;</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- ==================== FEATURED DOCTORS ==================== -->
        <div class="section-padding bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <p class="text-sm font-semibold text-secondary uppercase tracking-wider mb-2">Tenaga Ahli Kami</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Dokter Spesialis Berpengalaman</h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Tim medis profesional yang siap memberikan pelayanan kesehatan optimal untuk Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="doctor in featuredDoctors.data" :key="doctor.id"
                         class="group bg-white border border-gray-100 rounded-2xl p-6 flex flex-col items-center text-center shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-28 h-28 rounded-full overflow-hidden mb-5 ring-4 ring-cyan-50 group-hover:ring-cyan-100 transition-all">
                            <img :src="doctor.photo_url || 'https://ui-avatars.com/api/?name='+encodeURIComponent(doctor.name)+'&color=0891b2&background=cffafe&size=256'" :alt="doctor.name" class="w-full h-full object-cover" />
                        </div>
                        <h3 class="text-base font-bold text-gray-900 mb-1">{{ doctor.full_name }}</h3>
                        <div class="flex flex-wrap justify-center gap-1 mb-1 min-h-[1.5rem]">
                            <span v-if="doctor.specialties && doctor.specialties.length > 0" v-for="spec in doctor.specialties" :key="spec.id" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">
                                {{ spec.name }}
                            </span>
                            <span v-else class="text-xs text-gray-400 italic">Belum ada spesialisasi</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-5">{{ doctor.experience_years }} Tahun Pengalaman</p>
                        <Link :href="route('doctors.show', doctor.id)" class="mt-auto w-full inline-flex items-center justify-center px-4 py-2.5 border-2 border-primary text-sm font-semibold rounded-xl text-primary bg-white hover:bg-primary hover:text-white transition-all duration-300">
                            Lihat Profil
                        </Link>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <Link :href="route('doctors.index')" class="inline-flex items-center px-8 py-3.5 rounded-xl bg-primary text-white font-semibold text-base shadow-md hover:bg-secondary hover:shadow-lg transition-all duration-300">
                        Lihat Semua Dokter
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- ==================== LATEST ARTICLES ==================== -->
        <div class="section-padding bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <p class="text-sm font-semibold text-secondary uppercase tracking-wider mb-2">Edukasi Kesehatan</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Artikel Kesehatan Terkini</h2>
                    <p class="mt-4 text-lg text-gray-500">
                        Informasi dan tips kesehatan dari pakar medis kami untuk gaya hidup yang lebih sehat.
                    </p>
                </div>

                <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="article in latestArticles.data" :key="article.id"
                         class="flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                        <Link :href="route('articles.show', article.slug)" class="block h-52 bg-gray-100 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1505751172876-fa1923c5c528?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" :alt="article.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </Link>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center text-xs text-gray-400 mb-3 gap-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-cyan-50 text-cyan-700 font-semibold">Edukasi</span>
                                <time :datetime="article.published_at">{{ article.published_at }}</time>
                            </div>
                            <Link :href="route('articles.show', article.slug)" class="block group/title">
                                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover/title:text-primary transition-colors line-clamp-2">{{ article.title }}</h3>
                            </Link>
                            <p class="text-sm text-gray-500 line-clamp-3 flex-grow">{{ article.content }}</p>
                            <div class="mt-5 pt-4 border-t border-gray-100 flex items-center">
                                <img :src="'https://ui-avatars.com/api/?name='+encodeURIComponent(article.author_name)+'&background=EFF6FF&color=0F4C81&size=64'" class="w-8 h-8 rounded-full mr-3 ring-2 ring-gray-100" alt="" />
                                <span class="text-sm font-medium text-gray-700">{{ article.author_name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <Link :href="route('articles.index')" class="inline-flex items-center text-base font-semibold text-primary hover:text-secondary transition-colors">
                        Baca artikel lainnya <span class="ml-1" aria-hidden="true">&rarr;</span>
                    </Link>
                </div>
            </div>
        </div>

        <!-- ==================== CTA BANNER ==================== -->
        <div class="bg-gradient-to-r from-primary to-secondary py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4">Butuh Konsultasi Medis?</h2>
                <p class="text-lg text-cyan-100 mb-8 max-w-2xl mx-auto">Hubungi kami segera untuk konsultasi dengan dokter spesialis terbaik. Layanan IGD 24 jam tersedia.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('doctors.index')" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white text-primary font-bold text-base shadow-lg hover:shadow-xl hover:bg-gray-50 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari Dokter Sekarang
                    </Link>
                    <a href="tel:119" class="inline-flex items-center justify-center px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm text-white font-bold text-base border border-white/20 hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        Hubungi IGD: 119
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
