<script setup>
import Button from '@/components/ui/button/Button.vue';
import { LucideX } from 'lucide-vue-next';
import Layout from '../../../App.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    materi: Object,
    errors: Object
});

const form = useForm({
    _method: 'put',
    judul : props.materi.judul,
    deskripsi : props.materi.deskripsi,
    tanggal_rilis : props.materi.tanggal_rilis,
    link_flipping : props.materi.link_flipping,
    jumlah_halaman: props.materi.jumlah_halaman,
    cover_path: null,
    remove_cover_path: false,
});

const preview = ref(props.materi.cover_path ? `/storage/${props.materi.cover_path}` : null);

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.cover_path = file;
        form.remove_cover_path = false;
        preview.value = URL.createObjectURL(file);
    }
};

 const removePhoto = () => {
    form.cover_path = null;
    preview.value = null;
    form.remove_cover_path = true;
    const input = document.getElementById('cover_path_input');
    if (input) input.value = '';
};

const submit = () => {
    form.post(`/dosen/pembelajaran/materi/${props.materi.id}`, {
        forceFormData: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Tambah Data Materi E-Modul Anatomi Tumbuhan</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Halaman untuk mengelola data materi e-modul anatomi tumbuhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Card -->
            <div class="lg:col-span-3 space-y-5">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-5 flex items-center gap-2">
                        Informasi Materi
                    </h2>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <!-- Nama -->
                            <div class="space-y-1.5">
                                <label for="judul" class="block text-xs font-medium text-gray-600">Judul Flipping Book</label>
                                <input
                                    id="judul"
                                    v-model="form.judul"
                                    type="text"
                                    placeholder="Contoh : Anatomi Tumbuhan"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.judul" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.judul }}
                                </small>
                            </div>
                            
                            <!-- Tanggal -->
                            <div class="space-y-1.5">
                                <label for="tanggal_rilis" class="block text-xs font-medium text-gray-600">Tanggal Rilis</label>
                                <input
                                    id="tanggal_rilis"
                                    v-model="form.tanggal_rilis"
                                    type="date"
                                    placeholder="Contoh: 2024"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.tanggal_rilis" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.tanggal_rilis }}
                                </small>
                            </div>

                            <!-- Link Flipping -->
                            <div class="space-y-1.5">
                                <label for="link_flipping" class="block text-xs font-medium text-gray-600">Link Flipping Book</label>
                                <input
                                    id="link_flipping"
                                    v-model="form.link_flipping"
                                    type="text"
                                    placeholder="https://example.com"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.link_flipping" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.link_flipping }}
                                </small>
                            </div>

                            <div class="space-y-1.5">
                                <label for="jumlah_halaman" class="block text-xs font-medium text-gray-600">Jumlah Halaman</label>
                                <input
                                    id="jumlah_halaman"
                                    v-model="form.jumlah_halaman"
                                    type="number"
                                    placeholder="Jumlah Halaman"
                                    class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.jumlah_halaman" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.jumlah_halaman }}
                                </small>
                            </div>

                            <!-- Cover -->
                            <div class="space-y-3">
                                <label class="block text-xs font-medium text-gray-600">Cover Materi (Gambar)</label>
                                
                                <div class="flex items-start gap-5">
                                    <!-- Preview -->
                                    <div v-if="preview" class="w-32 h-44 rounded-lg border-2 border-dashed border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center relative">
                                        <img :src="preview" class="w-full h-full object-cover" />
                                        <Button @click="removePhoto" variant="delete" size="sm" class="absolute top-0 right-0" > <LucideX/> </Button>
                                    </div>
                                    <div v-else class="w-32 h-44 rounded-lg border-2 border-dashed border-gray-200 bg-gray-50 flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="text-[10px]">No Preview</span>
                                    </div>
                                    

                                    <!-- Input -->
                                    <div class="flex-1">
                                        <input
                                            type="file"
                                            id="cover_path"
                                            @change="onFileChange"
                                            accept="image/*"
                                            class="hidden"
                                        />
                                        <label
                                            for="cover_path"
                                            class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition-colors shadow-sm"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                            Pilih Gambar Cover
                                        </label>
                                        <p class="text-[11px] text-gray-400 mt-2">Format: JPG, PNG, WEBP (Maks. 2MB)</p>
                                        
                                        <small v-if="form.errors.cover_path" class="text-red-500 text-xs mt-1 block">
                                            {{ form.errors.cover_path }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="deskripsi" class="block text-xs font-medium text-gray-600">Deskripsi</label>
                                <textarea
                                    id="deskripsi"
                                    v-model="form.deskripsi"
                                    type="text"
                                    placeholder="Masukkan Deskripsi E-Modul"
                                    class="w-full h-50 px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                                />
                                <small v-if="form.errors.deskripsi" class="text-red-500 text-xs mt-1 d-block">
                                    {{ form.errors.deskripsi }}
                                </small>
                            </div>
                        </div>

                        <hr class="border-gray-100 my-2">

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