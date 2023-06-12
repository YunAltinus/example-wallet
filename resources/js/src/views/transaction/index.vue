<script setup>
import axios from '@/plugins/app-axios.js'
import { onMounted, ref } from 'vue';
import $dayjs from "@/plugins/app-dayjs.js"

const loader = ref(false)

const transactionList = ref([])

const transactionType = ref("")
const transferType = ref("")

const financial = (number) => {
    if (number) return `${Number.parseFloat(number).toFixed(2)}`;
}


const changeSelectBox = async () => {
    getAllTransaction()
}

const getAllTransaction = async () => {
    const query = {
        transactionType: transactionType.value,
        transferType: transferType.value
    }

    loader.value = true

    try {
        const { data } = await axios.get("/api/fetchWalletHistory", { params: query })
        transactionList.value = data
        // popupClose()
    } catch (error) {
        console.log(error)
    }
    finally {
        loader.value = false
    }
}

onMounted(() => {
    getAllTransaction()
})
</script>

<template>
    <div class="w-full">
        <div class="flex justify-between">

            <h3>Wallet transactions</h3>
            <div class="flex gap-2">
                <div>
                    <label for="transfer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Select an transfer option
                    </label>
                    <select @change="changeSelectBox" v-model="transferType" id="transfer"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="">All</option>
                        <option value="1">Yes</option>
                        <option value="2">No</option>
                    </select>
                </div>
                <div>
                    <label for="transaction" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Select an transaction option
                    </label>
                    <select @change="changeSelectBox" v-model="transactionType" id="transaction"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected value="">All</option>
                        <option value="1">inflow</option>
                        <option value="2">out</option>
                    </select>
                </div>
            </div>
        </div>

        <template v-if="loader">
            <SkeletonTable />
        </template>

        <template v-else>
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
                            money transfer
                        </th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                            money inflow/out
                        </th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 dark:bg-gray-800">
                            transaction date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(transaction, index) in transactionList" :key="index"
                        class="border-b border-gray-200 dark:border-gray-700">
                        <th scope="row" class="bg-gray-50 dark:bg-gray-800">
                            {{ transaction.currency }}
                        </th>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800 text-center">
                            {{ financial(transaction.amount) }}
                        </td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
                            <template v-if="transaction.transfer == '1'">
                                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 text-center"
                                    role="alert">
                                    <span class="font-medium">Transfer</span>
                                </div>
                            </template>
                            <template v-else>
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 text-center"
                                    role="alert">
                                    <span class="font-medium">Not Transfer</span>
                                </div>
                            </template>
                        </td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
                            <template v-if="transaction.transaction == '1'">
                                <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 text-center"
                                    role="alert">
                                    <span class="font-medium">Inflow</span>
                                </div>
                            </template>
                            <template v-else>
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 text-center"
                                    role="alert">
                                    <span class="font-medium">Out</span>
                                </div>
                            </template>
                        </td>
                        <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800 text-center">
                            {{ $dayjs(transaction.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </template>
    </div>
</template>