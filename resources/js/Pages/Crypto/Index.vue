<template>
    <div>
      <layout>

        <!-- LOADER SCREEN -->
        <LoadingScreen :showLoadingScreen="showLoading" :status="status"></LoadingScreen>

        <!-- ADD CRYPTO FORM SCREEN -->
        <ModalWindow :showModal="showAddForm">
          <AddCryptoForm @close-form="disableAddCryptoForm" :crypto="cryptoToAdd"></AddCryptoForm>   
        </ModalWindow>

        <!-- MAIN CONTENT --> 
        <div class="w-full">
            <div class="bg-white mx-auto w-11/12 md:w-10/12 2xl:w-8/12">

              <div class="flex flex-col mx-auto w-full mt-8">
                <div>
                    <p class="text-xl font-bold lg:text-2xl">Crypto's Market Ranks</p>
                </div>

                <!-- CRYPTO -->
                <div class="flex flex-col mt-4 divide-y-2 divide-gray-300 w-full">
                  <div v-for="(crypto, index) in cryptoData" :key="index" class="flex flex-wrap sm:flex-row sm:items-center sm:justify-between w-full py-2">
                      
                      <!-- CRYPTO MARKET RANK -->
                      <div class="flex order-1 h-5 px-1 bg-black text-white sm:order-1 sm:w-auto sm:h-6 sm:items-center">
                        <p class="text-sm font-black lg:text-base">{{ crypto.rank }}#</p>
                      </div>

                      <!-- CRYPTO SYMBOL -->
                      <div class="flex order-2 ml-2 sm:order-2 sm:w-2/12 sm:ml-0">
                        <img class="w-10 h-10 xl:w-12 xl:h-12" :src="crypto.image">
                        <div class="flex flex-col ml-2">
                            <p class="text-base font-black md:text-base lg:text-xl">{{ crypto.symbol }}</p>
                            <Link 
                              class="underline text-xs font-semibold text-indigo-500 md:text-sm lg:text-lg" 
                              :href="crypto.url">
                                {{ crypto.name }}
                            </Link>
                        </div>
                      </div>
        
                      <!-- ADD OR DELETE BUTTON-->
                      <div class="flex order-3 ml-auto sm:order-5 sm:w-auto sm:justify-end sm:ml-0">
                        <DeleteCryptoBtn v-if="crypto.inPortfolio" :cg_id="crypto.cg_id"></DeleteCryptoBtn> 
                        <AddCryptoBtn v-else @open-add-form="activateAddCryptoForm" :crypto="crypto"></AddCryptoBtn> 
                      </div>

                      <!-- PRICE & AMOUNT --> 
                      <div class="flex order-4 w-full mt-2 sm:order-3 sm:w-3/12">
                        
                        <!-- CURRENT PRICE -->
                        <div class="flex w-1/2 sm:flex-col">
                          <p class="flex items-center text-xs md:text-sm lg:text-lg">Price: </p>
                          <p class="text-sm font-bold ml-2 sm:ml-0 md:text-base lg:text-xl">${{ crypto.price }}</p>
                        </div>

                        <!-- AMOUNT -->
                        <div class="flex w-1/2 sm:flex-col">
                            <p class="flex items-center text-xs md:text-sm lg:text-lg">Amount: </p>                  
                            <div v-if="crypto.inPortfolio" class="flex items-center ml-2 sm:ml-0">
                              <p class="flex items-center text-sm font-bold md:text-base lg:text-xl">{{ crypto.amount }}</p>
                              <p class="flex items-center text-sm font-bold ml-1 md:text-base lg:text-xl">{{ crypto.symbol }}</p>
                            </div>

                            <div v-else class="flex sm:flex-col"> 
                              <p class="flex text-base ml-2 sm:ml-0 lg:text-xl">-</p>
                          </div>
                        </div>
                      </div>
                                      
                      <!-- PRICE CHANGES -->
                      <div class="flex order-5 w-full mt-2 sm:order-4 sm:w-5/12">
                          <!-- 1h Change -->
                          <div class="flex flex-col w-4/12 items-baseline mb-2 sm:mb-0">
                              <p class="text-xs md:text-sm lg:text-lg">Change 1h: </p>
                              <p 
                                :class="[(Math.sign(crypto.price_change_percentage_1h) >= 0)? 'up':'down']" class="font-black text-sm md:text-base lg:text-xl">
                                  {{ crypto.price_change_percentage_1h }}%
                              </p>
                          </div>   

                          <!-- 24h Change -->
                          <div class="flex flex-col w-4/12 items-baseline mb-2 sm:mb-0">
                              <p class="text-xs md:text-sm lg:text-lg">Change 24h: </p>
                              <p :class="[(Math.sign(crypto.price_change_percentage_24h) >= 0)? 'up':'down']" class="font-black text-sm md:text-base lg:text-xl">
                                {{ crypto.price_change_percentage_24h }}%
                              </p>
                          </div>

                          <!-- 7d Change -->
                          <div class="flex flex-col w-4/12 items-baseline mb-2 sm:mb-0">
                              <p class="text-xs md:text-sm lg:text-lg">Change 7d: </p>
                              <p :class="[(Math.sign(crypto.price_change_percentage_7d) >= 0)? 'up':'down']" class="font-black text-sm md:text-base lg:text-xl">
                                  {{ crypto.price_change_percentage_7d }}%
                            </p>
                          </div>
                      </div>
                  </div>
                </div>

                <!-- PAGINATION -->
                <div class="flex justify-between w-52 mx-auto mt-10">
                    <button @click="goToPrev" class="bg-blue-900 text-white font-bold py-1 px-2 rounded-md lg:text-lg">Prev</button>
                    <div class="flex">
                      <p class="border-2 border-gray-200 font-bold py-1 px-2">{{ currentPage }}</p>
                    </div>
                    <button @click="goToNext" class="bg-blue-900 text-white font-bold py-1 px-2 rounded-md lg:text-lg">Next</button>
                </div>
              </div>

            </div>
        </div>
    </layout>  
    </div>
</template>

<script>

// Layout
import Layout from "../../Layouts/AppLayout";

// VUE BLOCKS
import { onMounted, ref, watch } from "vue";

// COMPOSABLES
import useAddCryptoForm from '../../Composables/useAddCryptoForm'

// COMPONENTS
import { Link } from '@inertiajs/inertia-vue3'
import LoadingScreen from '../../Components/LoadingScreen'
import ModalWindow from '../../Components/ModalWindow.vue'
import AddCryptoForm from '../../Components/AddCryptoForm.vue'
import AddCryptoBtn from '../../Components/AddCryptoBtn.vue'
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'

// HELPERS
import { generateCryptoDataArray } from '../../Helpers/GenerateCryptoDataArray';
import { getCryptoRankList } from '../../Services/cryptoApiService';
import { useToast } from 'vue-toastification';

export default {
  components: {
    Layout,
    LoadingScreen,
    Link,
    AddCryptoBtn,
    AddCryptoForm,
    DeleteCryptoBtn,
    ModalWindow,
  },
  props: {
    cryptos: {
      type: Array,
      required: true,
    },
  },
  setup(props) {

    const toast = useToast()
    
    const cryptoData = ref([]);

    // PAGINATION
    const currentPage = ref(1);

    // ADD CRYPTO FORM STATE AND DATA
    const { showAddForm, cryptoToAdd, disableAddCryptoForm, activateAddCryptoForm } = useAddCryptoForm();

    const status = ref('loading');
    const showLoading = ref(true);

     // JOIN DATA OPTIONS
    let options = {
      rank: true,
      price_change_percentage_1h: true, 
      price_change_percentage_24h: true,
      price_change_percentage_7d: true,
    };

    // METHODS
    const fetchCGData = async () => {
      showLoading.value = true;
      status.value = 'fetching';

      try {
        const { data } = await getCryptoRankList(currentPage.value)
        cryptoData.value = generateCryptoDataArray(props.cryptos, data, options);
        
        status.value = "ready"
        showLoading.value = false;
      } 
      catch (ex) {
        status.value = 'ready'
        showLoading.value = false

        if (ex.response && ex.response.status >= 400 && ex.response.status < 500)
            toast.error(`${ex.response.status} ${ex.response.data}`)
      }
    }

    const goToPrev = () => {
      if (currentPage.value > 1)
        currentPage.value--;
    }

    const goToNext = () => {
      currentPage.value++;
    }

		onMounted(() => {
      document.title = 'CPT - Cryptos'
      fetchCGData();
		});

    watch(() => props.cryptos, () => {
      disableAddCryptoForm();
      fetchCGData();
    })

    watch(currentPage, () => {
      fetchCGData();
    })

		return {
      cryptoData, 
      currentPage,
      showAddForm,
      cryptoToAdd,
      activateAddCryptoForm,
      disableAddCryptoForm,
      goToPrev,
      goToNext,
      showLoading,
      status
		};
	}
}
</script>