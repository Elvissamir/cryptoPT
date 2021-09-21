
<template>
    <ModalWindow :showModal="showLoadingScreen">
        <div class="text-base text-white font-extrabold flex justify-center items-center w-8/12 mx-auto sm:text-xl sm:w-6/12">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
            <p class="ml-1">{{ status.toUpperCase() }}...</p>
        </div>
    </ModalWindow>
</template>

<script>
import ModalWindow from "./ModalWindow.vue";

export default {
    components: {
        ModalWindow,
    },
    props: {
        status: {
            required: true,
            type: String,
        },
        showLoadingScreen: {
            required: true,
            type: Boolean,
        },
    },
    setup() {
      
        
    },
}
</script>