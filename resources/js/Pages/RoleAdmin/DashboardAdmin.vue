<script setup>
import Layout from '../../App.vue';
import { Toast } from '@/lib/toast';
import { onMounted, onUnmounted, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    recent_submissions: Array,
});

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
            only: ['stats', 'recent_submissions'],
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
                <p class="text-xs text-gray-400 mb-1">Jumlah Mahasiswa</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.count_mahasiswa }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Terdaftar Aktif</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Jumlah Dosen</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.count_dosen }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Pengelola Modul</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Jumlah Materi</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.count_materi }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Unit Pembelajaran</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-gray-400 mb-1">Jumlah Quiz</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">{{ stats.count_quiz }}</p>
                <p class="text-[10px] text-emerald-500 mt-1">Sistem Evaluasi</p>
            </div>
        </div>

        <!-- Chart + Recent Activity — stacked on mobile, side-by-side on lg -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

            <!-- Informasi Modul & Mata Kuliah -->
            <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column: Deskripsi & Tujuan -->
                    <div class="flex flex-col justify-between">
                        <div>
                            <h2 class="font-bold text-base text-gray-800 mb-3 flex items-center gap-2">
                                <span class="p-1 rounded bg-green-50 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </span>
                                Deskripsi Modul
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed">
                                E-Modul Biologi ini disusun oleh Nakhlatul Faradisa, mahasiswa Universitas Abulyatama Aceh, 
                                sebagai media pembelajaran untuk membantu mahasiswa memahami materi jaringan tumbuhan. Modul ini 
                                menggunakan pendekatan Project Based Learning (PjBL) yang mendorong pembelajaran aktif, kreatif, 
                                dan kontekstual guna meningkatkan kemampuan berpikir kritis, pemecahan masalah, serta kolaborasi. 
                                Materi disajikan secara sistematis dengan memanfaatkan teknik grafting sebagai sarana untuk memahami 
                                struktur dan fungsi jaringan tumbuhan secara lebih mendalam.
                            </p>
                        </div>
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <h3 class="font-bold text-base text-gray-800 mb-3">Tujuan Pembelajaran</h3>
                            <ul class="lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 text-gray-600 px-2">
                                <li class="flex items-start gap-1.5">
                                    <span class="text-green-500 font-bold">✓</span> 
                                    Mendukung Pembelajaran Berbasis Proyek (PjBL)
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-green-500 font-bold">✓</span> 
                                    Meningkatkan Kemampuan Berpikir Kritis
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-green-500 font-bold">✓</span> 
                                    Memfasilitasi Pembelajaran Mandiri
                                </li>
                                <li class="flex items-start gap-1.5">
                                    <span class="text-green-500 font-bold">✓</span> 
                                    Pemahaman Materi secara Mendalam
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Right Column: Informasi Mata Kuliah -->
                    <div class="border-t md:border-t-0 md:border-l border-gray-100 pt-5 md:pt-0 md:pl-6 flex flex-col">
                        <h2 class="font-bold text-base text-gray-800 mb-4 flex items-center gap-2">
                            <span class="p-1 rounded bg-green-50 text-green-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </span>
                            Informasi Mata Kuliah
                        </h2>
                        <div class="flex-1 divide-y divide-gray-100 text-xs sm:text-sm">
                            <div class="py-2 flex justify-between gap-4">
                                <span class="text-gray-500 font-medium shrink-0">Nama Mata Kuliah</span>
                                <span class="text-gray-800 font-semibold text-right">Anatomi Tumbuhan</span>
                            </div>
                            <div class="py-2 flex justify-between gap-4">
                                <span class="text-gray-500 font-medium shrink-0">Kode Mata Kuliah</span>
                                <span class="text-gray-800 font-semibold text-right">BIO112</span>
                            </div>
                            <div class="py-2 flex justify-between gap-4">
                                <span class="text-gray-500 font-medium shrink-0">Jumlah SKS</span>
                                <span class="text-gray-800 font-semibold text-right">3 SKS</span>
                            </div>
                            <div class="py-2 flex justify-between gap-4">
                                <span class="text-gray-500 font-medium shrink-0">Jumlah Pertemuan</span>
                                <span class="text-gray-800 font-semibold text-right">4 Kali</span>
                            </div>
                            <div class="py-2 flex justify-between gap-4">
                                <span class="text-gray-500 font-medium shrink-0">Model Pembelajaran</span>
                                <span class="text-gray-800 font-semibold text-right">PjBL</span>
                            </div>
                            <div class="py-2 flex flex-col gap-1">
                                <span class="text-gray-500 font-medium">Tema Proyek</span>
                                <span class="text-gray-800 font-semibold leading-relaxed">Teknik Grafting Sebagai Eksplorasi Jaringan Tumbuhan</span>
                            </div>
                            <div class="py-2 flex justify-between gap-4">
                                <span class="text-gray-500 font-medium shrink-0">Dosen Pengampu</span>
                                <span class="text-gray-800 font-semibold text-right">Samsuar, S.Pd., M.Pd</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-base text-gray-800 mb-4">Aktivitas Quiz Terbaru</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div v-for="sub in recent_submissions" :key="sub.id" class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center text-[10px] font-bold text-white bg-green-600"
                        >
                            {{ sub.user_name.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ sub.user_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ sub.quiz_title }}</p>
                        </div>
                        <span class="text-sm font-bold" :class="sub.score >= 70 ? 'text-green-600' : 'text-red-500'">{{ sub.score }}</span>
                    </div>
                    <div v-if="recent_submissions.length === 0" class="py-4 text-center text-xs text-gray-400">
                        Belum ada aktivitas.
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
