<script setup>
import Layout from '../../App.vue';

const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

const recentSales = [
    { name: 'Olivia Martin',   email: 'olivia.martin@email.com',   amount: '+$1,999.00', color: 'linear-gradient(135deg,#6366f1,#8b5cf6)' },
    { name: 'Jackson Lee',     email: 'jackson.lee@email.com',      amount: '+$39.00',    color: 'linear-gradient(135deg,#f59e0b,#ef4444)' },
    { name: 'Isabella Nguyen', email: 'isabella.nguyen@email.com',  amount: '+$299.00',   color: 'linear-gradient(135deg,#10b981,#3b82f6)' },
    { name: 'William Kim',     email: 'will.kim@email.com',         amount: '+$99.00',    color: 'linear-gradient(135deg,#ec4899,#f43f5e)' },
]
</script>

<template>
    <Layout>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-white/40 mb-1">Nama</p>
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">$45,231.89</p>
                <p class="text-xs text-emerald-400 mt-1">+20.1% from last month</p>
            </div>
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-white/40 mb-1">Prodi</p>
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">+2350</p>
                <p class="text-xs text-emerald-400 mt-1">+180.1% from last month</p>
            </div>
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-white/40 mb-1">Jenis Kelamin</p>
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">+12,234</p>
                <p class="text-xs text-emerald-400 mt-1">+19% from last month</p>
            </div>
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-3 sm:p-4">
                <p class="text-xs text-white/40 mb-1">Kelas</p>
                <p class="text-lg sm:text-2xl font-bold text-white tracking-tight">+573</p>
                <p class="text-xs text-white/40 mt-1">+201 since last hour</p>
            </div>
        </div>

        <!-- Chart + Recent Sales — stacked on mobile, side-by-side on lg -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <h2 class="text-sm font-semibold text-white mb-4">Revenue Over Time</h2>
                <div class="relative h-40 sm:h-52">
                    <svg viewBox="0 0 600 200" class="w-full h-full" preserveAspectRatio="none">
                        <line x1="0" y1="40" x2="600" y2="40" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                        <line x1="0" y1="80" x2="600" y2="80" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                        <line x1="0" y1="120" x2="600" y2="120" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                        <line x1="0" y1="160" x2="600" y2="160" stroke="rgba(255,255,255,0.04)" stroke-width="1"/>
                        <defs>
                            <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="rgba(255,255,255,0.12)"/>
                                <stop offset="100%" stop-color="rgba(255,255,255,0)"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,160 C40,155 70,140 100,120 C130,100 145,130 180,110 C215,90 240,60 280,50 C320,40 345,80 380,70 C415,60 440,40 480,30 C520,20 560,35 600,25 L600,200 L0,200 Z" fill="url(#areaGrad)"/>
                        <path d="M0,160 C40,155 70,140 100,120 C130,100 145,130 180,110 C215,90 240,60 280,50 C320,40 345,80 380,70 C415,60 440,40 480,30 C520,20 560,35 600,25" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="1.5"/>
                    </svg>
                    <!-- Y-axis labels -->
                    <div class="absolute left-0 top-0 h-full flex flex-col justify-between pr-2 text-right">
                        <span class="text-[10px] text-white/25 leading-none">$800</span>
                        <span class="text-[10px] text-white/25 leading-none">$600</span>
                        <span class="text-[10px] text-white/25 leading-none">$400</span>
                        <span class="text-[10px] text-white/25 leading-none">$200</span>
                        <span class="text-[10px] text-white/25 leading-none">$0</span>
                    </div>
                </div>
                <!-- X-axis labels — show fewer on mobile -->
                <div class="flex justify-between mt-2 px-6">
                    <span
                        v-for="(m, i) in months"
                        :key="m"
                        :class="['text-[10px] text-white/25', { 'hidden sm:inline': i % 2 !== 0 }]"
                    >{{ m }}</span>
                </div>
            </div>

            <!-- Recent Sales -->
            <div class="bg-[#1a1a1a] border border-white/5 rounded-xl p-4 sm:p-5">
                <h2 class="text-sm font-semibold text-white mb-4">Recent Sales</h2>
                <div class="space-y-3 sm:space-y-4">
                    <div v-for="sale in recentSales" :key="sale.email" class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center text-xs font-semibold text-white"
                            :style="{ background: sale.color }"
                        >
                            {{ sale.name.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-white/90 truncate">{{ sale.name }}</p>
                            <p class="text-xs text-white/35 truncate">{{ sale.email }}</p>
                        </div>
                        <span class="text-sm font-semibold text-white shrink-0">{{ sale.amount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>

