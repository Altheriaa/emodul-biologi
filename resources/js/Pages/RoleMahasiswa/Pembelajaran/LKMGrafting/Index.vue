<script setup>
import Layout from '../../../../App.vue';
import { Button } from '@/components/ui/button';
import { ArrowRight, Lock, Plus, Trash, Pencil, Clock, NotepadText, X } from 'lucide-vue-next';
import { router, usePage } from '@inertiajs/vue3';
import { Toast } from '@/lib/toast';
import Swal from 'sweetalert2';
import { onMounted, watch, ref } from 'vue';

const props = defineProps({
    listPertemuan: Array,
});

const goToForm = (pertemuan) => {
    router.visit(`/mahasiswa/pembelajaran/lkm-grafting/form/${pertemuan}`);
};

const formatTanggal = (dateString) => {
    if (!dateString) return '-';
    const opsi = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString('id-ID', opsi) + ' WIB';
};

const isModalOpen = ref(false);
const modalCatatan = ref('');
const modalPertemuan = ref('');

const openModal = (catatan, pertemuan) => {
    modalCatatan.value = catatan;
    modalPertemuan.value = pertemuan;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
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
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">LKM Grafting</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Daftar LKM Grafting</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <div 
                v-for="item in listPertemuan" 
                :key="item.pertemuan"
                class="col-span-1 bg-white border border-gray-200 rounded-xl p-4 sm:p-5 flex flex-col justify-between h-full hover:border-gray-300 transition-colors duration-200"
            >
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">{{ item.judul }}</h2>
                        
                        <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                            <span v-if="!item.submission" class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-medium">
                                Belum Mulai
                            </span>
                            <span v-else-if="item.submission.status === 'draft'" class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-medium border border-yellow-200">
                                Draft
                            </span>
                            <span v-else-if="item.submission.status === 'submitted'" class="bg-green-100 text-green-700 px-2.5 py-1 rounded-md text-[10px] sm:text-xs font-medium border border-green-200">
                                Terkumpul
                            </span>
                            
                            <!-- <span v-if="item.submission?.catatan_dosen" class="bg-green-50 text-green-600 px-2 py-0.5 rounded-full text-[10px] font-semibold border border-green-100 flex items-center gap-1 shadow-sm animate-pulse">
                                <span class="relative flex h-1.5 w-1.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-500"></span>
                                </span>
                                Ada Catatan
                            </span> -->
                        </div>
                    </div>
                    
                    <p class="text-xs sm:text-sm text-gray-500 mb-4 line-clamp-2">
                        {{ item.setting?.deskripsi || 'Belum Dibuka' }}
                    </p>

                    <div v-if="item.setting" class="flex flex-col gap-1.5 text-[11px] sm:text-xs text-gray-500 mb-3 bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                        <div class="flex items-center gap-1.5">
                            <Clock class="w-3.5 h-3.5 text-blue-500" />
                            <span>Dibuka: <span class="font-medium text-gray-700">{{ formatTanggal(item.setting.open_at) }}</span></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <Clock class="w-3.5 h-3.5" :class="item.status_akses.is_overdue ? 'text-red-500' : 'text-orange-500'" />
                            <span>Ditutup: <span class="font-medium" :class="item.status_akses.is_overdue ? 'text-red-600' : 'text-gray-700'">{{ formatTanggal(item.setting.deadline_at) }}</span></span>
                        </div>
                    </div>

                    <!-- Lecturer Feedback Note (Compact & Clickable Modal Trigger) -->
                    <button 
                        v-if="item.submission?.catatan_dosen" 
                        type="button"
                        @click="openModal(item.submission.catatan_dosen, item.pertemuan)"
                        class="w-full mt-3 text-left bg-green-50/70 hover:bg-green-100/50 border border-green-100/80 rounded-xl p-3 shadow-sm hover:shadow transition-all duration-200 flex items-center justify-between gap-3 group"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="bg-green-500/10 p-1.5 rounded-lg text-green-600 group-hover:bg-green-500/20 transition-colors">
                                <NotepadText class="w-4 h-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-green-900">Catatan Dosen Tersedia</p>
                                <p class="text-[10px] text-green-600 truncate mt-0.5 max-w-[200px] sm:max-w-[280px]">
                                    Klik untuk membaca feedback dari dosen
                                </p>
                            </div>
                        </div>
                        <ArrowRight class="w-4 h-4 text-green-500 transform group-hover:translate-x-1 transition-transform flex-shrink-0" />
                    </button>
                </div>

                <div>
                    <hr class="border-gray-200 mt-2 mb-4">
                    
                    <div class="flex items-center justify-between gap-2">
                        <!-- <div class="flex items-center">
                            <div v-if="item.submission?.nilai !== null" class="flex flex-col">
                                <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Nilai</span>
                                <span class="text-lg font-bold text-green-600 leading-none">{{ item.submission.nilai }}</span>
                            </div>
                            <span v-else-if="item.submission?.status === 'submitted'" class="text-xs text-gray-400 italic">
                                Belum dinilai
                            </span>
                        </div> -->

                        <div class="flex gap-1.5 sm:gap-2 justify-end ml-auto">
                            
                            <Button 
                                v-if="item.submission?.status === 'submitted'" 
                                variant="outline" 
                                @click="goToForm(item.pertemuan)" 
                                class="h-8 px-3 sm:h-9 sm:px-4 border-gray-300 text-gray-600 hover:bg-gray-50 text-[10px] sm:text-sm font-medium transition-all duration-200"
                                :class="item.submission?.catatan_dosen ? 'border-green-300 text-green-700 hover:bg-green-50/50 hover:border-green-400' : ''"
                            >
                                <NotepadText class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600" v-if="item.submission?.catatan_dosen" />
                                <Lock class="w-3.5 h-3.5 sm:w-4 sm:h-4" v-else />
                                <span>{{ item.submission?.catatan_dosen ? 'Lihat Jawaban & Catatan' : 'Lihat Jawaban' }}</span>
                            </Button>

                            <Button 
                                v-else-if="item.status_akses.bisa_mengisi" 
                                variant="outline" 
                                @click="goToForm(item.pertemuan)" 
                                class="h-8 px-3 sm:h-9 sm:px-4 border-green-600 text-green-700 hover:bg-green-50 text-[10px] sm:text-sm font-medium"
                            >
                                <ArrowRight class="w-3.5 h-3.5 sm:w-4 sm:h-4" v-if="!item.submission" />
                                <Pencil class="w-3.5 h-3.5 sm:w-4 sm:h-4" v-else />
                                <span>{{ item.submission ? 'Lanjutkan' : 'Mulai LKM' }}</span>
                            </Button>

                            <span v-else class="text-[10px] sm:text-xs text-red-500 font-medium px-2 py-1 bg-red-50 rounded border border-red-100">
                                Waktu Habis
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Catatan Dosen (Form/Card Overlay) -->
        <div 
            v-if="isModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300"
            @click.self="closeModal"
        >
            <div class="bg-white border border-gray-250 rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] flex flex-col overflow-hidden transform transition-all duration-300 scale-100 animate-in fade-in zoom-in-95">
                <!-- Header -->
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-green-600">
                        <NotepadText class="w-5 h-5" />
                        <span class="font-bold text-gray-800 text-base sm:text-lg">Catatan LKM Pertemuan {{ modalPertemuan }}</span>
                    </div>
                    <button 
                        type="button" 
                        @click="closeModal" 
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1.5 rounded-lg hover:bg-gray-100"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6 overflow-y-auto space-y-4 flex-1">
                    <div class="bg-green-50/40 border border-green-100 rounded-xl p-4.5">
                        <p class="text-xs text-green-600 font-semibold uppercase tracking-wider mb-2">Feedback / Catatan dari Dosen:</p>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line text-left">
                            {{ modalCatatan }}
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50 flex justify-end">
                    <Button 
                        type="button" 
                        @click="closeModal" 
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition-colors"
                    >
                        Tutup
                    </Button>
                </div>
            </div>
        </div>
    </Layout>
</template>

