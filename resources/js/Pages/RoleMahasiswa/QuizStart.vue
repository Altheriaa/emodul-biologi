<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { Clock, ChevronLeft, ChevronRight, AlertTriangle, ShieldCheck } from 'lucide-vue-next';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';

const props = defineProps({
    quiz: Object,
    questions: Array,
    alreadySubmitted: Boolean,
    previousScore: Object,
});

// ─── Jawaban ────────────────────────────────────────────────────
// answers: { [question_id]: option_id }
const answers = ref({});

const currentIndex = ref(0);
const currentQuestion = computed(() => props.questions[currentIndex.value]);

const answeredCount = computed(() =>
    Object.values(answers.value).filter(v => v !== null && v !== undefined).length
);

const isAnswered = (qId) => answers.value[qId] !== undefined && answers.value[qId] !== null;

const selectAnswer = (questionId, optionId) => {
    answers.value[questionId] = optionId;
};

const goTo = (idx) => {
    if (idx >= 0 && idx < props.questions.length) {
        currentIndex.value = idx;
    }
};

// ─── Timer ──────────────────────────────────────────────────────
const totalSeconds = ref(props.quiz.remaining_seconds);
let timerInterval = null;

const formattedTime = computed(() => {
    const total = Math.floor(totalSeconds.value);
    const m = Math.floor(total / 60).toString().padStart(2, '0');
    const s = (total % 60).toString().padStart(2, '0');
    return `${m}:${s}`;
});

const isTimeCritical = computed(() => totalSeconds.value <= 60);

const submitForm = useForm({});

const doSubmit = () => {
    clearInterval(timerInterval);
    submitForm.transform(() => ({
        answers: answers.value,
    })).post(`/mahasiswa/evaluasi/quiz/${props.quiz.id}/submit`);
};

const confirmSubmit = () => {
    const unanswered = props.questions.length - answeredCount.value;

    Swal.fire({
        title: unanswered > 0 ? `${unanswered} soal belum dijawab` : 'Akhiri Quiz?',
        text: unanswered > 0
            ? 'Soal yang belum dijawab tidak akan mendapat poin. Lanjutkan submit?'
            : 'Pastikan semua jawaban sudah benar sebelum submit.',
        icon: unanswered > 0 ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Submit!',
        cancelButtonText: 'Kembali',
        confirmButtonColor: '#15803d',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) doSubmit();
    });
};

// ─── Security ───────────────────────────────────────────────────
const tabSwitchCount = ref(0);
const maxTabSwitches = 3;

const handleVisibilityChange = () => {
    if (document.hidden) {
        tabSwitchCount.value++;
        
        if (tabSwitchCount.value >= maxTabSwitches) {
            Swal.fire({
                title: 'Pelanggaran Keamanan!',
                text: 'Anda terlalu banyak meninggalkan halaman kuis. Kuis akan otomatis disubmit.',
                icon: 'error',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
            }).then(() => {
                doSubmit();
            });
        } else {
            Toast.fire({
                icon: 'warning',
                title: 'Peringatan Keamanan!',
                text: `Dilarang meninggalkan halaman kuis! Pelanggaran: ${tabSwitchCount.value}/${maxTabSwitches}`,
                timer: 5000
            });
        }
    }
};

const blockRightClick = (e) => e.preventDefault();

const blockInspect = (e) => {
    // F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
    if (
        e.keyCode === 123 || 
        (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) || 
        (e.ctrlKey && e.keyCode === 85) ||
        (e.metaKey && e.altKey && (e.keyCode === 73 || e.keyCode === 74)) // Mac shortcuts
    ) {
        e.preventDefault();
        return false;
    }
};

const handleBeforeUnload = (e) => {
    if (!submitForm.wasSuccessful) {
        e.preventDefault();
        e.returnValue = '';
    }
};

onMounted(() => {
    if (props.alreadySubmitted) return;

    timerInterval = setInterval(() => {
        if (totalSeconds.value <= 0) {
            clearInterval(timerInterval);
            doSubmit(); // auto submit saat waktu habis
        } else {
            totalSeconds.value--;
        }
    }, 1000);

    // Security listeners
    document.addEventListener('visibilitychange', handleVisibilityChange);
    document.addEventListener('contextmenu', blockRightClick);
    document.addEventListener('keydown', blockInspect);
    window.addEventListener('beforeunload', handleBeforeUnload);
});

onBeforeUnmount(() => {
    clearInterval(timerInterval);
    document.removeEventListener('visibilitychange', handleVisibilityChange);
    document.removeEventListener('contextmenu', blockRightClick);
    document.removeEventListener('keydown', blockInspect);
    window.removeEventListener('beforeunload', handleBeforeUnload);
});
</script>

<template>
    <div class="flex h-screen bg-gray-100 text-gray-800 overflow-hidden font-sans select-none">
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Header Bar -->
            <div class="px-4 sm:px-5 pt-4 sm:pt-5 shrink-0">
                <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center shrink-0">
                        <img src="/asset/Logo Pals.png" alt="" class="w-full h-full object-contain" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] sm:text-xs text-green-600 font-bold uppercase tracking-wider">Quiz Akhir</p>
                        <p class="text-sm sm:text-lg font-bold text-gray-800 tracking-tight truncate">{{ quiz.title }}</p>
                    </div>
                    <!-- Timer -->
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg border transition-colors shrink-0"
                        :class="isTimeCritical
                            ? 'bg-red-50 border-red-200 animate-pulse'
                            : 'bg-green-50 border-green-100'">
                        <Clock :size="18"
                            :class="isTimeCritical ? 'text-red-600' : 'text-green-700'"
                            :stroke-width="2.5" />
                        <p class="text-base sm:text-lg font-bold tabular-nums"
                            :class="isTimeCritical ? 'text-red-600' : 'text-green-700'">
                            {{ formattedTime }}
                        </p>
                    </div>

                    <!-- Security Badge -->
                    <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100 shrink-0">
                        <ShieldCheck class="h-4 w-4 text-blue-600" />
                        <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider">Security Active</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-1 overflow-hidden mt-3 gap-4 px-4 sm:px-5 pb-4 sm:pb-5">

                <!-- Sidebar navigasi soal -->
                <aside class="hidden lg:flex flex-col w-52 shrink-0">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex-1 overflow-y-auto">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            Navigasi Soal
                        </p>
                        <p class="text-xs text-gray-400 mb-4">
                            {{ answeredCount }}/{{ questions.length }} terjawab
                        </p>
                        <div class="grid grid-cols-4 gap-2">
                            <button
                                v-for="(q, idx) in questions" :key="q.id"
                                @click="goTo(idx)"
                                class="w-full aspect-square rounded-lg text-xs font-bold transition-colors"
                                :class="{
                                    'bg-green-600 text-white shadow-sm': currentIndex === idx,
                                    'bg-green-100 text-green-700': isAnswered(q.id) && currentIndex !== idx,
                                    'bg-gray-100 text-gray-500 hover:bg-gray-200': !isAnswered(q.id) && currentIndex !== idx,
                                }">
                                {{ idx + 1 }}
                            </button>
                        </div>
                        <div class="mt-4 space-y-1.5 text-xs text-gray-500">
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-green-600 inline-block"></span> Sedang dikerjakan</div>
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-green-100 inline-block"></span> Sudah dijawab</div>
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-gray-100 inline-block"></span> Belum dijawab</div>
                        </div>
                    </div>
                </aside>

                <!-- Main soal area -->
                <main class="flex-1 overflow-y-auto">
                    <div class="max-w-2xl mx-auto flex flex-col gap-4 pb-8">

                        <!-- Progress bar (mobile) -->
                        <div class="lg:hidden flex items-center gap-3">
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500 rounded-full transition-all"
                                    :style="{ width: `${(answeredCount / questions.length) * 100}%` }"></div>
                            </div>
                            <span class="text-xs text-gray-500 shrink-0">{{ answeredCount }}/{{ questions.length }}</span>
                        </div>

                        <!-- Question Card -->
                        <div v-if="currentQuestion"
                            class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                            <!-- Header -->
                            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <span class="text-xs font-bold text-green-700 uppercase tracking-widest">
                                    Pertanyaan {{ currentIndex + 1 }} dari {{ questions.length }}
                                </span>
                                <span v-if="isAnswered(currentQuestion.id)"
                                    class="text-xs text-green-600 font-semibold">✓ Terjawab</span>
                            </div>

                            <!-- Body -->
                            <div class="p-5 sm:p-8">
                                <p class="text-base sm:text-lg font-semibold text-gray-800 leading-relaxed mb-7">
                                    {{ currentQuestion.question_text }}
                                </p>
                                <div v-if="currentQuestion.image" class="mb-7">
                                    <img :src="`/storage/${currentQuestion.image}`" alt="Soal Image" class="max-h-64 rounded-xl border border-gray-200" />
                                </div>

                                <div class="grid grid-cols-1 gap-3">
                                    <button
                                        v-for="opt in currentQuestion.options" :key="opt.id"
                                        @click="selectAnswer(currentQuestion.id, opt.id)"
                                        class="flex items-center gap-4 p-4 rounded-xl border-2 text-left transition-all"
                                        :class="answers[currentQuestion.id] === opt.id
                                            ? 'border-green-600 bg-green-50/60'
                                            : 'border-gray-100 bg-white hover:bg-gray-50 hover:border-gray-200'">
                                        <div class="w-9 h-9 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors"
                                            :class="answers[currentQuestion.id] === opt.id
                                                ? 'border-green-600 bg-green-600'
                                                : 'border-gray-200'">
                                            <span class="text-sm font-bold"
                                                :class="answers[currentQuestion.id] === opt.id
                                                    ? 'text-white'
                                                    : 'text-gray-500'">
                                                {{ opt.option_label }}
                                            </span>
                                        </div>
                                        <span class="text-sm sm:text-base font-medium text-gray-700">
                                            {{ opt.option_text }}
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation buttons -->
                        <div class="flex items-center justify-between gap-3">
                            <button @click="goTo(currentIndex - 1)" :disabled="currentIndex === 0"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                                <ChevronLeft class="h-4 w-4" /> Sebelumnya
                            </button>

                            <button v-if="currentIndex < questions.length - 1"
                                @click="goTo(currentIndex + 1)"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition-colors shadow-sm">
                                Berikutnya <ChevronRight class="h-4 w-4" />
                            </button>

                            <button v-else @click="confirmSubmit"
                                :disabled="submitForm.processing"
                                class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-700 hover:bg-green-800 text-white text-sm font-bold transition-colors shadow-sm disabled:opacity-50">
                                Submit & Akhiri
                            </button>
                        </div>

                    </div>
                </main>

            </div>
        </div>
    </div>
</template>
