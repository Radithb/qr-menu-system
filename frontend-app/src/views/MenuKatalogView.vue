<template>
  <div class="min-h-screen bg-background pb-48 font-sans relative">
    
    <header class="bg-[#E9D8C6] shadow-sm sticky top-0 z-10 border-b-2 border-[#B98B6A]/30">
      <div class="px-6 pt-5 pb-3 flex justify-between items-center">
        <div>
          <img src="@/assets/logo-panjang.png" alt="Logo" class="h-24 sm:h-12 w-auto object-contain mb-1" />
          <p class="text-sm font-medium text-[#4B2E2A]/80 mt-1 flex flex-wrap items-center gap-2">
            <span v-if="orderStore.nomorMeja" class="text-white bg-[#B98B6A] px-3 py-0.5 rounded-lg font-bold text-xs shadow-sm">Meja {{ orderStore.nomorMeja }}</span>
            <button @click="showCustomerModal = true" class="text-[#7A4A3A] bg-[#B98B6A]/10 hover:bg-[#B98B6A]/20 px-3 py-0.5 rounded-lg font-bold text-xs border border-[#B98B6A]/30 flex items-center gap-1 transition-colors">
              👤 {{ orderStore.customerName || 'Isi Nama' }}
            </button>
          </p>
        </div>
      </div>
      
      <!-- Search Input Bar -->
      <div class="px-6 pb-3">
        <div class="relative flex items-center">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#7A4A3A]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Cari nama menu atau rasa..." 
            class="w-full pl-10 pr-9 py-2.5 rounded-xl bg-white/90 border border-[#B98B6A]/40 text-[#4B2E2A] text-xs font-bold placeholder-[#7A4A3A]/60 focus:outline-none focus:bg-white focus:border-[#4B2E2A] transition-all shadow-sm"
          />
          <button 
            v-if="searchQuery" 
            @click="searchQuery = ''" 
            class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#7A4A3A] hover:text-[#4B2E2A]"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Category Tabs -->
      <div v-if="categories.length > 0" class="flex overflow-x-auto hide-scrollbar px-6 pb-4 pt-1 gap-3 snap-x">
        <button 
          v-for="cat in categories" 
          :key="cat"
          :id="'tab-' + cat"
          @click="scrollToCategory(cat)"
          :class="activeCategory === cat ? 'bg-[#4B2E2A] text-white shadow-md scale-105' : 'bg-transparent text-[#4B2E2A]/80 border border-[#4B2E2A]/30 hover:bg-[#4B2E2A]/10'"
          class="px-5 py-2 rounded-full font-bold text-sm whitespace-nowrap transition-all snap-start"
        >
          {{ cat }}
        </button>
      </div>
    </header>

    <main class="p-6 space-y-6">
      <div v-if="isLoading" class="space-y-12 w-full">
        <div v-for="i in 2" :key="i" class="animate-pulse">
          <div class="h-8 bg-[#B98B6A]/30 rounded-full w-1/3 mb-6"></div>
          <div class="space-y-4">
            <div v-for="j in 3" :key="j" class="bg-[#F7F2EC] rounded-3xl p-5 shadow-sm border border-[#B98B6A]/10 flex gap-5 items-center">
              <div class="w-28 h-28 flex-shrink-0 bg-[#E9D8C6] rounded-2xl"></div>
              <div class="flex-1 space-y-3">
                <div class="h-6 bg-[#E9D8C6] rounded-full w-3/4"></div>
                <div class="h-4 bg-[#E9D8C6] rounded-full w-full"></div>
                <div class="mt-4 flex justify-between items-center pt-2">
                  <div class="h-6 bg-[#E9D8C6] rounded-full w-1/4"></div>
                  <div class="h-10 bg-[#B98B6A]/20 rounded-xl w-24"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else-if="menus.length === 0" class="text-center py-16 bg-card rounded-3xl border-2 border-primary/10 shadow-lg">
        <p class="text-textColor/70 text-base font-medium">Menu belum tersedia di outlet ini.</p>
      </div>

      <div v-else-if="Object.keys(filteredGroupedMenus).length === 0" class="text-center py-16 bg-white/60 rounded-3xl border border-[#B98B6A]/20 shadow-sm px-4">
        <div class="text-4xl mb-3">🔍</div>
        <p class="text-[#4B2E2A] font-bold text-base">Menu "{{ searchQuery }}" tidak ditemukan</p>
        <p class="text-[#4B2E2A]/70 text-xs mt-1">Coba gunakan kata kunci lain atau hapus pencarian</p>
        <button @click="searchQuery = ''" class="mt-4 px-4 py-2 bg-[#B98B6A] text-white text-xs font-bold rounded-xl shadow-sm hover:bg-[#7A4A3A] transition-colors">
          Lihat Semua Menu
        </button>
      </div>

      <div v-else class="space-y-12">
        <div v-for="(items, category) in filteredGroupedMenus" :key="category" :id="'category-' + category" class="scroll-mt-36">
          <h2 class="text-2xl font-extrabold text-textColor mb-6 flex items-center gap-3">
            <span class="w-2 h-8 bg-primary rounded-full inline-block"></span>
            {{ category }}
          </h2>
          
          <div class="space-y-4">
            <div 
              v-for="menu in items" 
              :key="menu.id" 
              class="bg-[#F7F2EC] rounded-3xl p-5 shadow-md border border-[#B98B6A]/20 flex gap-5 items-center transition-all active:scale-[0.98] cursor-pointer"
              @click="openAddModal(menu)"
            >
              <div class="w-28 h-28 flex-shrink-0 bg-[#E9D8C6] rounded-2xl overflow-hidden border border-[#B98B6A]/30 relative">
                <img v-if="menu.image_url" :src="menu.image_url" :alt="menu.name" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-[#7A4A3A]/60 text-sm font-bold">
                  No Pic
                </div>
              </div>
              
              <div class="flex-1">
                <h3 class="font-bold text-[#4B2E2A] text-lg leading-tight font-heading">{{ menu.name }}</h3>
                <p v-if="menu.description" class="text-sm text-[#4B2E2A]/70 mt-1 line-clamp-2 leading-relaxed">{{ menu.description }}</p>
                
                <div class="mt-4 flex justify-between items-center">
                  <span class="font-extrabold text-[#7A4A3A] text-base">Rp {{ formatPrice(menu.price) }}</span>
                  <button 
                    @click.stop="openAddModal(menu)"
                    class="bg-[#B98B6A] text-white hover:bg-[#7A4A3A] px-5 py-2.5 rounded-xl text-sm font-bold active:scale-90 transition-colors duration-200 shadow-sm"
                  >
                    Tambah
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Bottom Sheet Modal for Variants -->
    <transition name="fade">
      <div v-if="selectedMenu" class="fixed inset-0 bg-textColor/50 backdrop-blur-sm z-30" @click="closeAddModal"></div>
    </transition>
    
    <transition name="slide-up">
      <div v-if="selectedMenu" class="fixed bottom-0 left-0 right-0 bg-card rounded-t-3xl shadow-[0_-10px_40px_rgba(0,0,0,0.1)] z-40 max-h-[85vh] flex flex-col">
        <div class="p-6 border-b-2 border-primary/10 flex-shrink-0 flex justify-between items-start">
          <div>
            <h2 class="text-2xl font-extrabold text-textColor font-heading">{{ selectedMenu.name }}</h2>
            <p class="text-primary font-bold mt-1 text-lg">Rp {{ formatPrice(selectedMenu.price) }}</p>
          </div>
          <button @click="closeAddModal" class="p-2 bg-background rounded-full text-textColor/50 hover:bg-primary/10 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
          </button>
        </div>

        <div class="overflow-y-auto p-6 space-y-6 flex-1">
          <div v-if="selectedMenu.variants && selectedMenu.variants.length > 0" class="space-y-6">
            <div v-for="variant in selectedMenu.variants" :key="variant.name" class="space-y-3">
              <div class="flex justify-between items-center">
                <h4 class="font-bold text-textColor text-lg">{{ variant.name }}</h4>
                <span class="text-xs font-bold px-2 py-1 bg-primary/10 text-primary rounded-md uppercase">
                  {{ variant.type === 'single' ? 'Pilih Satu' : 'Boleh Lebih Dari Satu' }}
                </span>
              </div>
              
              <div class="space-y-2">
                <label v-for="option in variant.options" :key="option.name" class="flex items-center justify-between p-4 bg-background/50 border-2 border-transparent focus-within:border-primary/30 rounded-2xl cursor-pointer hover:bg-background transition-colors">
                  <div class="flex items-center gap-3">
                    <input 
                      v-if="variant.type === 'single'"
                      type="radio" 
                      :name="variant.name" 
                      :value="option.name" 
                      v-model="selectedOptions[variant.name]"
                      class="w-5 h-5 text-primary accent-primary focus:ring-primary"
                    >
                    <input 
                      v-else
                      type="checkbox" 
                      :value="option.name" 
                      v-model="selectedOptions[variant.name]"
                      class="w-5 h-5 text-primary accent-primary rounded focus:ring-primary"
                    >
                    <span class="font-semibold text-textColor">{{ option.name }}</span>
                  </div>
                  <span v-if="option.price > 0" class="font-bold text-textColor/70">+Rp {{ formatPrice(option.price) }}</span>
                </label>
              </div>
            </div>
          </div>

          <div class="pt-4 border-t-2 border-primary/10 flex items-center justify-between">
            <span class="font-bold text-textColor text-lg">Jumlah</span>
            <div class="flex items-center gap-4 bg-background/50 rounded-2xl p-1 border border-primary/10">
              <button @click="modalQuantity > 1 && modalQuantity--" class="w-10 h-10 flex items-center justify-center rounded-xl bg-card text-textColor font-bold shadow-sm active:scale-90">-</button>
              <span class="font-bold w-6 text-center text-lg">{{ modalQuantity }}</span>
              <button @click="modalQuantity++" class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-cta font-bold shadow-sm active:scale-90">+</button>
            </div>
          </div>
        </div>

        <div class="p-6 bg-card border-t-2 border-primary/10 flex-shrink-0">
          <button @click="confirmAddToCart" class="w-full bg-primary text-cta rounded-2xl py-4 px-6 flex justify-between items-center shadow-xl active:scale-[0.97] transition-all">
            <span class="font-bold text-lg tracking-widest uppercase">Tambah Pesanan</span>
            <span class="font-extrabold text-xl font-heading">Rp {{ formatPrice(modalTotalPrice) }}</span>
          </button>
        </div>
      </div>
    </transition>

    <transition name="slide-up">
      <div v-if="cartStore.totalItems > 0 && !selectedMenu" class="fixed bottom-16 left-0 right-0 p-6 bg-background/80 backdrop-blur-lg border-t-2 border-primary/10 z-20">
        <button 
          @click="goToCart"
          class="w-full bg-primary text-cta rounded-2xl py-5 px-6 flex justify-between items-center shadow-xl active:scale-[0.97] transition-transform duration-200"
        >
          <div class="flex items-center gap-4">
            <div class="bg-secondary px-4 py-1.5 rounded-full text-base font-bold text-cta shadow-inner">
              {{ cartStore.totalItems }}
            </div>
            <span class="font-bold text-base tracking-widest uppercase">Lihat Pesanan</span>
          </div>
          <span class="font-extrabold text-xl font-heading">Rp {{ formatPrice(cartStore.totalPrice) }}</span>
        </button>
      </div>
    </transition>

    <!-- Customer Info Modal -->
    <transition name="modal-fade">
      <div v-if="showCustomerModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#4B2E2A]/60 backdrop-blur-sm">
        <div class="bg-[#F7F2EC] max-w-md w-full p-8 rounded-3xl shadow-2xl border-2 border-[#B98B6A]/30 space-y-6 modal-box">
          
          <div class="text-center space-y-2">
          <h3 class="text-2xl font-extrabold text-[#4B2E2A] font-heading uppercase">Data Pemesan</h3>
          <p class="text-sm text-[#4B2E2A]/80 font-medium leading-relaxed">
            Silakan masukkan nama dan email Anda agar kasir dapat memproses pesanan Anda.
          </p>
        </div>

        <form @submit.prevent="submitCustomerInfo" class="space-y-4">
          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-[#4B2E2A] mb-2">Nama Lengkap</label>
            <input 
              v-model="customerName" 
              type="text" 
              required 
              placeholder="Contoh: Radith" 
              class="w-full px-5 py-3.5 rounded-xl border-2 border-[#B98B6A]/30 bg-white text-[#4B2E2A] font-bold focus:outline-none focus:border-[#B98B6A]"
            />
          </div>

          <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-[#4B2E2A] mb-2">Email</label>
            <input 
              v-model="customerEmail" 
              type="email" 
              required 
              placeholder="Contoh: radith@example.com" 
              class="w-full px-5 py-3.5 rounded-xl border-2 border-[#B98B6A]/30 bg-white text-[#4B2E2A] font-bold focus:outline-none focus:border-[#B98B6A]"
            />
          </div>

          <button 
            type="submit" 
            class="w-full mt-4 bg-[#B98B6A] hover:bg-[#7A4A3A] text-white py-4 rounded-xl font-bold uppercase tracking-wider shadow-lg active:scale-95 transition-all"
          >
            Mulai Memesan
          </button>
        </form>

      </div>
    </div>
    </transition>

    <BottomNav v-if="!selectedMenu && !showCustomerModal" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useOrderStore } from '@/stores/orderStore';
import { useCartStore } from '@/stores/cartStore';
import axios from 'axios';
import BottomNav from '@/components/BottomNav.vue';

const router = useRouter();
const orderStore = useOrderStore();
const cartStore = useCartStore();

const menus = ref([]);
const isLoading = ref(true);
const searchQuery = ref('');

const showCustomerModal = ref(false);
const customerName = ref(orderStore.customerName || '');
const customerEmail = ref(orderStore.customerEmail || '');

const submitCustomerInfo = () => {
  if (!customerName.value || !customerEmail.value) return;
  orderStore.setCustomerData(customerName.value, customerEmail.value);
  showCustomerModal.value = false;
};

const selectedMenu = ref(null);
const selectedOptions = ref({});
const modalQuantity = ref(1);

const categories = computed(() => {
  const categoryOrder = [
    'Coffee', 
    'Non Coffee', 
    'Hot Drink', 
    'Main Course', 
    'Warm & Spicy Bowls', 
    'Light Meals & Platters', 
    'Sweet Bites'
  ];
  
  const cats = new Set(menus.value.map(m => m.category || 'Lainnya'));
  const catArray = Array.from(cats);
  
  catArray.sort((a, b) => {
    const indexA = categoryOrder.indexOf(a);
    const indexB = categoryOrder.indexOf(b);
    const posA = indexA === -1 ? 999 : indexA;
    const posB = indexB === -1 ? 999 : indexB;
    return posA - posB;
  });
  
  return catArray;
});

const groupedMenus = computed(() => {
  const groups = {};
  categories.value.forEach(cat => {
    groups[cat] = [];
  });
  
  menus.value.forEach(m => {
    const cat = m.category || 'Lainnya';
    if (!groups[cat]) groups[cat] = [];
    groups[cat].push(m);
  });
  
  // Return only non-empty groups, preserving insertion order
  const finalGroups = {};
  categories.value.forEach(cat => {
    if (groups[cat] && groups[cat].length > 0) {
      finalGroups[cat] = groups[cat];
    }
  });
  
  return finalGroups;
});

const filteredGroupedMenus = computed(() => {
  const query = searchQuery.value.trim().toLowerCase();
  if (!query) return groupedMenus.value;

  const result = {};
  Object.keys(groupedMenus.value).forEach(cat => {
    const matchingItems = groupedMenus.value[cat].filter(menu => {
      const matchName = menu.name.toLowerCase().includes(query);
      const matchDesc = menu.description ? menu.description.toLowerCase().includes(query) : false;
      const matchCat = cat.toLowerCase().includes(query);
      return matchName || matchDesc || matchCat;
    });

    if (matchingItems.length > 0) {
      result[cat] = matchingItems;
    }
  });

  return result;
});

const activeCategory = ref('');
let observer = null;
let isManualScrolling = false;
let scrollTimeout = null;

const scrollToCategory = (cat) => {
  activeCategory.value = cat;
  isManualScrolling = true;
  
  if (scrollTimeout) clearTimeout(scrollTimeout);
  scrollTimeout = setTimeout(() => {
    isManualScrolling = false;
  }, 1000); // Resume observer after scroll animation finishes
  
  const el = document.getElementById(`category-${cat}`);
  if (el) {
    // Scroll element to view
    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // Scroll tab horizontally
    const tabEl = document.getElementById(`tab-${cat}`);
    if (tabEl) {
      tabEl.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
  }
};

const setupIntersectionObserver = () => {
  const options = {
    root: null,
    rootMargin: '-150px 0px -60% 0px', // Trigger point near the top of the viewport
    threshold: 0
  };

  observer = new IntersectionObserver((entries) => {
    // Only process if user is not manually clicking tabs
    if (isManualScrolling) return;

    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const id = entry.target.id;
        if (id && id.startsWith('category-')) {
          const newCat = id.replace('category-', '');
          if (activeCategory.value !== newCat) {
            activeCategory.value = newCat;
            
            // Auto scroll horizontal tabs
            const tabEl = document.getElementById(`tab-${newCat}`);
            if (tabEl) {
              tabEl.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
          }
        }
      }
    });
  }, options);
  
  setTimeout(() => {
    categories.value.forEach(cat => {
      const el = document.getElementById(`category-${cat}`);
      if (el) observer.observe(el);
    });
  }, 500); // wait for DOM
};

onUnmounted(() => {
  if (observer) observer.disconnect();
  if (scrollTimeout) clearTimeout(scrollTimeout);
});

onMounted(async () => {
  if (!orderStore.outletData?.id) {
    router.push('/');
    return;
  }
  
  if (!orderStore.customerName || !orderStore.customerEmail) {
    showCustomerModal.value = true;
  }
  
  await fetchMenus();
});

const fetchMenus = async () => {
  try {
    const outletId = orderStore.outletData.id;
    const response = await axios.get(`http://127.0.0.1:8000/api/outlet/${outletId}/menus`);
    if (response.data.success) {
      menus.value = response.data.data;
      if (categories.value.length > 0) {
        activeCategory.value = categories.value[0];
      }
      setupIntersectionObserver();
    }
  } catch (error) {
    console.error("Gagal memuat katalog menu", error);
  } finally {
    isLoading.value = false;
  }
};

const formatPrice = (price) => {
  return Number(price).toLocaleString('id-ID');
};

const openAddModal = (menu) => {
  if (!menu.variants || menu.variants.length === 0) {
    cartStore.addToCart(menu, 1);
    return;
  }
  
  selectedMenu.value = menu;
  modalQuantity.value = 1;
  selectedOptions.value = {};
  
  // Default values for single type variants
  menu.variants.forEach(variant => {
    if (variant.type === 'single' && variant.options.length > 0) {
      selectedOptions.value[variant.name] = variant.options[0].name;
    } else if (variant.type === 'multiple') {
      selectedOptions.value[variant.name] = [];
    }
  });
};

const closeAddModal = () => {
  selectedMenu.value = null;
};

const modalTotalPrice = computed(() => {
  if (!selectedMenu.value) return 0;
  
  let total = Number(selectedMenu.value.price);
  
  if (selectedMenu.value.variants) {
    selectedMenu.value.variants.forEach(variant => {
      const selected = selectedOptions.value[variant.name];
      if (variant.type === 'single' && selected) {
        const option = variant.options.find(o => o.name === selected);
        if (option) total += Number(option.price);
      } else if (variant.type === 'multiple' && Array.isArray(selected)) {
        selected.forEach(selName => {
          const option = variant.options.find(o => o.name === selName);
          if (option) total += Number(option.price);
        });
      }
    });
  }
  
  return total * modalQuantity.value;
});

const confirmAddToCart = () => {
  if (selectedMenu.value) {
    // Clone menu to override price with options
    const menuToAdd = { ...selectedMenu.value };
    // The total base price for one item, excluding quantity
    menuToAdd.price = modalTotalPrice.value / modalQuantity.value;
    
    // Pass the selectedOptions 
    cartStore.addToCart(menuToAdd, modalQuantity.value, selectedOptions.value);
    closeAddModal();
  }
};

const goToCart = () => {
  router.push({ name: 'Checkout' });
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

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}
.modal-fade-enter-active .modal-box,
.modal-fade-leave-active .modal-box {
  transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
.modal-fade-enter-from .modal-box,
.modal-fade-leave-to .modal-box {
  opacity: 0;
  transform: translateY(30px) scale(0.95);
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
