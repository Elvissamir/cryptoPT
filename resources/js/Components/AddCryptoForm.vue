<template>
    <div class="flex flex-col bg-white h-44 md:h-48 py-4 px-4 rounded-md">
        <div class="flex justify-between">
            <div class="flex w-8/12">
                <img class="w-9 h-9 md:w-11 md:h-11" :src="crypto.image">
                <div class="flex flex-col ml-2">
                    <p class="text-sm font-bold md:text-base">{{ crypto.symbol }}</p>
                    <p class="underline text-xs font-bold text-indigo-500 md:text-base">{{ crypto.name }}</p>
                </div>
            </div>

            <div class="flex">
                <button @click="closeForm" class="flex p-2 h-8 w-8 justify-center items-center rounded-full bg-indigo-900 text-white text-base font-bold md:h-8 md:w-8 md:text-xl">
                    <div class="text-center">X</div>
                </button>
            </div>
        </div>

        <form @submit.prevent="addCrypto" class="flex flex-col items-end h-full">
            <div class="flex items-baseline justify-between mt-4">
                <label class="flex items-end" for="amount">
                    <p class="text-sm md:text-base">Amount</p>
                </label>
                <input class="ml-2 border-2 h-6 w-9/12 md:h-8" type="number" step=".0001" id="amount" v-model="amount">
            </div>
            
            <div class="flex justify-between mt-auto w-full">
                <button type="submit" class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-bold md:text-base">
                    Add
                </button>

                <button @click="closeForm" type="button" class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-bold md:text-base">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { ref } from "vue";

// INERTIA
import { Inertia } from '@inertiajs/inertia'

export default {
    props: {
        crypto: {
            type: Object,
            required: true
        },
    },
    setup(props, context) {
        
        const addCryptoUrl = '/portfolio/cryptos';
        const amount = ref(0);

        const addCrypto = () => {
            
            Inertia.post(addCryptoUrl, {
                cg_id: props.crypto.cg_id,
                amount: amount.value
            });      
        }

        const closeForm = () => {
            context.emit('closeForm');
        }

        return {
            amount,
            addCrypto,
            closeForm,
        }
    },
}
</script>