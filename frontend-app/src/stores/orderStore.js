import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useOrderStore = defineStore('order', () => {
    const kodeOutlet = ref(null);
    const nomorMeja = ref(null);
    const outletData = ref(null);

    function setOutletData(kode, data) {
        kodeOutlet.value = kode;
        outletData.value = data;
    }

    function setNomorMeja(nomor) {
        nomorMeja.value = nomor;
    }

    function clearData() {
        kodeOutlet.value = null;
        nomorMeja.value = null;
        outletData.value = null;
    }

    return { 
        kodeOutlet, 
        nomorMeja, 
        outletData, 
        setOutletData, 
        setNomorMeja, 
        clearData 
    };
});
