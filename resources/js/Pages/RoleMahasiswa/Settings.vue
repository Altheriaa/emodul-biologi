<script setup>
import Layout from '../../App.vue';
import { ref } from 'vue';

const form = ref({
    name: 'Budi Santoso',
    email: 'budi@example.com',
    nim: '22810001',
    angkatan: '2022',
    prodi: 'Pendidikan Biologi',
    jenis_kelamin: 'Laki-laki',
    password: '',
    password_confirmation: '',
});

const saved = ref(false);

const submit = () => {
    // placeholder — backend nanti
    saved.value = true;
    setTimeout(() => { saved.value = false; }, 3000);
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Pengaturan Profil</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Kelola informasi data diri mahasiswa</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Avatar Card -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 rounded-xl p-6 flex flex-col items-center gap-4 shadow-sm">
                    <!-- Avatar -->
                    <div class="w-24 h-24 rounded-full bg-linear-to-br from-green-600 to-emerald-700 flex items-center justify-center text-3xl font-bold text-white">
                        {{ form.name.charAt(0) }}
                    </div>
                    <div class="text-center">
                        <p class="text-gray-800 font-semibold text-lg">{{ form.name }}</p>
                        <p class="text-gray-400 text-sm">{{ form.nim }}</p>
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs bg-green-50 text-green-700 border border-green-200">
                            Mahasiswa Aktif
                        </span>
                    </div>

                    <hr class="w-full border-gray-100">

                    <!-- Info ringkas -->
                    <div class="w-full space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">Prodi</span>
                            <span class="text-xs text-gray-700">{{ form.prodi }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">Angkatan</span>
                            <span class="text-xs text-gray-700">{{ form.angkatan }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-400">Jenis Kelamin</span>
                            <span class="text-xs text-gray-700">{{ form.jenis_kelamin }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        Informasi Pribadi
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
                            </div>

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
                            </div>

                            <!-- Angkatan -->
                            <div class="space-y-1.5">
                                <label for="angkatan" class="block text-xs font-medium text-gray-600">Angkatan</label>
                                <input
                                    id="angkatan"
                                    v-model="form.angkatan"
                                    type="text"
                                    placeholder="Contoh: 2022"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="space-y-1.5">
                                <label for="jenis_kelamin" class="block text-xs font-medium text-gray-600">Jenis Kelamin</label>
                                <select
                                    id="jenis_kelamin"
                                    v-model="form.jenis_kelamin"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                >
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                        </div>

                        <hr class="border-gray-100 my-2">

                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mt-4">Ubah Password</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label for="password" class="block text-xs font-medium text-gray-600">Password Baru</label>
                                <input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="••••••••"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
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
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-2">
                            <Transition enter-active-class="transition duration-300" enter-from-class="opacity-0 translate-y-1" leave-active-class="transition duration-200" leave-to-class="opacity-0">
                                <span v-if="saved" class="flex items-center gap-1.5 text-xs text-green-600">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                    Perubahan berhasil disimpan
                                </span>
                                <span v-else></span>
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
