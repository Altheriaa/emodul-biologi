<script setup>
import Layout from '../../App.vue';
import { router } from '@inertiajs/vue3';
import { History, Search, FileText, CheckCircle2, XCircle, ChevronRight, ArrowLeft } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    history: Array,
});

const searchQuery = ref('');

const filteredHistory = computed(() => {
    if (!searchQuery.value) return props.history;
    return props.history.filter(item =>
        item.quiz_title.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const viewDetails = (quizId) => {
    router.visit(`/mahasiswa/evaluasi/quiz/${quizId}/result`);
};

const goBack = () => {
    router.visit('/mahasiswa/evaluasi/quiz');
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div>
                        <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Riwayat Nilai</p>
                        <p class="text-xs sm:text-sm text-green-600 mt-1">Daftar nilai kuis yang telah Anda selesaikan</p>
                    </div>
                </div>
                <button @click="goBack" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-green-700 transition-colors">
                    <ArrowLeft class="h-4 w-4" /> Kembali ke Quiz
                </button>
            </div>
        </div>

        <!-- Filters & Stats -->
        <!-- <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="lg:col-span-3">
                <div class="relative group">
                    <Search class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 group-focus-within:text-green-600 transition-colors" />
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari nama kuis..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500/20 focus:border-green-500 outline-none transition-all"
                    >
                </div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center justify-between shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase">Total Selesai</span>
                <span class="text-lg font-bold text-green-700">{{ history.length }}</span>
            </div>
        </div> -->

        <!-- History Table/List -->
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div v-if="filteredHistory.length > 0" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kuis</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Nilai</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="item in filteredHistory" :key="item.id" class="group hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-lg bg-gray-100 text-gray-500 group-hover:bg-green-100 group-hover:text-green-700 transition-colors">
                                        <FileText class="h-4 w-4" />
                                    </div>
                                    <span class="font-bold text-gray-800 text-sm">{{ item.quiz_title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-base font-bold" :class="item.is_passed ? 'text-green-700' : 'text-red-600'">
                                    {{ item.score }}
                                </span>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ item.correct_answers }}/{{ item.total_questions }} Benar</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span v-if="item.is_passed" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                    <CheckCircle2 class="h-3 w-3" /> Lulus
                                </span>
                                <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase">
                                    <XCircle class="h-3 w-3" /> Tidak Lulus
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-xs text-gray-500 font-medium">{{ item.submitted_at }}</span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <button
                                    @click="viewDetails(item.quiz_id)"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 hover:text-green-800 transition-colors"
                                >
                                    Detail <ChevronRight class="h-4 w-4" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div v-else class="py-20 flex flex-col items-center justify-center text-center px-4">
                <div class="bg-gray-50 p-6 rounded-full mb-4">
                    <History class="h-10 w-10 text-gray-300" />
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada riwayat</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-xs mx-auto">
                    {{ searchQuery ? 'Tidak ditemukan kuis dengan nama tersebut.' : 'Anda belum pernah mengerjakan kuis apapun.' }}
                </p>
                <button v-if="searchQuery" @click="searchQuery = ''" class="mt-4 text-sm font-bold text-green-700 underline">
                    Hapus Pencarian
                </button>
            </div>
        </div>
    </Layout>
</template>
