<script setup>
import { reactive, ref } from 'vue';
import errorManager from '@/lib/validatons.js'
import axios from '@/plugins/app-axios.js'
import box from "@/store/box.js";


const { required } = errorManager()

const props = defineProps({
    walletList: { type: Array, required: false, default: [] }
})

const formData = reactive({
    currency: '',
    dirty: false,
    error: {}
})

const loader = ref(false)

const emit = defineEmits(["popupClose"]);

const currencyValidation = () => {
    if (!formData.dirty) return
    required(formData, 'currency')
}


const popupClose = () => {
    emit("popupClose");
};

const popupSubmit = async () => {
    formData.dirty = true

    currencyValidation()

    if (Object.keys(formData.error).length > 0) {
        box.addError("Error", "Please fill out the form completely");
        return;
    }

    loader.value = true

    const copyData = {
        currency: formData.currency.toUpperCase()
    }

    // İstek Atıcaz
    try {
        const { data } = await axios.post("/api/createPurse", copyData)
        box.addSuccess('Success', `Add expense successful`)
        props.walletList.push(data)
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
            Create Wallet
        </template>
        <template #body>
            <div>
                <InputTag :disabled="loader" :onKeyup="currencyValidation" :element="formData" field="currency"
                    v-model="formData.currency" type="text" label="Currency" required />
            </div>
        </template>
    </Popup>

</template>