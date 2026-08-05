<template>
  <div class="min-h-screen bg-background pb-32 font-sans relative">
    
    <header class="bg-[#E9D8C6] shadow-sm sticky top-0 z-10 border-b-2 border-[#B98B6A]/30">
      <div class="px-6 py-5 flex items-center gap-4">
        <button @click="router.back()" class="p-2 -ml-2 rounded-full hover:bg-[#B98B6A]/10 text-[#4B2E2A] transition-colors">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <h1 class="text-2xl font-extrabold text-[#4B2E2A] tracking-tight font-heading uppercase">Pesanan Anda</h1>
      </div>
    </header>

    <main class="p-6 space-y-6">
      <div v-if="cartStore.items.length === 0" class="text-center py-16 bg-[#F7F2EC] rounded-3xl border border-[#B98B6A]/20 shadow-lg">
        <p class="text-[#4B2E2A]/70 text-base font-medium">Keranjang belanja Anda masih kosong.</p>
        <button 
          @click="router.push({name: 'MenuKatalog'})" 
          class="mt-6 bg-[#B98B6A] text-white px-6 py-3 rounded-xl font-bold active:scale-95 transition-transform"
        >
          Lihat Menu
        </button>
      </div>

      <div v-else class="space-y-6">
        <!-- Item List -->
        <div class="bg-[#F7F2EC] rounded-3xl p-5 shadow-md border border-[#B98B6A]/20 space-y-4">
          <div v-for="(item, index) in cartStore.items" :key="index" class="flex items-center justify-between pb-4 border-b border-[#B98B6A]/20 last:border-0 last:pb-0">
            <div class="flex-1 pr-4">
              <h3 class="font-bold text-[#4B2E2A] text-base font-heading">{{ item.name }}</h3>
              <div v-if="item.options && Object.keys(item.options).length > 0" class="mt-1 flex flex-wrap gap-1">
                <span v-for="(value, key) in item.options" :key="key" class="text-[10px] font-bold bg-[#E9D8C6] text-[#7A4A3A] px-2 py-0.5 rounded uppercase">
                  {{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}
                </span>
              </div>
              <p class="text-[#7A4A3A] font-bold mt-1 text-sm">Rp {{ formatPrice(item.price) }} / porsi</p>
            </div>
            
            <div class="flex items-center gap-3 bg-[#E9D8C6] rounded-xl p-1 border border-[#B98B6A]/30 flex-shrink-0">
              <button 
                @click="cartStore.updateQuantity(index, item.quantity - 1)"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#F7F2EC] text-[#4B2E2A] font-bold shadow-sm active:scale-90"
              >-</button>
              <span class="font-bold w-4 text-center text-[#4B2E2A]">{{ item.quantity }}</span>
              <button 
                @click="cartStore.updateQuantity(index, item.quantity + 1)"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#B98B6A] text-white font-bold shadow-sm active:scale-90"
              >+</button>
            </div>
          </div>
        </div>

        <!-- Summary -->
        <div class="bg-[#F7F2EC] rounded-3xl p-5 shadow-md border border-[#B98B6A]/20 space-y-3">
          <h3 class="font-bold text-[#4B2E2A] font-heading text-lg border-b border-[#B98B6A]/20 pb-2">Ringkasan</h3>
          <div class="flex justify-between items-center">
            <span class="text-[#4B2E2A]/70 font-medium">Total Item</span>
            <span class="font-bold text-[#4B2E2A]">{{ cartStore.totalItems }}</span>
          </div>
          <div class="flex justify-between items-center text-xl pt-2">
            <span class="font-bold text-[#4B2E2A]">Total Bayar</span>
            <span class="font-extrabold text-[#7A4A3A] font-heading text-2xl">Rp {{ formatPrice(cartStore.totalPrice) }}</span>
          </div>
        </div>
      </div>
    </main>

    <transition name="slide-up">
      <div v-if="cartStore.items.length > 0" class="fixed bottom-0 left-0 right-0 p-6 bg-[#E9D8C6]/90 backdrop-blur-lg border-t-2 border-[#B98B6A]/30 z-20">
        <button 
          @click="checkout"
          :disabled="isSubmitting"
          class="w-full bg-[#B98B6A] hover:bg-[#7A4A3A] text-white rounded-2xl py-5 px-6 flex justify-center items-center shadow-xl active:scale-[0.97] transition-all duration-200 disabled:opacity-50"
        >
          <span v-if="isSubmitting" class="mr-3">
            <svg class="animate-spin h-5 w-5 text-cta" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </span>
          <span class="font-bold text-lg tracking-widest uppercase">{{ isSubmitting ? 'MEMPROSES...' : 'PESAN SEKARANG' }}</span>
        </button>
      </div>
    </transition>

    <!-- Success Modal -->
    <div v-if="orderSuccess" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-textColor/50 backdrop-blur-sm">
      <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center space-y-6 shadow-2xl scale-in">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
          <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div>
          <h2 class="text-2xl font-extrabold text-textColor font-heading">Pesanan Berhasil!</h2>
          <p class="text-textColor/70 mt-2 font-medium">Pesanan Anda sedang disiapkan. Silakan menuju kasir untuk melakukan pembayaran.</p>
        </div>
        <button 
          @click="finishOrder"
          class="w-full bg-background border-2 border-primary text-primary hover:bg-primary hover:text-cta rounded-2xl py-4 font-bold active:scale-95 transition-all"
        >
          Selesai
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cartStore';
import { useOrderStore } from '@/stores/orderStore';
import axios from 'axios';

const router = useRouter();
const cartStore = useCartStore();
const orderStore = useOrderStore();

const isSubmitting = ref(false);
const orderSuccess = ref(false);

onMounted(() => {
  if (!orderStore.outletData?.id) {
    router.push('/');
  }
});

const formatPrice = (price) => {
  return Number(price).toLocaleString('id-ID');
};

const checkout = async () => {
  if (cartStore.items.length === 0) return;
  
  isSubmitting.value = true;
  
  try {
    const payload = {
      outlet_id: orderStore.outletData.id,
      nomor_meja: orderStore.nomorMeja || 'Takeaway',
      customer_name: orderStore.customerName || null,
      customer_email: orderStore.customerEmail || null,
      items: cartStore.items.map(item => ({
        menu_id: item.menu_id,
        quantity: item.quantity,
        subtotal: item.subtotal,
        options: item.options
      }))
    };
    
    const response = await axios.post('http://127.0.0.1:8000/api/orders', payload);
    
    if (response.data.success) {
      orderSuccess.value = true;
      cartStore.clearCart();
    }
  } catch (error) {
    console.error("Gagal melakukan pesanan", error.response?.data || error);
    const errorMsg = error.response?.data?.message || "Gagal melakukan pesanan. Silakan coba lagi.";
    alert(errorMsg);
  } finally {
    isSubmitting.value = false;
  }
};

const finishOrder = () => {
  orderSuccess.value = false;
  router.push({ name: 'MenuKatalog' });
};
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
}
.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100%);
  opacity: 0;
}

@keyframes scaleIn {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
.scale-in {
  animation: scaleIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
</style>
