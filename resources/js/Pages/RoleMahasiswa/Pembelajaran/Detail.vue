<script setup>
import { ref } from 'vue';
import Layout from '../../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote } from 'lucide-vue-next';

const props = defineProps({
    materi: Object,
})

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
    </Layout>
</template>

