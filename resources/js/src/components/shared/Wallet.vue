<script setup>
const props = defineProps({
    wallet: {
        type: Object,
        required: false,

    }
})
</script>
<template>

    <div class="bg-white p-4 shadow-sm rounded-md">
        <div id="currency-name">
            <span class="font-semibold">
                {{ wallet.currency }}
            </span>
        </div>
        <div id="currency-amount">
            <Price :amount="wallet.totalAmount" :currency="wallet.currency" />
        </div>
        <div id="currency-footer">
            <slot :item="wallet" name="footer"/>
        </div>
    </div>
</template>