<script setup>
import Layout from '../../../../App.vue';
import { router } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Search, Monitor, CheckCircle2, XCircle, Clock, Calendar } from 'lucide-vue-next';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import dayjs from 'dayjs';
import 'dayjs/locale/id';

// Data Props From Controller
const props = defineProps({
    scores: Object,
    filters: Object,
});

// Search Functionality
const searchQuery = ref(props.filters?.search || '');
let searchTimeout = null;

watch(searchQuery, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get('/dosen/evaluasi/monitoring', 
            { search: value || undefined },
            { preserveState: true, replace: true }
        );
    }, 300);
});

const formatDate = (date) => {
    return dayjs(date).locale('id').format('DD MMMM YYYY, HH:mm');
};

</script>

<template>
    <Layout>
        <div class="w-full">
            <Card class="border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 flex flex-row items-center gap-3 border-b border-gray-100">
                    <Monitor class="text-green-600" />
                    <h2 class="font-bold text-lg sm:text-xl text-gray-800">Monitoring Hasil Quiz</h2> 
                </div>
                
                <CardHeader class="sm:px-6">
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                        <div class="relative w-full max-w-sm">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="searchQuery"
                                type="search"
                                placeholder="Cari mahasiswa atau quiz..."
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
                                    <TableHead class="w-[60px] pl-4 sm:pl-6">NO</TableHead>
                                    <TableHead class="px-4">Mahasiswa</TableHead>
                                    <TableHead class="px-4">Quiz</TableHead>
                                    <TableHead class="px-4 text-center">Skor</TableHead>
                                    <TableHead class="px-4 text-center">Jawaban Benar</TableHead>
                                    <TableHead class="px-4">Status</TableHead>
                                    <TableHead class="px-4">Waktu Selesai</TableHead>
                                    <TableHead class="px-4 pr-6">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="scores.data.length === 0">
                                    <TableCell colspan="8" class="text-center py-10 text-muted-foreground">
                                        Tidak ada data hasil quiz ditemukan.
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="(item, index) in scores.data" :key="item.id" class="hover:bg-gray-50/50 transition-colors">
                                    <TableCell class="font-medium pl-4 sm:pl-6 py-4">{{ (scores.current_page - 1) * scores.per_page + index + 1 }}</TableCell>
                                    <TableCell class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-900">{{ item.user?.name }}</span>
                                            <span v-if="item.user?.mahasiswa?.nim" class="text-xs text-gray-500">NIM: {{ item.user.mahasiswa.nim }}</span>
                                            <span class="text-xs text-gray-400">{{ item.user?.email }}</span>
                                        </div>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">{{ item.quiz?.title }}</TableCell>
                                    <TableCell class="px-4 py-4 text-center">
                                        <span class="text-lg font-bold" :class="item.is_passed ? 'text-green-600' : 'text-red-600'">
                                            {{ Math.round(item.score) }}
                                        </span>
                                    </TableCell>
                                    <TableCell class="px-4 py-4 text-center">
                                        <Badge variant="outline" class="font-mono">
                                            {{ item.correct_answers }} / {{ item.total_questions }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">
                                        <Badge :class="{
                                            'flex w-fit items-center gap-1': true,
                                            'bg-green-500 hover:bg-green-600': item.is_passed,
                                            'bg-red-500 hover:bg-red-600': !item.is_passed,
                                        }">
                                            <CheckCircle2 v-if="item.is_passed" class="h-3 w-3" />
                                            <XCircle v-else class="h-3 w-3" />
                                            <span>{{ item.is_passed ? 'Lulus' : 'Tidak Lulus' }}</span>
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="px-4 py-4">
                                        <div class="flex items-center gap-1.5 text-xs text-gray-600">
                                            <Calendar class="h-3 w-3" />
                                            {{ formatDate(item.submitted_at) }}
                                        </div>
                                    </TableCell>
                                    <TableCell class="px-4 pr-6 py-4">
                                        <Button variant="outline" size="sm" @click="router.get(`/dosen/evaluasi/monitoring/${item.id}`)">Detail</Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-5 sm:px-0 sm:py-4 gap-4">
                        <p class="text-sm text-muted-foreground order-2 sm:order-1">
                            Menampilkan {{ scores.from || 0 }}
                            sampai {{ scores.to || 0 }}
                            dari {{ scores.total }} data hasil quiz.
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto order-1 sm:order-2">
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!scores.prev_page_url"
                                @click="scores.prev_page_url && router.get(scores.prev_page_url, {}, { preserveState: true, preserveScroll: true, replace: true })"
                                class="flex-1 sm:flex-none"
                            >
                                Sebelumnya
                            </Button>

                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="!scores.next_page_url"
                                @click="scores.next_page_url && router.get(scores.next_page_url, {}, { preserveState: true, preserveScroll: true, replace: true })"
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
