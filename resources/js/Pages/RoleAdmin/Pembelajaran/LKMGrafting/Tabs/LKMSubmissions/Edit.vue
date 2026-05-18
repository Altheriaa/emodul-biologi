<script setup>
import Layout from '../../Index.vue';
import { useForm, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'

const props = defineProps({
    lkm: Object,
});

const formatDateTimeLocal = (dateStr) => {
    if (!dateStr) return '';
    return dateStr.replace(' ', 'T').slice(0, 16);
};

const form = useForm({
    pertemuan: props.lkm.pertemuan,
    title: props.lkm.title,
    deskripsi: props.lkm.deskripsi,
    open_at: formatDateTimeLocal(props.lkm.open_at),
    deadline_at: formatDateTimeLocal(props.lkm.deadline_at),
    is_active: props.lkm.is_active,
    allow_late_submit: props.lkm.allow_late_submit,
});

const cancel = () => {
    router.visit('/admin/pembelajaran/lkm-grafting/settings', {
        preserveScroll: true,
    });
};

const submit = () => {
    form.put(`/admin/pembelajaran/lkm-grafting/settings/${props.lkm.id}`, {
        preserveScroll: true,
    });
};

</script>

<template>
    <Layout>
        <!-- Page Header -->
        <div class="grid grid-cols-1 gap-4 mb-4 mt-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4 sm:p-6 shadow-sm">
                <p class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">Update LKM Settings</p>
                <p class="text-xs sm:text-sm text-green-600 mt-1">Isi informasi dasar LKM Settings</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Form Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-800 mb-5">Informasi LKM Settings</h2>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <!-- Pertemuan -->
                        <div class="space-y-1.5">
                            <label for="pertemuan" class="block text-xs font-medium text-gray-600">
                                Pertemuan <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="pertemuan"
                                v-model="form.pertemuan"
                                type="number"
                                placeholder="1 / 2 / 3 / 4"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.pertemuan" class="text-red-500 text-xs block">
                                {{ form.errors.pertemuan }}
                            </small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="space-y-1.5">
                            <label for="title" class="block text-xs font-medium text-gray-600">
                                Judul LKM Setting <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                placeholder="Judul LKM : LKM 1 (Topik : ...)"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.title" class="text-red-500 text-xs block">
                                {{ form.errors.title }}
                            </small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="space-y-1.5">
                            <label for="deskripsi" class="block text-xs font-medium text-gray-600">
                                Deskripsi <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="deskripsi"
                                v-model="form.deskripsi"
                                type="text"
                                placeholder="Deskripsi Singkat LKM"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.deskripsi" class="text-red-500 text-xs block">
                                {{ form.errors.deskripsi }}
                            </small>
                        </div>

                        <!-- se -->
                        <div class="space-y-1.5">
                            <label for="open_at" class="block text-xs font-medium text-gray-600">
                                Dibuka Pada <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="open_at"
                                v-model="form.open_at"
                                type="datetime-local"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.open_at" class="text-red-500 text-xs block">
                                {{ form.errors.open_at }}
                            </small>
                        </div>

                        <!-- Deadline -->
                        <div class="space-y-1.5">
                            <label for="deadline_at" class="block text-xs font-medium text-gray-600">
                                Deadline <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="deadline_at"
                                v-model="form.deadline_at"
                                type="datetime-local"
                                class="w-full px-3 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-800 placeholder-gray-300 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition"
                            />
                            <small v-if="form.errors.deadline_at" class="text-red-500 text-xs block">
                                {{ form.errors.deadline_at }}
                            </small>
                        </div>

                        <!-- Status -->
                        <div class="flex gap-5">
                            <div class="space-y-2">
                                <label for="is_active" class="block text-xs font-medium text-gray-600">
                                    Aktifkan LKM <span class="text-red-500">*</span>
                                </label>
                                <Switch class="mt-1" id="is_active" v-model="form.is_active" size="default" sm:size="lg" />
                                <small v-if="form.errors.is_active" class="text-red-500 text-xs block">
                                    {{ form.errors.is_active }}
                                </small>
                            </div>
                            <div class="space-y-2">
                                <label for="allow_late_submit" class="block text-xs font-medium text-gray-600">
                                    Submit Waktu Terlambat <span class="text-red-500">*</span>
                                </label>
                                <Switch class="mt-1" id="allow_late_submit" v-model="form.allow_late_submit" size="default" sm:size="lg" />
                                <small v-if="form.errors.allow_late_submit" class="text-red-500 text-xs block">
                                    {{ form.errors.allow_late_submit }}
                                </small>
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100" />

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button
                            type="button"
                            @click="cancel"
                            class="outline px-6 py-2 rounded-lg hover:bg-gray-100 text-gray-800 text-sm font-medium transition-colors shadow-sm"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2 rounded-lg bg-green-700 hover:bg-green-800 text-white text-sm font-medium transition-colors shadow-sm disabled:opacity-50"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>
