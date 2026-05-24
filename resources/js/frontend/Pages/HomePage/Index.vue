<template>
  <!-- ══ HEADER ══ -->
  <header>
    <div class="logo">
      <img v-if="siteData.logoUrl" :src="siteData.logoUrl" class="logo-img" :alt="siteData.name" />
      <span v-else>🍛</span>
      <span>{{ siteData.name }}<span class="dot">.</span></span>
    </div>
  </header>

  <!-- ══ BOTTOM NAV ══ -->
  <nav class="bottom-nav">
    <!-- Home -->
    <a class="bn-item bn-home" :class="{ active: activeNav === 'home' }" href="#" @click.prevent="setNav('home')">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#e63946" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
      </svg>
      <span class="bn-label">হোম</span>
    </a>

    <!-- Messenger brand icon -->
    <a class="bn-item bn-msg" :class="{ active: activeNav === 'msg' }" :href="messengerUrl" target="_blank" @click="setNav('msg')">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#0084ff" d="M12 2C6.477 2 2 6.145 2 11.243c0 3.06 1.558 5.78 3.99 7.57v3.687l3.574-1.96c.955.264 1.966.407 3.013.407 5.523 0 10-4.145 10-9.257C22 6.145 17.523 2 12 2zm.982 12.465l-2.546-2.71-4.971 2.71 5.467-5.806 2.614 2.71 4.9-2.71-5.464 5.806z"/>
      </svg>
      <span class="bn-label">Messenger</span>
    </a>

    <!-- Cart -->
    <div class="bn-center-wrap">
      <button class="bn-center" @click="openCart">
        <svg viewBox="0 0 24 24" fill="currentColor" width="26" height="26">
          <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM5.2 5H2V3H0v2h2l3.6 7.59L4.25 15c-.16.28-.25.61-.25.96C4 17.1 4.9 18 6 18h14v-2H6.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H19c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0023.46 5H5.2z" fill="#fff"/>
        </svg>
        <span class="bn-badge" :class="{ bump: cartBump }" v-show="cartItemCount > 0">{{ cartItemCount }}</span>
      </button>
    </div>

    <!-- WhatsApp brand icon -->
    <a class="bn-item bn-wa" :class="{ active: activeNav === 'wa' }" :href="whatsappUrl" target="_blank" @click="setNav('wa')">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#25D366" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
      </svg>
      <span class="bn-label">WhatsApp</span>
    </a>

    <!-- Order -->
    <a class="bn-item bn-order" :class="{ active: activeNav === 'order' }" href="#orderForm" @click="setNav('order')">
      <svg class="bn-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path fill="#ff6f00" d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm0 10c-1.66 0-3-1.34-3-3h2c0 .55.45 1 1 1s1-.45 1-1h2c0 1.66-1.34 3-3 3z"/>
      </svg>
      <span class="bn-label">অর্ডার</span>
    </a>
  </nav>

  <!-- ══ HERO ══ -->
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

  <!-- ══ CATEGORY FILTER ══ -->
  <div class="menu-filters">
    <div class="container">
      <div class="filters-bar">
        <button class="filter-btn" :class="{ active: currentCategory === 'all' }" @click="setCategory('all')">সব</button>
        <button
          v-for="cat in categories"
          :key="cat.id"
          class="filter-btn"
          :class="{ active: String(currentCategory) === String(cat.id) }"
          @click="setCategory(cat.id)"
        >{{ cat.name }}</button>
      </div>
    </div>
  </div>

  <!-- ══ MENU ══ -->
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
      <div
        v-for="item in filteredMenu"
        :key="item.id"
        class="food-card"
      >
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
            <button v-if="!cart[item.id]" class="add-btn" @click="addOne(item.id)" title="কার্টে যোগ করুন">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
              </svg>
            </button>
            <div v-else class="item-counter">
              <button class="counter-btn counter-btn--minus" @click="removeOne(item.id)">−</button>
              <span class="counter-num">{{ cart[item.id] }}</span>
              <button class="counter-btn counter-btn--plus" @click="addOne(item.id)">+</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div><!-- /.container -->
  </div>

  <!-- ══ REVIEWS SLIDER ══ -->
  <section class="reviews-section">
    <div class="container">
    <h2>সন্তুষ্টিভূত কাস্টমারদের<br />রিভিউ গুলো <span class="pink">আমাদের প্রশান্তি দেয়</span></h2>
    <div class="reviews-badge">{{ testimonials.length > 0 ? testimonials.length + '+' : '0' }} রিভিউ</div>

    <div class="slider-outer">
      <button class="sl-arrow sl-prev" @click="slMove(-1)">&#8249;</button>
      <div class="slider-wrap">
        <div
          class="sl-track"
          :style="{ transform: 'translateX(-' + slCurrent * 100 + '%)' }"
          @touchstart.passive="onTouchStart"
          @touchend="onTouchEnd"
        >
          <div v-for="(slide, si) in testimonialSlides" :key="si" class="sl-slide">
            <div v-for="(item, ii) in slide" :key="ii" class="review-card">
              <img v-if="item.imageUrl" :src="item.imageUrl" :alt="item.customer_name || 'Customer'" class="review-avatar-img" />
              <div v-else class="review-avatar" :style="{ background: avatarGradients[(si * 2 + ii) % avatarGradients.length] }">
                {{ (item.customer_name || '★').charAt(0) }}
              </div>
              <div class="review-name">{{ item.customer_name || 'Customer' }}</div>
              <div class="review-stars">{{ ratingStars(item.rating) }}</div>
              <div class="review-text">{{ item.testimonial_text || item.description || '' }}</div>
            </div>
          </div>
        </div>
      </div>
      <button class="sl-arrow sl-next" @click="slMove(1)">&#8250;</button>
    </div>

    <div class="sl-dots">
      <span
        v-for="(_, idx) in testimonialSlides"
        :key="idx"
        class="sl-dot"
        :class="{ active: idx === slCurrent }"
        @click="slGo(idx)"
      ></span>
    </div>
    </div><!-- /.container -->
  </section>

  <!-- ══ ORDER FORM SECTION ══ -->
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
            <p>কার্টে কোনো পণ্য নেই।<br>উপরের মেনু থেকে পণ্য বেছে নিন।</p>
            <a href="#" @click.prevent="scrollToMenu" class="of-goto-menu">↑ মেনু দেখুন</a>
          </div>
          <div
            v-for="item in cartItems"
            :key="item.id"
            class="of-product-row"
          >
            <div class="ofp-thumb">
              <img v-if="item.image" :src="item.image" :alt="item.name" class="ofp-img" />
              <span v-else class="ofp-emoji">{{ item.emoji }}</span>
            </div>
            <span class="ofp-name">{{ item.name }}</span>
            <div class="ofp-controls">
              <button class="ofp-btn" @click="removeOne(item.id)">−</button>
              <span class="ofp-num">{{ cart[item.id] }}</span>
              <button class="ofp-btn" @click="addOne(item.id)">+</button>
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

      <div class="of-field of-full" style="margin-bottom:16px">
        <label>ঠিকানা *</label>
        <textarea v-model="orderForm.address" placeholder="বাসার ঠিকানা, এলাকা, থানা…"></textarea>
      </div>

      <div class="of-field of-full" style="margin-bottom:16px">
        <label>বিশেষ নির্দেশনা (ঐচ্ছিক)</label>
        <input type="text" v-model="orderForm.notes" placeholder="যেমন: কম ঝাল, অতিরিক্ত সস ইত্যাদি" />
      </div>

      <!-- Promo Code -->
      <div class="of-promo-row">
        <input
          type="text"
          v-model="promoCode"
          placeholder="প্রোমো কোড লিখুন (যদি থাকে)"
          class="of-promo-input"
          :disabled="promoApplied"
          @keyup.enter="applyPromo"
        />
        <button
          class="of-promo-btn"
          @click="applyPromo"
          :disabled="promoLoading || promoApplied"
        >
          {{ promoLoading ? '…' : promoApplied ? '✓ প্রযোজ্য' : 'প্রয়োগ করুন' }}
        </button>
      </div>
      <div v-if="promoMsg" class="of-promo-msg" :class="promoApplied ? 'promo-ok' : 'promo-err'">
        {{ promoMsg }}
      </div>

      <!-- Shipping -->
      <div class="of-shipping">
        <span>🚚 ডেলিভারি এলাকা</span>
        <select v-model="ofShip">
          <option v-for="opt in shippingOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
        </select>
        <span class="of-ship-price">৳ {{ ofShip }}</span>
      </div>

      <div class="of-dash"></div>

      <!-- Discount row (only when promo applied) -->
      <div v-if="promoApplied" class="of-discount-row">
        <span>ছাড় ({{ promoCodeApplied }})</span>
        <span class="of-discount-amt">− ৳ {{ promoDiscount }}</span>
      </div>

      <div class="of-total-row">
        <span>মোট পরিমাণ</span>
        <span class="of-total-price">৳ {{ ofGrandTotal }}</span>
      </div>

      <button class="of-submit" @click="ofSubmit" :disabled="orderSubmitting">
        {{ orderSubmitting ? '⏳ অপেক্ষা করুন…' : '✅ অর্ডার নিশ্চিত করুন' }}
      </button>
    </div>
    </div><!-- /.container -->
  </div>

  <!-- ══ FOOTER ══ -->
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
    </div><!-- /.container -->
  </footer>

  <!-- ══ CART DRAWER ══ -->
  <div class="overlay" :class="{ open: cartOpen }" @click="closeCart"></div>
  <div class="cart-drawer" :class="{ open: cartOpen }">
    <div class="cart-header">
      <h2>🛒 আপনার কার্ট</h2>
      <button class="close-btn" @click="closeCart">✕</button>
    </div>
    <div class="cart-body">
      <div v-if="cartItems.length === 0" class="cart-empty">
        <div class="empty-icon">🛒</div>
        <p>কার্ট খালি আছে<br>মেনু থেকে আইটেম যোগ করুন</p>
      </div>
      <div v-for="item in cartItems" :key="item.id" class="cart-item">
        <div class="cart-item-thumb">
          <img v-if="item.image" :src="item.image" :alt="item.name" class="cart-item-img" />
          <span v-else class="cart-item-emoji">{{ item.emoji }}</span>
        </div>
        <div class="cart-item-info">
          <div class="cart-item-name">{{ item.name }}</div>
          <div class="cart-item-price">৳{{ item.price }} × {{ cart[item.id] }} = ৳{{ item.price * cart[item.id] }}</div>
        </div>
        <div class="cart-item-controls">
          <button class="ci-btn" @click="removeOne(item.id)">−</button>
          <span class="ci-num">{{ cart[item.id] }}</span>
          <button class="ci-btn" @click="addOne(item.id)">+</button>
        </div>
      </div>
    </div>
    <div class="cart-footer">
      <div class="subtotal-row"><span>ডেলিভারি চার্জ</span><span>বিনামূল্যে ✅</span></div>
      <div class="total-row"><span>মোট</span><span>৳{{ cartTotal }}</span></div>
      <button class="checkout-btn" :disabled="cartItems.length === 0" @click="openOrderModal">
        অর্ডার করুন →
      </button>
    </div>
  </div>

  <!-- ══ ORDER MODAL ══ -->
  <div class="modal-overlay" :class="{ open: modalOpen }">
    <div class="modal">
      <div class="modal-head">
        <h3>📋 অর্ডার কনফার্ম করুন</h3>
        <button class="close-btn" @click="closeModal">✕</button>
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
            যোগাযোগের জন্য: 📞 01711-123456
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
        <button class="place-btn" @click="placeOrder" :disabled="orderSubmitting">
          {{ orderSubmitting ? '⏳ অপেক্ষা করুন…' : '✅ অর্ডার দিন' }}
        </button>
      </div>
    </div>
  </div>

  <!-- ══ TOAST ══ -->
  <div class="toast" :class="{ show: toastVisible }">{{ toastMsg }}</div>
</template>

<script>
import { site_settings_store } from "../../GlobalStore/site_settings_store.js";

export default {
  name: "HomePage",

  data() {
    return {
      // Site settings
      siteData: {
        name: "Food Corner",
        logoUrl: null,
        phone: "01711-123456",
        address: "ঢাকা, বাংলাদেশ",
        hours: "সকাল ১১টা – রাত ১০টা · প্রতিদিন খোলা",
      },

      // Hero
      hero: {},
      heroStyle: {},

      // Navigation
      activeNav: "home",
      messengerUrl: "https://m.me/",
      whatsappUrl: "https://wa.me/",

      // Products & categories
      menu: [],
      categories: [],
      currentCategory: "all",
      productsLoading: true,

      // Cart
      cart: {},
      cartOpen: false,
      cartBump: false,

      // Testimonials / Slider
      testimonials: [],
      slCurrent: 0,
      slTouchStartX: 0,
      slAutoTimer: null,
      avatarGradients: [
        "linear-gradient(135deg, #f8bbd0, #e63946)",
        "linear-gradient(135deg, #bbdefb, #1565c0)",
        "linear-gradient(135deg, #c8e6c9, #2e7d32)",
        "linear-gradient(135deg, #ffe0b2, #e65100)",
        "linear-gradient(135deg, #e1bee7, #6a1b9a)",
        "linear-gradient(135deg, #b2dfdb, #00796b)",
      ],

      // Bottom order form
      ofShip: 60,
      orderForm: { name: "", phone: "", address: "", notes: "" },

      // Promo code
      promoCode: "",
      promoLoading: false,
      promoMsg: "",
      promoApplied: false,
      promoDiscount: 0,
      promoCodeApplied: "",

      // Cart checkout modal
      modalOpen: false,
      orderSuccess: false,
      modal: { name: "", phone: "", address: "", note: "", shipping: 60 },

      // Shared submission state
      orderSubmitting: false,

      // Toast
      toastMsg: "",
      toastVisible: false,
      toastTimer: null,

      // Shipping options (overridden by API if delivery areas exist)
      shippingOptions: [
        { value: 60,  label: "ঢাকার ভেতরে — ৳ ৬০" },
        { value: 120, label: "ঢাকার বাইরে — ৳ ১২০" },
      ],

      emojis: ["🍛", "🍕", "🍔", "🍜", "🥗", "🍗", "🥩", "🍲", "🧆", "🍮", "🌮", "🍟"],
    };
  },

  computed: {
    heroParsed() {
      const defaultDesc = "অর্ডার করুন, আমরা পৌঁছে দেব — একদম তাজা ও মজাদার";
      const defaultBadges = [
        "⏱️ ৩০-৪৫ মিনিটে ডেলিভারি",
        "🚫 কোনো অতিরিক্ত চার্জ নেই",
        "💳 ক্যাশ অন ডেলিভারি",
      ];
      if (!this.hero.description) return { desc: defaultDesc, badges: defaultBadges };
      const lines = this.hero.description
        .replace(/<br\s*\/?>/gi, "\n")
        .replace(/<\/p>/gi, "\n")
        .replace(/<\/li>/gi, "\n")
        .replace(/<\/div>/gi, "\n")
        .replace(/<[^>]+>/g, "")
        .replace(/&nbsp;/g, " ")
        .replace(/&amp;/g, "&")
        .split("\n")
        .map(l => l.trim())
        .filter(Boolean);
      if (!lines.length) return { desc: defaultDesc, badges: defaultBadges };
      return {
        desc:   lines[0],
        badges: lines.length > 1 ? lines.slice(1) : defaultBadges,
      };
    },
    heroDescText() { return this.heroParsed.desc; },
    heroBadges()   { return this.heroParsed.badges; },
    cartItems() {
      return this.menu.filter(m => (this.cart[m.id] || 0) > 0);
    },
    cartItemCount() {
      return Object.values(this.cart).reduce((a, b) => a + b, 0);
    },
    cartTotal() {
      return this.menu.reduce((sum, item) => sum + (this.cart[item.id] || 0) * item.price, 0);
    },
    filteredMenu() {
      if (this.currentCategory === "all") return this.menu;
      return this.menu.filter(m => {
        if (m.categoryId != null) return String(m.categoryId) === String(this.currentCategory);
        return false;
      });
    },
    testimonialSlides() {
      const slides = [];
      for (let i = 0; i < this.testimonials.length; i += 2) {
        slides.push(this.testimonials.slice(i, i + 2));
      }
      return slides.length
        ? slides
        : [[{ customer_name: "রিভিউ নেই", testimonial_text: "এই মুহূর্তে কোনো রিভিউ পাওয়া যায়নি।", rating: 5 }]];
    },
    slTotal() {
      return this.testimonialSlides.length;
    },
    ofGrandTotal() {
      return Math.max(0, this.cartTotal + this.ofShip - this.promoDiscount);
    },
  },

  methods: {
    // ── Site Settings ───────────────────────────
    async loadSiteSettings() {
      try {
        const store = site_settings_store();
        await store.get_all_website_settings();
        const name    = store.get_setting_value("site_name");
        const logoRaw = store.get_setting_value("image");
        const phone   = store.get_setting_value("phone_number");
        const address = store.get_setting_value("address");
        const hours   = store.get_setting_value("business_hours");
        if (name)    this.siteData.name    = name;
        if (logoRaw) this.siteData.logoUrl = this.resolveUrl(logoRaw);
        if (phone)   this.siteData.phone   = phone;
        if (address) this.siteData.address = address;
        if (hours)   this.siteData.hours   = hours;
      } catch (e) {
        console.error("Site settings load failed:", e);
      }
    },

    // ── Navigation ──────────────────────────────
    setNav(nav) {
      this.activeNav = nav;
    },
    scrollToMenu() {
      const el = document.querySelector(".menu-section");
      if (el) el.scrollIntoView({ behavior: "smooth" });
    },

    // ── Cart ────────────────────────────────────
    addOne(id) {
      this.cart = { ...this.cart, [id]: (this.cart[id] || 0) + 1 };
      this.bumpBadge();
      this.showToast("🛒 কার্টে যোগ হয়েছে!");
    },
    removeOne(id) {
      if (!this.cart[id]) return;
      const c = { ...this.cart };
      c[id]--;
      if (c[id] === 0) delete c[id];
      this.cart = c;
    },
    bumpBadge() {
      this.cartBump = true;
      setTimeout(() => { this.cartBump = false; }, 300);
    },
    openCart() {
      this.cartOpen = true;
      document.body.style.overflow = "hidden";
    },
    closeCart() {
      this.cartOpen = false;
      document.body.style.overflow = "";
    },

    // ── Order Modal ─────────────────────────────
    openOrderModal() {
      this.closeCart();
      this.orderSuccess = false;
      this.modalOpen = true;
      document.body.style.overflow = "hidden";
    },
    closeModal() {
      this.modalOpen = false;
      document.body.style.overflow = "";
    },

    // ── Place Order (cart modal) ─────────────────
    async placeOrder() {
      const { name, phone, address, shipping } = this.modal;
      if (!name.trim())                          { this.showToast("❌ নাম দিন"); return; }
      if (!phone.trim() || phone.trim().length < 11) { this.showToast("❌ সঠিক মোবাইল নম্বর দিন"); return; }
      if (!address.trim())                       { this.showToast("❌ ঠিকানা দিন"); return; }
      if (this.cartItems.length === 0)           { this.showToast("❌ কার্ট খালি"); return; }

      this.orderSubmitting = true;
      try {
        const items = this.cartItems.map(item => ({
          product_id:   item.id,
          product_name: item.name,
          quantity:     this.cart[item.id],
          unit_price:   item.price,
          total_price:  item.price * this.cart[item.id],
        }));

        const res = await fetch("/api/v1/public/place-order", {
          method: "POST",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          body: JSON.stringify({
            customer_name:    name.trim(),
            customer_phone:   phone.trim(),
            delivery_address: address.trim(),
            shipping_charge:  Number(shipping),
            subtotal:         this.cartTotal,
            discount_amount:  0,
            total_amount:     this.cartTotal + Number(shipping),
            special_notes:    this.modal.note.trim() || null,
            items,
          }),
        });
        const data = await res.json();

        if (res.ok && data.status === "success") {
          this.orderSuccess = true;
          this.cart = {};
          setTimeout(() => { this.closeModal(); this.orderSuccess = false; }, 4500);
        } else {
          this.showToast("❌ " + (data.message || "অর্ডার দেওয়া সম্ভব হয়নি।"));
        }
      } catch {
        this.showToast("❌ নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।");
      } finally {
        this.orderSubmitting = false;
      }
    },

    // ── Category Filter ─────────────────────────
    setCategory(cat) {
      this.currentCategory = String(cat);
    },

    // ── Promo Code ──────────────────────────────
    async applyPromo() {
      if (this.promoApplied) return;
      const code = this.promoCode.trim();
      if (!code)              { this.showToast("প্রোমো কোড লিখুন"); return; }
      if (this.cartTotal < 1) { this.showToast("আগে পণ্য যোগ করুন"); return; }

      this.promoLoading = true;
      this.promoMsg = "";
      try {
        const res = await fetch("/api/v1/public/apply-promo", {
          method: "POST",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          body: JSON.stringify({ code, subtotal: this.cartTotal }),
        });
        const data = await res.json();
        if (res.ok && data.status === "success") {
          this.promoDiscount    = data.discount;
          this.promoApplied     = true;
          this.promoCodeApplied = data.code || code.toUpperCase();
          this.promoMsg         = data.message;
        } else {
          this.promoMsg     = data.message || "অবৈধ প্রোমো কোড।";
          this.promoApplied = false;
        }
      } catch {
        this.promoMsg = "নেটওয়ার্ক সমস্যা।";
      } finally {
        this.promoLoading = false;
      }
    },

    // ── Submit Bottom Order Form ─────────────────
    async ofSubmit() {
      const { name, phone, address, notes } = this.orderForm;
      if (!name.trim())                              { this.showToast("❌ নাম দিন"); return; }
      if (!phone.trim() || phone.trim().length < 11) { this.showToast("❌ সঠিক মোবাইল নম্বর দিন"); return; }
      if (!address.trim())                           { this.showToast("❌ ঠিকানা দিন"); return; }
      if (this.cartItems.length === 0)               { this.showToast("❌ কার্টে কোনো পণ্য নেই"); return; }

      this.orderSubmitting = true;
      try {
        const items = this.cartItems.map(item => ({
          product_id:   item.id,
          product_name: item.name,
          quantity:     this.cart[item.id],
          unit_price:   item.price,
          total_price:  item.price * this.cart[item.id],
        }));

        const res = await fetch("/api/v1/public/place-order", {
          method: "POST",
          headers: { "Content-Type": "application/json", Accept: "application/json" },
          body: JSON.stringify({
            customer_name:   name.trim(),
            customer_phone:  phone.trim(),
            delivery_address: address.trim(),
            shipping_charge: this.ofShip,
            subtotal:        this.cartTotal,
            discount_amount: this.promoDiscount,
            promo_code_used: this.promoApplied ? this.promoCodeApplied : null,
            total_amount:    this.ofGrandTotal,
            special_notes:   notes.trim() || null,
            items,
          }),
        });
        const data = await res.json();

        if (res.ok && data.status === "success") {
          this.showToast("🎉 অর্ডার সফল হয়েছে! শীঘ্রই যোগাযোগ করা হবে।");
          this.orderForm = { name: "", phone: "", address: "", notes: "" };
          this.cart = {};
          this.promoCode = "";
          this.promoApplied = false;
          this.promoDiscount = 0;
          this.promoMsg = "";
          this.promoCodeApplied = "";
        } else {
          this.showToast("❌ " + (data.message || "অর্ডার দেওয়া সম্ভব হয়নি।"));
        }
      } catch {
        this.showToast("❌ নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।");
      } finally {
        this.orderSubmitting = false;
      }
    },

    // ── Slider ──────────────────────────────────
    slGo(idx) {
      this.slCurrent = Math.max(0, Math.min(idx, this.slTotal - 1));
    },
    slMove(dir) {
      this.slGo((this.slCurrent + dir + this.slTotal) % this.slTotal);
    },
    onTouchStart(e) {
      this.slTouchStartX = e.touches[0].clientX;
    },
    onTouchEnd(e) {
      const diff = this.slTouchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 40) this.slMove(diff > 0 ? 1 : -1);
    },
    startSliderAuto() {
      this.slAutoTimer = setInterval(() => {
        if (this.slTotal > 1) this.slCurrent = (this.slCurrent + 1) % this.slTotal;
      }, 4000);
    },

    // ── Toast ────────────────────────────────────
    showToast(msg) {
      this.toastMsg     = msg;
      this.toastVisible = true;
      clearTimeout(this.toastTimer);
      this.toastTimer = setTimeout(() => { this.toastVisible = false; }, 2500);
    },

    // ── Utilities ────────────────────────────────
    resolveUrl(path) {
      if (!path) return "";
      if (/^https?:\/\//i.test(path)) return path;
      return `${window.location.origin}/${String(path).replace(/^\/+/, "")}`;
    },
    decodeHtml(html) {
      if (!html) return "";
      try {
        const tmp = document.createElement("div");
        tmp.innerHTML = html;
        return (tmp.textContent || tmp.innerText || "").replace(/\s+/g, " ").trim();
      } catch {
        return String(html).replace(/<[^>]*>/g, " ").replace(/&nbsp;/g, " ").replace(/\s+/g, " ").trim();
      }
    },
    stripHtml(text) {
      return this.decodeHtml(text);
    },
    ratingStars(rating) {
      const v = Math.max(0, Math.min(5, Math.round(Number(rating || 5))));
      return "★★★★★".slice(0, v).padEnd(5, "☆");
    },
    extractList(payload) {
      if (Array.isArray(payload)) return payload;
      if (payload && Array.isArray(payload.data)) return payload.data;
      if (payload && payload.data && Array.isArray(payload.data.data)) return payload.data.data;
      return [];
    },

    // ── API Loaders ──────────────────────────────
    async loadHeroSection() {
      try {
        const res = await fetch("/api/v1/hero-sections?status=active&limit=1", {
          headers: { Accept: "application/json" },
        });
        if (!res.ok) return;
        const payload = await res.json();
        const list = this.extractList(payload).filter(h => String(h.status || "").toLowerCase() === "active");
        const heroData = list[0];
        if (!heroData) return;
        this.hero = heroData;
        if (heroData.background_image) {
          const bgUrl = this.resolveUrl(heroData.background_image);
          this.heroStyle = {
            backgroundImage: `linear-gradient(135deg,rgba(26,26,46,.88) 0%,rgba(45,21,21,.82) 60%,rgba(61,26,10,.82) 100%),url('${bgUrl}')`,
            backgroundSize: "cover",
            backgroundPosition: "center",
          };
        }
      } catch (e) {
        console.error("Hero load failed:", e);
      }
    },

    async loadProductsAndCategories() {
      this.productsLoading = true;
      try {
        const [catRes, prodRes] = await Promise.all([
          fetch("/api/v1/product-categories?status=active&limit=200", { headers: { Accept: "application/json" } }),
          fetch("/api/v1/products?status=active&limit=200", { headers: { Accept: "application/json" } }),
        ]);
        const catPayload  = catRes.ok  ? await catRes.json()  : null;
        const prodPayload = prodRes.ok ? await prodRes.json() : null;

        const rawCats  = this.extractList(catPayload).filter(c => String(c.status || "active").toLowerCase() === "active");
        const rawProds = this.extractList(prodPayload).filter(p => String(p.status || "active").toLowerCase() === "active");

        const catMap = {};
        if (rawCats.length) {
          this.categories = rawCats.map(c => ({
            id:   String(c.id ?? c.slug ?? c.name),
            name: c.category_name || c.name || c.title || "Category",
          }));
          this.categories.forEach(c => { catMap[c.id] = c.name; });
        }

        if (rawProds.length) {
          this.menu = rawProds.map((p, idx) => {
            const regular    = Number(p.regular_price || p.price || 0);
            const sales      = Number(p.sales_price || 0);
            const finalPrice = sales > 0 && sales < regular ? sales : regular;
            const oldPrice   = regular > finalPrice ? regular : null;
            const catId      = p.category_id != null ? String(p.category_id) : null;
            return {
              id:         Number(p.id),
              emoji:      this.emojis[idx % this.emojis.length],
              image:      p.image ? this.resolveUrl(p.image) : null,
              name:       p.name || p.title || "Product",
              desc:       this.stripHtml(p.description || ""),
              price:      finalPrice,
              oldPrice,
              badge:      catId ? (catMap[catId] || null) : null,
              bg:         "linear-gradient(135deg, #fff0e6 0%, #fde8d8 100%)",
              categoryId: catId,
            };
          });
        }

      } catch (e) {
        console.error("Products load failed:", e);
      } finally {
        this.productsLoading = false;
      }
    },

    async loadTestimonials() {
      try {
        const res = await fetch("/api/v1/testimonials?status=active&limit=8", {
          headers: { Accept: "application/json" },
        });
        if (!res.ok) return;
        const payload = await res.json();
        const list = this.extractList(payload)
          .filter(t => String(t.status || "active").toLowerCase() === "active")
          .filter(t => String(t.is_published ?? 1) !== "0")
          .slice(0, 8);
        this.testimonials = list.map(item => ({
          ...item,
          imageUrl: (item.customer_image || item.image || item.avatar)
            ? this.resolveUrl(item.customer_image || item.image || item.avatar)
            : null,
        }));
      } catch (e) {
        console.error("Testimonials load failed:", e);
      }
    },

    async loadDeliveryAreas() {
      try {
        const res = await fetch("/api/v1/public/delivery-areas", { headers: { Accept: "application/json" } });
        if (!res.ok) return;
        const data  = await res.json();
        const areas = data.data || [];
        if (areas.length) {
          this.shippingOptions = areas.map(a => ({
            value: Number(a.delivery_charge || 0),
            label: `${a.area_name || "এলাকা"} — ৳ ${a.delivery_charge || 0}`,
          }));
          this.ofShip       = this.shippingOptions[0].value;
          this.modal.shipping = this.shippingOptions[0].value;
        }
      } catch {
        // keep static defaults
      }
    },
  },

  async mounted() {
    await Promise.all([
      this.loadSiteSettings(),
      this.loadHeroSection(),
      this.loadProductsAndCategories(),
      this.loadTestimonials(),
      this.loadDeliveryAreas(),
    ]);
    this.startSliderAuto();
  },

  beforeUnmount() {
    clearInterval(this.slAutoTimer);
    clearTimeout(this.toastTimer);
  },
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@400;500;600;700;800&family=Hind+Siliguri:wght@300;400;500;600;700&display=swap");
*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --red:    #e63946;
  --orange: #f4a261;
  --yellow: #f9c74f;
  --dark:   #1a1a2e;
  --card:   #ffffff;
  --bg:     #fff8f0;
  --pink-bg:#fff0f8;
  --text:   #2b2b2b;
  --gray:   #888;
  --border: #f0e4d0;
  --shadow: 0 4px 24px rgba(230,57,70,.1);
  --radius: 16px;
}
body {
  font-family: "Hind Siliguri", sans-serif;
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  padding-bottom: 60px;
}

/* ── GLOBAL CONTAINER ── */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 24px;
  width: 100%;
  box-sizing: border-box;
}

/* ── HEADER ── */
header {
  background: var(--dark);
  position: sticky; top: 0; z-index: 200;
  height: 60px;
  box-shadow: 0 2px 20px rgba(0,0,0,.3);
  display: flex; align-items: center; justify-content: center;
  padding: 0 24px;
}
.logo {
  font-family: "Baloo Da 2", cursive;
  font-size: 22px; font-weight: 800;
  color: #fff;
  display: flex; align-items: center; gap: 6px;
  white-space: nowrap;
}
.logo .dot { color: var(--red); }
.logo-img { height: 36px; width: auto; object-fit: contain; border-radius: 6px; }

/* ── BOTTOM NAV ── */
.bottom-nav {
  position: fixed; bottom: 0;
  left: 50%; transform: translateX(-50%);
  width: 100%; max-width: 1200px;
  height: 64px;
  background: rgba(255,255,255,.97);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  display: flex; align-items: center; justify-content: space-around;
  border-top: 1px solid rgba(0,0,0,.07);
  box-shadow: 0 -4px 24px rgba(0,0,0,.08);
  z-index: 500;
  padding-bottom: env(safe-area-inset-bottom);
}
.bn-item {
  flex: 1;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 4px;
  color: #c0c0c0; font-size: 10px; font-weight: 600;
  font-family: "Hind Siliguri", sans-serif;
  cursor: pointer; border: none; background: none;
  text-decoration: none;
  transition: color .2s, transform .2s;
}
.bn-item:active { transform: scale(0.92); }
.bn-svg {
  width: 22px; height: 22px;
  display: block; flex-shrink: 0;
  transition: transform .2s;
}
.bn-home  .bn-svg { fill: var(--red); }
.bn-msg   .bn-svg { fill: #0084ff; }
.bn-wa    .bn-svg { fill: #25D366; }
.bn-order .bn-svg { fill: #ff6f00; }

.bn-item.active .bn-svg { transform: scale(1.15); }

.bn-home.active  { color: var(--red); }
.bn-msg.active   { color: #0084ff; }
.bn-wa.active    { color: #25D366; }
.bn-order.active { color: #ff6f00; }

.bn-label { font-size: 10px; line-height: 1; }
.bn-center-wrap {
  flex: 1;
  display: flex; align-items: center; justify-content: center;
}
.bn-center {
  width: 52px; height: 52px;
  background: linear-gradient(135deg, var(--red) 0%, #c62828 100%);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 18px rgba(230,57,70,.5);
  cursor: pointer; border: none;
  margin-top: -22px;
  transition: transform .2s, box-shadow .2s;
  position: relative;
}
.bn-center:hover { transform: scale(1.08); box-shadow: 0 8px 24px rgba(230,57,70,.6); }
.bn-center:active { transform: scale(0.94); }
.bn-badge {
  position: absolute; top: -2px; right: -2px;
  background: var(--dark); color: #fff;
  border-radius: 50%; width: 18px; height: 18px;
  font-size: 10px; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid #fff;
  transition: transform .25s;
  font-family: "Baloo Da 2", cursive;
}
.bn-badge.bump { transform: scale(1.5); }

/* ── HERO ── */
.hero {
  background: linear-gradient(135deg, #1a1a2e 0%, #2d1515 60%, #3d1a0a 100%);
  padding: 40px 24px 36px;
  text-align: center; position: relative; overflow: hidden;
}
.hero::before {
  content: "";
  position: absolute; inset: 0;
  background: radial-gradient(ellipse at 50% 0%, rgba(230,57,70,.3) 0%, transparent 65%);
}
.hero-inner { position: relative; z-index: 1; }
.hero-emoji {
  font-size: 52px; margin-bottom: 10px; display: block;
  animation: float 3s ease-in-out infinite;
}
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
.hero h1 {
  font-family: "Baloo Da 2", cursive;
  font-size: clamp(28px,6vw,44px); font-weight: 800;
  color: #fff; line-height: 1.15; margin-bottom: 10px;
}
.hero h1 span { color: var(--yellow); }
.hero p { color: rgba(255,255,255,.7); font-size: 15px; margin-bottom: 20px; }
.hero-desc-text { color: rgba(255,255,255,.75); font-size: 15px; margin-bottom: 20px; line-height: 1.6; }
.hero-pills { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
.pill {
  background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.2);
  color: rgba(255,255,255,.85); font-size: 13px;
  padding: 6px 16px; border-radius: 50px;
}
.pill span { margin-right: 4px; }
.hero-badges {
  display: inline-flex; flex-direction: column; align-items: center;
  gap: 8px; margin-top: 18px;
}
.hero-badge {
  display: inline-block;
  background: rgba(255,255,255,.07);
  border: 1.5px solid rgba(255,255,255,.22);
  border-left: 4px solid var(--red);
  color: #fff;
  font-size: 14px; font-weight: 600;
  font-family: "Hind Siliguri", sans-serif;
  padding: 9px 16px;
  border-radius: 10px;
  white-space: nowrap;
  backdrop-filter: blur(6px);
}

/* ── MENU SECTION ── */
.menu-section {
  padding: 36px 0 40px;
  background: #fafafa;
}
.section-title {
  font-family: "Baloo Da 2", cursive;
  font-size: 22px; font-weight: 700; color: var(--dark);
  margin-bottom: 20px;
  display: flex; align-items: center; gap: 10px;
}
.section-title::after {
  content: ""; flex: 1; height: 2px;
  background: linear-gradient(90deg, var(--border), transparent);
  border-radius: 2px;
}
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 20px;
}
.menu-filters {
  width: 100%;
  overflow-x: auto; overflow-y: hidden;
  -webkit-overflow-scrolling: touch;
  -ms-overflow-style: none; scrollbar-width: none;
  background: #fff;
  border-bottom: 1px solid #f0e4d0;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.menu-filters::-webkit-scrollbar { display: none; }
.filters-bar {
  display: flex; gap: 8px; align-items: center;
  padding: 12px 0;
  flex-wrap: wrap;
}
.filter-btn {
  display: inline-block; padding: 8px 12px;
  border-radius: 999px; border: 1px solid #f0e4d0;
  background: transparent; color: var(--dark);
  cursor: pointer; font-weight: 700;
  font-family: "Hind Siliguri", sans-serif; white-space: nowrap;
}
.filter-btn.active {
  background: var(--red); color: #fff; border-color: var(--red);
  box-shadow: 0 6px 18px rgba(230,57,70,.12);
}

/* ── FOOD CARDS ── */
.food-card {
  background: var(--card); border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(0,0,0,.07);
  border: 1.5px solid #f5e8d8;
  transition: transform .22s, box-shadow .22s;
  display: flex; flex-direction: column;
}
.food-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 16px 40px rgba(230,57,70,.14);
}
.food-card-img {
  height: 155px; display: flex; align-items: center; justify-content: center;
  font-size: 68px; position: relative; overflow: hidden;
}
.food-card-img::after {
  content: ""; position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,.08) 0%, transparent 60%);
  pointer-events: none;
}
.food-emoji { pointer-events: none; z-index: 1; }
.food-img-cover {
  width: 100%; height: 100%; object-fit: cover;
  position: absolute; inset: 0;
}
.food-card-img .badge {
  position: absolute; top: 10px; left: 10px;
  background: var(--red); color: #fff;
  font-size: 11px; font-weight: 700;
  padding: 4px 12px; border-radius: 50px; z-index: 2;
  box-shadow: 0 2px 8px rgba(230,57,70,.4);
  font-family: "Hind Siliguri", sans-serif;
}
.food-card-body {
  padding: 14px 14px 14px; flex: 1;
  display: flex; flex-direction: column;
}
.food-name {
  font-family: "Baloo Da 2", cursive;
  font-size: 16px; font-weight: 700; color: var(--dark);
  margin-bottom: 3px; line-height: 1.3;
}
.food-desc {
  font-size: 12px; color: #aaa; line-height: 1.5; flex: 1;
  margin-bottom: 12px;
  display: -webkit-box; -webkit-line-clamp: 2;
  line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.food-footer { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
.food-price-wrap { display: flex; flex-direction: column; line-height: 1.1; }
.food-old-price {
  font-size: 12px; color: #bbb; text-decoration: line-through;
  font-family: "Hind Siliguri", sans-serif;
}
.food-price {
  font-family: "Baloo Da 2", cursive;
  font-size: 20px; font-weight: 800; color: var(--red);
}
.add-btn {
  width: 40px; height: 40px; border-radius: 50%;
  background: var(--red); color: #fff; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 14px rgba(230,57,70,.45);
  transition: transform .15s, box-shadow .15s, background .15s;
  flex-shrink: 0;
}
.add-btn:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(230,57,70,.55); background: #c62828; }
.add-btn:active { transform: scale(0.93); }
.item-counter {
  display: flex; align-items: center; gap: 0;
  background: #fff5f5; border: 1.5px solid #ffd0d0;
  border-radius: 50px; padding: 3px;
}
.counter-btn {
  width: 30px; height: 30px; border-radius: 50%;
  border: none; cursor: pointer;
  font-size: 18px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s;
}
.counter-btn--minus { background: transparent; color: var(--red); }
.counter-btn--minus:hover { background: #ffeaea; }
.counter-btn--plus { background: var(--red); color: #fff; box-shadow: 0 2px 8px rgba(230,57,70,.4); }
.counter-btn--plus:hover { background: #c62828; }
.counter-num {
  font-family: "Baloo Da 2", cursive;
  font-size: 15px; font-weight: 700;
  min-width: 26px; text-align: center; color: var(--dark);
}

/* ── Skeleton Loading ── */
.sk-card { pointer-events: none; }
.sk-img {
  height: 140px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: shimmer 1.4s infinite;
}
.sk-line {
  height: 12px; border-radius: 6px;
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: shimmer 1.4s infinite;
}
.sk-w70 { width: 70%; }
.sk-w100 { width: 100%; }
.sk-w40 { width: 40%; }
.sk-mt6 { margin-top: 6px; }
.sk-mt10 { margin-top: 10px; }
@keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

.no-products {
  grid-column: 1/-1; text-align: center;
  padding: 40px; color: var(--gray); font-size: 15px;
}

/* ── REVIEWS SLIDER ── */
.reviews-section {
  background: linear-gradient(160deg, #fff5f7 0%, #fce8f0 60%, #f9e0ec 100%);
  box-shadow: inset 0 8px 32px rgba(230,57,70,.08), inset 0 -4px 16px rgba(230,57,70,.05);
  padding: 48px 0 40px; text-align: center;
}
.reviews-section h2 {
  font-family: "Baloo Da 2", cursive;
  font-size: clamp(20px,5vw,28px); font-weight: 700;
  color: #1a1a2e; line-height: 1.4;
  margin-bottom: 14px; padding: 0 20px;
}
.reviews-section h2 .pink { color: var(--red); }
.reviews-badge {
  display: inline-block; background: var(--red); color: #fff;
  font-size: 14px; font-weight: 700;
  padding: 7px 22px; border-radius: 50px; margin-bottom: 28px;
  font-family: "Baloo Da 2", cursive;
}
.slider-outer { display: flex; align-items: center; gap: 0; padding: 0 2px; }
.slider-wrap { flex: 1; min-width: 0; overflow: hidden; }
.sl-track {
  display: flex;
  transition: transform .4s cubic-bezier(.4,0,.2,1);
  will-change: transform;
}
.sl-slide {
  display: flex; gap: 5px; min-width: 100%; padding: 5px 2px 5px;
}
.review-card {
  background: #fff; border-radius: 18px; padding: 22px 18px;
  text-align: left; box-shadow: none;
  flex: 1; min-width: 0;
}
.review-avatar {
  width: 50px; height: 50px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-weight: 800; font-size: 20px;
  font-family: "Baloo Da 2", cursive; margin-bottom: 12px;
}
.review-avatar-img {
  width: 50px; height: 50px; border-radius: 50%; object-fit: cover;
  display: block; margin-bottom: 12px;
  border: 2px solid rgba(255,255,255,.9);
  box-shadow: 0 3px 12px rgba(0,0,0,.12);
}
.review-name {
  font-size: 15px; font-weight: 700; color: #1a1a2e;
  margin-bottom: 5px; font-family: "Baloo Da 2", cursive;
}
.review-stars { color: #f4b942; font-size: 14px; margin-bottom: 9px; }
.review-text { font-size: 13px; color: #666; line-height: 1.65; }
.sl-arrow {
  flex-shrink: 0;
  background: transparent; color: #ccc;
  border: none; border-radius: 50%;
  width: 22px; height: 22px; font-size: 24px; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; box-shadow: none;
  transition: color .2s; line-height: 1; padding: 0;
}
.sl-arrow:hover { color: #e63946; }
.sl-dots { display: flex; justify-content: center; gap: 8px; margin-top: 18px; }
.sl-dot {
  width: 8px; height: 8px; border-radius: 50%;
  background: #f0c0df; cursor: pointer;
  transition: background .2s, transform .2s;
  border: none; padding: 0;
}
.sl-dot.active { background: var(--red); transform: scale(1.3); }

/* ── CART DRAWER ── */
.overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.55);
  z-index: 590; opacity: 0; pointer-events: none; transition: opacity .25s;
}
.overlay.open { opacity: 1; pointer-events: all; }
.cart-drawer {
  position: fixed; top: 0; bottom: 0;
  right: 0; width: min(420px, 100vw);
  background: #fff; z-index: 600;
  transform: translateX(100%);
  transition: transform .3s cubic-bezier(.4,0,.2,1);
  display: flex; flex-direction: column;
  box-shadow: -8px 0 40px rgba(0,0,0,.2);
}
.cart-drawer.open { transform: translateX(0); }
.cart-header {
  background: var(--dark); padding: 16px 20px;
  display: flex; align-items: center; justify-content: space-between;
  flex-shrink: 0;
}
.cart-header h2 {
  font-family: "Baloo Da 2", cursive; color: #fff;
  font-size: 20px; font-weight: 700;
}
.close-btn {
  background: rgba(255,255,255,.12); border: none; cursor: pointer;
  color: #fff; font-size: 20px; width: 36px; height: 36px;
  border-radius: 50%; display: flex; align-items: center; justify-content: center;
}
.close-btn:hover { background: rgba(255,255,255,.2); }
.cart-body {
  flex: 1; overflow-y: auto; padding: 16px;
  overscroll-behavior: contain;
  -webkit-overflow-scrolling: touch;
  min-height: 0;
}
.cart-empty { text-align: center; padding: 60px 20px; color: var(--gray); }
.cart-empty .empty-icon { font-size: 56px; margin-bottom: 12px; }
.cart-empty p { font-size: 16px; }
.cart-item {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 0; border-bottom: 1px solid #f5f5f5;
}
.cart-item-thumb {
  width: 52px; height: 52px; border-radius: 10px;
  overflow: hidden; flex-shrink: 0;
  background: linear-gradient(135deg, #fff0e6, #fde8d8);
  display: flex; align-items: center; justify-content: center;
}
.cart-item-img {
  width: 100%; height: 100%; object-fit: cover;
}
.cart-item-emoji { font-size: 28px; }
.cart-item-info { flex: 1; }
.cart-item-name { font-size: 14px; font-weight: 700; color: var(--dark); }
.cart-item-price { font-size: 13px; color: var(--red); font-weight: 600; margin-top: 2px; }
.cart-item-controls { display: flex; align-items: center; gap: 6px; }
.ci-btn {
  width: 28px; height: 28px; border-radius: 50%;
  border: 1.5px solid var(--red); background: #fff; color: var(--red);
  font-size: 16px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s;
}
.ci-btn:hover { background: var(--red); color: #fff; }
.ci-num { font-size: 14px; font-weight: 700; min-width: 18px; text-align: center; }
.cart-footer {
  padding: 16px 20px; border-top: 2px solid #f5f5f5; background: #fafafa;
  flex-shrink: 0;
}
.subtotal-row {
  display: flex; justify-content: space-between;
  font-size: 14px; color: var(--gray); margin-bottom: 6px;
}
.total-row {
  display: flex; justify-content: space-between;
  font-family: "Baloo Da 2", cursive;
  font-size: 22px; font-weight: 800; color: var(--dark); margin-bottom: 16px;
}
.total-row span:last-child { color: var(--red); }
.checkout-btn {
  width: 100%; background: var(--red); color: #fff; border: none; cursor: pointer;
  font-family: "Baloo Da 2", cursive; font-size: 18px; font-weight: 700;
  padding: 15px; border-radius: 12px;
  box-shadow: 0 6px 20px rgba(230,57,70,.4);
  transition: transform .15s, box-shadow .15s;
}
.checkout-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(230,57,70,.5); }
.checkout-btn:disabled { background: #ccc; box-shadow: none; cursor: not-allowed; transform: none; }

/* ── ORDER MODAL ── */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.6);
  z-index: 500; display: flex; align-items: center; justify-content: center;
  padding: 20px; opacity: 0; pointer-events: none; transition: opacity .25s;
}
.modal-overlay.open { opacity: 1; pointer-events: all; }
.modal {
  background: #fff; border-radius: 20px; width: 100%; max-width: 460px;
  box-shadow: 0 20px 60px rgba(0,0,0,.3); overflow: hidden;
  transform: scale(0.9); transition: transform .25s;
  max-height: 90vh; overflow-y: auto;
}
.modal-overlay.open .modal { transform: scale(1); }
.modal-head {
  background: var(--dark); padding: 20px 24px;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 1;
}
.modal-head h3 {
  font-family: "Baloo Da 2", cursive; color: #fff;
  font-size: 20px; font-weight: 700;
}
.modal-body { padding: 24px; }
.order-summary {
  background: var(--bg); border-radius: 12px; padding: 14px 16px;
  margin-bottom: 20px; max-height: 160px; overflow-y: auto;
}
.order-line {
  display: flex; justify-content: space-between;
  font-size: 14px; padding: 4px 0; color: #444;
}
.order-line strong { color: var(--red); }
.form-group { margin-bottom: 14px; }
.form-group label {
  display: block; font-size: 13px; font-weight: 600;
  color: #444; margin-bottom: 6px;
}
.form-group input,
.form-group select,
.form-group textarea {
  width: 100%; padding: 11px 14px;
  border: 1.5px solid #e8e8e8; border-radius: 10px;
  font-family: "Hind Siliguri", sans-serif; font-size: 14px;
  color: var(--dark); background: #fafafa;
  outline: none; transition: border .2s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { border-color: var(--red); background: #fff; }
.form-group textarea { resize: vertical; min-height: 70px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.grand-total-bar {
  display: flex; justify-content: space-between; align-items: center;
  background: var(--red); color: #fff; border-radius: 12px;
  padding: 14px 18px;
  font-family: "Baloo Da 2", cursive; font-size: 20px; font-weight: 700;
  margin: 16px 0;
}
.place-btn {
  width: 100%; background: var(--dark); color: #fff;
  border: none; cursor: pointer;
  font-family: "Baloo Da 2", cursive; font-size: 17px; font-weight: 700;
  padding: 15px; border-radius: 12px; transition: background .2s;
}
.place-btn:hover { background: #2d2d4e; }
.place-btn:disabled { background: #999; cursor: not-allowed; }
.success-screen { text-align: center; padding: 40px 24px; }
.success-screen .tick { font-size: 64px; margin-bottom: 12px; }
.success-screen h3 {
  font-family: "Baloo Da 2", cursive; font-size: 24px;
  color: var(--dark); margin-bottom: 8px;
}
.success-screen p { color: var(--gray); font-size: 15px; line-height: 1.6; }

/* ── FOOTER ── */
footer {
  background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 60%, #16213e 100%);
  margin-top: 0px;
  border-top: 3px solid var(--red);
}
.footer-inner {
  padding: 36px 24px 28px;
  display: flex; flex-direction: column; align-items: center; gap: 0;
  text-align: center;
}
.footer-brand {
  display: flex; align-items: center; gap: 10px; margin-bottom: 20px;
}
.footer-logo { height: 40px; width: auto; border-radius: 8px; object-fit: contain; }
.footer-logo-emoji { font-size: 32px; }
.footer-name {
  font-family: "Baloo Da 2", cursive;
  font-size: 24px; font-weight: 800; color: #fff;
}
.footer-dot { color: var(--red); }
.footer-divider {
  width: 60px; height: 2px;
  background: linear-gradient(90deg, transparent, var(--red), transparent);
  border-radius: 2px; margin: 0 0 20px;
}
.footer-info {
  display: flex; flex-direction: column; gap: 12px;
  margin-bottom: 20px; width: 100%;
}
.footer-info-item {
  display: flex; align-items: center; justify-content: center; gap: 10px;
  font-size: 14px; color: rgba(255,255,255,.7);
  font-family: "Hind Siliguri", sans-serif;
}
.footer-info-icon { font-size: 16px; flex-shrink: 0; }
.footer-copy {
  font-size: 12px; color: rgba(255,255,255,.35);
  font-family: "Hind Siliguri", sans-serif;
}

/* ── TOAST ── */
.toast {
  position: fixed; bottom: 90px; left: 50%;
  transform: translateX(-50%) translateY(20px);
  background: #1e1e3a; color: #fff;
  font-size: 14px; font-weight: 600;
  font-family: "Hind Siliguri", sans-serif;
  padding: 13px 22px; border-radius: 50px;
  box-shadow: 0 8px 32px rgba(0,0,0,.35);
  z-index: 700;
  transition: transform .3s cubic-bezier(.4,0,.2,1), opacity .3s ease, visibility .3s;
  white-space: nowrap; border-left: 4px solid var(--red);
  pointer-events: none;
  opacity: 0; visibility: hidden;
}
.toast.show {
  transform: translateX(-50%) translateY(0);
  opacity: 1; visibility: visible;
}

/* ══ ORDER FORM SECTION ══ */
.order-form-section { background: #1a1a2e; padding: 48px 16px 80px; }
.order-form-inner { width: 100%; }
.of-title {
  font-family: "Baloo Da 2", cursive;
  font-size: 30px; font-weight: 800; color: #fff;
  text-align: center; margin-bottom: 6px;
}
.of-sub { text-align: center; font-size: 14px; color: rgba(255,255,255,.55); margin-bottom: 28px; }
.of-box {
  background: #fff; border-radius: 14px;
  overflow: hidden; margin-bottom: 20px;
  box-shadow: 0 4px 20px rgba(0,0,0,.2);
}
.of-box-header {
  background: var(--red); color: #fff;
  font-size: 14px; font-weight: 700;
  font-family: "Baloo Da 2", cursive;
  padding: 12px 16px; display: flex; align-items: center; gap: 8px;
}
.of-box-body { padding: 4px 16px 8px; }
.of-loading { text-align: center; padding: 20px; color: var(--gray); font-size: 14px; }
.of-empty-cart {
  text-align: center; padding: 32px 20px; color: #999;
}
.of-empty-icon { font-size: 48px; margin-bottom: 10px; }
.of-empty-cart p { font-size: 14px; line-height: 1.7; margin-bottom: 14px; }
.of-goto-menu {
  display: inline-block; background: var(--red); color: #fff;
  padding: 9px 22px; border-radius: 50px; font-size: 14px; font-weight: 700;
  text-decoration: none; font-family: "Hind Siliguri", sans-serif;
  transition: opacity .15s;
}
.of-goto-menu:hover { opacity: .85; }
.of-product-row {
  display: flex; align-items: center; gap: 8px;
  padding: 9px 0; flex-wrap: nowrap;
  border-bottom: 1px solid #f3e8e0;
}
.of-product-row:last-child { border-bottom: none; }
.ofp-thumb {
  width: 36px; height: 36px; border-radius: 8px;
  overflow: hidden; flex-shrink: 0;
  background: linear-gradient(135deg, #fff0e6, #fde8d8);
  display: flex; align-items: center; justify-content: center;
}
.ofp-img { width: 100%; height: 100%; object-fit: cover; }
.ofp-emoji { font-size: 18px; }
.ofp-name {
  flex: 1; min-width: 0;
  font-size: 12px; font-weight: 700; color: var(--dark);
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ofp-controls {
  display: flex; align-items: center; gap: 4px;
  flex-shrink: 0;
}
.ofp-btn {
  width: 24px; height: 24px; border-radius: 50%;
  border: 1.5px solid var(--red);
  background: #fff; color: var(--red);
  font-size: 14px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s; line-height: 1;
}
.ofp-btn:hover { background: var(--red); color: #fff; }
.ofp-num {
  font-family: "Baloo Da 2", cursive;
  font-size: 13px; font-weight: 700;
  min-width: 16px; text-align: center; color: var(--dark);
}
.ofp-price {
  font-family: "Baloo Da 2", cursive;
  font-size: 13px; font-weight: 800; color: var(--red);
  flex-shrink: 0; white-space: nowrap;
}
.of-row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.of-field { display: flex; flex-direction: column; gap: 6px; }
.of-field label { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.7); }
.of-field input,
.of-field select,
.of-field textarea {
  width: 100%; padding: 12px 14px;
  border: 1.5px solid #2e2e50; border-radius: 10px;
  font-family: "Hind Siliguri", sans-serif; font-size: 14px;
  color: #1a1a2e; background: #f7f7fc; outline: none; transition: border .2s;
}
.of-field input:focus,
.of-field select:focus,
.of-field textarea:focus { border-color: var(--red); background: #fff; }
.of-field textarea { resize: vertical; min-height: 90px; }
.of-full { display: flex; flex-direction: column; gap: 6px; }
.of-full label { font-size: 13px; font-weight: 600; color: rgba(255,255,255,.7); }
.of-full input { width:100%; padding:12px 14px; border:1.5px solid #2e2e50; border-radius:10px; font-family:"Hind Siliguri",sans-serif; font-size:14px; color:#1a1a2e; background:#f7f7fc; outline:none; transition:border .2s; }
.of-full input:focus { border-color:var(--red); background:#fff; }

/* Promo code */
.of-promo-row {
  display: flex; gap: 10px; margin-bottom: 8px;
}
.of-promo-input {
  flex: 1; padding: 11px 14px;
  border: 1.5px solid #2e2e50; border-radius: 10px;
  font-family: "Hind Siliguri", sans-serif; font-size: 14px;
  color: #1a1a2e; background: #f7f7fc; outline: none; transition: border .2s;
}
.of-promo-input:focus { border-color: var(--red); background: #fff; }
.of-promo-input:disabled { opacity: .6; cursor: not-allowed; }
.of-promo-btn {
  padding: 11px 18px; border-radius: 10px;
  background: var(--yellow); color: var(--dark);
  border: none; font-weight: 700; cursor: pointer;
  font-family: "Hind Siliguri", sans-serif; font-size: 13px;
  transition: opacity .15s; white-space: nowrap;
}
.of-promo-btn:hover { opacity: .85; }
.of-promo-btn:disabled { opacity: .5; cursor: not-allowed; }
.of-promo-msg {
  font-size: 13px; font-weight: 600; padding: 6px 10px;
  border-radius: 8px; margin-bottom: 14px;
}
.promo-ok { background: rgba(46,204,113,.15); color: #2ecc71; }
.promo-err { background: rgba(230,57,70,.1); color: var(--red); }

.of-shipping {
  display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
  background: #23234a; border-radius: 10px; padding: 13px 18px;
  margin-bottom: 10px; font-size: 14px; color: rgba(255,255,255,.85);
}
.of-shipping strong { color: #fff; }
.of-shipping select {
  flex: 1; padding: 8px 12px;
  border: 1.5px solid #3a3a6a; border-radius: 8px;
  background: #1a1a2e; color: #fff;
  font-family: "Hind Siliguri", sans-serif; font-size: 13px;
  outline: none; cursor: pointer;
}
.of-ship-price {
  font-family: "Baloo Da 2", cursive;
  font-size: 17px; font-weight: 800; color: var(--red);
  min-width: 54px; text-align: right;
}
.of-dash { border: none; border-top: 2px dashed #2e2e50; margin: 14px 0; }
.of-discount-row {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 8px; font-size: 14px; color: #2ecc71;
}
.of-discount-amt { font-family: "Baloo Da 2", cursive; font-size: 17px; font-weight: 700; }
.of-total-row {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 24px; font-size: 15px; color: rgba(255,255,255,.7);
}
.of-total-price {
  font-family: "Baloo Da 2", cursive;
  font-size: 26px; font-weight: 800; color: var(--red);
}
.of-submit {
  width: 100%; background: var(--red); color: #fff;
  border: none; cursor: pointer;
  font-family: "Baloo Da 2", cursive; font-size: 18px; font-weight: 700;
  padding: 16px; border-radius: 12px;
  box-shadow: 0 6px 24px rgba(230,57,70,.45);
  transition: transform .15s, box-shadow .15s;
}
.of-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(230,57,70,.55); }
.of-submit:disabled { background: #999; box-shadow: none; transform: none; cursor: not-allowed; }

/* ── RESPONSIVE ── */

/* Small mobile ≤ 480px */
@media (max-width: 480px) {
  .container { padding: 0 14px; }
  .menu-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
  .food-card-img { height: 120px; font-size: 50px; }
  .food-name { font-size: 13px; }
  .food-price { font-size: 16px; }
  .add-btn { width: 32px; height: 32px; }
  .counter-btn { width: 26px; height: 26px; font-size: 14px; }
  .counter-num { font-size: 12px; min-width: 20px; }
  .of-row2 { grid-template-columns: 1fr; }
  .form-row { grid-template-columns: 1fr; }
  .review-card { padding: 14px 12px; }
  .review-name { font-size: 13px; }
  .review-text { font-size: 12px; }
  .review-avatar, .review-avatar-img { width: 38px; height: 38px; font-size: 15px; }
}

/* Tablet 481px–768px */
@media (min-width: 481px) and (max-width: 768px) {
  .menu-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
  .food-card-img { height: 150px; }
}

/* Tablet large 769px–1024px */
@media (min-width: 769px) and (max-width: 1024px) {
  .menu-grid { grid-template-columns: repeat(3, 1fr); gap: 18px; }
  .food-card-img { height: 160px; }
}

/* Desktop ≥ 1025px */
@media (min-width: 1025px) {
  .menu-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
  .food-card-img { height: 170px; }
  .order-form-inner { max-width: 720px; margin: 0 auto; }
}
</style>
