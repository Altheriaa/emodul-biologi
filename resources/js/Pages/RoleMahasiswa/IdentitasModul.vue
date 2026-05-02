<script setup>
import Layout from '../../App.vue';

import {
  Accordion,
  AccordionContent,
  AccordionItem,
  AccordionTrigger,
} from '@/components/ui/accordion'

import { Check } from 'lucide-vue-next';
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Identitas E-Modul Anatomi Tumbuhan</p>
                <p class="text-xs sm:text-sm text-emerald-400 mt-1">Berisi petunjuk modul, deskripsi modul, tujuan modul</p>
            </div>
        </div>

        <div class="grid grid-cols-4 sm:grid-cols-1 gap-4">
            <!-- grid -->
            <div class="col-start-1 col-span-4 row-start-1 row-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-gray-800">Capaian Pembelajaran Lulusan (CPL)</h2> 
                <hr class="border-gray-200 mt-3">
                <div class="space-y-2">
                    <!-- accord -->
                    <Accordion type="single" collapsible>
                        <AccordionItem value="item-1">
                            <AccordionTrigger>A. Sikap</AccordionTrigger>
                            <AccordionContent>
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
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-2">
                            <AccordionTrigger>B. Pengetahuan</AccordionTrigger>
                            <AccordionContent>
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
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-3">
                            <AccordionTrigger>C. Keterampilan Umum</AccordionTrigger>
                            <AccordionContent>
                                <ul class="list-decimal list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                    <li>
                                        Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah dibidang keahliannya berdasarkan hasil analisis informasi dan data (KU4)
                                    </li>
                                </ul>
                            </AccordionContent>
                        </AccordionItem>
                    </Accordion>
                </div>
            </div>
            
            <!-- grid 2 -->
            <div class="col-start-1 col-span-4 row-start-2 row-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5">
                <h2 class="font-bold text-md text-gray-800">Capaian Pembelajaran Mata Kuliah (CPMK)</h2> 
                <hr class="border-gray-200 mt-2 mb-4">
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
        </div>
    </Layout>
</template>

