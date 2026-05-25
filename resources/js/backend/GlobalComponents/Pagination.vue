<template>
  <nav aria-label="" class="index-pagination-wrap d-flex align-items-center" style="gap: 10px; flex-wrap: wrap;">
    <ul class="pagination my-2" style="font-size: 11px; margin-bottom: 0;">
      <template v-for="(link, index) in data.links" :key="index">
        <li class="page-item" :class="{ active: link.active }">
          <a class="page-link"
            :class="data.current_page == data.last_page && data.links.length - 1 == index ? 'disabled' : ''"
            @click.prevent="set_page_data(link)" :href="link.url" v-html="`<span>${link.label}</span>`">
          </a>
        </li>
      </template>
    </ul>
    <div class="d-flex align-items-center" style="gap: 4px; font-size: 12px; white-space: nowrap;">
      <span>{{ data.from }}</span>
      <span>-</span>
      <span>{{ data.to }}</span>
      <span>of</span>
      <span>{{ data.total }}</span>
    </div>
    <div class="d-flex align-items-center" style="gap: 5px; font-size: 12px; white-space: nowrap;">
      <span>Limit</span>
      <select @change="set_per_page_limit" class="pagination-limit-select rounded-1">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="30">30</option>
        <option value="50">50</option>
        <option value="100">100</option>
      </select>
    </div>
  </nav>
</template>

<script>
export default {
  props: {
    data: Object,
    set_page: Function,
    get_data: Function,
    set_paginate: Function,
  },
  methods: {
    set_page_data: function (link) {
      try {
        let url = new URL(link.url);
        let page = url.searchParams.get("page");
        link.url ? this.set_page(parseInt(page)) : "";
        this.get_data();
      } catch (error) { }
    },
    set_per_page_limit: function () {
      this.set_paginate(event.target.value);
      this.set_page(1);
      this.get_data();
    },
  },
};
</script>

<style>
.pagination-limit-select {
    background: transparent;
    color: inherit;
    border: 1px solid var(--border-color, #dee2e6);
    padding: 2px 4px;
    font-size: 12px;
    cursor: pointer;
}

@media (max-width: 767px) {
    .index-pagination-wrap {
        padding: 4px 0;
    }
    .pagination-limit-select {
        padding: 3px 6px;
    }
}
</style>
