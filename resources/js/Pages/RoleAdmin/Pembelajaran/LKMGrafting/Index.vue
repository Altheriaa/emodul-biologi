<script setup>
import Layout from '../../../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote } from 'lucide-vue-next';
import { router, usePage, Link } from '@inertiajs/vue3';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';
import { ref, onMounted, watch, computed } from 'vue';
import LKMTabs from '@/components/LKMTabs.vue';
import { Search, Plus, UserRoundCog } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger, } from '@/components/ui/dropdown-menu';

const props = defineProps({
    materis: Array,
})

// const CreateMateri = () => {
//     router.visit("/admin/pembelajaran/materi/create");
// }

// const Show = (id) => {
//     router.visit(`/admin/pembelajaran/materi/${id}`);
// }; 

// const OpenEdit = (id) => {
//     router.visit(`/admin/pembelajaran/materi/${id}/edit`);
// }; 


// confirm delete
const confirmDelete = (item) => {
    Swal.fire({
        title: 'Hapus Materi?',
        text: `Materi "${item.judul}" akan dihapus permanen!`,
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
            router.delete(`/admin/pembelajaran/materi/${item.id}`);
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
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 shadow-sm">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Daftar LKM <i>Grafting</i></p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Daftar LKM yang akan dipelajari pada mata kuliah <i>Grafting</i></p>
                <!-- <Button variant="outline" @click="CreateMateri" class="text-xs sm:text-sm justify-self-end border-green-600 text-green-700 hover:bg-green-50 mt-3">
                    <Plus class="w-4 h-4 sm:w-2 sm:h-2" />
                    Tambah LKM
                </Button> -->
            </div>
        </div>

        <!-- Tab -->
        <LKMTabs />

        <!-- Content Tabel -->
        <slot />
        <!-- End Content Tabel -->

    </Layout>
</template>

