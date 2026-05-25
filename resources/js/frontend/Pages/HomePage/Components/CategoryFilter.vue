<template>
  <div class="menu-filters">
    <div class="container">
      <div class="filters-bar">
        <button
          class="filter-btn"
          :class="{ active: currentCategory === 'all' }"
          @click="store.setCategory('all')"
        >সব</button>
        <button
          v-for="cat in categories"
          :key="cat.id"
          class="filter-btn"
          :class="{ active: String(currentCategory) === String(cat.id) }"
          @click="store.setCategory(cat.id)"
        >{{ cat.name }}</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const store = useHomePageStore();
const { categories, currentCategory } = storeToRefs(store);
</script>
