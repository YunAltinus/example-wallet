<script setup>
import { reactive, ref } from 'vue';
import errorManager from '@/lib/validatons.js'
import axios from '@/plugins/app-axios.js'
import box from "@/store/box.js";
import {transactionType} from '@/lib/enums.js'


const { required } = errorManager()

const props = defineProps({
    wallet: { type: Object, required: false, default: {} }
})

const formData = reactive({
    amount: '',
    dirty: false,
    error: {}
})

const loader = ref(false)

const emit = defineEmits(["popupClose"]);

const amountValidation = () => {
    if (!formData.dirty) return
    required(formData, 'amount')
}


const popupClose = () => {
    emit("popupClose");
};

const popupSubmit = async () => {
    formData.dirty = true

    amountValidation()

    if (Object.keys(formData.error).length > 0) {
        box.addError("Error", "Please fill out the form completely");
        return;
    }

    loader.value = true

    const copyData = {
        amount: formData.amount,
        walletId: props.wallet.id,
        transaction: transactionType.MONEY_OUT
    }

    // İstek Atıcaz
    try {
        const { data } = await axios.post("/api/addAmountToPurse", copyData)
        box.addSuccess('Success', `Add expense successful`)
        Object.assign(props.wallet, data)
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
            Gider Oluştur
        </template>
        <template #body>
            <div class="flex flex-col gap-2 bg-slate-300 p-2 mb-3">
                <h5>Aktif Hesap</h5>
                <Wallet :wallet="wallet" />
            </div>
            <div>
                <InputTag :disabled="loader" :onKeyup="amountValidation" :element="formData" field="amount"
                    v-model="formData.amount" type="number" label="Oluşturulcak Tutar" required />
            </div>
        </template>
    </Popup>
</template>