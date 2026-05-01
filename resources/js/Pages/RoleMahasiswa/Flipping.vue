<script setup>
import { ref } from 'vue';
import Layout from '../../App.vue';
import { Button } from '@/components/ui/button';
import { Check, ArrowRight, StickyNote } from 'lucide-vue-next';

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
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">Flipping Anatomi Tumbuhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 h-[80vh]">
            <!-- Left Panel: Flipbook -->
            <div class="lg:col-span-2 md:col-span-1 bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4 h-[50vh] lg:h-full">
                <iframe 
                    ref="iframeRef"
                    src="https://heyzine.com/flip-book/2afbaafeaa162f52" 
                    class="w-full h-full rounded-lg border-0 shadow-2xl"
                    allowfullscreen
                    allow="clipboard-write"
                ></iframe>
            </div>

            <!-- Right Panel: Info -->
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-5 sm:p-6 flex flex-col h-full overflow-y-auto">

                <h2 class="text-white font-bold text-xl sm:text-2xl leading-tight mb-3 tracking-tight">
                    Anatomi Tumbuhan
                </h2>
                <div class="w-10 h-1 bg-emerald-500 rounded mb-6"></div>

                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3 text-white/70 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                            <StickyNote class="w-4 h-4 text-emerald-400" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-white/30 uppercase font-bold tracking-wider">Edisi</span>
                            <span>Volume 1, Nomor 1</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-white/70 text-sm">
                        <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                            <Check class="w-4 h-4 text-emerald-400" />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] text-white/30 uppercase font-bold tracking-wider">Tanggal</span>
                            <span>Januari 2026</span>
                        </div>
                    </div>
                </div>

                <p class="text-gray-400 text-sm leading-relaxed text-justify mb-8 flex-1">
                    Modul ini dirancang untuk memberikan pemahaman komprehensif mengenai struktur internal tumbuhan, 
                    prinsip-prinsip dasar proses fisiologis, serta aplikasinya dalam berbagai aspek botani. 
                    Materi disajikan secara interaktif dengan ilustrasi, diagram, dan contoh-contoh nyata.
                </p>

                <Button 
                    variant="outline" 
                    @click="toggleFullScreen"
                    class="w-full border-emerald-500/50 text-emerald-400 hover:bg-emerald-500/10 gap-2 h-11 rounded-xl"
                >
                    <ArrowRight class="w-4 h-4" />
                    Baca Layar Penuh
                </Button>
            </div>
        </div>
    </Layout>
</template>

