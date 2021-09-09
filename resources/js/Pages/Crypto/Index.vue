<template>
    <div>
      <layout>

        <ModalWindow :showModal="showAddForm">
          <AddCryptoForm @close-form="disableAddCryptoForm" :crypto="cryptoToAdd"></AddCryptoForm>   
        </ModalWindow>

        <div class="w-full">
            <div class="bg-white mx-auto w-11/12 py-2">

              <div class="flex flex-col mx-auto w-11/12">
                <div>
                    <p class="text-lg font-bold">Crypto's Market Ranks</p>
                </div>

                <!-- CRYPTO -->
                <div v-for="(crypto, index) in cryptoData" :key="index" class="flex flex-wrap sm:max-w-3xl sm:flex-row sm:justify-between w-full py-2">
                                    
                                    <!-- CRYPTO MARKET RANK -->
                                    <div>
                                      <p>{{ crypto.rank }}#</p>
                                    </div>

                                    <!-- CRYPTO SYMBOL -->
                                    <div class="flex ">
                                        <img class="w-9 h-9" :src="crypto.image">
                                        <div class="flex flex-col ml-2">
                                            <p class="text-sm font-semibold">{{ crypto.symbol }}</p>
                                            <Link 
                                              class="underline text-xs font-semibold text-indigo-500" 
                                              :href="crypto.url">
                                                {{ crypto.name }}
                                            </Link>
                                        </div>
                                    </div>
        

                                    <!-- ADD OR DELETE BUTTON-->
                                    <div class="flex">
                                        <DeleteCryptoBtn v-if="crypto.inPortfolio" :cg_id="crypto.cg_id"></DeleteCryptoBtn> 
                                        <AddCryptoBtn v-else @open-add-form="activeAddCryptoForm" :crypto="crypto"></AddCryptoBtn> 
                                    </div>
                                    
                                    <!-- CURRENT PRICE -->
                                    <div class="flex flex-col w-4/12 sm:w-1/12">
                                        <p class="text-xs">Price: </p>
                                        <p class="text-sm font-bold">${{ crypto.price }}</p>
                                    </div>

                                    <!-- AMOUNT -->
                                    <div class="flex flex-col w-4/12 sm:w-1/12">
                                        <p class="text-xs">Amount: </p>
                                        
                                        <div v-if="crypto.inPortfolio" class="flex">
                                            <p class="text-sm font-bold">{{ crypto.amount }}</p>
                                            <p class="text-sm font-bold ml-1">{{ crypto.symbol }}</p>
                                        </div>

                                        <div v-else> 
                                            <p>-</p>
                                        </div>

                                    </div>

                                    <!-- 1h Change -->
                                    <div class="flex sm:flex-col sm:w-2/12 items-baseline w-full  mb-2 sm:mb-0">
                                      <p>Change 1h: </p>
                                      <p 
                                        :class="[priceColor(crypto.price_change_1h), 'font-bold']">
                                          {{ crypto.price_change_1h }}%
                                      </p>
                                    </div>   

                                    <!-- 24h Change -->
                                    <div class="flex sm:flex-col sm:w-2/12 items-baseline w-full  mb-2 sm:mb-0">
                                      <p>
                                        Change 24h: 
                                      </p>
                                      <p :class="[priceColor(crypto.price_change_24h), 'font-bold']">
                                        {{ crypto.price_change_24h }}%
                                      </p>
                                    </div>

                                    <!-- 7d Change -->
                                    <div class="flex sm:flex-col sm:w-2/12 items-baseline w-full  mb-2 sm:mb-0">
                                      <p>Change 7d: </p>
                                      <p :class="[priceColor(crypto.price_change_7d), 'font-bold']">
                                        {{ crypto.price_change_7d }}%
                                      </p>
                                    </div>
        
                </div>

                <!-- PAGINATION -->
                <div class="flex justify-between w-52 mx-auto">
                    <button @click="goToPrev" class="bg-blue-900 text-white font-bold py-1 px-2">Previous</button>
                    <div class="flex">
                      <p class="border-2 border-gray-200 font-bold py-1 px-2">{{ currentPage }}</p>
                    </div>
                    <button @click="goToNext" class="bg-blue-900 text-white font-bold py-1 px-2">Next</button>
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

// COMPONENTS
import ModalWindow from '../../Components/ModalWindow.vue'
import AddCryptoForm from '../../Components/AddCryptoForm.vue'
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'
import AddCryptoBtn from '../../Components/AddCryptoBtn.vue'
import { Link } from '@inertiajs/inertia-vue3'

// HELPERS
import { formatNumber } from '../../Helpers/FormatNumber.js'
import { priceColor } from '../../Helpers/PriceColor.js'
import { generateCryptoDataArray } from '../../Helpers/GenerateCryptoDataArray';

export default {
  components: {
    Layout,
    Link,
    AddCryptoBtn,
    DeleteCryptoBtn,
    ModalWindow,
    AddCryptoForm,
  },
  props: {
    cryptos: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    
    let cryptoData = ref([]);

		// REQUEST URL
		const baseUrl = "https://api.coingecko.com/api/v3/coins";
		const currency = 'usd';
		const order = 'market_cap_desc';
		const perPage = 10;
		const page = ref(1);
		const sparkline = false;
		const price_change = "1h%2C24h%2C7d";
		const marketRanksUrl = ref('');

    // PAGINATION
    const currentPage = ref(1);

    // ADD CRYPTO FORM STATE AND DATA
    const showAddForm = ref(false);
    const cryptoToAdd = ref({});

     // JOIN DATA OPTIONS
    let options = {
      rank: true,
      price_change_1h: true, 
      price_change_24h: true,
      price_change_percentage_7d: true,
    };

    // METHODS
    const fetchCGData = () => {
      page.value = currentPage.value;
      marketRanksUrl.value = `${baseUrl}/markets?vs_currency=${currency}&order=${order}&per_page=${perPage}&page=${page.value}&sparkline=${sparkline}&price_change_percentage=${price_change}`;

      axios
        .get(marketRanksUrl.value)
        .then((res) => {
          cryptoData.value = generateCryptoDataArray(props.cryptos, res.data, options);
        })
			  .catch((e) => console.log(e));
    }

    const activeAddCryptoForm = (crypto) => {
      cryptoToAdd.value = crypto;
      showAddForm.value = true;
    }

    const disableAddCryptoForm = () => {
      showAddForm.value = false;
    }

    const goToPrev = () => {
      if (currentPage.value > 1)
        currentPage.value--;
    }

    const goToNext = () => {
      currentPage.value++;
    }

		onMounted(() => {
      fetchCGData();
		});

    watch(() => currentPage, () => {
      fetchCGData();
    })

    watch(() => props.cryptos, () => {
      fetchCGData();
    })

		return {
      cryptoData, 
      currentPage,
      priceColor,
		  formatNumber,
      showAddForm,
      cryptoToAdd,
      activeAddCryptoForm,
      disableAddCryptoForm,
      goToPrev,
      goToNext,
		};
	}
}
</script>