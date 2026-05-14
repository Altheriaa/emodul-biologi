<script setup>
import Layout from '../../../../App.vue';
import { useForm, router } from '@inertiajs/vue3';

const form = useForm({
    title : '',
    description : '',
    duration_minutes : '',
    status : '',
});

const cancel = () => {
    router.visit(`/admin/evaluasi/bank-soal`, {
        preserveScroll: true,
    })
}

function store() {
    form.post('/admin/evaluasi/bank-soal');
}
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Tambah Bank Soal</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Halaman untuk mengelola bank soal</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        Informasi Bank Soal
                    </h2>

                    <form @submit.prevent="store" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <!-- Title -->
                            <div class="space-y-1.5">
                                <label for="title" class="block text-xs font-medium text-gray-600">Judul Soal</label>
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    placeholder="Judul Soal : Anatomi Tumbuhan"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.title" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.title }}
                                </small>
                            </div>

                            <!-- Deskripsi -->
                            <div class="space-y-1.5">
                                <label for="description" class="block text-xs font-medium text-gray-600">Deskripsi</label>
                                <input
                                    id="description"
                                    v-model="form.description"
                                    type="text"
                                    placeholder="Deskripsi Soal :"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.description" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.description }}
                                </small>
                            </div>

                            <!-- Durasi -->
                            <div class="space-y-1.5">
                                <label for="durasi" class="block text-xs font-medium text-gray-600">Durasi Pengerjaan (Menit)</label>
                                <input
                                    id="durasi"
                                    v-model="form.duration_minutes"
                                    type="number"
                                    placeholder="Durasi Pengerjaan : 30 menit"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.duration_minutes" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.duration_minutes }}
                                </small>
                            </div>

                            <!-- Jabatan -->
                            <div class="space-y-1.5">
                                <label for="status" class="block text-xs font-medium text-gray-600">Status</label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                >
                                    <option value="">Pilih Status</option>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="archived">Archived</option>
                                </select>
                                <small v-if="form.errors.status" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.status }}
                                </small>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-2">

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-2">
                            <div class="flex space-x-2 ml-auto">
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
                                    class="px-6 py-2 rounded-lg bg-green-700 hover:bg-green-800 text-white text-sm font-medium transition-colors shadow-sm"
                                >
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>
