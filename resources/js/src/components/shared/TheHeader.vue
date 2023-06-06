<script setup>
import axios from "@/plugins/app-axios.js"
import { navigationMenu } from '@/lib/enums.js'
import box from '@/store/box.js'
import { ref } from "vue";


import { useRouter } from "vue-router";

const router = useRouter();

const loader = ref(false)

const logout = async () => {
    loader.value = true
    try {
        await axios.post("api/logout")

        $cookies.remove("accessToken")
        box.addSuccess('Success', `Logout is successfully`)
        router.push({ name: "login" })
    } catch (error) {
        box.addError('Error', `${error.response?.data.message}`)

    } finally {
        loader.value = false
    }
}


</script>

<template>
    <div class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        APP
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <router-link class="bg-gray-700 text-white rounded-md px-3 py-2 text-sm font-medium"
                                v-for="item in navigationMenu" :key="item.text" :to="item.path">{{ item.text
                                }}</router-link>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- logout -->
                        <Menu as="div" class="relative ml-3">
                            <div class="ml-10 flex items-baseline space-x-4 mx-auto">
                                <InputButton @click="logout" :loader="loader" type="button" text="Logout" />
                            </div>
                        </Menu>
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>

<style></style>