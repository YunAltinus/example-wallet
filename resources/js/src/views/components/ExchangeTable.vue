<script setup>
import $dayjs from "@/plugins/app-dayjs.js"
import { toastList } from "@/store/widget/toast.js";


const props = defineProps({
    exchangeList: {
        type: Array,
        default: []
    }
})

const financial = (number) => {
    if (number) return `${Number.parseFloat(number).toFixed(2)}`;
}
</script>

<template>
    <table class="theme-table w-full mt-4">
        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                    currency
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                    amount of money
                </th>
                <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                    transaction date
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(exchange, index) in exchangeList" :key="index"
                class="border-b border-gray-200 dark:border-gray-700">
                <th scope="row" class="bg-gray-50 dark:bg-gray-800">
                    {{ exchange.currency }}
                </th>
                <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800 text-center">
                    ≈ {{ financial(exchange.amount) }}
                </td>
                <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800 text-center">
                    {{ $dayjs(exchangeList.created_at) }}
                </td>
            </tr>
        </tbody>
    </table>
</template>