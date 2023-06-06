<script setup>
import { reactive, ref } from "@vue/reactivity";
import errorManager from '@/lib/validatons.js'
import axios from '@/plugins/app-axios.js'
import box from "@/store/box.js";

import { useRouter } from "vue-router";

const router = useRouter();

const { required } = errorManager()

const formData = reactive({
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    dirty: false,
    error: {}
})

const loader = ref(false)

const emailValidation = () => {
    if (!formData.dirty) return
    required(formData, 'email')
}

const usernameValidation = () => {
    if (!formData.dirty) return
    required(formData, 'username')
}


const passwordConfirmationValidation = () => {
    if (!formData.dirty) return
    required(formData, 'password_confirmation')
}

const passwordValidation = () => {
    if (!formData.dirty) return
    required(formData, 'password')
}

const passwordCheck = () => {
    if (formData.password != formData.password_confirmation) {
        formData.error.password_confirmation = "Şifreleriniz uyuşmuyor"
        return loader.value = false
    }
}


const login = async () => {
    formData.dirty = true
    emailValidation()
    passwordValidation()
    usernameValidation()
    passwordConfirmationValidation()
    passwordCheck()

    if (Object.keys(formData.error).length > 0) {
        box.addError("Error", "Please fill out the form completely");
        return;
    }

    loader.value = true

    const copyData = {
        username: formData.username,
        email: formData.email,
        password: formData.password,
        password_confirmation: formData.password_confirmation
    }
    try {
        const { data } = await axios.post("api/register", copyData)
        box.addSuccess('Success', `Regsiter is successful`)

        router.push({ name: "login" })
    } catch (error) {
        if (error.response) {
            if(error.response.status == 422) {
                Object.keys(error.response?.data.errors).forEach(key => {
                    box.addError('Error', `${error.response?.data.errors[key][0]}`)
                })
            }else {
                box.addError('Error', `${error.response?.data.message}`)
            }
        }
    } finally {
        loader.value = false
    }

}
</script>

<template>
    <div class="bg-white p-8 max-w-[520px] mx-auto rounded">
        <h3 class="mb-5">
            Register
        </h3>
        <form @submit.prevent="login" class="grid grid-rows-1 gap-2">
            <div>
                <InputTag :disabled="loader" :onKeyup="usernameValidation" :element="formData" field="username"
                    v-model="formData.username" type="text" label="Username" required />
            </div>
            <div>
                <InputTag :disabled="loader" :onKeyup="emailValidation" :element="formData" field="email"
                    v-model="formData.email" type="text" label="E-mail" required />
            </div>
            <div>
                <InputTag :disabled="loader" :onKeyup="passwordValidation" :element="formData" field="password"
                    v-model="formData.password" type="password" label="Password" required />
            </div>
            <div>
                <InputTag :disabled="loader" :onKeyup="passwordConfirmationValidation" :element="formData"
                    field="password_confirmation" v-model="formData.password_confirmation" type="password"
                    label="Paswword Confirm" required />
            </div>
            <div>
                <InputButton :loader="loader" type="submit" text="Register" />
            </div>
        </form>
        <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-5">
            Already have an account?
            <router-link :to="{ name: 'login' }" class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                Login
            </router-link>
        </p>
    </div>
</template>