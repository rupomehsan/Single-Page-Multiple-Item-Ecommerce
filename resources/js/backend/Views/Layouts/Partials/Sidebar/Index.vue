<template>
  <!--Start sidebar-wrapper-->
  <div id="sidebar-wrapper">
    <div class="brand-logo">
      <router-link :to="{ name: `adminDashboard` }" class="d-flex align-items-center">
        <img :src="`${get_setting_value('image') ?? 'avatar.png'} `" class="logo-icon" alt="logo icon" />
        <h5 class="logo-text">Control Panel</h5>
      </router-link>
      <div class="close-btn">
        <i class="zmdi zmdi-close" @click="toggle_menu"></i>
      </div>
    </div>

    <!-- Polished user card -->
    <div class="sidebar-user-card">
      <div class="sidebar-user-avatar">
        <img :src="`${auth_info.image ?? 'avatar.png'}`" alt="user avatar" />
        <span class="sidebar-user-status" aria-hidden="true"></span>
      </div>
      <div class="sidebar-user-info">
        <p class="sidebar-user-name" :title="user_display_name">{{ user_display_name }}</p>
        <span class="sidebar-user-role" :title="user_role_name">
          <i class="zmdi zmdi-shield-security"></i>
          {{ user_role_name }}
        </span>
      </div>
    </div>

    <!-- Sidebar menu search -->
    <div class="sidebar-search">
      <i class="zmdi zmdi-search sidebar-search-icon"></i>
      <input
        v-model="search_query"
        type="text"
        class="sidebar-search-input"
        placeholder="Search menu..."
        autocomplete="off"
        spellcheck="false"
      />
      <button
        v-if="search_query"
        type="button"
        class="sidebar-search-clear"
        @click="search_query = ''"
        aria-label="Clear search"
      >
        <i class="zmdi zmdi-close"></i>
      </button>
    </div>

    <hr class="sidebar-divider" />

    <ul class="metismenu" id="menu">
      <side-bar-single-menu
        v-if="has_permission('dashboard-view')"
        :icon="`zmdi zmdi-view-dashboard`"
        :menu_title="`Dashboard`"
        :route_name="`adminDashboard`"
        :search_query="search_query"
        :class="'border border-primary rounded'"
      />

      <side-bar-drop-down-menus
        v-if="has_permission('user-view') || has_permission('role-view')"
        :icon="`fa fa-plus`"
        :menu_title="`User Management`"
        :search_query="search_query"
        :menus="[
          {
            route_name: `AllUser`,
            title: `User`,
            icon: `zmdi zmdi-dot-circle-alt`,
            permission: 'user-view',
          },
          {
            route_name: `AllRole`,
            title: `User Role`,
            icon: `zmdi zmdi-dot-circle-alt`,
            permission: 'role-view',
          },
        ]"
      />

      

        <!-- CMS Management -->
      <side-bar-drop-down-menus
        :icon="`zmdi zmdi-layers`"
        :menu_title="`CMS Management`"
        :search_query="search_query"
        :menus="[
         
          {
            route_name: `AllHeroSection`,
            title: `Hero Section`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
          {
            route_name: `AllBenefitCard`,
            title: `Benefit Card`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
          {
            route_name: `AllHowItWorksStep`,
            title: `How It Works`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
          {
            route_name: `AllTestimonial`,
            title: `Testimonial`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
          {
            route_name: `AllBanner`,
            title: `Banner/Slider`,
            icon: `zmdi zmdi-dot-circle-alt`,
          }
         
        ]"
      />

      <!-- Product Management -->
      <side-bar-drop-down-menus
        :icon="`zmdi zmdi-shopping-cart`"
        :menu_title="`Product Management`"
        :search_query="search_query"
        :menus="[
          {
            route_name: `AllProductCategory`,
            title: `Product Category`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
          {
            route_name: `AllProduct`,
            title: `Product`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
        ]"
      />

      <!-- Order Management -->
      <side-bar-drop-down-menus
        :icon="`zmdi zmdi-receipt`"
        :menu_title="`Order Management`"
        :search_query="search_query"
        :menus="[
          {
            route_name: `AllOrder`,
            title: `Orders`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
         
        ]"
      />

      <!-- Customer Management -->
      <side-bar-single-menu
        :icon="`zmdi zmdi-account-box`"
        :menu_title="`Customer Management`"
        :route_name="`AllCustomer`"
        :search_query="search_query"
      />

    

    

      <!-- FAQ Management -->
      <side-bar-single-menu
        :icon="`zmdi zmdi-help`"
        :menu_title="`FAQ Management`"
        :route_name="`AllFAQ`"
        :search_query="search_query"
      />

      <!-- Promo Code Management -->
      <side-bar-single-menu
        :icon="`zmdi zmdi-local-offer`"
        :menu_title="`Promo Code Management`"
        :route_name="`AllPromoCode`"
        :search_query="search_query"
      />

      <!-- Delivery Area Management -->
      <side-bar-single-menu
        :icon="`zmdi zmdi-local-shipping`"
        :menu_title="`Delivery Area Management`"
        :route_name="`AllDeliveryArea`"
        :search_query="search_query"
      />

    
    </ul>

    <!-- No results state -->
    <div v-if="search_query && no_menu_results" class="sidebar-empty-search">
      <i class="zmdi zmdi-search"></i>
      <p>No matching menus</p>
      <span>Try a different search</span>
    </div>
  </div>
</template>

<script>
//auth_store
import { auth_store } from "../../../../GlobalStore/auth_store";
import { site_settings_store } from "../../../../GlobalStore/site_settings_store";
import { mapState, mapActions } from "pinia";
//components
import SideBarDropDownMenus from "./SideBarDropDownMenus.vue";
import SideBarSingleMenu from "./SideBarSingleMenu.vue";
export default {
  components: { SideBarDropDownMenus, SideBarSingleMenu },
  data: () => ({
    search_query: "",
  }),
  created() {
    const authS = auth_store();
    if (!authS.is_auth) {
      authS.check_is_auth();
    }
  },
  methods: {
    ...mapActions(site_settings_store, {
      get_setting_value: "get_setting_value",
    }),

    logout_submit: function () {
      let confirm = window.confirm("logout");
      if (confirm) {
        this.log_out();
      }
    },
    toggle_menu: function () {
      const wrapper = document.getElementById("wrapper");
      wrapper.classList.toggle("toggled");
      if (window.innerWidth <= 991) {
        const isOpen = wrapper.classList.contains("toggled");
        document.body.classList.toggle("sidebar-open", isOpen);
      }
    },
    hide_menu: function () {
      document.getElementById("wrapper").classList.add("toggled");
    },
    onDashboardClick() {
      window.dispatchEvent(
        new CustomEvent("collapse-all-menus", {
          detail: { except: null },
        }),
      );
    },
  },
  watch: {
    $route(to, from) {
      if (window.innerWidth <= 991) {
        document.getElementById("wrapper")?.classList.remove("toggled");
        document.body.classList.remove("sidebar-open");
      }
    },
  },
  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
      role: "role",
      has_permission: "has_permission",
    }),
    user_display_name() {
      const name = this.auth_info?.name;
      return name ? `Mr. ${name}` : "Guest";
    },
    user_role_name() {
      return (
        this.role?.name ||
        this.role?.title ||
        this.auth_info?.role?.name ||
        this.auth_info?.role?.title ||
        "Member"
      );
    },
    no_menu_results() {
      if (!this.search_query) return false;
      const q = this.search_query.toLowerCase().trim();
      if (!q) return false;
      const allTitles = [
        "Dashboard",
        "User Management",
        "User",
        "User Role",
        "Product Management",
        "Product Category",
        "Product",
        "Order Management",
        "Orders",
        "Order Items",
        "Customer Management",
        "Payment Management",
        "Inventory Management",
        "Review Management",
        "FAQ Management",
        "Promo Code Management",
        "Delivery Area Management",
        "CMS Management",
        "Page Section",
        "Hero Section",
        "Benefit Card",
        "How It Works",
        "Testimonial",
        "Banner/Slider",
        "Site Setting",
        "Media File",
      ];
      return !allTitles.some((t) => t.toLowerCase().includes(q));
    },
  },
};
</script>

<style>
/* Dashboard active state styling */
#menu > li.active > a,
#menu > li > a.active {
  background-color: #007bff !important;
  color: white !important;
  border-radius: 4px;
  margin: 2px;
}
</style>

<style scoped>
/* =====================================
   SIDEBAR USER CARD
   ===================================== */

.sidebar-user-card {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin: 0.85rem 0.85rem 0;
  padding: 0.8rem 0.9rem;
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.12) 0%,
    rgba(99, 102, 241, 0.08) 100%
  );
  border: 1px solid rgba(99, 102, 241, 0.18);
  border-radius: 12px;
  transition: background 0.25s ease, border-color 0.25s ease;
}

.sidebar-user-card:hover {
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.18) 0%,
    rgba(99, 102, 241, 0.12) 100%
  );
  border-color: rgba(99, 102, 241, 0.32);
}

.sidebar-user-avatar {
  position: relative;
  width: 44px;
  height: 44px;
  flex-shrink: 0;
}

.sidebar-user-avatar img {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(255, 255, 255, 0.92);
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.25);
  display: block;
}

.sidebar-user-status {
  position: absolute;
  bottom: 1px;
  right: 1px;
  width: 11px;
  height: 11px;
  border-radius: 50%;
  background-color: #22c55e;
  border: 2px solid #1e293b;
  box-shadow: 0 0 0 1px rgba(34, 197, 94, 0.4);
}

.sidebar-user-info {
  display: flex;
  flex-direction: column;
  min-width: 0;
  flex: 1;
}

.sidebar-user-name {
  font-size: 0.92rem;
  font-weight: 600;
  color: #f8fafc;
  margin: 0;
  line-height: 1.2;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar-user-role {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  font-size: 0.72rem;
  font-weight: 500;
  color: #c7d2fe;
  margin-top: 3px;
  letter-spacing: 0.02em;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  text-transform: capitalize;
}

.sidebar-user-role i {
  font-size: 0.85rem;
  color: #a5b4fc;
}

/* =====================================
   SIDEBAR SEARCH
   ===================================== */

.sidebar-search {
  position: relative;
  margin: 0.75rem 0.85rem 0.25rem;
}

.sidebar-search-input {
  width: 100%;
  height: 38px;
  padding: 0 36px 0 36px;
  font-size: 0.85rem;
  color: #e2e8f0;
  background-color: rgba(15, 23, 42, 0.55);
  border: 1px solid rgba(148, 163, 184, 0.18);
  border-radius: 10px;
  outline: none;
  transition: border-color 0.2s ease, background-color 0.2s ease,
    box-shadow 0.2s ease;
}

.sidebar-search-input::placeholder {
  color: rgba(148, 163, 184, 0.7);
}

.sidebar-search-input:focus {
  border-color: rgba(99, 102, 241, 0.55);
  background-color: rgba(15, 23, 42, 0.75);
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

.sidebar-search-icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  font-size: 1rem;
  color: rgba(148, 163, 184, 0.85);
  pointer-events: none;
}

.sidebar-search-clear {
  position: absolute;
  top: 50%;
  right: 8px;
  transform: translateY(-50%);
  width: 22px;
  height: 22px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(148, 163, 184, 0.15);
  border: none;
  border-radius: 50%;
  color: #cbd5e1;
  cursor: pointer;
  transition: background-color 0.18s ease, color 0.18s ease;
}

.sidebar-search-clear:hover {
  background: rgba(248, 113, 113, 0.25);
  color: #fca5a5;
}

.sidebar-search-clear i {
  font-size: 0.85rem;
  line-height: 1;
}

/* =====================================
   DIVIDER + EMPTY STATE
   ===================================== */

.sidebar-divider {
  margin: 0.6rem 0.85rem 0.4rem;
  border: 0;
  border-top: 1px solid rgba(148, 163, 184, 0.18);
}

.sidebar-empty-search {
  margin: 1rem 0.85rem;
  padding: 1.4rem 1rem;
  text-align: center;
  background-color: rgba(15, 23, 42, 0.4);
  border: 1px dashed rgba(148, 163, 184, 0.2);
  border-radius: 10px;
  color: rgba(203, 213, 225, 0.85);
}

.sidebar-empty-search i {
  font-size: 1.8rem;
  color: rgba(148, 163, 184, 0.6);
  margin-bottom: 0.4rem;
  display: block;
}

.sidebar-empty-search p {
  font-size: 0.88rem;
  font-weight: 600;
  margin: 0;
  color: #e2e8f0;
}

.sidebar-empty-search span {
  font-size: 0.75rem;
  color: rgba(148, 163, 184, 0.8);
}

/* Light theme overrides */
:global(body.light-theme) .sidebar-user-name {
  color: #0f172a;
}
:global(body.light-theme) .sidebar-user-role {
  color: #4338ca;
}
:global(body.light-theme) .sidebar-user-role i {
  color: #6366f1;
}
:global(body.light-theme) .sidebar-user-status {
  border-color: #ffffff;
}
:global(body.light-theme) .sidebar-search-input {
  color: #0f172a;
  background-color: #ffffff;
  border-color: #e2e8f0;
}
:global(body.light-theme) .sidebar-search-input::placeholder {
  color: #94a3b8;
}
:global(body.light-theme) .sidebar-search-icon {
  color: #64748b;
}
:global(body.light-theme) .sidebar-search-clear {
  background: rgba(15, 23, 42, 0.08);
  color: #475569;
}
:global(body.light-theme) .sidebar-divider {
  border-top-color: #e2e8f0;
}
:global(body.light-theme) .sidebar-empty-search {
  background-color: #f8fafc;
  border-color: #e2e8f0;
  color: #475569;
}
:global(body.light-theme) .sidebar-empty-search p {
  color: #0f172a;
}
:global(body.light-theme) .sidebar-empty-search span {
  color: #64748b;
}

@media (max-width: 991px) {
  .sidebar-user-card {
    margin: 0.5rem 0.85rem 0;
  }
}
</style>
