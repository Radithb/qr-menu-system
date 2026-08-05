import { createRouter, createWebHistory } from 'vue-router';
import InputCodeView from '../views/InputCodeView.vue';
import MenuKatalogView from '../views/MenuKatalogView.vue';
import CheckoutView from '../views/CheckoutView.vue';
import KasirView from '../views/KasirView.vue';
import ProfilView from '../views/ProfilView.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'Home',
      component: InputCodeView
    },
    {
      path: '/menu',
      name: 'MenuKatalog',
      component: MenuKatalogView
    },
    {
      path: '/cart',
      name: 'Checkout',
      component: CheckoutView
    },
    {
      path: '/profil',
      name: 'Profil',
      component: ProfilView
    },
    {
      path: '/kasir',
      name: 'Kasir',
      component: KasirView
    }
  ]
});

export default router;
