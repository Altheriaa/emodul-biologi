<script setup>
import Layout from '../../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote, Plus, Trash, Pencil } from 'lucide-vue-next';
import { router, usePage } from '@inertiajs/vue3';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';
import { ref, onMounted, watch, computed } from 'vue';

const props = defineProps({
    materis: Array,
})

const CreateMateri = () => {
    router.visit("/dosen/pembelajaran/materi/create");
}

const Show = (id) => {
    router.visit(`/dosen/pembelajaran/materi/${id}`);
}; 

const OpenEdit = (id) => {
    router.visit(`/dosen/pembelajaran/materi/${id}/edit`);
}; 

// confirm delete
const confirmDelete = (id) => {
    Swal.fire({
        title: 'Hapus Materi?',
        text: `Materi "${id.judul}" akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#344767',
        reverseButtons: true,
        customClass: {
            popup: 'glass-popup rounded-3xl shadow-blur p-6',
            title: 'font-weight-bold',
            confirmButton: 'px-4 py-2 rounded-xl',
            cancelButton: 'px-4 py-2 rounded-xl',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/dosen/pembelajaran/materi/${id}`);
        }
    });
};

// sweet alert toast
const page = usePage();

const showFlashMessage = () => {
    const flash = page.props.flash;
    const errors = page.props.errors;

    if (flash.success) {
        Toast.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: flash.success,
            showConfirmButton: false,
            customClass: {
                popup: 'glass-popup rounded-3xl shadow-blur p-6',
                title: 'font-semibold',
                icon: 'icon-custom bg-transparent'
            },
            timer: 2000
        });
    } else if (flash.warning) {
        Toast.fire({
            icon: 'warning',
            text: flash.warning,
            showConfirmButton: false,
            customClass: {
                popup: 'glass-popup rounded-3xl shadow-blur p-6',
                title: 'font-semibold',
                icon: 'icon-custom bg-transparent'
            },
            timer: 2000
        });
    }

    if (Object.keys(errors).length > 0) {
        const errorMessages = Object.values(errors).join('<br>');
        Toast.fire({
            icon: 'error',
            title: 'Oops...',
            html: errorMessages,
            customClass: {
                popup: 'glass-popup rounded-3xl shadow-blur p-6',
                title: 'font-bold',
                confirmButton: 'button-confirm px-6 py-2 rounded-xl text-white',
            }
        });
    }
};

onMounted(() => {
    showFlashMessage();
});

watch(() => page.props.flash, () => {
    showFlashMessage();
}, { deep: true });

</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-2 sm:mb-4">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Daftar Materi E-Modul Anatomi Tumbuhan</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Daftar materi yang akan dipelajari pada mata kuliah Anatomi Tumbuhan</p>
                <Button variant="outline" @click="CreateMateri" class="text-xs sm:text-sm justify-self-end border-green-600 text-green-700 hover:bg-green-50 mt-3">
                    <Plus class="w-4 h-4 sm:w-2 sm:h-2" />
                    Tambah Materi
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- grid 1 -->
            <div v-for="item in materis" class="col-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 aspect-video">
                <img loading="lazy" :src="item.cover_path ? `/storage/${item.cover_path}` : null" alt="" class="shadow-lg w-full h-full object-cover rounded-xl mb-5">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight mb-3">{{ item.judul }}</h2>
                <p class="text-xs sm:text-base text-gray-400 mb-4">{{ item.deskripsi }}</p>
                <hr class="border-gray-200 mt-2 mb-5">
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
                    <div class="flex items-center gap-2">
                        <StickyNote class="w-4 h-4" />
                        <span class="text-xs sm:text-sm text-gray-700">{{ item.jumlah_halaman }} Halaman</span>
                    </div>
                    <div class="flex gap-1.5 sm:gap-2 justify-end">
                        <Button variant="outline" @click="OpenEdit(item.id)" class="h-8 w-8 p-0 sm:h-9 sm:w-9 border-yellow-600 text-yellow-700 hover:bg-yellow-50">
                            <Pencil class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                        </Button>
                        <Button variant="outline" @click="confirmDelete(item.id)" class="h-8 w-8 p-0 sm:h-9 sm:w-9 border-red-600 text-red-700 hover:bg-red-50">
                            <Trash class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
                        </Button>
                        <Button variant="outline" @click="Show(item.id)" class="h-8 px-2 sm:h-9 sm:px-4 border-green-600 text-green-700 hover:bg-green-50 text-[10px] sm:text-sm font-medium">
                            <ArrowRight class="w-3.5 h-3.5 sm:w-4 sm:h-4 " />
                            <span class="inline">Mulai Belajar</span>
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

