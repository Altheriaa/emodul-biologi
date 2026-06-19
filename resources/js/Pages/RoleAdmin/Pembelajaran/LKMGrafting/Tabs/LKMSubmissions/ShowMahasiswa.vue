<script setup>
import Layout from '../../Index.vue';
import { usePage, router } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, CircleDashed, XCircle } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';

const props = defineProps({
    mahasiswa: Object,
    lkmData: Array,
});

const goBack = () => {
    router.visit('/admin/pembelajaran/lkm-grafting/submissions');
};

const openDetail = (submissionId) => {
    router.visit(`/admin/pembelajaran/lkm-grafting/submissions/${submissionId}`);
};
</script>

<template>
    <Layout>
        <div class="w-full mt-4">
            <Card class="border-gray-200 overflow-hidden">
                <div class="px-6 flex flex-row gap-2 items-center mb-4 mt-6">
                    <Button variant="ghost" size="icon" @click="goBack" class="mr-2">
                        <ArrowLeft class="w-5 h-5" />
                    </Button>
                    <h2 class="font-bold text-lg sm:text-xl text-gray-800">Detail Submission: {{ mahasiswa.user.name }} ({{ mahasiswa.nim }})</h2> 
                </div>
                <hr class="border-gray-200">
                
                <CardContent class="p-0 sm:p-6 mt-4">
                    <div class="rounded-none sm:rounded-md border-y sm:border overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow class="bg-gray-50/50">
                                    <TableHead class="px-4">Pertemuan</TableHead>
                                    <TableHead class="px-4">Materi LKM</TableHead>
                                    <TableHead class="px-4">Status</TableHead>
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
