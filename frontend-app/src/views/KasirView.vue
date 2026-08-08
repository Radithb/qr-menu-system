<template>
  <div 
    class="min-h-screen flex font-sans bg-cover bg-center bg-no-repeat bg-fixed"
    :style="{ backgroundImage: `url(${bgDashboard})` }"
  >
    
    <!-- Sidebar Kasir -->
    <aside class="w-80 bg-[#E9D8C6]/95 border-r border-[#B98B6A]/30 flex flex-col h-screen sticky top-0 shadow-lg backdrop-blur-sm z-20">
      <div class="p-6 border-b border-[#B98B6A]/20 text-[#4B2E2A]">
        <h1 class="text-2xl font-extrabold font-heading tracking-wider uppercase flex items-center gap-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          POS Kasir
        </h1>
        <p class="text-xs font-bold uppercase tracking-widest mt-2 opacity-70">Live Order Dashboard</p>
      </div>
      
      <div class="p-6 flex-1 overflow-y-auto space-y-4">
        <div class="flex justify-between items-center">
          <h2 class="font-bold text-[#4B2E2A]">Status Koneksi</h2>
          <span class="flex items-center gap-2">
            <span class="relative flex h-3 w-3">
              <span :class="isConnected ? 'bg-green-400' : 'bg-red-400'" class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"></span>
              <span :class="isConnected ? 'bg-green-500' : 'bg-red-500'" class="relative inline-flex rounded-full h-3 w-3"></span>
            </span>
            <span class="text-xs font-bold" :class="isConnected ? 'text-green-600' : 'text-red-600'">
              {{ isConnected ? 'Terhubung' : 'Terputus' }}
            </span>
          </span>
        </div>
      </div>
    </aside>

    <!-- Right Panel Container -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10">
      
      <!-- Top Fixed Header Bar -->
      <header class="bg-[#E9D8C6] border-b border-[#B98B6A]/30 px-8 py-4 shadow-sm z-20 flex-shrink-0">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
          <div>
            <h2 class="text-2xl font-extrabold text-[#4B2E2A] drop-shadow-sm font-heading">
              {{ currentTab === 'active' ? 'Pesanan Masuk' : 'Riwayat Selesai' }}
            </h2>
            <p class="text-[#4B2E2A]/70 text-xs mt-0.5 font-medium">
              {{ currentTab === 'active' ? 'Pesanan terbaru akan muncul di sini secara otomatis.' : 'Daftar pesanan yang sudah selesai dan lunas.' }}
            </p>
          </div>
          
          <div class="flex gap-4 items-center">
            <div class="bg-[#F7F2EC] p-1 rounded-xl flex gap-1 font-bold text-sm border border-[#B98B6A]/30 shadow-sm">
              <button 
                @click="switchTab('active')"
                :class="currentTab === 'active' ? 'bg-[#4B2E2A] text-white shadow-md' : 'text-[#4B2E2A]/70 hover:text-[#4B2E2A]'"
                class="px-5 py-2 rounded-lg transition-all"
              >
                Aktif
              </button>
              <button 
                @click="switchTab('history')"
                :class="currentTab === 'history' ? 'bg-[#4B2E2A] text-white shadow-md' : 'text-[#4B2E2A]/70 hover:text-[#4B2E2A]'"
                class="px-5 py-2 rounded-lg transition-all"
              >
                Riwayat
              </button>
            </div>

            <button 
              @click="currentTab === 'active' ? fetchOrders() : fetchHistoryOrders()"
              class="p-2 bg-[#F7F2EC] hover:bg-[#E9D8C6] text-[#4B2E2A] rounded-xl font-bold transition-all shadow-sm border border-[#B98B6A]/30 flex items-center gap-1 text-sm"
              title="Refresh Pesanan"
            >
              <img src="@/assets/refresh.png" alt="Refresh" class="w-4 h-4 object-contain inline-block" /> <span class="hidden sm:inline">Refresh</span>
            </button>
            
            <div class="bg-[#F7F2EC] px-5 py-2 rounded-2xl shadow-sm border border-[#B98B6A]/30 flex gap-4">
              <div class="text-center">
                <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-widest">
                  {{ currentTab === 'active' ? 'Total Aktif' : 'Total Riwayat' }}
                </p>
                <p class="text-xl font-extrabold text-[#4B2E2A]">
                  {{ currentTab === 'active' ? orders.length : historyOrders.length }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Scrollable Main Content -->
      <main class="flex-1 p-8 overflow-y-auto">
        <div class="max-w-6xl mx-auto">
          
          <!-- Loading State -->
          <div v-if="isLoading" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <div v-for="i in 6" :key="'skel-'+i" class="bg-[#F7F2EC]/80 rounded-3xl p-6 shadow-sm border border-[#B98B6A]/20 flex flex-col min-h-[250px] animate-pulse">
              <div class="flex justify-between items-start mb-4">
                <div class="w-full">
                  <div class="h-4 bg-[#B98B6A]/30 rounded w-1/4 mb-2"></div>
                  <div class="h-6 bg-[#B98B6A]/30 rounded w-1/2 mb-2"></div>
                  <div class="h-4 bg-[#B98B6A]/20 rounded w-1/3"></div>
                </div>
              </div>
              <div class="flex-1 bg-[#E9D8C6]/50 rounded-2xl p-4 space-y-3 mb-6">
                <div v-for="j in 2" :key="'skel-item-'+j" class="flex justify-between">
                  <div class="h-4 bg-[#B98B6A]/20 rounded w-1/2"></div>
                  <div class="h-4 bg-[#B98B6A]/20 rounded w-1/4"></div>
                </div>
              </div>
              <div class="flex justify-between items-center mt-auto border-t border-[#B98B6A]/10 pt-4">
                <div class="h-4 bg-[#B98B6A]/30 rounded w-1/4"></div>
                <div class="h-8 bg-[#B98B6A]/30 rounded-xl w-24"></div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else-if="(currentTab === 'active' && orders.length === 0) || (currentTab === 'history' && historyOrders.length === 0)" class="bg-[#F7F2EC]/80 border-2 border-dashed border-[#B98B6A]/40 rounded-3xl p-16 flex flex-col items-center justify-center text-center backdrop-blur-sm shadow-sm">
            <svg class="w-16 h-16 text-[#B98B6A]/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            <h3 class="text-xl font-bold text-[#4B2E2A]">Belum Ada Pesanan</h3>
            <p class="text-[#7A4A3A] mt-2 max-w-md">Menunggu pesanan masuk dari pelanggan... Pastikan koneksi web socket aktif.</p>
          </div>

          <!-- Orders Grid -->
          <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <div v-for="order in (currentTab === 'active' ? orders : historyOrders)" :key="order.id" class="bg-[#F7F2EC]/95 rounded-3xl p-6 shadow-md border border-[#B98B6A]/30 flex flex-col relative overflow-hidden transition-all hover:shadow-lg slide-in-top backdrop-blur-sm">
              
              <div v-if="currentTab === 'active'" class="absolute top-0 right-0 bg-[#B98B6A] text-white text-xs font-bold px-3 py-1 rounded-bl-xl uppercase tracking-widest shadow-sm">
                Baru
              </div>

              <div class="flex justify-between items-start mb-4">
                <div>
                  <span class="text-xs font-bold text-[#7A4A3A] uppercase tracking-widest">Order #{{ order.id }}</span>
                  <h3 class="text-2xl font-extrabold text-[#4B2E2A] mt-1 font-heading">Meja {{ order.nomor_meja }}</h3>
                  <p v-if="order.customer_name" class="text-sm font-bold text-[#7A4A3A] mt-1 flex items-center gap-1.5">
                    <img src="@/assets/people.png" alt="Pelanggan" class="w-4 h-4 object-contain inline-block" /> {{ order.customer_name }}
                    <span v-if="order.customer_email" class="text-xs text-[#7A4A3A]/70 font-normal ml-1">({{ order.customer_email }})</span>
                  </p>
                </div>
              </div>

              <div class="flex-1 bg-[#E9D8C6]/50 rounded-2xl p-4 space-y-3 mb-6 border border-[#B98B6A]/10">
                <div v-for="item in order.items" :key="item.id" class="flex justify-between items-start">
                  <div class="flex-1 pr-2">
                    <span class="font-bold text-[#4B2E2A] text-sm">{{ item.quantity }}x {{ item.menu?.name || 'Item' }}</span>
                    <div v-if="item.options && Object.keys(item.options).length > 0" class="mt-1 flex flex-wrap gap-1">
                      <span v-for="(value, key) in (typeof item.options === 'string' ? JSON.parse(item.options) : item.options)" :key="key" class="text-[10px] font-bold bg-[#B98B6A]/20 text-[#7A4A3A] px-2 py-0.5 rounded uppercase">
                        {{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}
                      </span>
                    </div>
                  </div>
                  <span class="font-semibold text-[#7A4A3A] text-sm">Rp {{ formatPrice(item.subtotal) }}</span>
                </div>
              </div>

              <div class="flex justify-between items-center mt-auto border-t border-[#B98B6A]/20 pt-4">
                <span class="text-xs font-bold uppercase" :class="currentTab === 'active' ? 'text-[#7A4A3A]' : 'text-green-600'">
                  {{ currentTab === 'active' ? 'Unpaid' : 'Paid' }}
                </span>
                <button 
                  v-if="currentTab === 'active'"
                  @click="markAsPaid(order.id)"
                  class="bg-[#4B2E2A] text-white hover:bg-[#7A4A3A] px-5 py-2 rounded-xl text-sm font-bold transition-colors shadow-sm"
                >
                  Tandai Lunas
                </button>
              </div>
            </div>
          </div>

        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import bgDashboard from '@/assets/bgdashboard.jpg';

window.Pusher = Pusher;

const currentTab = ref('active');
const orders = ref([]);
const historyOrders = ref([]);
const outletId = ref('all'); // Ambil semua outlet agar semua pesanan pelanggan muncul di POS Kasir
const isConnected = ref(false);
const isLoading = ref(true);
let echoInstance = null;

const formatPrice = (price) => {
  return Number(price).toLocaleString('id-ID');
};

const connectWebSocket = () => {
  if (echoInstance) {
    echoInstance.leave(`outlet.${outletId.value}.orders`);
  }

  // Setup Echo Client
  echoInstance = new Echo({
    broadcaster: 'pusher',
    key: '12345', // Ganti dengan PUSHER_APP_KEY dari env
    cluster: 'ap1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
  });

  isConnected.value = true;

  echoInstance.channel(`outlet.${outletId.value}.orders`)
    .listen('.new-order', (e) => {
      console.log('Pesanan Baru Masuk!', e.order);
      // Mainkan notifikasi suara (opsional)
      playNotificationSound();
      
      // Tambahkan ke depan daftar
      orders.value.unshift(e.order);
    });
};

const fetchOrders = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/outlets/${outletId.value}/orders`);
    if (response.data.success) {
      orders.value = response.data.data;
    }
  } catch (error) {
    console.error('Gagal mengambil data pesanan', error);
  } finally {
    isLoading.value = false;
  }
};

let pollTimer = null;

const fetchOrdersSilently = async () => {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/outlets/${outletId.value}/orders`);
    if (response.data.success) {
      orders.value = response.data.data;
    }
  } catch (error) {
    console.error('Gagal mengambil data pesanan', error);
  }
};

onMounted(() => {
  fetchOrders();
  connectWebSocket();
  
  // Polling otomatis setiap 3 detik agar pesanan baru selalu otomatis muncul
  pollTimer = setInterval(() => {
    if (currentTab.value === 'active') {
      fetchOrdersSilently();
    }
  }, 3000);
});

onUnmounted(() => {
  if (pollTimer) clearInterval(pollTimer);
  if (echoInstance) {
    echoInstance.disconnect();
  }
});

const fetchHistoryOrders = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/outlets/${outletId.value}/orders/history`);
    if (response.data.success) {
      historyOrders.value = response.data.data;
    }
  } catch (error) {
    console.error('Gagal mengambil data riwayat pesanan', error);
  } finally {
    isLoading.value = false;
  }
};

const switchTab = (tab) => {
  currentTab.value = tab;
  if (tab === 'history') {
    fetchHistoryOrders();
  } else if (tab === 'active') {
    fetchOrders();
  }
};

const markAsPaid = async (id) => {
  try {
    const response = await axios.post(`http://127.0.0.1:8000/api/orders/${id}/pay`);
    if (response.data.success) {
      orders.value = orders.value.filter(o => o.id !== id);
      // Optional: add to history array so it updates immediately in frontend
      historyOrders.value.unshift(response.data.data);
    }
  } catch (error) {
    console.error('Gagal update status bayar', error);
    alert('Gagal update status pesanan!');
  }
};

const playNotificationSound = () => {
  try {
    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
    audio.play().catch(e => console.log('Audio autoplay prevented by browser'));
  } catch (e) {}
};
</script>

<style scoped>
@keyframes slideInTop {
  0% { transform: translateY(-20px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}
.slide-in-top {
  animation: slideInTop 0.4s cubic-bezier(0.25, 1, 0.5, 1) forwards;
}
</style>
