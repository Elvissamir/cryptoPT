<template>
    <div v-if="showModal" class="flex justify-center items-center h-screen fixed -top-0 bg-gray-900 bg-opacity-100 w-full">
        <slot></slot>
    </div>    
</template>

<script>
export default {
    components: {
       // AddCryptoForm,
    },
    props: {
        showModal: {
            type: Boolean,
            required: true,
        },
    },
    setup() {
        
    },
}
</script>