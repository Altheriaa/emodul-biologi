<script setup>
import Layout from '../../../../App.vue';
import { Button } from '@/components/ui/button';
import { Send, Save } from 'lucide-vue-next';
import { router, usePage, useForm } from '@inertiajs/vue3';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';
import { Badge } from '@/components/ui/badge';
import { onMounted, watch, computed } from 'vue';

const props = defineProps({
    submission: Object,
});

// Cek apakah form harus dikunci (hanya bisa dilihat)
const isReadOnly = computed(() => props.submission.status === 'submitted');

// Inisialisasi Data Form menggunakan Inertia useForm
// Kita ambil data dari relasi jika sudah ada (saat lanjut draft), jika belum pakai default kosong
const form = useForm({
    // --- Pertemuan 1 ---
    observations: props.submission.p1_observations?.length > 0 
        ? props.submission.p1_observations 
        : [
            // Sediakan template baris default jika mahasiswa baru pertama kali buka
            { nama_tanaman: 'Mangga', organ: 'Batang', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Alpukat', organ: 'Batang', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Durian', organ: 'Batang', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Mangga', organ: 'Akar', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Alpukat', organ: 'Akar', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Durian', organ: 'Akar', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Mangga', organ: 'Daun', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Alpukat', organ: 'Daun', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Durian', organ: 'Daun', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Mangga', organ: 'Bunga', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Alpukat', organ: 'Bunga', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Durian', organ: 'Bunga', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Mangga', organ: 'Buah', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Alpukat', organ: 'Buah', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Durian', organ: 'Buah', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Mangga', organ: 'Bijian', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Alpukat', organ: 'Bijian', morfologis: '', anatomis: '' },
            { nama_tanaman: 'Durian', organ: 'Bijian', morfologis: '', anatomis: '' },
            // ... (bisa Anda tambahkan organ lain seperti Daun, Bunga, Buah, Biji)
        ],
    questions: props.submission.p1_questions || {
        q1_jenis_tumbuhan_cocok: '',
        q2_jaringan_terlibat: '',
        q3_peran_kambium: '',
        q4_pemilihan_batang_bawah: '',
    },

    // --- Pertemuan 2 ---
    items: (() => {
        const loadedAlats = props.submission.p2_items?.filter(i => i.jenis === 'alat') || [];
        const loadedBahans = props.submission.p2_items?.filter(i => i.jenis === 'bahan') || [];
        const rows = [];
        for (let i = 0; i < 6; i++) {
            rows.push({
                alat: loadedAlats[i]?.nama_item || '',
                bahan: loadedBahans[i]?.nama_item || ''
            });
        }
        return rows;
    })(),

    specifications: props.submission.p2_specs || {
        batang_atas_rootstock: '',
        batang_bawah_scion: '',
        usia_batang_atas: '',
        usia_batang_bawah: '',
        jumlah_mata_tunas: '',
    },


    // --- Pertemuan 3 ---
    // --- Pertemuan 4 ---

    // --- Data Khusus Pertemuan 2 ---
    // specs: props.submission.p2_specs || { ... },
    // items: props.submission.p2_items || [],
    // steps: props.submission.p2_steps || [],

    // --- Data Khusus Pertemuan 3 & 4 ---
    // ... siapkan juga state defaultnya di sini
});

const submitData = (actionType) => {
    if (actionType === 'submit') {
        Swal.fire({
            title: 'Kumpulkan LKM?',
            text: 'Setelah dikumpulkan, jawaban LKM tidak dapat diubah lagi!',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kumpulkan!',
            cancelButtonText: 'Kembali',
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                executeSubmit(actionType);
            }
        });
    } else {
        executeSubmit(actionType);
    }
};

const executeSubmit = (actionType) => {
    // Tambahkan indikator action (draft / submit) sebelum dikirim ke backend
    form.transform((data) => ({
        ...data,
        action: actionType 
    })).post(`/mahasiswa/pembelajaran/lkm-grafting/form/${props.submission.pertemuan}`, {
        preserveScroll: true,
        onSuccess: () => {
            if (actionType === 'submit') {
                Toast.fire({ icon: 'success', title: 'Berhasil!', text: 'LKM berhasil dikumpulkan.' });
            } else {
                Toast.fire({ icon: 'success', title: 'Tersimpan!', text: 'Progres LKM berhasil disimpan sebagai Draft.' });
            }
        },
        onError: () => {
            Toast.fire({ icon: 'error', title: 'Oops!', text: 'Pastikan semua form telah diisi dengan benar.' });
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
            <!-- <div v-if="isReadOnly" class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
                <Lock class="w-5 h-5 text-blue-600 mt-0.5" />
                <div>
                    <h3 class="text-sm font-bold text-blue-900">LKM Terkunci</h3>
                    <p class="text-xs text-blue-700 mt-0.5">Anda sudah mengumpulkan LKM ini. Form di bawah ini bersifat *Read-Only* (hanya untuk dilihat) dan tidak dapat diubah lagi.</p>
                </div>
            </div> -->
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">LKM Grafting {{ submission?.pertemuan ?? 'Judul LKM' }}</p>
                <p class="text-sm sm:text-s text-green-600 mt-1">{{ submission.lkm_setting?.deskripsi ?? '-' }}</p>
                <template v-if="props.submission.status === 'submitted'">
                    <Badge :class="{
                        'mt-2': true,
                        'flex w-fit items-center gap-1': true,
                        'bg-green-500 hover:bg-green-600 text-white border-transparent': true,
                    }"> 
                        <span class="capitalize">Status : Submitted</span>
                    </Badge>
                </template>
                <template v-else>
                    <Badge :class="{
                        'mt-2': true,
                        'flex w-fit items-center gap-1': true,
                        'bg-yellow-500 hover:bg-yellow-600 text-white border-transparent': true,
                    }"> 
                        <span class="capitalize">Status : Draft</span>
                    </Badge>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <div 
                class="col-span-3 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col justify-between h-full"
            >
            <form @submit.prevent="submitData('submit')" class="space-y-8">

                <!-- LKM 1 -->
                <template v-if="submission.pertemuan == 1">
                    
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">1. Pengamatan Jaringan dan Organ Tumbuhan</h2>
                            <p class="text-xs text-gray-500 mt-1">Amatilah jenis jaringan dan organ pada beberapa jenis tumbuhan, kemudian kemukakan hasil amatan tersebut ke dalam kolom berikut!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Tanaman - Organ</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/4">Sifat Morfologis</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/4">Sifat Anatomis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(obs, index) in form.observations" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ obs.nama_tanaman }} - {{ obs.organ }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="obs.morfologis" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tulis ciri morfologis..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="obs.anatomis" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tulis ciri anatomis..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">2. Mempelajari Prinsip Dasar Teknik Grafting</h2>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Q1 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Bagaimana jenis tumbuhan yang cocok untuk grafting sehingga grafting yang dilakukan berhasil?
                                </label>
                                <textarea 
                                    v-model="form.questions.q1_jenis_tumbuhan_cocok" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3" 
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <!-- Q2 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Jelaskan jaringan-jaringan tumbuhan yang terlibat dalam teknik grafting!
                                </label>
                                <textarea 
                                    v-model="form.questions.q2_jaringan_terlibat" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <!-- Q3 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Bagaimana struktur jaringan kambium berperan dalam proses grafting?
                                </label>
                                <textarea 
                                    v-model="form.questions.q3_peran_kambium" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <!-- Q4 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Bagaimana pemilihan batang bawah yang tepat dapat menentukan pertumbuhan dan produktivitas tanaman yang di grafting?
                                </label>
                                <textarea 
                                    v-model="form.questions.q4_pemilihan_batang_bawah" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- LKM 2 -->
                <template v-else-if="submission.pertemuan == 2">
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">2. Persiapan Alat dan Bahan</h2>
                            <p class="text-xs text-gray-500 mt-1">Uraikan alat dan bahan apa saja yang digunakan untuk melakukan grafting tanaman!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold">No</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/4">Alat</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/4">Bahan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.items" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="item.alat" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tulis Alat..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="item.bahan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tulis Bahan..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- specs -->
                     <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">2. Prosedur kerja</h2>
                            <p class="text-xs mt-1">Sebutkan jenis tumbuhan yang digunakan untuk grafting!</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Q1 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Batang atas (rootstock) :
                                </label>
                                <input 
                                    v-model="form.specifications.batang_atas_rootstock" 
                                    :disabled="isReadOnly"
                                    type="text"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    placeholder="Jawaban Anda..."
                                />
                            </div>
                            <!-- Q2 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Batang Bawah (scion) :
                                </label>
                                <input 
                                    v-model="form.specifications.batang_bawah_scion" 
                                    :disabled="isReadOnly"
                                    type="text"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    placeholder="Jawaban Anda..."
                                />
                            </div>
                            <!-- Q3 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Usia Batang atas (rootstock) :
                                </label>
                                <input 
                                    v-model="form.specifications.usia_batang_atas" 
                                    :disabled="isReadOnly"
                                    type="text"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    placeholder="Jawaban Anda..."
                                />
                            </div>
                            <!-- Q4 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Usia Batang Bawah (scion) :
                                </label>
                                <input 
                                    v-model="form.specifications.usia_batang_bawah" 
                                    :disabled="isReadOnly"
                                    type="text"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    placeholder="Jawaban Anda..."
                                />
                            </div>
                            <!-- Q5 -->
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q:</span>
                                    Jumlah mata tunas batang Bawah (scion) :
                                </label>
                                <input 
                                    v-model="form.specifications.jumlah_mata_tunas" 
                                    :disabled="isReadOnly"
                                    type="number"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    placeholder="Jawaban Anda..."
                                />
                            </div>
                        </div>
                    </div>
                </template>

                <template v-else-if="submission.pertemuan == 3">
                    </template>
                
                <template v-else-if="submission.pertemuan == 4">
                    </template>


                <div v-if="!isReadOnly" class="bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 z-50">
                    <div class="flex justify-end gap-2">
                        <Button 
                            type="button" 
                            variant="outline" 
                            @click="submitData('draft')"
                            :disabled="form.processing"
                            class="rounded-xl sm:size-lg sm:text-sm size-sm text-sm border-gray-300 text-gray-700 hover:bg-gray-50"
                        >
                            <Save class="w-4 h-4 text-gray-500" />
                            Draft
                        </Button>

                        <Button 
                            type="submit" 
                            :disabled="form.processing"
                            class="rounded-xl sm:size-lg sm:text-sm size-sm text-sm bg-green-600 text-white hover:bg-green-700"
                        >
                            <Send class="w-4 h-4" />
                            Submit
                        </Button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </Layout>
</template>

