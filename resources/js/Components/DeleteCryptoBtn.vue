
<template>
    <button @click="removeCrypto()" 
            class="flex p-2 h-6 w-6 justify-center items-center bg-indigo-900 text-white text-sm font-bold rounded-full">
        <p class="flex text-base">
            X
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
            console.log('delete url: ', `/portfolio/cryptos/${props.cg_id}`);
            Inertia.delete(`/portfolio/cryptos/${props.cg_id}`);
        }

        return {
            removeCrypto,
        }
    },
}
</script>