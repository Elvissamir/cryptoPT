
<template>
    <button @click="addCrypto" class="flex p-2 h-6 w-6 justify-center items-center bg-indigo-900 text-white text-sm font-bold rounded-full">
        <p class="flex text-lg">
            +
        </p>
    </button>
</template>

<script>
export default {
    props: {
        crypto: {
            type: Object,
            required: true
        }
    },
    setup(props) {
        
        const addCryptoUrl = '/portfolio/cryptos';

        const addCrypto = () => {
            axios.post(addCryptoUrl, props.crypto);      
        }

        return {
            addCrypto,
        }
    },
}
</script>