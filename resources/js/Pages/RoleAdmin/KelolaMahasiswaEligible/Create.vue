<script setup>
import Layout from '../../../App.vue';
import { useForm, router } from '@inertiajs/vue3';

const form = useForm({
    nama : '',
    nim : '',
});

const cancel = () => {
    router.visit(`/admin/mahasiswa-eligible`, {
        preserveScroll: true,
    })
}

function store() {
    form.post('/admin/mahasiswa-eligible');
}
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Tambah Data Master Mahasiswa</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Halaman untuk menambahkan data mahasiswa yang eligible untuk mendaftar</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        Informasi Mahasiswa
                    </h2>

                    <form @submit.prevent="store" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <!-- NIM -->
                            <div class="space-y-1.5">
                                <label for="nim" class="block text-xs font-medium text-gray-600">NIM</label>
                                <input
                                    id="nim"
                                    v-model="form.nim"
                                    type="text"
                                    placeholder="Nomor Induk Mahasiswa"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.nim" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.nim }}
                                </small>
                            </div>

                            <!-- Nama -->
                            <div class="space-y-1.5">
                                <label for="nama" class="block text-xs font-medium text-gray-600">Nama Lengkap</label>
                                <input
                                    id="nama"
                                    v-model="form.nama"
                                    type="text"
                                    placeholder="Nama lengkap"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.nama" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.nama }}
                                </small>
                            </div>
                        </div>

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
                                    Simpan Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>
