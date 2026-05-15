<script setup>
import Layout from '../../../../App.vue';
import { useForm, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const form = useForm({
    title: '',
    description: '',
    duration_minutes: '',
    status: '',
});

const cancel = () => {
    router.visit('/dosen/evaluasi/bank-soal', {
        preserveScroll: true,
    });
};

const store = () => {
    form.post('/dosen/evaluasi/bank-soal');
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Tambah Bank Soal</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Isi informasi dasar bank soal. Pertanyaan dapat ditambahkan setelah disimpan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Form Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-800 mb-5">Informasi Bank Soal</h2>

                <form @submit.prevent="store" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <!-- Title -->
                        <div class="space-y-1.5">
                            <label for="title" class="block text-xs font-medium text-gray-600">
                                Judul Soal <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Judul Soal : Anatomi Tumbuhan"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.title" class="text-red-500 text-xs block">
                                {{ form.errors.title }}
                            </small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="space-y-1.5">
                            <label for="description" class="block text-xs font-medium text-gray-600">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="description"
                                v-model="form.description"
                                type="text"
                                placeholder="Deskripsi Soal"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.description" class="text-red-500 text-xs block">
                                {{ form.errors.description }}
                            </small>
                        </div>

                        <!-- Durasi -->
                        <div class="space-y-1.5">
                            <label for="durasi" class="block text-xs font-medium text-gray-600">
                                Durasi Pengerjaan (Menit) <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="durasi"
                                v-model="form.duration_minutes"
                                type="number"
                                min="5"
                                max="180"
                                placeholder="30"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.duration_minutes" class="text-red-500 text-xs block">
                                {{ form.errors.duration_minutes }}
                            </small>
                        </div>

                        <!-- Status -->
                        <div class="space-y-1.5">
                            <label for="status" class="block text-xs font-medium text-gray-600">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="status"
                                v-model="form.status"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            >
                                <option value="">Pilih Status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                            <small v-if="form.errors.status" class="text-red-500 text-xs block">
                                {{ form.errors.status }}
                            </small>
                        </div>
                    </div>

                    <!-- Info note -->
                    <div class="bg-green-50 border border-green-100 rounded-lg px-4 py-3 text-xs text-green-600 flex items-start gap-2">
                        <p>Setelah bank soal disimpan, Anda akan diarahkan ke halaman daftar. Buka "Edit" untuk menambahkan pertanyaan beserta pilihan jawaban.</p>
                    </div>

                    <hr class="border-gray-100" />

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button
                            type="button"
                            @click="cancel"
                            class="outline px-6 py-2 rounded-lg hover:bg-gray-100 text-gray-800 text-sm font-medium transition-colors shadow-sm"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 rounded-lg bg-green-700 hover:bg-green-800 text-white text-sm font-medium transition-colors shadow-sm disabled:opacity-50"
                        >
                            Simpan Bank Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>
