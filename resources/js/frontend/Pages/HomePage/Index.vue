<template>
  <Head :title="siteData.name" />
  <AppHeader />
  <BottomNav />
  <HeroSection />
  <CategoryFilter />
  <MenuSection />
  <ReviewsSlider />
  <OrderFormSection />
  <OrderGuide />
  <AppFooter />
  <CartDrawer />
  <OrderModal />
  <ToastNotification />
</template>

<script setup>
import './Css/Style.css';
import { onMounted, onBeforeUnmount } from 'vue';
import { storeToRefs } from 'pinia';
import { useHomePageStore } from './Store/index';

import AppHeader        from './Components/AppHeader.vue';
import BottomNav        from './Components/BottomNav.vue';
import HeroSection      from './Components/HeroSection.vue';
import CategoryFilter   from './Components/CategoryFilter.vue';
import MenuSection      from './Components/MenuSection.vue';
import ReviewsSlider    from './Components/ReviewsSlider.vue';
import OrderFormSection from './Components/OrderFormSection.vue';
import OrderGuide       from './Components/OrderGuide.vue';
import AppFooter        from './Components/AppFooter.vue';
import CartDrawer       from './Components/CartDrawer.vue';
import OrderModal       from './Components/OrderModal.vue';
import ToastNotification from './Components/ToastNotification.vue';

const store = useHomePageStore();
const { siteData } = storeToRefs(store);

onMounted(async () => {
  await store.init();
});

onBeforeUnmount(() => {
  store.cleanup();
});
</script>
