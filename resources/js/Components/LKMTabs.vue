<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const tabs = [
    {
        name: 'LKM Settings',
        href: '/admin/pembelajaran/lkm-grafting/settings',
    },
    {
        name: 'LKM Submission',
        href: '/admin/pembelajaran/lkm-grafting/submission',
    },
    {
        name: 'Penilaian',
        href: '/admin/pembelajaran/lkm-grafting/grading',
    },
]
</script>

<template>
    <div class="bg-white rounded-lg shadow-sm">
        <nav class="flex justify-center items-center gap-4 py-4 px-1 text-xs sm:text-[15px]">

            <Link
                v-for="tab in tabs"
                :key="tab.href"
                :href="tab.href"
                class="px-4 py-2 rounded-lg transition-all duration-200"
                :class="
                    page.url.startsWith(tab.href)
                        ? 'border border-green-600 bg-white text-green-600'
                        : 'text-gray-600 hover:text-green-600'
                "
            >
                {{ tab.name }}
            </Link>

        </nav>
    </div>
</template>