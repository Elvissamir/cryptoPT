
<template>
    <div>
 <Layout>

    <!-- LOADER SCREEN -->
    <LoadingScreen :showLoadingScreen="showLoading" :status="status"></LoadingScreen>

    <!-- ADD CRYPTO FORM SCREEN -->
     <ModalWindow :showModal="showAddForm">
        <AddCryptoForm @close-form="disableAddCryptoForm" :crypto="cryptoToAdd"></AddCryptoForm>   
    </ModalWindow>

    <!-- EDIT CRYPTO AMOUNT FORM SCREEN --> 
    <ModalWindow :showModal="showEditForm">
        <EditCryptoForm @close-edit-form="disableEditCryptoForm" :crypto="cryptoToEdit"></EditCryptoForm>
    </ModalWindow>

    <!-- MAIN CONTENT --> 
    <div class="flex w-full">
        <div class="flex bg-white w-11/12 mx-auto md:w-11/12 lg:w-10/12 2xl:w-8/12">
            <div class="flex flex-col w-full mx-auto">
                
                <!-- CRYPTO CONTENT -->
                <div class="flex flex-wrap w-full mx-auto mt-8">
                    
                    <!-- CRYPTO SYMBOL -->
                    <div class="flex w-8/12 sm:order-1 sm:w-auto">
                        <img class="w-12 h-12 sm:w-14 sm:h-14" :src="coin.image">
                        <div class="flex flex-col ml-2">
                            <p class="text-lg font-black sm:text-xl">{{ coin.symbol }}</p>
                            <Link class="underline text-sm font-black text-indigo-500 sm:text-base" :href="coin.url">{{ coin.name }}</Link>
                        </div>
                    </div>

                    <!-- ADD OR DELETE BUTTON -->
                    <div class="flex justify-end ml-auto w-2/12 sm:order-4 sm:ml-0 sm:w-auto">
                        <DeleteCryptoBtn v-if="coin.inPortfolio" :cg_id="coin.cg_id"></DeleteCryptoBtn> 
                        <AddCryptoBtn v-else @open-add-form="activateAddCryptoForm" :crypto="coin"></AddCryptoBtn> 
                    </div>

                    <!-- RANK -->
                    <div class="flex justify-between items-baseline w-full mt-5 border-gray-300 sm:order-2 sm:ml-1 sm:w-auto sm:mt-1 sm:border-none sm:justify-start">
                        <p class="text-base sm:hidden">Market Cap Rank: </p>
                        <p class="text-lg font-black sm:bg-black sm:text-white sm:px-2 sm:ml-2 sm:text-base sm:rounded-md">{{ coin.rank }}#</p>
                    </div>

                     <!-- PRICE -->
                    <div class="flex justify-between items-baseline w-full border-gray-300 border-t-2 py-2 sm:order-3 sm:mr-5 sm:w-auto sm:ml-auto sm:border-none sm:py-0 sm:justify-start">
                        <p class="text-xl">Price: </p>
                        <p class="text-lg up font-black sm:text-2xl sm:ml-4">${{ coin.price }}</p>    
                    </div> 

                    <!-- AMOUNT OWNED & WORTH --> 
                    <div class="flex flex-wrap w-full border-gray-300 border-t-2 py-2 sm:order-5 sm:border-t-0 sm:py-0 sm:mt-4 sm:justify-between lg:border-b-2 lg:py-2">
                        <div class="flex items-baseline justify-between w-full sm:justify-start sm:w-auto">
                            <p class="text-base">Amount: </p>
                            <div class="ml-2">
                                <div class="flex text-lg font-black sm:text-xl" v-if="coin.inPortfolio">
                                    <p>{{ coin.amount }}</p>
                                    <p>{{ coin.symbol }}</p>
                                    <EditCryptoBtn @edit-crypto="activateEditCryptoForm(coin)"></EditCryptoBtn>
                                </div>
                                
                                <div v-else>
                                    <p>-</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-baseline justify-between w-full sm:justify-start sm:w-auto">
                            <p class="text-base">Total: </p>
                            <div class="flex ml-2">
                                <div v-if="coin.inPortfolio">
                                    <p class="text-lg font-black sm:text-xl">${{ coin.total_worth }}</p>
                                </div>

                                <div v-else>
                                    <p>-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- 24H HIGH & LOW -->
                    <div class="flex flex-wrap justify-between items-baseline w-full border-gray-300 border-t-2 py-2 sm:order-6 sm:w-6/12 sm:mb-auto sm:mt-2 lg:border-t-0 lg:border-r-2 xl:w-2/12">
                        <div class="flex justify-between items-baseline w-full sm:justify-start">
                            <p class="text-base">24h low: </p>
                            <p class="text-lg down font-black ml-2">${{ coin.low_24h }}</p>
                            <p class="down text-2xl ml-1"><font-awesome-icon :icon="['fas', 'angle-down']" /></p>
                        </div>

                        <div class="flex justify-between items-baseline w-full sm:justify-start">
                            <p class="text-base">24h high: </p>
                            <p class="text-lg up font-black ml-2">${{ coin.high_24h }}</p>
                            <p class="up text-2xl ml-1"><font-awesome-icon :icon="['fas', 'angle-up']" /></p>
                        </div>
                    </div>

                    <!-- PRICE CHANGES --> 
                    <div class="flex justify-between items-baseline w-full border-gray-300 border-t-2 py-2 sm:order-7 sm:flex-col sm:mt-2 sm:w-6/12 sm:ml-auto sm:justify-end lg:flex-row lg:border-t-0 lg:justify-end xl:w-5/12 xl:justify-around xl:border-r-2">
                        <div class="flex flex-col items-baseline sm:flex-row sm:justify-end lg:flex-col lg:mx-auto xl:mx-0 xl:items-start">
                            <p class="text-base">Change 1h:</p>
                            <p :class="[(Math.sign(coin.price_change_percentage_1h) >= 0)? 'up':'down']" class="font-black text-lg sm:ml-2 lg:ml-0">
                                {{ coin.price_change_percentage_1h }}%
                            </p>
                        </div>

                        <div class="flex flex-col items-baseline sm:flex-row sm:justify-end lg:flex-col lg:mr-auto xl:mx-0">
                            <p class="text-base">Change 24h:</p>
                            <p :class="[(Math.sign(coin.price_change_percentage_24h) >= 0)? 'up':'down']" class="font-black text-lg sm:ml-2 lg:ml-0">
                                {{ coin.price_change_percentage_24h }}%
                            </p>
                        </div>

                        <div class="flex flex-col items-baseline sm:flex-row sm:justify-end lg:flex-col">
                            <p class="text-base">Change 7d:</p>
                            <p :class="[(Math.sign(coin.price_change_percentage_7d) >= 0)? 'up':'down']" class="font-black text-lg sm:ml-2 lg:ml-0">
                                {{ coin.price_change_percentage_7d }}%
                            </p>
                        </div>
                    </div>

                    <!-- ALL TIME HIGH AND LOW -->
                    <div class="flex flex-wrap w-full border-gray-300 border-t-2 py-2 sm:order-8 sm:mt-2 xl:border-t-0 xl:w-5/12 xl:justify-end">
                        <div class="flex flex-col w-full sm:w-6/12 sm:justify-between xl:w-auto xl:mx-auto">
                            <div class="flex justify-between items-baseline sm:justify-start">
                                <p class="text-base">Atl: </p>
                                <p class="ml-1 text-lg font-black sm:ml-2">${{ coin.atl }}</p>
                            </div>

                            <div class="flex justify-between items-baseline sm:justify-start">
                                <p class="text-base">Atl Date: </p>
                                <p class="ml-1 text-lg font-black sm:ml-2">{{ coin.atl_date }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col w-full order-last sm:w-6/12 xl:w-auto">
                            <div class="flex justify-between items-baseline sm:justify-end">
                                <p class="text-base">Ath: </p>
                                <p class="ml-1 text-lg font-black sm:ml-2">${{ coin.ath }}</p>
                            </div>

                            <div class="flex justify-between items-baseline sm:justify-end">
                                <p class="text-base">Ath Date: </p>
                                <p class="ml-1 text-lg font-black sm:ml-2">{{ coin.ath_date }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- CIRCULATING SUPPLY -->
                    <div class="flex flex-wrap items-baseline justify-between border-gray-300 border-t-2 w-full py-2 sm:order-9 sm:mt-2">
                        <div class="flex items-baseline justify-between w-full sm:justify-start sm:w-6/12">
                            <p class="text-base">Circulating Supply: </p>
                            <p class="ml-1 text-lg font-black sm:ml-2">{{ coin.circulating_supply }}</p>
                        </div>

                        <div class="flex items-baseline justify-between w-full sm:justify-end sm:w-6/12">
                            <p class="text-base">Max Supply: </p>
                            <p class="ml-1 text-lg font-black sm:ml-2">{{ coin.max_supply }}</p>
                        </div>
                    </div>
                </div>

                <!-- 7d DAYS GRAPH -->
                <div class="flex flex-col mt-6">
                    <div>
                        <h2 class="sm:text-lg font-black lg:text-2xl">
                            7 Days Price Chart
                        </h2>
                    </div>
                    <canvas id="lineChart" class="mt-3"></canvas>
                </div>

            </div>
        </div>
    </div>

    </Layout>
    </div>
</template>

<script>

// Layout
import Layout from "../../Layouts/AppLayout";

import { ref, onMounted, watch } from 'vue'

// Services
import { getCryptoById, getCryptoChartDataById } from '../../Services/cryptoApiService'

// COMPONENTS
import { Link } from '@inertiajs/inertia-vue3'
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'
import AddCryptoBtn from '../../Components/AddCryptoBtn.vue'
import AddCryptoForm from '../../Components/AddCryptoForm.vue'
import EditCryptoForm from '../../Components/EditCryptoForm.vue'
import EditCryptoBtn from '../../Components/EditCryptoBtn.vue'
import ModalWindow from '../../Components/ModalWindow'
import LoadingScreen from '../../Components/LoadingScreen'

// COMPOSABLES
import useAddCryptoForm from '../../Composables/useAddCryptoForm'
import useEditCryptoForm from '../../Composables/useEditCryptoForm'
import { useToast } from 'vue-toastification';

// CHART
import { createLineChart } from '../../Charts/LineChart';
import { joinCryptoData } from '../../Helpers/JoinCryptoData';

export default {
    components: {
        Layout,
        Link,
        AddCryptoForm,
        AddCryptoBtn,
        EditCryptoForm,
        EditCryptoBtn,
        DeleteCryptoBtn,
        ModalWindow,
        LoadingScreen,
    },
    props: {
        crypto: {
            type: Object,
            required: true,
        }
    },
    setup(props) {

        let lineChart = null
        const toast = useToast()
        const cryptoCGid = props.crypto.cg_id

        let coin = ref({
            url: ''
        });

        const options = {
            price_change_percentage_1h: true, 
            price_change_percentage_24h: true,
            price_change_percentage_7d: true,
            ath: true,
            atl: true,
            rank: true,
            atl_date: true,
            ath_date: true,
            circulating_supply: true,
            max_supply: true,
            high_24h: true,
            low_24h: true,
            total_worth: true,
        };

        const chartData = ref([])
        const status = ref('loading');
        const showLoading = ref(true);

        const { showAddForm, cryptoToAdd, disableAddCryptoForm, activateAddCryptoForm } = useAddCryptoForm();
        const { showEditForm, cryptoToEdit, disableEditCryptoForm, activateEditCryptoForm } = useEditCryptoForm();

        const setCoin = (data) => {
            let cryptoObj = {};
            cryptoObj[cryptoCGid] = props.crypto;
            coin.value = joinCryptoData(cryptoObj, data[0], options);  
        }
          
        const fetchCryptoData = async (afterFetch) => {
            try {
                status.value = 'fetching';
                showLoading.value = true;
                
                const { data } = await getCryptoById(cryptoCGid)
                status.value = 'ready'
                showLoading.value = false;
                
                afterFetch(data)
            }
            catch (ex) {
                status.value = 'ready'
                showLoading.value = false

                if (ex.response && ex.response.status >= 400 && ex.response.status < 500)
                    toast.error(`${ex.response.status} ${ex.response.data}`)
            }
        }

        const generateLineChart = (data) => {
            chartData.value = data.prices.map(price => price[1]);

            const htmLineElement = document.getElementById('lineChart');
            lineChart = createLineChart(htmLineElement, chartData.value)
        }

        const fetchCryptoChartData = async (afterFetch) => {
            try {
                const { data } = await getCryptoChartDataById(cryptoCGid)
                afterFetch(data)
            }
            catch (ex) {
                status.value = 'ready'
                showLoading.value = false

                if (ex.response && ex.response.status >= 400 && ex.response.status < 500)
                    toast.error(`${ex.response.status} ${ex.response.data}`)
            }
        }

        onMounted(() => {
             document.title = `CPT - ${cryptoCGid}`

            fetchCryptoData(setCoin);
            fetchCryptoChartData(generateLineChart)
        })

        watch(() => props.crypto, () => {
            
            disableAddCryptoForm();
            disableEditCryptoForm();

            fetchCryptoData(setCoin);
        });

        return {
            coin,
            showAddForm,
            cryptoToAdd,
            showEditForm,
            cryptoToEdit,
            activateEditCryptoForm,
            activateAddCryptoForm,
            disableAddCryptoForm,
            disableEditCryptoForm,
            status,
            showLoading,
        }
    },
}
</script>