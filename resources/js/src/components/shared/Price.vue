<template>
    <span id="price" class="text-md">
        {{ financial(amount) }} {{ currency }}
    </span>
</template>
<script setup>
const financial = (number) => {
    if (number) return `${Number.parseFloat(number).toFixed(2)}`;
}

const props = defineProps({
    amount: {
        type: String,
        default: 0,
        required: false
    },
    currency: {
        type: String,
        default: 0,
        required: false,
    }
})
</script>