<template>
  <nav class="fixed bottom-0 left-0 right-0 bg-[#E9D8C6] border-t-2 border-[#B98B6A]/30 pb-safe z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
    <div class="flex justify-around items-center h-16">
      
      <!-- Home -->
      <button 
        @click="navigate('home')" 
        class="flex flex-col items-center justify-center w-full h-full gap-1 transition-all"
        :class="activeTab === 'home' ? 'text-[#4B2E2A] font-extrabold scale-105' : 'text-[#7A4A3A]/60 hover:text-[#7A4A3A] font-medium'"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="activeTab === 'home' ? 'stroke-[2.5]' : 'stroke-[1.8]'">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="text-[10px] uppercase tracking-wider">Home</span>
      </button>

      <!-- Pesanan Saya -->
      <button 
        @click="navigate('orders')" 
        class="flex flex-col items-center justify-center w-full h-full gap-1 transition-all relative"
        :class="activeTab === 'orders' ? 'text-[#4B2E2A] font-extrabold scale-105' : 'text-[#7A4A3A]/60 hover:text-[#7A4A3A] font-medium'"
      >
        <!-- Notification badge if there's an order -->
        <span v-if="hasOrders" class="absolute top-1 right-[25%] min-w-[16px] h-4 px-1 bg-red-500 rounded-full border border-[#E9D8C6] flex items-center justify-center text-[9px] font-bold text-white z-10 shadow-sm">
          {{ cartStore.totalItems }}
        </span>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="activeTab === 'orders' ? 'stroke-[2.5]' : 'stroke-[1.8]'">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="text-[10px] uppercase tracking-wider">Pesanan Saya</span>
      </button>

      <!-- Profil -->
      <button 
        @click="navigate('profile')" 
        class="flex flex-col items-center justify-center w-full h-full gap-1 transition-all"
        :class="activeTab === 'profile' ? 'text-[#4B2E2A] font-extrabold scale-105' : 'text-[#7A4A3A]/60 hover:text-[#7A4A3A] font-medium'"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="activeTab === 'profile' ? 'stroke-[2.5]' : 'stroke-[1.8]'">
          <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span class="text-[10px] uppercase tracking-wider">Profil</span>
      </button>

    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useCartStore } from '@/stores/cartStore';

const router = useRouter();
const route = useRoute();
const cartStore = useCartStore();

const activeTab = computed(() => {
  if (route.name === 'Checkout' || route.path.includes('/cart') || route.path.includes('/pesanan')) return 'orders';
  if (route.name === 'Profil' || route.path.includes('/profil')) return 'profile';
  return 'home';
});

const hasOrders = computed(() => cartStore.totalItems > 0);

const navigate = (tab) => {
  if (tab === 'home') {
    if (route.name !== 'MenuKatalog') router.push({ name: 'MenuKatalog' });
  } else if (tab === 'orders') {
    if (route.name !== 'Checkout') router.push({ name: 'Checkout' });
  } else if (tab === 'profile') {
    if (route.name !== 'Profil') router.push({ name: 'Profil' });
  }
};
</script>

<style scoped>
.pb-safe {
  padding-bottom: env(safe-area-inset-bottom);
}
</style>
