import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useOrderStore = defineStore('order', () => {
    const kodeOutlet = ref(null);
    const nomorMeja = ref(null);
    const outletData = ref(null);
    const customerName = ref('');
    const customerEmail = ref('');

    function resetCustomerData() {
        customerName.value = '';
        customerEmail.value = '';
        localStorage.removeItem('customer_name');
        localStorage.removeItem('customer_email');
        sessionStorage.removeItem('customer_name');
        sessionStorage.removeItem('customer_email');
    }

    function setOutletData(kode, data) {
        kodeOutlet.value = kode;
        outletData.value = data;
        localStorage.setItem('kode_outlet', kode || '');
        localStorage.setItem('outlet_data', JSON.stringify(data || null));
    }

    function setNomorMeja(nomor) {
        nomorMeja.value = nomor;
        localStorage.setItem('nomor_meja', nomor || '');
    }

    function setCustomerData(name, email) {
        customerName.value = name || '';
        customerEmail.value = email || '';
        sessionStorage.setItem('customer_name', name || '');
        sessionStorage.setItem('customer_email', email || '');
    }

    function clearData() {
        kodeOutlet.value = null;
        nomorMeja.value = null;
        outletData.value = null;
        customerName.value = '';
        customerEmail.value = '';
        localStorage.removeItem('kode_outlet');
        localStorage.removeItem('nomor_meja');
        localStorage.removeItem('outlet_data');
        localStorage.removeItem('customer_name');
        localStorage.removeItem('customer_email');
        sessionStorage.clear();
    }

    return { 
        kodeOutlet, 
        nomorMeja, 
        outletData, 
        customerName,
        customerEmail,
        resetCustomerData,
        setOutletData, 
        setNomorMeja, 
        setCustomerData,
        clearData 
    };
});
