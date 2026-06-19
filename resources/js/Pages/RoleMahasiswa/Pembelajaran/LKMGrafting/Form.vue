<script setup>
import Layout from '../../../../App.vue';
import { Button } from '@/components/ui/button';
import { Send, Save, Camera, X, Image, ArrowLeft, NotepadText } from 'lucide-vue-next';
import { router, usePage, useForm } from '@inertiajs/vue3';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';
import { Badge } from '@/components/ui/badge';
import { onMounted, watch, computed, ref } from 'vue';

const props = defineProps({
    submission: Object,
    isAdmin: {
        type: Boolean,
        default: false
    }
});

const page = usePage();

// Cek apakah form harus dikunci (hanya bisa dilihat)
const isReadOnly = computed(() => props.submission.status === 'submitted' || props.isAdmin);

const activePreviewImage = ref(null);

const openImagePreview = (imageUrl) => {
    activePreviewImage.value = imageUrl;
};

const closeImagePreview = () => {
    activePreviewImage.value = null;
};

const goBack = () => {
    const role = page.props.auth.user.role;
    if (role === 'admin') {
        router.visit(`/admin/pembelajaran/lkm-grafting/submissions/mahasiswa/${props.submission.mahasiswa_id}`);
    } else if (role === 'dosen') {
        router.visit(`/dosen/pembelajaran/lkm-grafting/submissions/mahasiswa/${props.submission.mahasiswa_id}`);
    } else {
        router.visit('/mahasiswa/pembelajaran/lkm-grafting');
    }
};

// =============================================================
// Inisialisasi Data Form menggunakan Inertia useForm
// =============================================================
const form = useForm({

    // --- Pertemuan 1 (Sintak 1-3) ---
    // Sintak 1: Pertanyaan Esensial
    questions: props.submission.p1_questions || {
        q1_jenis_tumbuhan_cocok: '',
        q2_jaringan_terlibat: '',
        q3_pemilihan_batang_bawah: '',
        q4_peran_kambium: '',
        q5_kondisi_lingkungan: '',
    },

    // Sintak 2: Pemilihan Tanaman
    specs: props.submission.p1_specs?.length > 0
        ? props.submission.p1_specs
        : [
            { variabel: 'Nama Spesies Batang Bawah (rootstock)', tanaman_a: '', tanaman_b: '', alasan_pemilihan: '' },
            { variabel: 'Nama Spesies Batang Atas (scion)', tanaman_a: '', tanaman_b: '', alasan_pemilihan: '' },
            { variabel: 'Usia Tanaman', tanaman_a: '', tanaman_b: '', alasan_pemilihan: '' },
            { variabel: 'Diameter Batang', tanaman_a: '', tanaman_b: '', alasan_pemilihan: '' },
            { variabel: 'Kondisi Kambium', tanaman_a: '', tanaman_b: '', alasan_pemilihan: '' },
            { variabel: 'Alasan Kompatibilitas', tanaman_a: '', tanaman_b: '', alasan_pemilihan: '' },
        ],

    // Sintak 2: Alat & Bahan
    items: (() => {
        const saved = props.submission.p1_items || [];
        const padded = [...saved];
        while (padded.length < 10) {
            padded.push({ alat: '', bahan: '' });
        }
        return padded;
    })(),

    // Sintak 3: Prosedur Kerja
    procedures: (() => {
        const saved = props.submission.p1_procedures || [];
        const padded = [...saved];
        while (padded.length < 10) {
            padded.push({ step_number: padded.length + 1, tahap: '', penjelasan: '' });
        }
        return padded;
    })(),

    // Sintak 3: Jadwal Capaian
    schedules: props.submission.p1_schedules?.length > 0
        ? props.submission.p1_schedules
        : [
            { pertemuan_ke: 1, target_kegiatan: '' },
            { pertemuan_ke: 2, target_kegiatan: '' },
            { pertemuan_ke: 3, target_kegiatan: '' },
            { pertemuan_ke: 4, target_kegiatan: '' },
        ],

    // --- Pertemuan 2 (Sintak 4) ---
    // Persiapan Alat & Bahan (single column)
    p2items: (() => {
        const saved = props.submission.p2_items || [];
        const padded = [...saved];
        while (padded.length < 10) {
            padded.push({ nama_item: '' });
        }
        return padded;
    })(),

    // Identifikasi Spesimen
    p2specs: props.submission.p2_specs?.length > 0
        ? props.submission.p2_specs
        : [
            { keterangan: 'Nama Spesies', batang_bawah: '', batang_atas: '', alasan: '' },
            { keterangan: 'Usia Tanaman', batang_bawah: '', batang_atas: '', alasan: '' },
            { keterangan: 'Diameter Batang', batang_bawah: '', batang_atas: '', alasan: '' },
            { keterangan: 'Jumlah Mata Tunas', batang_bawah: '', batang_atas: '', alasan: '' },
            { keterangan: 'Kondisi Fisik', batang_bawah: '', batang_atas: '', alasan: '' },
            { keterangan: 'Kondisi Kambium', batang_bawah: '', batang_atas: '', alasan: '' },
        ],

    // Prosedur Pelaksanaan Grafting
    p2procedures: (() => {
        const saved = props.submission.p2_procedures || [];
        const padded = [...saved];
        while (padded.length < 10) {
            padded.push({ step_number: padded.length + 1, tahap_kegiatan: '', kondisi_jaringan: '' });
        }
        return padded;
    })(),

    // Monitoring Proyek
    p2monitorings: props.submission.p2_monitorings?.length > 0
        ? props.submission.p2_monitorings
        : [
            { aspek: 'Kondisi Sambungan', hasil_pengamatan: '' },
            { aspek: 'Kelembapan', hasil_pengamatan: '' },
            { aspek: 'Lokasi Penyimpanan', hasil_pengamatan: '' },
            { aspek: 'Kekuatan Sambungan', hasil_pengamatan: '' },
            { aspek: 'Kondisi Tanaman Keseluruhan', hasil_pengamatan: '' },
        ],

    // Pertanyaan Esensial P2
    p2questions: props.submission.p2_questions || {
        q1_ditutup_rapat: '',
        q2_pengaruh_kelembapan: '',
        q3_lokasi_penyimpanan: '',
        q4_kekuatan_lemah: '',
        q5_keberhasilan_kegagalan: '',
        q6_peran_xilem: '',
        q7_peran_epidermis: '',
        q8_aktivitas_meristem: '',
    },

    // --- Pertemuan 3 (Sintak 5) ---
    // Pengamatan Pertumbuhan Tunas & Daun
    growths: props.submission.p3_growths?.length > 0
        ? props.submission.p3_growths
        : [
            { parameter: 'Jumlah Tunas', data_jumlah: '', deskripsi_kondisi: '' },
            { parameter: 'Panjang Tunas (cm)', data_jumlah: '', deskripsi_kondisi: '' },
            { parameter: 'Jumlah Daun', data_jumlah: '', deskripsi_kondisi: '' },
            { parameter: 'Ukuran Daun', data_jumlah: '', deskripsi_kondisi: '' },
            { parameter: 'Warna Daun', data_jumlah: '', deskripsi_kondisi: '' },
            { parameter: 'Kondisi Daun', data_jumlah: '', deskripsi_kondisi: '' },
            { parameter: 'Tekstur Daun', data_jumlah: '', deskripsi_kondisi: '' },
        ],

    // Pengamatan Kondisi Batang Atas (Scion)
    scions: props.submission.p3_scions?.length > 0
        ? props.submission.p3_scions.map(s => ({ ...s, dokumentasi_file: null }))
        : [
            { parameter: 'Warna Batang', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Turgiditas', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Pertumbuhan Baru', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Kondisi Sambungan', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Tanda Nekrosis', kondisi_deskripsi: '', dokumentasi_file: null },
        ],

    // Pengamatan Kondisi Batang Bawah (Rootstock)
    rootstocks: props.submission.p3_rootstocks?.length > 0
        ? props.submission.p3_rootstocks.map(r => ({ ...r, dokumentasi_file: null }))
        : [
            { parameter: 'Warna Batang', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Kondisi Akar', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Tunas Baru', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Kondisi Sambungan', kondisi_deskripsi: '', dokumentasi_file: null },
            { parameter: 'Kondisi Daun Tersisa', kondisi_deskripsi: '', dokumentasi_file: null },
        ],

    // Pengamatan Kondisi Sambungan
    connection: props.submission.p3_connections || {
        rincian_sambungan: '',
        is_tumbuh_tunas: null,
        alasan: '',
    },

    // Pertanyaan Esensial P3
    p3questions: props.submission.p3_questions || {
        q1_apakah_berhasil: '',
        q2_indikator_keberhasilan: '',
        q3_tunas_baru_muncul: '',
        q4_hubungan_jaringan_pengangkut: '',
        q5_faktor_penyebab_gagal: '',
    },

    // --- Pertemuan 4 (Sintak 6) ---
    // Analisis Keberhasilan
    analyses: props.submission.p4_analyses?.length > 0
        ? props.submission.p4_analyses
        : [
            { variabel_analisis: 'Jumlah tunas yang berhasil tumbuh', hasil_pengamatan: '' },
            { variabel_analisis: 'Jumlah dan ukuran daun', hasil_pengamatan: '' },
            { variabel_analisis: 'Warna daun', hasil_pengamatan: '' },
            { variabel_analisis: 'Warna dan kondisi batang atas', hasil_pengamatan: '' },
            { variabel_analisis: 'Warna dan kondisi batang bawah', hasil_pengamatan: '' },
            { variabel_analisis: 'Kondisi sambungan', hasil_pengamatan: '' },
            { variabel_analisis: 'Terbentuknya kalus', hasil_pengamatan: '' },
            { variabel_analisis: 'Tidak ada nekrosis', hasil_pengamatan: '' },
        ],

    // Pertanyaan Analisis Mendalam
    deepQuestions: props.submission.p4_deep_questions || {
        q1_tujuan_grafting: '',
        q2_karakteristik_anatomi: '',
        q3_kesejajaran_kambium: '',
        q4_faktor_anatomi_inkompatibilitas: '',
        q5_proses_penyembuhan: '',
    },

    // Penilaian Diri
    selfAssessments: props.submission.p4_self_assessments?.length > 0
        ? props.submission.p4_self_assessments
        : [
            { aspek: 'Pemahaman konsep jaringan tumbuhan', skor: null, catatan: '' },
            { aspek: 'Pemahaman prinsip dasar grafting', skor: null, catatan: '' },
            { aspek: 'Kemampuan memilih tanaman yang kompatibel', skor: null, catatan: '' },
            { aspek: 'Keterampilan melaksanakan teknik grafting', skor: null, catatan: '' },
            { aspek: 'Kemampuan memonitor perkembangan grafting', skor: null, catatan: '' },
            { aspek: 'Kemampuan menganalisis keberhasilan/kegagalan', skor: null, catatan: '' },
            { aspek: 'Kemampuan menghubungkan teori anatomi dengan praktik', skor: null, catatan: '' },
            { aspek: 'Kemampuan bekerja secara mandiri dan kelompok', skor: null, catatan: '' },
        ],

    // Refleksi Essay
    reflections: props.submission.p4_reflections || {
        r1_pengalaman_baru: '',
        r2_kesulitan: '',
        r3_cara_mengatasi: '',
        r4_manfaat_pjbl: '',
        r5_perasaan: '',
        r6_kesejajaran_kambium: '',
        r7_peran_kutikula: '',
        r8_perbedaan_sel_epidermis: '',
        r9_kondisi_lingkungan: '',
        r10_fungsi_sungkup: '',
    },
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
    // Build payload based on pertemuan
    const pertemuan = props.submission.pertemuan;
    let payload = { action: actionType };

    if (pertemuan === 1) {
        payload.questions = form.questions;
        payload.specs = form.specs;
        payload.items = form.items;
        payload.procedures = form.procedures;
        payload.schedules = form.schedules;
    } else if (pertemuan === 2) {
        payload.items = form.p2items;
        payload.specs = form.p2specs;
        payload.procedures = form.p2procedures;
        payload.monitorings = form.p2monitorings;
        payload.p2questions = form.p2questions;
    } else if (pertemuan === 3) {
        // For P3 we need FormData because of file uploads
        const formData = new FormData();
        formData.append('action', actionType);

        // Growths
        form.growths.forEach((g, i) => {
            formData.append(`growths[${i}][parameter]`, g.parameter || '');
            formData.append(`growths[${i}][data_jumlah]`, g.data_jumlah || '');
            formData.append(`growths[${i}][deskripsi_kondisi]`, g.deskripsi_kondisi || '');
        });

        // Scions (with file uploads)
        form.scions.forEach((s, i) => {
            formData.append(`scions[${i}][parameter]`, s.parameter || '');
            formData.append(`scions[${i}][kondisi_deskripsi]`, s.kondisi_deskripsi || '');
            if (s.dokumentasi_file instanceof File) {
                formData.append(`scions[${i}][dokumentasi_file]`, s.dokumentasi_file);
            }
        });

        // Rootstocks (with file uploads)
        form.rootstocks.forEach((r, i) => {
            formData.append(`rootstocks[${i}][parameter]`, r.parameter || '');
            formData.append(`rootstocks[${i}][kondisi_deskripsi]`, r.kondisi_deskripsi || '');
            if (r.dokumentasi_file instanceof File) {
                formData.append(`rootstocks[${i}][dokumentasi_file]`, r.dokumentasi_file);
            }
        });

        // Connection
        formData.append('connection[rincian_sambungan]', form.connection.rincian_sambungan || '');
        if (form.connection.is_tumbuh_tunas !== null) {
            formData.append('connection[is_tumbuh_tunas]', form.connection.is_tumbuh_tunas ? '1' : '0');
        }
        formData.append('connection[alasan]', form.connection.alasan || '');

        // P3 Questions
        Object.keys(form.p3questions).forEach(key => {
            formData.append(`p3questions[${key}]`, form.p3questions[key] || '');
        });

        router.post(`/mahasiswa/pembelajaran/lkm-grafting/form/${pertemuan}`, formData, {
            preserveScroll: true,
            forceFormData: true,
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
        return; // Early return — we handle P3 separately with FormData
    } else if (pertemuan === 4) {
        payload.analyses = form.analyses;
        payload.deepQuestions = form.deepQuestions;
        payload.selfAssessments = form.selfAssessments;
        payload.reflections = form.reflections;
    }

    form.transform(() => payload).post(`/mahasiswa/pembelajaran/lkm-grafting/form/${pertemuan}`, {
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

const getFilePreview = (file) => {
    return file ? URL.createObjectURL(file) : null;
};

// sweet alert toast

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
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 flex items-start gap-3">
                <Button type="button" variant="ghost" size="icon" @click="goBack" class="mt-1 flex-shrink-0 hover:bg-gray-100 rounded-lg">
                    <ArrowLeft class="w-5 h-5 text-gray-600" />
                </Button>
                <div class="flex-grow">
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
        </div>

        <div v-if="submission.catatan_dosen" class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 shadow-sm flex items-start gap-3">
            <NotepadText class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
            <div>
                <h3 class="font-bold text-green-800 text-sm">Catatan / Feedback dari Dosen:</h3>
                <p class="text-xs sm:text-sm text-green-700 mt-1 whitespace-pre-line leading-relaxed">{{ submission.catatan_dosen }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <div 
                class="col-span-3 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col justify-between h-full"
            >
            <form @submit.prevent="submitData('submit')" class="space-y-8">

                <!-- ============================================ -->
                <!-- LKM 1 (Sintak 1-3) -->
                <!-- ============================================ -->
                <template v-if="submission.pertemuan == 1">
                    
                    <!-- Sintak 1: Pertanyaan Esensial -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Sintak 1: Pertanyaan Esensial</h2>
                            <p class="text-xs text-gray-500 mt-1">Jawablah pertanyaan-pertanyaan berikut berdasarkan pemahaman Anda tentang teknik grafting dan jaringan tumbuhan.</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Skenario Pemantik Box -->
                            <div class="border-2 border-green-300 rounded-xl p-5 bg-green-50/10">
                                <h3 class="text-sm font-bold text-gray-800 tracking-wider text-center uppercase mb-3">Skenario Pemantik</h3>
                                <p class="text-sm text-gray-700 leading-relaxed text-justify mb-4">
                                    Bayangkan Anda adalah seorang petani buah yang ingin menghasilkan mangga harum manis dengan sistem perakaran yang kuat dan tahan terhadap penyakit. Salah satu cara yang dapat ditempuh adalah dengan teknik grafting menyambungkan batang mangga harum manis (scion) ke batang pohon kuwini yang kuat (rootstock).
                                </p>
                                <p class="text-sm text-green-600 font-medium leading-relaxed text-justify">
                                    Namun, mengapa tidak semua grafting berhasil? Apa yang terjadi di tingkat jaringan saat dua batang disambungkan? Bagaimana kambium berperan? Faktor lingkungan apa yang memengaruhi proses ini?
                                </p>
                            </div>

                            <!-- Intro Text -->
                            <p class="text-sm font-semibold text-gray-700 leading-relaxed">
                                Berdasarkan skenario di atas, rumuskan pertanyaan esensial yang akan menjadi fokus proyek Anda!
                            </p>

                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q1:</span>
                                    Bagaimana jenis tumbuhan yang cocok untuk grafting sehingga grafting yang dilakukan berhasil?
                                </label>
                                <textarea 
                                    v-model="form.questions.q1_jenis_tumbuhan_cocok" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3" 
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q2:</span>
                                    Jelaskan jaringan-jaringan tumbuhan yang terlibat dalam teknik grafting!
                                </label>
                                <textarea 
                                    v-model="form.questions.q2_jaringan_terlibat" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q3:</span>
                                    Bagaimana pemilihan batang bawah yang tepat dapat menentukan pertumbuhan dan produktivitas tanaman yang di grafting?
                                </label>
                                <textarea 
                                    v-model="form.questions.q3_pemilihan_batang_bawah" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q4:</span>
                                    Bagaimana struktur jaringan kambium berperan dalam proses grafting?
                                </label>
                                <textarea 
                                    v-model="form.questions.q4_peran_kambium" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q5:</span>
                                    Bagaimana kondisi lingkungan, seperti suhu dan kelembapan, mempengaruhi keberhasilan grafting?
                                </label>
                                <textarea 
                                    v-model="form.questions.q5_kondisi_lingkungan" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3"
                                    placeholder="Jawaban Anda..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Sintak 2: Pemilihan Tanaman -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Sintak 2: Perencanaan Proyek - Pemilihan Tanaman</h2>
                            <p class="text-xs text-gray-500 mt-1">Bandingkan dua jenis tanaman yang akan digunakan untuk grafting. Isilah data berikut untuk Tanaman A dan Tanaman B.</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-1/5">Variabel</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Tanaman A</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Tanaman B</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Alasan Pemilihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(spec, index) in form.specs" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ spec.variabel }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="spec.tanaman_a" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Isi data..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="spec.tanaman_b" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Isi data..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="spec.alasan_pemilihan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Alasan..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sintak 2: Alat & Bahan -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Persiapan Alat dan Bahan</h2>
                            <p class="text-xs text-gray-500 mt-1">Uraikan alat dan bahan apa saja yang digunakan untuk melakukan grafting tanaman!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-16">No</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/2">Alat</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/2">Bahan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in form.items" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50 text-center">
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

                    <!-- Sintak 3: Prosedur Kerja -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Sintak 3: Prosedur Kerja Grafting</h2>
                            <p class="text-xs text-gray-500 mt-1">Uraikan tahap-tahap yang dilaksanakan untuk melakukan grafting dari awal sampai akhir!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-16">No</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Tahap</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-3/4">Penjelasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(proc, index) in form.procedures" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50 text-center">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="proc.tahap" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Nama Tahap..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="proc.penjelasan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Penjelasan langkah..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sintak 3: Jadwal Capaian -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Penyusunan Jadwal</h2>
                            <p class="text-xs text-gray-500 mt-1">Tentukan target kegiatan untuk setiap pertemuan!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-16">Pertemuan Ke-</th>
                                        <th class="p-3 border border-gray-200 font-semibol">Target Kegiatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(sched, index) in form.schedules" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50 text-center">
                                            {{ sched.pertemuan_ke }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="sched.target_kegiatan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tuliskan target kegiatan..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </template>

                <!-- ============================================ -->
                <!-- LKM 2 (Sintak 4) -->
                <!-- ============================================ -->
                <template v-else-if="submission.pertemuan == 2">
                    
                    <!-- Persiapan Alat & Bahan -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Sintak 4: Persiapan Alat dan Bahan</h2>
                            <p class="text-xs text-gray-500 mt-1">Tuliskan alat dan bahan yang digunakan untuk pelaksanaan grafting!</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Petunjuk Pelaksanaan Box -->
                            <div class="border-2 border-green-300 rounded-xl p-5 bg-green-50/10">
                                <h3 class="text-sm font-bold text-gray-800 tracking-wider mb-2">Petunjuk Pelaksanaan</h3>
                                <p class="text-sm text-gray-700 leading-relaxed text-justify">
                                    Pada pertemuan ini, Anda akan melaksanakan teknik grafting sesuai dengan rancangan yang telah dibuat pada Pertemuan 1. Ikuti prosedur dengan teliti, dokumentasikan setiap langkah, dan catat semua pengamatan secara jujur dan sistematis.
                                </p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse border border-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50 text-sm text-gray-600">
                                            <th class="p-3 border border-gray-200 font-semibold w-16">No</th>
                                            <th class="p-3 border border-gray-200 font-semibold">Alat/Bahan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in form.p2items" :key="index">
                                            <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50 text-center">
                                                {{ index + 1 }}
                                            </td>
                                            <td class="p-3 border border-gray-200">
                                                <textarea 
                                                    v-model="item.nama_item" 
                                                    :disabled="isReadOnly"
                                                    class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                    rows="2" 
                                                    placeholder="Tuliskan alat/bahan..."></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Identifikasi Spesimen -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Identifikasi Spesimen Tanaman</h2>
                            <p class="text-xs text-gray-500 mt-1">Sebutkan dan identifikasi jenis tumbuhan yang digunakan untuk grafting!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-1/5">Keterangan</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Batang Bawah (Rootstock)</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Batang Atas (Scion)</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Alasan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(spec, index) in form.p2specs" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ spec.keterangan }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="spec.batang_bawah" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Isi data..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="spec.batang_atas" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Isi data..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="spec.alasan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Alasan..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Prosedur Pelaksanaan -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Prosedur Pelaksanaan Grafting</h2>
                            <p class="text-xs text-gray-500 mt-1">Uraikan prosedur pelaksanaan grafting beserta kondisi jaringan yang teramati!</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-16">No</th>
                                        <th class="p-3 border border-gray-200 font-semibold">Tahap Kegiatan</th>
                                        <th class="p-3 border border-gray-200 font-semibold">Kondisi Jaringan yang Teramati</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(proc, index) in form.p2procedures" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50 text-center">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="proc.tahap_kegiatan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tahap kegiatan..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="proc.kondisi_jaringan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Kondisi jaringan..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Monitoring Proyek -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Monitoring Proyek</h2>
                            <p class="text-xs text-gray-500 mt-1">Catat hasil monitoring awal setelah proses grafting dilakukan.</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-1/4">Aspek</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-3/4">Hasil Pengamatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(mon, index) in form.p2monitorings" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ mon.aspek }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="mon.hasil_pengamatan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Tulis hasil pengamatan..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pertanyaan Esensial P2 -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Pertanyaan Esensial</h2>
                            <p class="text-xs text-gray-500 mt-1">Jawablah pertanyaan berikut berdasarkan pengalaman pelaksanaan grafting Anda!</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q1:</span>
                                    Mengapa sambungan grafting harus ditutup rapat setelah penyambungan dilakukan?
                                </label>
                                <textarea v-model="form.p2questions.q1_ditutup_rapat" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q2:</span>
                                    Bagaimana pengaruh kelembapan terhadap keberhasilan proses penyambungan?
                                </label>
                                <textarea v-model="form.p2questions.q2_pengaruh_kelembapan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q3:</span>
                                    Mengapa lokasi penyimpanan tanaman setelah grafting perlu diperhatikan?
                                </label>
                                <textarea v-model="form.p2questions.q3_lokasi_penyimpanan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q4:</span>
                                    Apa yang terjadi jika kekuatan sambungan lemah pada tanaman hasil grafting?
                                </label>
                                <textarea v-model="form.p2questions.q4_kekuatan_lemah" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q5:</span>
                                    Apa indikator awal yang menunjukkan keberhasilan atau kegagalan grafting pada minggu pertama?
                                </label>
                                <textarea v-model="form.p2questions.q5_keberhasilan_kegagalan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q6:</span>
                                    Bagaimana peran xilem dan floem dalam mendukung keberhasilan grafting?
                                </label>
                                <textarea v-model="form.p2questions.q6_peran_xilem" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q7:</span>
                                    Bagaimana peran jaringan epidermis dalam melindungi area sambungan grafting?
                                </label>
                                <textarea v-model="form.p2questions.q7_peran_epidermis" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q8:</span>
                                    Mengapa aktivitas jaringan meristem penting dalam proses penyembuhan grafting?
                                </label>
                                <textarea v-model="form.p2questions.q8_aktivitas_meristem" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- ============================================ -->
                <!-- LKM 3 (Sintak 5) -->
                <!-- ============================================ -->
                <template v-else-if="submission.pertemuan == 3">

                    <!-- Pengamatan Pertumbuhan Tunas & Daun -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Sintak 5: Pengamatan Pertumbuhan Tunas dan Daun</h2>
                            <p class="text-xs text-gray-500 mt-1">Amati dan catat perkembangan tunas serta daun pada tanaman hasil grafting.</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Tujuan Pertemuan Ini Box -->
                            <div class="border-2 border-green-300 rounded-xl p-5 bg-green-50/10">
                                <h3 class="text-sm font-bold text-gray-800 tracking-wider mb-2">Tujuan Pertemuan Ini</h3>
                                <p class="text-sm text-gray-700 leading-relaxed text-justify">
                                    Pada pertemuan ini, Anda akan mengamati dan mendokumentasikan perkembangan tanaman hasil grafting yang telah dilakukan pada Pertemuan 2. Amati secara cermat setiap perubahan yang terjadi, baik pada scion, rootstock, maupun sambungan antar keduanya. Data yang dikumpulkan akan menjadi dasar analisis pada Pertemuan 4.
                                </p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse border border-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50 text-sm text-gray-600">
                                            <th class="p-3 border border-gray-200 font-semibold w-1/4">Parameter</th>
                                            <th class="p-3 border border-gray-200 font-semibold w-1/4">Data/Jumlah</th>
                                            <th class="p-3 border border-gray-200 font-semibold w-1/2">Deskripsi Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(growth, index) in form.growths" :key="index">
                                            <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                                {{ growth.parameter }}
                                            </td>
                                            <td class="p-3 border border-gray-200">
                                                <input 
                                                    v-model="growth.data_jumlah" 
                                                    :disabled="isReadOnly"
                                                    type="text"
                                                    class="w-full text-sm border-0 focus:ring-0 p-2 bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                    placeholder="Data...">
                                            </td>
                                            <td class="p-3 border border-gray-200">
                                                <textarea 
                                                    v-model="growth.deskripsi_kondisi" 
                                                    :disabled="isReadOnly"
                                                    class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                    rows="2" 
                                                    placeholder="Deskripsi kondisi..."></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pengamatan Kondisi Batang Atas (Scion) -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Pengamatan Kondisi Batang Atas (Scion)</h2>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-1/5">Parameter</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/5">Deskripsi Kondisi</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/5">Dokumentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(scion, index) in form.scions" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ scion.parameter }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="scion.kondisi_deskripsi" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Deskripsi kondisi..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <!-- Existing uploaded image -->
                                            <div v-if="scion.dokumentasi_path && !scion.dokumentasi_file" class="mb-2">
                                                <div class="relative inline-block group">
                                                    <img 
                                                        :src="`/storage/${scion.dokumentasi_path}`" 
                                                        alt="Dokumentasi" 
                                                        class="w-24 h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 hover:scale-[1.02] transition-all duration-200"
                                                        @click="openImagePreview(`/storage/${scion.dokumentasi_path}`)"
                                                    >
                                                    <button v-if="!isReadOnly" type="button" @click="scion.dokumentasi_path = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600 transition-colors">
                                                        <X class="w-3 h-3" />
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- New file preview -->
                                            <div v-if="scion.dokumentasi_file" class="mb-2">
                                                <div class="relative inline-block group">
                                                    <img 
                                                        :src="getFilePreview(scion.dokumentasi_file)" 
                                                        alt="Preview" 
                                                        class="w-24 h-24 object-cover rounded-lg border border-green-300 cursor-pointer hover:opacity-90 hover:scale-[1.02] transition-all duration-200"
                                                        @click="openImagePreview(getFilePreview(scion.dokumentasi_file))"
                                                    >
                                                    <button v-if="!isReadOnly" type="button" @click="scion.dokumentasi_file = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600 transition-colors">
                                                        <X class="w-3 h-3" />
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Upload button -->
                                            <label v-if="!isReadOnly && !scion.dokumentasi_file" class="flex items-center gap-2 cursor-pointer text-sm text-green-600 hover:text-green-700 transition-colors">
                                                <Camera class="w-4 h-4" />
                                                <span>Upload Foto</span>
                                                <input 
                                                    type="file" 
                                                    accept="image/*" 
                                                    class="hidden" 
                                                    @change="(e) => { scion.dokumentasi_file = e.target.files[0]; }">
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pengamatan Kondisi Batang Bawah (Rootstock) -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Pengamatan Kondisi Batang Bawah (Rootstock)</h2>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-1/5">Parameter</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/5">Deskripsi Kondisi</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/5">Dokumentasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(rootstock, index) in form.rootstocks" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ rootstock.parameter }}
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="rootstock.kondisi_deskripsi" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Deskripsi kondisi..."></textarea>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <!-- Existing uploaded image -->
                                            <div v-if="rootstock.dokumentasi_path && !rootstock.dokumentasi_file" class="mb-2">
                                                <div class="relative inline-block group">
                                                    <img 
                                                        :src="`/storage/${rootstock.dokumentasi_path}`" 
                                                        alt="Dokumentasi" 
                                                        class="w-24 h-24 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 hover:scale-[1.02] transition-all duration-200"
                                                        @click="openImagePreview(`/storage/${rootstock.dokumentasi_path}`)"
                                                    >
                                                    <button v-if="!isReadOnly" type="button" @click="rootstock.dokumentasi_path = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600 transition-colors">
                                                        <X class="w-3 h-3" />
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- New file preview -->
                                            <div v-if="rootstock.dokumentasi_file" class="mb-2">
                                                <div class="relative inline-block group">
                                                    <img 
                                                        :src="getFilePreview(rootstock.dokumentasi_file)" 
                                                        alt="Preview" 
                                                        class="w-24 h-24 object-cover rounded-lg border border-green-300 cursor-pointer hover:opacity-90 hover:scale-[1.02] transition-all duration-200"
                                                        @click="openImagePreview(getFilePreview(rootstock.dokumentasi_file))"
                                                    >
                                                    <button v-if="!isReadOnly" type="button" @click="rootstock.dokumentasi_file = null" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-0.5 hover:bg-red-600 transition-colors">
                                                        <X class="w-3 h-3" />
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Upload button -->
                                            <label v-if="!isReadOnly && !rootstock.dokumentasi_file" class="flex items-center gap-2 cursor-pointer text-sm text-green-600 hover:text-green-700 transition-colors">
                                                <Camera class="w-4 h-4" />
                                                <span>Upload Foto</span>
                                                <input 
                                                    type="file" 
                                                    accept="image/*" 
                                                    class="hidden" 
                                                    @change="(e) => { rootstock.dokumentasi_file = e.target.files[0]; }">
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pengamatan Kondisi Sambungan -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Pengamatan Kondisi Sambungan</h2>
                        </div>
                        <div class="p-5 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800">Uraikan rincian kondisi pada sambungan batang yang teramati:</label>
                                <textarea 
                                    v-model="form.connection.rincian_sambungan" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="4" 
                                    placeholder="Deskripsikan kondisi sambungan..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800">Apakah batang bawah menumbuhkan tunas baru?</label>
                                <div class="flex items-center gap-4 mt-1">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" v-model="form.connection.is_tumbuh_tunas" :value="true" :disabled="isReadOnly" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" v-model="form.connection.is_tumbuh_tunas" :value="false" :disabled="isReadOnly" class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800">Jelaskan alasan / penyebabnya:</label>
                                <textarea 
                                    v-model="form.connection.alasan" 
                                    :disabled="isReadOnly"
                                    class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" 
                                    rows="3" 
                                    placeholder="Jelaskan alasan..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Pertanyaan Esensial P3 -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Pertanyaan Esensial</h2>
                        </div>
                        <div class="p-5 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q1:</span>
                                    Apakah grafting yang dilakukan berhasil? Jelaskan!
                                </label>
                                <textarea v-model="form.p3questions.q1_apakah_berhasil" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q2:</span>
                                    Apa indikator anatomi yang menunjukkan keberhasilan penyatuan jaringan?
                                </label>
                                <textarea v-model="form.p3questions.q2_indikator_keberhasilan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q3:</span>
                                    Mengapa tunas baru bisa muncul dari batang atas setelah grafting berhasil?
                                </label>
                                <textarea v-model="form.p3questions.q3_tunas_baru_muncul" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q4:</span>
                                    Bagaimana hubungan antara jaringan pengangkut (xilem dan floem) dengan pertumbuhan tunas dan daun baru?
                                </label>
                                <textarea v-model="form.p3questions.q4_hubungan_jaringan_pengangkut" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q5:</span>
                                    Apa faktor anatomi yang mungkin menyebabkan kegagalan grafting pada tanaman Anda?
                                </label>
                                <textarea v-model="form.p3questions.q5_faktor_penyebab_gagal" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                </template>
                
                <!-- ============================================ -->
                <!-- LKM 4 (Sintak 6) -->
                <!-- ============================================ -->
                <template v-else-if="submission.pertemuan == 4">

                    <!-- Analisis Keberhasilan -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h1 class="text-xl font-bold text-gray-800">Sintak 6: Analisis Keberhasilan Tanaman Hasil Grafting</h1>
                            <p class="text-xs text-gray-500 mt-1">Analisis keberhasilan tanaman grafting Anda berdasarkan variabel-variabel berikut.</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <!-- Tujuan Pertemuan Ini Box -->
                            <div class="border-2 border-green-300 rounded-xl p-5 bg-green-50/10">
                                <h3 class="text-sm font-bold text-gray-800 tracking-wider mb-2">Tujuan Pertemuan Ini</h3>
                                <p class="text-sm text-gray-700 leading-relaxed text-justify">
                                    Pada pertemuan akhir ini, kelompok Anda akan menganalisis secara menyeluruh keberhasilan proyek grafting, mempresentasikan hasil proyek kepada kelas, dan merefleksikan proses pembelajaran yang telah dilalui. Sintak ini bertujuan untuk mengintegrasikan pengetahuan anatomi tumbuhan dengan pengalaman praktis grafting.
                                </p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse border border-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50 text-sm text-gray-600">
                                            <th class="p-3 border border-gray-200 font-semibold w-1/4">Variabel Analisis</th>
                                            <th class="p-3 border border-gray-200 font-semibold w-3/4">Hasil Pengamatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(analysis, index) in form.analyses" :key="index">
                                            <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                                {{ analysis.variabel_analisis }}
                                            </td>
                                            <td class="p-3 border border-gray-200">
                                                <textarea 
                                                    v-model="analysis.hasil_pengamatan" 
                                                    :disabled="isReadOnly"
                                                    class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                    rows="2" 
                                                    placeholder="Tulis hasil pengamatan..."></textarea>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Pertanyaan Analisis Mendalam -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Pertanyaan Analisis Mendalam</h2>
                        </div>
                        <div class="p-5 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q1:</span>
                                    Apa tujuan dilakukan grafting dan bagaimana kaitannya dengan anatomi tumbuhan?
                                </label>
                                <textarea v-model="form.deepQuestions.q1_tujuan_grafting" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q2:</span>
                                    Bagaimana karakteristik anatomi batang yang mempengaruhi keberhasilan grafting antara batang bawah dengan batang atas?
                                </label>
                                <textarea v-model="form.deepQuestions.q2_karakteristik_anatomi" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q3:</span>
                                    Mengapa kesejajaran jaringan kambium sangat penting dalam teknik grafting?
                                </label>
                                <textarea v-model="form.deepQuestions.q3_kesejajaran_kambium" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q4:</span>
                                    Apa faktor anatomi yang menyebabkan ketidakcocokan (inkompatibilitas) antara dua spesies yang dicoba untuk digrafting?
                                </label>
                                <textarea v-model="form.deepQuestions.q4_faktor_anatomi_inkompatibilitas" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">Q5:</span>
                                    Bagaimana tumbuhan dapat pulih pada tempat terjadi grafting (proses penyembuhan)?
                                </label>
                                <textarea v-model="form.deepQuestions.q5_proses_penyembuhan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Penilaian Diri -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Penilaian Diri</h2>
                            <p class="text-xs text-gray-500 mt-1">Berikan penilaian diri Anda (skala 1-5) untuk setiap aspek berikut. 1 = Sangat Kurang, 5 = Sangat Baik.</p>
                        </div>
                        <div class="p-5 overflow-x-auto">
                            <table class="w-full text-left border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-gray-50 text-sm text-gray-600">
                                        <th class="p-3 border border-gray-200 font-semibold w-2/5">Aspek</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-1/5 text-center">Skor (1-5)</th>
                                        <th class="p-3 border border-gray-200 font-semibold w-2/5">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(assessment, index) in form.selfAssessments" :key="index">
                                        <td class="p-3 border border-gray-200 text-sm font-medium text-gray-700 bg-gray-50/50">
                                            {{ assessment.aspek }}
                                        </td>
                                        <td class="p-3 border border-gray-200 text-center">
                                            <div class="flex justify-center gap-1">
                                                <template v-for="score in 5" :key="score">
                                                    <label class="cursor-pointer">
                                                        <input 
                                                            type="radio" 
                                                            :name="`assessment_${index}`"
                                                            :value="score"
                                                            v-model="assessment.skor"
                                                            :disabled="isReadOnly"
                                                            class="sr-only peer"
                                                        >
                                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg border border-gray-300 text-sm font-medium text-gray-600 peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500 hover:bg-gray-100 transition-colors disabled:opacity-50">
                                                            {{ score }}
                                                        </span>
                                                    </label>
                                                </template>
                                            </div>
                                        </td>
                                        <td class="p-3 border border-gray-200">
                                            <textarea 
                                                v-model="assessment.catatan" 
                                                :disabled="isReadOnly"
                                                class="w-full text-sm border-0 focus:ring-0 p-2 resize-none bg-transparent placeholder-gray-300 disabled:text-gray-500" 
                                                rows="2" 
                                                placeholder="Catatan (opsional)..."></textarea>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Refleksi Essay -->
                    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-5 border-b border-gray-100 bg-gray-50">
                            <h2 class="text-lg font-bold text-gray-800">Refleksi</h2>
                            <p class="text-xs text-gray-500 mt-1">Jawablah pertanyaan refleksi berikut berdasarkan pengalaman Anda selama proyek grafting.</p>
                        </div>
                        <div class="p-5 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R1:</span>
                                    Apa pengalaman baru yang Anda dapatkan dari proyek grafting ini?
                                </label>
                                <textarea v-model="form.reflections.r1_pengalaman_baru" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R2:</span>
                                    Apa kesulitan yang Anda hadapi selama proses grafting?
                                </label>
                                <textarea v-model="form.reflections.r2_kesulitan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R3:</span>
                                    Bagaimana cara Anda mengatasi kesulitan tersebut?
                                </label>
                                <textarea v-model="form.reflections.r3_cara_mengatasi" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R4:</span>
                                    Apa manfaat pembelajaran berbasis proyek (PjBL) yang Anda rasakan?
                                </label>
                                <textarea v-model="form.reflections.r4_manfaat_pjbl" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R5:</span>
                                    Bagaimana perasaan Anda selama mengerjakan proyek grafting ini?
                                </label>
                                <textarea v-model="form.reflections.r5_perasaan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R6:</span>
                                    Mengapa kesejajaran jaringan kambium sangat penting dalam teknik grafting?
                                </label>
                                <textarea v-model="form.reflections.r6_kesejajaran_kambium" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R7:</span>
                                    Apa peran kutikula dalam mencegah kehilangan air selama tahap awal grafting?
                                </label>
                                <textarea v-model="form.reflections.r7_peran_kutikula" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R8:</span>
                                    Apa pengaruh perbedaan struktur sel epidermis pada batang bawah terhadap keberhasilan penyatuan dalam grafting?
                                </label>
                                <textarea v-model="form.reflections.r8_perbedaan_sel_epidermis" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R9:</span>
                                    Bagaimana kondisi lingkungan, seperti suhu dan kelembapan, mempengaruhi keberhasilan grafting?
                                </label>
                                <textarea v-model="form.reflections.r9_kondisi_lingkungan" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-800 flex gap-2">
                                    <span class="text-green-600">R10:</span>
                                    Apa fungsi sungkup yang dilakukan pada proses grafting?
                                </label>
                                <textarea v-model="form.reflections.r10_fungsi_sungkup" :disabled="isReadOnly" class="p-4 w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm disabled:bg-gray-100 disabled:text-gray-600" rows="3" placeholder="Jawaban Anda..."></textarea>
                            </div>
                        </div>
                    </div>
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

        <!-- Image Preview Modal -->
        <div 
            v-if="activePreviewImage" 
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 transition-all duration-300"
            @click="closeImagePreview"
        >
            <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.stop>
                <button 
                    type="button" 
                    @click="closeImagePreview" 
                    class="absolute -top-14 right-2 sm:right-0 bg-white/10 hover:bg-white/20 text-white rounded-full p-2 transition-colors border border-white/10 cursor-pointer"
                >
                    <X class="w-6 h-6" />
                </button>
                <img 
                    :src="activePreviewImage" 
                    alt="Preview Besar" 
                    class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl border border-white/10" 
                />
            </div>
        </div>
    </Layout>
</template>
