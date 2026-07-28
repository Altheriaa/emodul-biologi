<script setup>
import Layout from '../../../../App.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft, User, Search, NotepadText } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    materi: Object,
    essays: Array
});

const searchQuery = ref('');

const filteredEssays = computed(() => {
    if (!searchQuery.value) return groupedEssays.value;
    return groupedEssays.value.filter(group => 
        (group.mahasiswa?.user?.name || '').toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        (group.mahasiswa?.nim || '').toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const groupedEssays = computed(() => {
    // Group answers by mahasiswa
    const groups = {};
    props.materi?.essay_questions?.forEach(question => {
        question.answers?.forEach(answer => {
            const mId = answer.mahasiswa_id;
            if (!groups[mId]) {
                groups[mId] = {
                    mahasiswa: answer.mahasiswa,
                    created_at: answer.created_at,
                    answers: []
                };
            }
            groups[mId].answers.push({
                question: question.pertanyaan,
                jawaban: answer.jawaban
            });
        });
    });
    return Object.values(groups).sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

const goBack = () => {
    router.visit('/admin/pembelajaran/materi');
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <Button @click="goBack" variant="outline" class="mb-4 h-8 px-3 text-xs">
                        <ArrowLeft class="w-3 h-3 mr-1" /> Kembali
                    </Button>
                    <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Monitoring Essay Mahasiswa</p>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Materi: <span class="font-semibold text-green-600">{{ materi.judul }}</span></p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Soal Essay -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                <h3 class="font-semibold text-sm text-gray-800 mb-4 flex items-center gap-2">
                    <NotepadText class="w-4 h-4 text-green-600" />
                    Daftar Soal Quiz:
                </h3>
                <div v-if="materi.essay_questions && materi.essay_questions.length > 0" class="space-y-3">
                    <div v-for="(question, index) in materi.essay_questions" :key="question.id" class="flex gap-3">
                        <span class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold shrink-0">{{ index + 1 }}</span>
                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-sm text-gray-700 whitespace-pre-line w-full">
                            {{ question.pertanyaan }}
                        </div>
                    </div>
                </div>
                <div v-else class="text-sm text-gray-500 italic">
                    Tidak ada quiz untuk materi ini.
                </div>
            </div>

            <!-- Daftar Jawaban -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="font-semibold text-gray-800">Jawaban Mahasiswa ({{ groupedEssays.length }})</h3>
                    
                    <div class="relative w-full sm:w-64">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                        <input 
                            v-model="searchQuery"
                            type="text" 
                            placeholder="Cari nama atau NIM..." 
                            class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 transition-all"
                        >
                    </div>
                </div>

                <div v-if="filteredEssays.length > 0" class="divide-y divide-gray-100">
                    <div v-for="(group, index) in filteredEssays" :key="index" class="p-5 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-1 font-bold text-green-700">
                                {{ group.mahasiswa?.user?.name ? group.mahasiswa.user.name.charAt(0).toUpperCase() : 'M' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-3 border-b border-gray-100 pb-3">
                                    <div>
                                        <h4 class="font-semibold text-gray-800 text-base">{{ group.mahasiswa?.user?.name || 'Mahasiswa' }}</h4>
                                        <p class="text-xs text-gray-500 font-mono mt-0.5">{{ group.mahasiswa?.nim }}</p>
                                    </div>
                                    <span class="text-[11px] text-gray-500 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-200 shadow-sm flex items-center gap-1">
                                        ⏱️ {{ new Date(group.created_at).toLocaleString('id-ID') }}
                                    </span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div v-for="(ans, i) in group.answers" :key="i" class="bg-white border border-gray-100 p-4 rounded-lg shadow-sm">
                                        <div class="text-xs font-semibold text-gray-500 mb-1 border-b border-gray-50 pb-1">Soal {{ i + 1 }}</div>
                                        <p class="text-sm text-gray-600 mb-2">{{ ans.question }}</p>
                                        <div class="bg-green-50/50 p-3 rounded text-sm text-gray-800 whitespace-pre-line border border-green-50">
                                            <span class="font-semibold text-green-700 block mb-1">Jawaban:</span>
                                            {{ ans.jawaban }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div v-else class="p-10 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                        <NotepadText class="w-8 h-8 text-gray-300" />
                    </div>
                    <p class="text-gray-500 font-medium">Belum ada mahasiswa yang menjawab essay ini</p>
                    <p class="text-xs text-gray-400 mt-1">Atau tidak ada hasil yang cocok dengan pencarian Anda</p>
                </div>
            </div>
        </div>
    </Layout>
</template>
