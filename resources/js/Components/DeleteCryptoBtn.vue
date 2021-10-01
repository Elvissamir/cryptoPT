
<template>
    <button @click="removeCrypto()" 
            class="flex p-2 h-6 w-6 justify-center items-center bg-indigo-900 text-white font-bold rounded-full sm:h-8 sm:w-8">
        <p class="flex sm:text-sm md:text-base font-black">
            <font-awesome-icon :icon="['fas','times']" />
        </p>
    </button> 
</template>
<script>

// INERTIA
import { Inertia } from '@inertiajs/inertia'

export default {
    name: 'DeleteCryptoBtn',
    props: {
        cg_id: {
            type: String,
            required: true,
        }
    },
    setup(props) {
        const removeCrypto = () => {
            Inertia.delete(`/portfolio/cryptos/${props.cg_id}`);
        }

        return {
            removeCrypto,
        }
    },
}
</script>