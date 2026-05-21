<script setup>
import Layout from '../../Index.vue';

import { ref, onMounted, watch, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Search, Plus, UserRoundCog, CheckCircle2, Archive, Clock, XCircle } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger, } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { MoreHorizontal } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';

// Data Props From Controller
const props = defineProps({
    lkms: Object,
    filters: Object,
    errors : Object
});

// Search Functionality
const searchQuery = ref(props.filters?.search || '');
let searchTimeout = null;

watch(searchQuery, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/admin/pembelajaran/lkm-grafting/settings', 
            { search: value || undefined },
            { preserveState: true, replace: true }
        );
    }, 300);
});

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


const openCreate = () => {
    router.visit("/admin/pembelajaran/lkm-grafting/settings/create");
};

const openEdit = (id) => {
    router.visit(`/admin/pembelajaran/lkm-grafting/settings/${id}/edit`);
};  

// confirm delete
// const confirmDelete = (item) => {
//     Swal.fire({
//         title: 'Hapus Data?',
//         text: `Data "${item.title}" akan dihapus permanen!`,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonText: 'Ya, Hapus!',
//         cancelButtonText: 'Batal',
//         confirmButtonColor: '#d33',
//         cancelButtonColor: '#344767',
//         reverseButtons: true,
//         customClass: {
//             popup: 'glass-popup rounded-3xl shadow-blur p-6',
//             title: 'font-weight-bold',
//             confirmButton: 'px-4 py-2 rounded-xl',
//             cancelButton: 'px-4 py-2 rounded-xl',
//         }
//     }).then((result) => {
//         if (result.isConfirmed) {
//             router.delete(`/admin/pembelajaran/lkm-grafting/settings/${item.id}`);
//         }
//     });
// };
</script>

<template>
    <Layout>
        <div class="w-full mt-4">
            <Card class="border-gray-200 overflow-hidden">
                <div class="px-6 flex flex-row gap-2">
                    <Settings />
                    <h2 class="font-bold text-lg sm:text-xl text-gray-800">Settings LKM</h2> 
                </div>
                <hr class="border-gray-200">
                <CardHeader class="sm:px-6">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="relative w-full max-w-sm">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Cari Settings LKM..."
                                class="pl-8 h-10"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- <Button variant="ghost" size="sm" class="flex-1 md:flex-none h-10 border md:border-none">
                                <Filter class="mr-2 h-4 w-4" /> Filter
                            </Button> -->
                            <Button v-if="lkms.length < 4" variant="outline" @click="openCreate" class="flex-1 md:flex-none h-10 border-green-600 text-green-700 hover:bg-green-50">
                                <Plus class="h-4 w-4" />
                                <span class="hidden sm:inline">Tambah Setting</span>
                                <span class="sm:hidden">Tambah</span>
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                
                <CardContent class="p-0 sm:p-6 pt-0 sm:pt-0">
                    <div class="rounded-none sm:rounded-md border-y sm:border overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-gray-50/50">
                                    <TableHead class="w-[120px] pl-4 sm:pl-6">Dibuat Oleh</TableHead>
                                    <TableHead class="w-[120px] pl-4 sm:pl-6">Pertemuan</TableHead>
                                    <TableHead class="w-[120px] pl-4 sm:pl-6">Judul LKM</TableHead>
                                    <TableHead class="w-[120px] pl-4 sm:pl-6">Deskripsi</TableHead>
                                    <TableHead class="px-4">Dibuka Pada</TableHead>
                                    <TableHead class="px-4">Deadline</TableHead>
                                    <TableHead class="px-4">Aktif</TableHead>
                                    <TableHead class="px-4">Submit Waktu Terlambat</TableHead>
                                    <TableHead class="text-right pr-4 sm:pr-6">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="lkms.data.length === 0">
                                    <TableCell colspan="10" class="text-center py-10 text-muted-foreground">
                                        Tidak ada data LKM Settings ditemukan.
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="(item, index) in lkms.data" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ item.created_by.name }}</TableCell>
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ item.pertemuan }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.title }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.deskripsi }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.open_at }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.deadline_at }}</TableCell>
                                    <TableCell class="px-4 py-4">
                                        <Badge :class="{
                                            'flex w-fit items-center gap-1': true,
                                            'bg-green-500 hover:bg-green-600 text-white border-transparent': item.is_active,
                                            'bg-red-500 hover:bg-red-600 text-white border-transparent': !item.is_active,
                                        }"> 
                                            <CheckCircle2 v-if="item.is_active" class="h-3 w-3" />
                                            <XCircle v-else class="h-3 w-3" />
                                            <span class="capitalize">{{ item.is_active ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">
                                        <Badge :class="{
                                            'flex w-fit items-center gap-1': true,
                                            'bg-green-500 hover:bg-green-600 text-white border-transparent': item.allow_late_submit,
                                            'bg-red-500 hover:bg-red-600 text-white border-transparent': !item.allow_late_submit,
                                        }">
                                            <CheckCircle2 v-if="item.allow_late_submit" class="h-3 w-3" />
                                            <XCircle v-else class="h-3 w-3" />
                                            <span class="capitalize">{{ item.allow_late_submit ? 'Ya' : 'Tidak' }}</span>
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right pr-4 sm:pr-6 py-4">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" class="h-8 w-8 p-0 hover:bg-gray-200">
                                                    <MoreHorizontal class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuLabel>Opsi</DropdownMenuLabel>
                                                <DropdownMenuItem @click="openEdit(item.id)">Edit</DropdownMenuItem>
                                                <DropdownMenuSeparator />
                                                <!-- <DropdownMenuItem @click="confirmDelete(item)" class="text-red-600">Hapus</DropdownMenuItem> -->
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                    
                    <!-- Pagination Area -->
                     <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-5 sm:px-0 sm:py-4 gap-4">
                        <p class="text-sm text-muted-foreground order-2 sm:order-1">
                            Menampilkan {{ lkms.from || 0 }}
                            sampai {{ lkms.to || 0 }}
                            dari {{ lkms.total }} data lkm settings.
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto order-1 sm:order-2">
                            
                            <!-- Prev -->
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!lkms.prev_page_url"
                                @click="lkms.prev_page_url && router.get(
                                    lkms.prev_page_url,
                                    {},
                                    {
                                        preserveState: true,
                                        preserveScroll: true,
                                        replace: true
                                    }
                                )"
                                class="flex-1 sm:flex-none"
                            >
                                Sebelumnya
                            </Button>

                            <!-- Current Page -->
                            <!-- <div class="text-sm text-muted-foreground px-2">
                                Halaman {{ dosens.current_page }}
                            </div> -->

                            <!-- Next -->
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!lkms.next_page_url"
                                @click="lkms.next_page_url && router.get(
                                    lkms.next_page_url,
                                    {},
                                    {
                                        preserveState: true,
                                        preserveScroll: true,
                                        replace: true
                                    }
                                )"
                                class="flex-1 sm:flex-none"
                            >
                                Berikutnya
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Layout>
</template>