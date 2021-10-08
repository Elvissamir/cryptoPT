
import { ref } from 'vue'

export default function () {

    const showAddForm = ref(false);
    const cryptoToAdd = ref({});

    function activateAddCryptoForm (crypto) {
        cryptoToAdd.value = crypto;
        showAddForm.value = true;
    }

    function disableAddCryptoForm () {
        showAddForm.value = false;
    }

    return {
        showAddForm,
        cryptoToAdd,
        activateAddCryptoForm,
        disableAddCryptoForm,
    }
}