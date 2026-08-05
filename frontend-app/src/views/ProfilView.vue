<template>
  <div class="min-h-screen bg-background pb-32 font-sans relative">
    <!-- Header -->
    <header class="bg-[#E9D8C6] shadow-sm sticky top-0 z-10 border-b-2 border-[#B98B6A]/30">
      <div class="px-6 py-5 flex justify-between items-center">
        <div>
          <h1 class="text-2xl font-extrabold text-[#4B2E2A] font-heading uppercase tracking-wider">Profil Pemesan</h1>
          <p class="text-xs font-medium text-[#4B2E2A]/70 mt-0.5">Informasi sesi & identitas pemesan</p>
        </div>
      </div>
    </header>

    <main class="max-w-md mx-auto p-6 space-y-6">
      <!-- Skeleton Loading State -->
      <div v-if="isLoading" class="bg-[#F7F2EC] rounded-3xl p-6 border-2 border-[#B98B6A]/30 space-y-6 animate-pulse">
        <!-- Header Profile Skeleton -->
        <div class="flex items-center gap-4 border-b-2 border-[#B98B6A]/20 pb-5">
          <div class="w-16 h-16 bg-[#B98B6A]/20 rounded-2xl flex-shrink-0"></div>
          <div class="space-y-2 flex-1">
            <div class="h-5 bg-[#B98B6A]/20 rounded-md w-2/3"></div>
            <div class="h-3.5 bg-[#B98B6A]/15 rounded-md w-1/2"></div>
          </div>
        </div>

        <!-- Details Skeleton List -->
        <div class="space-y-4">
          <div v-for="i in 3" :key="i" class="p-4 bg-white/70 rounded-2xl border border-[#B98B6A]/20 flex items-center gap-3">
            <div class="w-6 h-6 bg-[#B98B6A]/20 rounded-md flex-shrink-0"></div>
            <div class="space-y-1.5 flex-1">
              <div class="h-3 bg-[#B98B6A]/15 rounded-md w-1/3"></div>
              <div class="h-4 bg-[#B98B6A]/20 rounded-md w-1/2"></div>
            </div>
          </div>
        </div>

        <!-- Button Skeleton -->
        <div class="h-12 bg-[#B98B6A]/20 rounded-2xl w-full"></div>
      </div>

      <!-- Profile Info Card -->
      <div v-else class="bg-[#F7F2EC] rounded-3xl p-6 shadow-md border-2 border-[#B98B6A]/30 space-y-6">
        <div class="flex items-center gap-4 border-b-2 border-[#B98B6A]/20 pb-5">
          <div class="w-16 h-16 bg-[#B98B6A]/20 rounded-2xl flex items-center justify-center p-3 border border-[#B98B6A]/30 shadow-sm flex-shrink-0">
            <img src="@/assets/people.png" alt="Profile" class="w-full h-full object-contain" />
          </div>
          <div>
            <h2 class="text-xl font-bold text-[#4B2E2A] leading-tight font-heading">
              {{ orderStore.customerName || 'Belum Mengisi Nama' }}
            </h2>
            <p class="text-sm font-semibold text-[#7A4A3A]/80 mt-0.5">
              {{ orderStore.customerEmail || 'Belum Mengisi Email' }}
            </p>
          </div>
        </div>

        <!-- Detail Information List -->
        <div class="space-y-4">
          <!-- Nomor Meja -->
          <div class="flex justify-between items-center p-4 bg-white/70 rounded-2xl border border-[#B98B6A]/20">
            <div class="flex items-center gap-3">
              <img src="@/assets/chair.png" alt="Nomor Meja" class="w-6 h-6 object-contain" />
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#7A4A3A]">Nomor Meja</p>
                <p class="text-base font-extrabold text-[#4B2E2A]">
                  {{ orderStore.nomorMeja ? `Meja ${orderStore.nomorMeja}` : 'Tidak terdeteksi' }}
                </p>
              </div>
            </div>
            <span v-if="orderStore.nomorMeja" class="px-3 py-1 bg-[#B98B6A]/20 text-[#7A4A3A] font-bold text-xs rounded-full">
              Aktif
            </span>
          </div>

          <!-- Nama Lengkap -->
          <div class="flex justify-between items-center p-4 bg-white/70 rounded-2xl border border-[#B98B6A]/20">
            <div class="flex items-center gap-3">
              <img src="@/assets/pen.png" alt="Nama Pemesan" class="w-6 h-6 object-contain" />
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#7A4A3A]">Nama Pemesan</p>
                <p class="text-base font-bold text-[#4B2E2A]">
                  {{ orderStore.customerName || '-' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Email -->
          <div class="flex justify-between items-center p-4 bg-white/70 rounded-2xl border border-[#B98B6A]/20">
            <div class="flex items-center gap-3">
              <img src="@/assets/mail.png" alt="Email" class="w-6 h-6 object-contain" />
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#7A4A3A]">Email</p>
                <p class="text-base font-bold text-[#4B2E2A]">
                  {{ orderStore.customerEmail || '-' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Action Button -->
        <button 
          @click="showEditModal = true"
          class="w-full py-4 bg-[#B98B6A] hover:bg-[#7A4A3A] text-white rounded-2xl font-bold uppercase tracking-wider shadow-lg active:scale-95 transition-all text-sm flex items-center justify-center gap-2"
        >
          Edit Data Pemesan
        </button>
      </div>
    </main>

    <!-- Edit Customer Info Modal -->
    <transition name="modal-fade">
      <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#4B2E2A]/60 backdrop-blur-sm">
        <div class="bg-[#F7F2EC] max-w-md w-full p-8 rounded-3xl shadow-2xl border-2 border-[#B98B6A]/30 space-y-6">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="text-xl font-extrabold text-[#4B2E2A] font-heading uppercase">Ubah Data Pemesan</h3>
              <p class="text-xs text-[#4B2E2A]/80 font-medium">Perbarui nama dan email Anda</p>
            </div>
            <button @click="showEditModal = false" class="text-[#7A4A3A] font-bold text-xl px-2">✕</button>
          </div>

          <form @submit.prevent="saveCustomerInfo" class="space-y-4">
            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-[#4B2E2A] mb-2">Nama Lengkap</label>
              <input 
                v-model="editName" 
                type="text" 
                required 
                placeholder="Contoh: Radith" 
                class="w-full px-5 py-3.5 rounded-xl border-2 border-[#B98B6A]/30 bg-white text-[#4B2E2A] font-bold focus:outline-none focus:border-[#B98B6A]"
              />
            </div>

            <div>
              <label class="block text-xs font-bold uppercase tracking-wider text-[#4B2E2A] mb-2">Email</label>
              <input 
                v-model="editEmail" 
                type="email" 
                required 
                placeholder="Contoh: radith@example.com" 
                class="w-full px-5 py-3.5 rounded-xl border-2 border-[#B98B6A]/30 bg-white text-[#4B2E2A] font-bold focus:outline-none focus:border-[#B98B6A]"
              />
            </div>

            <div class="flex gap-3 pt-2">
              <button 
                type="button" 
                @click="showEditModal = false"
                class="w-1/2 py-3.5 border-2 border-[#B98B6A]/40 text-[#7A4A3A] rounded-xl font-bold uppercase text-xs hover:bg-[#B98B6A]/10 transition-colors"
              >
                Batal
              </button>
              <button 
                type="submit" 
                class="w-1/2 py-3.5 bg-[#B98B6A] hover:bg-[#7A4A3A] text-white rounded-xl font-bold uppercase text-xs shadow-md transition-all"
              >
                Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </transition>

    <BottomNav />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useOrderStore } from '@/stores/orderStore';
import BottomNav from '@/components/BottomNav.vue';

const orderStore = useOrderStore();
const isLoading = ref(true);

onMounted(() => {
  setTimeout(() => {
    isLoading.value = false;
  }, 400);
});

const showEditModal = ref(false);
const editName = ref(orderStore.customerName || '');
const editEmail = ref(orderStore.customerEmail || '');

const saveCustomerInfo = () => {
  if (!editName.value || !editEmail.value) return;
  orderStore.setCustomerData(editName.value, editEmail.value);
  showEditModal.value = false;
};
</script>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
</style>
