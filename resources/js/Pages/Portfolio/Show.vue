
<template>
<div>
    <layout>

    <!-- LOADER SCREEN -->
    <LoadingScreen :showLoadingScreen="showLoading" :status="status"></LoadingScreen>

    <!-- EDIT CRYPTO FORM SCREEN -->
    <ModalWindow :showModal="showEditForm">
        <EditCryptoForm @close-form="disableEditCryptoForm" :crypto="cryptoToEdit"></EditCryptoForm>   
    </ModalWindow>

    <!-- MAIN -->
    <div class="w-full">
        <div v-if="hasData" class="w-full">
            
            <div class="grid grid-cols-1">
                <!-- MY PORTFOLIO DATA & CRYPTOS SECTION -->
                <div class="flex flex-col text-white bg-blue-900 w-full mx-auto">    

                    <!-- PORTFOLIO INFO -->
                    <div class="mx-auto mt-4 w-11/12">

                        <div class="w-full">
                            <!-- PORTFOLIO DETAILS TOP --> 
                            <div class="flex">
                                    <div class="w-6/12 flex flex-wrap">
                                        <h1 class="sm:text-xl font-semibold">My Portfolio</h1>
                                    </div>
                                        
                                    <div class="w-6/12 flex justify-end items-baseline">
                                        <p class="text-sm sm:text-md mr-2">Total: </p>
                                        <p class="sm:text-xl font-bold text-green-300">
                                                ${{ portfolioTotalWorth }}
                                        </p>
                                    </div>
                            </div>

                            <!-- PORTFOLIO DETAILS CONTENT -->
                            <div class="flex mt-2">
                                    <div class="flex flex-col w-6/12">
                                        <div class="flex">
                                            <p class="sm:text-sm text-xs my-auto">Number of cryptos: </p>
                                            <p class="sm:text-sm text-sm font-bold ml-2">{{ cryptoData.length }}</p>
                                        </div>

                                        <div class="flex">
                                            <p class="sm:text-sm text-xs my-auto">Created: </p>
                                            <p class="sm:text-sm text-sm font-bold ml-2">{{ portfolio.created_at }}</p>
                                        </div> 

                                        <div class="flex">
                                            <p class="sm:text-sm text-xs my-auto">Modified: </p>
                                            <p class="sm:text-md text-sm font-bold ml-2">{{ portfolio.updated_at }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end w-6/12">
                                        <div class="flex">
                                            <p class="sm:text-sm text-xs my-auto">Growth % (24h): </p>
                                            <p :class="[priceColor(portfolioGrowthPercentage), 'font-bold']" 
                                                class="sm:text-sm text-sm ml-2">
                                                    {{ portfolioGrowthPercentage }}%
                                            </p>
                                        </div>

                                        <div class="flex">
                                            <p class="sm:text-sm text-xs my-auto">Growth (24h):</p>
                                            <p :class="[priceColor(portfolioGrowth), 'font-bold']" 
                                                class="sm:text-sm text-sm ml-2">
                                                ${{ portfolioGrowth }}
                                            </p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
            
                    <!-- MY CRYPTOS SECTION -->
                    <div class="bg-white rounded-t-3xl w-full mt-4 text-black align-bottom">
                        <div class="w-11/12 mx-auto mt-5">
                            <div class="flex justify-between">
                                <div class="">
                                    <h2 class="sm:text-xl font-semibold">My Cryptos</h2>
                                </div>
                                <div class="mb-1">
                                    <LinkAddCrypto></LinkAddCrypto>
                                </div>
                            </div>
                    
                            <!-- LIST OF CRYPTOS -->
                            <div class="flex flex-col mt-4 divide-y-2 divide-gray-300 border-b-2 border-t-2 border-gray-300">
                                <div v-for="(crypto, index) in cryptoData" :key="index" class="flex flex-wrap sm:max-w-3xl sm:flex-row sm:justify-between w-full py-2">
                                                    <!-- CRYPTO SYMBOL -->
                                                    <div class="flex w-8/12 sm:w-2/12">
                                                        <img class="w-9 h-9" :src="crypto.image">
                                                        <div class="flex flex-col ml-2">
                                                            <p class="text-sm font-semibold">{{ crypto.symbol }}</p>
                                                            <Link class="underline text-xs font-semibold text-indigo-500" :href="crypto.url">{{ crypto.name }}</Link>
                                                        </div>
                                                    </div>
                        
                                                    <!-- DELETE BUTTON-->
                                                    <div class="flex w-4/12 sm:w-1/12 rounded justify-end sm:my-auto sm:order-6">
                                                        <DeleteCryptoBtn :cg_id="crypto.cg_id" ></DeleteCryptoBtn>
                                                    </div>
                                                    
                                                    <!-- ADDED AT -->
                                                    <div class="flex sm:flex-col sm:w-2/12 items-baseline w-full  mb-2 sm:mb-0">
                                                        <p class="text-xs">Added: </p>
                                                        <p class="text-xs font-bold ml-2 sm:text-sm sm:ml-0">{{ crypto.created_at }}</p>
                                                    </div>
                        
                                                    <!-- PRICE -->
                                                    <div class="flex flex-col w-4/12 sm:w-1/12">
                                                        <p class="text-xs">Price: </p>
                                                        <p class="text-sm font-bold">${{ crypto.price }}</p>
                                                    </div>
                        
                                                    <!-- AMOUNT -->
                                                    <div class="flex flex-col w-4/12 sm:w-1/12">
                                                        <p class="text-xs">Amount: </p>

                                                        <div class="flex">
                                                            <div class="flex">
                                                                <p class="text-sm font-bold">{{ crypto.amount }}</p>
                                                                <p class="text-sm font-bold ml-1">{{ crypto.symbol }}</p>
                                                                <button @click='editCryptoAmount(crypto, index)' class="bg-blue-900 font-bold text-sm rounded-md text-white px-2 ml-1">
                                                                    Edit
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                        
                                                    <!-- TOTAL -->
                                                    <div class="flex flex-col w-4/12 sm:w-1/12">
                                                        <p class="text-xs">Total: </p>
                                                        <p class="text-sm font-bold">${{ crypto.total_worth }}</p>
                                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHARTS -->
                <div class="bg-white w-full mx-auto">     
                    <div class="w-10/12 mx-auto">
                        <!-- PORTFOLIO DISTRIBUTION CHART-->
                        <div class="">
                            <div class="">
                                <h2 class="sm:text-xl font-semibold">
                                    Portfolio Distribution
                                </h2>
                            </div>
                            <div class="relative w-32 h-32">
                                <canvas class="" id="doughnutChart"></canvas>
                            </div>
                        </div>

                        <!-- CRYPTOS PERFORMANCE CHART --> 
                        <div class="">
                            <div>
                                <h2 class="sm:text-xl font-semibold">  
                                    Cryptos Performance (% Increase in 7 Days)
                                </h2>
                            </div>
                            <div class="relative w-32 h-32">
                                <canvas class="" id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div> 

            </div>
        </div>

        <div v-else class="flex flex-col justify-center items-center w-11/12 mx-auto h-screen">
            <p class="font-extrabold text-lg">
                There's no data to show.
            </p>

            <p class="font-extrabold text-lg mt-2 text-center">
                Please add some cryptos to your portfolio
            </p>

            <div class="mt-3">
                <LinkAddCrypto></LinkAddCrypto>   
            </div>
        </div>

    </div>
    </layout>
</div>
</template>
<script>

// Layout
import Layout from '../../Layouts/AppLayout'

// Vue
import { ref, onMounted, watch } from 'vue'

// COMPONENTS
import { Link } from '@inertiajs/inertia-vue3'
import LinkAddCrypto from '../../Components/LinkAddCrypto.vue'
import LoadingScreen from '../../Components/LoadingScreen.vue'
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'

// INERTIA
import { Inertia } from '@inertiajs/inertia'

// Helpers
import { generateCryptoDataArray } from '../../Helpers/GenerateCryptoDataArray'
import { 
    calculateTotalWorth, 
    calculateGrowth, 
    calculateGrowthPercentage, 
    calculateCryptoDistribution,
    calculateTopCryptos,
} from '../../Helpers/PortfolioHelperFunctions'
import { priceColor } from '../../Helpers/PriceColor'

// Charts
import {
    Chart,  
    BarController,
    DoughnutController, 
    ArcElement,
    BarElement,
    Tooltip,
    Legend,
    LinearScale,
    CategoryScale,
    } from 'chart.js'

import { generateDoughnutChartConf, updateDoughnutChart } from '../../Charts/DoughnutChart.js' 
import { generateBarChartConf, updateBarChart } from '../../Charts/BarChart.js'

// Register Chart dependencies
Chart.register(DoughnutController, BarController, BarElement, ArcElement, CategoryScale, Legend, LinearScale, Tooltip);

export default {
  components: {
    Layout,
    Link,
    LinkAddCrypto,
    LoadingScreen,
    DeleteCryptoBtn,
  },
  props: {
    portfolio: {
       type: Object,
       required: true
    },
    cryptos: {
       type: Array,
       required: true
    }
  },
  setup(props) {

        // Properties
        let cryptoData = ref([]);

        let doughnutChart = null;
        let barChart = null;

        // Request Parameters
        let main_url = "https://api.coingecko.com/api/v3/coins/";
        let currency = 'usd';
        let order = "market_cap_desc";
        let per_page = "100";
        let page = 1;
        let sparkline = false;
        let price_change_percentage = '7d%2C%2024h';

        // JOIN DATA OPTIONS
        let options = {
            created_at: true,
            total_worth: true, 
            price_change_24h: true,
            price_change_percentage_7d: true,
        };

        // PORTFOLIO WORTH
        const portfolioTotalWorth = ref(0);
        const portfolioGrowth = ref(0);
        const portfolioGrowthPercentage = ref(0);

        // STATUS
        const status = ref('loading');
        const showLoading = ref(true);
        const hasData = ref(false);
        
        // METHODS
        // REQUEST URL
        const generateRequestCgUrl = (cryptoArr) => {
            
            let ids = cryptoArr.map(crypto => {
                return crypto.cg_id
            });

            let allIds = ids.join('%2C%20');

            // Request URL
            return `${main_url}markets?vs_currency=${currency}&ids=${allIds}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change_percentage}`;
        }

        // PORTFOLIO DATA
        const calculatePortfolioData = (cryptosData) => {

            portfolioTotalWorth.value = calculateTotalWorth(cryptosData);
            portfolioGrowth.value = calculateGrowth(cryptosData);
            portfolioGrowthPercentage.value = calculateGrowthPercentage(portfolioTotalWorth.value, portfolioGrowth.value);
        }

        // EDIT METHODS
        const cancelEdit = () => {
            editing.value = false;
        }

        // CYCLE HOOKS
        onMounted(() => {

            if (props.cryptos.length == 0)
            {
                hasData.value = false;
                showLoading.value = false;
                status.value = 'ready';
                cryptoData.value = [];
                calculatePortfolioData(cryptoData.value);
            }
            else {
                
                hasData.value = true;
                status.value = 'fetching';

                // DATA OF ALL CRYPTOS INFORMATION
                axios.get(generateRequestCgUrl(props.cryptos))
                     .then(res => {
    
                        status.value = 'ready';
                        showLoading.value = false;

                        cryptoData.value = generateCryptoDataArray(props.cryptos, res.data, options);
    
                        // CALCULATE PORTFOLIO DATA
                        calculatePortfolioData(cryptoData.value);
        
                        // CALCULATE CHARTS DATA
                        const cryptoDistribution = calculateCryptoDistribution(cryptoData.value, portfolioTotalWorth.value);
                        const topCryptos = calculateTopCryptos(cryptoData.value);                  

                        const doughnutHtmlElement = document.getElementById("doughnutChart");
                        doughnutChart = new Chart(doughnutHtmlElement, generateDoughnutChartConf(cryptoDistribution, cryptoDistribution.cryptos.length));
                                        
                        const barHtmlElement = document.getElementById('barChart');
                        barChart = new Chart(barHtmlElement, generateBarChartConf(topCryptos, topCryptos.cryptos.length));
                })
                .catch(e => console.log(e));
            }
        });

        // WATCHERS
        watch(() => props.cryptos, () => {
              if (props.cryptos.length == 0)
            {
                hasData.value = false;
                showLoading.value = false;
                status.value = 'ready';
                cryptoData.value = [];
                calculatePortfolioData(cryptoData.value);
            }
            else {
                
                hasData.value = true;
                status.value = 'fetching';

                // DATA OF ALL CRYPTOS INFORMATION
                axios.get(generateRequestCgUrl(props.cryptos))
                     .then(res => {
    
                        status.value = 'ready';
                        showLoading.value = false;

                        cryptoData.value = generateCryptoDataArray(props.cryptos, res.data, options);
    
                        // CALCULATE PORTFOLIO DATA
                        calculatePortfolioData(cryptoData.value);
        
                        // CALCULATE CHARTS DATA
                        const cryptoDistribution = calculateCryptoDistribution(cryptoData.value, portfolioTotalWorth.value);
                        const topCryptos = calculateTopCryptos(cryptoData.value);

                        updateDoughnutChart(doughnutChart, cryptoDistribution);
                        updateBarChart(barChart, topCryptos);                    
                })
                .catch(e => console.log(e));
            }
        });

        return { 
            cryptoData, 
            portfolioTotalWorth, 
            portfolioGrowth,
            portfolioGrowthPercentage,
            priceColor,
            editCryptoAmount,
            cancelEdit,
            status, 
            showLoading,
            hasData,
        }
  },
}
</script>