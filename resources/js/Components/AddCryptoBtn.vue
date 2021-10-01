
<template>
    <button @click="addCrypto" class="flex p-2 h-6 w-6 justify-center items-center bg-indigo-900 text-white font-bold rounded-full sm:h-8 sm:w-8">
        <p class="flex sm:text-sm md:text-base font-black">
            <font-awesome-icon :icon="['fas', 'plus']"/>
        </p>
    </button>
</template>

<script>
export default {
    props: {
        crypto: {
            type: Object,
            required: true
        },
    },
    setup(props, context) {
        
        const addCrypto = () => {
            context.emit('openAddForm', props.crypto);
        }

        return {
            addCrypto,
        }
    },
}
</script>