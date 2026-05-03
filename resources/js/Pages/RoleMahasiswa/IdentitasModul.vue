<script setup>
import Layout from '../../App.vue';
import { Check } from 'lucide-vue-next';
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">E-Plant Anatomy Learning System (E-PALS)</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Berisi petunjuk modul, deskripsi modul, tujuan modul</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-3 md:row-span-3 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-gray-800">Deskripsi Modul</h2> 
                <hr class="border-gray-200 mt-3">
                <p class="text-xs sm:text-sm text-gray-800 mt-2">
                    Modul ini menyajikan materi ajar mata kuliah Anatomi Tumbuhan yang difokuskan pada pengenalan struktur, organisasi, fungsi, serta
                    interaksi komponen penyusun tumbuhan mulai dari tingkat sel, jaringan, hingga organ. Materi yang disajikan mencakup komponen kimia 
                    penyusun sel, struktur dan ultrstruktur sel tumbuhan, dinding sel, plasma membrane, mekanisme transpor melalui membrane, sintesis 
                    protein dan organel, sistem endomembrane, retikulum endoplasma, kompleks golgi, vakuola, mitokondria, kloroplas, fotosintesis, peroksosom 
                    dan glioksisom, sitoskeleton, pergerakan dan posisi organel, jaringan tumbuhan (meristem dan jaringan dewasa), struktur dan fungsi organ tumbuhan 
                    (akar, batang, daun, bunga, buah dan biji).</p>
            </div>
            <div class="md:col-span-3 md:row-span-2 md:col-start-1 md:row-start-4 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-gray-800">Tujuan Pembelajaran</h2> 
                <hr class="border-gray-200 mt-3 mb-4">
                <ol class="lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                    <li class="flex items-start gap-2">
                        <Check class="w-4 h-4 text-green-500 shrink-0 mt-0.5" /> 
                        Mampu menjelaskan dan memahami tentang struktur sel tumbuhan,
                    </li>
                    <li class="flex items-start gap-2">
                        <Check class="w-4 h-4 text-green-500 shrink-0 mt-0.5" /> 
                        Mampu menjelaskan dan memahami tentang proses pertumbuhan dan perkembangan tumbuhan,
                    </li>
                    <li class="flex items-start gap-2">
                        <Check class="w-4 h-4 text-green-500 shrink-0 mt-0.5" /> 
                        Mampu menjelaskan dan memahami tentang konsep jaringan dan sistem jaringan pada tumbuhan,
                    </li>
                    <li class="flex items-start gap-2">
                        <Check class="w-4 h-4 text-green-500 shrink-0 mt-0.5" /> 
                        Mampu menjelaskan dan memahami tentang Struktur organ reproduksi : Bunga, Buah dan Biji,
                    </li>
                    <li class="flex items-start gap-2">
                        <Check class="w-4 h-4 text-green-500 shrink-0 mt-0.5" /> 
                        Mampu memahami dan menjelaskan tentang proses polinasi dan fertilisasi pada tumbuhan.
                    </li>
                </ol>
            </div>
            <div class="md:col-span-2 md:row-span-5 md:col-start-4 md:row-start-1 bg-white border border-gray-200 rounded-xl p-5 flex flex-col">
                <h2 class="font-bold text-base text-gray-800">Petunjuk Penggunaan</h2>
                <hr class="border-gray-200 mt-3 mb-5">

                <!-- Stepper -->
                <div class="flex-1 space-y-0">

                    <!-- Step 1 — active -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                            <div class="w-0.5 bg-gray-200 flex-1 my-1"></div>
                        </div>
                        <div class="pb-5">
                            <p class="text-sm font-semibold text-gray-800">1. Pelajari Materi Konseptual</p>
                            <p class="text-xs text-gray-500 mt-0.5">Baca dan pahami materi yang tersedia pada setiap bab.</p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                            <div class="w-0.5 bg-gray-200 flex-1 my-1"></div>
                        </div>
                        <div class="pb-5">
                            <p class="text-sm font-semibold text-gray-800">2. Eksplorasi Virtual Lab</p>
                            <p class="text-xs text-gray-500 mt-0.5">Lakukan praktikum virtual untuk memperdalam pemahaman.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                            <div class="w-0.5 bg-gray-200 flex-1 my-1"></div>
                        </div>
                        <div class="pb-5">
                            <p class="text-sm font-semibold text-gray-800">3. Kerjakan Lembar Kerja (LKM)</p>
                            <p class="text-xs text-gray-500 mt-0.5">Selesaikan LKM-Grafting sebagai evaluasi pemahaman.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-5 h-5 rounded-full bg-green-500 flex items-center justify-center shrink-0 mt-0.5">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div class="pb-2">
                            <p class="text-sm font-semibold text-gray-800">4. Ikuti Evaluasi Akhir</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kerjakan kuis interaktif di akhir setiap bab.</p>
                        </div>
                    </div>
                </div>
                <!-- CTA Button -->
                <a href="/mahasiswa/pembelajaran/materi" class="mt-5 block w-full text-center px-4 py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold text-sm transition-colors shadow-sm">
                    Mulai Modul →
                </a>
            </div>
        </div>
    </Layout>
</template>

