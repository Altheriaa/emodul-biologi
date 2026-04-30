<script setup>
import Layout from '../App.vue';
import { Button } from '@/components/ui/button';

import { Check, ArrowRight, StickyNote } from 'lucide-vue-next';
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">Daftar Materi E-Modul Anatomi Tumbuhan</p>
                <p class="text-xs sm:text-sm text-emerald-400 mt-1">Daftar materi yang akan dipelajari pada mata kuliah Anatomi Tumbuhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- grid 1 -->
            <div class="col-span-1 row-span-2 bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <img src="/asset/dashboard_overview.png" alt="" class="shadow-lg w-full h-auto rounded-xl mb-5">

                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight mb-3">Anatomi Tumbuhan</h2>

                <p class="text-xs sm:text-base text-gray-400 mb-4">Modul ini dirancang untuk memberikan pemahaman komprehensif mengenai struktur internal tumbuhan, 
                    prinsip-prinsip dasar proses fisiologis, serta aplikasinya dalam berbagai aspek botani. 
                    Materi disajikan secara interaktif dengan ilustrasi, diagram, dan contoh-contoh nyata
                </p>
                <hr class="border-white/10 mt-2 mb-5">
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-2">
                        <StickyNote class="w-4 h-4" />
                        <span class="text-xs sm:text-sm text-white">45 Halaman</span>
                    </div>
                    <Button variant="outline" class="text-xs sm:text-sm justify-self-end border-emerald-500/50 text-emerald-400 hover:bg-emerald-500/10">
                        <ArrowRight class="w-4 h-4 sm:w-2 sm:h-2" />
                        Mulai Belajar
                    </Button>
                </div>
            </div>
            <!-- grid 2 -->
            <div class="col-span-1 row-span-2 bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <img src="/asset/dashboard_overview.png" alt="" class="shadow-lg w-full h-auto rounded-xl mb-5">

                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight mb-3">Anatomi Tumbuhan</h2>

                <p class="text-xs sm:text-base text-gray-400 mb-4">Modul ini dirancang untuk memberikan pemahaman komprehensif mengenai struktur internal tumbuhan, 
                    prinsip-prinsip dasar proses fisiologis, serta aplikasinya dalam berbagai aspek botani. 
                    Materi disajikan secara interaktif dengan ilustrasi, diagram, dan contoh-contoh nyata
                </p>
                <hr class="border-white/10 mt-2 mb-5">
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-2">
                        <StickyNote class="w-4 h-4" />
                        <span class="text-xs sm:text-sm text-white">45 Halaman</span>
                    </div>
                    <Button variant="outline" class="text-xs sm:text-sm justify-self-end border-emerald-500/50 text-emerald-400 hover:bg-emerald-500/10">
                        <ArrowRight class="w-4 h-4 sm:w-2 sm:h-2" />
                        Mulai Belajar
                    </Button>
                </div>
            </div>
        </div>
    </Layout>
</template>

