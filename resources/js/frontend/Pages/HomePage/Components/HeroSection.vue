<template>
  <section class="hero" :style="heroStyle">
    <div class="container">
      <div class="hero-inner">
        <span class="hero-emoji">🍽️</span>
        <h1>
          {{ hero.heading || 'গরম গরম খাবার' }}
          <br />
          <span>{{ hero.subheading || 'দোরগোড়ায় পৌঁছে দিই!' }}</span>
        </h1>
        <p v-if="heroDescText" class="hero-desc-text">{{ heroDescText }}</p>
        <div class="hero-badges">
          <div v-for="(line, i) in heroBadges" :key="i" class="hero-badge">{{ line }}</div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const { hero, heroStyle, heroDescText, heroBadges } = storeToRefs(useHomePageStore());
</script>
