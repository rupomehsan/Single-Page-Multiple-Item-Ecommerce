<template>
  <header>
    <div class="logo">
      <img v-if="siteData.logoUrl" :src="siteData.logoUrl" class="logo-img" :alt="siteData.name" />
      <span v-else>🍛</span>
      <span>{{ siteData.name }}<span class="dot">.</span></span>
    </div>
  </header>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const { siteData } = storeToRefs(useHomePageStore());
</script>
