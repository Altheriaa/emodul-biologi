<script setup>
import Layout from '../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

const OpenFlipping = () => {
    router.visit("/mahasiswa/pembelajaran/materi/flipping-book");
}

</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-2 sm:mb-4">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Quiz E-Modul Anatomi Tumbuhan</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Quiz sebagai evaluasi akhir pembelajaran e-modul anatomi tumbuhan</p>
            </div>
        </div>
    </Layout>
</template>

s