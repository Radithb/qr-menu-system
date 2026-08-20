<template>
  <div 
    class="min-h-screen flex font-sans bg-cover bg-center bg-no-repeat bg-fixed"
    :style="{ backgroundImage: `url(${bgDashboard})` }"
  >
    
    <!-- Floating Toast Notification -->
    <transition name="toast-slide">
      <div 
        v-if="toastMessage" 
        class="fixed top-6 right-6 z-50 bg-[#4B2E2A] text-white px-5 py-3.5 rounded-2xl shadow-2xl border border-[#B98B6A]/40 flex items-center gap-3.5 backdrop-blur-md no-print"
      >
        <div class="w-9 h-9 rounded-xl bg-green-500/20 text-green-400 flex items-center justify-center flex-shrink-0 font-bold border border-green-500/30">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
          <p class="text-xs font-bold text-white leading-tight">{{ toastMessage.title }}</p>
          <p class="text-[11px] text-[#E9D8C6]/80 font-medium mt-0.5">{{ toastMessage.text }}</p>
        </div>
      </div>
    </transition>

    <!-- Sidebar Kasir -->
    <aside class="w-80 bg-[#E9D8C6]/95 border-r border-[#B98B6A]/30 flex flex-col h-screen sticky top-0 shadow-lg backdrop-blur-sm z-20 no-print">
      <div class="px-6 py-5 border-b border-[#B98B6A]/20 flex items-center justify-center">
        <img 
          src="@/assets/logo-panjang.png" 
          alt="Kopi Ruang Hati" 
          class="w-full max-w-[145px] h-auto object-contain mix-blend-multiply" 
        />
      </div>
      
      <div class="p-6 flex-1 overflow-y-auto space-y-5">
        <!-- Sidebar Navigation -->
        <div class="space-y-1.5">
          <p class="text-[10px] font-bold uppercase tracking-widest text-[#7A4A3A] mb-2">Navigasi</p>
          <button 
            @click="sidebarPage = 'orders'"
            :class="sidebarPage === 'orders' ? 'bg-[#4B2E2A] text-white shadow-md' : 'bg-[#F7F2EC] text-[#4B2E2A] hover:bg-[#E9D8C6]'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Pesanan Masuk
          </button>
          <button 
            @click="switchToMenuManagement()"
            :class="sidebarPage === 'menu-management' ? 'bg-[#4B2E2A] text-white shadow-md' : 'bg-[#F7F2EC] text-[#4B2E2A] hover:bg-[#E9D8C6]'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Kelola Menu
          </button>
          <button 
            @click="switchToTransactionHistory()"
            :class="sidebarPage === 'transaction-history' ? 'bg-[#4B2E2A] text-white shadow-md' : 'bg-[#F7F2EC] text-[#4B2E2A] hover:bg-[#E9D8C6]'"
            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Riwayat Transaksi
          </button>
        </div>
      </div>
    </aside>

    <!-- Right Panel Container -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative z-10">

      <!-- ======================== -->
      <!-- PAGE: PESANAN (ORDERS) -->
      <!-- ======================== -->
      <template v-if="sidebarPage === 'orders'">
        <!-- Top Fixed Header Bar -->
        <header class="bg-[#E9D8C6] border-b border-[#B98B6A]/30 px-8 py-4 shadow-sm z-20 flex-shrink-0 no-print">
          <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-extrabold text-[#4B2E2A] drop-shadow-sm font-heading">
                {{ currentTab === 'active' ? 'Pesanan Masuk' : 'Riwayat Selesai' }}
              </h2>
              <p class="text-[#4B2E2A]/70 text-xs mt-0.5 font-medium">
                {{ currentTab === 'active' ? 'Pesanan terbaru dari semua meja akan muncul di sini secara otomatis.' : 'Daftar pesanan yang sudah selesai dan lunas.' }}
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
        <main class="flex-1 p-8 overflow-y-auto no-print">
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
              <div 
                v-for="order in (currentTab === 'active' ? orders : historyOrders)" 
                :key="order.id" 
                :class="successOrderIds.includes(order.id) ? 'ring-4 ring-green-500 scale-[0.98] bg-green-50/95 transition-all duration-300' : 'bg-[#F7F2EC]/95 transition-all'"
                class="rounded-3xl p-6 shadow-md border border-[#B98B6A]/30 flex flex-col relative overflow-hidden hover:shadow-lg slide-in-top backdrop-blur-sm"
              >
                
                <div v-if="currentTab === 'active'" class="absolute top-0 right-0 bg-[#B98B6A] text-white text-xs font-bold px-3 py-1 rounded-bl-xl uppercase tracking-widest shadow-sm">
                  Baru
                </div>
                <div v-else class="absolute top-0 right-0 bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-bl-xl uppercase tracking-widest shadow-sm">
                  Selesai
                </div>

                <div class="flex justify-between items-start mb-4">
                  <div>
                    <div class="flex items-center gap-2">
                      <span class="text-xs font-bold text-[#7A4A3A] uppercase tracking-widest">Order #{{ order.id }}</span>
                      <span class="text-[11px] text-[#7A4A3A]/70 font-medium">• {{ formatTime(order.created_at) }}</span>
                    </div>
                    <h3 class="text-2xl font-extrabold text-[#4B2E2A] mt-1 font-heading">Meja {{ order.nomor_meja }}</h3>
                    <p class="text-sm font-bold text-[#7A4A3A] mt-1 flex items-center gap-1.5">
                      <img src="@/assets/people.png" alt="Pelanggan" class="w-4 h-4 object-contain inline-block" /> 
                      <span>{{ order.customer_name || 'Pelanggan' }}</span>
                      <span v-if="order.customer_email" class="text-xs text-[#7A4A3A]/70 font-normal ml-1">({{ order.customer_email }})</span>
                    </p>
                  </div>
                </div>

                <!-- Item List -->
                <div class="flex-1 bg-[#E9D8C6]/50 rounded-2xl p-4 space-y-3 mb-4 border border-[#B98B6A]/10">
                  <div v-for="item in order.items" :key="item.id" class="flex justify-between items-start">
                    <div class="flex-1 pr-2">
                      <span class="font-bold text-[#4B2E2A] text-sm">{{ item.quantity }}x {{ item.menu?.name || 'Item' }}</span>
                      <div v-if="item.options && Object.keys(item.options).length > 0" class="mt-1.5 flex flex-wrap gap-1">
                        <span 
                          v-for="(value, key) in (typeof item.options === 'string' ? JSON.parse(item.options) : item.options)" 
                          :key="key" 
                          :class="key.toLowerCase() === 'catatan' ? 'bg-amber-100 text-amber-900 border border-amber-300 font-bold' : 'bg-[#B98B6A]/20 text-[#7A4A3A] font-bold'"
                          class="text-[10px] px-2 py-0.5 rounded uppercase flex items-center gap-1"
                        >
                          <svg v-if="key.toLowerCase() === 'catatan'" class="w-3 h-3 text-amber-700 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                          <span>{{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}</span>
                        </span>
                      </div>
                    </div>
                    <span class="font-semibold text-[#7A4A3A] text-sm">Rp {{ formatPrice(item.subtotal) }}</span>
                  </div>
                </div>

                <!-- Total Card Summary -->
                <div class="flex justify-between items-center px-1 mb-4">
                  <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-xs font-bold uppercase tracking-wider px-2 py-0.5 rounded" :class="currentTab === 'active' ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800'">
                      {{ currentTab === 'active' ? 'Unpaid' : 'Paid' }}
                    </span>
                    <span 
                      class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded flex items-center gap-1"
                      :class="order.payment_method === 'QRIS' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-[#4B2E2A]/10 text-[#4B2E2A]'"
                    >
                      <svg v-if="order.payment_method === 'QRIS'" class="w-3 h-3 text-blue-600 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                      {{ order.payment_method || 'QRIS' }}
                    </span>
                  </div>
                  <div class="text-right">
                    <p class="text-[10px] text-[#7A4A3A] font-bold uppercase">Total</p>
                    <p class="text-lg font-extrabold text-[#4B2E2A]">Rp {{ formatPrice(calculateOrderTotal(order)) }}</p>
                  </div>
                </div>

                <!-- Card Action Buttons -->
                <div class="flex items-center gap-2 border-t border-[#B98B6A]/20 pt-4">
                  <button 
                    @click="openReceiptModal(order)"
                    class="flex-1 py-2.5 px-3 rounded-xl border border-[#B98B6A]/40 bg-[#F7F2EC] hover:bg-[#E9D8C6] text-[#4B2E2A] text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-sm active:scale-95"
                    title="Pratinjau & Cetak Struk"
                  >
                    <svg class="w-4 h-4 text-[#7A4A3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Struk</span>
                  </button>
                  <button 
                    v-if="currentTab === 'active'"
                    @click="markAsPaid(order)"
                    :disabled="processingOrderIds.includes(order.id) || successOrderIds.includes(order.id)"
                    :class="successOrderIds.includes(order.id) 
                      ? 'bg-green-600 text-white shadow-md' 
                      : processingOrderIds.includes(order.id) 
                        ? 'bg-[#4B2E2A]/70 text-white/80 cursor-wait' 
                        : 'bg-[#4B2E2A] text-white hover:bg-[#7A4A3A] active:scale-95 shadow-sm'"
                    class="flex-1 py-2.5 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                  >
                    <template v-if="processingOrderIds.includes(order.id)">
                      <svg class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                      <span>Memproses...</span>
                    </template>
                    <template v-else-if="successOrderIds.includes(order.id)">
                      <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                      </svg>
                      <span>✓ Berhasil Lunas!</span>
                    </template>
                    <template v-else>
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                      </svg>
                      <span>Tandai Lunas</span>
                    </template>
                  </button>
                </div>

              </div>
            </div>

          </div>
        </main>
      </template>

      <!-- ================================== -->
      <!-- PAGE: RIWAYAT TRANSAKSI (HISTORY)  -->
      <!-- ================================== -->
      <template v-if="sidebarPage === 'transaction-history'">
        <!-- Header -->
        <header class="bg-[#E9D8C6] border-b border-[#B98B6A]/30 px-8 py-4 shadow-sm z-20 flex-shrink-0 no-print">
          <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-extrabold text-[#4B2E2A] drop-shadow-sm font-heading">Riwayat Transaksi</h2>
              <p class="text-[#4B2E2A]/70 text-xs mt-0.5 font-medium">Detail seluruh transaksi yang telah selesai</p>
            </div>
            <button 
              @click="fetchTransactionHistory()"
              class="p-2 bg-[#F7F2EC] hover:bg-[#E9D8C6] text-[#4B2E2A] rounded-xl font-bold transition-all shadow-sm border border-[#B98B6A]/30 flex items-center gap-1 text-sm"
              title="Refresh"
            >
              <img src="@/assets/refresh.png" alt="Refresh" class="w-4 h-4 object-contain inline-block" /> <span class="hidden sm:inline">Refresh</span>
            </button>
          </div>
        </header>

        <main class="flex-1 overflow-y-auto no-print">
          <div class="max-w-7xl mx-auto px-8 py-6 space-y-6">

            <!-- Filter Bar -->
            <div class="bg-[#F7F2EC]/95 rounded-2xl p-5 border border-[#B98B6A]/20 shadow-sm backdrop-blur-sm">
              <!-- Period Navigator -->
              <div class="flex flex-col sm:flex-row gap-4 mb-4 border-b border-[#B98B6A]/20 pb-4">
                <div class="flex items-center gap-2">
                  <label class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider">Rentang Waktu:</label>
                  <div class="relative">
                    <select 
                      v-model="periodMode" 
                      @change="onPeriodModeChange"
                      class="bg-white border border-[#B98B6A]/30 rounded-xl pl-3 pr-8 py-1.5 text-xs font-bold text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 appearance-none cursor-pointer"
                    >
                      <option value="daily">Harian</option>
                      <option value="weekly">Mingguan</option>
                      <option value="monthly">Bulanan</option>
                      <option value="custom">Kustom</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-[#7A4A3A]">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>
                
                <div v-if="periodMode !== 'custom'" class="flex items-center bg-white border border-[#B98B6A]/30 rounded-xl overflow-hidden shadow-sm">
                  <button @click="shiftPeriod(-1)" class="px-3 py-1.5 hover:bg-[#E9D8C6] transition-colors border-r border-[#B98B6A]/30 text-[#4B2E2A]" title="Sebelumnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                  </button>
                  <div class="px-4 py-1.5 text-xs font-bold text-[#4B2E2A] min-w-[150px] text-center bg-[#F7F2EC]">
                    {{ periodLabel }}
                  </div>
                  <button @click="shiftPeriod(1)" class="px-3 py-1.5 hover:bg-[#E9D8C6] transition-colors border-l border-[#B98B6A]/30 text-[#4B2E2A]" title="Selanjutnya">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                  </button>
                </div>
              </div>

              <div class="flex flex-wrap gap-4 items-end">
                <!-- Date From -->
                <div v-if="periodMode === 'custom'" class="flex-1 min-w-[160px]">
                  <label class="block text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                  <input 
                    v-model="txFilter.dateFrom" 
                    type="date" 
                    class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-3 py-2 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50"
                  />
                </div>
                <!-- Date To -->
                <div v-if="periodMode === 'custom'" class="flex-1 min-w-[160px]">
                  <label class="block text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                  <input 
                    v-model="txFilter.dateTo" 
                    type="date" 
                    class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-3 py-2 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50"
                  />
                </div>
                <!-- Payment Method -->
                <div class="flex-1 min-w-[140px]">
                  <label class="block text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider mb-1.5">Metode Bayar</label>
                  <div class="relative">
                    <select 
                      v-model="txFilter.paymentMethod" 
                      class="w-full bg-white border border-[#B98B6A]/30 rounded-xl pl-3 pr-8 py-2 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 appearance-none cursor-pointer"
                    >
                      <option value="">Semua Metode</option>
                      <option value="QRIS">QRIS</option>
                      <option value="Kasir / Tunai">Tunai</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-[#7A4A3A]">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                      </svg>
                    </div>
                  </div>
                </div>
                <!-- Search -->
                <div class="flex-[2] min-w-[200px]">
                  <label class="block text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider mb-1.5">Cari</label>
                  <input 
                    v-model="txFilter.search" 
                    type="text" 
                    placeholder="Cari nama, no. order, atau meja..." 
                    class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-3 py-2 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 placeholder-[#B98B6A]/50"
                  />
                </div>
                <!-- Apply / Reset Buttons -->
                <div class="flex gap-2">
                  <button 
                    @click="fetchTransactionHistory()" 
                    class="bg-[#4B2E2A] text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-[#7A4A3A] transition-all shadow-sm active:scale-95"
                  >
                    Terapkan
                  </button>
                  <button 
                    @click="resetTxFilter()" 
                    class="bg-white border border-[#B98B6A]/30 text-[#4B2E2A] px-4 py-2 rounded-xl font-bold text-sm hover:bg-[#E9D8C6] transition-all"
                  >
                    Reset
                  </button>
                </div>
              </div>
            </div>

            <!-- Summary Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="bg-[#F7F2EC]/95 rounded-2xl p-5 border border-[#B98B6A]/20 shadow-sm backdrop-blur-sm">
                <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-2xl font-extrabold text-[#4B2E2A] mt-1">Rp {{ formatPrice(txSummary.total_revenue) }}</p>
              </div>
              <div class="bg-[#F7F2EC]/95 rounded-2xl p-5 border border-[#B98B6A]/20 shadow-sm backdrop-blur-sm">
                <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider">Jumlah Transaksi</p>
                <p class="text-2xl font-extrabold text-[#4B2E2A] mt-1">{{ txSummary.total_orders }}</p>
              </div>
              <div class="bg-[#F7F2EC]/95 rounded-2xl p-5 border border-[#B98B6A]/20 shadow-sm backdrop-blur-sm">
                <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider">Total Item Terjual</p>
                <p class="text-2xl font-extrabold text-[#4B2E2A] mt-1">{{ txSummary.total_items }}</p>
              </div>
            </div>

            <!-- Loading -->
            <div v-if="isTxLoading" class="text-center py-16">
              <div class="inline-flex items-center gap-3 bg-[#F7F2EC] px-6 py-3 rounded-2xl border border-[#B98B6A]/20 shadow-sm">
                <svg class="w-5 h-5 animate-spin text-[#B98B6A]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-sm font-bold text-[#4B2E2A]">Memuat data riwayat...</span>
              </div>
            </div>

            <!-- Empty -->
            <div v-else-if="txOrders.length === 0" class="bg-[#F7F2EC]/80 border-2 border-dashed border-[#B98B6A]/40 rounded-3xl p-16 flex flex-col items-center justify-center text-center backdrop-blur-sm shadow-sm">
              <svg class="w-16 h-16 text-[#B98B6A]/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              <h3 class="text-xl font-bold text-[#4B2E2A]">Belum Ada Riwayat</h3>
              <p class="text-[#7A4A3A] mt-2 max-w-md">Tidak ditemukan transaksi selesai untuk filter yang dipilih.</p>
            </div>

            <!-- Transaction Table -->
            <div v-else class="bg-[#F7F2EC]/95 rounded-2xl border border-[#B98B6A]/20 shadow-sm backdrop-blur-sm overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="bg-[#4B2E2A] text-white">
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">No. Order</th>
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">Hari / Tanggal</th>
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">Waktu</th>
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">Meja</th>
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">Pelanggan</th>
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">Metode</th>
                      <th class="text-left px-4 py-3 font-bold text-xs uppercase tracking-wider">Item</th>
                      <th class="text-right px-4 py-3 font-bold text-xs uppercase tracking-wider">Total</th>
                      <th class="text-center px-4 py-3 font-bold text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="order in txOrders" :key="order.id">
                      <tr 
                        class="border-b border-[#B98B6A]/10 hover:bg-[#E9D8C6]/40 transition-colors cursor-pointer"
                        @click="toggleTxExpand(order.id)"
                      >
                        <td class="px-4 py-3 font-bold text-[#4B2E2A]">#{{ order.id }}</td>
                        <td class="px-4 py-3 text-[#4B2E2A]">
                          <div class="font-semibold">{{ formatDayName(order.created_at) }}</div>
                          <div class="text-[11px] text-[#7A4A3A]/70">{{ formatDateFull(order.created_at) }}</div>
                        </td>
                        <td class="px-4 py-3 text-[#4B2E2A] font-medium">{{ formatTime(order.created_at) }}</td>
                        <td class="px-4 py-3">
                          <span class="bg-[#4B2E2A]/10 text-[#4B2E2A] font-bold text-xs px-2.5 py-1 rounded-lg">{{ order.nomor_meja }}</span>
                        </td>
                        <td class="px-4 py-3">
                          <div class="font-semibold text-[#4B2E2A]">{{ order.customer_name || 'Pelanggan' }}</div>
                          <div v-if="order.customer_email" class="text-[11px] text-[#7A4A3A]/70">{{ order.customer_email }}</div>
                        </td>
                        <td class="px-4 py-3">
                          <span 
                            class="text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg inline-flex items-center gap-1"
                            :class="order.payment_method === 'QRIS' ? 'bg-blue-100 text-blue-800' : 'bg-[#4B2E2A]/10 text-[#4B2E2A]'"
                          >
                            {{ order.payment_method || 'QRIS' }}
                          </span>
                        </td>
                        <td class="px-4 py-3 text-[#4B2E2A] font-medium">{{ calculateTotalQty(order) }} item</td>
                        <td class="px-4 py-3 text-right font-extrabold text-[#4B2E2A]">Rp {{ formatPrice(calculateOrderTotal(order)) }}</td>
                        <td class="px-4 py-3 text-center">
                          <div class="flex items-center justify-center gap-1">
                            <button 
                              @click.stop="openReceiptModal(order)" 
                              class="p-1.5 rounded-lg bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] transition-colors" 
                              title="Cetak Struk"
                            >
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            </button>
                            <button 
                              @click.stop="toggleTxExpand(order.id)" 
                              class="p-1.5 rounded-lg bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] transition-all" 
                              :class="{ 'rotate-180': txExpandedId === order.id }"
                              title="Detail"
                            >
                              <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                          </div>
                        </td>
                      </tr>
                      <!-- Expanded Detail Row -->
                      <tr v-if="txExpandedId === order.id">
                        <td colspan="9" class="px-6 py-4 bg-[#E9D8C6]/30 border-b border-[#B98B6A]/10">
                          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Item Details -->
                            <div>
                              <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider mb-2">Detail Item Pesanan</p>
                              <div class="bg-white rounded-xl p-3 space-y-2 border border-[#B98B6A]/10">
                                <div v-for="item in order.items" :key="item.id" class="flex justify-between items-start text-sm">
                                  <div class="flex-1 pr-3">
                                    <span class="font-bold text-[#4B2E2A]">{{ item.quantity }}x {{ item.menu?.name || 'Item' }}</span>
                                    <div v-if="item.options && Object.keys(typeof item.options === 'string' ? JSON.parse(item.options) : item.options).length > 0" class="flex flex-wrap gap-1 mt-1.5">
                                      <span 
                                        v-for="(value, key) in (typeof item.options === 'string' ? JSON.parse(item.options) : item.options)" 
                                        :key="key" 
                                        :class="key.toLowerCase() === 'catatan' ? 'bg-amber-100 text-amber-900 border border-amber-300 font-bold' : 'bg-[#B98B6A]/15 text-[#7A4A3A] font-bold'"
                                        class="text-[10px] px-2 py-0.5 rounded flex items-center gap-1"
                                      >
                                        <svg v-if="key.toLowerCase() === 'catatan'" class="w-3 h-3 text-amber-700 inline flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>{{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}</span>
                                      </span>
                                    </div>
                                  </div>
                                  <span class="font-semibold text-[#7A4A3A] whitespace-nowrap">Rp {{ formatPrice(item.subtotal) }}</span>
                                </div>
                              </div>
                            </div>
                            <!-- Order Info -->
                            <div>
                              <p class="text-[10px] font-bold text-[#7A4A3A] uppercase tracking-wider mb-2">Info Transaksi</p>
                              <div class="bg-white rounded-xl p-3 space-y-1.5 text-sm border border-[#B98B6A]/10">
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">ID Order:</span><span class="font-bold text-[#4B2E2A]">#{{ order.id }}</span></div>
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">Hari:</span><span class="font-bold text-[#4B2E2A]">{{ formatDayName(order.created_at) }}</span></div>
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">Tanggal:</span><span class="font-bold text-[#4B2E2A]">{{ formatDateFull(order.created_at) }}</span></div>
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">Waktu:</span><span class="font-bold text-[#4B2E2A]">{{ formatTime(order.created_at) }}</span></div>
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">Meja:</span><span class="font-bold text-[#4B2E2A]">{{ order.nomor_meja }}</span></div>
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">Pelanggan:</span><span class="font-bold text-[#4B2E2A]">{{ order.customer_name || 'Pelanggan' }}</span></div>
                                <div v-if="order.customer_email" class="flex justify-between"><span class="text-[#7A4A3A]">Email:</span><span class="font-bold text-[#4B2E2A]">{{ order.customer_email }}</span></div>
                                <div class="flex justify-between"><span class="text-[#7A4A3A]">Metode Bayar:</span><span class="font-bold text-[#4B2E2A]">{{ order.payment_method || 'QRIS' }}</span></div>
                                <div class="flex justify-between border-t border-[#B98B6A]/10 pt-1.5 mt-1"><span class="text-[#7A4A3A] font-bold">Total:</span><span class="font-extrabold text-[#4B2E2A] text-base">Rp {{ formatPrice(calculateOrderTotal(order)) }}</span></div>
                              </div>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </main>
      </template>

      <!-- ============================== -->
      <!-- PAGE: KELOLA MENU (MANAGEMENT) -->
      <!-- ============================== -->
      <template v-if="sidebarPage === 'menu-management'">
        <!-- Header -->
        <header class="bg-[#E9D8C6] border-b border-[#B98B6A]/30 px-8 py-4 shadow-sm z-20 flex-shrink-0 no-print">
          <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div>
              <h2 class="text-2xl font-extrabold text-[#4B2E2A] drop-shadow-sm font-heading">Kelola Menu</h2>
              <p class="text-[#4B2E2A]/70 text-xs mt-0.5 font-medium">Atur daftar menu minuman & makanan Kopi Ruang Hati.</p>
            </div>
            <div class="flex gap-3 items-center">
              <!-- Category Filter -->
              <div class="relative">
                <select 
                  v-model="menuCategoryFilter"
                  class="bg-[#F7F2EC] border border-[#B98B6A]/30 rounded-xl pl-4 pr-9 py-2 text-sm font-bold text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 appearance-none cursor-pointer"
                >
                  <option value="">Semua Kategori ({{ menuItems.length }})</option>
                  <option v-for="cat in availableCategories" :key="cat" :value="cat">{{ cat }}</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-[#7A4A3A]">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>

              <button 
                @click="fetchMenuItems()"
                class="p-2 bg-[#F7F2EC] hover:bg-[#E9D8C6] text-[#4B2E2A] rounded-xl font-bold transition-all shadow-sm border border-[#B98B6A]/30 flex items-center gap-1 text-sm"
                title="Refresh Menu"
              >
                <img src="@/assets/refresh.png" alt="Refresh" class="w-4 h-4 object-contain inline-block" />
              </button>
              
              <button 
                @click="openMenuModal(null)"
                class="bg-[#4B2E2A] hover:bg-[#7A4A3A] text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm flex items-center gap-2 active:scale-95"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Menu
              </button>
            </div>
          </div>
        </header>

        <!-- Menu Content -->
        <main class="flex-1 p-8 overflow-y-auto">
          <div class="max-w-6xl mx-auto">

            <!-- Loading Menu -->
            <div v-if="isMenuLoading" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
              <div v-for="i in 6" :key="'mskel-'+i" class="bg-[#F7F2EC]/80 rounded-2xl p-5 shadow-sm border border-[#B98B6A]/20 animate-pulse">
                <div class="h-5 bg-[#B98B6A]/30 rounded w-3/4 mb-3"></div>
                <div class="h-4 bg-[#B98B6A]/20 rounded w-1/2 mb-2"></div>
                <div class="h-4 bg-[#B98B6A]/20 rounded w-1/3"></div>
              </div>
            </div>

            <!-- Empty Menu -->
            <div v-else-if="filteredMenuItems.length === 0" class="bg-[#F7F2EC]/80 border-2 border-dashed border-[#B98B6A]/40 rounded-3xl p-16 flex flex-col items-center justify-center text-center backdrop-blur-sm shadow-sm">
              <svg class="w-16 h-16 text-[#B98B6A]/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
              <h3 class="text-xl font-bold text-[#4B2E2A]">{{ menuCategoryFilter ? 'Tidak Ada Menu di Kategori Ini' : 'Belum Ada Menu' }}</h3>
              <p class="text-[#7A4A3A] mt-2 max-w-md">{{ menuCategoryFilter ? 'Coba pilih kategori lain atau tambahkan menu baru.' : 'Klik tombol "Tambah Menu" untuk mulai menambahkan item menu.' }}</p>
            </div>

            <!-- Menu Grid -->
            <div v-else>
              <!-- Category Groups -->
              <div v-for="(items, category) in groupedMenuItems" :key="category" class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                  <h3 class="text-lg font-extrabold text-[#4B2E2A] font-heading">{{ category || 'Tanpa Kategori' }}</h3>
                  <span class="text-xs font-bold bg-[#B98B6A]/20 text-[#7A4A3A] px-2.5 py-0.5 rounded-full">{{ items.length }} item</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                  <div v-for="menu in items" :key="menu.id" class="bg-[#F7F2EC]/95 rounded-2xl p-5 shadow-sm border border-[#B98B6A]/20 hover:shadow-md transition-all backdrop-blur-sm group">
                    <div class="flex justify-between items-start mb-2">
                      <div class="flex-1 pr-3">
                        <h4 class="font-bold text-[#4B2E2A] text-sm leading-tight">{{ menu.name }}</h4>
                        <p v-if="menu.description" class="text-[11px] text-[#7A4A3A]/70 mt-1 line-clamp-2">{{ menu.description }}</p>
                      </div>
                      <span class="text-sm font-extrabold text-[#4B2E2A] whitespace-nowrap">Rp {{ formatPrice(menu.price) }}</span>
                    </div>

                    <!-- Variant Tags -->
                    <div v-if="menu.variants && menu.variants.length > 0" class="flex flex-wrap gap-1 mt-2 mb-3">
                      <span v-for="(v, vi) in menu.variants" :key="vi" class="text-[10px] font-bold bg-[#B98B6A]/15 text-[#7A4A3A] px-2 py-0.5 rounded">
                        {{ v.name }} ({{ v.options?.length || 0 }} opsi)
                      </span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mt-3 pt-3 border-t border-[#B98B6A]/10">
                      <button 
                        @click="openMenuModal(menu)"
                        class="flex-1 py-2 px-3 rounded-lg bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                      >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                      </button>
                      <button 
                        @click="openDeleteModal(menu)"
                        class="py-2 px-3 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition-all flex items-center justify-center gap-1.5"
                      >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </main>
      </template>

    </div>

    <!-- ================================ -->
    <!-- MODAL: Pratinjau & Cetak Struk    -->
    <!-- ================================ -->
    <div 
      v-if="showReceiptModal && selectedReceiptOrder" 
      id="printable-receipt-modal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto"
    >
      <div class="bg-[#F7F2EC] rounded-3xl p-6 max-w-md w-full shadow-2xl border border-[#B98B6A]/30 flex flex-col max-h-[90vh] my-auto">
        <div class="flex justify-between items-center pb-4 border-b border-[#B98B6A]/20 no-print">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-[#4B2E2A] text-white flex items-center justify-center shadow-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-[#4B2E2A] leading-tight">Pratinjau Struk</h3>
              <p class="text-[11px] text-[#7A4A3A] font-medium">Format Cetak Thermal POS</p>
            </div>
          </div>
          <button @click="closeReceiptModal" class="w-8 h-8 rounded-full bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] flex items-center justify-center transition-colors font-bold text-sm">✕</button>
        </div>
        <div class="flex-1 overflow-y-auto py-4">
          <div class="receipt-paper bg-white text-gray-800 p-5 rounded-2xl shadow-md border border-gray-200 font-mono text-xs max-w-sm mx-auto">
            <div class="text-center space-y-1 pb-2 pt-1">
              <img :src="logoPersegi" alt="Logo" class="w-14 h-14 object-contain mx-auto mt-2 mb-2" />
              <h2 class="text-base font-extrabold tracking-wider uppercase text-gray-900">KOPI RUANG HATI</h2>
              <p class="text-[10px] text-gray-500">Sistem Pemesanan Mandiri QR</p>
            </div>
            <div class="border-b-2 border-dashed border-gray-400 my-2"></div>
            <div class="space-y-1 text-[11px] leading-relaxed">
              <div class="flex justify-between"><span class="text-gray-500">No. Order:</span><span class="font-bold text-gray-900">#{{ selectedReceiptOrder.id }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Meja:</span><span class="font-bold text-gray-900">Meja {{ selectedReceiptOrder.nomor_meja }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Waktu:</span><span class="text-gray-900">{{ formatFullDateTime(selectedReceiptOrder.created_at) }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Pelanggan:</span><span class="font-semibold text-gray-900">{{ selectedReceiptOrder.customer_name || 'Pelanggan' }}</span></div>
              <div v-if="selectedReceiptOrder.customer_email" class="flex justify-between"><span class="text-gray-500">Email:</span><span class="text-gray-900 font-medium truncate max-w-[180px] text-right">{{ selectedReceiptOrder.customer_email }}</span></div>
              <div class="flex justify-between"><span class="text-gray-500">Kasir:</span><span class="text-gray-900">Kasir Utama</span></div>
            </div>
            <div class="border-b-2 border-dashed border-gray-400 my-2"></div>
            <div class="space-y-2 py-1">
              <div v-for="item in selectedReceiptOrder.items" :key="item.id" class="text-[11px]">
                <div class="flex justify-between items-start"><span class="font-bold text-gray-900 flex-1 pr-2">{{ item.menu?.name || 'Item' }}</span><span class="font-bold text-gray-900 text-right">Rp {{ formatPrice(item.subtotal) }}</span></div>
                <div class="flex justify-between text-gray-500 text-[10px]"><span>{{ item.quantity }} x Rp {{ formatPrice(item.subtotal / item.quantity) }}</span></div>
                <div v-if="item.options && Object.keys(item.options).length > 0" class="text-[10px] text-gray-500 pl-2 pt-0.5 italic">
                  <span v-for="(value, key) in (typeof item.options === 'string' ? JSON.parse(item.options) : item.options)" :key="key" class="block">• {{ key }}: {{ Array.isArray(value) ? value.join(', ') : value }}</span>
                </div>
              </div>
            </div>
            <div class="border-b-2 border-dashed border-gray-400 my-2"></div>
            <div class="space-y-1 text-[11px]">
              <div class="flex justify-between"><span class="text-gray-600">Total Qty:</span><span class="font-bold text-gray-900">{{ calculateTotalQty(selectedReceiptOrder) }} Item</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Subtotal:</span><span class="font-semibold text-gray-900">Rp {{ formatPrice(calculateOrderTotal(selectedReceiptOrder)) }}</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Pajak / PB1 (0%):</span><span class="text-gray-900">Rp 0</span></div>
              <div class="border-b border-dashed border-gray-300 my-1"></div>
              <div class="flex justify-between text-sm font-extrabold pt-1"><span class="text-gray-900">TOTAL:</span><span class="text-gray-900">Rp {{ formatPrice(calculateOrderTotal(selectedReceiptOrder)) }}</span></div>
            </div>
            <div class="border-b-2 border-dashed border-gray-400 my-2"></div>
            <div class="space-y-1 text-[11px]">
              <div class="flex justify-between items-center"><span class="text-gray-600">Status Bayar:</span><span class="font-bold px-2 py-0.5 rounded text-[10px]" :class="selectedReceiptOrder.payment_status === 'Paid' || selectedReceiptOrder.status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'">{{ selectedReceiptOrder.payment_status === 'Paid' || selectedReceiptOrder.status === 'Selesai' ? 'LUNAS (PAID)' : 'BELUM LUNAS (UNPAID)' }}</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Metode Bayar:</span><span class="font-semibold text-gray-900">{{ selectedReceiptOrder.payment_method || 'QRIS' }}</span></div>
            </div>
            <div class="border-b-2 border-dashed border-gray-400 my-3"></div>
            <div class="text-center space-y-1 text-[10px] text-gray-500 pt-1">
              <p class="font-bold text-gray-700">*** TERIMA KASIH ***</p>
              <p>Silakan simpan struk ini sebagai bukti pembayaran</p>
              <p class="text-[9px] pt-1 text-gray-400">QR Menu Ordering System</p>
            </div>
          </div>
        </div>
        <div class="flex gap-3 pt-4 border-t border-[#B98B6A]/20 no-print">
          <button @click="closeReceiptModal" class="flex-1 py-2.5 px-4 rounded-xl bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] font-bold text-sm transition-colors">Tutup</button>
          <button @click="printReceipt" class="flex-1 py-2.5 px-4 rounded-xl bg-[#4B2E2A] hover:bg-[#7A4A3A] text-white font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            <span>Cetak Struk Sekarang</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ================================ -->
    <!-- MODAL: Tambah / Edit Menu        -->
    <!-- ================================ -->
    <div v-if="showMenuModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto">
      <div class="bg-[#F7F2EC] rounded-3xl p-6 max-w-lg w-full shadow-2xl border border-[#B98B6A]/30 flex flex-col max-h-[90vh] my-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center pb-4 border-b border-[#B98B6A]/20">
          <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-[#4B2E2A] text-white flex items-center justify-center shadow-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-[#4B2E2A] leading-tight">{{ menuForm.id ? 'Edit Menu' : 'Tambah Menu Baru' }}</h3>
              <p class="text-[11px] text-[#7A4A3A] font-medium">{{ menuForm.id ? 'Perbarui informasi menu item' : 'Isi detail menu item baru' }}</p>
            </div>
          </div>
          <button @click="closeMenuModal" class="w-8 h-8 rounded-full bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] flex items-center justify-center transition-colors font-bold text-sm">✕</button>
        </div>

        <!-- Form -->
        <div class="flex-1 overflow-y-auto py-4 space-y-4">
          <!-- Nama Menu -->
          <div>
            <label class="block text-xs font-bold text-[#4B2E2A] mb-1.5 uppercase tracking-wider">Nama Menu <span class="text-red-500">*</span></label>
            <input v-model="menuForm.name" type="text" placeholder="Contoh: Kopi Susu Aren" class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-4 py-2.5 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 placeholder-[#B98B6A]/50" />
          </div>

          <!-- Kategori + Harga Row -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-[#4B2E2A] mb-1.5 uppercase tracking-wider">Kategori</label>
              <select v-model="menuForm.category" class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-4 py-2.5 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 appearance-none cursor-pointer">
                <option value="">Pilih Kategori</option>
                <option v-for="cat in defaultCategories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-bold text-[#4B2E2A] mb-1.5 uppercase tracking-wider">Harga <span class="text-red-500">*</span></label>
              <input v-model.number="menuForm.price" type="number" min="0" placeholder="25000" class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-4 py-2.5 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 placeholder-[#B98B6A]/50" />
            </div>
          </div>

          <!-- Deskripsi -->
          <div>
            <label class="block text-xs font-bold text-[#4B2E2A] mb-1.5 uppercase tracking-wider">Deskripsi</label>
            <textarea v-model="menuForm.description" rows="2" placeholder="Deskripsi singkat menu..." class="w-full bg-white border border-[#B98B6A]/30 rounded-xl px-4 py-2.5 text-sm text-[#4B2E2A] focus:outline-none focus:ring-2 focus:ring-[#B98B6A]/50 placeholder-[#B98B6A]/50 resize-none"></textarea>
          </div>

          <!-- Foto / Gambar Menu -->
          <div>
            <label class="block text-xs font-bold text-[#4B2E2A] mb-1.5 uppercase tracking-wider">Foto Menu</label>
            <div class="flex items-start gap-4 p-3.5 bg-white border border-[#B98B6A]/30 rounded-2xl">
              <!-- Thumbnail Preview Box -->
              <div class="w-24 h-24 rounded-xl bg-[#F7F2EC] border border-[#B98B6A]/30 overflow-hidden flex-shrink-0 flex items-center justify-center relative shadow-inner">
                <img 
                  v-if="imagePreviewUrl" 
                  :src="imagePreviewUrl" 
                  alt="Preview" 
                  class="w-full h-full object-cover" 
                />
                <div v-else class="text-center p-2 text-[#7A4A3A]/60">
                  <svg class="w-7 h-7 mx-auto mb-1 text-[#B98B6A]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span class="text-[10px] font-bold">Tanpa Foto</span>
                </div>
              </div>

              <!-- Upload Actions -->
              <div class="flex-1 space-y-2">
                <input 
                  type="file" 
                  ref="fileInputRef" 
                  accept="image/png, image/jpeg, image/jpg, image/webp" 
                  class="hidden" 
                  @change="handleFileSelected" 
                />
                
                <div class="flex flex-wrap gap-2">
                  <button 
                    type="button"
                    @click="triggerFileInput" 
                    class="px-4 py-2 rounded-xl bg-[#4B2E2A] hover:bg-[#7A4A3A] text-white text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 active:scale-95 cursor-pointer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>{{ imagePreviewUrl ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                  </button>
                  
                  <button 
                    v-if="imagePreviewUrl" 
                    type="button"
                    @click="removeSelectedImage" 
                    class="px-3 py-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition-all cursor-pointer"
                  >
                    Hapus
                  </button>
                </div>
                
                <p class="text-[11px] text-[#7A4A3A]/70 leading-relaxed">
                  Format: JPG, PNG, atau WebP (Maks. 5 MB). Gambar akan otomatis terupload ke server.
                </p>
              </div>
            </div>
          </div>

          <!-- Variants / Add-ons Section -->
          <div class="border-t border-[#B98B6A]/20 pt-4">
            <div class="flex justify-between items-center mb-3">
              <label class="text-xs font-bold text-[#4B2E2A] uppercase tracking-wider">Varian / Add-on</label>
              <button @click="addVariantGroup" class="text-xs font-bold text-[#4B2E2A] bg-[#E9D8C6] hover:bg-[#B98B6A]/30 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Grup
              </button>
            </div>

            <div v-for="(variant, vi) in menuForm.variants" :key="vi" class="bg-white rounded-xl border border-[#B98B6A]/20 p-4 mb-3">
              <div class="flex gap-3 mb-3">
                <div class="flex-1">
                  <input v-model="variant.name" type="text" placeholder="Nama grup (misal: Add On)" class="w-full bg-[#F7F2EC] border border-[#B98B6A]/20 rounded-lg px-3 py-2 text-xs text-[#4B2E2A] focus:outline-none focus:ring-1 focus:ring-[#B98B6A]/40 placeholder-[#B98B6A]/50" />
                </div>
                <select v-model="variant.type" class="bg-[#F7F2EC] border border-[#B98B6A]/20 rounded-lg px-3 py-2 text-xs text-[#4B2E2A] focus:outline-none appearance-none cursor-pointer">
                  <option value="single">Single</option>
                  <option value="multiple">Multiple</option>
                </select>
                <button @click="removeVariantGroup(vi)" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-500 flex items-center justify-center transition-colors flex-shrink-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
              </div>

              <!-- Variant Options -->
              <div v-for="(opt, oi) in variant.options" :key="oi" class="flex gap-2 mb-2">
                <input v-model="opt.name" type="text" placeholder="Nama opsi" class="flex-1 bg-[#F7F2EC] border border-[#B98B6A]/15 rounded-lg px-3 py-1.5 text-xs text-[#4B2E2A] focus:outline-none focus:ring-1 focus:ring-[#B98B6A]/40 placeholder-[#B98B6A]/50" />
                <input v-model.number="opt.price" type="number" min="0" placeholder="Harga" class="w-24 bg-[#F7F2EC] border border-[#B98B6A]/15 rounded-lg px-3 py-1.5 text-xs text-[#4B2E2A] focus:outline-none focus:ring-1 focus:ring-[#B98B6A]/40 placeholder-[#B98B6A]/50" />
                <button @click="removeVariantOption(vi, oi)" class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 flex items-center justify-center transition-colors flex-shrink-0 text-xs">✕</button>
              </div>
              <button @click="addVariantOption(vi)" class="text-[11px] font-bold text-[#7A4A3A] hover:text-[#4B2E2A] flex items-center gap-1 mt-1 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Opsi
              </button>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-3 pt-4 border-t border-[#B98B6A]/20">
          <button @click="closeMenuModal" class="flex-1 py-2.5 px-4 rounded-xl bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] font-bold text-sm transition-colors">Batal</button>
          <button 
            @click="saveMenu" 
            :disabled="isSavingMenu"
            class="flex-1 py-2.5 px-4 rounded-xl bg-[#4B2E2A] hover:bg-[#7A4A3A] text-white font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-md active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="isSavingMenu" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            <span>{{ menuForm.id ? 'Simpan Perubahan' : 'Tambah Menu' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- ================================ -->
    <!-- MODAL: Konfirmasi Hapus Menu     -->
    <!-- ================================ -->
    <div v-if="showDeleteModal && menuToDelete" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
      <div class="bg-[#F7F2EC] rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-[#B98B6A]/30">
        <div class="text-center mb-5">
          <div class="w-14 h-14 rounded-full bg-red-100 text-red-500 flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </div>
          <h3 class="text-lg font-bold text-[#4B2E2A]">Hapus Menu?</h3>
          <p class="text-sm text-[#7A4A3A] mt-2">Anda yakin ingin menghapus <strong>"{{ menuToDelete.name }}"</strong>? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="flex gap-3">
          <button @click="closeDeleteModal" class="flex-1 py-2.5 px-4 rounded-xl bg-[#E9D8C6] hover:bg-[#B98B6A]/30 text-[#4B2E2A] font-bold text-sm transition-colors">Batal</button>
          <button 
            @click="deleteMenu" 
            :disabled="isDeletingMenu"
            class="flex-1 py-2.5 px-4 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm transition-all shadow-md active:scale-95 disabled:opacity-50"
          >
            {{ isDeletingMenu ? 'Menghapus...' : 'Ya, Hapus' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import bgDashboard from '@/assets/bgdashboard.jpg';
import logoPersegi from '@/assets/logo-persegi.png';

window.Pusher = Pusher;

const API_BASE = 'http://127.0.0.1:8000/api';

// ====== SIDEBAR & PAGE STATE ======
const sidebarPage = ref('orders'); // 'orders' | 'menu-management' | 'transaction-history'

// ====== ORDERS STATE ======
const currentTab = ref('active');
const orders = ref([]);
const historyOrders = ref([]);
const outletId = ref('all');
const isConnected = ref(false);
const isLoading = ref(true);
let echoInstance = null;

// ====== PAID ACTION FEEDBACK STATE ======
const processingOrderIds = ref([]);
const successOrderIds = ref([]);
const toastMessage = ref(null);
let toastTimeout = null;

// ====== RECEIPT MODAL STATE ======
const showReceiptModal = ref(false);
const selectedReceiptOrder = ref(null);

// ====== MENU MANAGEMENT STATE ======
const menuItems = ref([]);
const isMenuLoading = ref(false);
const menuCategoryFilter = ref('');

// Menu Form Modal
const showMenuModal = ref(false);
const isSavingMenu = ref(false);
const menuForm = ref(getEmptyMenuForm());
const fileInputRef = ref(null);
const menuImageFile = ref(null);
const imagePreviewUrl = ref('');

// Delete Modal
const showDeleteModal = ref(false);
const menuToDelete = ref(null);
const isDeletingMenu = ref(false);

// ====== TRANSACTION HISTORY STATE ======
const txOrders = ref([]);
const txSummary = ref({ total_revenue: 0, total_orders: 0, total_items: 0 });
const isTxLoading = ref(false);
const txExpandedId = ref(null);
const txFilter = ref({
  dateFrom: '',
  dateTo: '',
  paymentMethod: '',
  search: '',
});
const periodMode = ref('daily');
const currentPeriodDate = ref(new Date());

const defaultCategories = [
  'Coffee',
  'Non Coffee',
  'Hot Drink',
  'Main Course',
  'Warm & Spicy Bowls',
  'Light Meals & Platters',
  'Sweet Bites'
];

function getEmptyMenuForm() {
  return {
    id: null,
    name: '',
    category: '',
    description: '',
    price: null,
    image_url: '',
    variants: []
  };
}

// ====== COMPUTED ======
const periodLabel = computed(() => {
  const date = currentPeriodDate.value;
  if (periodMode.value === 'daily') {
    return date.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
  } else if (periodMode.value === 'weekly') {
    const day = date.getDay();
    const diff = date.getDate() - day + (day === 0 ? -6 : 1);
    const firstDay = new Date(new Date(date).setDate(diff));
    const lastDay = new Date(new Date(firstDay).setDate(firstDay.getDate() + 6));
    
    const startStr = firstDay.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    const endStr = lastDay.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    return `${startStr} - ${endStr}`;
  } else if (periodMode.value === 'monthly') {
    return date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
  }
  return '';
});

const availableCategories = computed(() => {
  const cats = [...new Set(menuItems.value.map(m => m.category).filter(Boolean))];
  return cats.sort();
});

const filteredMenuItems = computed(() => {
  if (!menuCategoryFilter.value) return menuItems.value;
  return menuItems.value.filter(m => m.category === menuCategoryFilter.value);
});

const groupedMenuItems = computed(() => {
  const categoryOrder = [
    'Coffee', 
    'Non Coffee', 
    'Hot Drink', 
    'Main Course', 
    'Warm & Spicy Bowls', 
    'Light Meals & Platters', 
    'Sweet Bites'
  ];

  const groups = {};
  filteredMenuItems.value.forEach(m => {
    const cat = m.category || 'Tanpa Kategori';
    if (!groups[cat]) groups[cat] = [];
    groups[cat].push(m);
  });

  // Sort categories according to preferred ordering
  const sortedGroups = {};
  const keys = Object.keys(groups).sort((a, b) => {
    const iA = categoryOrder.indexOf(a);
    const iB = categoryOrder.indexOf(b);
    return (iA === -1 ? 999 : iA) - (iB === -1 ? 999 : iB);
  });

  keys.forEach(k => {
    sortedGroups[k] = groups[k];
  });

  return sortedGroups;
});

// ====== FORMAT HELPERS ======
const formatPrice = (price) => {
  return Number(price || 0).toLocaleString('id-ID');
};

const formatTime = (dateStr) => {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
};

const formatFullDateTime = (dateStr) => {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  return date.toLocaleString('id-ID', {
    weekday: 'long',
    day: 'numeric', month: 'short', year: 'numeric',
    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
  });
};

const calculateOrderTotal = (order) => {
  if (!order || !order.items) return 0;
  return order.items.reduce((total, item) => total + (Number(item.subtotal) || 0), 0);
};

const calculateTotalQty = (order) => {
  if (!order || !order.items) return 0;
  return order.items.reduce((qty, item) => qty + (Number(item.quantity) || 0), 0);
};

const formatDayName = (dateStr) => {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  return date.toLocaleDateString('id-ID', { weekday: 'long' });
};

const formatDateFull = (dateStr) => {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

// ====== RECEIPT MODAL ======
const openReceiptModal = (order) => {
  selectedReceiptOrder.value = order;
  showReceiptModal.value = true;
};

const closeReceiptModal = () => {
  showReceiptModal.value = false;
  selectedReceiptOrder.value = null;
};

const printReceipt = () => {
  window.print();
};

// ====== ORDERS LOGIC ======
const connectWebSocket = () => {
  if (echoInstance) {
    echoInstance.leave(`outlet.${outletId.value}.orders`);
  }
  echoInstance = new Echo({
    broadcaster: 'pusher',
    key: '12345',
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
      playNotificationSound();
      orders.value.unshift(e.order);
    });
};

const fetchOrders = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`${API_BASE}/outlets/${outletId.value}/orders`);
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
    const response = await axios.get(`${API_BASE}/outlets/${outletId.value}/orders`);
    if (response.data.success) {
      orders.value = response.data.data;
    }
  } catch (error) {
    console.error('Gagal mengambil data pesanan', error);
  }
};

const fetchHistoryOrders = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get(`${API_BASE}/outlets/${outletId.value}/orders/history`);
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
  if (tab === 'history') fetchHistoryOrders();
  else if (tab === 'active') fetchOrders();
};

const showToast = (title, text) => {
  if (toastTimeout) clearTimeout(toastTimeout);
  toastMessage.value = { title, text };
  toastTimeout = setTimeout(() => {
    toastMessage.value = null;
  }, 3500);
};

const playSuccessSound = () => {
  try {
    const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.type = 'sine';
    osc.frequency.setValueAtTime(587.33, audioCtx.currentTime); // D5
    osc.frequency.setValueAtTime(880, audioCtx.currentTime + 0.08); // A5
    gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
    osc.connect(gain);
    gain.connect(audioCtx.destination);
    osc.start();
    osc.stop(audioCtx.currentTime + 0.3);
  } catch (e) {}
};

const markAsPaid = async (order) => {
  const id = order.id;
  if (processingOrderIds.value.includes(id) || successOrderIds.value.includes(id)) return;
  
  processingOrderIds.value.push(id);
  
  try {
    const response = await axios.post(`${API_BASE}/orders/${id}/pay`);
    if (response.data.success) {
      // 1. Success feedback state
      successOrderIds.value.push(id);
      
      // 2. Play positive chime
      playSuccessSound();
      
      // 3. Show floating toast notification
      showToast('Pembayaran Berhasil!', `Order #${id} (Meja ${order.nomor_meja}) telah ditandai lunas.`);
      
      // 4. Smooth transition: Keep green confirmation briefly before moving to history
      setTimeout(() => {
        orders.value = orders.value.filter(o => o.id !== id);
        historyOrders.value.unshift(response.data.data);
        processingOrderIds.value = processingOrderIds.value.filter(x => x !== id);
        successOrderIds.value = successOrderIds.value.filter(x => x !== id);
      }, 600);
    }
  } catch (error) {
    console.error('Gagal update status bayar', error);
    processingOrderIds.value = processingOrderIds.value.filter(x => x !== id);
    alert('Gagal update status pesanan!');
  }
};

const playNotificationSound = () => {
  try {
    const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
    audio.play().catch(e => console.log('Audio autoplay prevented by browser'));
  } catch (e) {}
};

// ====== MENU MANAGEMENT LOGIC ======
const fetchMenuItems = async () => {
  isMenuLoading.value = true;
  try {
    const response = await axios.get(`${API_BASE}/menus`);
    if (response.data.success) {
      menuItems.value = response.data.data;
    }
  } catch (error) {
    console.error('Gagal mengambil data menu', error);
  } finally {
    isMenuLoading.value = false;
  }
};

const switchToMenuManagement = () => {
  sidebarPage.value = 'menu-management';
  fetchMenuItems();
};

// ====== TRANSACTION HISTORY LOGIC ======
const switchToTransactionHistory = () => {
  sidebarPage.value = 'transaction-history';
  fetchTransactionHistory();
};

const fetchTransactionHistory = async () => {
  isTxLoading.value = true;
  try {
    const params = {};
    if (txFilter.value.dateFrom) params.date_from = txFilter.value.dateFrom;
    if (txFilter.value.dateTo) params.date_to = txFilter.value.dateTo;
    if (txFilter.value.paymentMethod) params.payment_method = txFilter.value.paymentMethod;
    if (txFilter.value.search) params.search = txFilter.value.search;

    const response = await axios.get(`${API_BASE}/transactions/history`, { params });
    if (response.data.success) {
      txOrders.value = response.data.data;
      txSummary.value = response.data.summary || { total_revenue: 0, total_orders: 0, total_items: 0 };
    }
  } catch (error) {
    console.error('Gagal mengambil data riwayat transaksi', error);
  } finally {
    isTxLoading.value = false;
  }
};

const resetTxFilter = () => {
  txFilter.value = { dateFrom: '', dateTo: '', paymentMethod: '', search: '' };
  periodMode.value = 'daily';
  currentPeriodDate.value = new Date();
  applyPeriodFilter();
};

const toLocalISOString = (date) => {
  const offset = date.getTimezoneOffset();
  const adjustedDate = new Date(date.getTime() - (offset * 60 * 1000));
  return adjustedDate.toISOString().split('T')[0];
};

const applyPeriodFilter = () => {
  if (periodMode.value === 'custom') {
    return; // Don't auto-fetch, let user press Terapkan
  }

  const date = currentPeriodDate.value;
  let from = '';
  let to = '';
  
  if (periodMode.value === 'daily') {
    from = toLocalISOString(date);
    to = from;
  } else if (periodMode.value === 'weekly') {
    const day = date.getDay();
    const diff = date.getDate() - day + (day === 0 ? -6 : 1);
    const firstDay = new Date(new Date(date).setDate(diff));
    const lastDay = new Date(new Date(firstDay).setDate(firstDay.getDate() + 6));
    from = toLocalISOString(firstDay);
    to = toLocalISOString(lastDay);
  } else if (periodMode.value === 'monthly') {
    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    from = toLocalISOString(firstDay);
    to = toLocalISOString(lastDay);
  }
  
  txFilter.value.dateFrom = from;
  txFilter.value.dateTo = to;
  fetchTransactionHistory();
};

const onPeriodModeChange = () => {
  currentPeriodDate.value = new Date();
  if (periodMode.value !== 'custom') {
    applyPeriodFilter();
  }
};

const shiftPeriod = (direction) => {
  const date = new Date(currentPeriodDate.value);
  if (periodMode.value === 'daily') {
    date.setDate(date.getDate() + direction);
  } else if (periodMode.value === 'weekly') {
    date.setDate(date.getDate() + (direction * 7));
  } else if (periodMode.value === 'monthly') {
    date.setMonth(date.getMonth() + direction);
  }
  currentPeriodDate.value = date;
  applyPeriodFilter();
};

const toggleTxExpand = (orderId) => {
  txExpandedId.value = txExpandedId.value === orderId ? null : orderId;
};

// Menu Modal
const triggerFileInput = () => {
  fileInputRef.value?.click();
};

const handleFileSelected = (e) => {
  const file = e.target.files?.[0];
  if (file) {
    if (file.size > 5 * 1024 * 1024) {
      alert('Ukuran file maksimal 5 MB!');
      return;
    }
    menuImageFile.value = file;
    imagePreviewUrl.value = URL.createObjectURL(file);
  }
};

const removeSelectedImage = () => {
  menuImageFile.value = null;
  imagePreviewUrl.value = '';
  menuForm.value.image_url = '';
  if (fileInputRef.value) fileInputRef.value.value = '';
};

const openMenuModal = (menu) => {
  if (menu) {
    menuForm.value = {
      id: menu.id,
      name: menu.name || '',
      category: menu.category || '',
      description: menu.description || '',
      price: menu.price || null,
      image_url: menu.image_url || '',
      variants: menu.variants ? JSON.parse(JSON.stringify(menu.variants)) : []
    };
    imagePreviewUrl.value = menu.image_url || '';
    menuImageFile.value = null;
  } else {
    menuForm.value = getEmptyMenuForm();
    imagePreviewUrl.value = '';
    menuImageFile.value = null;
  }
  if (fileInputRef.value) fileInputRef.value.value = '';
  showMenuModal.value = true;
};

const closeMenuModal = () => {
  showMenuModal.value = false;
  menuForm.value = getEmptyMenuForm();
  imagePreviewUrl.value = '';
  menuImageFile.value = null;
  if (fileInputRef.value) fileInputRef.value.value = '';
};

const saveMenu = async () => {
  if (!menuForm.value.name || !menuForm.value.price) {
    alert('Nama dan Harga menu wajib diisi!');
    return;
  }
  isSavingMenu.value = true;
  try {
    const formData = new FormData();
    formData.append('name', menuForm.value.name);
    if (menuForm.value.category) formData.append('category', menuForm.value.category);
    if (menuForm.value.description) formData.append('description', menuForm.value.description);
    formData.append('price', menuForm.value.price);

    if (menuImageFile.value) {
      formData.append('image', menuImageFile.value);
    } else if (menuForm.value.image_url) {
      formData.append('image_url', menuForm.value.image_url);
    } else {
      formData.append('image_url', '');
    }

    if (menuForm.value.variants && menuForm.value.variants.length > 0) {
      formData.append('variants', JSON.stringify(menuForm.value.variants));
    }

    const config = {
      headers: { 'Content-Type': 'multipart/form-data' }
    };

    if (menuForm.value.id) {
      await axios.post(`${API_BASE}/menus/${menuForm.value.id}`, formData, config);
    } else {
      await axios.post(`${API_BASE}/menus`, formData, config);
    }
    closeMenuModal();
    fetchMenuItems();
  } catch (error) {
    console.error('Gagal menyimpan menu', error);
    alert('Gagal menyimpan menu! Periksa data input.');
  } finally {
    isSavingMenu.value = false;
  }
};

// Delete Modal
const openDeleteModal = (menu) => {
  menuToDelete.value = menu;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  menuToDelete.value = null;
};

const deleteMenu = async () => {
  if (!menuToDelete.value) return;
  isDeletingMenu.value = true;
  try {
    await axios.delete(`${API_BASE}/menus/${menuToDelete.value.id}`);
    closeDeleteModal();
    fetchMenuItems();
  } catch (error) {
    console.error('Gagal menghapus menu', error);
    alert('Gagal menghapus menu!');
  } finally {
    isDeletingMenu.value = false;
  }
};

// Variant Helpers
const addVariantGroup = () => {
  menuForm.value.variants.push({
    name: '',
    type: 'single',
    options: [{ name: '', price: 0 }]
  });
};

const removeVariantGroup = (index) => {
  menuForm.value.variants.splice(index, 1);
};

const addVariantOption = (variantIndex) => {
  menuForm.value.variants[variantIndex].options.push({ name: '', price: 0 });
};

const removeVariantOption = (variantIndex, optionIndex) => {
  menuForm.value.variants[variantIndex].options.splice(optionIndex, 1);
};

// ====== LIFECYCLE ======
onMounted(() => {
  fetchOrders();
  connectWebSocket();
  pollTimer = setInterval(() => {
    if (currentTab.value === 'active' && sidebarPage.value === 'orders') {
      fetchOrdersSilently();
    }
  }, 3000);
});

onUnmounted(() => {
  if (pollTimer) clearInterval(pollTimer);
  if (echoInstance) echoInstance.disconnect();
});
</script>

<style scoped>
@keyframes slideInTop {
  0% { transform: translateY(-20px); opacity: 0; }
  100% { transform: translateY(0); opacity: 1; }
}
.slide-in-top {
  animation: slideInTop 0.4s cubic-bezier(0.25, 1, 0.5, 1) forwards;
}

/* Toast Notification Animation */
.toast-slide-enter-active,
.toast-slide-leave-active {
  transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
}
.toast-slide-enter-from {
  opacity: 0;
  transform: translateX(40px) scale(0.92);
}
.toast-slide-leave-to {
  opacity: 0;
  transform: translateY(-20px) scale(0.95);
}

/* Styling Khusus Cetak Struk (Thermal POS Printer) */
@media print {
  body * {
    visibility: hidden !important;
  }

  #printable-receipt-modal,
  #printable-receipt-modal * {
    visibility: visible !important;
  }

  #printable-receipt-modal {
    position: fixed !important;
    left: 0 !important;
    top: 0 !important;
    width: 100% !important;
    height: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    background: transparent !important;
    backdrop-filter: none !important;
    display: flex !important;
    justify-content: center !important;
    align-items: flex-start !important;
    z-index: 99999 !important;
  }

  #printable-receipt-modal > div {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
    margin: 0 !important;
    max-width: 80mm !important;
    width: 100% !important;
  }

  .no-print {
    display: none !important;
  }

  .receipt-paper {
    width: 80mm !important;
    max-width: 80mm !important;
    border: none !important;
    box-shadow: none !important;
    padding: 4mm !important;
    margin: 0 auto !important;
    background: #ffffff !important;
    color: #000000 !important;
    font-size: 9pt !important;
    line-height: 1.3 !important;
  }

  .receipt-paper img {
    max-width: 25mm !important;
    height: auto !important;
    margin: 4mm auto 3mm auto !important;
    display: block !important;
  }

  @page {
    size: 80mm auto;
    margin: 0;
  }
}
</style>
