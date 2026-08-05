<template>
  <div class="min-h-screen bg-background flex flex-col justify-center items-center px-4 font-sans relative overflow-x-hidden">
    
    <!-- Logo Image (Absolute floating, lowered closer to cardbox) -->
    <div class="absolute top-[14vh] sm:top-[16vh] left-1/2 -translate-x-1/2 w-40 sm:w-48 z-0">
      <img src="@/assets/logo-persegi.png" alt="Logo" class="w-full h-auto object-contain drop-shadow-lg" />
    </div>

    <!-- Main Card (Position untouched, centered in viewport) -->
    <div class="w-full max-w-[280px] space-y-4 bg-[#E9D8C6] text-[#4B2E2A] p-5 rounded-2xl border-2 border-[#4B2E2A] shadow-[6px_6px_0px_#4B2E2A] relative z-10">
      
      <!-- Header Section -->
      <div class="text-center space-y-1.5">
        <h2 class="text-2xl font-extrabold font-heading tracking-wide">
          Selamat Datang
        </h2>
        <p class="text-xs font-medium leading-relaxed opacity-90 px-1">
          Silahkan meminta bantuan pelayan atau langsung input kode outlet
        </p>
      </div>
      
      <!-- Form Section -->
      <form class="space-y-3.5" @submit.prevent="validateCode">
        <div>
          <label for="kode_outlet" class="sr-only">Kode Outlet</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
              <!-- Store Icon -->
              <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V10C19 8.89543 18.1046 8 17 8H7C5.89543 8 5 8.89543 5 10V21M3 21H21M9 21V16C9 15.4477 9.44772 15 10 15H14C14.5523 15 15 15.4477 15 16V21M17 3H7C5.89543 3 5 3.89543 5 5V6H19V5C19 3.89543 18.1046 3 17 3Z"></path>
              </svg>
            </div>
            <input 
              id="kode_outlet" 
              v-model="inputCode"
              type="text" 
              required 
              maxlength="6"
              class="appearance-none block w-full pl-10 pr-3 py-2.5 border-2 rounded-xl text-gray-900 bg-white placeholder-gray-400 text-sm font-bold uppercase tracking-widest focus:outline-none transition-all duration-300"
              :class="[
                isError 
                  ? 'border-red-500 focus:ring-red-500 animate-shake' 
                  : 'border-transparent focus:border-[#B98B6A]'
              ]"
              placeholder="Masukan kode outlet"
              @input="handleInput"
            >
          </div>
          <p v-if="isError" class="mt-1.5 text-center text-xs font-bold text-red-500 animate-pulse">
            {{ errorMessage }}
          </p>
        </div>

        <!-- Submit Button -->
        <div>
          <button 
            type="submit" 
            :disabled="isLoading || inputCode.length < 3"
            class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-bold rounded-xl text-[#F7F2EC] bg-[#4B2E2A] hover:bg-[#7A4A3A] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#4B2E2A] transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:active:scale-100 disabled:cursor-not-allowed shadow-md"
          >
            <span v-if="isLoading" class="absolute left-0 inset-y-0 flex items-center pl-6">
              <!-- SVG Spinner -->
              <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            <span class="tracking-wide">{{ isLoading ? 'MEMVALIDASI...' : 'Simpan' }}</span>
          </button>
        </div>
      </form>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useOrderStore } from '@/stores/orderStore';
import axios from 'axios';

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
