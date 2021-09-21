
<template>
<div>
    <layout>

    <!-- LOADER SCREEN -->
    <LoadingScreen :showLoadingScreen="showLoading" :status="status"></LoadingScreen>

    <!-- EDIT CRYPTO FORM SCREEN -->
    <ModalWindow :showModal="showEditForm">
        <EditCryptoForm @close-edit-form="disableEditCryptoForm" :crypto="cryptoToEdit"></EditCryptoForm>
    </ModalWindow>

    <!-- MAIN -->
    <div class="w-full">
        <div v-if="hasData" class="w-full">

            <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-2 lg:bg-gray-300">
                <!-- MY PORTFOLIO DATA & CRYPTOS SECTION -->
                <div class="flex flex-col text-white bg-blue-900 w-full mx-auto">

                    <!-- PORTFOLIO INFO -->
                    <div class="mx-auto mt-4 w-11/12 lg:w-10/12">
                        <div class="w-full">
                            <!-- PORTFOLIO DETAILS TOP -->
                            <div class="flex">
                                    <div class="w-6/12 flex flex-wrap">
                                        <h1 class="sm:text-xl font-black">My Portfolio</h1>
                                    </div>

                                    <div class="w-6/12 flex justify-end items-baseline">
                                        <p class="text-sm sm:text-md mr-2">Total: </p>
                                        <p class="sm:text-xl font-black text-green-300">
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
                    <div class="bg-white rounded-t-3xl w-full mt-4 text-black align-bottom lg:h-full">
                        <div class="w-11/12 mx-auto mt-5 lg:w-10/12">
                            <div class="flex justify-between">
                                <div class="">
                                    <h2 class="sm:text-xl font-black">My Cryptos</h2>
                                </div>
                                <div class="mb-1">
                                    <LinkAddCrypto></LinkAddCrypto>
                                </div>
                            </div>

                            <!-- LIST OF CRYPTOS -->
                            <div class="flex flex-col mt-4 divide-y-2 divide-gray-300 border-b-2 border-t-2 border-gray-300 lg:mb-12">
                                <div v-for="(crypto, index) in cryptoData" :key="index" class="flex flex-wrap py-2 sm:justify-between lg:justify-start xl:justify-between">
                                    <!-- CRYPTO SYMBOL -->
                                    <div class="flex sm:w-3/12 lg:order-1">
                                        <img class="w-9 h-9" :src="crypto.image">
                                        <div class="flex flex-col ml-2">
                                            <p class="text-sm font-black">{{ crypto.symbol }}</p>
                                            <Link class="underline text-xs font-semibold text-indigo-500" :href="crypto.url">{{ crypto.name }}</Link>
                                        </div>
                                    </div>

                                    <!-- DELETE BUTTON-->
                                    <div class="flex rounded justify-end ml-auto sm:order-6 sm:ml-0 sm:w-1/12 sm:items-center lg:order-2 lg:ml-auto xl:ml-0 xl:order-4">
                                        <DeleteCryptoBtn :cg_id="crypto.cg_id" ></DeleteCryptoBtn>
                                    </div>

                                    <!-- ADDED AT -->
                                    <div class="flex items-baseline w-full mt-1 mb-2 sm:my-0 sm:flex-col sm:w-2/12 lg:w-full lg:mt-1 lg:mb-2 lg:flex-row lg:order-3 xl:flex-col xl:w-2/12 xl:mt-0 xl:mb-0 xl:order-2">
                                        <p class="text-xs">Added: </p>
                                        <p class="text-sm font-bold ml-2 sm:ml-0 lg:ml-2 xl:ml-0">{{ crypto.created_at }}</p>
                                    </div>

                                    <div class="flex w-full sm:w-5/12 lg:w-full lg:order-4 xl:order-3 xl:w-5/12">
                                        <!-- PRICE -->
                                        <div class="flex flex-col">
                                            <p class="text-xs">Price: </p>
                                            <p class="text-sm font-bold">${{ crypto.price }}</p>
                                        </div>

                                        <!-- AMOUNT -->
                                        <div class="flex flex-col mx-auto">
                                            <p class="text-xs">Amount: </p>
                                            <div class="flex">
                                                <div class="flex">
                                                    <p class="text-sm font-bold">{{ crypto.amount }}</p>
                                                    <p class="text-sm font-bold ml-1">{{ crypto.symbol }}</p>
                                                    <button @click='editCryptoAmount(crypto)' class="bg-blue-900 font-bold text-sm rounded-md text-white px-2 ml-1">
                                                        <font-awesome-icon :icon="['fas', 'pencil-alt']"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- TOTAL -->
                                        <div class="flex flex-col">
                                            <p class="text-xs">Total: </p>                                                    
                                            <p class="text-sm font-bold">${{ crypto.total_worth }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHARTS -->
                <div class="bg-white w-full mx-auto">
                    <div class="w-11/12 mx-auto lg:w-10/12">
                        <!-- PORTFOLIO DISTRIBUTION CHART-->
                        <div class="flex flex-col h-40 sm:h-80 lg:h-40 xl:h-80">
                            <div class="mt-4">
                                <h2 class="sm:text-xl font-black">
                                    Portfolio Distribution
                                </h2>
                            </div>
                            <div class="flex w-full mt-4 h-40 sm:h-full sm:justify-between lg:h-40 xl:h-80 xl:mt-2 2xl:mt-4">
                                <div class="w-6/12 sm:h-full sm:w-6/12 lg:w-6/12 lg:h-40 xl:h-80 xl:w-7/12 2xl:w-6/12">
                                    <canvas class="" id="doughnutChart"></canvas>
                                </div>
                                <div class="flex flex-col w-6/12 sm:w-5/12 lg:w-5/12 lg:mt-1 xl:w-4/12 2xl:w-5/12">
                                    <div v-for="(c, index) in cryptoDistribution.cryptos" :key="index" class="flex mt-2">
                                        <div class="w-3 h-3 my-auto rounded-full sm:w-5 sm:h-5" :style="{backgroundColor: chartColors.backgroundColors[index]}"></div>
                                        <p class="text-xs font-bold ml-1 sm:text-sm">{{ c }}</p>
                                        <p class="text-xs font-bold ml-1 sm:text-sm">{{ cryptoDistribution.percentages[index] }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CRYPTOS PERFORMANCE CHART -->
                        <div class="mt-16 mb-8 sm:mt-12 lg:mt-20 xl:mt-24">
                            <div class="flex flex-wrap">
                                <p class="sm:text-xl font-black">
                                    Cryptos Performance
                                </p>
                                <p class="sm:text-xl font-black sm:ml-1">
                                    (% Increase in 7 Days)
                                </p>
                            </div>
                            <div class="mt-4">
                                <canvas class="" id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- NO DATA -->
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
import ModalWindow from '../../Components/ModalWindow.vue'
import LoadingScreen from '../../Components/LoadingScreen.vue'
import EditCryptoForm from '../../Components/EditCryptoForm.vue'
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'

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
import { chartColors } from '../../Charts/ChartColors'

// Charts
import {
    Chart,
    BarController,
    DoughnutController,
    ArcElement,
    BarElement,
    Tooltip,
    LinearScale,
    CategoryScale,
    } from 'chart.js'

import { generateDoughnutChartConf, updateDoughnutChart } from '../../Charts/DoughnutChart.js'
import { generateBarChartConf, updateBarChart } from '../../Charts/BarChart.js'

// Register Chart dependencies
Chart.register(DoughnutController, BarController, BarElement, ArcElement, CategoryScale, LinearScale, Tooltip);

export default {
  components: {
    Layout,
    Link,
    LinkAddCrypto,
    LoadingScreen,
    ModalWindow,
    DeleteCryptoBtn,
    EditCryptoForm,
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
        const topCryptos = ref({
            cryptos: [],
            percentage: [],
        });
        const cryptoDistribution = ref({
            cryptos: [],
            percentage: [],
        });

        // STATUS
        const status = ref('loading');
        const showLoading = ref(true);
        const hasData = ref(false);

        // EDIT FORM
        const showEditForm = ref(false);
        const cryptoToEdit = ref({});

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
        const disableEditCryptoForm = () => {
            showEditForm.value = false;
        }

        const editCryptoAmount = (crypto) => {
            cryptoToEdit.value = crypto;
            showEditForm.value = true;
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
                        cryptoDistribution.value = calculateCryptoDistribution(cryptoData.value, portfolioTotalWorth.value);
                        topCryptos.value = calculateTopCryptos(cryptoData.value);

                        const doughnutHtmlElement = document.getElementById("doughnutChart");
                        doughnutChart = new Chart(doughnutHtmlElement, generateDoughnutChartConf(cryptoDistribution.value, cryptoDistribution.value.cryptos.length));

                        const barHtmlElement = document.getElementById('barChart');
                        barChart = new Chart(barHtmlElement, generateBarChartConf(topCryptos.value, topCryptos.value.cryptos.length));
                })
                .catch(e => console.log(e));
            }
        });

        // WATCHERS
        watch(() => props.cryptos, () => {

            showEditForm.value = false;

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
                        cryptoDistribution.value = calculateCryptoDistribution(cryptoData.value, portfolioTotalWorth.value);
                        topCryptos.value = calculateTopCryptos(cryptoData.value);

                        updateDoughnutChart(doughnutChart, cryptoDistribution.value);
                        updateBarChart(barChart, topCryptos.value);
                })
                .catch(e => console.log(e));
            }
        });

        return {
            cryptoData,
            portfolioTotalWorth,
            portfolioGrowth,
            portfolioGrowthPercentage,
            cryptoDistribution,
            priceColor,
            chartColors,
            disableEditCryptoForm,
            status,
            showLoading,
            showEditForm,
            cryptoToEdit,
            editCryptoAmount,
            hasData,
        }
  },
}
</script>