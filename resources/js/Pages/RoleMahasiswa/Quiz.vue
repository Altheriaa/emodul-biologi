<script setup>
import Layout from '../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote, Clock } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

function startQuiz() {
    router.visit('/mahasiswa/evaluasi/quiz/start');
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">
            <!-- Revenue Chart -->
            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col items-center justify-center space-y-3">
                <div class="bg-green-100 p-4 rounded-full opacity-50 shadow-md">
                    <Clock :size="75" color="#1a6b3c" :stroke-width="1" />
                </div>
                <h2 class="font-bold text-xl md:text-2xl mt-2"> 45 Menit</h2>
                <h2 class="text-gray-400 text-sm"> Durasi Pengerjaan</h2>
            </div>

            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col items-center justify-center space-y-3">
                <div class="bg-green-100 p-4 rounded-full opacity-50 shadow-md">
                    <StickyNote :size="75" color="#1a6b3c" :stroke-width="1" />
                </div>
                <h2 class="font-bold text-xl md:text-2xl mt-2"> 25 Soal</h2>
                <h2 class="text-gray-400 text-sm"> Pilihan Ganda</h2>
            </div>

            <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col items-center justify-center space-y-3">
                <div class="bg-green-100 p-3 rounded-full opacity-50 shadow-md">
                    <Check :size="75" color="#1a6b3c" :stroke-width="1" />
                </div>
                <h2 class="font-bold text-xl md:text-2xl mt-2"> 70</h2>
                <h2 class="text-gray-400 text-sm"> Passing Grade</h2>
            </div>
        </div>

        <div class="col-start-1 col-span-4 row-start-2 row-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 mt-3">
            <h2 class="font-bold text-md text-gray-800">Petunjuk Pengerjaan Soal</h2> 
            <hr class="border-gray-200 mt-2 mb-4">

            <div class="flex lg:flex-row flex-col space-y-0">
                <!-- Step 1  -->
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                        <div class="w-0.5 bg-gray-200 flex-1 lg:hidden my-1"></div>
                    </div>
                    <div class="pb-5 space-y-1.5">
                        <p class="text-sm font-semibold text-gray-800">Pastikan Koneksi Internet</p>
                        <p class="text-xs text-gray-500 mt-0.5">Koneksi internet yang stabil sangat penting untuk menghindari gangguan saat mengerjakan ujian.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                        <div class="w-0.5 bg-gray-200 flex-1 lg:hidden my-1"></div>
                    </div>
                    <div class="pb-5 space-y-1.5">
                        <p class="text-sm font-semibold text-gray-800">Waktu Berjalan</p>
                        <p class="text-xs text-gray-500 mt-0.5">Waktu akan terus berjalan sewaktu klik mulai Quiz, pastikan untuk menyelesaikannya tepat waktu.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                        <div class="w-0.5 bg-gray-200 flex-1 lg:hidden my-1"></div>
                    </div>
                    <div class="pb-5 space-y-1.5">
                        <p class="text-sm font-semibold text-gray-800">Tidak Dapat dijeda</p>
                        <p class="text-xs text-gray-500 mt-0.5">Tidak ada jeda dalam pengerjaan ujian, jika keluar dari halaman waktu akan terus berjalan.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                            <div class="w-2 h-2 rounded-full bg-white"></div>
                        </div>
                    </div>
                    <div class="pb-5 space-y-1.5">
                        <p class="text-sm font-semibold text-gray-800">Sistem akan mengumpulkan jawaban</p>
                        <p class="text-xs text-gray-500 mt-0.5">Sistem akan mengumpulkan jawaban secara otomatis jika waktu habis.</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200 mb-4">

            <button @click="startQuiz" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors">Mulai Quiz</button>
        </div>
    </Layout>
</template>

