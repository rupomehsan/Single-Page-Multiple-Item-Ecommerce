<template>
  <footer>
    <div class="container">
      <div class="footer-inner">
        <div class="footer-brand">
          <img v-if="siteData.logoUrl" :src="siteData.logoUrl" class="footer-logo" :alt="siteData.name" />
          <span v-else class="footer-logo-emoji">🍛</span>
          <span class="footer-name">{{ siteData.name }}<span class="footer-dot">.</span></span>
        </div>
        <div class="footer-divider"></div>
        <div class="footer-info">
          <div class="footer-info-item">
            <span class="footer-info-icon">📞</span>
            <span>{{ siteData.phone }}</span>
          </div>
          <div class="footer-info-item">
            <span class="footer-info-icon">📍</span>
            <span>{{ siteData.address }}</span>
          </div>
          <div class="footer-info-item">
            <span class="footer-info-icon">🕐</span>
            <span>{{ siteData.hours }}</span>
          </div>
        </div>
        <div class="footer-divider"></div>
        <div class="footer-copy">
          &copy; {{ new Date().getFullYear() }} {{ siteData.name }} · সর্বস্বত্ব সংরক্ষিত
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const { siteData } = storeToRefs(useHomePageStore());
</script>
