<script setup>
import Layout from '../../../../App.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Plus, LucidePencil, LucideTrash2, X, CheckCircle2 } from 'lucide-vue-next';
import { usePage } from '@inertiajs/vue3';
import { onMounted, watch } from 'vue';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';

const props = defineProps({
    quiz: Object,
    questions: Array,
    errors: Object,
});

const page = usePage();

const showFlashMessage = () => {
    const flash = page.props.flash;
    if (flash?.success) {
        Toast.fire({ icon: 'success', title: 'Berhasil!', text: flash.success, timer: 2000 });
    }
    if (page.props.errors && Object.keys(page.props.errors).length > 0) {
        const msg = Object.values(page.props.errors).join('<br>');
        Toast.fire({ icon: 'error', title: 'Oops...', html: msg });
    }
};

onMounted(showFlashMessage);
watch(() => page.props.flash, showFlashMessage, { deep: true });

// ─── Quiz info form ────────────────────────────────────────────
const quizForm = useForm({
    title: props.quiz.title,
    description: props.quiz.description,
    duration_minutes: props.quiz.duration_minutes,
    status: props.quiz.status,
});

const cancel = () => router.visit('/dosen/evaluasi/bank-soal', { preserveScroll: true });

const submitQuiz = () => {
    quizForm.put(`/dosen/evaluasi/bank-soal/${props.quiz.id}`, { preserveScroll: true });
};

// ─── Modal state ───────────────────────────────────────────────
const showModal     = ref(false);
const editingQuestion = ref(null); // null = tambah baru

const LABELS = ['A', 'B', 'C', 'D', 'E'];

const defaultOptions = () => [
    { option_text: '', is_correct: false },
    { option_text: '', is_correct: false },
    { option_text: '', is_correct: false },
    { option_text: '', is_correct: false },
];

const questionForm = useForm({
    question_text: '',
    options: defaultOptions(),
});

const modalTitle = computed(() => editingQuestion.value ? 'Edit Pertanyaan' : 'Tambah Pertanyaan');

const openCreate = () => {
    editingQuestion.value = null;
    questionForm.reset();
    questionForm.question_text = '';
    questionForm.options = defaultOptions();
    showModal.value = true;
};

const openEdit = (q) => {
    editingQuestion.value = q;
    questionForm.question_text = q.question_text;
    questionForm.options = q.options.map(o => ({
        option_text: o.option_text,
        is_correct: o.is_correct,
    }));
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingQuestion.value = null;
};

const addOption = () => {
    if (questionForm.options.length < 5) {
        questionForm.options.push({ option_text: '', is_correct: false });
    }
};

const removeOption = (idx) => {
    if (questionForm.options.length > 2) {
        questionForm.options.splice(idx, 1);
    }
};

const setCorrect = (idx) => {
    questionForm.options.forEach((o, i) => { o.is_correct = i === idx; });
};

const modalError = ref('');

const submitQuestion = () => {
    modalError.value = '';
    const correctCount = questionForm.options.filter(o => o.is_correct).length;
    if (correctCount !== 1) {
        modalError.value = 'Pilih tepat satu jawaban yang benar.';
        return;
    }

    if (editingQuestion.value) {
        questionForm.put(
            `/dosen/evaluasi/bank-soal/${props.quiz.id}/soal/${editingQuestion.value.id}`,
            { onSuccess: closeModal, preserveScroll: true }
        );
    } else {
        questionForm.post(
            `/dosen/evaluasi/bank-soal/${props.quiz.id}/soal`,
            { onSuccess: closeModal, preserveScroll: true }
        );
    }
};

const confirmDelete = (q) => {
    Swal.fire({
        title: 'Hapus Pertanyaan?',
        text: 'Pertanyaan ini akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#344767',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/dosen/evaluasi/bank-soal/${props.quiz.id}/soal/${q.id}`, {
                preserveScroll: true,
            });
        }
    });
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Edit Bank Soal</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Kelola informasi dan pertanyaan bank soal</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Quiz Info Form -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5">Informasi Bank Soal</h2>
                    <form @submit.prevent="submitQuiz" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label for="title" class="block text-xs font-medium text-gray-600">Judul Soal</label>
                                <input id="title" v-model="quizForm.title" type="text"
                                    placeholder="Judul Soal : Anatomi Tumbuhan"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition" />
                                <small v-if="quizForm.errors.title" class="text-red-500 text-xs">{{ quizForm.errors.title }}</small>
                            </div>
                            <div class="space-y-1.5">
                                <label for="description" class="block text-xs font-medium text-gray-600">Deskripsi</label>
                                <input id="description" v-model="quizForm.description" type="text"
                                    placeholder="Deskripsi Soal"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition" />
                                <small v-if="quizForm.errors.description" class="text-red-500 text-xs">{{ quizForm.errors.description }}</small>
                            </div>
                            <div class="space-y-1.5">
                                <label for="durasi" class="block text-xs font-medium text-gray-600">Durasi Pengerjaan (Menit)</label>
                                <input id="durasi" v-model="quizForm.duration_minutes" type="number"
                                    placeholder="30"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition" />
                                <small v-if="quizForm.errors.duration_minutes" class="text-red-500 text-xs">{{ quizForm.errors.duration_minutes }}</small>
                            </div>
                            <div class="space-y-1.5">
                                <label for="status" class="block text-xs font-medium text-gray-600">Status</label>
                                <select id="status" v-model="quizForm.status"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition">
                                    <option value="">Pilih Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                                <small v-if="quizForm.errors.status" class="text-red-500 text-xs">{{ quizForm.errors.status }}</small>
                            </div>
                        </div>
                        <hr class="border-gray-100" />
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="cancel"
                                class="outline px-6 py-2 rounded-lg hover:bg-gray-100 text-gray-800 text-sm font-medium transition-colors shadow-sm">
                                Batal
                            </button>
                            <button type="submit" :disabled="quizForm.processing"
                                class="px-6 py-2 rounded-lg bg-green-700 hover:bg-green-800 text-white text-sm font-medium transition-colors shadow-sm disabled:opacity-50">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Pertanyaan -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-sm font-semibold text-gray-800">
                            Daftar Pertanyaan
                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ questions.length }} soal
                            </span>
                        </h2>
                        <Button variant="outline" @click="openCreate"
                            class="h-9 border-green-600 text-green-700 hover:bg-green-50 gap-1.5">
                            <Plus class="h-4 w-4" />
                            <span class="hidden sm:inline">Tambah Pertanyaan</span>
                            <span class="sm:hidden">Tambah</span>
                        </Button>
                    </div>

                    <!-- Empty State -->
                    <div v-if="questions.length === 0"
                        class="text-center py-16 text-gray-400 border-2 border-dashed border-gray-200 rounded-xl">
                        <p class="font-medium text-gray-500">Belum ada pertanyaan</p>
                        <p class="text-sm mt-1">Klik "Tambah Pertanyaan" untuk mulai menambahkan soal.</p>
                    </div>

                    <!-- Question List -->
                    <div v-else class="space-y-3">
                        <div v-for="(q, index) in questions" :key="q.id"
                            class="group hover:bg-green-50 transition-colors flex gap-4 border-2 border-gray-200 rounded-xl px-4 py-4 items-start hover:border-green-200">
                            <div class="flex-shrink-0 rounded-full bg-green-600 w-8 h-8 flex justify-center items-center mt-0.5">
                                <span class="text-xs text-white font-bold">{{ index + 1 }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800 font-medium leading-snug">{{ q.question_text }}</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span v-for="opt in q.options" :key="opt.id"
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs border"
                                        :class="opt.is_correct
                                            ? 'bg-green-50 border-green-300 text-green-700 font-semibold'
                                            : 'bg-gray-50 border-gray-200 text-gray-500'">
                                        <CheckCircle2 v-if="opt.is_correct" class="h-3 w-3" />
                                        <span class="font-bold">{{ opt.option_label }}.</span> {{ opt.option_text }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-shrink-0 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEdit(q)"
                                    class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 hover:text-gray-800 transition-colors">
                                    <LucidePencil class="h-4 w-4" />
                                </button>
                                <button @click="confirmDelete(q)"
                                    class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-600 transition-colors">
                                    <LucideTrash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah/Edit Pertanyaan -->
        <Teleport to="body">
            <Transition name="modal-fade">
                <div v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    @click.self="closeModal">
                    <!-- Backdrop -->
                    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeModal"></div>

                    <!-- Modal Card -->
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <!-- Modal Header -->
                        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
                            <h3 class="text-base font-semibold text-gray-800">{{ modalTitle }}</h3>
                            <button @click="closeModal"
                                class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                                <X class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 space-y-5">
                            <!-- Error -->
                            <div v-if="modalError" class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                                {{ modalError }}
                            </div>

                            <!-- Pertanyaan -->
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-gray-600">Pertanyaan <span class="text-red-500">*</span></label>
                                <textarea v-model="questionForm.question_text" rows="3"
                                    placeholder="Tuliskan pertanyaan di sini..."
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition resize-none"></textarea>
                                <small v-if="questionForm.errors.question_text" class="text-red-500 text-xs">{{ questionForm.errors.question_text }}</small>
                            </div>

                            <!-- Pilihan Jawaban -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-medium text-gray-600">
                                        Pilihan Jawaban <span class="text-red-500">*</span>
                                        <span class="ml-1 text-gray-400 font-normal">(klik lingkaran untuk pilih jawaban benar)</span>
                                    </label>
                                    <button v-if="questionForm.options.length < 5" type="button"
                                        @click="addOption"
                                        class="text-xs text-green-700 hover:text-green-800 font-medium flex items-center gap-1">
                                        <Plus class="h-3.5 w-3.5" /> Tambah Opsi
                                    </button>
                                </div>

                                <div v-for="(opt, idx) in questionForm.options" :key="idx"
                                    class="flex items-center gap-3">
                                    <!-- Correct indicator -->
                                    <button type="button" @click="setCorrect(idx)"
                                        class="flex-shrink-0 w-7 h-7 rounded-full border-2 flex items-center justify-center transition-colors"
                                        :class="opt.is_correct
                                            ? 'bg-green-600 border-green-600'
                                            : 'border-gray-300 hover:border-green-400'">
                                        <CheckCircle2 v-if="opt.is_correct" class="h-4 w-4 text-white" />
                                    </button>

                                    <!-- Label -->
                                    <span class="flex-shrink-0 w-6 text-sm font-bold text-gray-500">{{ LABELS[idx] }}.</span>

                                    <!-- Input -->
                                    <input v-model="opt.option_text" type="text"
                                        :placeholder="`Opsi ${LABELS[idx]}`"
                                        class="flex-1 px-3 py-2 rounded-lg bg-gray-50 border text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 transition"
                                        :class="opt.is_correct
                                            ? 'border-green-400 focus:border-green-500 focus:ring-green-100'
                                            : 'border-gray-200 focus:border-green-500 focus:ring-green-100'" />

                                    <!-- Remove -->
                                    <button v-if="questionForm.options.length > 2" type="button"
                                        @click="removeOption(idx)"
                                        class="flex-shrink-0 p-1 rounded hover:bg-red-50 text-gray-300 hover:text-red-500 transition-colors">
                                        <X class="h-4 w-4" />
                                    </button>
                                </div>

                                <small v-if="questionForm.errors.options" class="text-red-500 text-xs block">
                                    {{ questionForm.errors.options }}
                                </small>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" @click="closeModal"
                                class="px-5 py-2 rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700 text-sm font-medium transition-colors">
                                Batal
                            </button>
                            <button type="button" @click="submitQuestion"
                                :disabled="questionForm.processing"
                                class="px-5 py-2 rounded-lg bg-green-700 hover:bg-green-800 text-white text-sm font-medium transition-colors disabled:opacity-50">
                                {{ editingQuestion ? 'Simpan Perubahan' : 'Tambah Pertanyaan' }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </Layout>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
