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
                <button @click="closeEditForm" class="flex p-2 h-8 w-8 justify-center items-center rounded-full bg-indigo-900 text-white text-base font-bold md:h-8 md:w-8 md:text-xl">
                    <div class="text-center">X</div>
                </button>
            </div>
        </div>

        <form @submit.prevent="saveChanges" class="flex flex-col items-end h-full">
            <div class="flex items-baseline justify-between mt-4">
                <label class="flex items-end" for="amount">
                    <p class="text-sm md:text-base">Amount</p>
                </label>
                <input class="ml-2 border-2 h-6 w-9/12 md:h-8 " type="number" step=".0001" id="amount" v-model="newAmount">
            </div>
            
            <div class="flex justify-between mt-auto w-full">
                <button type="submit" class="px-3 py-2 mb-1 bg-blue-900 rounded-md text-sm text-white font-bold md:text-base">
                    Save
                </button>

                <button @click="closeEditForm" type="button" class="px-3 py-2 mb-1 bg-blue-900 rounded-md text-sm text-white font-bold md:text-base">
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
        
        const newAmount = ref(props.crypto.amount);

        const validAmount = (amount) => {
            return true;
        }

        const saveChanges = () => {

            let url = `/portfolio${props.crypto.url}`;

            if (validAmount(newAmount.value)) 
                Inertia.put(url, {amount: newAmount.value});        
        }

        const closeEditForm = () => {
            context.emit('closeEditForm');
        }

        return {
            newAmount,
            saveChanges,
            closeEditForm,
        }
    },
}
</script>