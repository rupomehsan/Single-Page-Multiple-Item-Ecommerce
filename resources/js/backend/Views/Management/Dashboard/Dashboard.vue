<template>
  <div class="db-wrap">

    <!-- ── KPI Cards ─────────────────────────────────────────── -->
    <div class="db-kpi-grid">
      <div v-for="card in kpiCards" :key="card.key">
        <div class="db-kpi-card">
          <div class="db-kpi-icon" :style="{ background: card.gradient }">
            <i :class="card.icon"></i>
          </div>
          <div class="db-kpi-body">
            <div class="db-kpi-value">{{ card.value }}</div>
            <div class="db-kpi-label">{{ card.label }}</div>
          </div>
          <div class="db-kpi-badge" :class="card.badgeClass">
            {{ card.badge }}
          </div>
        </div>
      </div>
    </div>

    <!-- ── Charts Row ─────────────────────────────────────────── -->
    <div class="db-charts-grid">

      <!-- Monthly Trend -->
      <div class="db-charts-main">
        <div class="db-card h-100">
          <div class="db-card-head">
            <div>
              <h6 class="db-card-title">মাসিক অর্ডার প্রবণতা</h6>
              <p class="db-card-sub">গত ১২ মাসের অর্ডার ও রাজস্ব</p>
            </div>
            <div class="db-legend">
              <span class="db-legend-dot" style="background:#6366f1"></span><span>অর্ডার</span>
              <span class="db-legend-dot ml" style="background:#10b981"></span><span>রাজস্ব (÷100)</span>
            </div>
          </div>
          <div class="db-card-body">
            <canvas ref="lineChart" style="max-height:280px"></canvas>
          </div>
        </div>
      </div>

      <!-- Order Status -->
      <div class="db-charts-side">
        <div class="db-card h-100">
          <div class="db-card-head">
            <div>
              <h6 class="db-card-title">অর্ডার স্ট্যাটাস</h6>
              <p class="db-card-sub">বর্তমান বিতরণ</p>
            </div>
          </div>
          <div class="db-card-body d-flex flex-column align-items-center justify-content-center">
            <canvas ref="donutChart" style="max-width:200px;max-height:200px"></canvas>
            <div class="db-donut-legend mt-3">
              <div v-for="(item, i) in statusLegend" :key="i" class="db-donut-leg-item">
                <span class="db-legend-dot" :style="{ background: item.color }"></span>
                <span class="db-donut-leg-label">{{ item.label }}</span>
                <span class="db-donut-leg-val">{{ item.count }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Recent Orders ──────────────────────────────────────── -->

    <div class="db-card">
      <div class="db-card-head">
        <div>
          <h6 class="db-card-title">সাম্প্রতিক অর্ডার</h6>
          <p class="db-card-sub">সর্বশেষ ৫টি অর্ডার</p>
        </div>
        <router-link :to="{ name: 'AllOrder' }" class="db-view-all">সব দেখুন →</router-link>
      </div>
      <div class="db-table-wrap">
        <table class="db-table">
          <thead>
            <tr>
              <th>অর্ডার নং</th>
              <th>গ্রাহক</th>
              <th>মোট</th>
              <th>স্ট্যাটাস</th>
              <th>পেমেন্ট</th>
              <th>তারিখ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="6" class="db-empty">লোড হচ্ছে…</td>
            </tr>
            <tr v-else-if="!recentOrders.length">
              <td colspan="6" class="db-empty">কোনো অর্ডার নেই</td>
            </tr>
            <tr v-for="(o, i) in recentOrders" :key="i" v-else>
              <td><span class="db-order-num">#{{ o.order_number || '—' }}</span></td>
              <td>
                <div class="db-cust-name">{{ o.customer_name || '—' }}</div>
                <div class="db-cust-phone">{{ o.customer_phone || '' }}</div>
              </td>
              <td><strong class="db-amount">৳{{ fmt(o.total_amount) }}</strong></td>
              <td><span class="db-badge" :class="orderClass(o.order_status)">{{ orderLabel(o.order_status) }}</span></td>
              <td><span class="db-badge" :class="payClass(o.payment_status)">{{ o.payment_status || 'pending' }}</span></td>
              <td class="db-date">{{ fmtDate(o.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script>
import Chart from "chart.js/auto";

const STATUS_COLORS = {
  pending:    "#f59e0b",
  confirmed:  "#3b82f6",
  processing: "#8b5cf6",
  shipped:    "#06b6d4",
  delivered:  "#10b981",
  cancelled:  "#ef4444",
  returned:   "#6b7280",
};

const STATUS_LABELS = {
  pending: "Pending", confirmed: "Confirmed", processing: "Processing",
  shipped: "Shipped", delivered: "Delivered", cancelled: "Cancelled", returned: "Returned",
};

export default {
  name: "Dashboard",

  data() {
    return {
      loading: true,
      isDark: false,
      kpi: {},
      orderStatus: {},
      monthly: { labels: [], orders: [], revenue: [] },
      recentOrders: [],
      lineChart: null,
      donutChart: null,
    };
  },

  computed: {
    kpiCards() {
      const kpi = this.kpi;
      return [
        {
          key: "revenue",
          label: "মোট রাজস্ব",
          value: "৳" + this.fmt(kpi.total_revenue ?? 0),
          icon: "zmdi zmdi-money",
          gradient: "linear-gradient(135deg,#667eea,#764ba2)",
          badge: "সক্রিয়",
          badgeClass: "db-badge--success",
        },
        {
          key: "orders",
          label: "মোট অর্ডার",
          value: (kpi.total_orders ?? 0).toLocaleString(),
          icon: "zmdi zmdi-receipt",
          gradient: "linear-gradient(135deg,#f093fb,#f5576c)",
          badge: "আজ " + (kpi.today_orders ?? 0),
          badgeClass: "db-badge--info",
        },
        {
          key: "customers",
          label: "মোট গ্রাহক",
          value: (kpi.total_customers ?? 0).toLocaleString(),
          icon: "zmdi zmdi-accounts",
          gradient: "linear-gradient(135deg,#30cfd0,#330867)",
          badge: "নিবন্ধিত",
          badgeClass: "db-badge--purple",
        },
        {
          key: "products",
          label: "মোট পণ্য",
          value: (kpi.total_products ?? 0).toLocaleString(),
          icon: "zmdi zmdi-shopping-cart",
          gradient: "linear-gradient(135deg,#fa709a,#fee140)",
          badge: "সক্রিয়",
          badgeClass: "db-badge--warning",
        },
        {
          key: "today_revenue",
          label: "আজকের রাজস্ব",
          value: "৳" + this.fmt(kpi.today_revenue ?? 0),
          icon: "zmdi zmdi-trending-up",
          gradient: "linear-gradient(135deg,#11998e,#38ef7d)",
          badge: "আজ",
          badgeClass: "db-badge--success",
        },
        {
          key: "pending",
          label: "পেন্ডিং অর্ডার",
          value: (kpi.pending_orders ?? 0).toLocaleString(),
          icon: "zmdi zmdi-time",
          gradient: "linear-gradient(135deg,#f7971e,#ffd200)",
          badge: "অপেক্ষমান",
          badgeClass: "db-badge--warning",
        },
        {
          key: "delivered",
          label: "ডেলিভার্ড",
          value: (kpi.delivered_orders ?? 0).toLocaleString(),
          icon: "zmdi zmdi-check-circle",
          gradient: "linear-gradient(135deg,#56ab2f,#a8e063)",
          badge: "সম্পন্ন",
          badgeClass: "db-badge--success",
        },
        {
          key: "reviews",
          label: "মোট রিভিউ",
          value: (kpi.total_reviews ?? 0).toLocaleString(),
          icon: "zmdi zmdi-star",
          gradient: "linear-gradient(135deg,#7b4397,#dc2430)",
          badge: "গ্রাহক",
          badgeClass: "db-badge--purple",
        },
      ];
    },

    statusLegend() {
      return Object.entries(this.orderStatus).map(([status, count]) => ({
        label: STATUS_LABELS[status] ?? status,
        count,
        color: STATUS_COLORS[status] ?? "#94a3b8",
      }));
    },
  },

  async mounted() {
    this.syncTheme();
    window.addEventListener("themechange", this.onThemeChange);
    await this.loadData();
    this.$nextTick(() => this.buildCharts());
  },

  beforeUnmount() {
    window.removeEventListener("themechange", this.onThemeChange);
    this.lineChart?.destroy();
    this.donutChart?.destroy();
  },

  methods: {
    syncTheme() {
      this.isDark = document.body.classList.contains("dark-theme");
    },

    onThemeChange(e) {
      this.isDark = e.detail?.theme === "dark-theme";
      this.$nextTick(() => this.buildCharts());
    },

    async loadData() {
      this.loading = true;
      try {
        const res = await axios.get("get-all-dashboard-data");
        if (res.status === 200) {
          const d = res.data.data;
          this.kpi          = d.kpi          ?? {};
          this.orderStatus  = d.order_status  ?? {};
          this.monthly      = d.monthly       ?? { labels: [], orders: [], revenue: [] };
          this.recentOrders = d.recent_orders ?? [];
        }
      } catch (_) {}
      this.loading = false;
    },

    buildCharts() {
      this.buildLineChart();
      this.buildDonutChart();
    },

    buildLineChart() {
      const ctx = this.$refs.lineChart;
      if (!ctx) return;
      this.lineChart?.destroy();

      const dark = this.isDark;
      const tick  = dark ? "#94a3b8" : "#6b7280";
      const grid  = dark ? "rgba(148,163,184,.12)" : "rgba(107,114,128,.1)";

      // Scale revenue ÷100 so both lines share one y-axis comfortably
      const revenueScaled = (this.monthly.revenue ?? []).map(v => +(v / 100).toFixed(1));

      this.lineChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: this.monthly.labels,
          datasets: [
            {
              label: "অর্ডার",
              data: this.monthly.orders,
              borderColor: "#6366f1",
              backgroundColor: "rgba(99,102,241,.12)",
              borderWidth: 2.5,
              fill: true,
              tension: 0.4,
              pointRadius: 4,
              pointBackgroundColor: "#6366f1",
              pointBorderColor: dark ? "#1e293b" : "#fff",
              pointBorderWidth: 2,
            },
            {
              label: "রাজস্ব (÷100)",
              data: revenueScaled,
              borderColor: "#10b981",
              backgroundColor: "rgba(16,185,129,.08)",
              borderWidth: 2.5,
              fill: true,
              tension: 0.4,
              pointRadius: 4,
              pointBackgroundColor: "#10b981",
              pointBorderColor: dark ? "#1e293b" : "#fff",
              pointBorderWidth: 2,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: { legend: { display: false } },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { color: tick, maxTicksLimit: 6 },
              grid: { color: grid },
              border: { dash: [4, 4] },
            },
            x: {
              ticks: { color: tick },
              grid: { display: false },
            },
          },
        },
      });
    },

    buildDonutChart() {
      const ctx = this.$refs.donutChart;
      if (!ctx) return;
      this.donutChart?.destroy();

      const statuses = Object.keys(this.orderStatus);
      const counts   = Object.values(this.orderStatus);
      const colors   = statuses.map(s => STATUS_COLORS[s] ?? "#94a3b8");

      this.donutChart = new Chart(ctx, {
        type: "doughnut",
        data: {
          labels: statuses.map(s => STATUS_LABELS[s] ?? s),
          datasets: [{
            data: counts,
            backgroundColor: colors,
            borderColor: this.isDark ? "#1e293b" : "#fff",
            borderWidth: 3,
            hoverOffset: 6,
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: true,
          cutout: "70%",
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: (ctx) => ` ${ctx.label}: ${ctx.parsed} অর্ডার`,
              },
            },
          },
        },
      });
    },

    fmt(v) {
      return Number(v || 0).toLocaleString("en-BD");
    },

    fmtDate(d) {
      if (!d) return "—";
      return new Date(d).toLocaleDateString("bn-BD", {
        day: "numeric", month: "short", year: "numeric",
      });
    },

    orderClass(s) {
      return { pending:"db-badge--warning", confirmed:"db-badge--info", processing:"db-badge--purple",
               shipped:"db-badge--cyan", delivered:"db-badge--success", cancelled:"db-badge--danger",
               returned:"db-badge--secondary" }[s] ?? "db-badge--secondary";
    },

    orderLabel(s) {
      return STATUS_LABELS[s] ?? s ?? "—";
    },

    payClass(s) {
      return { pending:"db-badge--warning", completed:"db-badge--success",
               failed:"db-badge--danger", refunded:"db-badge--info" }[s] ?? "db-badge--secondary";
    },
  },
};
</script>

<style scoped>
/* ── Wrapper ── */
.db-wrap {
  padding: 4px 0 24px;
}

/* ── KPI grid (CSS Grid — independent of Bootstrap row/gutter overrides) ── */
.db-kpi-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px;
  margin-bottom: 16px;
}

@media (min-width: 768px) {
  .db-kpi-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* ── Charts grid ── */
.db-charts-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 14px;
  margin-bottom: 16px;
}

@media (min-width: 992px) {
  .db-charts-grid {
    grid-template-columns: 2fr 1fr;
  }
}

/* ══════════════════════════════════════
   KPI CARDS
══════════════════════════════════════ */
.db-kpi-card {
  background: var(--db-card, #ffffff);
  border: 1px solid var(--db-border, #e5e7eb);
  border-radius: 14px;
  padding: 18px 16px;
  display: flex;
  align-items: center;
  gap: 14px;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
  transition: transform .2s ease, box-shadow .2s ease;
  position: relative;
  overflow: hidden;
}

.db-kpi-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0,0,0,.1);
}

.db-kpi-icon {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: #fff;
  flex-shrink: 0;
}

.db-kpi-body {
  flex: 1;
  min-width: 0;
}

.db-kpi-value {
  font-size: 20px;
  font-weight: 800;
  color: var(--db-text, #111827);
  line-height: 1.2;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.db-kpi-label {
  font-size: 12px;
  color: var(--db-muted, #6b7280);
  margin-top: 3px;
}

.db-kpi-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  font-size: 10px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 20px;
}

/* ══════════════════════════════════════
   DASHBOARD CARD
══════════════════════════════════════ */
.db-card {
  background: var(--db-card, #ffffff);
  border: 1px solid var(--db-border, #e5e7eb);
  border-radius: 14px;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.db-card-head {
  padding: 16px 20px;
  border-bottom: 1px solid var(--db-border, #f3f4f6);
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
}

.db-card-title {
  font-size: 15px;
  font-weight: 700;
  color: var(--db-text, #111827);
  margin: 0;
}

.db-card-sub {
  font-size: 12px;
  color: var(--db-muted, #6b7280);
  margin: 2px 0 0 0;
}

.db-card-body {
  padding: 20px;
  flex: 1;
}

/* ── Legend ── */
.db-legend {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--db-muted, #6b7280);
}

.db-legend-dot {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  display: inline-block;
  flex-shrink: 0;
}

.ml { margin-left: 8px; }

/* ── Donut legend ── */
.db-donut-legend {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.db-donut-leg-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
}

.db-donut-leg-label {
  flex: 1;
  color: var(--db-muted, #6b7280);
}

.db-donut-leg-val {
  font-weight: 700;
  color: var(--db-text, #111827);
}

/* ── View all link ── */
.db-view-all {
  font-size: 13px;
  font-weight: 600;
  color: #6366f1;
  text-decoration: none;
  white-space: nowrap;
  align-self: center;
}

.db-view-all:hover { color: #4f46e5; text-decoration: underline; }

/* ══════════════════════════════════════
   TABLE
══════════════════════════════════════ */
.db-table-wrap {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.db-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
  min-width: 560px;
}

.db-table thead tr {
  background: var(--db-thead, #f9fafb);
  border-bottom: 1px solid var(--db-border, #e5e7eb);
}

.db-table th {
  padding: 12px 16px;
  text-align: left;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--db-muted, #6b7280);
  white-space: nowrap;
}

.db-table td {
  padding: 13px 16px;
  border-bottom: 1px solid var(--db-border, #f3f4f6);
  color: var(--db-text-sec, #374151);
  vertical-align: middle;
}

.db-table tbody tr:last-child td { border-bottom: none; }

.db-table tbody tr:hover {
  background: var(--db-row-hover, #f9fafb);
}

.db-order-num {
  font-weight: 700;
  color: #6366f1;
  font-size: 12px;
}

.db-cust-name {
  font-weight: 600;
  color: var(--db-text, #111827);
}

.db-cust-phone {
  font-size: 11px;
  color: var(--db-muted, #6b7280);
}

.db-amount { color: var(--db-text, #111827); }

.db-date {
  font-size: 12px;
  color: var(--db-muted, #6b7280);
  white-space: nowrap;
}

.db-empty {
  text-align: center;
  padding: 32px;
  color: var(--db-muted, #6b7280);
}

/* ══════════════════════════════════════
   BADGES
══════════════════════════════════════ */
.db-badge {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.db-badge--success  { background:#d1fae5; color:#065f46; }
.db-badge--warning  { background:#fef3c7; color:#92400e; }
.db-badge--danger   { background:#fee2e2; color:#991b1b; }
.db-badge--info     { background:#dbeafe; color:#1e40af; }
.db-badge--purple   { background:#ede9fe; color:#5b21b6; }
.db-badge--cyan     { background:#cffafe; color:#155e75; }
.db-badge--secondary{ background:#f3f4f6; color:#4b5563; }

/* ══════════════════════════════════════
   DARK THEME  (body.dark-theme)
   All selectors use :global so they
   escape scoped CSS and read the body class
══════════════════════════════════════ */

/* CSS variables scoped to dark body */
:global(body.dark-theme) .db-kpi-card,
:global(body.dark-theme) .db-card {
  --db-card:     rgb(30 41 59);
  --db-border:   rgb(51 65 85);
  --db-text:     #e2e8f0;
  --db-text-sec: #cbd5e1;
  --db-muted:    #94a3b8;
  --db-thead:    rgb(15 23 42);
  --db-row-hover:rgb(51 65 85 / .4);
}

:global(body.dark-theme) .db-kpi-card {
  background: rgb(30 41 59);
  border-color: rgb(51 65 85);
  box-shadow: 0 1px 4px rgba(0,0,0,.3);
}

:global(body.dark-theme) .db-card {
  background: rgb(30 41 59);
  border-color: rgb(51 65 85);
}

:global(body.dark-theme) .db-card-head {
  border-bottom-color: rgb(51 65 85);
}

:global(body.dark-theme) .db-card-title { color: #e2e8f0; }
:global(body.dark-theme) .db-card-sub   { color: #94a3b8; }

:global(body.dark-theme) .db-kpi-value  { color: #f1f5f9; }
:global(body.dark-theme) .db-kpi-label  { color: #94a3b8; }

:global(body.dark-theme) .db-table thead tr {
  background: rgb(15 23 42);
  border-bottom-color: rgb(51 65 85);
}

:global(body.dark-theme) .db-table th   { color: #64748b; }

:global(body.dark-theme) .db-table td {
  border-bottom-color: rgb(51 65 85 / .5);
  color: #cbd5e1;
}

:global(body.dark-theme) .db-table tbody tr:hover {
  background: rgb(51 65 85 / .35);
}

:global(body.dark-theme) .db-cust-name  { color: #e2e8f0; }
:global(body.dark-theme) .db-cust-phone { color: #64748b; }
:global(body.dark-theme) .db-amount     { color: #f1f5f9; }
:global(body.dark-theme) .db-date       { color: #64748b; }
:global(body.dark-theme) .db-empty      { color: #64748b; }

:global(body.dark-theme) .db-donut-leg-label { color: #94a3b8; }
:global(body.dark-theme) .db-donut-leg-val   { color: #e2e8f0; }

/* dark badges — keep same bg, slightly adjust */
:global(body.dark-theme) .db-badge--success  { background:rgba(16,185,129,.2); color:#6ee7b7; }
:global(body.dark-theme) .db-badge--warning  { background:rgba(245,158,11,.2); color:#fcd34d; }
:global(body.dark-theme) .db-badge--danger   { background:rgba(239,68,68,.2);  color:#fca5a5; }
:global(body.dark-theme) .db-badge--info     { background:rgba(59,130,246,.2); color:#93c5fd; }
:global(body.dark-theme) .db-badge--purple   { background:rgba(139,92,246,.2); color:#c4b5fd; }
:global(body.dark-theme) .db-badge--cyan     { background:rgba(6,182,212,.2);  color:#67e8f9; }
:global(body.dark-theme) .db-badge--secondary{ background:rgba(107,114,128,.2);color:#9ca3af; }

/* ══════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════ */

/* Tablet: tighten spacing */
@media (max-width: 768px) {
  .db-card-head { padding: 12px 14px; }
  .db-card-body { padding: 14px; }
  .db-kpi-card  { padding: 14px 12px; gap: 10px; }
}

/* Mobile: stack KPI card vertically, badge inline */
@media (max-width: 575px) {
  .db-kpi-card {
    flex-direction: column;
    align-items: flex-start;
    padding: 12px;
    gap: 8px;
  }

  .db-kpi-icon {
    width: 38px;
    height: 38px;
    font-size: 17px;
    border-radius: 10px;
  }

  .db-kpi-value {
    font-size: 15px;
    white-space: normal;
    word-break: break-word;
  }

  .db-kpi-label { font-size: 11px; }

  /* move badge from absolute to inline under label */
  .db-kpi-badge {
    position: static;
    align-self: flex-start;
    font-size: 10px;
    padding: 2px 7px;
  }

  .db-card-head {
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
  }

  .db-legend {
    flex-wrap: wrap;
    gap: 4px;
  }

  .db-table { min-width: 480px; }

  .db-donut-legend { gap: 4px; }
}
</style>
