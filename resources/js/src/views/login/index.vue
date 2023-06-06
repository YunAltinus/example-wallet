<script setup>
import { reactive, ref } from "@vue/reactivity";
import errorManager from '@/lib/validatons.js'
import axios from '@/plugins/app-axios.js'
import box from "@/store/box.js";

import { useRouter } from "vue-router";

const router = useRouter();

const { required } = errorManager()

const formData = reactive({
    email: '',
    password: '',
    dirty: false,
    error: {}
})

const loader = ref(false)

const emailValidation = () => {
    if (!formData.dirty) return
    required(formData, 'email')
}

const passwordValidation = () => {
    if (!formData.dirty) return
    required(formData, 'password')
}

const login = async () => {
    formData.dirty = true
    emailValidation()
    passwordValidation()

    if (Object.keys(formData.error).length > 0) {
        box.addError("Error", "Please fill out the form completely");
        return;
    }
    loader.value = true

    const copyData = {
        email: formData.email,
        password: formData.password
    }
    try {
        const { data } = await axios.post("api/login", copyData)

        box.addSuccess('Success', `Login is successful`)
        $cookies.set("accessToken", data.accessToken)

        router.push({ name: "home" })

    } catch (error) {
        if (error.response) {
            if (error.response.status == 422) {
                Object.keys(error.response?.data.errors).forEach(key => {
                    box.addError('Error', `${error.response?.data.errors[key][0]}`)
                })
            } else {
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
            Login Account
        </h3>
        <form @submit.prevent="login" class="grid grid-rows-1 gap-2">
            <div>
                <InputTag :disabled="loader" :onKeyup="emailValidation" :element="formData" field="email"
                    v-model="formData.email" type="text" label="E-mail" required />
            </div>
            <div>
                <InputTag :disabled="loader" :onKeyup="passwordValidation" :element="formData" field="password"
                    v-model="formData.password" type="password" label="Password" required />
            </div>
            <div>
                <InputButton :loader="loader" type="submit" text="Login" />
            </div>
        </form>

        <p class="text-sm font-light text-gray-500 dark:text-gray-400 mt-5">
            Don’t have an account yet?

            <router-link :to="{ name: 'register' }"
                class="font-medium text-primary-600 hover:underline dark:text-primary-500">
                Register
            </router-link>
        </p>
    </div>
</template>