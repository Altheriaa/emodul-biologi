<script setup>
    import { ref } from 'vue'
    import Aside from './components/sidebar/Aside.vue'; 
    import Header from './components/sidebar/Header.vue'; 

    const sidebarOpen = ref(false)
</script>

<template>
    <div class="flex h-screen bg-gray-100 text-gray-800 overflow-hidden font-sans">
        <Aside :show="sidebarOpen" @close="sidebarOpen = false" />
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <Header @open-sidebar="sidebarOpen = true" />            

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-5">
                <slot />
            </main>
        </div>
    </div>
</template>