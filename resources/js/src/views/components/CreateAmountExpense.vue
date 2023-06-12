<script setup>
import { reactive, ref } from 'vue';
import errorManager from '@/lib/validatons.js'
import axios from '@/plugins/app-axios.js'
import box from "@/store/box.js";
import { transactionType, supportedCodes } from '@/lib/enums.js'


const { required } = errorManager()

const props = defineProps({
    wallet: { type: Object, required: false, default: {} },
    getWalletExchange: { type: Function }
})

const formData = reactive({
    amount: '',
    currrency: '',
    dirty: false,
    error: {}
})

const loader = ref(false)

const emit = defineEmits(["popupClose", 'changeData']);

const amountValidation = () => {
    if (!formData.dirty) return
    required(formData, 'amount')
}

const currencyValidation = () => {
    if (!formData.dirty) return
    required(formData, 'currency')
}

const popupClose = () => {
    emit("popupClose");
};

const responseWallatListEach = (newWalletList) => {
    emit('changeData', newWalletList)
}

const popupSubmit = async () => {
    formData.dirty = true

    amountValidation()
    currencyValidation()

    if (Object.keys(formData.error).length > 0) {
        box.addError("Error", "Please fill out the form completely");
        return;
    }

    loader.value = true

    const copyData = {
        amount: formData.amount,
        currency: formData.currency,
        walletId: props.wallet.id,
        transaction: transactionType.MONEY_OUT
    }

    // İstek Atıcaz
    try {
        const { data } = await axios.post("/api/addAmountToWallet", copyData)
        box.addSuccess('Success', `Add expense successful`)
        responseWallatListEach(data)
        props.getWalletExchange()
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
            Create an Expense
        </template>
        <template #body>
            <div>
                <InputTag :disabled="loader" :onKeyup="amountValidation" :element="formData" field="amount"
                    v-model="formData.amount" type="number" label="Enter the amount" required />

                <InputSelect :items="supportedCodes" itemKey="0" itemValue="1" label="Currency"
                    defaultOptions="Please select a currency" v-model="formData.currency" :element="formData"
                    field="currency" :onKeyup="currencyValidation" required :disabled="loader" />
            </div>
        </template>
    </Popup>
</template>