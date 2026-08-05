import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useOrderStore = defineStore('order', () => {
    const kodeOutlet = ref(null);
    const nomorMeja = ref(null);
    const outletData = ref(null);
    const customerName = ref(localStorage.getItem('customer_name') || '');
    const customerEmail = ref(localStorage.getItem('customer_email') || '');

    function setOutletData(kode, data) {
        kodeOutlet.value = kode;
        outletData.value = data;
    }

    function setNomorMeja(nomor) {
        nomorMeja.value = nomor;
    }

    function setCustomerData(name, email) {
        customerName.value = name;
        customerEmail.value = email;
        localStorage.setItem('customer_name', name);
        localStorage.setItem('customer_email', email);
    }

    function clearData() {
        kodeOutlet.value = null;
        nomorMeja.value = null;
        outletData.value = null;
        customerName.value = '';
        customerEmail.value = '';
        localStorage.removeItem('customer_name');
        localStorage.removeItem('customer_email');
    }

    return { 
        kodeOutlet, 
        nomorMeja, 
        outletData, 
        customerName,
        customerEmail,
        setOutletData, 
        setNomorMeja, 
        setCustomerData,
        clearData 
    };
});
