<script setup>
import Layout from '../App.vue';
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
  } from '@headlessui/vue'

import { Check } from 'lucide-vue-next';
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">Informasi E-Modul Biologi</p>
                <p class="text-xs sm:text-sm text-emerald-400 mt-1">Panduan lengkap mengenai identitas, tujuan dan capaian pembelajaran materi Biologi</p>
            </div>
        </div>

        <!-- grid 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="col-span-1 row-span-2 bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-white">Identitas Modul</h2> 
                <hr class="border-white/10 mt-2 mb-2">

                <span class="opacity-25 lg:text-xs xl:text-sm text-xs sm:text-base text-white">Nama Modul</span>
                <p class="text-xs sm:text-base text-gray-400 mb-2">Anatomi Tumbuhan</p>

                <span class="opacity-25 lg:text-xs xl:text-sm text-xs sm:text-base text-white">Kode Mata Kuliah</span>
                <p class="text-xs sm:text-base text-gray-400 mb-2">PBA303</p>

                <span class="opacity-25 lg:text-xs xl:text-sm text-xs sm:text-base text-white">Semester</span>
                <p class="text-xs sm:text-base text-gray-400 mb-2">III</p>

                <span class="opacity-25 lg:text-xs xl:text-sm text-xs sm:text-base text-white">SKS</span>
                <p class="text-xs sm:text-base text-gray-400 mb-2">3 SKS (2 Teori, 1 Praktikum)</p>
            </div>

            <div class="col-span-1 sm:col-span-2 sm:col-start-2 bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-white">Capaian Pembelajaran Lulusan (CPL)</h2> 
                <hr class="border-white/10 mt-2 mb-4">
                
                <div class="space-y-2">
                    <!-- accord 1 -->
                    <Disclosure v-slot="{ open }">
                        <DisclosureButton class="flex w-full items-center justify-between py-2 bg-[#1C352D] rounded-lg px-4">
                            <span class="font-medium lg:text-xs xl:text-sm text-sm sm:text-base text-white">A. Sikap</span>
                            <svg class="w-4 h-4" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path v-if="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                <path v-if="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        </DisclosureButton>
                        <DisclosurePanel class="text-gray-500 px-2 mt-2 bg-gray-700/20 py-2 rounded-lg">
                            <ul class="list-decimal list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    (SP3) Menginternalisasi nilai, norma, dan etika akademik
                                </li>
                                <li>
                                    (SP7) Bekerjasama dan memiliki kepekaan sosial serta kepedulian terhadap masyarakat dan lingkungan
                                </li>
                                <li>
                                    (SP10) Menunjukkan sikap bertanggung jawab atas pekerjaan dibidang keahliannya secara mandiri
                                </li>
                            </ul>
                        </DisclosurePanel>
                    </Disclosure>
                    <!-- accord 2 -->
                    <Disclosure v-slot="{ open }">
                        <DisclosureButton class="flex w-full items-center justify-between py-2 bg-[#1C352D] rounded-lg px-4">
                            <span class="font-medium lg:text-xs xl:text-sm text-sm sm:text-base text-white">B. Pengetahuan</span>
                            <svg class="w-4 h-4" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path v-if="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                <path v-if="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        </DisclosureButton>
                        <DisclosurePanel class="text-gray-500 px-2 mt-2 bg-gray-700/20 py-2 rounded-lg">
                            <ul class="list-decimal list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    (P2) Menguasai konsep-konsep dasar, hukum-hukum, teori-teori, dan perkembangan keilmuan biologi, serta pola pikir biologis yang diperlukan untuk melaksanakan pembelajaran di satuan pendidikan
                                </li>
                                <li>
                                    (P3) Menguasai konsep-konsep pengelolaan sumber daya hayati dan lingkungan serta integrasinya dalam pembelajaran biologi
                                </li>
                                <li>
                                    (P5) menguasai konsep pengembangan potensi peserta didik dan sistem pembelajaran biologi berbasis Student Center Learning (SCL)
                                </li>
                            </ul>
                        </DisclosurePanel>
                    </Disclosure>
                    <!-- accord 3 -->
                    <Disclosure v-slot="{ open }">
                        <DisclosureButton class="flex w-full items-center justify-between py-2 bg-[#1C352D] rounded-lg px-4">
                            <span class="font-medium lg:text-xs xl:text-sm text-sm sm:text-base text-white">C. Keterampilan Umum</span>
                            <svg class="w-4 h-4" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path v-if="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                <path v-if="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                            </svg>
                        </DisclosureButton>
                        <DisclosurePanel class="text-gray-500 px-2 mt-2 bg-[bg-[#1C352D]] py-2 rounded-lg">
                            <ul class="list-decimal list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah dibidang keahliannya berdasarkan hasil analisis informasi dan data (KU4)
                                </li>
                            </ul>
                        </DisclosurePanel>
                    </Disclosure>
                </div>
            </div>
            
            <div class="col-span-1 sm:col-span-2 sm:col-start-2 bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-white">Capaian Pembelajaran Mata Kuliah (CPMK)</h2> 
                <hr class="border-white/10 mt-2 mb-4">
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
                        Mampu memahami dan menjelaskan tentang proses polinasi dan fertilisasi pada tumbuahan.
                    </li>
                </ol>
            </div>
        </div>
    </Layout>
</template>

