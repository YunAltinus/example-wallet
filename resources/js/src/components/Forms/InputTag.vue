<script setup>
const props = defineProps({
  type: {
    required: false,
    default: "text",
  },
  label: {
    required: false,
    default: "",
  },
  modelValue: {
    required: false,
    default: "",
  },
  placeholder: {
    required: false,
    default: "",
  },
  onKeyup: {
    required: false,
    default: () => void 0,
  },
  element: {
    type: Object,
    required: true,
  },
  field: {
    type: String,
    required: true,
  },
  disabled: {
    default: false,
    type: Boolean,
  },
  required: {
    default: false,
    type: Boolean,
  },
});
</script>
<template>
  <div class="flex flex-col w-full">
    <label v-if="label" class="theme-label"
      >{{ label }}
      <span v-if="required" class="required text-red-500 text-sm"
        >*</span
      ></label
    >
    <input
      @keyup="onKeyup"
      :class="
        element && element.error && element.error[field]
          ? 'theme-input-error'
          : 'theme-input'
      "
      :placeholder="placeholder"
      :type="type"
      :value="modelValue"
      :disabled="disabled"
      @input="(e) => $emit('update:modelValue', e.target.value)"
    />
    <span
      v-if="element && element.error && element.error[field]"
      class="error-message text-red-500 mt-2 pl-1 text-sm"
    >
      {{ element.error[field] }}
    </span>
  </div>
</template>