<script setup>
import Layout from '../../App.vue';
import { router } from '@inertiajs/vue3';
import { CheckCircle2, XCircle, Trophy, RotateCcw, ArrowLeft, Check, X } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    quiz: Object,
    score: Object,
});

const percentage = props.score.score;
const isPassed = props.score.is_passed;

const goBack = () => router.visit('/mahasiswa/evaluasi/quiz/history');

const getOptionState = (questionId, optionId, isCorrectOption) => {
    // get student's chosen option for this question
    const studentChosenId = props.score.answers?.[questionId];

    if (studentChosenId == optionId) {
        if (isCorrectOption) {
            return 'correct_chosen';
        } else {
            return 'wrong_chosen';
        }
    } else {
        if (isCorrectOption) {
            return 'correct_unchosen';
        } else {
            return 'default';
        }
    }
};
</script>

<template>
    <Layout>
        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header Actions -->
            <div class="flex items-center gap-3">
                <button @click="goBack" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-green-700 transition-colors bg-white px-4 py-2 rounded-lg border shadow-sm">
                    <ArrowLeft class="h-4 w-4" /> Kembali ke Riwayat
                </button>
                <h2 class="text-xl font-bold text-gray-800">Hasil & Detail Jawaban Quiz</h2>
            </div>

            <!-- Card Hasil -->
            <div class="w-full bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col md:flex-row">
                
                <!-- Info Section -->
                <div class="flex-1 flex flex-col">
                    <div class="px-6 py-5 border-b border-gray-100 flex-1"
                        :class="isPassed ? 'bg-green-50' : 'bg-red-50'">
                        <div class="flex items-center gap-3 mb-2">
                            <CheckCircle2 v-if="isPassed" class="h-7 w-7 text-green-600" />
                            <XCircle v-else class="h-7 w-7 text-red-500" />
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider"
                                    :class="isPassed ? 'text-green-600' : 'text-red-500'">
                                    {{ isPassed ? 'Selamat! Anda Lulus' : 'Belum Lulus' }}
                                </p>
                                <p class="text-xl font-bold text-gray-800">{{ quiz.title }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 space-y-4">
                            <!-- Passing grade info -->
                            <div class="w-full bg-white/60 border border-gray-200/60 rounded-xl px-4 py-3 flex items-center gap-3">
                                <Trophy class="h-5 w-5 text-green-600 shrink-0" />
                                <div class="text-sm">
                                    <span class="text-gray-700">Passing Grade: </span>
                                    <span class="font-bold text-gray-900">70</span>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="w-full grid grid-cols-3 gap-3 text-center">
                                <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                                    <p class="text-xl font-black text-green-600">{{ score.correct_answers }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Benar</p>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                                    <p class="text-xl font-black text-red-500">{{ score.total_questions - score.correct_answers }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Salah</p>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
                                    <p class="text-xl font-black text-gray-800">{{ score.total_questions }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Score Circle Section -->
                <div class="md:w-64 bg-white px-6 py-8 flex flex-col items-center justify-center border-l border-gray-100 shrink-0">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Skor Akhir</span>
                    <div class="relative w-36 h-36">
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
                            <span class="text-4xl font-black"
                                :class="isPassed ? 'text-green-600' : 'text-red-500'">
                                {{ percentage }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Answers List -->
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 text-lg tracking-tight">Detail Jawaban Anda</h3>
                </div>
                
                <div class="divide-y divide-gray-100">
                    <div v-for="(question, qIndex) in score.quiz?.questions" :key="question.id" class="p-6 md:p-8 hover:bg-gray-50/30 transition-colors">
                        <div class="flex gap-4 md:gap-6">
                            <!-- Number Badge -->
                            <div class="shrink-0 w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-black text-gray-700">
                                {{ qIndex + 1 }}
                            </div>
                            
                            <!-- Question Content -->
                            <div class="flex-1 space-y-5">
                                <div>
                                    <p class="text-base font-medium text-gray-900 leading-relaxed">{{ question.question_text }}</p>
                                    <div v-if="question.image" class="mt-4">
                                        <img :src="`/storage/${question.image}`" alt="Gambar Soal" class="max-h-64 rounded-xl border border-gray-200 shadow-sm" />
                                    </div>
                                </div>
                                
                                <!-- Options -->
                                <div class="grid grid-cols-1 gap-3">
                                    <div v-for="(option) in question.options" :key="option.id"
                                        class="flex items-center gap-3 p-3 md:p-4 rounded-xl border text-sm transition-all"
                                        :class="{
                                            'bg-green-50 border-green-300 shadow-sm': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                            'bg-red-50 border-red-300 shadow-sm': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                            'bg-green-50/40 border-green-200 border-dashed': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
                                            'bg-white border-gray-200': getOptionState(question.id, option.id, option.is_correct) === 'default',
                                        }">
                                        
                                        <!-- Check/Cross Icon based on student answer & correct answer -->
                                        <div class="shrink-0 w-6 h-6 rounded-full flex items-center justify-center border"
                                            :class="{
                                                'bg-green-500 border-green-600': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                                'bg-red-500 border-red-600': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                                'bg-white border-green-400': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
                                                'bg-gray-50 border-gray-300': getOptionState(question.id, option.id, option.is_correct) === 'default',
                                            }">
                                            <Check v-if="getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen'" class="w-3.5 h-3.5 text-white" />
                                            <X v-else-if="getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen'" class="w-3.5 h-3.5 text-white" />
                                            <Check v-else-if="getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen'" class="w-3.5 h-3.5 text-green-500" />
                                        </div>
                                        
                                        <div class="flex-1 flex items-center">
                                            <span class="font-bold text-gray-700 mr-2">{{ option.option_label }}.</span>
                                            <span :class="{
                                                'font-medium text-green-800': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                                'font-medium text-red-800': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                                'font-medium text-green-800': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
                                                'text-gray-600': getOptionState(question.id, option.id, option.is_correct) === 'default',
                                            }">
                                                {{ option.option_text }}
                                            </span>
                                        </div>
                                        
                                        <!-- Labels to explain state -->
                                        <div class="shrink-0 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded-md"
                                            :class="{
                                                'bg-green-100 text-green-700': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                                'bg-red-100 text-red-700': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                                'bg-green-50 text-green-600': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
                                            }">
                                            <span v-if="getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen'">Jawaban Anda (Benar)</span>
                                            <span v-else-if="getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen'">Jawaban Anda (Salah)</span>
                                            <span v-else-if="getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen'">Kunci Jawaban</span>
                                        </div>
                                    </div>
                                    <div v-if="!score.answers?.[question.id]" class="mt-2 text-sm font-medium text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200 flex items-center gap-2">
                                        <XCircle class="w-4 h-4" /> Anda tidak menjawab soal ini
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </Layout>
</template>
