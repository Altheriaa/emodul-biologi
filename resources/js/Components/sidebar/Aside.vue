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
    return urls.filter(url => url && currentUrl.startsWith(url)).length > 0;
};

const dropdowns = ref({
    informasiModul: isUrl('/mahasiswa/informasi-modul'),
    pembelajaran: isUrl('/mahasiswa/pembelajaran'),
});

const toggleDropdown = (key) => {
    dropdowns.value[key] = !dropdowns.value[key];
};

const navigation = computed(() => {
    const role = user.value?.role;

    if (role === 'admin') {
        return [
            { label: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
            { label: 'Kelola User', href: '/admin/users', icon: Users },
            { label: 'Pengaturan', href: '/admin/settings', icon: Settings },
        ];
    }

    if (role === 'dosen') {
        return [
            { label: 'Dashboard', href: '/dosen/dashboard', icon: LayoutDashboard },
            { label: 'Kelola Materi', href: '/dosen/materi', icon: BookOpen },
            { label: 'Monitoring Mahasiswa', href: '/dosen/monitoring', icon: Users },
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
                    { label: 'Materi', href: '/mahasiswa/pembelajaran/materi' },
                    { label: 'LKM-Grafting', href: '#' },
                ]
            },
        ];
    }

    return [];
});
</script>

<template>
    <!-- Mobile Overlay -->
    <div
        v-if="show"
        class="fixed inset-0 bg-black/60 z-20 lg:hidden"
        @click="emit('close')"
    />

    <!-- Sidebar -->
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-30 w-64 bg-[#171717] flex flex-col shrink-0 border-r border-white/5 transition-transform duration-300 ease-in-out',
            'lg:relative lg:translate-x-0 lg:w-56 lg:z-auto',
            show ? 'translate-x-0' : '-translate-x-full'
        ]"
    >
        <!-- Brand -->
        <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
            <span class="font-semibold text-sm tracking-wide text-white/90">E-Modul Biologi</span>
            <!-- Close button (mobile only) -->
            <button
                class="lg:hidden p-1 rounded text-white/40 hover:text-white hover:bg-white/10 transition-colors"
                @click="emit('close')"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto custom-scrollbar">
            <template v-for="item in navigation" :key="item.label">
                <!-- Simple Link -->
                <Link 
                    v-if="!item.children"
                    :href="item.href" 
                    :class="[
                        isUrl(item.href) ? 'bg-white/10 text-white' : 'text-white/50 hover:text-white hover:bg-white/5', 
                        'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors'
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
                            isUrl(item.href, ...(item.children?.map(c => c.href) || [])) ? 'text-white' : 'text-white/50 hover:text-white hover:bg-white/5',
                            'w-full flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium transition-colors group'
                        ]"
                    >
                        <div class="flex items-center gap-3">
                            <component :is="item.icon" class="w-4 h-4 shrink-0" />
                            {{ item.label }}
                        </div>
                        <svg
                            :class="['w-3.5 h-3.5 text-white/20 group-hover:text-white/40 transition-transform duration-200', dropdowns[item.id] ? 'rotate-180' : '']"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        >
                            <path d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div v-show="dropdowns[item.id]" class="mt-1 ml-4 pl-3 border-l border-white/5 space-y-0.5">
                        <Link 
                            v-for="child in item.children" 
                            :key="child.label"
                            :href="child.href" 
                            :class="[
                                isUrl(child.href) ? 'text-white bg-white/5' : 'text-white/40 hover:text-white hover:bg-white/5', 
                                'block px-3 py-2 rounded-md text-sm transition-colors'
                            ]"
                        >
                            {{ child.label }}
                        </Link>
                    </div>
                </div>
            </template>
        </nav>

        <!-- User Profile -->
        <div class="px-3 py-3 border-t border-white/5">
            <Link href="/logout" method="get" as="button" class="w-full flex items-center gap-3 px-2 py-2 rounded-md hover:bg-white/5 cursor-pointer transition-colors group">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-xs font-semibold shrink-0">
                    {{ user?.name?.charAt(0) || 'U' }}
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-xs font-medium text-white/90 truncate">{{ user?.name }}</p>
                    <p class="text-xs text-white/40 truncate">{{ user?.email }}</p>
                </div>
                <LogOut class="w-4 h-4 text-white group-hover:text-white transition-colors shrink-0" />
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
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.1);
}
</style>
