<script setup>
import { router } from '@inertiajs/vue3';
import { CheckCircle2, XCircle, Trophy, RotateCcw, ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
    quiz: Object,
    score: Object,
});

const percentage = props.score.score;
const isPassed = props.score.is_passed;

const goBack = () => router.visit('/mahasiswa/evaluasi/quiz');
const retake = () => router.visit(`/mahasiswa/evaluasi/quiz/${props.quiz.id}/start`);
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col items-center justify-center p-4">

        <!-- Card Hasil -->
        <div class="w-full max-w-md bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100"
                :class="isPassed ? 'bg-green-50' : 'bg-red-50'">
                <div class="flex items-center gap-3">
                    <CheckCircle2 v-if="isPassed" class="h-7 w-7 text-green-600" />
                    <XCircle v-else class="h-7 w-7 text-red-500" />
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider"
                            :class="isPassed ? 'text-green-600' : 'text-red-500'">
                            {{ isPassed ? 'Selamat! Anda Lulus' : 'Belum Lulus' }}
                        </p>
                        <p class="text-sm font-bold text-gray-800">{{ quiz.title }}</p>
                    </div>
                </div>
            </div>

            <!-- Score -->
            <div class="px-6 py-8 flex flex-col items-center">
                <div class="relative w-36 h-36 mb-5">
                    <!-- Circle progress -->
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#f3f4f6" stroke-width="10" />
                        <circle cx="60" cy="60" r="50" fill="none"
                            :stroke="isPassed ? '#16a34a' : '#ef4444'"
                            stroke-width="10"
                            stroke-linecap="round"
                            :stroke-dasharray="`${2 * Math.PI * 50}`"
                            :stroke-dashoffset="`${2 * Math.PI * 50 * (1 - percentage / 100)}`"
                            class="transition-all duration-1000" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold"
                            :class="isPassed ? 'text-green-700' : 'text-red-600'">
                            {{ percentage }}
                        </span>
                        <span class="text-xs text-gray-400">dari 100</span>
                    </div>
                </div>

                <!-- Stats -->
                <div class="w-full grid grid-cols-3 gap-3 text-center mb-6">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-lg font-bold text-gray-800">{{ score.correct_answers }}</p>
                        <p class="text-xs text-gray-400">Benar</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-lg font-bold text-gray-800">{{ score.total_questions - score.correct_answers }}</p>
                        <p class="text-xs text-gray-400">Salah</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-lg font-bold text-gray-800">{{ score.total_questions }}</p>
                        <p class="text-xs text-gray-400">Total Soal</p>
                    </div>
                </div>

                <!-- Passing grade info -->
                <div class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 flex items-center gap-3 mb-6">
                    <Trophy class="h-5 w-5 text-green-500 shrink-0" />
                    <div class="text-sm">
                        <span class="text-gray-600">Passing Grade: </span>
                        <span class="font-semibold text-gray-800">70</span>
                        <span class="ml-2 text-gray-400">•</span>
                        <span class="ml-2"
                            :class="isPassed ? 'text-green-600 font-semibold' : 'text-red-500 font-semibold'">
                            Nilai Anda: {{ percentage }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="w-full flex flex-col gap-2">
                    <button @click="goBack"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold text-sm transition-colors shadow-sm">
                        <ArrowLeft class="h-4 w-4" /> Kembali ke Daftar Quiz
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
