<template>
  <section class="reviews-section">
    <div class="container">
      <h2>সন্তুষ্টিভূত কাস্টমারদের<br />রিভিউ গুলো <span class="pink">আমাদের প্রশান্তি দেয়</span></h2>
      <div class="reviews-badge">{{ testimonials.length > 0 ? testimonials.length + '+' : '0' }} রিভিউ</div>

      <div class="slider-outer">
        <button class="sl-arrow sl-prev" @click="store.slMove(-1)">&#8249;</button>
        <div class="slider-wrap">
          <div
            class="sl-track"
            :style="{ transform: 'translateX(-' + slCurrent * 100 + '%)' }"
            @touchstart.passive="store.onTouchStart"
            @touchend="store.onTouchEnd"
          >
            <div v-for="(slide, si) in testimonialSlides" :key="si" class="sl-slide">
              <div v-for="(item, ii) in slide" :key="ii" class="review-card">
                <img v-if="item.imageUrl" :src="item.imageUrl" :alt="item.customer_name || 'Customer'" class="review-avatar-img" />
                <div v-else class="review-avatar" :style="{ background: avatarGradients[(si * 2 + ii) % avatarGradients.length] }">
                  {{ (item.customer_name || '★').charAt(0) }}
                </div>
                <div class="review-name">{{ item.customer_name || 'Customer' }}</div>
                <div class="review-stars">{{ store.ratingStars(item.rating) }}</div>
                <div class="review-text">{{ item.testimonial_text || item.description || '' }}</div>
              </div>
            </div>
          </div>
        </div>
        <button class="sl-arrow sl-next" @click="store.slMove(1)">&#8250;</button>
      </div>

      <div class="sl-dots">
        <span
          v-for="(_, idx) in testimonialSlides"
          :key="idx"
          class="sl-dot"
          :class="{ active: idx === slCurrent }"
          @click="store.slGo(idx)"
        ></span>
      </div>
    </div>
  </section>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const store = useHomePageStore();
const { testimonials, slCurrent, avatarGradients, testimonialSlides } = storeToRefs(store);
</script>
