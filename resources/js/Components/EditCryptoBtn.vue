<template>
    <button @click='emitEditCryptoEvent()' class="bg-blue-900 font-bold text-sm rounded-md text-white px-2 ml-1">
        <font-awesome-icon :icon="['fas', 'pencil-alt']"/>
    </button>
</template>

<script>
export default {
    setup(props, context) {

        const emitEditCryptoEvent = () => {
            context.emit('editCrypto');
        }

        return {
            emitEditCryptoEvent,
        }
    },
}
</script>