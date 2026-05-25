<template>
  <div class="od-wrap">
    <!-- ── Top bar ── -->
    <div class="od-topbar">
      <router-link class="od-btn-back" :to="{ name: `All${setup.route_prefix}` }"> <i class="fa fa-arrow-left"></i> সব অর্ডার </router-link>
      <button class="od-btn-print" @click="printInvoice"><i class="fa fa-print"></i> প্রিন্ট / PDF</button>
    </div>

    <!-- ── Loading ── -->
    <div v-if="!item.id" class="od-loading">
      <div class="spinner-border" role="status"></div>
      <p>লোড হচ্ছে…</p>
    </div>

    <template v-else>
      <!-- ══════════ INVOICE CARD ══════════ -->
      <div id="od-invoice" class="od-invoice-card">
        <!-- ── Top brand bar: Logo+Name | Invoice+OrderNo ── -->
        <div class="od-brand-bar">
          <div class="od-bb-brand">
            <img v-if="siteLogo" :src="siteLogo" class="od-bb-logo" alt="logo" />
            <span v-else class="od-bb-icon">🛒</span>
            <span class="od-bb-left">{{ siteName }}</span>
          </div>
          <div class="od-bb-right">
            <div class="od-bb-invoice">Invoice</div>
            <div class="od-bb-ordernum">#{{ item.order_number || '—' }}</div>
          </div>
        </div>

        <!-- ── Horizontal rule ── -->
        <div class="od-inv-rule"></div>

        <!-- ── Two-column: Shipping Info | Order Meta ── -->
        <div class="od-info-grid">
          <!-- LEFT: Shipping / Customer Info -->
          <div class="od-ship-section">
            <div class="od-ship-title">Shipping Info :</div>
            <div class="od-ship-row od-ship-name">Name : {{ item.customer_name || "—" }}</div>
            <div class="od-ship-row od-ship-name">Phone : {{ item.customer_phone || "—" }}</div>
            <div v-if="item.customer_email" class="od-ship-row">Email : {{ item.customer_email }}</div>
            <div class="od-ship-row od-ship-name">Address : {{ item.delivery_address || "—" }}</div>
            <div v-if="item.delivery_thana" class="od-ship-row">Thana : {{ item.delivery_thana }}</div>
            <div v-if="item.delivery_district" class="od-ship-row">District : {{ item.delivery_district }}</div>
            <div v-if="item.special_notes" class="od-ship-row">Note : {{ item.special_notes }}</div>
          </div>

          <!-- RIGHT: Order Meta -->
          <div class="od-order-meta">
           
            <div class="od-om-row">
              <span class="od-om-label">Order Date:</span>
              <span class="od-om-val">{{ fmtDate(item.created_at) }}</span>
            </div>
            <div class="od-om-row">
              <span class="od-om-label">Order Status:</span>
              <span :class="orderStatusClass(item.order_status)" class="od-badge">{{ orderStatusLabel(item.order_status) }}</span>
            </div>
            <div class="od-om-row">
              <span class="od-om-label">Payment Method:</span>
              <span class="od-badge od-badge--info">{{ item.payment_method || "COD" }}</span>
            </div>
            <div class="od-om-row">
              <span class="od-om-label">Payment Status:</span>
              <span :class="paymentStatusClass(item.payment_status)" class="od-badge">{{ item.payment_status || "pending" }}</span>
            </div>
          </div>
        </div>

        <!-- ── Horizontal rule ── -->
        <div class="od-inv-rule"></div>

        <!-- ── Items table ── -->
        <div class="od-items-section">
          <div class="od-section-label">🛍 অর্ডারকৃত পণ্য</div>
          <table class="od-table">
            <thead>
              <tr>
                <th style="width: 40px">#</th>
                <th>পণ্যের নাম</th>
                <th class="tc" style="width: 80px">পরিমাণ</th>
                <th class="tr" style="width: 110px">একক মূল্য</th>
                <th class="tr" style="width: 110px">মোট</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="orderItems.length">
                <tr v-for="(row, i) in orderItems" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>
                    <div class="od-item-name">{{ row.product_name || row.name || "—" }}</div>
                    <div v-if="row.variant" class="od-item-variant">{{ row.variant }}</div>
                  </td>
                  <td class="tc">{{ row.quantity }}</td>
                  <td class="tr">৳ {{ fmtMoney(row.unit_price) }}</td>
                  <td class="tr">
                    <strong>৳ {{ fmtMoney(row.total_price) }}</strong>
                  </td>
                </tr>
              </template>
              <tr v-else>
                <td colspan="5" class="tc od-empty-row">পণ্যের তথ্য পাওয়া যায়নি</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- ── Totals ── -->
        <div class="od-totals-wrap">
          <div class="od-totals">
            <div class="od-total-line">
              <span>সাবটোটাল</span>
              <span>৳ {{ fmtMoney(item.subtotal) }}</span>
            </div>
            <div v-if="Number(item.shipping_charge) > 0" class="od-total-line">
              <span>ডেলিভারি চার্জ</span>
              <span>৳ {{ fmtMoney(item.shipping_charge) }}</span>
            </div>
            <div v-if="Number(item.discount_amount) > 0" class="od-total-line od-discount-line">
              <span
                >ছাড় <span v-if="item.promo_code_used">({{ item.promo_code_used }})</span></span
              >
              <span>− ৳ {{ fmtMoney(item.discount_amount) }}</span>
            </div>
            <div class="od-total-line od-grand-line">
              <span>সর্বমোট</span>
              <span>৳ {{ fmtMoney(item.total_amount) }}</span>
            </div>
          </div>
        </div>
      </div>
      <!-- ══════════ END INVOICE ══════════ -->

      <!-- ══════════ STATUS UPDATE FORM ══════════ -->
      <form class="od-update-card" @submit.prevent="submitUpdate" ref="formRef">
        <!-- pass existing fields so API doesn't wipe them -->
        <input type="hidden" name="customer_name" :value="item.customer_name" />
        <input type="hidden" name="customer_phone" :value="item.customer_phone" />
        <input type="hidden" name="customer_email" :value="item.customer_email" />
        <input type="hidden" name="delivery_address" :value="item.delivery_address" />
        <input type="hidden" name="subtotal" :value="item.subtotal" />
        <input type="hidden" name="shipping_charge" :value="item.shipping_charge" />
        <input type="hidden" name="discount_amount" :value="item.discount_amount" />
        <input type="hidden" name="total_amount" :value="item.total_amount" />

        <div class="od-update-head"><i class="fa fa-pencil-alt"></i> অর্ডার স্ট্যাটাস আপডেট</div>

        <div class="od-update-body">
          <div class="od-field">
            <label>অর্ডার স্ট্যাটাস</label>
            <select name="order_status" v-model="edit.order_status" class="form-control">
              <option value="pending">⏳ Pending</option>
              <option value="confirmed">✅ Confirmed</option>
              <option value="processing">⚙️ Processing</option>
              <option value="shipped">🚚 Shipped</option>
              <option value="delivered">📦 Delivered</option>
              <option value="cancelled">❌ Cancelled</option>
              <option value="returned">↩️ Returned</option>
            </select>
          </div>

          <div class="od-field">
            <label>পেমেন্ট স্ট্যাটাস</label>
            <select name="payment_status" v-model="edit.payment_status" class="form-control">
              <option value="pending">⏳ Pending</option>
              <option value="completed">✅ Completed</option>
              <option value="failed">❌ Failed</option>
              <option value="refunded">↩️ Refunded</option>
            </select>
          </div>

          <div class="od-field">
            <label>ডেলিভারি তারিখ</label>
            <input type="date" name="delivery_date" v-model="edit.delivery_date" class="form-control" />
          </div>

          <div class="od-field od-field-full">
            <label>অ্যাডমিন নোট</label>
            <textarea name="admin_notes" v-model="edit.admin_notes" rows="3" class="form-control" placeholder="শুধুমাত্র অ্যাডমিন দেখবেন…"></textarea>
          </div>
        </div>

        <div class="od-update-foot">
          <button type="submit" class="od-save-btn" :disabled="saving">
            <i class="fa fa-save"></i>
            {{ saving ? "সংরক্ষণ হচ্ছে…" : "পরিবর্তন সংরক্ষণ করুন" }}
          </button>
        </div>
      </form>
    </template>
  </div>
</template>

<script>
import { mapActions, mapWritableState } from "pinia";
import { store } from "../store";
import setup from "../setup";
import { site_settings_store } from "@/GlobalStore/site_settings_store.js";

export default {
  data: () => ({
    setup,
    saving: false,
    siteName: "",
    siteLogo: "",
    edit: { order_status: "", payment_status: "", delivery_date: "", admin_notes: "" },
  }),

  created() {
    this.loadData(this.$route.params.id);
    this.loadSite();
  },

  methods: {
    ...mapActions(store, { details: "details", update: "update" }),

    async loadData(slug) {
      this.item = {};
      await this.details(slug);
      this.edit.order_status = this.item.order_status || "pending";
      this.edit.payment_status = this.item.payment_status || "pending";
      this.edit.delivery_date = this.item.delivery_date || "";
      this.edit.admin_notes = this.item.admin_notes || "";
    },

    async loadSite() {
      try {
        const s = site_settings_store();
        await s.get_all_website_settings();
        const name = s.get_setting_value("site_name");
        const logo = s.get_setting_value("image");
        if (name) this.siteName = name;
        if (logo) this.siteLogo = logo.startsWith("http") ? logo : "/" + logo.replace(/^\//, "");
      } catch (_) {}
    },

    async submitUpdate() {
      this.saving = true;
      const res = await this.update({ target: this.$refs.formRef });
      if ([200, 201].includes(res?.status)) {
        window.s_alert("অর্ডার সফলভাবে আপডেট হয়েছে!", "success");
        await this.loadData(this.item.slug);
      } else {
        window.s_alert("আপডেট করা সম্ভব হয়নি।", "error");
      }
      this.saving = false;
    },

    async printInvoice() {
      const el = document.getElementById("od-invoice");
      if (!el) return;

      // clone so we can swap logo src to absolute URL
      const clone = el.cloneNode(true);
      const logoImg = clone.querySelector(".od-bb-logo");
      if (logoImg && this.siteLogo) {
        const abs = this.siteLogo.startsWith("http")
          ? this.siteLogo
          : window.location.origin + (this.siteLogo.startsWith("/") ? "" : "/") + this.siteLogo;
        try {
          const res = await fetch(abs);
          const blob = await res.blob();
          const b64 = await new Promise((r) => {
            const fr = new FileReader();
            fr.onload = (e) => r(e.target.result);
            fr.readAsDataURL(blob);
          });
          logoImg.src = b64;
        } catch (_) {
          logoImg.src = abs;
        }
      }

      const html = `<!DOCTYPE html><html lang="bn"><head>
        <meta charset="utf-8"/>
        <title>Invoice #${this.item.order_number || ""}</title>
        <style>
          *{box-sizing:border-box;margin:0;padding:0}
          body{font-family:"SolaimanLipi","Noto Sans Bengali",Arial,sans-serif;font-size:13px;color:#111;padding:28px 32px;background:#fff}

          /* brand bar */
          .od-brand-bar{display:flex;justify-content:space-between;align-items:center;padding-bottom:4px}
          .od-bb-brand{display:flex;align-items:center;gap:10px}
          .od-bb-logo{height:44px;width:auto;border-radius:8px;object-fit:contain}
          .od-bb-icon{font-size:34px}
          .od-bb-left{font-size:22px;font-weight:800;color:#1a1a2e;letter-spacing:.3px}
          .od-bb-right{text-align:right}
          .od-bb-invoice{font-size:22px;font-weight:800;color:#1a1a2e;letter-spacing:1px;line-height:1.1}
          .od-bb-ordernum{font-size:13px;color:#6c757d;font-weight:600;margin-top:2px}

          /* rule */
          .od-inv-rule{border:none;border-top:1px solid #e0e0e0;margin:16px 0}

          /* info grid */
          .od-info-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:0}
          .od-ship-section{display:flex;flex-direction:column;gap:4px}
          .od-ship-title{font-weight:700;font-size:14px;color:#1a1a2e;margin-bottom:6px}
          .od-ship-row{font-size:13px;color:#495057;line-height:1.7}
          .od-ship-name{font-weight:700;color:#1a1a2e}
          .od-order-meta{display:flex;flex-direction:column;gap:7px;align-items:flex-end;text-align:right;margin-left:auto}
          .od-om-row{display:flex;align-items:center;justify-content:flex-end;gap:12px;font-size:13px}
          .od-om-label{font-weight:700;color:#1a1a2e;flex-shrink:0}
          .od-om-val{color:#495057}

          /* badges */
          .od-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600}
          .od-badge--warning{background:#fff3cd;color:#856404}
          .od-badge--success{background:#d1e7dd;color:#0a3622}
          .od-badge--info{background:#cff4fc;color:#055160}
          .od-badge--primary{background:#cfe2ff;color:#084298}
          .od-badge--purple{background:#e9d8fd;color:#5a0080}
          .od-badge--danger{background:#f8d7da;color:#842029}
          .od-badge--secondary{background:#e2e3e5;color:#41464b}

          /* items */
          .od-section-label{font-size:14px;font-weight:700;color:#1a1a2e;margin-bottom:10px}
          .od-items-section{margin-bottom:20px}
          .od-table{width:100%;border-collapse:collapse;font-size:13px}
          .od-table thead tr{background:#1a1a2e}
          .od-table th{padding:10px 12px;color:#fff;font-weight:600;text-align:left}
          .od-table tbody tr{border-bottom:1px solid #f0f0f0;background:#fff}
          .od-table td{padding:10px 12px;color:#1a1a2e;background:#fff}
          .od-item-name{font-weight:600;color:#1a1a2e}
          .od-item-variant{font-size:11px;color:#888;margin-top:2px}
          .tc{text-align:center}.tr{text-align:right}

          /* totals */
          .od-totals-wrap{display:flex;justify-content:flex-end;margin-top:8px}
          .od-totals{width:280px}
          .od-total-line{display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-bottom:1px solid #f0f0f0;color:#444}
          .od-discount-line{color:#198754}
          .od-grand-line{font-size:16px;font-weight:800;color:#1a1a2e;border-top:2px solid #1a1a2e;border-bottom:none;padding-top:10px;margin-top:4px}
        </style>
      </head><body>${clone.innerHTML}</body></html>`;

      const blob = new Blob([html], { type: "text/html" });
      const url = URL.createObjectURL(blob);
      const win = window.open(url, "_blank");
      if (win)
        win.onload = () => {
          win.print();
          URL.revokeObjectURL(url);
        };
    },

    fmtDate(d) {
      if (!d) return "—";
      return new Date(d).toLocaleDateString("bn-BD", { year: "numeric", month: "long", day: "numeric" });
    },

    fmtMoney(v) {
      return Number(v || 0).toLocaleString("en-BD");
    },

    orderStatusClass(s) {
      return (
        {
          pending: "od-badge--warning",
          confirmed: "od-badge--info",
          processing: "od-badge--primary",
          shipped: "od-badge--purple",
          delivered: "od-badge--success",
          cancelled: "od-badge--danger",
          returned: "od-badge--secondary",
        }[s] || "od-badge--secondary"
      );
    },

    orderStatusLabel(s) {
      return (
        {
          pending: "⏳ Pending",
          confirmed: "✅ Confirmed",
          processing: "⚙️ Processing",
          shipped: "🚚 Shipped",
          delivered: "📦 Delivered",
          cancelled: "❌ Cancelled",
          returned: "↩️ Returned",
        }[s] ||
        s ||
        "—"
      );
    },

    paymentStatusClass(s) {
      return (
        {
          pending: "od-badge--warning",
          completed: "od-badge--success",
          failed: "od-badge--danger",
          refunded: "od-badge--info",
        }[s] || "od-badge--secondary"
      );
    },
  },

  computed: {
    ...mapWritableState(store, { item: "item" }),
    orderItems() {
      return this.item?.order_items || this.item?.items || [];
    },
  },
};
</script>

<style scoped>
/* ── wrapper ── */
.od-wrap {
  padding: 20px;
  max-width: 1120px;
  margin: 0 auto;
}

/* ── top bar ── */
.od-topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}
.od-btn-back {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 13px;
  border: 1px solid #dee2e6;
  color: #555;
  text-decoration: none;
  background: transparent;
  transition: 0.2s;
}
.od-btn-back:hover {
  background: #f1f3f5;
  color: #111;
}
.od-btn-print {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 20px;
  border-radius: 8px;
  font-size: 13px;
  border: none;
  background: #0d6efd;
  color: #fff;
  cursor: pointer;
  transition: 0.2s;
}
.od-btn-print:hover {
  background: #0b5ed7;
}

/* ── loading ── */
.od-loading {
  text-align: center;
  padding: 60px 0;
  color: #6c757d;
}

/* ── invoice card ── */
.od-invoice-card {
  background: #fff;
  border: 1px solid #e4e6ea;
  border-radius: 14px;
  padding: 32px 36px;
  margin-bottom: 24px;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
}

/* ── brand bar ── */
.od-brand-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 4px;
}
.od-bb-brand {
  display: flex;
  align-items: center;
  gap: 10px;
}
.od-bb-logo {
  height: 44px;
  width: auto;
  border-radius: 8px;
  object-fit: contain;
}
.od-bb-icon { font-size: 34px; }
.od-bb-left {
  font-size: 22px;
  font-weight: 800;
  color: #1a1a2e;
  letter-spacing: .3px;
}
.od-bb-right {
  text-align: right;
}
.od-bb-invoice {
  font-size: 22px;
  font-weight: 800;
  color: #1a1a2e;
  letter-spacing: 1px;
  line-height: 1.1;
}
.od-bb-ordernum {
  font-size: 13px;
  color: #6c757d;
  font-weight: 600;
  margin-top: 2px;
}

/* ── two-column info grid ── */
.od-info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 0;
}

/* shipping info */
.od-ship-section {
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.od-ship-title {
  font-weight: 700;
  font-size: 14px;
  color: #1a1a2e;
  margin-bottom: 6px;
}
.od-ship-row {
  font-size: 13px;
  color: #495057;
  line-height: 1.7;
}
.od-ship-name {
  font-weight: 700;
  color: #1a1a2e;
}

/* order meta */
.od-order-meta {
  display: flex;
  flex-direction: column;
  gap: 7px;
  align-items: flex-end;
  text-align: right;
  margin-left: auto;
}
.od-om-row {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 12px;
  font-size: 13px;
}
.od-om-label {
  font-weight: 700;
  color: #1a1a2e;
  flex-shrink: 0;
}
.od-om-val {
  color: #495057;
}

/* badges */
.od-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}
.od-badge--warning {
  background: #fff3cd;
  color: #856404;
}
.od-badge--info {
  background: #cff4fc;
  color: #055160;
}
.od-badge--primary {
  background: #cfe2ff;
  color: #084298;
}
.od-badge--purple {
  background: #e9d8fd;
  color: #5a0080;
}
.od-badge--success {
  background: #d1e7dd;
  color: #0a3622;
}
.od-badge--danger {
  background: #f8d7da;
  color: #842029;
}
.od-badge--secondary {
  background: #e2e3e5;
  color: #41464b;
}

/* horizontal rule */
.od-inv-rule {
  border: none;
  border-top: 1px solid #e9ecef;
  margin: 24px 0;
}

/* items table */
.od-items-section {
  margin-bottom: 20px;
}
.od-section-label {
  font-size: 15px;
  font-weight: 700;
  color: #1a1a2e;
  margin-bottom: 12px;
}
.od-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}
.od-table thead tr {
  background: #1a1a2e;
}
.od-table thead th {
  padding: 11px 14px;
  color: #fff;
  font-weight: 600;
  text-align: left;
}
.od-table tbody tr {
  border-bottom: 1px solid #f0f2f5;
  background: #fff;
}
.od-table tbody tr:last-child {
  border-bottom: none;
}
.od-table tbody tr:hover {
  background: #f8f9fa;
}
.od-table td {
  padding: 12px 14px;
  color: #2c2c3e;
  background: #fff;
}
.od-item-name {
  font-weight: 600;
  color: #1a1a2e;
}
.od-item-variant {
  font-size: 12px;
  color: #6c757d;
  margin-top: 2px;
}
.od-empty-row {
  color: #6c757d;
  padding: 20px;
}
.tc {
  text-align: center;
}
.tr {
  text-align: right;
}

/* totals */
.od-totals-wrap {
  display: flex;
  justify-content: flex-end;
}
.od-totals {
  width: 300px;
}
.od-total-line {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 7px 0;
  font-size: 14px;
  border-bottom: 1px solid #f0f2f5;
  color: #495057;
}
.od-discount-line {
  color: #198754;
}
.od-grand-line {
  font-size: 17px;
  font-weight: 800;
  color: #1a1a2e;
  border-top: 2px solid #1a1a2e;
  border-bottom: none;
  padding-top: 10px;
  margin-top: 4px;
}

/* ── update card ── */
.od-update-card {
  background: #fff;
  border: 1px solid #e4e6ea;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
}
.od-update-head {
  background: #1a1a2e;
  color: #fff;
  padding: 15px 24px;
  font-size: 15px;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 8px;
}
.od-update-body {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 16px;
  padding: 24px;
}
.od-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.od-field-full {
  grid-column: 1 / -1;
}
.od-field label {
  font-size: 13px;
  font-weight: 600;
  color: #495057;
}
.od-update-foot {
  padding: 16px 24px;
  border-top: 1px solid #e4e6ea;
  background: #f8f9fa;
}
.od-save-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 28px;
  border: none;
  border-radius: 8px;
  background: #198754;
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}
.od-save-btn:hover {
  background: #157347;
}
.od-save-btn:disabled {
  background: #999;
  cursor: not-allowed;
}

/* ── responsive ── */
@media (max-width: 768px) {
  .od-brand-bar {
    grid-template-columns: 1fr;
    gap: 8px;
  }
  .od-bb-right {
    text-align: left;
  }
  .od-info-grid {
    grid-template-columns: 1fr;
  }
  .od-update-body {
    grid-template-columns: 1fr;
  }
  .od-invoice-card {
    padding: 20px 16px;
  }
  .od-totals {
    width: 100%;
  }
}

/* ════════════════════════════════════
   DARK THEME
════════════════════════════════════ */

/* page bg */
:global(.dark-theme) .od-wrap {
  color: #e8e8f0;
}

/* top bar */
:global(.dark-theme) .od-btn-back {
  border-color: #2e2e45;
  color: #9090b8;
  background: transparent;
}
:global(.dark-theme) .od-btn-back:hover {
  background: #1e1e2e;
  color: #e8e8f0;
}

/* invoice card */
:global(.dark-theme) .od-invoice-card {
  background: #1a1a2e;
  border-color: #2e2e45;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.4);
}

/* brand bar */
:global(.dark-theme) .od-bb-left {
  color: #e8e8f0;
}
:global(.dark-theme) .od-bb-invoice {
  color: #e8e8f0;
}
:global(.dark-theme) .od-bb-ordernum {
  color: #7878a0;
}

/* divider */
:global(.dark-theme) .od-inv-rule {
  border-top-color: #2e2e45;
}

/* shipping info — keep readable in dark */
:global(.dark-theme) .od-ship-title {
  color: #e8e8f0;
}
:global(.dark-theme) .od-ship-row {
  color: #b0b0cc;
}
:global(.dark-theme) .od-ship-name {
  color: #f0f0ff;
}

/* order meta */
:global(.dark-theme) .od-om-label {
  color: #e8e8f0;
}
:global(.dark-theme) .od-om-val {
  color: #b0b0cc;
}

/* section label */
:global(.dark-theme) .od-section-label {
  color: #e8e8f0;
}

/* items table */
:global(.dark-theme) .od-table thead tr {
  background: #0e0e22;
}
:global(.dark-theme) .od-table thead th {
  color: #c8c8e8;
}
:global(.dark-theme) .od-table tbody tr {
  border-bottom-color: #e9ecef;
  background: #fff !important;
}
:global(.dark-theme) .od-table tbody tr:hover {
  background: #f8f9fa !important;
}
:global(.dark-theme) .od-table td {
  color: #1a1a2e !important;
  background: #fff !important;
}
:global(.dark-theme) .od-item-name {
  color: #1a1a2e !important;
}
:global(.dark-theme) .od-item-variant {
  color: #7878a0;
}
:global(.dark-theme) .od-empty-row {
  color: #7878a0;
}

/* totals */
:global(.dark-theme) .od-total-line {
  border-bottom-color: #2a2a42;
  color: #a8a8c8;
}
:global(.dark-theme) .od-discount-line {
  color: #6fcf97;
}
:global(.dark-theme) .od-grand-line {
  color: #f0f0ff;
  border-top-color: #4a4a7a;
}

/* update card */
:global(.dark-theme) .od-update-card {
  background: #1a1a2e;
  border-color: #2e2e45;
}
:global(.dark-theme) .od-update-head {
  background: #0e0e22;
}
:global(.dark-theme) .od-update-body {
  background: #1a1a2e;
}
:global(.dark-theme) .od-field label {
  color: #9090b8;
}
:global(.dark-theme) .od-update-foot {
  background: #12122a;
  border-top-color: #2e2e45;
}

/* form controls — force override Bootstrap */
:global(.dark-theme) .od-update-card .form-control,
:global(.dark-theme) .od-update-card select,
:global(.dark-theme) .od-update-card textarea,
:global(.dark-theme) .od-update-card input[type="date"],
:global(.dark-theme) .od-update-card input[type="text"] {
  background-color: #12122a !important;
  border-color: #2e2e45 !important;
  color: #e8e8f0 !important;
}
:global(.dark-theme) .od-update-card .form-control:focus,
:global(.dark-theme) .od-update-card select:focus,
:global(.dark-theme) .od-update-card textarea:focus,
:global(.dark-theme) .od-update-card input:focus {
  border-color: #5a5ab0 !important;
  box-shadow: 0 0 0 3px rgba(90, 90, 176, 0.25) !important;
  outline: none;
}
:global(.dark-theme) .od-update-card select option {
  background: #1a1a2e;
  color: #e8e8f0;
}
:global(.dark-theme) .od-update-card input[type="date"]::-webkit-calendar-picker-indicator {
  filter: invert(1);
}
</style>
