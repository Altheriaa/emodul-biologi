<script setup>
import Layout from '../../App.vue';
import { router } from '@inertiajs/vue3';
import { Clock, StickyNote, CheckCircle2, Trophy, ChevronRight, BookOpen, History } from 'lucide-vue-next';

const props = defineProps({
    quizzes: Array,
});

const startQuiz = (quizId) => {
    router.visit(`/mahasiswa/evaluasi/quiz/${quizId}/start`);
};

const statusColor = (quiz) => {
    if (quiz.submitted_at === null) return null;
    return quiz.is_passed ? 'passed' : 'failed';
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Evaluasi Quiz</p>
                    <p class="text-xs sm:text-sm text-green-600 mt-1">Kerjakan quiz sebagai bagian dari evaluasi akhir pembelajaran</p>
                </div>
                <button @click="router.visit('/mahasiswa/evaluasi/quiz/history')"
                    class="flex items-center justify-center gap-2 px-4 py-2.5 bg-green-50 text-green-700 border border-green-200 rounded-xl text-sm font-bold hover:bg-green-100 transition-all">
                    <History class="h-4 w-4" />
                    Riwayat Nilai
                </button>
            </div>
        </div>

        <!-- Empty state -->
        <div v-if="quizzes.length === 0"
            class="bg-white border border-gray-200 rounded-xl p-16 shadow-sm text-center text-gray-400">
            <BookOpen class="mx-auto h-12 w-12 mb-4 opacity-30" />
            <p class="font-medium text-gray-500">Belum ada quiz yang tersedia</p>
            <p class="text-sm mt-1">Tunggu hingga dosen atau admin mempublikasikan quiz.</p>
        </div>

        <!-- Quiz Cards -->
        <div v-else class="grid grid-cols-1 gap-4">
            <div v-for="quiz in quizzes" :key="quiz.id"
                class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">

                <!-- Card Header -->
                <div class="p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <!-- Status badge -->
                                <span v-if="statusColor(quiz) === 'passed'"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    <CheckCircle2 class="h-3 w-3" /> Lulus
                                </span>
                                <span v-else-if="statusColor(quiz) === 'failed'"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Belum Lulus
                                </span>
                                <span v-else-if="quiz.is_ongoing"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 animate-pulse">
                                    Sedang Dikerjakan
                                </span>
                                <span v-else
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    Belum Dikerjakan
                                </span>
                            </div>
                            <h2 class="text-base sm:text-lg font-bold text-gray-800">{{ quiz.title }}</h2>
                            <p v-if="quiz.description" class="text-sm text-gray-500 mt-1 line-clamp-2">{{ quiz.description }}</p>
                        </div>

                        <!-- Score badge (jika sudah dikerjakan) -->
                        <div v-if="quiz.score !== null" class="shrink-0 text-center">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center border-4"
                                :class="quiz.is_passed ? 'border-green-500 bg-green-50' : 'border-red-400 bg-red-50'">
                                <span class="text-xl font-bold"
                                    :class="quiz.is_passed ? 'text-green-700' : 'text-red-600'">
                                    {{ quiz.score }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Nilai</p>
                        </div>
                    </div>

                    <!-- Info pills -->
                    <div class="flex flex-wrap gap-3 mt-4">
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                            <Clock class="h-3.5 w-3.5 text-green-600" />
                            <span>{{ quiz.duration_minutes }} Menit</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                            <StickyNote class="h-3.5 w-3.5 text-green-600" />
                            <span>{{ quiz.questions_count }} Soal Pilihan Ganda</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                            <Trophy class="h-3.5 w-3.5 text-green-600" />
                            <span>Passing Grade 70</span>
                        </div>
                    </div>
                </div>

                <!-- Petunjuk -->
                <div class="border-t border-gray-100 bg-gray-50/50 px-5 sm:px-6 py-4">
                    <p class="text-xs font-semibold text-gray-600 mb-3">Petunjuk Pengerjaan</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                        <div v-for="(tip, i) in [
                            { title: 'Pastikan Koneksi Internet', desc: 'Koneksi stabil mencegah gangguan ujian.' },
                            { title: 'Waktu Berjalan', desc: 'Timer mulai saat klik Mulai Quiz.' },
                            { title: 'Tidak Dapat Dijeda', desc: 'Waktu terus berjalan jika keluar halaman.' },
                            { title: 'Auto Submit', desc: 'Jawaban dikumpulkan otomatis saat waktu habis.' },
                        ]" :key="i" class="flex items-start gap-2">
                            <div class="mt-0.5 w-4 h-4 rounded-full bg-green-500 flex items-center justify-center shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-700">{{ tip.title }}</p>
                                <p class="text-xs text-gray-400 leading-snug mt-0.5">{{ tip.desc }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action button -->
                <div class="px-5 sm:px-6 py-4 border-t border-gray-100">
                    <button @click="quiz.submitted_at ? router.visit(`/mahasiswa/evaluasi/quiz/${quiz.id}/result`) : startQuiz(quiz.id)"
                        class="w-full flex items-center justify-center gap-2 py-3 rounded-xl font-semibold text-sm transition-colors"
                        :class="quiz.submitted_at
                            ? 'bg-gray-100 hover:bg-gray-200 text-gray-700'
                            : (quiz.is_ongoing ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-sm' : 'bg-green-600 hover:bg-green-700 text-white shadow-sm')">
                        <span>{{ quiz.submitted_at ? 'Lihat Hasil' : (quiz.is_ongoing ? 'Lanjutkan Quiz' : 'Mulai Quiz') }}</span>
                        <ChevronRight class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </Layout>
</template>
