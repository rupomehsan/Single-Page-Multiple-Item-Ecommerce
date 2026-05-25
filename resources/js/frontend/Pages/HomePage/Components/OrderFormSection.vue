<template>
  <div class="order-form-section" id="orderForm">
    <div class="container">
      <div class="order-form-inner">
        <div class="of-title">🛒 অর্ডার করুন</div>
        <div class="of-sub">ক্যাশ অন ডেলিভারি · কোনো অগ্রিম পেমেন্ট নেই</div>

        <!-- Cart items box -->
        <div class="of-box">
          <div class="of-box-header">🛍 আপনার নির্বাচিত পণ্য</div>
          <div class="of-box-body">
            <div v-if="productsLoading" class="of-loading">পণ্য লোড হচ্ছে…</div>
            <div v-else-if="cartItems.length === 0" class="of-empty-cart">
              <div class="of-empty-icon">🛒</div>
              <p>কার্টে কোনো পণ্য নেই।<br />উপরের মেনু থেকে পণ্য বেছে নিন।</p>
              <a href="#" @click.prevent="store.scrollToMenu()" class="of-goto-menu"
                >↑ মেনু দেখুন</a
              >
            </div>
            <div v-for="item in cartItems" :key="item.id" class="of-product-row">
              <div class="ofp-thumb">
                <img
                  v-if="item.image"
                  :src="item.image"
                  :alt="item.name"
                  class="ofp-img"
                />
                <span v-else class="ofp-emoji">{{ item.emoji }}</span>
              </div>
              <span class="ofp-name">{{ item.name }}</span>
              <div class="ofp-controls">
                <button class="ofp-btn" @click="store.removeOne(item.id)">−</button>
                <span class="ofp-num">{{ cart[item.id] }}</span>
                <button class="ofp-btn" @click="store.addOne(item.id)">+</button>
              </div>
              <span class="ofp-price">৳{{ item.price * cart[item.id] }}</span>
            </div>
          </div>
        </div>

        <!-- Customer info -->
        <div class="of-row2">
          <div class="of-field">
            <label>নাম *</label>
            <input type="text" v-model="orderForm.name" placeholder="আপনার পূর্ণ নাম" />
          </div>
          <div class="of-field">
            <label>মোবাইল *</label>
            <input type="tel" v-model="orderForm.phone" placeholder="01XXXXXXXXX" />
          </div>
        </div>

        <div class="of-field of-full" style="margin-bottom: 16px">
          <label>ঠিকানা *</label>
          <textarea
            v-model="orderForm.address"
            placeholder="বাসার ঠিকানা, এলাকা, থানা…"
          ></textarea>
        </div>

        <div class="of-field of-full" style="margin-bottom: 16px">
          <label>বিশেষ নির্দেশনা (ঐচ্ছিক)</label>
          <input
            type="text"
            v-model="orderForm.notes"
            placeholder="যেমন: কম ঝাল, অতিরিক্ত সস ইত্যাদি"
          />
        </div>

        <!-- Promo Code -->
        <div class="of-promo-row">
          <input
            type="text"
            v-model="promoCode"
            placeholder="প্রোমো কোড লিখুন (যদি থাকে)"
            class="of-promo-input"
            :disabled="promoApplied"
            @keyup.enter="store.applyPromo()"
          />
          <button
            class="of-promo-btn"
            @click="store.applyPromo()"
            :disabled="promoLoading || promoApplied"
          >
            {{ promoLoading ? "…" : promoApplied ? "✓ প্রযোজ্য" : "প্রয়োগ করুন" }}
          </button>
        </div>
        <div
          v-if="promoMsg"
          class="of-promo-msg"
          :class="promoApplied ? 'promo-ok' : 'promo-err'"
        >
          {{ promoMsg }}
        </div>

        <!-- Shipping -->
        <div class="of-shipping">
          <span>🚚 ডেলিভারি এলাকা</span>
          <select v-model="ofShip">
            <option v-for="opt in shippingOptions" :key="opt.value" :value="opt.value">
              {{ opt.label }}
            </option>
          </select>
          <span class="of-ship-price">৳ {{ ofShip }}</span>
        </div>

        <div class="of-dash"></div>

        <div v-if="promoApplied" class="of-discount-row">
          <span>ছাড় ({{ promoCodeApplied }})</span>
          <span class="of-discount-amt">− ৳ {{ promoDiscount }}</span>
        </div>

        <div class="of-total-row">
          <span>মোট পরিমাণ</span>
          <span class="of-total-price">৳ {{ ofGrandTotal }}</span>
        </div>

        <button class="of-submit" @click="store.ofSubmit()" :disabled="orderSubmitting">
          {{ orderSubmitting ? "⏳ অপেক্ষা করুন…" : "✅ অর্ডার নিশ্চিত করুন" }}
        </button>

        <div v-if="cartItems.length > 0" class="of-or-divider">
          <span class="of-or-line"></span>
          <span class="of-or-text">অথবা</span>
          <span class="of-or-line"></span>
        </div>

        <button
          v-if="cartItems.length > 0"
          class="of-wa-btn"
          @click="store.openWhatsApp()"
        >
          <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
            <path
              d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
            />
          </svg>
          WhatsApp এ অর্ডার করুন
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { storeToRefs } from "pinia";
import { useHomePageStore } from "../Store/index";

const store = useHomePageStore();
const {
  productsLoading,
  cartItems,
  cart,
  orderForm,
  promoCode,
  promoLoading,
  promoApplied,
  promoMsg,
  promoDiscount,
  promoCodeApplied,
  ofShip,
  shippingOptions,
  ofGrandTotal,
  orderSubmitting,
} = storeToRefs(store);
</script>
