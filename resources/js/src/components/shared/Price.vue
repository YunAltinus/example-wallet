<script setup>
const props = defineProps({
    walletList: {
        type: Array,
        defaulf: []
    }
})

const financial = (number) => {
    if (number) return `${Number.parseFloat(number).toFixed(2)}`;
}
</script>

<template>
    <span v-for="(wallet, index) in walletList" :key="index" class="text-md">
        ≈ <strong> {{ financial(wallet.amount) }} {{ wallet.currency }}</strong> <br>
    </span>
</template>