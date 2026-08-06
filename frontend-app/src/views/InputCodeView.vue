<template>
  <div 
  class="min-h-screen flex flex-col justify-center items-center px-4 font-sans relative overflow-x-hidden overflow-y-hidden bg-cover bg-no-repeat" 
  :style="{ 
    backgroundImage: `url(${bgImage})`,
    backgroundPosition: '50% 30%'  /* <--- [Sumbu X %] [Sumbu Y %] */
  }"
>


    <!-- Form & Main Card Container -->
    <form class="w-full max-w-[300px] flex flex-col items-center relative z-10" @submit.prevent="validateCode">
      
      <!-- Gradasi Layer (Behind Cardbox, in front of love.png) -->
      <div class="absolute -inset-48 translate-y-36 -z-10 flex justify-center items-center pointer-events-none">
        <img src="@/assets/gradasi.png" alt="Gradasi" class="w-full h-full object-contain opacity-90" />
      </div>

      <!-- Logo Image (Anchored to the top of the cardbox so it never overlaps) -->
      <div class="absolute bottom-full mb-0 left-1/2 -translate-x-1/2 w-56 sm:w-64 z-0 pointer-events-none">
        <img src="@/assets/logonga.png" alt="Logo" class="w-full h-auto object-contain drop-shadow-lg" />
      </div>
      
      <!-- Cardbox -->
      <div class="w-full space-y-5 bg-[#E9D8C6]/95 text-[#4B2E2A] p-6 rounded-3xl border border-white/60 shadow-xl backdrop-blur-sm">
        
        <!-- Header Section -->
        <div class="text-center space-y-1">
          <h2 class="text-xl font-extrabold font-heading tracking-widest uppercase">
            Selamat Datang!
          </h2>
          <p class="text-[10px] sm:text-xs font-semibold leading-snug px-1">
            Silahkan meminta bantuan pelayan<br>atau langsung input kode outlet
          </p>
        </div>
        
        <!-- Inputs Section -->
        <div class="space-y-3.5">
          
          <!-- Nama -->
          <div>
            <label for="nama_lengkap" class="sr-only">Nama</label>
            <input 
              id="nama_lengkap" 
              v-model="customerName"
              type="text" 
              class="appearance-none block w-full px-4 py-2.5 rounded-xl text-gray-900 bg-white/90 placeholder:text-gray-400 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-[#B98B6A] transition-all duration-300 shadow-sm"
              placeholder="Nama"
            >
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="sr-only">Email</label>
            <input 
              id="email" 
              v-model="customerEmail"
              type="email" 
              class="appearance-none block w-full px-4 py-2.5 rounded-xl text-gray-900 bg-white/90 placeholder:text-gray-400 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-[#B98B6A] transition-all duration-300 shadow-sm"
              placeholder="Email"
            >
            <p class="mt-1.5 text-[10px] font-bold text-[#A57C5A] px-1 tracking-wide">*Opsional</p>
          </div>

          <!-- Kode Outlet -->
          <div class="pt-2">
            <label for="kode_outlet" class="sr-only">Kode Outlet</label>
            <input 
              id="kode_outlet" 
              v-model="inputCode"
              type="text" 
              required 
              maxlength="6"
              class="appearance-none block w-full px-4 py-2.5 rounded-xl text-gray-900 bg-white border border-transparent placeholder:text-gray-400 text-xs font-semibold uppercase tracking-wide focus:outline-none focus:ring-1 focus:ring-[#B98B6A] transition-all duration-300 shadow-sm"
              :class="[
                isError ? 'border-red-500 ring-1 ring-red-500 animate-shake' : ''
              ]"
              placeholder="Masukan Kode Outlet"
              @input="handleInput"
            >
            <p v-if="isError" class="mt-1.5 text-center text-xs font-bold text-red-500 animate-pulse">
              {{ errorMessage }}
            </p>
            <p v-else class="mt-2 text-[10px] font-bold text-[#A57C5A] leading-tight px-1 text-center">
              Masukan kode yang berada di bawah barcode<br>meja anda
            </p>
          </div>

        </div>
      </div>
      
      <!-- Submit Button (Outside Card) -->
      <div class="mt-8 w-[85%] max-w-[240px] relative z-20">
        <button 
          type="submit" 
          :disabled="isLoading || inputCode.length < 3"
          class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-extrabold rounded-xl text-[#F7F2EC] bg-[#4B2E2A] hover:bg-[#3A221E] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4B2E2A] transition-all duration-200 active:scale-95 disabled:opacity-100 disabled:active:scale-100 disabled:cursor-not-allowed shadow-xl tracking-wider z-20"
        >
          <span v-if="isLoading" class="absolute left-0 inset-y-0 flex items-center pl-6">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </span>
          <span>{{ isLoading ? 'MEMVALIDASI...' : 'PESAN SEKARANG' }}</span>
        </button>
      </div>
    </form>
    
    <!-- Love Graphic (Placed behind the cardbox) -->
    <div class="absolute top-[55vh] sm:top-[50vh] left-1/2 -translate-x-1/2 w-[110%] max-w-[550px] z-0 flex justify-center pointer-events-none">
      <img src="@/assets/loveg2.png" alt="Love Graphic" class="w-full h-auto object-contain drop-shadow-sm opacity-90" />
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useOrderStore } from '@/stores/orderStore';
import axios from 'axios';
import bgImage from '@/assets/bgbaru.png';

const inputCode = ref('');
const isError = ref(false);
const errorMessage = ref('');
const isLoading = ref(false);

const showCustomerModal = ref(false);
const customerName = ref('');
const customerEmail = ref('');

const router = useRouter();
const route = useRoute();
const orderStore = useOrderStore();

onMounted(() => {
  if (route.query.meja) {
    orderStore.setNomorMeja(route.query.meja);
  }
  if (orderStore.customerName) {
    customerName.value = orderStore.customerName;
  }
  if (orderStore.customerEmail) {
    customerEmail.value = orderStore.customerEmail;
  }
});

const handleInput = (e) => {
  inputCode.value = e.target.value.toUpperCase();
  if (isError.value) {
    isError.value = false;
    errorMessage.value = '';
  }
};

const validateCode = async () => {
  if (!inputCode.value) return;
  
  isLoading.value = true;
  isError.value = false;
  
  try {
    const response = await axios.post('http://127.0.0.1:8000/api/outlet/validate', {
      kode_outlet: inputCode.value
    });
    
    if (response.data.success) {
      orderStore.setOutletData(inputCode.value, response.data.data);
      if (response.data.data.nomor_meja) {
        orderStore.setNomorMeja(response.data.data.nomor_meja);
      }
      router.push({ name: 'MenuKatalog' });
    }
  } catch (error) {
    isError.value = true;
    errorMessage.value = error.response?.data?.message || 'Gagal memvalidasi kode. Silakan periksa kembali.';
    
    setTimeout(() => {
      isError.value = false;
    }, 820);
  } finally {
    isLoading.value = false;
  }
};

const submitCustomerInfo = () => {
  if (!customerName.value || !customerEmail.value) return;
  orderStore.setCustomerData(customerName.value, customerEmail.value);
  showCustomerModal.value = false;
  router.push({ name: 'MenuKatalog' });
};
</script>

<style scoped>
@keyframes shake {
  10%, 90% { transform: translate3d(-1px, 0, 0); }
  20%, 80% { transform: translate3d(2px, 0, 0); }
  30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
  40%, 60% { transform: translate3d(4px, 0, 0); }
}

.animate-shake {
  animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
}
</style>
