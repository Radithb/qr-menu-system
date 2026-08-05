import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useCartStore = defineStore('cart', () => {
    const items = ref([]); // Format: { menu_id, name, price, quantity, options: {}, subtotal }

    // Computed properties untuk auto-kalkulasi
    const totalItems = computed(() => items.value.reduce((total, item) => total + item.quantity, 0));
    const totalPrice = computed(() => items.value.reduce((total, item) => total + item.subtotal, 0));

    // Fungsi menambah ke keranjang
    function addToCart(menu, quantity = 1, options = {}) {
        // Cek apakah item dengan opsi yang SAMA PERSIS sudah ada di keranjang
        const existingItemIndex = items.value.findIndex(
            item => item.menu_id === menu.id && JSON.stringify(item.options) === JSON.stringify(options)
        );

        if (existingItemIndex !== -1) {
            // Jika ada, tambah quantity saja
            items.value[existingItemIndex].quantity += quantity;
            items.value[existingItemIndex].subtotal = items.value[existingItemIndex].quantity * items.value[existingItemIndex].price;
        } else {
            // Jika belum ada / opsinya beda (misal: satu Es, satu Panas), buat baris baru
            items.value.push({
                menu_id: menu.id,
                name: menu.name,
                price: parseFloat(menu.price),
                quantity: quantity,
                options: options,
                subtotal: parseFloat(menu.price) * quantity
            });
        }
    }

    function clearCart() {
        items.value = [];
    }

    function updateQuantity(index, newQuantity) {
        if (index >= 0 && index < items.value.length) {
            if (newQuantity <= 0) {
                items.value.splice(index, 1);
            } else {
                items.value[index].quantity = newQuantity;
                items.value[index].subtotal = items.value[index].price * newQuantity;
            }
        }
    }

    return { items, totalItems, totalPrice, addToCart, clearCart, updateQuantity };
});
