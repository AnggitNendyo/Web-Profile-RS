<script setup>
import { ref, computed } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);

const page = usePage();

const userInitials = computed(() => {
    const name = page.props.auth.user.name || '';
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
});

const navItems = [
    { label: 'Dashboard', route: 'dashboard', icon: 'dashboard', match: 'dashboard' },
    { label: 'Dokter', route: 'admin.doctors.index', icon: 'doctors', match: 'admin.doctors.*' },
    { label: 'Artikel', route: 'admin.articles.index', icon: 'articles', match: 'admin.articles.*' },
    { label: 'Layanan Unggulan', route: 'admin.coes.index', icon: 'coes', match: 'admin.coes.*' },
    { label: 'Fasilitas', route: 'admin.facilities.index', icon: 'facilities', match: 'admin.facilities.*' },
];

const masterItems = [
    { label: 'Halaman', route: 'admin.pages.index', icon: 'pages', match: 'admin.pages.*' },
    { label: 'Spesialisasi', route: 'admin.specialties.index', match: 'admin.specialties.*' },
    { label: 'Gelar Dokter', route: 'admin.degrees.index', match: 'admin.degrees.*' },
];

const isMasterActive = computed(() => {
    return masterItems.some(item => route().current(item.match));
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50/30">
            <!-- ═══════════════════════════════════════════ -->
            <!-- TOP NAVBAR                                   -->
            <!-- ═══════════════════════════════════════════ -->
            <nav class="admin-navbar sticky top-0 z-50">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">

                        <!-- ── Left: Logo + Nav ────────────────────── -->
                        <div class="flex items-center gap-1">
                            <!-- Logo -->
                            <Link :href="route('dashboard')" class="flex items-center gap-2.5 mr-6 group">
                                <div class="navbar-logo-icon">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <span class="hidden lg:block text-sm font-bold text-white/90 group-hover:text-white transition-colors">
                                    Admin Panel
                                </span>
                            </Link>

                            <!-- Desktop Nav Links -->
                            <div class="hidden md:flex items-center gap-0.5">
                                <!-- Regular Nav Items -->
                                <Link
                                    v-for="item in navItems"
                                    :key="item.route"
                                    :href="route(item.route)"
                                    class="navbar-link"
                                    :class="{ 'navbar-link--active': route().current(item.match) }"
                                >
                                    {{ item.label }}
                                </Link>

                                <!-- Data Master Dropdown -->
                                <div class="relative">
                                    <Dropdown align="left" width="48" content-classes="py-1 bg-white/95 backdrop-blur-xl border border-gray-200/60">
                                        <template #trigger>
                                            <button
                                                type="button"
                                                class="navbar-link inline-flex items-center gap-1"
                                                :class="{ 'navbar-link--active': isMasterActive }"
                                            >
                                                Data Master
                                                <svg class="h-3.5 w-3.5 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </template>

                                        <template #content>
                                            <DropdownLink
                                                v-for="mItem in masterItems"
                                                :key="mItem.route"
                                                :href="route(mItem.route)"
                                            >
                                                <span class="flex items-center gap-2">
                                                    <span class="h-1.5 w-1.5 rounded-full" :class="route().current(mItem.match) ? 'bg-blue-500' : 'bg-gray-300'"></span>
                                                    {{ mItem.label }}
                                                </span>
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>

                                <!-- Settings (Separate) -->
                                <Link
                                    :href="route('admin.settings.index')"
                                    class="navbar-link"
                                    :class="{ 'navbar-link--active': route().current('admin.settings.*') }"
                                >
                                    <svg class="h-4 w-4 mr-1 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Pengaturan
                                </Link>
                            </div>
                        </div>

                        <!-- ── Right: User Profile ─────────────────── -->
                        <div class="hidden md:flex items-center gap-3">
                            <Dropdown align="right" width="48" content-classes="py-1 bg-white/95 backdrop-blur-xl border border-gray-200/60">
                                <template #trigger>
                                    <button type="button" class="navbar-user-btn group">
                                        <div class="navbar-avatar">
                                            {{ userInitials }}
                                        </div>
                                        <div class="hidden lg:block text-left">
                                            <p class="text-xs font-semibold text-white/90 group-hover:text-white leading-tight">
                                                {{ $page.props.auth.user.name }}
                                            </p>
                                            <p class="text-[10px] text-white/50 leading-tight">Administrator</p>
                                        </div>
                                        <svg class="h-3.5 w-3.5 text-white/40 group-hover:text-white/60 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="px-4 py-2.5 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-800">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-xs text-gray-500">{{ $page.props.auth.user.email }}</p>
                                    </div>
                                    <DropdownLink :href="route('profile.edit')">
                                        <span class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                            Profil Saya
                                        </span>
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        <span class="flex items-center gap-2 text-red-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                            </svg>
                                            Keluar
                                        </span>
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- ── Mobile Hamburger ────────────────────── -->
                        <div class="flex items-center md:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-lg p-2 text-white/70 hover:text-white hover:bg-white/10 transition-all duration-200"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════ -->
                <!-- MOBILE NAV MENU                              -->
                <!-- ═══════════════════════════════════════════ -->
                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 -translate-y-2"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-2"
                >
                    <div v-show="showingNavigationDropdown" class="md:hidden border-t border-white/10">
                        <div class="bg-slate-800/95 backdrop-blur-xl pb-3 pt-2 space-y-1">
                            <ResponsiveNavLink
                                v-for="item in navItems"
                                :key="item.route"
                                :href="route(item.route)"
                                :active="route().current(item.match)"
                            >
                                {{ item.label }}
                            </ResponsiveNavLink>

                            <!-- Data Master Group -->
                            <div class="pt-3 pb-1 border-t border-white/10">
                                <div class="px-4 text-[10px] font-bold text-white/30 uppercase tracking-widest">
                                    Data Master
                                </div>
                                <div class="mt-1.5 space-y-1">
                                    <ResponsiveNavLink
                                        v-for="mItem in masterItems"
                                        :key="mItem.route"
                                        :href="route(mItem.route)"
                                        :active="route().current(mItem.match)"
                                        class="pl-8"
                                    >
                                        {{ mItem.label }}
                                    </ResponsiveNavLink>
                                </div>
                            </div>

                            <ResponsiveNavLink
                                :href="route('admin.settings.index')"
                                :active="route().current('admin.settings.*')"
                            >
                                Pengaturan Web
                            </ResponsiveNavLink>
                        </div>

                        <!-- User Info Mobile -->
                        <div class="bg-slate-900/80 backdrop-blur-xl border-t border-white/10 pb-3 pt-4">
                            <div class="flex items-center px-4 gap-3">
                                <div class="navbar-avatar navbar-avatar--mobile">
                                    {{ userInitials }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white/90">{{ $page.props.auth.user.name }}</div>
                                    <div class="text-xs text-white/50">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')">
                                    Profil Saya
                                </ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                    Keluar
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </Transition>
            </nav>

            <!-- Page Heading -->
            <header class="admin-page-header" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* ═══════════════════════════════════════════════════════
   ADMIN NAVBAR STYLES
   Modern dark gradient with glassmorphism effects
   ═══════════════════════════════════════════════════════ */

.admin-navbar {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #1e1b4b 100%);
    box-shadow:
        0 1px 3px rgba(0, 0, 0, 0.3),
        0 4px 12px rgba(0, 0, 0, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.05);
}

/* Subtle top accent line */
.admin-navbar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg,
        #3b82f6 0%,
        #6366f1 25%,
        #8b5cf6 50%,
        #6366f1 75%,
        #3b82f6 100%
    );
    opacity: 0.8;
}

/* Logo icon circle */
.navbar-logo-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.group:hover .navbar-logo-icon {
    transform: scale(1.05);
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.5);
}

/* ── Navigation Links ──────────────────────────── */
.navbar-link {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.65);
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
    cursor: pointer;
    border: 1px solid transparent;
}
.navbar-link:hover {
    color: rgba(255, 255, 255, 0.95);
    background: rgba(255, 255, 255, 0.08);
}
.navbar-link--active {
    color: #fff !important;
    background: rgba(99, 102, 241, 0.2) !important;
    border-color: rgba(99, 102, 241, 0.3) !important;
    box-shadow: 0 0 12px rgba(99, 102, 241, 0.15);
}

/* ── User Button ───────────────────────────────── */
.navbar-user-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 4px 10px 4px 4px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    background: rgba(255, 255, 255, 0.04);
    transition: all 0.2s ease;
    cursor: pointer;
}
.navbar-user-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.15);
}

/* ── Avatar ────────────────────────────────────── */
.navbar-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 6px rgba(99, 102, 241, 0.3);
    flex-shrink: 0;
}
.navbar-avatar--mobile {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    font-size: 13px;
}

/* ── Page Header ───────────────────────────────── */
.admin-page-header {
    background: white;
    border-bottom: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

/* ── Mobile Nav Link Overrides (dark background) ── */
:deep(.border-l-4.border-transparent) {
    color: rgba(255, 255, 255, 0.7) !important;
    border-color: transparent !important;
}
:deep(.border-l-4.border-transparent:hover) {
    color: rgba(255, 255, 255, 0.95) !important;
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(255, 255, 255, 0.2) !important;
}
:deep(.border-l-4.border-indigo-400) {
    color: #c7d2fe !important;
    background: rgba(99, 102, 241, 0.15) !important;
    border-color: #818cf8 !important;
}
</style>
