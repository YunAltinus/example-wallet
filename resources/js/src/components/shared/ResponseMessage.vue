<script setup>
import { deleteToast } from "@/store/widget/toast.js";
import { onMounted } from "vue";
const props = defineProps({
  toastData: {
    type: Object,
    default: {},
    required: true,
  },
  length: {
    type: Number,
    required: true,
  },
});

onMounted(() => {
  setTimeout(() => {
    deleteToast(props.toastData.uniqId);
  }, props.toastData.time);
});
</script>

<template>
  <div id="response-message" :class="toastData.cls">
    <div class="icon-box" :class="toastData.iconCls">
      <fai class="text-white" :icon="toastData.icon" />
    </div>
    <div id="response-content" class="flex flex-col">
      <h4 class="text-gray-900">{{ toastData.title }}</h4>
      <span class="text-sm">{{ toastData.text }}</span>
    </div>
  </div>
</template>