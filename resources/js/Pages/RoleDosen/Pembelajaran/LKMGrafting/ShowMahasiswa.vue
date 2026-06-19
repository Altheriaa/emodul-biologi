<script setup>
import Layout from '../../../../App.vue';
import { usePage, router } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, CircleDashed, XCircle } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Swal from 'sweetalert2';
import { Toast } from '@/lib/toast';

const props = defineProps({
    mahasiswa: Object,
    lkmData: Array,
});

const goBack = () => {
    router.visit('/dosen/pembelajaran/lkm-grafting/submissions');
};

const openDetail = (submissionId) => {
    router.visit(`/dosen/pembelajaran/lkm-grafting/submissions/${submissionId}`);
};

const editCatatan = (item) => {
    Swal.fire({
        title: `Catatan LKM Pertemuan ${item.pertemuan}`,
        input: 'textarea',
        inputLabel: 'Berikan feedback / catatan pengerjaan untuk mahasiswa:',
        inputValue: item.catatan_dosen || '',
        inputPlaceholder: 'Tulis catatan...',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'glass-popup rounded-2xl shadow-blur p-5',
            title: 'font-bold text-lg text-gray-800',
            input: 'rounded-xl border-gray-300 text-sm focus:border-green-500 focus:ring-green-500',
            confirmButton: 'button-confirm px-5 py-2 rounded-xl text-white font-medium text-sm',
            cancelButton: 'px-5 py-2 rounded-xl text-gray-700 font-medium text-sm',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/dosen/pembelajaran/lkm-grafting/submissions/${item.submission_id}/catatan`, {
                catatan_dosen: result.value
            }, {
                preserveScroll: true,
                onSuccess: () => {
                    Toast.fire({
                        icon: 'success',
                        title: 'Tersimpan!',
                        text: 'Catatan berhasil diperbarui.',
                        timer: 2000
                    });
                }
            });
        }
    });
};
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-2 sm:mb-4">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 shadow-sm flex items-center gap-3">
                <Button variant="ghost" size="icon" @click="goBack" class="mr-1 hover:bg-gray-100 rounded-lg">
                    <ArrowLeft class="w-5 h-5 text-gray-600" />
                </Button>
                <div>
                    <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Detail Submission Mahasiswa</p>
                    <p class="text-xs sm:text-sm text-green-600 mt-0.5">Daftar pengerjaan LKM oleh {{ mahasiswa.user.name }} (NIM: {{ mahasiswa.nim }})</p>
                </div>
            </div>
        </div>

        <div class="w-full mt-4">
            <Card class="border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
                    <h2 class="font-bold text-lg text-gray-800">Detail Submission: {{ mahasiswa.user.name }} ({{ mahasiswa.nim }})</h2> 
                </div>
                
                <CardContent class="p-0 sm:p-6 mt-4">
                    <div class="rounded-none sm:rounded-md border-y sm:border overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-gray-50/50">
                                    <TableHead class="px-4">Pertemuan</TableHead>
                                    <TableHead class="px-4">Materi LKM</TableHead>
                                    <TableHead class="px-4">Status</TableHead>
                                    <TableHead class="px-4">Catatan Dosen</TableHead>
                                    <TableHead class="text-right pr-4 sm:pr-6">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="(item, index) in lkmData" :key="index" class="hover:bg-gray-50/50 transition-colors">
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">Pertemuan {{ item.pertemuan }}</TableCell>
                                    <TableCell class="py-4">{{ item.title }}</TableCell>
                                    <TableCell class="px-4 py-4">
                                        <Badge :class="{
                                            'flex w-fit items-center gap-1': true,
                                            'bg-green-500 hover:bg-green-600 text-white border-transparent': item.status === 'submitted',
                                            'bg-yellow-500 hover:bg-yellow-600 text-white border-transparent': item.status === 'draft',
                                            'bg-gray-500 hover:bg-gray-600 text-white border-transparent': item.status === 'Belum Mengerjakan',
                                        }"> 
                                            <CheckCircle2 v-if="item.status === 'submitted'" class="h-3 w-3" />
                                            <CircleDashed v-else-if="item.status === 'draft'" class="h-3 w-3" />
                                            <XCircle v-else class="h-3 w-3" />
                                            <span class="capitalize">{{ item.status === 'submitted' ? 'Submitted' : (item.status === 'draft' ? 'Drafted' : 'Belum Mengerjakan') }}</span>
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">
                                        <div v-if="item.submission_id" class="flex flex-col gap-1.5 max-w-[300px]">
                                            <p v-if="item.catatan_dosen" class="text-xs text-gray-600 italic whitespace-pre-line bg-gray-50 p-2.5 rounded border border-gray-100">
                                                {{ item.catatan_dosen }}
                                            </p>
                                            <Button 
                                                variant="outline" 
                                                size="sm"
                                                @click="editCatatan(item)"
                                                class="w-fit text-[11px] h-7 border-dashed border-gray-300 text-gray-500 hover:text-green-600 hover:border-green-600 hover:bg-green-50/50"
                                            >
                                                {{ item.catatan_dosen ? 'Edit Catatan' : '+ Catatan' }}
                                            </Button>
                                        </div>
                                        <span v-else class="text-gray-400 text-xs italic">Belum ada submission</span>
                                    </TableCell>
                                    <TableCell class="text-right pr-4 sm:pr-6 py-4">
                                        <Button 
                                            v-if="item.submission_id" 
                                            variant="outline" 
                                            size="sm" 
                                            @click="openDetail(item.submission_id)"
                                            class="text-green-600 border-green-600 hover:bg-green-50"
                                        >
                                            Lihat Jawaban
                                        </Button>
                                        <span v-else class="text-gray-400 text-sm italic">Belum ada data</span>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Layout>
</template>
