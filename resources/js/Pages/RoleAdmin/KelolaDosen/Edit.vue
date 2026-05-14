<script setup>
import Layout from '../../../App.vue';
import { useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    dosen: Object,
    errors: Object
})

const cancel = () => {
    router.visit(`/admin/dosen`, {
        preserveScroll: true,
    })
}

const form = useForm({
    name: props.dosen.user.name,
    email: props.dosen.user.email,
    password: '', 
    password_confirmation: '',
    nuptk: props.dosen.nuptk,
    jabatan: props.dosen.jabatan,
});

const submit = () => {
    form.put(`/admin/dosen/${props.dosen.id}`, {
        preserveScroll: true,
    });
};

</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Kelola Dosen</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Halaman untuk mengelola data dosen</p>
            </div>  
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        Informasi Dosen
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
                                <label for="nuptk" class="block text-xs font-medium text-gray-600">NUPTK</label>
                                <input
                                    id="nuptk"
                                    v-model="form.nuptk"
                                    type="text"
                                    placeholder="Nomor Unik Pendidik dan Tenaga Kependidikan"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.nuptk" class="text-red-500 text-xs d-block">
                                    {{ form.errors.nuptk }}
                                </small>
                            </div>

                            <!-- Jabatan -->
                            <div class="space-y-1.5">
                                <label for="jabatan" class="block text-xs font-medium text-gray-600">Jabatan</label>
                                <input
                                    id="jabatan"
                                    v-model="form.jabatan"
                                    type="text"
                                    placeholder="Contoh: Dosen Biologi"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.jabatan" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.jabatan }}
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
