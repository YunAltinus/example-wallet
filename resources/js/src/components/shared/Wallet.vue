<script setup>
import { supportedCodes } from "@/lib/enums.js"

const props = defineProps({
    walletList: {
        type: Array,
        default: []
    }
})
</script>

<template>
    <div class="bg-white p-4 shadow-sm rounded-md w-full">
        <div id="currency-name">
            <div class="flex justify-between">
                <h2 class="font-semibold mb-4">
                    Account
                </h2>
                <div>
                    <!-- <InputButton @click="openAmountPopup(wallet)" text="Exchange" /> -->
                </div>
            </div>
        </div>
        <div id="currency-amount">
            <Price :walletList="walletList" />
        </div>
        <div id="currency-footer">
            <slot :item="wallet" name="footer" />
        </div>
    </div>
</template>