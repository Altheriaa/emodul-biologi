<script setup>
import Layout from '../../../App.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    mahasiswa: Object,
    errors: Object
})

const form = useForm({
    name: props.mahasiswa.user.name,
    email: props.mahasiswa.user.email,
    password: '', 
    password_confirmation: '',
    nim: props.mahasiswa.nim,
    angkatan: props.mahasiswa.angkatan,
});

const submit = () => {
    form.put(`/admin/mahasiswa/${props.mahasiswa.id}`, {
        preserveScroll: true,
    });
};

</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Kelola Mahasiswa</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Halaman untuk mengelola data Mahasiswa</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        Informasi Mahasiswa
                    </h2>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <!-- Nama -->
                            <div class="space-y-1.5">
                                <label for="name" class="block text-xs font-medium text-gray-600">Nama Lengkap</label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Nama lengkap"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.name" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.name }}
                                </small>
                            </div>

                            <!-- Email -->
                            <div class="space-y-1.5">
                                <label for="email" class="block text-xs font-medium text-gray-600">Email</label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="email@example.com"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.email" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.email }}
                                </small>
                            </div>

                            <!-- NUPTK -->
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

                            <!-- Jabatan -->
                            <div class="space-y-1.5">
                                <label for="angkatan" class="block text-xs font-medium text-gray-600">Angkatan</label>
                                <input
                                    id="angkatan"
                                    v-model="form.angkatan"
                                    type="text"
                                    placeholder="Contoh: 2020"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.angkatan" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.angkatan }}
                                </small>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-2">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label for="password" class="block text-xs font-medium text-gray-600">Password</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.password" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.password }}
                                </small>
                                <p class="text-[11px] text-gray-400">Minimal 8 karakter</p>
                            </div>
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-600">Konfirmasi Password</label>
                                <input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.password_confirmation" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.password_confirmation }}
                                </small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-2">
                            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-1" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                                <span v-if="form.wasSuccessful" class="flex items-center gap-1.5 text-xs text-green-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                    Perubahan berhasil disimpan
                                </span>
                            </Transition>
                            <button
                                type="submit"
                                class="ml-auto px-6 py-2 rounded-lg bg-green-700 hover:bg-green-800 text-white text-sm font-medium transition-colors shadow-sm"
                            >
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </Layout>
</template>
