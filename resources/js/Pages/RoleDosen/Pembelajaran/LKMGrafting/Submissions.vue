<script setup>
import Layout from '../../../../App.vue';
import { ref, onMounted, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Search, MoreHorizontal } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { Toast } from '@/lib/toast';

const props = defineProps({
    mahasiswas: Object,
    filters: Object,
    errors: Object
});

const searchQuery = ref(props.filters?.search || '');
let searchTimeout = null;

watch(searchQuery, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/dosen/pembelajaran/lkm-grafting/submissions', 
            { search: value || undefined },
            { preserveState: true, replace: true }
        );
    }, 300);
});

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
            timer: 2000
        });
    } else if (flash.warning) {
        Toast.fire({
            icon: 'warning',
            text: flash.warning,
            showConfirmButton: false,
            timer: 2000
        });
    }

    if (Object.keys(errors).length > 0) {
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

const openDetail = (id) => {
    router.visit(`/dosen/pembelajaran/lkm-grafting/submissions/mahasiswa/${id}`);
};
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-2 sm:mb-4">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 shadow-sm">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Daftar LKM <i>Grafting</i> Mahasiswa</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Monitoring pengerjaan Lembar Kerja Mahasiswa (LKM) pada mata kuliah <i>Grafting</i></p>
            </div>
        </div>

        <div class="w-full mt-4">
            <Card class="border-gray-200 overflow-hidden">
                <div class="px-6 py-4 flex flex-row items-center border-b border-gray-200 bg-gray-50/50">
                    <h2 class="font-bold text-lg text-gray-800">Monitoring Submissions LKM</h2> 
                </div>
                <CardHeader class="sm:px-6">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="relative w-full max-w-sm">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Cari Submissions LKM..."
                                class="pl-8 h-10"
                            />
                        </div>
                    </div>
                </CardHeader>
                
                <CardContent class="p-0 sm:p-6 pt-0 sm:pt-0">
                    <div class="rounded-none sm:rounded-md border-y sm:border overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-gray-50/50">
                                    <TableHead class="px-4">NIM</TableHead>
                                    <TableHead class="px-4">Nama Mahasiswa</TableHead>
                                    <TableHead class="text-right pr-4 sm:pr-6">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="mahasiswas.data.length === 0">
                                    <TableCell colspan="3" class="text-center py-10 text-muted-foreground">
                                        Tidak ada data Mahasiswa ditemukan.
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="item in mahasiswas.data" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ item.nim }}</TableCell>
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ item.user.name }}</TableCell>
                                    <TableCell class="text-right pr-4 sm:pr-6 py-4">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" class="h-8 w-8 p-0 hover:bg-gray-200">
                                                    <MoreHorizontal class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuLabel>Opsi</DropdownMenuLabel>
                                                <DropdownMenuItem @click="openDetail(item.id)">Detail</DropdownMenuItem>
                                                <DropdownMenuSeparator />
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
                            Menampilkan {{ mahasiswas.from || 0 }}
                            sampai {{ mahasiswas.to || 0 }}
                            dari {{ mahasiswas.total }} data mahasiswa.
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto order-1 sm:order-2">
                            <!-- Prev -->
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!mahasiswas.prev_page_url"
                                @click="mahasiswas.prev_page_url && router.get(
                                    mahasiswas.prev_page_url,
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

                            <!-- Next -->
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!mahasiswas.next_page_url"
                                @click="mahasiswas.next_page_url && router.get(
                                    mahasiswas.next_page_url,
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
