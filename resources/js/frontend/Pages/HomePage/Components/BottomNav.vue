<template>
  <nav class="bottom-nav">
    <svg class="bn-curve-bg" viewBox="0 0 400 64" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0,1 L152,1 C168,1 178,31 200,31 C222,31 232,1 248,1 L400,1 L400,64 L0,64 Z" fill="rgba(255,255,255,0.97)"/>
      <path d="M0,1 L152,1 C168,1 178,31 200,31 C222,31 232,1 248,1 L400,1" fill="none" stroke="rgba(0,0,0,0.07)" stroke-width="1"/>
    </svg>

    <a class="bn-item bn-home" :class="{ active: activeNav === 'home' }" href="#" @click.prevent="store.setNav('home')">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#e63946" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
      </svg>
      <span class="bn-label">হোম</span>
    </a>

    <a class="bn-item bn-msg" :class="{ active: activeNav === 'msg' }" href="#" @click.prevent="store.openMessenger()">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#0084ff" d="M12 2C6.477 2 2 6.145 2 11.243c0 3.06 1.558 5.78 3.99 7.57v3.687l3.574-1.96c.955.264 1.966.407 3.013.407 5.523 0 10-4.145 10-9.257C22 6.145 17.523 2 12 2zm.982 12.465l-2.546-2.71-4.971 2.71 5.467-5.806 2.614 2.71 4.9-2.71-5.464 5.806z"/>
      </svg>
      <span class="bn-label">Messenger</span>
    </a>

    <div class="bn-center-wrap">
      <button class="bn-center" @click="store.openCart()">
        <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
          <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM5.2 5H2V3H0v2h2l3.6 7.59L4.25 15c-.16.28-.25.61-.25.96C4 17.1 4.9 18 6 18h14v-2H6.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0023.46 5H5.2z" fill="#fff"/>
        </svg>
        <span class="bn-badge" :class="{ bump: cartBump }" v-show="cartItemCount > 0">{{ cartItemCount }}</span>
      </button>
    </div>

    <a class="bn-item bn-wa" :class="{ active: activeNav === 'wa' }" href="#" @click.prevent="store.openWhatsApp()">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#25D366" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
      <span class="bn-label">WhatsApp</span>
    </a>

    <a class="bn-item bn-order" :class="{ active: activeNav === 'order' }" href="#orderForm" @click="store.setNav('order')">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#ff6f00" d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm0 10c-1.66 0-3-1.34-3-3h2c0 .55.45 1 1 1s1-.45 1-1h2c0 1.66-1.34 3-3 3z"/>
      </svg>
      <span class="bn-label">অর্ডার</span>
    </a>
  </nav>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const store = useHomePageStore();
const { activeNav, cartBump, cartItemCount } = storeToRefs(store);
</script>
