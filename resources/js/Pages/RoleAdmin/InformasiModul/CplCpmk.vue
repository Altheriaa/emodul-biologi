<script setup>
import Layout from '../../../App.vue';

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
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Informasi CPL dan CPMK E-Modul Anatomi Tumbuhan</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Panduan lengkap mengenai capaian pembelajaran lulusan dan capaian pembelajaran mata kuliah materi Anatomi Tumbuhan</p>
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
                            <AccordionTrigger>CPL 4</AccordionTrigger>
                            <AccordionContent>
                                <ul class="list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                    <li>
                                        Mampu memahami dan menguasai teori, konsep, prinsip, hukum, dan proses biologi dengan menerapkan pendekatan ilmiah dan pemikiran logis, serta menunjukkan hasil pemahaman konsep yang utuh dan terintegrasi untuk mendukung pembelajaran Biologi yang bermakna
                                    </li>
                                </ul>
                            </AccordionContent>
                        </AccordionItem>
                        <AccordionItem value="item-2">
                            <AccordionTrigger>CPL 7</AccordionTrigger>
                            <AccordionContent>
                                <ul class="list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                    <li>
                                        Mampu mengimplementasikan konsep biologi dan pendidikan dalam konteks kewirausahaan dengan menerapkan strategi inovatif, analisis kebutuhan, dan pemanfaatan teknologi, serta menunjukkan hasil pembelajaran sepanjang hayat untuk menciptakan nilai tambah ekonomi, sosial, dan lingkungan.
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
                <Accordion type="single" collapsible>
                    <AccordionItem value="item-1">
                        <AccordionTrigger><span class="text-left">CPMK 1: Mampu menjelaskan dan mengintegrasikan konsep, prinsip, dan proses anatomi tumbuhan secara komprehensif, meliputi struktur sel, jaringan (dasar, pembuluh, dermal, meristem), organ (akar, batang, daun, bunga, buah, biji), serta proses embriologi tumbuhan secara ilmiah dan logis (CPL-4).</span></AccordionTrigger>
                        <AccordionContent>
                            <ul class="list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    1.1 Menjelaskan struktur sel tumbuhan serta jaringan penyusun tumbuhan (jaringan dasar, pembuluh, dermal, dan meristem)
                                </li>
                                <li>
                                    1.2 Menganalisis hubungan struktur dan fungsi jaringan tumbuhan dalam sistem organ
                                </li>
                                <li>
                                    1.3 Menganalisis struktur anatomi organ tumbuhan (akar, batang, daun)
                                </li>
                                <li>
                                    1.4 Menjelaskan struktur reproduktif dan proses embriologi tumbuhan (bunga, buah, biji, hingga perkembangan embrio)
                                </li>
                            </ul>
                        </AccordionContent>
                    </AccordionItem>
                    <AccordionItem value="item-2">
                        <AccordionTrigger><span class="text-left">CPMK 3: Mampu mengaplikasikan konsep anatomi tumbuhan (jaringan tumbuhan) dalam praktik lapangan melalui teknik <i>grafting</i>, serta menganalisis keterkaitan struktur jaringan dengan keberhasilan penyambungan tanaman (CPL-4 & CPL-7).</span></AccordionTrigger>
                        <AccordionContent>
                            <ul class="list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    3.1 Menjelaskan prinsip <i>grafting</i> berdasarkan konsep anatomi tumbuhan (khususnya kambium)
                                </li>
                                <li>
                                    3.2 Menerapkan teknik <i>grafting</i> secara tepat dan sistematis
                                </li>
                                <li>
                                    3.3 Menganalisis faktor keberhasilan dan kegagalan <i>grafting</i> berdasarkan penyatuan jaringan
                                </li>
                                <li>
                                    3.4 Mengaitkan hasil <i>grafting</i> dengan konsep struktur dan fungsi anatomi tumbuhan
                                </li>
                            </ul>
                        </AccordionContent>
                    </AccordionItem>
                    <AccordionItem value="item-3">
                        <AccordionTrigger><span class="text-left">CPMK 4: Mampu merancang dan mengembangkan proyek berbasis anatomi tumbuhan (<i>grafting</i>) yang inovatif dengan mempertimbangkan analisis kebutuhan dan potensi kewirausahaan untuk menghasilkan nilai tambah ekonomi, sosial, dan lingkungan (CPL-7).</span></AccordionTrigger>
                        <AccordionContent>
                            <ul class="list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    4.1 Menganalisis potensi pemanfaatan <i>grafting</i> dalam konteks kebutuhan nyata (ekonomi/lingkungan)
                                </li>
                                <li>
                                    4.2 Merancang proyek <i>grafting</i> berbasis inovasi dan analisis kebutuhan
                                </li>
                                <li>
                                    4.3 Mengembangkan produk tanaman hasil <i>grafting</i> yang bernilai tambah
                                </li>
                                <li>
                                    4.4 Mengevaluasi kelayakan dan manfaat produk (ekonomi, sosial, lingkungan)
                                </li>
                            </ul>
                        </AccordionContent>
                    </AccordionItem>
                    <AccordionItem value="item-4">
                        <AccordionTrigger><span class="text-left">CPMK 5: Mampu mengomunikasikan hasil pembelajaran (teori, praktikum, dan proyek <i>grafting</i>) dalam bentuk laporan ilmiah, presentasi, dan refleksi secara kolaboratif, sistematis, dan bertanggung jawab (CPL-8).</span></AccordionTrigger>
                        <AccordionContent>
                            <ul class="list-inside lg:text-xs xl:text-sm text-sm sm:text-base space-y-2 px-2">
                                <li>
                                    5.1 Menyusun laporan ilmiah hasil praktikum dan proyek <i>grafting</i> secara sistematis
                                </li>
                                <li>
                                    5.2 Menyajikan data dan hasil analisis dalam bentuk visual dan naratif ilmiah
                                </li>
                                <li>
                                    5.3 Melakukan presentasi ilmiah secara komunikatif dan kolaboratif
                                </li>
                                <li>
                                    4.4 Mengevaluasi kelayakan dan manfaat produk 5.4 Melakukan refleksi pembelajaran dan menunjukkan etika akademik
                                </li>
                            </ul>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </div>
        </div>
    </Layout>
</template>

