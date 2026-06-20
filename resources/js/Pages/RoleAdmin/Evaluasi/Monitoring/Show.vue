<script setup>
import Layout from '../../../../App.vue';
import { router } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, XCircle, Check, X } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import dayjs from 'dayjs';
import 'dayjs/locale/id';

const props = defineProps({
    score: Object,
});

const formatDate = (date) => {
    return dayjs(date).locale('id').format('DD MMMM YYYY, HH:mm');
};

const goBack = () => {
    router.visit('/admin/evaluasi/monitoring');
};

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
            <!-- Header Nav -->
            <div class="flex items-center gap-3">
                <Button variant="outline" size="sm" @click="goBack" class="gap-2">
                    <ArrowLeft class="w-4 h-4" /> Kembali
                </Button>
                <h2 class="text-xl font-bold text-gray-800">Detail Hasil Quiz Mahasiswa</h2>
            </div>

            <!-- Score Info Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col md:flex-row justify-between md:items-center gap-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">{{ score.user?.name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ score.user?.email }}</p>
                    <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
                        <div class="text-gray-500">Quiz:</div>
                        <div class="font-medium text-gray-800">{{ score.quiz?.title }}</div>
                        <div class="text-gray-500">Waktu Submit:</div>
                        <div class="font-medium text-gray-800">{{ formatDate(score.submitted_at) }}</div>
                        <div class="text-gray-500">Status:</div>
                        <div class="font-medium flex items-center gap-1" :class="score.is_passed ? 'text-green-600' : 'text-red-600'">
                            <CheckCircle2 v-if="score.is_passed" class="w-4 h-4" />
                            <XCircle v-else class="w-4 h-4" />
                            {{ score.is_passed ? 'Lulus' : 'Tidak Lulus' }}
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl border border-gray-100 min-w-[150px]">
                    <span class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-1">Skor Akhir</span>
                    <span class="text-4xl font-black" :class="score.is_passed ? 'text-green-600' : 'text-red-600'">
                        {{ Math.round(score.score) }}
                    </span>
                    <div class="mt-2 text-xs font-semibold text-gray-500 bg-white px-3 py-1 rounded-full border border-gray-200">
                        {{ score.correct_answers }} / {{ score.total_questions }} Benar
                    </div>
                </div>
            </div>

            <!-- Answers List -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Detail Jawaban</h3>
                </div>
                
                <div class="divide-y divide-gray-100">
                    <div v-for="(question, qIndex) in score.quiz?.questions" :key="question.id" class="p-6 hover:bg-gray-50/30 transition-colors">
                        <div class="flex gap-4">
                            <!-- Number Badge -->
                            <div class="shrink-0 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center font-bold text-sm text-gray-700">
                                {{ qIndex + 1 }}
                            </div>
                            
                            <!-- Question Content -->
                            <div class="flex-1 space-y-4">
                                <div>
                                    <p class="text-base font-medium text-gray-800">{{ question.question_text }}</p>
                                    <div v-if="question.image" class="mt-3">
                                        <img :src="`/storage/${question.image}`" alt="Gambar Soal" class="max-h-48 rounded-lg border border-gray-200" />
                                    </div>
                                </div>
                                
                                <!-- Options -->
                                <div class="grid grid-cols-1 gap-2.5">
                                    <div v-for="(option) in question.options" :key="option.id"
                                        class="flex items-center gap-3 p-3 rounded-lg border text-sm transition-all"
                                        :class="{
                                            'bg-green-50 border-green-300': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                            'bg-red-50 border-red-300': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                            'bg-green-50/30 border-green-200 border-dashed': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
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
                                        
                                        <div class="flex-1">
                                            <span class="font-bold text-gray-700 mr-2">{{ option.option_label }}.</span>
                                            <span :class="{
                                                'font-medium text-green-800': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                                'font-medium text-red-800': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                                'font-medium text-green-700': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
                                                'text-gray-600': getOptionState(question.id, option.id, option.is_correct) === 'default',
                                            }">
                                                {{ option.option_text }}
                                            </span>
                                        </div>
                                        
                                        <!-- Labels to explain state -->
                                        <div class="shrink-0 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded"
                                            :class="{
                                                'bg-green-100 text-green-700': getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen',
                                                'bg-red-100 text-red-700': getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen',
                                                'bg-green-50 text-green-600': getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen',
                                            }">
                                            <span v-if="getOptionState(question.id, option.id, option.is_correct) === 'correct_chosen'">Dipilih & Benar</span>
                                            <span v-else-if="getOptionState(question.id, option.id, option.is_correct) === 'wrong_chosen'">Dipilih & Salah</span>
                                            <span v-else-if="getOptionState(question.id, option.id, option.is_correct) === 'correct_unchosen'">Kunci Jawaban</span>
                                        </div>
                                    </div>
                                    <div v-if="!score.answers?.[question.id]" class="mt-2 text-sm font-medium text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200 flex items-center gap-2">
                                        <XCircle class="w-4 h-4" /> Mahasiswa tidak menjawab soal ini
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
