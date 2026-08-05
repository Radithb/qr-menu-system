<template>
  <div class="min-h-screen bg-background flex flex-col justify-center items-center px-4 sm:px-6 font-sans relative">
    <div class="max-w-md w-full space-y-10 bg-white p-10 rounded-3xl shadow-xl border-4 border-primary/10">
      
      <!-- Header Section -->
      <div class="text-center space-y-4">
        <h2 class="text-4xl font-extrabold text-textColor font-heading tracking-wide uppercase">
          QR Cafe
        </h2>
        <p class="text-base text-textColor/70 leading-relaxed font-medium">
          Masukkan kode outlet yang tertera pada meja Anda untuk melihat menu.
        </p>
      </div>
      
      <!-- Form Section -->
      <form class="space-y-8" @submit.prevent="validateCode">
        <div>
          <label for="kode_outlet" class="sr-only">Kode Outlet</label>
          <input 
            id="kode_outlet" 
            v-model="inputCode"
            type="text" 
            required 
            maxlength="6"
            class="appearance-none relative block w-full px-6 py-6 border-4 rounded-2xl placeholder-textColor/30 text-textColor text-center text-4xl font-bold uppercase tracking-[0.25em] focus:outline-none transition-all duration-300 shadow-inner bg-background/50"
            :class="[
              isError 
                ? 'border-red-500 focus:ring-red-500 focus:border-red-500 animate-shake' 
                : 'border-primary/20 focus:border-primary focus:bg-white'
            ]"
            placeholder="S2LZ6B"
            @input="handleInput"
          >
          <p v-if="isError" class="mt-4 text-center text-sm font-bold text-red-600 animate-pulse">
            {{ errorMessage }}
          </p>
        </div>

        <!-- Submit Button -->
        <div>
          <button 
            type="submit" 
            :disabled="isLoading || inputCode.length < 3"
            class="group relative w-full flex justify-center py-5 px-4 border border-transparent text-xl font-bold rounded-2xl text-cta bg-primary hover:bg-secondary focus:outline-none focus:ring-4 focus:ring-secondary/50 transition-all duration-200 active:scale-95 disabled:opacity-50 disabled:active:scale-100 disabled:cursor-not-allowed shadow-lg"
          >
            <span v-if="isLoading" class="absolute left-0 inset-y-0 flex items-center pl-6">
              <!-- SVG Spinner -->
              <svg class="animate-spin h-6 w-6 text-cta" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            <span class="tracking-wider">{{ isLoading ? 'MEMVALIDASI...' : 'LIHAT MENU' }}</span>
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
