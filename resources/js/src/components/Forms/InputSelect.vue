<script setup>
const props = defineProps({
  label: {
    required: false,
    default: "",
  },
  modelValue: {
    required: true,
  },

  items: {
    default: [],
    required: false,
  },
  itemKey: {
  },
  itemValue: {
  },
  defaultOptions: {
    default: "Lütfen bir değer seçiniz",
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
  <div class="w-full flex flex-col">
    <label class="theme-label">
      {{ label }}
      <span v-if="required" class="required text-red-500 text-sm">*</span>
    </label>

    <select @change="onKeyup" :class="element && element.error && element.error[field]
      ? 'theme-input-error'
      : 'theme-input'
      " :value="modelValue" :disabled="disabled" @input="(e) => $emit('update:modelValue', e.target.value)">

      <option value="" selected="selected" disabled="disabled" hidden="hidden">
        {{ defaultOptions }}
      </option>
      <option v-for="(item, index) in items" :key="index" :value="itemKey ? item[itemKey] : item[0]">
        {{ itemValue ? item[itemValue] : item[1] }}
      </option>
    </select>

    <span v-if="element && element.error && element.error[field]" class="error-message text-red-500 mt-2 pl-1 text-sm">
      {{ element.error[field] }}
    </span>
  </div>
</template>