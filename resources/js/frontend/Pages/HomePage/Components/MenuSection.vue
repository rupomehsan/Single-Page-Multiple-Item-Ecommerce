<template>
  <div class="menu-section">
    <div class="container">
      <div class="section-title">🍴 আমাদের মেনু</div>

      <!-- Skeleton while loading -->
      <div class="menu-grid" v-if="productsLoading">
        <div v-for="n in 6" :key="n" class="food-card sk-card">
          <div class="food-card-img sk-img"></div>
          <div class="food-card-body">
            <div class="sk-line sk-w70"></div>
            <div class="sk-line sk-w100 sk-mt6"></div>
            <div class="sk-line sk-w40 sk-mt10"></div>
          </div>
        </div>
      </div>

      <!-- Actual menu grid -->
      <div class="menu-grid" v-else>
        <div v-if="filteredMenu.length === 0" class="no-products">
          <p>এই ক্যাটাগরিতে কোনো পণ্য পাওয়া যায়নি।</p>
        </div>
        <div v-for="item in filteredMenu" :key="item.id" class="food-card">
          <div class="food-card-img" :style="{ background: item.bg }">
            <div v-if="item.badge" class="badge">{{ item.badge }}</div>
            <img v-if="item.image" :src="item.image" :alt="item.name" class="food-img-cover" />
            <span v-else class="food-emoji">{{ item.emoji }}</span>
          </div>
          <div class="food-card-body">
            <div class="food-name">{{ item.name }}</div>
            <div class="food-desc">{{ item.desc }}</div>
            <div class="food-footer">
              <div class="food-price-wrap">
                <span v-if="item.oldPrice" class="food-old-price">৳{{ item.oldPrice }}</span>
                <span class="food-price">৳{{ item.price }}</span>
              </div>
              <button v-if="!cart[item.id]" class="add-btn" @click="store.addOne(item.id)" title="কার্টে যোগ করুন">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                  <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
              </button>
              <div v-else class="item-counter">
                <button class="counter-btn counter-btn--minus" @click="store.removeOne(item.id)">−</button>
                <span class="counter-num">{{ cart[item.id] }}</span>
                <button class="counter-btn counter-btn--plus" @click="store.addOne(item.id)">+</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const store = useHomePageStore();
const { productsLoading, cart, filteredMenu } = storeToRefs(store);
</script>
