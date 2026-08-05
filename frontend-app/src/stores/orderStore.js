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
    }

    function setOutletData(kode, data) {
        kodeOutlet.value = kode;
        outletData.value = data;
        resetCustomerData();
    }

    function setNomorMeja(nomor) {
        nomorMeja.value = nomor;
    }

    function setCustomerData(name, email) {
        customerName.value = name;
        customerEmail.value = email;
    }

    function clearData() {
        kodeOutlet.value = null;
        nomorMeja.value = null;
        outletData.value = null;
        customerName.value = '';
        customerEmail.value = '';
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
