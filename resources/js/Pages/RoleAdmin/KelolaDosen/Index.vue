<script setup>
import Layout from '../../../App.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
  Search, 
  Plus, 
  MoreHorizontal, 
  Filter, 
  Download,
  CheckCircle2,
  Clock
} from 'lucide-vue-next';
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

// Data Dummy untuk Mockup Layout
const dataMahasiswa = [
  { id: 1, nim: '2100101', nama: 'Budi Santoso', prodi: 'Teknik Informatika', kelompok: 'Desa A', status: 'Selesai' },
  { id: 2, nim: '2100102', nama: 'Siti Aminah', prodi: 'Sistem Informasi', kelompok: 'Desa B', status: 'Pending' },
  { id: 3, nim: '2100103', nama: 'Andi Wijaya', prodi: 'Teknik Sipil', kelompok: 'Desa A', status: 'Selesai' },
  { id: 4, nim: '2100104', nama: 'Dewi Lestari', prodi: 'Akuntansi', kelompok: 'Desa C', status: 'Pending' },
  { id: 5, nim: '2100105', nama: 'Dewi Lestari', prodi: 'Akuntansi', kelompok: 'Desa C', status: 'Pending' },
  { id: 6, nim: '2100106', nama: 'Dewi Lestari', prodi: 'Akuntansi', kelompok: 'Desa C', status: 'Pending' },
  { id: 7, nim: '2100107', nama: 'Dewi Lestari', prodi: 'Akuntansi', kelompok: 'Desa C', status: 'Pending' },
  { id: 8, nim: '2100108', nama: 'Dewi Lestari', prodi: 'Akuntansi', kelompok: 'Desa C', status: 'Pending' },
];

const searchQuery = ref('');

</script>

<template>
    <Layout>
        <!-- Header Page -->
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Kelola Dosen</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Halaman untuk mengelola data dosen</p>
            </div>
        </div>

        <!-- Content Area -->
        <div class="w-full">
            <Card class="border-gray-200 shadow-sm overflow-hidden">
                <CardHeader class="sm:px-6">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="relative w-full max-w-sm">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Cari NIP atau Nama..."
                                class="pl-8 h-10"
                            />
                        </div>
                        <div class="flex items-center gap-2">
                            <Button variant="ghost" size="sm" class="flex-1 md:flex-none h-10 border md:border-none">
                                <Filter class="mr-2 h-4 w-4" /> Filter
                            </Button>
                            <Button variant="outline" @click="OpenFlipping" class="flex-1 md:flex-none h-10 border-green-600 text-green-700 hover:bg-green-50">
                                <Plus class="mr-2 h-4 w-4" />
                                <span class="hidden sm:inline">Tambah Dosen</span>
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
                                    <TableHead class="w-[120px] pl-4 sm:pl-6">NUPTK</TableHead>
                                    <TableHead class="px-4">Nama</TableHead>
                                    <TableHead class="px-4">Jabatan</TableHead>
                                    <TableHead class="px-4">Email</TableHead>
                                    <TableHead class="px-4">Password</TableHead>
                                    <TableHead class="text-right pr-4 sm:pr-6">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in dataMahasiswa" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ item.nim }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.nama }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.prodi }}</TableCell>
                                    <TableCell class="px-4 py-4">{{ item.kelompok }}</TableCell>
                                    <TableCell class="px-4 py-4">
                                        <Badge 
                                            :variant="item.status === 'Selesai' ? 'default' : 'secondary'"
                                            class="flex w-fit items-center gap-1"
                                        >
                                            <CheckCircle2 v-if="item.status === 'Selesai'" class="h-3 w-3" />
                                            <Clock v-else class="h-3 w-3" />
                                            {{ item.status }}
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
                                                <DropdownMenuItem>Lihat Detail</DropdownMenuItem>
                                                <DropdownMenuItem>Edit Data</DropdownMenuItem>
                                                <DropdownMenuSeparator />
                                                <DropdownMenuItem class="text-red-600">Hapus</DropdownMenuItem>
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
                            Menampilkan 4 dari 120 data mahasiswa.
                        </p>
                        <div class="flex items-center gap-2 w-full sm:w-auto order-1 sm:order-2">
                            <Button variant="outline" size="sm" disabled class="flex-1 sm:flex-none">Sebelumnya</Button>
                            <Button variant="outline" size="sm" class="flex-1 sm:flex-none">Berikutnya</Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Layout>
</template>

