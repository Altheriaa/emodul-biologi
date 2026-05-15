<script setup>
import Layout from '../../App.vue';
import { onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    stats: Object,
    recent_materi: Array,
});

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

let interval = null;

onMounted(() => {
    // Real-time polling every 30 seconds
    interval = setInterval(() => {
        router.reload({ 
            only: ['stats', 'recent_materi'],
            preserveScroll: true,
            preserveState: true
        });
    }, 30000);
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
});
</script>

<template>
    <Layout>
        <!-- Info Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Nama Mahasiswa</p>
                <p class="text-sm sm:text-lg font-bold text-gray-800 tracking-tight line-clamp-1">{{ user.name }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Status: Aktif</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">NIM / Angkatan</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ user.nim }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Angkatan {{ user.angkatan }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Skor Rata-rata</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.avg_score }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Hasil Evaluasi</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Quiz Selesai</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.quiz_taken }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Total Dikerjakan</p>
            </div>
        </div>

        <!-- Chart + Recent Materi — stacked on mobile, side-by-side on lg -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

            <!-- Progress Chart -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-4">Statistik Belajar</h2>
                <div class="relative h-40 sm:h-52">
                    <svg viewBox="0 0 600 200" class="w-full h-full" preserveAspectRatio="none">
                        <line x1="0" y1="40" x2="600" y2="40" stroke="rgba(0,0,0,0.06)" stroke-width="1"/>
                        <line x1="0" y1="80" x2="600" y2="80" stroke="rgba(0,0,0,0.06)" stroke-width="1"/>
                        <line x1="0" y1="120" x2="600" y2="120" stroke="rgba(0,0,0,0.06)" stroke-width="1"/>
                        <line x1="0" y1="160" x2="600" y2="160" stroke="rgba(0,0,0,0.06)" stroke-width="1"/>
                        <defs>
                            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="rgba(22,163,74,0.15)"/>
                                <stop offset="100%" stop-color="rgba(22,163,74,0)"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,160 C40,155 70,140 100,120 C130,100 145,130 180,110 C215,90 240,60 280,50 C320,40 345,80 380,70 C415,60 440,40 480,30 C520,20 560,35 600,25 L600,200 L0,200 Z" fill="url(#areaGrad)"/>
                        <path d="M0,160 C40,155 70,140 100,120 C130,100 145,130 180,110 C215,90 240,60 280,50 C320,40 345,80 380,70 C415,60 440,40 480,30 C520,20 560,35 600,25" fill="none" stroke="rgba(22,163,74,0.8)" stroke-width="1.5"/>
                    </svg>
                    <!-- Y-axis labels -->
                    <div class="absolute left-0 top-0 h-full flex flex-col justify-between pr-2 text-right">
                        <span class="text-[10px] text-gray-400 leading-none">100</span>
                        <span class="text-[10px] text-gray-400 leading-none">80</span>
                        <span class="text-[10px] text-gray-400 leading-none">60</span>
                        <span class="text-[10px] text-gray-400 leading-none">40</span>
                        <span class="text-[10px] text-gray-400 leading-none">0</span>
                    </div>
                </div>
                <!-- X-axis labels — show fewer on mobile -->
                <div class="flex justify-between mt-2 px-6">
                    <span
                        v-for="(m, i) in months"
                        :key="m"
                        :class="['text-[10px] text-gray-400', { 'hidden sm:inline': i % 2 !== 0 }]"
                    >{{ m }}</span>
                </div>
            </div>

            <!-- Recent Materi -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-4">Materi Terbaru</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div v-for="materi in recent_materi" :key="materi.id" class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center text-[10px] font-bold text-white bg-green-100"
                        >
                           <img v-if="materi.cover_path" :src="`/storage/${materi.cover_path}`" class="w-full h-full object-cover rounded-lg" />
                           <span v-else class="text-green-600">B</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-800 truncate">{{ materi.judul }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ materi.tanggal_rilis }}</p>
                        </div>
                    </div>
                    <div v-if="recent_materi.length === 0" class="py-4 text-center text-xs text-gray-400">
                        Belum ada materi terbaru.
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
