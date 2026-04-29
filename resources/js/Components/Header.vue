<script setup>

</script>

<template>
    <header class="flex items-center justify-between px-4 sm:px-6 py-3 bg-[#171717] border-b border-white/5 shrink-0">
        <div class="flex items-center gap-3">
            <!-- Hamburger (mobile only) -->
            <button
                class="lg:hidden p-1.5 rounded-md text-white/50 hover:text-white hover:bg-white/10 transition-colors"
                @click="sidebarOpen = true"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Breadcrumb -->
            <nav class="flex items-center gap-1.5 text-sm text-white/40">
                <span class="hidden sm:inline">Dashboard</span>
                <svg class="hidden sm:block w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
                <span class="text-white/80">Overview</span>
            </nav>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-2 sm:gap-3">
            <!-- Search (hidden on small screens) -->
            <div class="relative hidden sm:block">
                <svg class="w-4 h-4 text-white/30 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    placeholder="Search"
                    class="bg-white/5 border border-white/10 rounded-md pl-9 pr-4 py-1.5 text-sm text-white/70 placeholder-white/25 w-40 lg:w-48 focus:outline-none focus:border-white/20 transition-colors"
                />
            </div>
            <!-- Search icon (mobile) -->
            <button class="sm:hidden w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/15 transition-colors">
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
            </button>
            <button class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/15 transition-colors">
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </button>
        </div>
    </header>
</template>