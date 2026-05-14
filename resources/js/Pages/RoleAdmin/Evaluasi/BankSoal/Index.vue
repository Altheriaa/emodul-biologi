<script setup>
import Layout from '../../../../App.vue';
import { router } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { Search, Plus, UserRoundCog, CheckCircle2, Archive, Clock } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Toast } from '@/lib/toast';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger, } from '@/components/ui/dropdown-menu';
import { MoreHorizontal } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';

// Data Props From Controller
const props = defineProps({
    quizzes: Object,
    filters: Object,
    errors : Object
});

// Search Functionality
const searchQuery = ref(props.filters?.search || '');
let searchTimeout = null;

watch(searchQuery, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/admin/evaluasi/bank-soal', 
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

    if (flash?.success) {
        Toast.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: flash.success,
            timer: 2000
        });
    } else if (flash?.warning) {
        Toast.fire({
            icon: 'warning',
            text: flash.warning,
            timer: 2000
        });
    }

    if (errors && Object.keys(errors).length > 0) {
        const errorMessages = Object.values(errors).join('<br>');
        Toast.fire({
            icon: 'error',
            title: 'Oops...',
            html: errorMessages,
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
    router.visit("/admin/evaluasi/bank-soal/create");
};

const openEdit = (id) => {
    router.visit(`/admin/evaluasi/bank-soal/${id}/edit`);
};  

// confirm delete
const confirmDelete = (item) => {
    Swal.fire({
        title: 'Hapus Soal?',
        text: `Soal "${item.title}" akan dihapus permanen!`,
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
            router.delete(`/admin/evaluasi/bank-soal/${item.id}`);
        }
    });
};

</script>

<template>
    <Layout>
        <!-- Content Area -->
        <div class="w-full">
            <Card class="border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 flex flex-row gap-2">
                    <UserRoundCog />
                    <h2 class="font-bold text-lg sm:text-xl text-gray-800">Kelola Soal</h2> 
                </div>
                <hr class="border-gray-200">
                <CardHeader class="sm:px-6">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="relative w-full max-w-sm">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Cari judul soal..."
                                class="pl-8 h-10"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <Button variant="outline" @click="openCreate" class="flex-1 md:flex-none h-10 border-green-600 text-green-700 hover:bg-green-50">
                                <Plus class="h-4 w-4" />
                                <span class="hidden sm:inline">Tambah Soal</span>
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
                                    <TableHead class="w-[80px] pl-4 sm:pl-6">NO</TableHead>
                                    <TableHead class="px-4">Judul Soal</TableHead>
                                    <TableHead class="px-4">Deskripsi</TableHead>
                                    <TableHead class="px-4">Durasi</TableHead>
                                    <TableHead class="px-4">Status</TableHead>
                                    <TableHead class="px-4">Jumlah Soal</TableHead>
                                    <TableHead class="text-right pr-4 sm:pr-6">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="quizzes.data.length === 0">
                                    <TableCell colspan="6" class="text-center py-10 text-muted-foreground">
                                        Tidak ada data soal ditemukan.
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="(item, index) in quizzes.data" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ (quizzes.current_page - 1) * quizzes.per_page + index + 1 }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.title }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.description }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.duration_minutes }} Menit</TableCell>
                                    <TableCell class="px-4 py-4">
                                        <Badge :class="{
                                            'flex w-fit items-center gap-1': true,
                                            'bg-green-500 hover:bg-green-600': item.status === 'published',
                                            'bg-gray-500 hover:bg-gray-600': item.status === 'draft',
                                            'bg-red-500 hover:bg-red-600': item.status === 'archived',
                                        }">
                                            <Clock v-if="item.status === 'draft'" class="h-3 w-3" />
                                            <CheckCircle2 v-else-if="item.status === 'published'" class="h-3 w-3" />
                                            <Archive v-else-if="item.status === 'archived'" class="h-3 w-3" />
                                            <span class="capitalize">{{ item.status }}</span>
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">{{ item.count_soal }}</TableCell>
                                    <TableCell class="text-right pr-4 sm:pr-6 py-4">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" class="h-8 w-8 p-0 hover:bg-gray-200">
                                                    <MoreHorizontal class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuLabel>Opsi</DropdownMenuLabel>
                                                <!-- <DropdownMenuItem @click="openEdit(item.quizzes.id)">Edit</DropdownMenuItem> -->
                                                <DropdownMenuItem @click="openEdit(item.id)">Edit</DropdownMenuItem>
                                                <DropdownMenuSeparator />
                                                <DropdownMenuItem @click="confirmDelete(item)" class="text-red-600">Hapus</DropdownMenuItem>
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
                            Menampilkan {{ quizzes.from || 0 }}
                            sampai {{ quizzes.to || 0 }}
                            dari {{ quizzes.total }} data dosen.
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto order-1 sm:order-2">
                            
                            <!-- Prev -->
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!quizzes.prev_page_url"
                                @click="quizzes.prev_page_url && router.get(
                                    quizzes.prev_page_url,
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
                                :disabled="!quizzes.next_page_url"
                                @click="quizzes.next_page_url && router.get(
                                    quizzes.next_page_url,
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


