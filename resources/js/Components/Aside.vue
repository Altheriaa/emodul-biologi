<script setup>
    const sidebarOpen = ref(false)
    import { ref } from 'vue';
</script>

<template>
    <!-- Mobile Overlay -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 bg-black/60 z-20 lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 w-64 bg-[#171717] flex flex-col shrink-0 border-r border-white/5 transition-transform duration-300 ease-in-out',
                'lg:relative lg:translate-x-0 lg:w-56 lg:z-auto',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Brand -->
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                <span class="font-semibold text-sm tracking-wide text-white/90">Admin Dashboard</span>
                <!-- Close button (mobile only) -->
                <button
                    class="lg:hidden p-1 rounded text-white/40 hover:text-white hover:bg-white/10 transition-colors"
                    @click="sidebarOpen = false"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-0.5">
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md bg-white/10 text-white text-sm font-medium">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-white/50 hover:text-white hover:bg-white/5 text-sm transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Orders
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-white/50 hover:text-white hover:bg-white/5 text-sm transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                    </svg>
                    Products
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-white/50 hover:text-white hover:bg-white/5 text-sm transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                    Customers
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-white/50 hover:text-white hover:bg-white/5 text-sm transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                    Analytics
                </a>
            </nav>

            <!-- User Profile -->
            <div class="px-3 py-3 border-t border-white/5">
                <div class="flex items-center gap-3 px-2 py-2 rounded-md hover:bg-white/5 cursor-pointer transition-colors">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-semibold shrink-0">S</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-white/90 truncate">shadcn</p>
                        <p class="text-xs text-white/40 truncate">m@example.com</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-white/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M8 9l4-4 4 4M8 15l4 4 4-4"/>
                    </svg>
                </div>
            </div>
        </aside>
</template>
