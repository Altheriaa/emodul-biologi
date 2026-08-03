<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { 
    LayoutDashboard, 
    Info, 
    BookOpen,
    Users,
    Settings,
    LogOut,
    ChevronDown,
    NotepadText,
    GraduationCap
} from 'lucide-vue-next';

const props = defineProps({
    show: Boolean
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const emit = defineEmits(['close']);

const isUrl = (...urls) => {
    let currentUrl = page.url.split('?')[0];
    if (urls[0] === '/') {
        return currentUrl === '/';
    }
    return urls.some(url => url && (currentUrl === url || currentUrl.startsWith(url + '/')));
};

// Dropdown biar ga nutup pas ditekan
const dropdowns = ref({
    informasiModul: isUrl('/mahasiswa/informasi-modul', '/admin/informasi-modul', '/dosen/informasi-modul'),
    pembelajaran: isUrl('/mahasiswa/pembelajaran', '/admin/pembelajaran', '/dosen/pembelajaran/'),
    evaluasi: isUrl('/mahasiswa/evaluasi', '/admin/evaluasi', '/dosen/evaluasi'),
    kelolaPengguna: isUrl('/admin/mahasiswa', '/admin/dosen', '/admin/mahasiswa-eligible'),
});

const toggleDropdown = (key) => {
    dropdowns.value[key] = !dropdowns.value[key];
};

const navigation = computed(() => {
    const role = user.value?.role;

    if (role === 'admin') {
        return [
            { label: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
            { 
                label: 'Informasi Modul', 
                icon: Info,
                id: 'informasiModul',
                children: [
                    { label: 'Identitas Modul', href: '/admin/informasi-modul/identitas-modul' },
                    { label: 'CPL & CPMK', href: '/admin/informasi-modul/cpl-cpmk' },
                ]
            },
            { 
                label: 'Pembelajaran', 
                icon: BookOpen,
                id: 'pembelajaran',
                children: [
                    { label: 'Materi Pembelajaran', href: '/admin/pembelajaran/materi' },
                    { label: 'LKM-<i>Grafting</i>', href: '/admin/pembelajaran/lkm-grafting' },
                    { label: 'Video <i>Grafting</i>', href: 'https://youtu.be/mkKjlcoUSW8?si=Ko0wUBkBeVwdBK8v' },
                    { label: 'Format Laporan', href: '/asset/Template Laporan Praktikum Grafting Tumbuhan.docx', download: true },
                ]
            },
            { 
                label: 'Evaluasi', 
                icon: NotepadText,
                id: 'evaluasi',
                children: [
                    { label: 'Bank Soal', href: '/admin/evaluasi/bank-soal' },
                    { label: 'Hasil Quiz', href: '/admin/evaluasi/monitoring' },
                ]
            },
            { 
                label: 'Kelola Pengguna', 
                icon: Users,
                id: 'kelolaPengguna',
                children: [
                    { label: 'Mahasiswa', href: '/admin/mahasiswa' },
                    { label: 'Dosen', href: '/admin/dosen' },
                    { label: 'Master Mahasiswa Eligible', href: '/admin/mahasiswa-eligible' },
                ]
            },
            { label: 'Settings', href: '/admin/settings', icon: Settings },
        ];
    }

    if (role === 'dosen') {
        return [
            { label: 'Dashboard', href: '/dosen/dashboard', icon: LayoutDashboard },
            { 
                label: 'Informasi Modul', 
                icon: Info,
                id: 'informasiModul',
                children: [
                    { label: 'Identitas Modul', href: '/dosen/informasi-modul/identitas-modul' },
                    { label: 'CPL & CPMK', href: '/dosen/informasi-modul/cpl-cpmk' },
                ]
            },
            { 
                label: 'Pembelajaran', 
                icon: BookOpen,
                id: 'pembelajaran',
                children: [
                    { label: 'Materi Pembelajaran', href: '/dosen/pembelajaran/materi' },
                    { label: 'LKM-<i>Grafting</i>', href: '/dosen/pembelajaran/lkm-grafting/submissions' },
                    { label: 'Video <i>Grafting</i>', href: 'https://youtu.be/mkKjlcoUSW8?si=Ko0wUBkBeVwdBK8v' },
                    { label: 'Format Laporan', href: '/asset/Template Laporan Praktikum Grafting Tumbuhan.docx', download: true },
                ]
            },
            { 
                label: 'Evaluasi', 
                icon: NotepadText,
                id: 'evaluasi',
                children: [
                    { label: 'Bank Soal', href: '/dosen/evaluasi/bank-soal' },
                    { label: 'Hasil Quiz', href: '/dosen/evaluasi/monitoring' },
                ]
            },
            { label: 'Settings', href: '/dosen/settings', icon: Settings },

        ];
    }

    if (role === 'mahasiswa') {
        return [
            { label: 'Dashboard', href: '/mahasiswa/dashboard', icon: LayoutDashboard },
            { 
                label: 'Informasi Modul', 
                icon: Info,
                id: 'informasiModul',
                children: [
                    { label: 'Identitas Modul', href: '/mahasiswa/informasi-modul/identitas-modul' },
                    { label: 'CPL & CPMK', href: '/mahasiswa/informasi-modul/cpl-cpmk' },
                ]
            },
            { 
                label: 'Pembelajaran', 
                icon: BookOpen,
                id: 'pembelajaran',
                children: [
                    { label: 'Materi Pembelajaran', href: '/mahasiswa/pembelajaran/materi' },
                    { label: 'LKM-<i>Grafting</i>', href: '/mahasiswa/pembelajaran/lkm-grafting' },
                    { label: 'Video <i>Grafting</i>', href: 'https://youtu.be/mkKjlcoUSW8?si=Ko0wUBkBeVwdBK8v' },
                    { label: 'Format Laporan', href: '/asset/Template Laporan Praktikum Grafting Tumbuhan.docx', download: true },
                ]
            },
            { 
                label: 'Evaluasi', 
                icon: NotepadText,
                id: 'evaluasi',
                children: [
                    { label: 'Soal Evaluasi Akhir', href: '/mahasiswa/evaluasi/quiz' },
                ]
            },
            { label: 'Settings', href: '/mahasiswa/settings', icon: Settings },
        ];
    }

    return [];
});
</script>

<template>
    <!-- Mobile Overlay -->
    <div
        v-if="show"
        class="fixed inset-0 bg-black/40 z-20 lg:hidden"
        @click="emit('close')"
    />

    <!-- Sidebar -->
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-30 w-64 bg-white flex flex-col shrink-0 border-r border-gray-200 transition-transform duration-300 ease-in-out shadow-sm',
            'lg:relative lg:translate-x-0 lg:w-56 lg:z-auto',
            show ? 'translate-x-0' : '-translate-x-full'
        ]"
    >
        <!-- Brand -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 shrink-0">
                    <img src="/asset/Logo Pals.png" alt="Logo Pals" class="h-8 w-auto">
                    <img src="/asset/Logo Unaya.png" alt="Logo Unaya" class="h-8 w-auto">
                </div>
                <span class="font-semibold text-sm tracking-wide text-gray-800">E-PALS</span>
            </div>
            <!-- Close button (mobile only) -->
            <button
                class="lg:hidden p-1 rounded text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                @click="emit('close')"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-3 space-y-0.5 overflow-y-auto custom-scrollbar">
            <template v-for="item in navigation" :key="item.label">
                <!-- Simple Link -->
                <Link 
                    v-if="!item.children"
                    :href="item.href" 
                    :class="[
                        isUrl(item.href)
                            ? 'bg-green-700 text-white shadow-sm'
                            : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100', 
                        'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all'
                    ]"
                >
                    <component :is="item.icon" class="w-4 h-4 shrink-0" />
                    {{ item.label }}
                </Link>

                <!-- Dropdown -->
                <div v-else>
                    <button
                        @click="toggleDropdown(item.id)"
                        :class="[
                            isUrl(item.href, ...(item.children?.map(c => c.href) || []))
                                ? 'text-green-700 bg-green-50'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100',
                            'w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all group'
                        ]"
                    >
                        <div class="flex items-center gap-3">
                            <component :is="item.icon" class="w-4 h-4 shrink-0" />
                            {{ item.label }}
                        </div>
                        <ChevronDown
                            :class="['w-3.5 h-3.5 text-gray-400 transition-transform duration-200', dropdowns[item.id] ? 'rotate-180' : '']"
                        />
                    </button>
                    <div v-show="dropdowns[item.id]" class="mt-0.5 ml-5 pl-3 border-l-1 border-gray-200 space-y-0.5">
                        <template v-for="child in item.children" :key="child.label">
                            <a
                                v-if="child.href.startsWith('http') || child.download"
                                :href="child.href"
                                :target="child.download ? undefined : '_blank'"
                                :download="child.download ? '' : undefined"
                                rel="noopener noreferrer"
                                class="block px-3 py-2 rounded-md text-sm transition-colors whitespace-normal break-words leading-snug text-gray-500 hover:text-gray-800 hover:bg-gray-50"
                            >
                                <span v-html="child.label"></span>
                            </a>
                            <Link 
                                v-else
                                :href="child.href" 
                                :class="[
                                    isUrl(child.href)
                                        ? 'text-green-700 bg-green-50 font-medium'
                                        : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50', 
                                    'block px-3 py-2 rounded-md text-sm transition-colors whitespace-normal break-words leading-snug'
                                ]"
                            >
                                <span v-html="child.label"></span>
                            </Link>
                        </template>
                    </div>
                </div>
            </template>
        </nav>

        <!-- User Profile -->
        <div class="px-2 py-3 border-t border-gray-100">
            <Link href="/logout" method="post" as="button" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-100 cursor-pointer transition-colors group">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-green-600 to-emerald-700 flex items-center justify-center text-xs font-semibold text-white shrink-0">
                    {{ user?.name?.charAt(0) || 'U' }}
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-xs font-medium text-gray-800 truncate">{{ user?.name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ user?.email }}</p>
                </div>
                <LogOut class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors shrink-0" />
            </Link>
        </div>

    </aside>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.08);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.15);
}
</style>
