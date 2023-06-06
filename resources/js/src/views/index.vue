<script setup>
import { onMounted, ref } from 'vue';
import axios from '@/plugins/app-axios.js'
import CreateWallet from '@/views/components/CreateWallet.vue'
import CreateAmount from '@/views/components/CreateAmount.vue'
import CreateAmountExpense from '@/views/components/CreateAmountExpense.vue'
import AmountTransfer from '@/views/components/AmountTransfer.vue'
import box from '@/store/box.js'

const walletList = ref([])
const activeWallet = ref({})

const createWalletPopup = ref(false)
const createAmountPopup = ref(false)
const createAmountExpense = ref(false)
const amountTransfer = ref(false)
const loader = ref(false)

const getCurrencyInformation = async () => {
    loader.value = true
    try {
        const { data } = await axios.get("/api/fetchPursesOfUser")
        walletList.value = data
    } catch (error) {
    }
    finally {
        loader.value = false
    }
}
const openAmountPopup = (wallet) => {
    activeWallet.value = wallet
    createAmountPopup.value = !createAmountPopup.value
}

const openExpense = (wallet) => {
    activeWallet.value = wallet
    createAmountExpense.value = !createAmountExpense.value
}

onMounted(() => {
    getCurrencyInformation()
})
</script>

<template>
    <CreateWallet v-if="createWalletPopup" :walletList="walletList" @popupClose="createWalletPopup = !createWalletPopup" />
    <CreateAmount v-if="createAmountPopup" :wallet="activeWallet" @popupClose="createAmountPopup = !createAmountPopup" />
    <CreateAmountExpense v-if="createAmountExpense" :wallet="activeWallet"
        @popupClose="createAmountExpense = !createAmountExpense" />
    <AmountTransfer v-if="amountTransfer" :walletList="walletList" @popupClose="amountTransfer = !amountTransfer" />

    <div class="flex flex-col gap-5">
        <div id="header" class="flex justify-between">
            <InputButton @click="createWalletPopup = !createWalletPopup" text="Add Wallet" />
            <InputButton @click="walletList.length == 1 ? box.addError('Hata', 'Please select a account') : amountTransfer = !amountTransfer" text="Money Transfer" />
        </div>
        <div v-if="loader" class="grid grid-cols-3 gap-2">
            <Skeleton v-for="(item, index) of 3" :key="index" />
        </div>
        <div v-else class="grid grid-cols-3 gap-2">
            <Wallet v-for="wallet of walletList" :key="wallet.id" :wallet="wallet">
                <template #footer>
                    <div class="flex gap-2 mt-5">
                        <InputButton @click="openAmountPopup(wallet)" text="Top up Balance" />
                        <InputButton @click="openExpense(wallet)" text="Create an Expense" />
                    </div>
                </template>
            </Wallet>

        </div>
    </div>
</template>