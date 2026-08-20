<template>
  <div class="min-h-screen bg-background pb-48 font-sans relative">
    
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
      <!-- Skeleton Loading State -->
      <div v-if="isLoading" class="space-y-6 animate-pulse">
        <!-- Item List Skeleton -->
        <div class="bg-[#F7F2EC] rounded-3xl p-5 border border-[#B98B6A]/20 space-y-5">
          <div v-for="i in 3" :key="i" class="flex items-center justify-between pb-4 border-b border-[#B98B6A]/10 last:border-0 last:pb-0">
            <div class="flex-1 space-y-2 pr-4">
              <div class="h-4 bg-[#B98B6A]/20 rounded-md w-3/4"></div>
              <div class="h-3 bg-[#B98B6A]/15 rounded-md w-1/2"></div>
            </div>
            <div class="w-24 h-9 bg-[#B98B6A]/20 rounded-xl"></div>
          </div>
        </div>

        <!-- Summary Skeleton -->
        <div class="bg-[#F7F2EC] rounded-3xl p-5 border border-[#B98B6A]/20 space-y-3">
          <div class="h-5 bg-[#B98B6A]/20 rounded-md w-1/3 mb-4"></div>
          <div class="flex justify-between items-center">
            <div class="h-4 bg-[#B98B6A]/15 rounded-md w-24"></div>
            <div class="h-4 bg-[#B98B6A]/20 rounded-md w-8"></div>
          </div>
        </div>
      </div>

      <!-- Empty Cart State -->
      <div v-else-if="cartStore.items.length === 0" class="text-center py-16 bg-[#F7F2EC] rounded-3xl border border-[#B98B6A]/20 shadow-lg">
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
          <div class="flex justify-between items-center pb-2 border-b border-[#B98B6A]/20">
            <h3 class="font-bold text-[#4B2E2A] font-heading text-lg">Daftar Menu</h3>
            <span class="text-xs font-bold text-[#7A4A3A] bg-[#B98B6A]/20 px-2.5 py-1 rounded-full">Meja {{ orderStore.nomorMeja || '-' }}</span>
          </div>
          <div v-for="(item, index) in cartStore.items" :key="index" class="flex items-center justify-between pb-4 border-b border-[#B98B6A]/20 last:border-0 last:pb-0">
            <div class="flex-1 pr-4">
              <h4 class="font-bold text-[#4B2E2A] text-base font-heading">{{ item.name }}</h4>
              <div v-if="item.options && Object.keys(item.options).length > 0" class="mt-1 flex flex-wrap gap-1">
                <template 
                  v-for="(value, key) in item.options" 
                  :key="key" 
                >
                  <span 
                    v-if="hasOptionValue(value)"
                    :class="key.toLowerCase() === 'catatan' ? 'bg-amber-100 text-amber-900 border border-amber-300 font-bold' : 'bg-[#E9D8C6] text-[#7A4A3A] font-bold'"
                    class="text-[10px] px-2 py-0.5 rounded uppercase flex items-center gap-1"
                  >
                    <svg v-if="key.toLowerCase() === 'catatan'" class="w-3 h-3 text-amber-700 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>{{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}</span>
                  </span>
                </template>
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

        <!-- Pilihan Metode Pembayaran -->
        <div class="bg-[#F7F2EC] rounded-3xl p-5 shadow-md border border-[#B98B6A]/20 space-y-3">
          <h3 class="font-bold text-[#4B2E2A] font-heading text-lg border-b border-[#B98B6A]/20 pb-2">Metode Pembayaran</h3>
          
          <div class="space-y-2.5 pt-1">
            <!-- Option 1: QRIS -->
            <label 
              @click="paymentMethod = 'QRIS'"
              :class="paymentMethod === 'QRIS' ? 'border-[#4B2E2A] bg-white ring-2 ring-[#4B2E2A]/20' : 'border-[#B98B6A]/30 bg-[#E9D8C6]/40 hover:bg-[#E9D8C6]/80'"
              class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all duration-200"
            >
              <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#4B2E2A] text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                </div>
                <div>
                  <div class="flex items-center gap-2">
                    <span class="font-bold text-[#4B2E2A] text-sm">QRIS (GoPay / Semua E-Wallet)</span>
                    <span class="text-[10px] font-extrabold bg-green-100 text-green-800 px-2 py-0.5 rounded-md">Praktis</span>
                  </div>
                  <p class="text-[11px] text-[#7A4A3A] font-medium mt-0.5">Scan langsung pakai GoPay, BCA, Dana, OVO, ShopeePay</p>
                </div>
              </div>
              <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0" :class="paymentMethod === 'QRIS' ? 'border-[#4B2E2A] bg-[#4B2E2A]' : 'border-[#B98B6A]/50'">
                <div v-if="paymentMethod === 'QRIS'" class="w-2 h-2 rounded-full bg-white"></div>
              </div>
            </label>

            <!-- Option 2: Tunai di Kasir -->
            <label 
              @click="paymentMethod = 'Kasir / Tunai'"
              :class="paymentMethod === 'Kasir / Tunai' ? 'border-[#4B2E2A] bg-white ring-2 ring-[#4B2E2A]/20' : 'border-[#B98B6A]/30 bg-[#E9D8C6]/40 hover:bg-[#E9D8C6]/80'"
              class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition-all duration-200"
            >
              <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#E9D8C6] text-[#4B2E2A] flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0 border border-[#B98B6A]/30">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                  <span class="font-bold text-[#4B2E2A] text-sm">Bayar Tunai di Kasir</span>
                  <p class="text-[11px] text-[#7A4A3A] font-medium mt-0.5">Bayar langsung ke kasir setelah memesan</p>
                </div>
              </div>
              <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0" :class="paymentMethod === 'Kasir / Tunai' ? 'border-[#4B2E2A] bg-[#4B2E2A]' : 'border-[#B98B6A]/50'">
                <div v-if="paymentMethod === 'Kasir / Tunai'" class="w-2 h-2 rounded-full bg-white"></div>
              </div>
            </label>
          </div>
        </div>

        <!-- Summary -->
        <div class="bg-[#F7F2EC] rounded-3xl p-5 shadow-md border border-[#B98B6A]/20 space-y-3">
          <h3 class="font-bold text-[#4B2E2A] font-heading text-lg border-b border-[#B98B6A]/20 pb-2">Ringkasan Biaya</h3>
          <div class="flex justify-between items-center">
            <span class="text-[#4B2E2A]/70 font-medium">Total Item</span>
            <span class="font-bold text-[#4B2E2A]">{{ cartStore.totalItems }} menu</span>
          </div>
          <div class="flex justify-between items-center text-xl pt-2">
            <span class="font-bold text-[#4B2E2A]">Total Bayar</span>
            <span class="font-extrabold text-[#7A4A3A] font-heading text-2xl">Rp {{ formatPrice(cartStore.totalPrice) }}</span>
          </div>
        </div>
      </div>
    </main>

    <!-- Bottom Action Button -->
    <transition name="slide-up">
      <div v-if="!isLoading && cartStore.items.length > 0" class="fixed bottom-16 left-0 right-0 p-6 bg-[#E9D8C6]/90 backdrop-blur-lg border-t-2 border-[#B98B6A]/30 z-20">
        <button 
          @click="checkout"
          :disabled="isSubmitting"
          class="w-full bg-[#B98B6A] hover:bg-[#7A4A3A] text-white rounded-2xl py-5 px-6 flex justify-center items-center shadow-xl active:scale-[0.97] transition-all duration-200 disabled:opacity-50"
        >
          <span v-if="isSubmitting" class="mr-3">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </span>
          <span class="font-bold text-base tracking-widest uppercase">
            {{ isSubmitting ? 'MEMPROSES...' : (paymentMethod === 'QRIS' ? 'BAYAR SEKARANG (QRIS)' : 'PESAN SEKARANG') }}
          </span>
        </button>
      </div>
    </transition>

    <!-- =================================== -->
    <!-- MODAL PEMBAYARAN QRIS               -->
    <!-- =================================== -->
    <div 
      v-if="showQrisModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/65 backdrop-blur-sm overflow-y-auto"
    >
      <div class="bg-[#F7F2EC] rounded-3xl p-5 sm:p-6 max-w-md w-full shadow-2xl border border-[#B98B6A]/30 flex flex-col max-h-[94vh] my-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-3 border-b border-[#B98B6A]/20">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-[#4B2E2A] text-white flex items-center justify-center shadow-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <div>
              <h3 class="text-base font-extrabold text-[#4B2E2A] leading-tight font-heading">Pembayaran QRIS</h3>
              <p class="text-[10px] text-[#7A4A3A] font-medium">Kopi Ruang Hati</p>
            </div>
          </div>
          <button @click="showQrisModal = false" class="w-8 h-8 rounded-full bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] flex items-center justify-center font-bold text-sm transition-colors">✕</button>
        </div>

        <!-- QRIS Content -->
        <div class="flex-1 overflow-y-auto py-3 space-y-3.5 text-center">
          <!-- Total Bayar Box -->
          <div class="bg-white p-3.5 rounded-2xl border border-[#B98B6A]/20 shadow-sm flex items-center justify-between">
            <div class="text-left">
              <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider">Total Tagihan</p>
              <p class="text-2xl font-extrabold text-[#4B2E2A] font-heading leading-tight">Rp {{ formatPrice(lastOrderTotal) }}</p>
            </div>
            <button 
              @click="copyTotal" 
              class="px-3.5 py-2 rounded-xl bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm active:scale-95"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
              <span>{{ isCopied ? 'Tersalin!' : 'Salin' }}</span>
            </button>
          </div>

          <!-- Barcode QRIS Image Card (Enlarged & Prominent) -->
          <div class="bg-white p-3.5 sm:p-4 rounded-3xl border-2 border-[#B98B6A]/30 shadow-md mx-auto w-full max-w-[340px]">
            <img 
              :src="qrisImage" 
              alt="QRIS Kopi Ruang Hati" 
              class="w-full h-auto max-h-[380px] object-contain mx-auto rounded-2xl shadow-sm"
            />
            <p class="text-[10px] text-gray-500 mt-2 font-bold tracking-wider uppercase">NMID: KOPI RUANG HATI</p>
          </div>

          <!-- Petunjuk Singkat -->
          <div class="bg-[#E9D8C6]/50 rounded-xl p-3 text-left border border-[#B98B6A]/15 space-y-1 text-[11px] text-[#4B2E2A]">
            <p class="font-extrabold flex items-center gap-1.5 text-xs text-[#4B2E2A] mb-1.5">
              <svg class="w-4 h-4 text-[#7A4A3A] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
              </svg>
              <span>Cara Pembayaran:</span>
            </p>
            <p class="leading-relaxed">1. Buka aplikasi <strong>GoPay, BCA, Dana, OVO</strong>, atau m-Banking Anda.</p>
            <p class="leading-relaxed">2. Scan barcode QRIS di atas.</p>
            <p class="leading-relaxed">3. Masukkan nominal tepat <strong>Rp {{ formatPrice(lastOrderTotal) }}</strong>.</p>
            <p class="leading-relaxed">4. Tekan tombol <strong>"Saya Sudah Bayar"</strong> di bawah.</p>
          </div>
        </div>

        <!-- Modal Footer Actions -->
        <div class="pt-3 border-t border-[#B98B6A]/20 space-y-2">
          <button 
            @click="handlePaidConfirmation" 
            class="w-full bg-[#4B2E2A] hover:bg-[#7A4A3A] text-white py-3 px-4 rounded-xl font-bold text-sm shadow-md active:scale-95 transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
            <span>Saya Sudah Bayar</span>
          </button>
        </div>
      </div>
    </div>

    <!-- =================================== -->
    <!-- SUCCESS MODAL                       -->
    <!-- =================================== -->
    <div v-if="orderSuccess" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm">
      <div class="bg-[#F7F2EC] rounded-3xl p-8 max-w-sm w-full text-center space-y-6 shadow-2xl scale-in border border-[#B98B6A]/30">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto text-green-600 shadow-inner">
          <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div>
          <h2 class="text-2xl font-extrabold text-[#4B2E2A] font-heading">Pesanan Berhasil Dibuat!</h2>
          <p v-if="paymentMethod === 'QRIS'" class="text-[#7A4A3A] mt-2 font-medium text-sm leading-relaxed">
            Terima kasih! Kasir kami sedang memeriksa mutasi pembayaran QRIS Anda. Pesanan untuk <strong>Meja {{ orderStore.nomorMeja || '-' }}</strong> akan segera diproses setelah pembayaran terverifikasi.
          </p>
          <p v-else class="text-[#7A4A3A] mt-2 font-medium text-sm leading-relaxed">
            Pesanan Anda untuk <strong>Meja {{ orderStore.nomorMeja || '-' }}</strong> telah dicatat. Silakan menuju kasir untuk melakukan pembayaran tunai.
          </p>
        </div>
        <button 
          @click="finishOrder"
          class="w-full bg-[#4B2E2A] text-white hover:bg-[#7A4A3A] rounded-2xl py-4 font-bold active:scale-95 transition-all shadow-md"
        >
          Kembali ke Menu
        </button>
      </div>
    </div>

    <BottomNav />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useCartStore } from '@/stores/cartStore';
import { useOrderStore } from '@/stores/orderStore';
import axios from 'axios';
import BottomNav from '@/components/BottomNav.vue';
import qrisImage from '@/assets/QRIS.jpeg';

const router = useRouter();
const cartStore = useCartStore();
const orderStore = useOrderStore();

const isSubmitting = ref(false);
const orderSuccess = ref(false);
const isLoading = ref(true);

const hasOptionValue = (val) => {
  if (!val && val !== 0) return false;
  if (Array.isArray(val)) return val.length > 0;
  if (typeof val === 'string') return val.trim().length > 0;
  return true;
};

// Payment Method State
const paymentMethod = ref('QRIS'); // 'QRIS' | 'Kasir / Tunai'
const showQrisModal = ref(false);
const lastOrderTotal = ref(0);
const isCopied = ref(false);

onMounted(() => {
  if (!orderStore.outletData?.id) {
    router.push('/');
    return;
  }
  
  setTimeout(() => {
    isLoading.value = false;
  }, 400);
});

const formatPrice = (price) => {
  return Number(price || 0).toLocaleString('id-ID');
};

const copyTotal = () => {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(lastOrderTotal.value.toString());
    isCopied.value = true;
    setTimeout(() => {
      isCopied.value = false;
    }, 2000);
  }
};

const checkout = async () => {
  if (cartStore.items.length === 0) return;
  
  isSubmitting.value = true;
  lastOrderTotal.value = cartStore.totalPrice;
  
  try {
    const payload = {
      outlet_id: orderStore.outletData.id,
      nomor_meja: orderStore.nomorMeja || 'Takeaway',
      customer_name: orderStore.customerName || null,
      customer_email: orderStore.customerEmail || null,
      payment_method: paymentMethod.value,
      items: cartStore.items.map(item => ({
        menu_id: item.menu_id,
        quantity: item.quantity,
        subtotal: item.subtotal,
        options: item.options
      }))
    };
    
    const response = await axios.post('http://127.0.0.1:8000/api/orders', payload);
    
    if (response.data.success) {
      if (paymentMethod.value === 'QRIS') {
        // Open QRIS Modal
        showQrisModal.value = true;
      } else {
        // Tunai direct success
        orderSuccess.value = true;
        cartStore.clearCart();
      }
    }
  } catch (error) {
    console.error("Gagal melakukan pesanan", error.response?.data || error);
    const errorMsg = error.response?.data?.message || "Gagal melakukan pesanan. Silakan coba lagi.";
    alert(errorMsg);
  } finally {
    isSubmitting.value = false;
  }
};

const handlePaidConfirmation = () => {
  showQrisModal.value = false;
  orderSuccess.value = true;
  cartStore.clearCart();
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
