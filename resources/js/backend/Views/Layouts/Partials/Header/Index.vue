<template>
  <!--Start topbar header-->
  <header class="topbar-nav">
    <nav class="navbar navbar-expand fixed-top">
      <div class="toggle-menu">
        <i @click.prevent="toggle_menu" class="zmdi zmdi-menu"></i>
      </div>

      <a
        href="/"
        target="_blank"
        title="Visit Website"
        class="d-flex align-items-center justify-content-center mx-2"
        style="background: var(--bg-hover); width: 30px; height: 30px"
      >
        <i class="zmdi zmdi-globe mt-1"></i>
      </a>

      <div class="search-bar flex-grow-1"></div>

      <!-- Theme Toggle Button -->
      <div class="theme-toggle-header me-3">
        <button
          class="btn btn-sm theme-switch-btn"
          :class="{
            'light-mode': isLightTheme,
            'dark-mode': isDarkTheme,
          }"
          @click="toggleTheme"
          :title="isLightTheme ? 'Switch to Dark Mode' : 'Switch to Light Mode'"
        >
          <i v-if="isDarkTheme" class="zmdi zmdi-sun"></i>
          <i v-else class="zmdi zmdi-brightness-2"></i>
        </button>
      </div>

      <!-- Old Theme Toggle (commented out) -->
      <!-- <theme-toggle></theme-toggle> -->

      <ul class="navbar-nav align-items-center right-nav-link ml-auto">
        <!-- <li
                    class="nav-item dropdown dropdown-lg"
                    @click="toggle_notification('show_notification')"
                >
                    <a
                        class="btn nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                        data-toggle="dropdown"
                        href="javascript:void();"
                        aria-expanded="false"
                    >
                        <i class="zmdi zmdi-comment-outline align-middle"></i
                        ><span class="bg-danger text-white badge-up"
                            >12</span
                        ></a
                    >
                    <div
                        class="dropdown-menu dropdown-menu-right"
                        :class="{ show: show_notification }"
                    >
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center"
                            >
                                New Messages
                                <a
                                    href="javascript:void();"
                                    class="extra-small-font"
                                    >Clear All</a
                                >
                            </li>
                            <li class="list-group-item">
                                <a href="javaScript:void();">
                                    <div class="media">
                                        <div class="avatar">
                                            <img
                                                class="align-self-start mr-3"
                                                src="avatar.png"
                                                alt="user avatar"
                                            />
                                        </div>
                                        <div class="media-body">
                                            <h6 class="mt-0 msg-title">
                                                Jhon Deo
                                            </h6>
                                            <p class="msg-info">
                                                Lorem ipsum dolor sit amet...
                                            </p>
                                            <small>Today, 4:10 PM</small>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <li class="list-group-item text-center">
                                <a href="javaScript:void();"
                                    >See All Messages</a
                                >
                            </li>
                        </ul>
                    </div>
                </li> -->

        <li
          class="nav-item dropdown notification-dropdown"
          @click="toggle_notification('show_message')"
        >
          <a
            role="button"
            class="btn nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
          >
            <i class="zmdi zmdi-notifications-active align-middle"></i>
            <span
              v-if="unseen_vouchers.length > 0"
              class="badge-up"
            >{{ unseen_vouchers.length > 99 ? '99+' : unseen_vouchers.length }}</span>
          </a>
          <div
            class="dropdown-menu dropdown-menu-right notification-menu"
            :class="{ show: show_message }"
          >
            <div class="notification-header">
              <div class="notification-header-title">
                <i class="zmdi zmdi-notifications"></i>
                <span>Notifications</span>
                <span class="notification-count-badge" v-if="unseen_vouchers.length">
                  {{ unseen_vouchers.length }}
                </span>
              </div>
              <a
                v-if="unseen_vouchers.length"
                href="javascript:void(0)"
                class="notification-clear"
                @click.prevent="mark_all_as_seen"
              >Mark all read</a>
            </div>

            <div class="notification-body">
              <template v-if="unseen_vouchers.length">
                <a
                  v-for="voucher in unseen_vouchers"
                  :key="voucher.id || voucher.title"
                  class="notification-item"
                  @click.prevent="mark_as_seen(voucher)"
                >
                  <span class="notification-item-icon">
                    <i class="zmdi zmdi-receipt"></i>
                  </span>
                  <span class="notification-item-content">
                    <span class="notification-item-title">
                      {{ voucher.title.substring(0, 40) }}
                    </span>
                    <span class="notification-item-meta">
                      Amount: {{ voucher.amount }}
                    </span>
                  </span>
                  <span class="notification-item-dot" aria-hidden="true"></span>
                </a>
              </template>
              <div v-else class="notification-empty">
                <div class="notification-empty-icon">
                  <i class="zmdi zmdi-notifications-none"></i>
                </div>
                <p class="notification-empty-title">You're all caught up</p>
                <p class="notification-empty-subtitle">No new notifications right now.</p>
              </div>
            </div>

            <div class="notification-footer" v-if="unseen_vouchers.length">
              <a href="javascript:void(0)" class="notification-view-all">
                View all notifications
                <i class="zmdi zmdi-long-arrow-right"></i>
              </a>
            </div>
          </div>
        </li>

        <li class="nav-item dropdown profile-dropdown" @click="toggle_notification('show_profile')">
          <a
            class="btn nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
          >
            <span class="user-profile">
              <img
                :src="`${auth_info.image ?? 'avatar.png'}`"
                class="img-circle"
                alt="user avatar"
              />
            </span>
          </a>
          <ul class="dropdown-menu dropdown-menu-right profile-menu" :class="{ show: show_profile }">
            <li class="profile-header">
              <div class="profile-header-avatar">
                <img
                  :src="`${auth_info.image ?? 'avatar.png'}`"
                  alt="user avatar"
                />
                <span class="profile-status-dot" aria-hidden="true"></span>
              </div>
              <div class="profile-header-info">
                <h6 class="profile-name">{{ auth_info.name }}</h6>
                <p class="profile-email">{{ auth_info.email }}</p>
              </div>
            </li>

            <li class="profile-divider"></li>

            <li>
              <router-link class="profile-link" :to="{ name: 'AdminProfileSettings' }">
                <span class="profile-link-icon"><i class="zmdi zmdi-account"></i></span>
                <span class="profile-link-label">Profile</span>
                <i class="zmdi zmdi-chevron-right profile-link-arrow"></i>
              </router-link>
            </li>
            <li>
              <router-link class="profile-link" :to="{ name: 'AdminSiteSettings' }">
                <span class="profile-link-icon"><i class="zmdi zmdi-settings"></i></span>
                <span class="profile-link-label">Settings</span>
                <i class="zmdi zmdi-chevron-right profile-link-arrow"></i>
              </router-link>
            </li>

            <li class="profile-divider"></li>

            <li>
              <button type="button" class="profile-link profile-link-danger" @click="logout()">
                <span class="profile-link-icon"><i class="zmdi zmdi-power"></i></span>
                <span class="profile-link-label">Logout</span>
              </button>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </header>
  <!--End topbar header-->
</template>

<script>
//auth_store
import { auth_store } from "../../../../GlobalStore/auth_store";
import { mapState } from "pinia";

export default {
  data: () => ({
    show_notification: 0,
    show_message: 0,
    show_profile: 0,
    unseen_vouchers: [],
    currentTheme: "light-theme",
  }),

  computed: {
    ...mapState(auth_store, {
      auth_info: "auth_info",
    }),
    isLightTheme() {
      return this.currentTheme === "light-theme";
    },
    isDarkTheme() {
      return this.currentTheme === "dark-theme";
    },
  },

  created: async function () {},

  mounted() {
    // Initialize current theme
    this.initializeTheme();

    // Listen to theme changes from other components
    this.themeChangeHandler = this.onThemeChange.bind(this);
    window.addEventListener("themechange", this.themeChangeHandler);

    // Add a small delay to check body class after page fully loads
    setTimeout(() => {
      this.syncThemeFromDOM();
    }, 100);

    // Close any open header dropdown when the user clicks outside of it.
    // Bound on capture so it runs even if children stopPropagation later.
    this.outsideClickHandler = this.handleOutsideClick.bind(this);
    document.addEventListener("click", this.outsideClickHandler);

    // Close dropdowns on Escape key — standard UX
    this.escKeyHandler = (e) => {
      if (e.key === "Escape") this.closeAllDropdowns();
    };
    document.addEventListener("keydown", this.escKeyHandler);
  },

  beforeUnmount() {
    if (this.themeChangeHandler) {
      window.removeEventListener("themechange", this.themeChangeHandler);
    }
    if (this.outsideClickHandler) {
      document.removeEventListener("click", this.outsideClickHandler);
    }
    if (this.escKeyHandler) {
      document.removeEventListener("keydown", this.escKeyHandler);
    }
  },

  methods: {
    initializeTheme() {
      // Try multiple ways to get the theme
      let theme = "light-theme";

      // Method 1: Check if themeManager is available and initialized
      if (window.themeManager && typeof window.themeManager.getCurrentTheme === "function") {
        theme = window.themeManager.getCurrentTheme();
      }
      // Method 2: Check body class
      else if (document.body.classList.contains("dark-theme")) {
        theme = "dark-theme";
      }
      // Method 3: Check localStorage
      else {
        theme = localStorage.getItem("app-theme") || "light-theme";
      }

      this.currentTheme = theme;
    },
    syncThemeFromDOM() {
      // Sync component state with actual DOM theme
      if (document.body.classList.contains("dark-theme")) {
        this.currentTheme = "dark-theme";
      } else if (document.body.classList.contains("light-theme")) {
        this.currentTheme = "light-theme";
      } else {
        // Default to light if no class found
        this.currentTheme = "light-theme";
      }
    },
    toggleTheme() {
      const newTheme = this.isLightTheme ? "dark-theme" : "light-theme";
      
      if (window.themeManager && typeof window.themeManager.toggleTheme === "function") {
        window.themeManager.toggleTheme();
      } else {
        // Fallback: toggle manually
        const body = document.body;
        const html = document.documentElement;
        
        if (body && html) {
          body.classList.remove("light-theme", "dark-theme");
          html.classList.remove("light-theme", "dark-theme");
          body.classList.add(newTheme);
          html.classList.add(newTheme);
          localStorage.setItem("app-theme", newTheme);
        }
      }
      
      // Update local state immediately
      this.currentTheme = newTheme;
    },
    onThemeChange(event) {
      // Handle theme change event from other components
      // The event might come as event.detail.theme or just check DOM
      if (event.detail && event.detail.theme) {
        this.currentTheme = event.detail.theme;
      } else {
        // Fallback: sync from DOM
        this.syncThemeFromDOM();
      }
    },
    toggle_menu: function () {
      document.getElementById("wrapper").classList.toggle("toggled");
    },
    closeAllDropdowns: function () {
      if (this.show_notification || this.show_message || this.show_profile) {
        this.show_notification = 0;
        this.show_message = 0;
        this.show_profile = 0;
      }
    },
    handleOutsideClick: function (event) {
      // If no dropdown is open, nothing to do.
      if (!this.show_notification && !this.show_message && !this.show_profile) return;

      // Don't close if the click landed inside ANY header dropdown
      // (the toggle button OR the dropdown menu itself). That path is
      // handled by Vue's @click on the nav-item.
      const target = event.target;
      if (target.closest && target.closest(".right-nav-link .nav-item.dropdown")) {
        return;
      }

      this.closeAllDropdowns();
    },
    mark_as_seen: function (voucher) {
      this.unseen_vouchers = this.unseen_vouchers.filter(
        (v) => (v.id || v.title) !== (voucher.id || voucher.title)
      );
    },
    mark_all_as_seen: function () {
      this.unseen_vouchers = [];
    },
    logout: async function () {
      let con = await window.s_confirm("Are you sure want to logout?");
      if (con) {
        localStorage.removeItem("auth_token");
        window.location.href = "/";
      }
    },

    toggle_notification: function (type) {
      if (type == "show_notification") {
        this.show_notification = this.show_notification ? 0 : 1;
        this.show_message = 0;
        this.show_profile = 0;
      } else if (type == "show_message") {
        this.show_message = this.show_message ? 0 : 1;
        this.show_notification = 0;
        this.show_profile = 0;
      } else if (type == "show_profile") {
        this.show_profile = this.show_profile ? 0 : 1;
        this.show_notification = 0;
        this.show_message = 0;
      }
    },
  },
};
</script>

<style scoped>
/* Hide the legacy diamond arrow (::after) that app-style.css adds to every
   topbar dropdown menu. Use :global so the rule escapes scoped CSS. */
:global(.topbar-nav .navbar .dropdown-menu::after) {
  display: none !important;
  content: none !important;
}

/* =====================================
   UNIFIED HEADER ACTION BUTTONS
   All three (theme, notification, profile) share the same 40x40 frame
   ===================================== */

.theme-toggle-header {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.5rem;
}

.theme-switch-btn {
  position: relative;
  width: 40px;
  height: 40px;
  padding: 0;
  border: 1px solid var(--border-light, #e2e8f0);
  border-radius: 10px;
  background-color: var(--bg-card, #ffffff);
  color: var(--text-primary, #0f172a);
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.25s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
  overflow: hidden;
}

.theme-switch-btn:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  transform: translateY(-1px);
}

/* Light mode look — warm amber tint, sun icon */
.theme-switch-btn.light-mode {
  background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
  border-color: #fdba74;
  color: #d97706;
}
.theme-switch-btn.light-mode:hover {
  background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
  border-color: #fb923c;
}

/* Dark mode look — cool indigo tint, moon icon */
.theme-switch-btn.dark-mode {
  background: linear-gradient(135deg, #1e293b 0%, #312e81 100%);
  border-color: #6366f1;
  color: #e0e7ff;
}
.theme-switch-btn.dark-mode:hover {
  background: linear-gradient(135deg, #312e81 0%, #4338ca 100%);
  border-color: #818cf8;
}

.theme-switch-btn i {
  font-size: 18px;
  line-height: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  animation: rotate-icon 0.3s ease;
}

@keyframes rotate-icon {
  from {
    transform: rotate(0deg);
    opacity: 0.7;
  }
  to {
    transform: rotate(360deg);
    opacity: 1;
  }
}

.theme-switch-btn:active {
  transform: scale(0.95);
}

/* Dark theme adjustments */
:global(body.dark-theme) .theme-switch-btn {
  background-color: var(--bg-card, rgb(15, 23, 42));
  border-color: var(--border-dark, rgb(71, 85, 105));
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .theme-switch-btn:hover {
  background-color: var(--bg-hover, rgb(24, 33, 49));
  border-color: var(--border-color, rgb(113, 126, 159));
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* =====================================
   NAVBAR RIGHT NAV ITEMS STYLING
   ===================================== */

.right-nav-link {
  gap: 0.5rem;
  padding: 0 0.35rem;
  display: flex;
  align-items: center;
}

.right-nav-link .nav-item {
  display: flex;
  align-items: center;
}

.right-nav-link .nav-link {
  width: 40px;
  height: 40px;
  padding: 0;
  border-radius: 10px;
  border: 1px solid var(--border-light, #e2e8f0);
  background-color: var(--bg-card, #ffffff);
  color: var(--text-secondary, #475569);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.25s ease;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
  position: relative;
  cursor: pointer;
}

.right-nav-link .nav-link i {
  font-size: 18px;
  line-height: 1;
}

.right-nav-link .nav-link:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  transform: translateY(-1px);
}

.right-nav-link .nav-link:focus-visible {
  outline: 2px solid var(--primary-color, #3b82f6);
  outline-offset: 2px;
}

/* Notification badge */
.right-nav-link .badge-up {
  min-width: 18px;
  height: 18px;
  padding: 0 5px;
  font-size: 10px;
  font-weight: 700;
  line-height: 1;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: -5px;
  right: -5px;
  border: 2px solid var(--bg-card, #ffffff);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
}

/* Dropdown menu styling */
.right-nav-link .dropdown-menu {
  border: 1px solid var(--border-color, #e2e8f0);
  border-radius: 10px;
  background-color: var(--dropdown-bg, #ffffff);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  margin-top: 0.55rem;
  padding: 0.5rem 0;
  min-width: 280px;
}

.right-nav-link .list-group-flush {
  border-radius: 10px;
}

.right-nav-link .list-group-item {
  border-color: var(--border-light, #f1f5f9);
  background-color: var(--dropdown-bg, #ffffff);
  color: var(--text-secondary, #475569);
  padding: 0.75rem 0.9rem;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.right-nav-link .list-group-item:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
}

.right-nav-link .list-group-item.user-details {
  padding: 0.9rem;
  border-bottom: 1px solid var(--border-light, #f1f5f9);
}

.right-nav-link .dropdown-item {
  color: var(--text-secondary, #475569);
  background-color: var(--dropdown-bg, #ffffff);
  padding: 0.75rem 0.9rem;
  transition: background-color 0.2s ease, color 0.2s ease;
}

.right-nav-link .dropdown-item:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
}

.right-nav-link .dropdown-divider {
  border-top: 1px solid var(--border-light, #f1f5f9);
  margin: 0.35rem 0;
}

/* User profile image — fits inside the 40x40 nav-link */
.right-nav-link .user-profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  min-width: 30px;
  min-height: 30px;
  aspect-ratio: 1 / 1;
  border-radius: 50%;
  overflow: hidden;
  border: 2px solid var(--primary-color, #3b82f6);
  transition: border-color 0.25s ease, transform 0.25s ease;
  flex-shrink: 0;
  box-shadow: none;
}

.right-nav-link .nav-link:hover .user-profile {
  border-color: var(--primary-color, #2563eb);
  transform: scale(1.05);
}

/* Override global app-style.css that forces .user-profile img to 35x35 */
.right-nav-link .user-profile img,
.right-nav-link .user-profile .img-circle {
  width: 100%;
  height: 100%;
  max-width: 100%;
  max-height: 100%;
  object-fit: cover;
  border-radius: 50%;
  box-shadow: none;
  display: block;
}

/* User title/subtitle text styling */
.user-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
  margin-bottom: 0.15rem;
}

.user-subtitle {
  font-size: 0.85rem;
  color: var(--text-tertiary, #64748b);
  margin: 0;
}

.msg-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
}

.msg-info {
  font-size: 0.82rem;
  color: var(--text-tertiary, #64748b);
  margin: 0.25rem 0 0;
}

/* Dark theme nav items */
:global(body.dark-theme) .right-nav-link .nav-link {
  background-color: var(--bg-card, rgb(15, 23, 42));
  border-color: var(--border-dark, rgb(71, 85, 105));
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .right-nav-link .nav-link:hover {
  background-color: var(--bg-hover, rgb(30, 41, 59));
  border-color: var(--border-color, rgb(113, 126, 159));
  color: var(--text-primary, #e2e8f0);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

:global(body.dark-theme) .right-nav-link .badge-up {
  border-color: var(--bg-card, rgb(15, 23, 42));
}

/* =====================================
   NOTIFICATION DROPDOWN
   ===================================== */

.right-nav-link .notification-menu {
  min-width: 340px;
  max-width: 360px;
  padding: 0;
  margin-top: 0.65rem;
  border: 1px solid var(--border-color, #e2e8f0);
  border-radius: 14px;
  background-color: var(--dropdown-bg, #ffffff);
  box-shadow:
    0 10px 30px rgba(15, 23, 42, 0.12),
    0 4px 8px rgba(15, 23, 42, 0.04);
  overflow: hidden;
}

.notification-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.85rem 1rem;
  border-bottom: 1px solid var(--border-light, #f1f5f9);
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.06) 0%,
    rgba(99, 102, 241, 0.04) 100%
  );
}

.notification-header-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
}

.notification-header-title i {
  font-size: 1.05rem;
  color: var(--primary-color, #3b82f6);
}

.notification-count-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 20px;
  height: 20px;
  padding: 0 6px;
  border-radius: 999px;
  background-color: var(--primary-color, #3b82f6);
  color: #ffffff;
  font-size: 0.7rem;
  font-weight: 700;
  line-height: 1;
}

.notification-clear {
  font-size: 0.78rem;
  font-weight: 500;
  color: var(--primary-color, #3b82f6);
  text-decoration: none;
  transition: color 0.18s ease;
}
.notification-clear:hover {
  color: var(--primary-color, #2563eb);
  text-decoration: underline;
}

.notification-body {
  max-height: 360px;
  overflow-y: auto;
}

/* Custom scrollbar */
.notification-body::-webkit-scrollbar {
  width: 6px;
}
.notification-body::-webkit-scrollbar-track {
  background: transparent;
}
.notification-body::-webkit-scrollbar-thumb {
  background-color: var(--border-color, #cbd5e1);
  border-radius: 999px;
}

.notification-item {
  display: flex;
  align-items: flex-start;
  gap: 0.7rem;
  padding: 0.75rem 1rem;
  text-decoration: none;
  cursor: pointer;
  border-bottom: 1px solid var(--border-light, #f1f5f9);
  position: relative;
  transition: background-color 0.18s ease;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-item:hover {
  background-color: var(--bg-hover, #f8fafc);
}

.notification-item-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background-color: rgba(59, 130, 246, 0.1);
  color: var(--primary-color, #3b82f6);
  flex-shrink: 0;
}

.notification-item-icon i {
  font-size: 16px;
  line-height: 1;
}

.notification-item-content {
  display: flex;
  flex-direction: column;
  gap: 2px;
  min-width: 0;
  flex: 1;
}

.notification-item-title {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
  line-height: 1.3;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.notification-item-meta {
  font-size: 0.75rem;
  color: var(--text-tertiary, #64748b);
  line-height: 1.3;
}

.notification-item-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background-color: var(--primary-color, #3b82f6);
  flex-shrink: 0;
  margin-top: 0.4rem;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

/* Empty state */
.notification-empty {
  padding: 2.25rem 1rem;
  text-align: center;
}

.notification-empty-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-tertiary, #94a3b8);
  margin: 0 auto 0.75rem;
}

.notification-empty-icon i {
  font-size: 28px;
  line-height: 1;
}

.notification-empty-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
  margin: 0 0 0.25rem 0;
}

.notification-empty-subtitle {
  font-size: 0.78rem;
  color: var(--text-tertiary, #64748b);
  margin: 0;
}

/* Footer */
.notification-footer {
  border-top: 1px solid var(--border-light, #f1f5f9);
  background-color: var(--bg-hover, #f8fafc);
}

.notification-view-all {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  padding: 0.75rem 1rem;
  font-size: 0.82rem;
  font-weight: 600;
  color: var(--primary-color, #3b82f6);
  text-decoration: none;
  transition: background-color 0.18s ease, color 0.18s ease;
}
.notification-view-all:hover {
  background-color: rgba(59, 130, 246, 0.08);
  color: var(--primary-color, #2563eb);
  text-decoration: none;
}
.notification-view-all i {
  font-size: 1rem;
  transition: transform 0.18s ease;
}
.notification-view-all:hover i {
  transform: translateX(3px);
}

/* Dark theme — notification dropdown */
:global(body.dark-theme) .right-nav-link .notification-menu {
  border-color: var(--border-dark, rgb(51, 65, 85));
  background-color: var(--dropdown-bg, rgb(20, 28, 44));
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
}

:global(body.dark-theme) .notification-header {
  background: linear-gradient(
    135deg,
    rgba(99, 102, 241, 0.15) 0%,
    rgba(59, 130, 246, 0.08) 100%
  );
  border-bottom-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .notification-header-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .notification-item {
  border-bottom-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .notification-item:hover {
  background-color: rgba(99, 102, 241, 0.1);
}

:global(body.dark-theme) .notification-item-icon {
  background-color: rgba(99, 102, 241, 0.2);
  color: #a5b4fc;
}

:global(body.dark-theme) .notification-item-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .notification-item-meta {
  color: var(--text-tertiary, #94a3b8);
}

:global(body.dark-theme) .notification-empty-icon {
  background-color: rgba(148, 163, 184, 0.1);
  color: var(--text-tertiary, #94a3b8);
}

:global(body.dark-theme) .notification-empty-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .notification-empty-subtitle {
  color: var(--text-tertiary, #94a3b8);
}

:global(body.dark-theme) .notification-footer {
  background-color: rgba(15, 23, 42, 0.4);
  border-top-color: var(--border-dark, rgb(51, 65, 85));
}

/* =====================================
   PROFILE DROPDOWN — POLISHED
   ===================================== */

.right-nav-link .profile-menu {
  min-width: 280px;
  padding: 0;
  margin-top: 0.65rem;
  border: 1px solid var(--border-color, #e2e8f0);
  border-radius: 14px;
  background-color: var(--dropdown-bg, #ffffff);
  box-shadow:
    0 10px 30px rgba(15, 23, 42, 0.12),
    0 4px 8px rgba(15, 23, 42, 0.04);
  overflow: hidden;
}

/* Header section with avatar + name + email */
.profile-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.95rem 1rem;
  background: linear-gradient(
    135deg,
    rgba(59, 130, 246, 0.08) 0%,
    rgba(99, 102, 241, 0.06) 100%
  );
  border-bottom: 1px solid var(--border-light, #f1f5f9);
}

.profile-header-avatar {
  position: relative;
  width: 44px;
  height: 44px;
  flex-shrink: 0;
}

.profile-header-avatar img {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #ffffff;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.15);
  display: block;
}

.profile-status-dot {
  position: absolute;
  bottom: 1px;
  right: 1px;
  width: 11px;
  height: 11px;
  border-radius: 50%;
  background-color: #22c55e;
  border: 2px solid var(--dropdown-bg, #ffffff);
}

.profile-header-info {
  display: flex;
  flex-direction: column;
  min-width: 0;
  flex: 1;
}

.profile-name {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-primary, #0f172a);
  margin: 0 0 2px 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.2;
}

.profile-email {
  font-size: 0.78rem;
  color: var(--text-tertiary, #64748b);
  margin: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.3;
}

/* Divider */
.profile-divider {
  height: 1px;
  margin: 0.35rem 0;
  background-color: var(--border-light, #f1f5f9);
  list-style: none;
}

/* Menu items */
.profile-link {
  display: flex;
  align-items: center;
  gap: 0.7rem;
  width: 100%;
  padding: 0.6rem 1rem;
  border: none;
  background: transparent;
  text-align: left;
  font-size: 0.88rem;
  font-weight: 500;
  color: var(--text-secondary, #475569);
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.18s ease, color 0.18s ease, padding-left 0.18s ease;
}

.profile-link:hover {
  background-color: var(--bg-hover, #f1f5f9);
  color: var(--text-primary, #0f172a);
  padding-left: 1.15rem;
  text-decoration: none;
}

.profile-link:focus-visible {
  outline: 2px solid var(--primary-color, #3b82f6);
  outline-offset: -2px;
}

.profile-link-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background-color: rgba(59, 130, 246, 0.1);
  color: var(--primary-color, #3b82f6);
  flex-shrink: 0;
  transition: background-color 0.18s ease, color 0.18s ease;
}

.profile-link-icon i {
  font-size: 16px;
  line-height: 1;
}

.profile-link:hover .profile-link-icon {
  background-color: var(--primary-color, #3b82f6);
  color: #ffffff;
}

.profile-link-label {
  flex: 1;
  line-height: 1.2;
}

.profile-link-arrow {
  font-size: 16px;
  color: var(--text-tertiary, #94a3b8);
  opacity: 0;
  transform: translateX(-4px);
  transition: opacity 0.18s ease, transform 0.18s ease;
}

.profile-link:hover .profile-link-arrow {
  opacity: 1;
  transform: translateX(0);
}

/* Danger (logout) variant */
.profile-link-danger {
  color: #dc2626;
}
.profile-link-danger .profile-link-icon {
  background-color: rgba(220, 38, 38, 0.1);
  color: #dc2626;
}
.profile-link-danger:hover {
  background-color: rgba(220, 38, 38, 0.08);
  color: #b91c1c;
}
.profile-link-danger:hover .profile-link-icon {
  background-color: #dc2626;
  color: #ffffff;
}

/* Dark theme overrides */
:global(body.dark-theme) .right-nav-link .profile-menu {
  border-color: var(--border-dark, rgb(51, 65, 85));
  background-color: var(--dropdown-bg, rgb(20, 28, 44));
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
}

:global(body.dark-theme) .profile-header {
  background: linear-gradient(
    135deg,
    rgba(99, 102, 241, 0.18) 0%,
    rgba(59, 130, 246, 0.1) 100%
  );
  border-bottom-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .profile-header-avatar img {
  border-color: rgb(30, 41, 59);
}

:global(body.dark-theme) .profile-status-dot {
  border-color: rgb(20, 28, 44);
}

:global(body.dark-theme) .profile-name {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .profile-email {
  color: var(--text-tertiary, #94a3b8);
}

:global(body.dark-theme) .profile-divider {
  background-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .profile-link {
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .profile-link:hover {
  background-color: rgba(99, 102, 241, 0.12);
  color: #ffffff;
}

:global(body.dark-theme) .profile-link-icon {
  background-color: rgba(99, 102, 241, 0.18);
  color: #a5b4fc;
}

:global(body.dark-theme) .profile-link-danger {
  color: #f87171;
}
:global(body.dark-theme) .profile-link-danger .profile-link-icon {
  background-color: rgba(248, 113, 113, 0.15);
  color: #f87171;
}
:global(body.dark-theme) .profile-link-danger:hover {
  background-color: rgba(248, 113, 113, 0.12);
  color: #fca5a5;
}

:global(body.dark-theme) .right-nav-link .dropdown-menu {
  border-color: var(--border-dark, rgb(51, 65, 85));
  background-color: var(--dropdown-bg, rgb(30, 41, 59));
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

:global(body.dark-theme) .right-nav-link .list-group-item,
:global(body.dark-theme) .right-nav-link .dropdown-item {
  border-color: var(--border-dark, rgb(51, 65, 85));
  background-color: var(--dropdown-bg, rgb(30, 41, 59));
  color: var(--text-secondary, #cbd5e1);
}

:global(body.dark-theme) .right-nav-link .list-group-item:hover,
:global(body.dark-theme) .right-nav-link .dropdown-item:hover {
  background-color: var(--bg-hover, rgb(51, 65, 85));
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .right-nav-link .dropdown-divider {
  border-top-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .user-profile {
  border-color: var(--border-dark, rgb(51, 65, 85));
}

:global(body.dark-theme) .user-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .user-subtitle {
  color: var(--text-tertiary, #94a3b8);
}

:global(body.dark-theme) .msg-title {
  color: var(--text-primary, #e2e8f0);
}

:global(body.dark-theme) .msg-info {
  color: var(--text-tertiary, #94a3b8);
}
</style>
