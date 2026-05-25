<template>
  <div class="toast" :class="{ show: toastVisible }">{{ toastMsg }}</div>
</template>

<script setup>
import { storeToRefs } from 'pinia';
import { useHomePageStore } from '../Store/index';

const { toastVisible, toastMsg } = storeToRefs(useHomePageStore());
</script>
