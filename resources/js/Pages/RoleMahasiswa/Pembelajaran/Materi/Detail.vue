<script setup>
import { ref, onMounted } from 'vue';
import Layout from '../../../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote, NotepadText } from 'lucide-vue-next';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { Toast } from '@/lib/toast';

const props = defineProps({
    materi: Object,
})

const form = useForm({
    jawaban: props.materi?.essay_questions?.map(q => {
        const existingAnswer = q.answers && q.answers.length > 0 ? q.answers[0].jawaban : '';
        return {
            materi_essay_question_id: q.id,
            jawaban: existingAnswer
        };
    }) || []
});

const submitEssay = () => {
    form.post(`/mahasiswa/pembelajaran/materi/${props.materi.id}/essay`, {
        preserveScroll: true,
        onSuccess: () => {
            // Toast is handled by watch/onMounted if desired, but we can just use the page flash message.
        }
    });
};

const page = usePage();
onMounted(() => {
    if (page.props.flash?.success) {
        Toast.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: page.props.flash.success,
            timer: 2000,
            showConfirmButton: false,
        });
    }
});

const iframeRef = ref(null);

const toggleFullScreen = () => {
    if (iframeRef.value) {
        if (iframeRef.value.requestFullscreen) {
            iframeRef.value.requestFullscreen();
        } else if (iframeRef.value.webkitRequestFullscreen) { 
            iframeRef.value.webkitRequestFullscreen();
        } else if (iframeRef.value.msRequestFullscreen) {
            iframeRef.value.msRequestFullscreen();
        }
    }
};
</script>

<template>
    <Layout>
        <div class="grid grid-cols-1 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-gray-800 tracking-tight">Flipping Anatomi Tumbuhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-[80vh]">
            <!-- Left Panel: Flipbook -->
            <div class="lg:col-span-2 md:col-span-1 bg-white border border-gray-200 rounded-xl p-3 sm:p-4 h-[50vh] lg:h-full">
                <iframe 
                    ref="iframeRef"
                    :src="materi.link_flipping"
                    class="w-full h-full rounded-lg border-0 shadow-2xl"
                    allowfullscreen
                    allow="clipboard-write"
                ></iframe>
            </div>

            <!-- Right Panel: Info -->
            <div class="bg-white border border-gray-200 rounded-xl p-5 sm:p-6 flex flex-col h-full overflow-y-auto shadow-sm">

                <h2 class="text-gray-800 font-bold text-xl sm:text-2xl leading-tight mb-3 tracking-tight">
                    {{ materi.judul }}
                </h2>
                <div class="w-10 h-1 bg-green-600 rounded mb-6"></div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3 text-gray-600 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                            <Check class="w-4 h-4 text-green-600" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Tanggal</span>
                            <span>{{ materi.tanggal_rilis }}</span>
                        </div>
                    </div>
                </div>

                <p class="text-gray-400 text-sm leading-relaxed text-justify mb-8 flex-1">
                    {{ materi.deskripsi }}
                </p>

                <Button 
                    variant="outline" 
                    @click="toggleFullScreen"
                    class="w-full border-green-600 text-green-700 hover:bg-green-50 gap-2 h-11 rounded-xl"
                >
                    <ArrowRight class="w-4 h-4" />
                    Baca Layar Penuh
                </Button>
            </div>
        </div>

        <!-- Essay Section -->
        <div v-if="materi.essay_questions && materi.essay_questions.length > 0" class="mt-6 grid grid-cols-1 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5 sm:p-6 shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight mb-4 flex items-center gap-2">
                    <NotepadText class="w-5 h-5 text-green-600" />
                    Tugas Essay
                </h2>

                <form @submit.prevent="submitEssay" class="space-y-6">
                    <div v-for="(question, index) in materi.essay_questions" :key="question.id" class="border border-gray-100 rounded-xl overflow-hidden">
                        <!-- Question -->
                        <div class="bg-green-50/50 p-4 border-b border-gray-100 flex gap-3 items-start">
                            <span class="w-6 h-6 rounded-full bg-green-100 text-green-700 flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">{{ index + 1 }}</span>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800 font-medium whitespace-pre-line leading-relaxed">{{ question.pertanyaan }}</p>
                            </div>
                        </div>

                        <!-- Answer Input or Readonly -->
                        <div class="p-4 bg-white">
                            <div v-if="question.answers && question.answers.length > 0">
                                <div class="flex items-center gap-2 mb-2">
                                    <Check class="w-4 h-4 text-green-600" />
                                    <h3 class="font-semibold text-sm text-gray-800">Jawaban Anda:</h3>
                                </div>
                                <div class="bg-gray-50 border border-gray-100 p-3 rounded-lg">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ question.answers[0].jawaban }}</p>
                                </div>
                                <p class="text-[11px] text-gray-400 mt-2 italic">* Anda sudah mengumpulkan jawaban untuk soal ini.</p>
                            </div>
                            <div v-else>
                                <label :for="'jawaban_' + index" class="block text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Tulis Jawaban</label>
                                <textarea 
                                    :id="'jawaban_' + index"
                                    v-model="form.jawaban[index].jawaban" 
                                    rows="4"
                                    placeholder="Ketik jawaban Anda di sini..."
                                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-100 focus:border-green-500 transition-all"
                                    :disabled="form.processing"
                                ></textarea>
                                <small v-if="form.errors[`jawaban.${index}.jawaban`]" class="text-red-500 text-xs mt-1 block">{{ form.errors[`jawaban.${index}.jawaban`] }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2" v-if="materi.essay_questions.some(q => !q.answers || q.answers.length === 0)">
                        <Button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-6 py-2"
                        >
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Kirim Jawaban</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </Layout>
</template>

