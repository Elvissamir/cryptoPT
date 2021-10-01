import { ref } from "vue";

export default function () {
  
  const showEditForm = ref(false);
  const cryptoToEdit = ref({});
  
  function disableEditCryptoForm () {
    showEditForm.value = false;
  }

  function enableEditCryptoForm () {
    showEditForm.value = true;
  }

  function setCryptoToEdit (crypto) {
    cryptoToEdit.value = crypto;
  }

  function activateEditCryptoForm (crypto) {
    enableEditCryptoForm();
    setCryptoToEdit(crypto);
  }
  
  return {
    showEditForm,
    cryptoToEdit,
    enableEditCryptoForm,
    setCryptoToEdit,
    disableEditCryptoForm,
    activateEditCryptoForm,
  }
}