<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
defineEmits(['open-sidebar']);

const page = usePage();
const user = computed(() => page.props.auth.user);

const profile = () => {
    if (user.value.role === 'mahasiswa') {
        router.visit('/mahasiswa/settings');
    } else if (user.value.role === 'dosen') {
        router.visit('/dosen/settings');
    } else if (user.value.role === 'admin') {
        router.visit('/admin/settings');
    }
}
</script>

<template>
    <header class="flex items-center justify-between px-4 sm:px-6 py-3 bg-white border-b border-gray-200 shrink-0 shadow-sm">
        <div class="flex items-center gap-3">
            <!-- Hamburger (mobile only) -->
            <button
                class="lg:hidden p-1.5 rounded-md text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition-colors"
                @click="$emit('open-sidebar')"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-1.5 text-sm text-gray-400">
                <span class="hidden sm:inline">Dashboard</span>
                <svg class="hidden sm:block w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
                <span class="text-gray-700 font-medium">Overview</span>
            </nav>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Search (hidden on small screens) -->
            <div class="relative hidden sm:block">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    placeholder="Cari..."
                    class="bg-gray-100 border border-gray-200 rounded-lg pl-9 pr-4 py-1.5 text-sm text-gray-700 placeholder-gray-400 w-40 lg:w-48 focus:outline-none focus:border-green-400 focus:ring-1 focus:ring-green-200 transition-colors"
                />
            </div>
            <!-- Search icon (mobile) -->
            <button class="sm:hidden w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </button>
            <!-- Profile button -->
            <button @click="profile" class="w-8 h-8 rounded-full bg-green-700 flex items-center justify-center hover:bg-green-800 transition-colors text-white text-xs font-semibold">
                {{ user?.name?.charAt(0) || 'U' }}
            </button>
        </div>
    </header>
</template>