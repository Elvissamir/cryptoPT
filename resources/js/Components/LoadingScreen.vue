
<template>
    <ModalWindow :showModal="showLoadingScreen">
        <div class="text-xl text-white font-extrabold flex justify-center items-center w-6/12 mx-auto">
            <p>{{ status.toUpperCase() }} ...</p>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
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