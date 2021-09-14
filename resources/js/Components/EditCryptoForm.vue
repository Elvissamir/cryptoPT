<template>
    <div class="flex flex-col bg-white h-44 py-4 px-4 rounded-md">
        <div class="flex justify-between">
            <div class="flex w-8/12">
                <img class="w-9 h-9" :src="crypto.image">
                <div class="flex flex-col ml-2">
                    <p class="text-sm font-bold">{{ crypto.symbol }}</p>
                    <p class="underline text-xs font-bold text-indigo-500">{{ crypto.name }}</p>
                </div>
            </div>

            <div class="flex">
                <button @click="closeEditForm" class="flex p-2 h-6 w-6 justify-center items-center rounded-full bg-indigo-900 text-white text-sm font-bold">
                    x
                </button>
            </div>
        </div>

        <form @submit.prevent="saveChanges" class="flex flex-col items-end h-full">
            <div class="flex items-baseline justify-between mt-4">
                <label class="flex items-end" for="amount">
                    <p class="text-sm">Amount</p>
                </label>
                <input class="ml-2 border-2 h-6 w-9/12" type="number" step=".0001" id="amount" v-model="newAmount">
            </div>
            
            <div class="flex justify-between mt-auto w-full">
                <button type="submit" class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-bold">
                    Save
                </button>

                <button @click="closeEditForm" type="button" class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-bold">
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
        
        const newAmount = ref(0);

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