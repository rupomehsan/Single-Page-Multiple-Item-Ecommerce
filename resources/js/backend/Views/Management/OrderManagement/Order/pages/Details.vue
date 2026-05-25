<template>
  <div class="od-page">

    <!-- Top Bar -->
    <div class="od-topbar d-print-none">
      <router-link class="od-back-btn" :to="{ name: `All${setup.route_prefix}` }">
        <i class="fa fa-arrow-left"></i> সব অর্ডার
      </router-link>
      <div class="od-topbar-right">
        <button class="od-btn-print" @click="printInvoice">
          <i class="fa fa-print"></i> প্রিন্ট / PDF
        </button>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="!item.id" class="od-loading">
      <div class="spinner-border text-primary" role="status"></div>
      <p>লোড হচ্ছে…</p>
    </div>

    <div v-else>

      <!-- ═══ PRINTABLE INVOICE ═══ -->
      <div id="od-invoice" class="od-invoice">

        <!-- Invoice Header -->
        <div class="od-inv-head">
          <div class="od-inv-brand">
            <div class="od-inv-logo">🛒</div>
            <div class="od-inv-brand-name">Order Management</div>
          </div>
          <div class="od-inv-meta">
            <div class="od-inv-title">INVOICE</div>
            <div class="od-inv-meta-row">
              <span>অর্ডার নং:</span>
              <strong>#{{ item.order_number }}</strong>
            </div>
            <div class="od-inv-meta-row">
              <span>তারিখ:</span>
              <strong>{{ formatDate(item.created_at) }}</strong>
            </div>
            <div class="od-inv-meta-row">
              <span>অর্ডার স্ট্যাটাস:</span>
              <span :class="statusClass(item.order_status)" class="od-badge">
                {{ statusLabel(item.order_status) }}
              </span>
            </div>
            <div class="od-inv-meta-row">
              <span>পেমেন্ট স্ট্যাটাস:</span>
              <span :class="paymentClass(item.payment_status)" class="od-badge">
                {{ item.payment_status || 'pending' }}
              </span>
            </div>
          </div>
        </div>

        <hr class="od-divider" />

        <!-- Customer + Delivery Info -->
        <div class="od-info-row">
          <div class="od-info-card">
            <div class="od-info-card-title"><i class="fa fa-user"></i> গ্রাহকের তথ্য</div>
            <table class="od-info-table">
              <tr><td>নাম</td><td><strong>{{ item.customer_name || '—' }}</strong></td></tr>
              <tr><td>মোবাইল</td><td>{{ item.customer_phone || '—' }}</td></tr>
              <tr v-if="item.customer_email"><td>ইমেইল</td><td>{{ item.customer_email }}</td></tr>
            </table>
          </div>
          <div class="od-info-card">
            <div class="od-info-card-title"><i class="fa fa-map-marker"></i> ডেলিভারি ঠিকানা</div>
            <table class="od-info-table">
              <tr><td>ঠিকানা</td><td><strong>{{ item.delivery_address || '—' }}</strong></td></tr>
              <tr v-if="item.delivery_thana"><td>থানা</td><td>{{ item.delivery_thana }}</td></tr>
              <tr v-if="item.delivery_district"><td>জেলা</td><td>{{ item.delivery_district }}</td></tr>
              <tr v-if="item.delivery_date"><td>ডেলিভারি তারিখ</td><td>{{ item.delivery_date }}</td></tr>
            </table>
          </div>
          <div class="od-info-card" v-if="item.special_notes">
            <div class="od-info-card-title"><i class="fa fa-sticky-note"></i> গ্রাহকের নোট</div>
            <p class="od-note-text">{{ item.special_notes }}</p>
          </div>
        </div>

        <!-- Order Items Table -->
        <div class="od-items-section">
          <div class="od-section-title">🛍 অর্ডারকৃত পণ্য</div>
          <table class="od-items-table">
            <thead>
              <tr>
                <th>#</th>
                <th>পণ্যের নাম</th>
                <th class="text-center">পরিমাণ</th>
                <th class="text-right">একক মূল্য</th>
                <th class="text-right">মোট</th>
              </tr>
            </thead>
            <tbody>
              <template v-if="orderItems.length > 0">
                <tr v-for="(row, i) in orderItems" :key="i">
                  <td>{{ i + 1 }}</td>
                  <td>
                    <div class="od-item-name">{{ row.product_name || row.name || '—' }}</div>
                    <div v-if="row.variant" class="od-item-variant">{{ row.variant }}</div>
                  </td>
                  <td class="text-center">{{ row.quantity }}</td>
                  <td class="text-right">৳ {{ formatMoney(row.unit_price) }}</td>
                  <td class="text-right"><strong>৳ {{ formatMoney(row.total_price) }}</strong></td>
                </tr>
              </template>
              <tr v-else>
                <td colspan="5" class="text-center text-muted py-3">পণ্যের তথ্য পাওয়া যায়নি</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Totals -->
        <div class="od-totals-wrap">
          <div class="od-totals">
            <div class="od-total-row">
              <span>সাবটোটাল</span>
              <span>৳ {{ formatMoney(item.subtotal) }}</span>
            </div>
            <div class="od-total-row" v-if="Number(item.discount_amount) > 0">
              <span>ছাড় <span v-if="item.promo_code_used">({{ item.promo_code_used }})</span></span>
              <span class="text-success">− ৳ {{ formatMoney(item.discount_amount) }}</span>
            </div>
            <div class="od-total-row">
              <span>ডেলিভারি চার্জ</span>
              <span>৳ {{ formatMoney(item.shipping_charge) }}</span>
            </div>
            <div class="od-total-row od-grand-total">
              <span>সর্বমোট</span>
              <span>৳ {{ formatMoney(item.total_amount) }}</span>
            </div>
          </div>
        </div>

      </div>
      <!-- ═══ END INVOICE ═══ -->


      <!-- ═══ STATUS UPDATE FORM (non-printable) ═══ -->
      <form class="od-update-card d-print-none" @submit.prevent="submitUpdate" ref="updateFormRef">

        <!-- Hidden: pass all existing fields so API doesn't wipe them -->
        <input type="hidden" name="customer_name"    :value="item.customer_name" />
        <input type="hidden" name="customer_phone"   :value="item.customer_phone" />
        <input type="hidden" name="customer_email"   :value="item.customer_email" />
        <input type="hidden" name="delivery_address" :value="item.delivery_address" />
        <input type="hidden" name="subtotal"         :value="item.subtotal" />
        <input type="hidden" name="shipping_charge"  :value="item.shipping_charge" />
        <input type="hidden" name="discount_amount"  :value="item.discount_amount" />
        <input type="hidden" name="total_amount"     :value="item.total_amount" />
        <input type="hidden" name="special_notes"    :value="item.special_notes" />

        <div class="od-update-header">
          <i class="fa fa-edit"></i> অর্ডার আপডেট করুন
        </div>

        <div class="od-update-grid">

          <div class="od-update-field">
            <label>অর্ডার স্ট্যাটাস</label>
            <select name="order_status" v-model="editData.order_status" class="form-control">
              <option value="pending">⏳ Pending</option>
              <option value="confirmed">✅ Confirmed</option>
              <option value="processing">⚙️ Processing</option>
              <option value="shipped">🚚 Shipped</option>
              <option value="delivered">📦 Delivered</option>
              <option value="cancelled">❌ Cancelled</option>
              <option value="returned">↩️ Returned</option>
            </select>
          </div>

          <div class="od-update-field">
            <label>পেমেন্ট স্ট্যাটাস</label>
            <select name="payment_status" v-model="editData.payment_status" class="form-control">
              <option value="pending">⏳ Pending</option>
              <option value="completed">✅ Completed</option>
              <option value="failed">❌ Failed</option>
              <option value="refunded">↩️ Refunded</option>
            </select>
          </div>

          <div class="od-update-field">
            <label>ডেলিভারি তারিখ</label>
            <input type="date" name="delivery_date" v-model="editData.delivery_date" class="form-control" />
          </div>

          <div class="od-update-field od-update-field--full">
            <label>অ্যাডমিন নোট (শুধুমাত্র অ্যাডমিন দেখবেন)</label>
            <textarea name="admin_notes" v-model="editData.admin_notes" rows="3" class="form-control" placeholder="অর্ডার সম্পর্কে কোনো নোট…"></textarea>
          </div>

        </div>

        <div class="od-update-footer">
          <button type="submit" class="od-save-btn" :disabled="saving">
            <i class="fa fa-save"></i>
            {{ saving ? 'সংরক্ষণ হচ্ছে…' : 'পরিবর্তন সংরক্ষণ করুন' }}
          </button>
        </div>

      </form>

    </div>
  </div>
</template>

<script>
import { mapActions, mapWritableState } from 'pinia';
import { store } from '../store';
import setup from '../setup';

export default {
  data: () => ({
    setup,
    saving: false,
    editData: {
      order_status:   '',
      payment_status: '',
      delivery_date:  '',
      admin_notes:    '',
    },
  }),

  created() {
    this.get_data(this.$route.params.id);
  },

  methods: {
    ...mapActions(store, { details: 'details', update: 'update' }),

    async get_data(slug) {
      this.item = {};
      await this.details(slug);
      this.editData.order_status   = this.item.order_status   || 'pending';
      this.editData.payment_status = this.item.payment_status || 'pending';
      this.editData.delivery_date  = this.item.delivery_date  || '';
      this.editData.admin_notes    = this.item.admin_notes    || '';
    },

    async submitUpdate() {
      this.saving = true;
      const response = await this.update({ target: this.$refs.updateFormRef });
      if ([200, 201].includes(response?.status)) {
        window.s_alert('অর্ডার সফলভাবে আপডেট হয়েছে!', 'success');
        await this.get_data(this.item.slug);
      } else {
        window.s_alert('আপডেট করা সম্ভব হয়নি।', 'error');
      }
      this.saving = false;
    },

    printInvoice() {
      window.print();
    },

    formatDate(d) {
      if (!d) return '—';
      return new Date(d).toLocaleDateString('bn-BD', { year: 'numeric', month: 'long', day: 'numeric' });
    },

    formatMoney(v) {
      return Number(v || 0).toLocaleString('en-BD');
    },

    statusClass(s) {
      const map = {
        pending:    'od-badge--warning',
        confirmed:  'od-badge--info',
        processing: 'od-badge--primary',
        shipped:    'od-badge--purple',
        delivered:  'od-badge--success',
        cancelled:  'od-badge--danger',
        returned:   'od-badge--secondary',
      };
      return map[s] || 'od-badge--secondary';
    },

    statusLabel(s) {
      const map = {
        pending:    '⏳ Pending',
        confirmed:  '✅ Confirmed',
        processing: '⚙️ Processing',
        shipped:    '🚚 Shipped',
        delivered:  '📦 Delivered',
        cancelled:  '❌ Cancelled',
        returned:   '↩️ Returned',
      };
      return map[s] || s || '—';
    },

    paymentClass(s) {
      const map = {
        pending:   'od-badge--warning',
        completed: 'od-badge--success',
        failed:    'od-badge--danger',
        refunded:  'od-badge--info',
      };
      return map[s] || 'od-badge--secondary';
    },
  },

  computed: {
    ...mapWritableState(store, { item: 'item' }),

    orderItems() {
      return this.item.order_items || this.item.items || [];
    },
  },
};
</script>

<style scoped>
/* ── Layout ── */
.od-page { padding: 20px; max-width: 1100px; margin: 0 auto; }

/* ── Top Bar ── */
.od-topbar {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 20px;
}
.od-back-btn {
  display: inline-flex; align-items: center; gap: 6px;
  color: #6c757d; text-decoration: none; font-size: 14px;
  padding: 7px 14px; border: 1px solid #dee2e6; border-radius: 8px;
  transition: all .2s;
}
.od-back-btn:hover { background: #f8f9fa; color: #333; }
.od-btn-print {
  background: #0d6efd; color: #fff; border: none; cursor: pointer;
  padding: 8px 18px; border-radius: 8px; font-size: 14px;
  display: inline-flex; align-items: center; gap: 6px;
}
.od-btn-print:hover { background: #0b5ed7; }

/* ── Loading ── */
.od-loading { text-align: center; padding: 60px 0; color: #6c757d; }

/* ── Invoice ── */
.od-invoice {
  background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
  padding: 32px 36px; margin-bottom: 24px;
  box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.od-inv-head {
  display: flex; justify-content: space-between; align-items: flex-start;
  margin-bottom: 24px;
}
.od-inv-brand { display: flex; align-items: center; gap: 12px; }
.od-inv-logo { font-size: 36px; }
.od-inv-brand-name { font-size: 20px; font-weight: 700; color: #1a1a2e; }
.od-inv-title { font-size: 26px; font-weight: 800; color: #1a1a2e; text-align: right; margin-bottom: 8px; }
.od-inv-meta-row {
  display: flex; justify-content: flex-end; gap: 10px; align-items: center;
  font-size: 13px; margin-bottom: 4px;
}
.od-inv-meta-row span:first-child { color: #6c757d; }
.od-divider { border-color: #e9ecef; margin: 0 0 24px; }

/* ── Badges ── */
.od-badge {
  display: inline-block; padding: 3px 10px; border-radius: 20px;
  font-size: 12px; font-weight: 600; white-space: nowrap;
}
.od-badge--warning   { background: #fff3cd; color: #856404; }
.od-badge--info      { background: #cff4fc; color: #055160; }
.od-badge--primary   { background: #cfe2ff; color: #084298; }
.od-badge--purple    { background: #e9d8fd; color: #5a0080; }
.od-badge--success   { background: #d1e7dd; color: #0a3622; }
.od-badge--danger    { background: #f8d7da; color: #842029; }
.od-badge--secondary { background: #e2e3e5; color: #41464b; }

/* ── Info Cards ── */
.od-info-row { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 28px; }
.od-info-card {
  background: #f8f9fa; border-radius: 10px; padding: 16px 18px;
  border: 1px solid #e9ecef;
}
.od-info-card-title {
  font-size: 13px; font-weight: 700; color: #495057;
  margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
}
.od-info-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.od-info-table td { padding: 4px 0; vertical-align: top; }
.od-info-table td:first-child { color: #6c757d; width: 80px; padding-right: 8px; }
.od-note-text { font-size: 13px; color: #495057; margin: 0; line-height: 1.6; }

/* ── Items Table ── */
.od-items-section { margin-bottom: 24px; }
.od-section-title { font-size: 15px; font-weight: 700; color: #1a1a2e; margin-bottom: 12px; }
.od-items-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.od-items-table thead tr { background: #1a1a2e; color: #fff; }
.od-items-table th { padding: 10px 14px; font-weight: 600; }
.od-items-table tbody tr { border-bottom: 1px solid #f0f0f0; }
.od-items-table tbody tr:last-child { border-bottom: none; }
.od-items-table td { padding: 12px 14px; }
.od-item-name { font-weight: 600; color: #1a1a2e; }
.od-item-variant { font-size: 12px; color: #6c757d; margin-top: 2px; }
.text-center { text-align: center; }
.text-right  { text-align: right; }

/* ── Totals ── */
.od-totals-wrap { display: flex; justify-content: flex-end; }
.od-totals { width: 300px; }
.od-total-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 7px 0; font-size: 14px; border-bottom: 1px solid #f0f0f0;
}
.od-grand-total {
  font-size: 17px; font-weight: 800; color: #1a1a2e;
  border-top: 2px solid #1a1a2e; border-bottom: none; margin-top: 4px; padding-top: 10px;
}

/* ── Update Card ── */
.od-update-card {
  background: #fff; border: 1px solid #e9ecef; border-radius: 12px;
  overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.od-update-header {
  background: #1a1a2e; color: #fff;
  padding: 14px 24px; font-size: 15px; font-weight: 700;
  display: flex; align-items: center; gap: 8px;
}
.od-update-grid {
  display: grid; grid-template-columns: 1fr 1fr 1fr;
  gap: 16px; padding: 24px;
}
.od-update-field { display: flex; flex-direction: column; gap: 6px; }
.od-update-field--full { grid-column: 1 / -1; }
.od-update-field label { font-size: 13px; font-weight: 600; color: #495057; }
.od-update-footer {
  padding: 16px 24px; border-top: 1px solid #e9ecef;
  background: #f8f9fa;
}
.od-save-btn {
  background: #198754; color: #fff; border: none; cursor: pointer;
  padding: 10px 28px; border-radius: 8px; font-size: 14px; font-weight: 600;
  display: inline-flex; align-items: center; gap: 8px;
  transition: background .2s;
}
.od-save-btn:hover    { background: #157347; }
.od-save-btn:disabled { background: #999; cursor: not-allowed; }

/* ── Print ── */
@media print {
  .d-print-none { display: none !important; }
  .od-page { padding: 0; }
  .od-invoice { border: none; box-shadow: none; padding: 20px; }
  .od-update-card { display: none; }
}

/* ── Responsive ── */
@media (max-width: 768px) {
  .od-info-row { grid-template-columns: 1fr; }
  .od-update-grid { grid-template-columns: 1fr; }
  .od-invoice { padding: 20px 16px; }
  .od-totals { width: 100%; }
}
</style>
