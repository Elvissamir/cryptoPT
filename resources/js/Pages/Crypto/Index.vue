<template>
    <layout>
        <div class="w-full">
            <div class="bg-white mx-auto w-11/12 py-2">

              <div class="flex flex-col mx-auto w-11/12">
                <div>
                    <p class="text-lg font-bold">Crypto's Market Ranks</p>
                </div>

                <!-- CRYPTO -->
                <div v-for="(crypto, index) in showCryptos" :key="index" class="flex flex-wrap sm:max-w-3xl sm:flex-row sm:justify-between w-full py-2">
                                    
                                    <!-- CRYPTO MARKET RANK -->
                                    <div>
                                      <p>Rank: </p>
                                      <p>{{ crypto.rank }}</p>
                                    </div>

                                    <!-- CRYPTO SYMBOL -->
                                    <div class="flex ">
                                        <img class="w-9 h-9" :src="crypto.image">
                                        <div class="flex flex-col ml-2">
                                            <p class="text-sm font-semibold">{{ crypto.symbol }}</p>
                                            <Link 
                                              class="underline text-xs font-semibold text-indigo-500" 
                                              :href="getCryptoUrl(crypto.cg_id)">
                                                {{ crypto.name }}
                                            </Link>
                                        </div>
                                    </div>
        

                                    <!-- ADD OR DELETE BUTTON-->
                                    <div class="flex">
                                      <div v-if="crypto.inPortfolio" class="">
                                          <DeleteCryptoBtn :cg_id="crypto.cg_id"></DeleteCryptoBtn>  
                                      </div>

                                      <div v-else>
                                          <AddCryptoBtn :crypto="crypto"></AddCryptoBtn>
                                      </div>
                                    </div>
                                    
                                    <!-- CURRENT PRICE -->
                                    <div class="flex flex-col w-4/12 sm:w-1/12">
                                        <p class="text-xs">Price: </p>
                                        <p class="text-sm font-bold">${{ crypto.current_price }}</p>
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
</template>

<script>

// Layout
import Layout from "../../Layouts/AppLayout";

// VUE BLOCKS
import { onMounted, ref, watch } from "vue";

// COMPONENTS
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'
import AddCryptoBtn from '../../Components/AddCryptoBtn.vue'
import { Link } from '@inertiajs/inertia-vue3'

// HELPERS
import { formatNumber } from '../../Helpers/FormatNumber.js'
import { priceColor } from '../../Helpers/PriceColor.js'

export default {
  components: {
    Layout,
    Link,
    AddCryptoBtn,
    DeleteCryptoBtn,
  },
  props: {
    cryptos: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    
    const showCryptos = ref([]);

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

    // METHODS
    const fetchCryptoList = () => {

        page.value = currentPage.value;
        marketRanksUrl.value = `${baseUrl}/markets?vs_currency=${currency}&order=${order}&per_page=${perPage}&page=${page.value}&sparkline=${sparkline}&price_change_percentage=${price_change}`;

        console.log(marketRanksUrl);

        axios
          .get(marketRanksUrl.value)
          .then((res) => {
            console.log(res.data);

            res.data.forEach(cgCrypto => {

              const crypto = {
                cg_id: cgCrypto.id,
                name: cgCrypto.name,
                image: cgCrypto.image,
                symbol: cgCrypto.symbol.toUpperCase(),
                rank: cgCrypto.market_cap_rank,
                current_price: formatNumber(cgCrypto.current_price),
                price_change_1h: formatNumber(cgCrypto.price_change_percentage_1h_in_currency),
                price_change_24h: formatNumber(cgCrypto.price_change_percentage_24h_in_currency),
                price_change_7d: formatNumber(cgCrypto.price_change_percentage_7d_in_currency),   
              }

              for (let dbCrypto of props.cryptos) {
                if (dbCrypto.cg_id == cgCrypto.id) {
                  crypto['inPortfolio'] = true;
                  crypto['amount'] = formatNumber(dbCrypto.amount)
                }
              }

              showCryptos.value.push(crypto);
          })
        })
			  .catch((e) => console.log(e));
    }

		const getCryptoUrl = (cg_id) => {
        return `/cryptos/${cg_id}`;
    }

    const goToPrev = () => {
      if (currentPage.value > 1)
        currentPage.value--;
    }

    const goToNext = () => {
      currentPage.value++;
    }

		onMounted(() => {
      fetchCryptoList();
		});

    watch(currentPage, () => {
      
      console.log('url: ', marketRanksUrl.value);
      console.log('page: ', page);

      showCryptos.value = [];
      fetchCryptoList();
    })

		return {
     	showCryptos,
      currentPage,
      priceColor,
		  formatNumber,
		  getCryptoUrl,
      goToPrev,
      goToNext,
		};
	}
}
</script>