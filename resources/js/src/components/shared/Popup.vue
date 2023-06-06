<script setup>
const props = defineProps({
  className: {
    type: String,
    required: false,
  },
  loader:{
    type:Boolean,
    required:false,
    default:false
  }
});
</script>

<template>
  <teleport to="#modal-box">
    <div
      class="w-full h-full fixed left-0 top-0 z-50 bg-black-rgb flex items-center justify-center"
    >
      <div
        :class="className"
        class="bg-white rounded-sm relative overflow-x-auto w-[500px]"
      >
        <div class="modal-box-header p-4 ">
          <h3 class="text-black">
            <slot name="header" />
          </h3>
        </div>
        <div class="modal-box-body px-4 pb-3 h-full">
          <slot name="body" />
        </div>
        <div
          @click="loader ? void 0 : $emit('popup-close')"
          class="close w-6 h-6 bg-theme-main text-white rounded-full absolute right-5 top-4 flex items-center justify-center cursor-pointer"
        >
          <fai class="text-xs" icon="xmark" />
        </div>
        <div class="modal-box-footer flex gap-2 justify-end border-t border-t-gray-200 p-4">
            <InputButton class="!bg-gray-400" text="Close" @click="loader ? void 0 : $emit('popup-close')"/>
            <InputButton :loader="loader" text="Save" @click="$emit('popup-submit')"/>
        </div>
      </div>
    </div>
  </teleport>
</template>