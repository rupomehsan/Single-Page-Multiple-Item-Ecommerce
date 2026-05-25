<template>
  <div class="modal-overlay" :class="{ open: modalOpen }">
    <div class="modal">
      <div class="modal-head">
        <h3>📋 অর্ডার কনফার্ম করুন</h3>
        <button class="close-btn" @click="store.closeModal()">✕</button>
      </div>

      <!-- Success screen -->
      <div class="modal-body" v-if="orderSuccess">
        <div class="success-screen">
          <div class="tick">🎉</div>
          <h3>অর্ডার সফল হয়েছে!</h3>
          <p>
            আপনার অর্ডার পেয়েছি।<br>
            ৩০-৪৫ মিনিটের মধ্যে পৌঁছে যাবে।<br><br>
            <strong>ধন্যবাদ, {{ modal.name }} ভাই/আপু!</strong><br><br>
            যোগাযোগের জন্য: 📞 {{ siteData.phone }}
          </p>
        </div>
      </div>

      <!-- Order form -->
      <div class="modal-body" v-else>
        <div class="order-summary">
          <div v-for="item in cartItems" :key="item.id" class="order-line">
            <span>{{ item.emoji }} {{ item.name }} ×{{ cart[item.id] }}</span>
            <strong>৳{{ item.price * cart[item.id] }}</strong>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>আপনার নাম *</label>
            <input type="text" v-model="modal.name" placeholder="পূর্ণ নাম" />
          </div>
          <div class="form-group">
            <label>মোবাইল নম্বর *</label>
            <input type="tel" v-model="modal.phone" placeholder="01XXXXXXXXX" />
          </div>
        </div>
        <div class="form-group">
          <label>ডেলিভারি ঠিকানা *</label>
          <textarea v-model="modal.address" placeholder="বাসার ঠিকানা, এলাকা, রোড নম্বর…"></textarea>
        </div>
        <div class="form-group">
          <label>শিপিং</label>
          <select v-model="modal.shipping">
            <option v-for="opt in shippingOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
          </select>
        </div>
        <div class="form-group">
          <label>বিশেষ নির্দেশনা (ঐচ্ছিক)</label>
          <input type="text" v-model="modal.note" placeholder="যেমন: কম ঝাল, অতিরিক্ত সস ইত্যাদি" />
        </div>
        <div class="grand-total-bar">
          <span>মোট পরিমাণ</span>
          <span>৳{{ cartTotal + Number(modal.shipping) }}</span>
        </div>
        <button class="place-btn" @click="store.placeOrder()" :disabled="orderSubmitting">
          {{ orderSubmitting ? '⏳ অপেক্ষা করুন…' : '✅ অর্ডার দিন' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const store = useHomePageStore();
const { modalOpen, orderSuccess, modal, cartItems, cart, cartTotal, shippingOptions, orderSubmitting, siteData } = storeToRefs(store);
</script>
