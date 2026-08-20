<template>
  <div 
    class="min-h-screen pb-48 font-sans relative bg-cover bg-center bg-no-repeat bg-fixed"
    :style="{ backgroundImage: `url(${bgDashboard})` }"
  >
    
    <header class="bg-[#E9D8C6] shadow-sm sticky top-0 z-30 border-b-2 border-[#B98B6A]/30">
      <div class="px-6 pt-4 pb-2 flex justify-between items-center">
        <div>
          <img src="@/assets/logonga.png" alt="Logo" class="h-24 sm:h-16 w-auto object-contain" />
        </div>

        <!-- Table Number Badge -->
        <div class="bg-white/90 backdrop-blur-sm px-3.5 py-1.5 rounded-xl border border-[#B98B6A]/30 shadow-sm flex items-center justify-center">
          <span class="text-xs font-extrabold text-[#4B2E2A] tracking-wider uppercase">
            Meja {{ orderStore.nomorMeja || '-' }}
          </span>
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
      <div 
        ref="categoryTabsRef"
        v-if="categories.length > 0" 
        class="flex overflow-x-auto hide-scrollbar px-6 pb-4 pt-1 gap-3 cursor-grab active:cursor-grabbing select-none touch-pan-x"
        @wheel.prevent="handleTabWheel"
        @mousedown="startDrag"
        @mouseleave="stopDrag"
        @mouseup="stopDrag"
        @mousemove="onDrag"
      >
        <button 
          v-for="cat in categories" 
          :key="cat"
          :id="'tab-' + cat"
          @click="handleTabClick(cat)"
          :class="activeCategory === cat ? 'bg-[#4B2E2A] text-white shadow-md' : 'bg-transparent text-[#4B2E2A]/80 border border-[#4B2E2A]/30 hover:bg-[#4B2E2A]/10'"
          class="px-5 py-2 rounded-full font-bold text-sm whitespace-nowrap transition-colors flex-shrink-0"
        >
          {{ cat }}
        </button>
      </div>
    </header>

    <main class="p-6 space-y-6 relative z-10">
      <div v-if="isLoading" class="space-y-12 w-full">
        <div v-for="i in 2" :key="i" class="animate-pulse">
          <!-- Category Title Skeleton -->
          <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-8 bg-[#B98B6A]/30 rounded-full"></div>
            <div class="h-6 bg-[#B98B6A]/30 rounded-full w-32"></div>
          </div>
          
          <div class="space-y-4">
            <div v-for="j in 3" :key="j" class="bg-[#F7F2EC]/80 rounded-3xl p-5 shadow-sm border border-[#B98B6A]/20 flex gap-5 items-center">
              <!-- Image Skeleton -->
              <div class="w-28 h-28 flex-shrink-0 bg-[#E9D8C6] rounded-2xl border border-[#B98B6A]/20"></div>
              
              <!-- Content Skeleton -->
              <div class="flex-1">
                <div class="h-6 bg-[#E9D8C6] rounded-full w-3/4 mb-3"></div>
                <div class="h-4 bg-[#E9D8C6] rounded-full w-full"></div>
                
                <div class="mt-6 flex justify-between items-center">
                  <div class="h-6 bg-[#E9D8C6] rounded-full w-1/3"></div>
                  <div class="h-10 bg-[#B98B6A]/20 rounded-xl w-24"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else-if="menus.length === 0" class="text-center py-16 bg-card rounded-3xl border-2 border-primary/10 shadow-lg">
        <p class="text-textColor/70 text-base font-medium">Menu belum tersedia saat ini.</p>
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
                    @click.stop="openAddModal(menu, $event)"
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

          <!-- Catatan Khusus Pesanan -->
          <div class="pt-4 border-t-2 border-primary/10 space-y-2">
            <label class="font-bold text-textColor text-sm flex items-center justify-between">
              <span class="flex items-center gap-2 text-[#4B2E2A]">
                <svg class="w-4 h-4 text-[#7A4A3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Catatan Pesanan
              </span>
              <span class="text-[11px] font-medium text-[#7A4A3A]/70">Opsional</span>
            </label>
            <textarea 
              v-model="modalNotes" 
              rows="2" 
              placeholder="Contoh: Less ice, manis sedang, pedas sedikit, tanpa sedotan, dll." 
              class="w-full p-3.5 bg-background/50 border-2 border-[#B98B6A]/20 focus:border-primary rounded-2xl text-xs font-medium text-textColor placeholder:text-textColor/40 focus:outline-none focus:bg-background transition-all resize-none shadow-sm"
              maxlength="150"
            ></textarea>
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
          <button @click="confirmAddToCart($event)" class="w-full bg-primary text-cta rounded-2xl py-4 px-6 flex justify-between items-center shadow-xl active:scale-[0.97] transition-all">
            <span class="font-bold text-lg tracking-widest uppercase">Tambah Pesanan</span>
            <span class="font-extrabold text-xl font-heading">Rp {{ formatPrice(modalTotalPrice) }}</span>
          </button>
        </div>
      </div>
    </transition>



    <!-- Pintu Graphic (Fixed at bottom right behind menu cards, in front of background layer) -->
    <div class="fixed bottom-0 right-0 w-[500px] sm:w-[650px] z-0 pointer-events-none opacity-90">
      <img src="@/assets/rhpintu.PNG" alt="Pintu Graphic" class="w-full h-auto object-contain drop-shadow-md" />
    </div>

    <!-- Flying Dots Animation -->
    <div 
      v-for="dot in flyingDots" 
      :key="dot.id"
      class="fixed z-[100] pointer-events-none dot-container"
      :style="{
        '--startX': `${dot.startX}px`,
        '--endX': `${dot.endX}px`,
        top: 0,
        left: 0
      }"
    >
      <div 
        class="w-6 h-6 rounded-full dot-element flex items-center justify-center text-white text-[10px] font-bold shadow-lg"
        :style="{
          '--startY': `${dot.startY}px`,
          '--endY': `${dot.endY}px`
        }"
      >+1</div>
    </div>

    <BottomNav v-if="!selectedMenu" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useOrderStore } from '@/stores/orderStore';
import { useCartStore } from '@/stores/cartStore';
import axios from 'axios';
import BottomNav from '@/components/BottomNav.vue';
import bgDashboard from '@/assets/bgdashboard.jpg';

const router = useRouter();
const orderStore = useOrderStore();
const cartStore = useCartStore();

const menus = ref([]);
const isLoading = ref(true);
const searchQuery = ref('');

const categoryTabsRef = ref(null);
let isDraggingTabs = false;
let startX = 0;
let scrollLeftStart = 0;
let dragMoved = false;
let velocity = 0;
let lastX = 0;
let lastTime = 0;
let animationFrameId = null;

const handleTabWheel = (e) => {
  if (categoryTabsRef.value) {
    categoryTabsRef.value.scrollLeft += e.deltaY * 1.2;
  }
};

const startDrag = (e) => {
  if (!categoryTabsRef.value) return;
  if (animationFrameId) cancelAnimationFrame(animationFrameId);
  isDraggingTabs = true;
  dragMoved = false;
  startX = e.pageX - categoryTabsRef.value.offsetLeft;
  scrollLeftStart = categoryTabsRef.value.scrollLeft;
  lastX = e.pageX;
  lastTime = performance.now();
  velocity = 0;
};

const stopDrag = () => {
  if (!isDraggingTabs) return;
  isDraggingTabs = false;
  
  // Inertia glide momentum
  if (Math.abs(velocity) > 0.08 && categoryTabsRef.value) {
    const momentumScroll = () => {
      if (Math.abs(velocity) < 0.02 || !categoryTabsRef.value) return;
      categoryTabsRef.value.scrollLeft -= velocity * 14;
      velocity *= 0.91; // friction decay
      animationFrameId = requestAnimationFrame(momentumScroll);
    };
    animationFrameId = requestAnimationFrame(momentumScroll);
  }
};

const onDrag = (e) => {
  if (!isDraggingTabs || !categoryTabsRef.value) return;
  const now = performance.now();
  const dt = now - lastTime;
  const currentX = e.pageX;
  
  if (dt > 0) {
    velocity = (currentX - lastX) / dt;
  }
  lastX = currentX;
  lastTime = now;

  const x = currentX - categoryTabsRef.value.offsetLeft;
  const walk = (x - startX);
  if (Math.abs(walk) > 3) {
    dragMoved = true;
  }
  categoryTabsRef.value.scrollLeft = scrollLeftStart - walk;
};

const handleTabClick = (cat) => {
  if (!dragMoved) {
    scrollToCategory(cat);
  }
};

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
const modalNotes = ref('');
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
  selectedMenu.value = menu;
  modalQuantity.value = 1;
  modalNotes.value = '';
  selectedOptions.value = {};
  
  // Default values for single type variants if any
  if (menu.variants && menu.variants.length > 0) {
    menu.variants.forEach(variant => {
      if (variant.type === 'single' && variant.options.length > 0) {
        selectedOptions.value[variant.name] = variant.options[0].name;
      } else if (variant.type === 'multiple') {
        selectedOptions.value[variant.name] = [];
      }
    });
  }
};

const closeAddModal = () => {
  selectedMenu.value = null;
  modalNotes.value = '';
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

const confirmAddToCart = (e) => {
  if (selectedMenu.value) {
    // Clone menu to override price with options
    const menuToAdd = { ...selectedMenu.value };
    // The total base price for one item, excluding quantity
    menuToAdd.price = modalTotalPrice.value / modalQuantity.value;
    
    // Clean options: remove any empty arrays, empty strings, null, undefined
    const cleanOptions = {};
    Object.entries(selectedOptions.value).forEach(([key, val]) => {
      if (Array.isArray(val)) {
        if (val.length > 0) {
          cleanOptions[key] = val;
        }
      } else if (typeof val === 'string') {
        if (val.trim() !== '') {
          cleanOptions[key] = val.trim();
        }
      } else if (val !== null && val !== undefined) {
        cleanOptions[key] = val;
      }
    });
    
    if (modalNotes.value && modalNotes.value.trim()) {
      cleanOptions['Catatan'] = modalNotes.value.trim();
    }
    
    // Pass the options
    cartStore.addToCart(menuToAdd, modalQuantity.value, cleanOptions);
    closeAddModal();
    if (e) animateAddToCart(e);
  }
};

const flyingDots = ref([]);
const animateAddToCart = (e) => {
  if (!e) return;
  const startX = e.clientX;
  const startY = e.clientY;
  
  const id = Date.now();
  flyingDots.value.push({
    id,
    startX,
    startY,
    endX: window.innerWidth / 2,
    endY: window.innerHeight - 30
  });
  
  setTimeout(() => {
    flyingDots.value = flyingDots.value.filter(d => d.id !== id);
  }, 700);
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

.dot-container {
  animation: flyX 0.7s ease-in-out forwards;
}

.dot-element {
  animation: flyY 0.7s ease-in-out forwards; 
}

@keyframes flyX {
  0% { transform: translateX(var(--startX)); }
  100% { transform: translateX(var(--endX)); }
}

@keyframes flyY {
  0% { 
    transform: translateY(var(--startY)) scale(1); 
    opacity: 1; 
    background-color: #4B2E2A;
  }
  30% { 
    transform: translateY(calc(var(--startY) - 150px)) scale(1.4); 
    opacity: 1; 
    background-color: #4B2E2A;
  }
  55% { 
    background-color: #ef4444;
  }
  100% { 
    transform: translateY(var(--endY)) scale(0.2); 
    opacity: 0; 
    background-color: #ef4444;
  }
}

.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
