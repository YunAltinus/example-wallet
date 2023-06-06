<script setup>
import { reactive, ref } from 'vue';
import errorManager from '@/lib/validatons.js'
import axios from '@/plugins/app-axios.js'
import box from "@/store/box.js";
import { transferType } from '@/lib/enums.js'


const { required } = errorManager()

const props = defineProps({
    walletList: { type: Array, required: false, default: [] }
})

const formData = reactive({
    fromCurrency: '',
    toCurrency: '',
    fromWalletId: '',
    toWalletId: '',
    error: {},
    dirty: false,
})

const loader = ref(false)

const emit = defineEmits(["popupClose"]);

const fromCurrencyValidation = () => {
    if (!formData.dirty) return
    required(formData, 'fromCurrency')
}

const toCurrencyValidation = () => {
    if (!formData.dirty) return
    required(formData, 'toCurrency')
}

const amountValidation = () => {
    if (!formData.dirty) return
    required(formData, 'amount')
}


const popupClose = () => {
    emit("popupClose");
};

const popupSubmit = async () => {
    formData.dirty = true

    fromCurrencyValidation()
    toCurrencyValidation()
    amountValidation()

    if (Object.keys(formData.error).length > 0) {
        box.addError("Error", "Please fill out the form completely");
        return;
    }

    loader.value = true

    const wallatListEach = () => {
        props.walletList.forEach((wallet) => {
            if (wallet["currency"] == formData.fromCurrency) formData["fromWalletId"] = wallet.id
            if (wallet["currency"] == formData.toCurrency) formData["toWalletId"] = wallet.id
        })
    }
    const responseWallatListEach = (data) => {
        props.walletList.forEach((wallet) => {
            if (wallet["currency"] == data.fromWallet.currency) wallet.totalAmount = data.fromWallet.totalAmount
            if (wallet["currency"] == data.toWallet.currency) wallet.totalAmount = data.toWallet.totalAmount
        })
    }

    wallatListEach()

    const copyData = {
        amount: formData.amount,
        fromWalletId: formData.fromWalletId,
        toWalletId: formData.toWalletId,
        toCurrency: formData.toCurrency,
        fromCurrency: formData.fromCurrency,
        toWalletId: formData.toWalletId,
        transfer: transferType.TRANSFER
    }

    try {
        const { data } = await axios.post("/api/exchangePurseToPurse", copyData)
        box.addSuccess('Success', `Wallet creation successful`)

        responseWallatListEach(data)
        popupClose()
    } catch (error) {
        box.addError('Error', `${error.response?.data.message}`)
    }
    finally {
        loader.value = false
    }
}

</script>
<template>
    <Popup :loader="loader" @popup-close="popupClose" @popup-submit="popupSubmit">
        <template #header>
            Money Transfer Between Accounts
        </template>
        <template #body>
            <div class="flex flex-col gap-3">
                <div>
                    <InputSelect :items="walletList.filter((wallet) => wallet.currency != formData.toCurrency)"
                        itemKey="currency" itemValue="currency" label="Select a Transaction Wallet"
                        defaultOptions="Please select a wallet" v-model="formData.fromCurrency" :element="formData"
                        field="fromCurrency" :onKeyup="fromCurrencyValidation" required :disabled="loader" />
                </div>
                <div>
                    <InputSelect :items="walletList.filter((wallet) => wallet.currency != formData.fromCurrency)"
                        itemKey="currency" itemValue="currency" label="Select a Transaction Wallet"
                        defaultOptions="Please select a wallet" v-model="formData.toCurrency" :element="formData"
                        :onKeyup="toCurrencyValidation" field="toCurrency" required :disabled="loader" />
                </div>
                <div>
                    <InputTag itemKey="amount" itemValue="amount" label="Select Amount to Transaction"
                        defaultOptions="Please enter the amount" v-model="formData.amount" :element="formData"
                        :onKeyup="amountValidation" field="toCurrency" required :disabled="loader" />
                </div>
            </div>
        </template>
    </Popup>
</template>