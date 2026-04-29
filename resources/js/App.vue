<script setup>
    import { ref } from 'vue'
    import Aside from './Components/Aside.vue'; 
    import Header from './Components/Header.vue'; 

</script>

<template>
    <div class="flex h-screen bg-[#0f0f0f] text-white overflow-hidden font-sans">
        <Aside />
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <Header />            

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>