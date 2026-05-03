<script setup>
import Layout from '../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote, Clock } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'

</script>

<template>
    <div class="flex h-screen bg-gray-100 text-gray-800 overflow-hidden font-sans">
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
          
            <div class="px-4 sm:px-5 pt-4 sm:pt-5">
                <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 flex items-center gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center shrink-0">
                        <img src="/asset/Logo Pals.png" alt="" class="w-full h-full object-contain">
                    </div>
                    <div class="flex-grow min-w-0">
                        <p class="text-[10px] sm:text-xs text-green-600 font-bold uppercase tracking-wider">Quiz Akhir</p>
                        <p class="text-sm sm:text-lg font-bold text-gray-800 tracking-tight truncate">Evaluasi Akhir Pembelajaran</p>
                    </div>
                    <div class="flex items-center gap-2 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                        <Clock :size="18" class="text-green-700" :stroke-width="2.5" />
                        <p class="text-base sm:text-lg font-bold text-green-700 tabular-nums">30:00</p>
                    </div>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto p-4 sm:p-5 pt-3 sm:pt-4">
                <div class="max-w-4xl mx-auto flex flex-col space-y-6 pb-12">
                    <!-- Question 1 -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden transition-all hover:shadow-md">
                        <!-- Question Header -->
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <span class="text-xs font-bold text-green-700 uppercase tracking-widest">Pertanyaan 1</span>
                        </div>

                        <!-- Question Content -->
                        <div class="p-6 sm:p-8">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 leading-relaxed mb-8">
                                Manakah dari pasangan berikut ini yang <span class="text-red-600 underline decoration-2 underline-offset-4">TIDAK</span> merepresentasikan hubungan yang benar?
                            </h2>

                            <RadioGroup default-value="r2" class="grid grid-cols-1 gap-4">
                                <div class="relative">
                                    <RadioGroupItem id="q1r1" value="r1" class="peer sr-only" />
                                    <Label for="q1r1" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">A</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Lemak; lipid</span>
                                    </Label>
                                </div>

                                <div class="relative">
                                    <RadioGroupItem id="q1r2" value="r2" class="peer sr-only" />
                                    <Label for="q1r2" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">B</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Pati; polisakarida</span>
                                    </Label>
                                </div>

                                <div class="relative">
                                    <RadioGroupItem id="q1r3" value="r3" class="peer sr-only" />
                                    <Label for="q1r3" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">C</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Pati; karbohidrat</span>
                                    </Label>
                                </div>

                                <div class="relative">
                                    <RadioGroupItem id="q1r4" value="r4" class="peer sr-only" />
                                    <Label for="q1r4" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">D</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Gula; karbohidratdrat</span>
                                    </Label>
                                </div>
                            </RadioGroup>
                        </div>
                    </div>

                    <!-- Question 2  -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden transition-all hover:shadow-md">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <span class="text-xs font-bold text-green-700 uppercase tracking-widest">Pertanyaan 2</span>
                        </div>
                        <div class="p-6 sm:p-8">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 leading-relaxed mb-8">
                                Organel sel yang berfungsi sebagai tempat respirasi seluler dan menghasilkan energi adalah...
                            </h2>
                            <RadioGroup class="grid grid-cols-1 gap-4">
                                <div class="relative">
                                    <RadioGroupItem id="q2r1" value="r1" class="peer sr-only" />
                                    <Label for="q2r1" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">A</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Ribosom</span>
                                    </Label>
                                </div>
                                <div class="relative">
                                    <RadioGroupItem id="q2r2" value="r2" class="peer sr-only" />
                                    <Label for="q2r2" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">B</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Mitokondria</span>
                                    </Label>
                                </div>
                                <div class="relative">
                                    <RadioGroupItem id="q2r3" value="r3" class="peer sr-only" />
                                    <Label for="q2r3" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">C</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Lisosom</span>
                                    </Label>
                                </div>
                                <div class="relative">
                                    <RadioGroupItem id="q2r4" value="r4" class="peer sr-only" />
                                    <Label for="q2r4" class="flex items-center gap-4 p-4 rounded-xl border-2 border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-50/50 transition-all cursor-pointer group">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-200 flex items-center justify-center shrink-0 group-hover:border-gray-300 peer-data-[state=checked]:border-green-600 peer-data-[state=checked]:bg-green-600 transition-colors">
                                            <span class="text-sm font-bold text-gray-500 peer-data-[state=checked]:text-white">D</span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">Badan Golgi</span>
                                    </Label>
                                </div>
                            </RadioGroup>
                        </div>
                    </div>

                    <!-- Final Action -->
                    <div class="pt-6 border-t border-gray-200 flex flex-col items-center space-y-4">
                        <p class="text-sm text-gray-500 italic font-medium">Sudah yakin dengan semua jawaban Anda?</p>
                        <Button class="w-full max-w-md bg-green-700 hover:bg-green-800 text-white py-6 rounded-xl font-bold text-lg shadow-lg shadow-green-100 flex items-center justify-center gap-3">
                            Submit & Akhiri Ujian
                        </Button>
                        <p class="text-[10px] text-gray-400">Pastikan untuk memeriksa kembali jawaban sebelum menekan tombol di atas.</p>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

