<template>
  <li
    v-show="matches_search"
    :class="{ active: isActive }"
    class="border border-primary rounded"
  >
    <router-link
      :to="{ name: route_name }"
      :class="{ active: isActive }"
      @click="onMenuClick"
    >
      <div class="parent-icon"><i :class="icon"></i></div>
      <div class="menu-title">{{ menu_title }}</div>
      <!-- <div class="badge badge-light ml-auto">New</div> -->
    </router-link>
  </li>
</template>

<script>
export default {
  props: {
    menu_title: String,
    route_name: String,
    icon: {
      type: String,
      default: () => "zmdi zmdi-view-dashboard",
    },
    search_query: {
      type: String,
      default: "",
    },
  },
  computed: {
    isActive() {
      return this.$route.name === this.route_name;
    },
    matches_search() {
      const q = (this.search_query || "").toLowerCase().trim();
      if (!q) return true;
      return (this.menu_title || "").toLowerCase().includes(q);
    },
  },
  methods: {
    onMenuClick() {
      // Close all dropdown menus when single menu is clicked
      window.dispatchEvent(
        new CustomEvent("collapse-all-menus", {
          detail: { except: null },
        }),
      );
    },
  },
};
</script>

<style>
/* Active single menu styling */
li.active > a,
li > a.active {
  background-color: #007bff !important;
  color: white !important;
  border-radius: 4px;
  margin: 2px;
}
</style>
