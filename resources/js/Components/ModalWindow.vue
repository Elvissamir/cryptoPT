<template>
    <div v-if="showModal" class="flex justify-center items-center h-screen fixed -top-0 bg-gray-600 bg-opacity-50 w-full">
        <slot></slot>
    </div>    
</template>

<script>

// import AddCryptoForm from './AddCryptoForm.vue'

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