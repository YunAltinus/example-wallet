<script setup>
import { onMounted, ref } from 'vue';
import axios from '@/plugins/app-axios.js'
import CreateWallet from '@/views/components/CreateWallet.vue'
import CreateAmount from '@/views/components/CreateAmount.vue'
import CreateAmountExpense from '@/views/components/CreateAmountExpense.vue'
import AmountTransfer from '@/views/components/AmountTransfer.vue'
import ExchangeTable from '@/views/components/ExchangeTable.vue'
import box from '@/store/box.js'

const walletList = ref([])
const exchangeList = ref([])
const activeWallet = ref({})

const createWalletPopup = ref(false)
const createAmountPopup = ref(false)
const createAmountExpense = ref(false)
const amountTransfer = ref(false)
const loader = ref(false)

const getCurrencyInformation = async () => {
    loader.value = true
    try {
        const { data } = await axios.get("/api/fetchWalletsOfUser")
        walletList.value = data
    } catch (error) {
        console.log(error)
    }
    finally {
        loader.value = false
    }
}

const getWalletExchange = async () => {
    loader.value = true
    try {
        const { data } = await axios.get("/api/fetchWalletExchange")
        exchangeList.value = data
    } catch (error) {
        console.log(error)
    }
    finally {
        loader.value = false
    }
}

const openAmountPopup = () => {
    // activeWallet.value = wallet
    createAmountPopup.value = !createAmountPopup.value
}

const openExpense = () => {
    // activeWallet.value = wallet
    createAmountExpense.value = !createAmountExpense.value
}

const changeWalletList = (newWalletList) => {
    walletList.value = newWalletList
}

onMounted(() => {
    getCurrencyInformation()

    getWalletExchange()
})
</script>

<template>
    <CreateWallet v-if="createWalletPopup" :walletList="walletList" @popupClose="createWalletPopup = !createWalletPopup" />
    
    <CreateAmount :getWalletExchange="getWalletExchange" @changeData="changeWalletList" v-if="createAmountPopup" :wallet="activeWallet" @popupClose="createAmountPopup = !createAmountPopup" />
    
    <CreateAmountExpense :getWalletExchange="getWalletExchange" @changeData="changeWalletList" v-if="createAmountExpense" :wallet="activeWallet"
        @popupClose="createAmountExpense = !createAmountExpense" />
    <AmountTransfer :getWalletExchange="getWalletExchange" @changeData="changeWalletList" v-if="amountTransfer" :walletList="walletList"
        @popupClose="amountTransfer = !amountTransfer" />

    <div class="flex flex-col gap-5">
        <div id="header" class="flex justify-between">
            <!-- <InputButton @click="createWalletPopup = !createWalletPopup" text="Add Wallet" /> -->
            <InputButton
                @click="walletList.length == 1 ? box.addError('Error', 'You must have at least two wallets') : amountTransfer = !amountTransfer"
                text="Money Transfer" />
        </div>
        <div v-if="loader" class="grid grid-cols-1 gap-2">
            <Skeleton />
        </div>
        <div v-else class="grid grid-cols-1 gap-2">
            <Wallet :walletList="walletList">
                <template #footer>
                    <div class="flex gap-2 mt-5">
                        <InputButton @click="openAmountPopup()" text="Top up Balance" />
                        <InputButton @click="openExpense()" text="Create an Expense" />
                    </div>
                </template>
            </Wallet>

        </div>

        <div>
            <ExchangeTable :exchangeList="exchangeList" />
        </div>
    </div>
</template>