<script setup>
import Layout from '../../App.vue';
import { Toast } from '@/lib/toast';
import { onMounted, onUnmounted, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    recent_results: Array,
});

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

// sweet alert toast
const page = usePage();

const showFlashMessage = () => {
    const flash = page.props.flash;
    if (flash.success) {
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: flash.success });
    }
};

let interval = null;

onMounted(() => {
    showFlashMessage();
    // Real-time polling every 10 seconds
    interval = setInterval(() => {
        router.reload({ 
            only: ['stats', 'recent_results'],
            preserveScroll: true,
            preserveState: true
        });
    }, 10000);
});

onUnmounted(() => {
    if (interval) clearInterval(interval);
});

watch(() => page.props.flash, () => {
    showFlashMessage();
}, { deep: true });

</script>

<template>
    <Layout>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Mhs Mengerjakan</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.students_taken }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Mahasiswa</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Quiz Saya</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.my_quizzes }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Total Dibuat</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Materi Tersedia</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.total_materi }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Unit Pembelajaran</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Total Mahasiswa</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.total_mahasiswa }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Terdaftar</p>
            </div>
        </div>

        <!-- Chart + Recent Results — stacked on mobile, side-by-side on lg -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

            <!-- Statistik Chart -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-4">Statistik Quiz</h2>
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

            <!-- Recent Results -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="text-sm font-semibold text-gray-800 mb-4">Hasil Quiz Terbaru</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div v-for="res in recent_results" :key="res.id" class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center text-[10px] font-bold text-white bg-blue-600"
                        >
                            {{ res.user_name.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-800 truncate">{{ res.user_name }}</p>
                            <p class="text-[10px] text-gray-400 truncate">{{ res.quiz_title }}</p>
                        </div>
                        <span class="text-xs font-bold" :class="res.is_passed ? 'text-green-600' : 'text-red-500'">{{ res.score }}</span>
                    </div>
                    <div v-if="recent_results.length === 0" class="py-4 text-center text-xs text-gray-400">
                        Belum ada aktivitas.
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
