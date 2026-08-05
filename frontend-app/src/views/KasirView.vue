<template>
  <div class="min-h-screen bg-gray-50 flex font-sans">
    
    <!-- Sidebar Kasir -->
    <aside class="w-80 bg-white border-r border-gray-200 flex flex-col h-screen sticky top-0 shadow-sm">
      <div class="p-6 border-b border-gray-100 bg-primary/5 text-primary">
        <h1 class="text-2xl font-extrabold font-heading tracking-wider uppercase flex items-center gap-2">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
          POS Kasir
        </h1>
        <p class="text-xs font-bold uppercase tracking-widest mt-2 opacity-70">Live Order Dashboard</p>
      </div>
      
      <div class="p-6 flex-1 overflow-y-auto space-y-4">
        <div class="flex justify-between items-center">
          <h2 class="font-bold text-gray-700">Status Koneksi</h2>
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

    <!-- Main Content -->
    <main class="flex-1 p-8 h-screen overflow-y-auto">
      <div class="max-w-6xl mx-auto">
        
        <header class="flex justify-between items-end mb-8">
          <div class="flex-1">
            <h2 class="text-3xl font-extrabold text-gray-900">
              {{ currentTab === 'active' ? 'Pesanan Masuk' : 'Riwayat Selesai' }}
            </h2>
            <p class="text-gray-500 mt-1">
              {{ currentTab === 'active' ? 'Pesanan terbaru akan muncul di sini secara otomatis.' : 'Daftar pesanan yang sudah selesai dan lunas.' }}
            </p>
          </div>
          
          <div class="flex gap-4 items-center">
            <div class="bg-gray-100 p-1 rounded-xl flex gap-1 font-bold text-sm border border-gray-200">
              <button 
                @click="currentTab = 'active'"
                :class="currentTab === 'active' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2 rounded-lg transition-all"
              >
                Aktif
              </button>
              <button 
                @click="switchTab('history')"
                :class="currentTab === 'history' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-2 rounded-lg transition-all"
              >
                Riwayat
              </button>
            </div>
          </div>
          
          <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 flex gap-6 ml-6">
            <div class="text-center">
              <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                {{ currentTab === 'active' ? 'Total Aktif' : 'Total Riwayat' }}
              </p>
              <p class="text-2xl font-extrabold text-gray-900">
                {{ currentTab === 'active' ? orders.length : historyOrders.length }}
              </p>
            </div>
          </div>
        </header>

        <!-- Empty State -->
        <div v-if="(currentTab === 'active' && orders.length === 0) || (currentTab === 'history' && historyOrders.length === 0)" class="bg-white border-2 border-dashed border-gray-200 rounded-3xl p-16 flex flex-col items-center justify-center text-center">
          <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
          </svg>
          <h3 class="text-xl font-bold text-gray-900">Belum Ada Pesanan</h3>
          <p class="text-gray-500 mt-2 max-w-md">Menunggu pesanan masuk dari pelanggan... Pastikan koneksi web socket aktif.</p>
        </div>

        <!-- Orders Grid -->
        <div v-else class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
          <div v-for="order in (currentTab === 'active' ? orders : historyOrders)" :key="order.id" class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col relative overflow-hidden transition-all hover:shadow-md slide-in-top">
            
            <div v-if="currentTab === 'active'" class="absolute top-0 right-0 bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-bl-xl uppercase tracking-widest">
              Baru
            </div>

            <div class="flex justify-between items-start mb-4">
              <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Order #{{ order.id }}</span>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Meja {{ order.nomor_meja }}</h3>
                <p v-if="order.customer_name" class="text-sm font-bold text-[#7A4A3A] mt-1 flex items-center gap-1">
                  <span>👤</span> {{ order.customer_name }}
                  <span v-if="order.customer_email" class="text-xs text-gray-500 font-normal ml-1">({{ order.customer_email }})</span>
                </p>
              </div>
            </div>

            <div class="flex-1 bg-gray-50 rounded-2xl p-4 space-y-3 mb-6">
              <div v-for="item in order.items" :key="item.id" class="flex justify-between items-start">
                <div class="flex-1 pr-2">
                  <span class="font-bold text-gray-900 text-sm">{{ item.quantity }}x {{ item.menu?.name || 'Item' }}</span>
                  <div v-if="item.options && Object.keys(item.options).length > 0" class="mt-1 flex flex-wrap gap-1">
                    <span v-for="(value, key) in (typeof item.options === 'string' ? JSON.parse(item.options) : item.options)" :key="key" class="text-[10px] font-bold bg-gray-200 text-gray-600 px-2 py-0.5 rounded uppercase">
                      {{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}
                    </span>
                  </div>
                </div>
                <span class="font-semibold text-gray-600 text-sm">Rp {{ formatPrice(item.subtotal) }}</span>
              </div>
            </div>

            <div class="flex justify-between items-center mt-auto border-t border-gray-100 pt-4">
              <span class="text-xs font-bold uppercase" :class="currentTab === 'active' ? 'text-gray-500' : 'text-green-600'">
                {{ currentTab === 'active' ? 'Unpaid' : 'Paid' }}
              </span>
              <button 
                v-if="currentTab === 'active'"
                @click="markAsPaid(order.id)"
                class="bg-green-100 text-green-700 hover:bg-green-600 hover:text-white px-5 py-2 rounded-xl text-sm font-bold transition-colors"
              >
                Tandai Lunas
              </button>
            </div>
          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const currentTab = ref('active');
const orders = ref([]);
const historyOrders = ref([]);
const outletId = ref(1); // Default simulasi ID outlet 1
const isConnected = ref(false);
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
  try {
    const response = await axios.get(`http://localhost:8000/api/outlets/${outletId.value}/orders`);
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
});

onUnmounted(() => {
  if (echoInstance) {
    echoInstance.disconnect();
  }
});

const fetchHistoryOrders = async () => {
  try {
    const response = await axios.get(`http://localhost:8000/api/outlets/${outletId.value}/orders/history`);
    if (response.data.success) {
      historyOrders.value = response.data.data;
    }
  } catch (error) {
    console.error('Gagal mengambil data riwayat pesanan', error);
  }
};

const switchTab = (tab) => {
  currentTab.value = tab;
  if (tab === 'history') {
    fetchHistoryOrders();
  }
};

const markAsPaid = async (id) => {
  try {
    const response = await axios.post(`http://localhost:8000/api/orders/${id}/pay`);
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
