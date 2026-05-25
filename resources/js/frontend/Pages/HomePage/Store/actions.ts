// @ts-ignore
import { site_settings_store } from '../../../GlobalStore/site_settings_store.js';

let _slAutoTimer: ReturnType<typeof setInterval> | null = null;
let _toastTimer: ReturnType<typeof setTimeout> | null = null;

const EMOJIS = ['🍛', '🍕', '🍔', '🍜', '🥗', '🍗', '🥩', '🍲', '🧆', '🍮', '🌮', '🍟'];

export const actions = {
    // ── Lifecycle ──────────────────────────────────────────────────────────
    async init(this: any) {
        await Promise.all([
            this.loadSiteSettings(),
            this.loadHeroSection(),
            this.loadProductsAndCategories(),
            this.loadTestimonials(),
            this.loadDeliveryAreas(),
        ]);
        this.startSliderAuto();
    },

    cleanup(this: any) {
        if (_slAutoTimer) { clearInterval(_slAutoTimer); _slAutoTimer = null; }
        if (_toastTimer)  { clearTimeout(_toastTimer);   _toastTimer  = null; }
    },

    // ── Site Settings ───────────────────────────────────────────────────────
    async loadSiteSettings(this: any) {
        try {
            const store = site_settings_store();
            await store.get_all_website_settings();

            const name      = store.get_setting_value('site_name');
            const logoRaw   = store.get_setting_value('image');
            const phone     = store.get_setting_value('phone_number');
            const address   = store.get_setting_value('address');
            const hours     = store.get_setting_value('business_hours');
            const messenger = store.get_setting_value('messenger_url');
            const whatsapp  = store.get_setting_value('whatsapp_number');

            if (name)      this.siteData.name    = name;
            if (logoRaw)   this.siteData.logoUrl = this.resolveUrl(logoRaw);
            if (phone)     this.siteData.phone   = phone;
            if (address)   this.siteData.address = address;
            if (hours)     this.siteData.hours   = hours;
            if (messenger) this.messengerUrl     = messenger;

            if (whatsapp) {
                let num = String(whatsapp).replace(/\D/g, '');
                if (num.startsWith('0')) num = '880' + num.substring(1);
                this.whatsappNumber = num;
                const siteName  = name || this.siteData.name;
                const waMessage = encodeURIComponent(
                    `আসসালামুয়ালাইকুম! 🍛 ${siteName} এ আপনাকে স্বাগতম!\nকি অর্ডার করতে চাচ্ছেন? আমাদের মেনু থেকে পছন্দের খাবার বেছে নিন। 😊`,
                );
                this.whatsappUrl = `https://wa.me/${num}?text=${waMessage}`;
            }
        } catch (e) {
            console.error('Site settings load failed:', e);
        }
    },

    // ── Navigation ──────────────────────────────────────────────────────────
    setNav(this: any, nav: string) {
        this.activeNav = nav;
    },

    buildOrderMessage(this: any): string {
        const siteName = this.siteData.name;
        let message = `আসসালামুয়ালাইকুম! 🍛 *${siteName}* এ আপনাকে স্বাগতম!\n\n`;

        const cartEntries = this.cartItems as any[];
        if (cartEntries.length > 0) {
            message += `🛒 *আমার পছন্দের অর্ডার:*\n`;
            let total = 0;
            for (const item of cartEntries) {
                const qty = Number((this.cart as Record<string, number>)[String(item.id)] || (this.cart as Record<string, number>)[item.id] || 0);
                const sub = qty * Number(item.price);
                total += sub;
                message += `• ${item.name} × ${qty} = ৳${sub}\n`;
            }
            const shipping     = Number(this.ofShip) || 0;
            const discount     = Number(this.promoDiscount) || 0;
            const grandTotal   = total + shipping - discount;
            const shippingLabel = (this.shippingOptions as any[]).find((o: any) => o.value === shipping)?.label || `৳${shipping}`;

            message += `\n🚚 *ডেলিভারি: ${shippingLabel}*\n`;
            if (discount > 0) message += `🎁 *ছাড়: -৳${discount}*\n`;
            message += `💰 *সর্বমোট: ৳${grandTotal}*\n`;

            const form = this.orderForm as { name: string; phone: string; address: string; notes: string };
            if (form.name || form.phone || form.address) {
                message += `\n📋 *গ্রাহকের তথ্য:*\n`;
                if (form.name)    message += `👤 নাম: ${form.name}\n`;
                if (form.phone)   message += `📞 মোবাইল: ${form.phone}\n`;
                if (form.address) message += `📍 ঠিকানা: ${form.address}\n`;
                if (form.notes)   message += `📝 নির্দেশনা: ${form.notes}\n`;
            }
            message += `\nঅনুগ্রহ করে আমার অর্ডারটি কনফার্ম করুন। ধন্যবাদ! 😊`;
        } else {
            message += `কি অর্ডার করতে চাচ্ছেন? আমাদের মেনু থেকে পছন্দের খাবার বেছে নিন। 😊`;
        }
        return message;
    },

    openWhatsApp(this: any) {
        this.setNav('wa');
        const num = this.whatsappNumber;
        if (!num) return;
        const message = this.buildOrderMessage();
        window.open(`https://wa.me/${num}?text=${encodeURIComponent(message)}`, '_blank');
    },

    async openMessenger(this: any) {
        this.setNav('msg');
        const message = this.buildOrderMessage();
        try {
            await navigator.clipboard.writeText(message);
            this.showToast('📋 মেসেজ কপি হয়েছে! Messenger এ পেস্ট করুন।');
        } catch {
            this.showToast('📋 মেসেজটি কপি করুন এবং Messenger এ পেস্ট করুন।');
        }
        setTimeout(() => window.open(this.messengerUrl, '_blank'), 800);
    },

    scrollToMenu(this: any) {
        const el = document.querySelector('.menu-section');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    },

    // ── Cart ────────────────────────────────────────────────────────────────
    addOne(this: any, id: number) {
        this.cart = { ...this.cart, [id]: (this.cart[id] || 0) + 1 };
        this.bumpBadge();
        this.showToast('🛒 কার্টে যোগ হয়েছে!');
    },

    removeOne(this: any, id: number) {
        if (!this.cart[id]) return;
        const c = { ...this.cart };
        c[id]--;
        if (c[id] === 0) delete c[id];
        this.cart = c;
    },

    bumpBadge(this: any) {
        this.cartBump = true;
        setTimeout(() => { this.cartBump = false; }, 300);
    },

    openCart(this: any) {
        this.cartOpen = true;
        document.body.style.overflow = 'hidden';
    },

    closeCart(this: any) {
        this.cartOpen = false;
        document.body.style.overflow = '';
    },

    // ── Order Modal ─────────────────────────────────────────────────────────
    openOrderModal(this: any) {
        this.closeCart();
        this.orderSuccess = false;
        this.modalOpen = true;
        document.body.style.overflow = 'hidden';
    },

    closeModal(this: any) {
        this.modalOpen = false;
        document.body.style.overflow = '';
    },

    // ── Cart Drawer Quick Checkout ───────────────────────────────────────────
    async drawerCheckout(this: any) {
        const { name, phone, address } = this.orderForm;
        if (!name.trim() || !phone.trim() || phone.trim().length < 11 || !address.trim()) {
            this.closeCart();
            setTimeout(() => {
                const el = document.getElementById('orderForm');
                if (el) el.scrollIntoView({ behavior: 'smooth' });
            }, 300);
            this.showToast('📝 আগে নাম, মোবাইল ও ঠিকানা পূরণ করুন');
            return;
        }
        this.closeCart();
        await this.ofSubmit();
    },

    // ── Place Order (cart modal) ─────────────────────────────────────────────
    async placeOrder(this: any) {
        const { name, phone, address, shipping } = this.modal;
        if (!name.trim())                              { this.showToast('❌ নাম দিন'); return; }
        if (!phone.trim() || phone.trim().length < 11) { this.showToast('❌ সঠিক মোবাইল নম্বর দিন'); return; }
        if (!address.trim())                           { this.showToast('❌ ঠিকানা দিন'); return; }
        if (this.cartItems.length === 0)               { this.showToast('❌ কার্ট খালি'); return; }

        this.orderSubmitting = true;
        try {
            const items = this.cartItems.map((item: any) => ({
                product_id:   item.id,
                product_name: item.name,
                quantity:     this.cart[item.id],
                unit_price:   item.price,
                total_price:  item.price * this.cart[item.id],
            }));

            const res = await fetch('/api/v1/public/place-order', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body:    JSON.stringify({
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
            if (res.ok && data.status === 'success') {
                this.orderSuccess = true;
                this.cart = {};
                setTimeout(() => { this.closeModal(); this.orderSuccess = false; }, 4500);
            } else {
                this.showToast('❌ ' + (data.message || 'অর্ডার দেওয়া সম্ভব হয়নি।'));
            }
        } catch {
            this.showToast('❌ নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।');
        } finally {
            this.orderSubmitting = false;
        }
    },

    // ── Category Filter ──────────────────────────────────────────────────────
    setCategory(this: any, cat: string | number) {
        this.currentCategory = String(cat);
    },

    // ── Promo Code ───────────────────────────────────────────────────────────
    async applyPromo(this: any) {
        if (this.promoApplied) return;
        const code = this.promoCode.trim();
        if (!code)              { this.showToast('প্রোমো কোড লিখুন'); return; }
        if (this.cartTotal < 1) { this.showToast('আগে পণ্য যোগ করুন'); return; }

        this.promoLoading = true;
        this.promoMsg = '';
        try {
            const res = await fetch('/api/v1/public/apply-promo', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body:    JSON.stringify({ code, subtotal: this.cartTotal }),
            });
            const data = await res.json();
            if (res.ok && data.status === 'success') {
                this.promoDiscount    = data.discount;
                this.promoApplied     = true;
                this.promoCodeApplied = data.code || code.toUpperCase();
                this.promoMsg         = data.message;
            } else {
                this.promoMsg     = data.message || 'অবৈধ প্রোমো কোড।';
                this.promoApplied = false;
            }
        } catch {
            this.promoMsg = 'নেটওয়ার্ক সমস্যা।';
        } finally {
            this.promoLoading = false;
        }
    },

    // ── Submit Bottom Order Form ─────────────────────────────────────────────
    async ofSubmit(this: any) {
        const { name, phone, address, notes } = this.orderForm;
        if (!name.trim())                              { this.showToast('❌ নাম দিন'); return; }
        if (!phone.trim() || phone.trim().length < 11) { this.showToast('❌ সঠিক মোবাইল নম্বর দিন'); return; }
        if (!address.trim())                           { this.showToast('❌ ঠিকানা দিন'); return; }
        if (this.cartItems.length === 0)               { this.showToast('❌ কার্টে কোনো পণ্য নেই'); return; }

        this.orderSubmitting = true;
        try {
            const items = this.cartItems.map((item: any) => ({
                product_id:   item.id,
                product_name: item.name,
                quantity:     this.cart[item.id],
                unit_price:   item.price,
                total_price:  item.price * this.cart[item.id],
            }));

            const res = await fetch('/api/v1/public/place-order', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body:    JSON.stringify({
                    customer_name:    name.trim(),
                    customer_phone:   phone.trim(),
                    delivery_address: address.trim(),
                    shipping_charge:  this.ofShip,
                    subtotal:         this.cartTotal,
                    discount_amount:  this.promoDiscount,
                    promo_code_used:  this.promoApplied ? this.promoCodeApplied : null,
                    total_amount:     this.ofGrandTotal,
                    special_notes:    notes.trim() || null,
                    items,
                }),
            });

            const data = await res.json();
            if (res.ok && data.status === 'success') {
                this.showToast('🎉 অর্ডার সফল হয়েছে! শীঘ্রই যোগাযোগ করা হবে।');
                this.orderForm        = { name: '', phone: '', address: '', notes: '' };
                this.cart             = {};
                this.promoCode        = '';
                this.promoApplied     = false;
                this.promoDiscount    = 0;
                this.promoMsg         = '';
                this.promoCodeApplied = '';
            } else {
                this.showToast('❌ ' + (data.message || 'অর্ডার দেওয়া সম্ভব হয়নি।'));
            }
        } catch {
            this.showToast('❌ নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।');
        } finally {
            this.orderSubmitting = false;
        }
    },

    // ── Slider ───────────────────────────────────────────────────────────────
    slGo(this: any, idx: number) {
        this.slCurrent = Math.max(0, Math.min(idx, this.slTotal - 1));
    },

    slMove(this: any, dir: number) {
        this.slGo((this.slCurrent + dir + this.slTotal) % this.slTotal);
    },

    onTouchStart(this: any, e: TouchEvent) {
        this.slTouchStartX = e.touches[0].clientX;
    },

    onTouchEnd(this: any, e: TouchEvent) {
        const diff = this.slTouchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) this.slMove(diff > 0 ? 1 : -1);
    },

    startSliderAuto(this: any) {
        if (_slAutoTimer) clearInterval(_slAutoTimer);
        _slAutoTimer = setInterval(() => {
            if (this.slTotal > 1) this.slCurrent = (this.slCurrent + 1) % this.slTotal;
        }, 4000);
    },

    // ── Toast ─────────────────────────────────────────────────────────────────
    showToast(this: any, msg: string) {
        this.toastMsg     = msg;
        this.toastVisible = true;
        if (_toastTimer) clearTimeout(_toastTimer);
        _toastTimer = setTimeout(() => { this.toastVisible = false; }, 2500);
    },

    // ── Utilities ─────────────────────────────────────────────────────────────
    resolveUrl(this: any, path: string): string {
        if (!path) return '';
        if (/^https?:\/\//i.test(path)) return path;
        return `${window.location.origin}/${String(path).replace(/^\/+/, '')}`;
    },

    decodeHtml(this: any, html: string): string {
        if (!html) return '';
        try {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return (tmp.textContent || tmp.innerText || '').replace(/\s+/g, ' ').trim();
        } catch {
            return String(html).replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
        }
    },

    ratingStars(this: any, rating: number): string {
        const v = Math.max(0, Math.min(5, Math.round(Number(rating || 5))));
        return '★★★★★'.slice(0, v).padEnd(5, '☆');
    },

    extractList(this: any, payload: any): any[] {
        if (Array.isArray(payload)) return payload;
        if (payload && Array.isArray(payload.data)) return payload.data;
        if (payload && payload.data && Array.isArray(payload.data.data)) return payload.data.data;
        return [];
    },

    // ── API Loaders ───────────────────────────────────────────────────────────
    async loadHeroSection(this: any) {
        try {
            const res = await fetch('/api/v1/hero-sections?status=active&limit=1', {
                headers: { Accept: 'application/json' },
            });
            if (!res.ok) return;
            const payload  = await res.json();
            const list     = this.extractList(payload).filter(
                (h: any) => String(h.status || '').toLowerCase() === 'active',
            );
            const heroData = list[0];
            if (!heroData) return;

            this.hero = heroData;
            if (heroData.background_image) {
                const bgUrl = this.resolveUrl(heroData.background_image);
                this.heroStyle = {
                    backgroundImage:    `linear-gradient(135deg,rgba(26,26,46,.88) 0%,rgba(45,21,21,.82) 60%,rgba(61,26,10,.82) 100%),url('${bgUrl}')`,
                    backgroundSize:     'cover',
                    backgroundPosition: 'center',
                };
            }
        } catch (e) {
            console.error('Hero load failed:', e);
        }
    },

    async loadProductsAndCategories(this: any) {
        this.productsLoading = true;
        try {
            const [catRes, prodRes] = await Promise.all([
                fetch('/api/v1/product-categories?status=active&limit=200', { headers: { Accept: 'application/json' } }),
                fetch('/api/v1/products?status=active&limit=200',           { headers: { Accept: 'application/json' } }),
            ]);

            const catPayload  = catRes.ok  ? await catRes.json()  : null;
            const prodPayload = prodRes.ok ? await prodRes.json() : null;

            const rawCats  = this.extractList(catPayload).filter(
                (c: any) => String(c.status || 'active').toLowerCase() === 'active',
            );
            const rawProds = this.extractList(prodPayload).filter(
                (p: any) => String(p.status || 'active').toLowerCase() === 'active',
            );

            const catMap: Record<string, string> = {};
            if (rawCats.length) {
                this.categories = rawCats.map((c: any) => ({
                    id:   String(c.id ?? c.slug ?? c.name),
                    name: c.category_name || c.name || c.title || 'Category',
                }));
                this.categories.forEach((c: any) => { catMap[c.id] = c.name; });
            }

            if (rawProds.length) {
                this.menu = rawProds.map((p: any, idx: number) => {
                    const regular    = Number(p.regular_price || p.price || 0);
                    const sales      = Number(p.sales_price || 0);
                    const finalPrice = sales > 0 && sales < regular ? sales : regular;
                    const oldPrice   = regular > finalPrice ? regular : null;
                    const catId      = p.category_id != null ? String(p.category_id) : null;
                    return {
                        id:         Number(p.id),
                        emoji:      EMOJIS[idx % EMOJIS.length],
                        image:      p.image ? this.resolveUrl(p.image) : null,
                        name:       p.name || p.title || 'Product',
                        desc:       this.decodeHtml(p.description || ''),
                        price:      finalPrice,
                        oldPrice,
                        badge:      catId ? (catMap[catId] || null) : null,
                        bg:         'linear-gradient(135deg, #fff0e6 0%, #fde8d8 100%)',
                        categoryId: catId,
                    };
                });
            }
        } catch (e) {
            console.error('Products load failed:', e);
        } finally {
            this.productsLoading = false;
        }
    },

    async loadTestimonials(this: any) {
        try {
            const res = await fetch('/api/v1/testimonials?status=active&limit=8', {
                headers: { Accept: 'application/json' },
            });
            if (!res.ok) return;
            const payload = await res.json();
            const list    = this.extractList(payload)
                .filter((t: any) => String(t.status || 'active').toLowerCase() === 'active')
                .filter((t: any) => String(t.is_published ?? 1) !== '0')
                .slice(0, 8);

            this.testimonials = list.map((item: any) => ({
                ...item,
                imageUrl: (item.customer_image || item.image || item.avatar)
                    ? this.resolveUrl(item.customer_image || item.image || item.avatar)
                    : null,
            }));
        } catch (e) {
            console.error('Testimonials load failed:', e);
        }
    },

    async loadDeliveryAreas(this: any) {
        try {
            const res  = await fetch('/api/v1/public/delivery-areas', { headers: { Accept: 'application/json' } });
            if (!res.ok) return;
            const data  = await res.json();
            const areas = data.data || [];
            if (areas.length) {
                this.shippingOptions = areas.map((a: any) => ({
                    value: Number(a.delivery_charge || 0),
                    label: `${a.area_name || 'এলাকা'} — ৳ ${a.delivery_charge || 0}`,
                }));
                this.ofShip          = this.shippingOptions[0].value;
                this.modal.shipping  = this.shippingOptions[0].value;
            }
        } catch {
            // keep static defaults
        }
    },
};
