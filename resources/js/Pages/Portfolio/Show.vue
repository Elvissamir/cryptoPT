
<template>
    <layout>

    <!-- MAIN -->
    <div class="w-full">
        <div class="w-full mx-auto grid grid-cols-1">
            
            <!-- MY PORTFOLIO & CRYPTOS SECTION -->
            <div class="flex flex-col text-white bg-blue-900">
                
                <!-- PORTFOLIO INFO -->
                <div class="py-6 w-11/12 mx-auto">
                    <div class="flex">
                        <div class="w-6/12 flex flex-wrap">
                            <h1 class="sm:text-xl font-semibold">My Portfolio</h1>
                        </div>
        
                        <div class="w-6/12 flex justify-end items-baseline">
                            <p class="text-xs sm:text-md mr-2">Total: </p>
                            <p class="sm:text-xl font-bold text-green-300">
                                ${{ portfolioTotalWorth }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 flex">
                        <div class="ml-auto align-top order-last flex justify-start flex-col">
                            <div class="flex justify-end">
                                <p class="sm:text-sm text-xs my-auto">Growth % (24h): </p>
                                <p :class="[priceColor(portfolioGrowthPercentage), 'font-bold']" 
                                    class="sm:text-sm text-sm ml-2">
                                        {{ portfolioGrowthPercentage }}%
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <p class="sm:text-sm text-xs my-auto">Growth (24h):</p>
                                <p 
                                    :class="[priceColor(portfolioGrowth), 'font-bold']" 
                                    class="sm:text-sm text-sm ml-2">
                                        {{ portfolioGrowth }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <div class="flex">
                                <p class="sm:text-sm text-xs my-auto">Number of cryptos in portfolio: </p>
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
                    </div>
                </div>

                <!-- MY CRYPTOS SECTION -->
                <div class="bg-white rounded-t-3xl w-full mt-4 text-black align-bottom">
                    <div class="py-6 w-11/12 mx-auto">
                        <div class="flex justify-between">
                            <div class="">
                                <h2 class="sm:text-xl font-semibold">My Cryptos</h2>
                            </div>
                            <div class="mb-1">
                                <Link class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-bold" href="/cryptos">
                                    Add +
                                </Link>
                            </div>
                        </div>
    
                        <!-- LIST OF CRYPTOS -->
                        <div class="flex flex-col mt-4 divide-y-2 divide-gray-300 border-b-2 border-t-2 border-gray-300">
    
                            <!-- CRYPTO -->
                            <div v-for="(crypto, index) in cryptoData" :key="index" class="flex flex-wrap sm:max-w-3xl sm:flex-row sm:justify-between w-full py-2">
                                <!-- CRYPTO SYMBOL -->
                                <div class="flex w-8/12 sm:w-2/12">
                                    <img class="w-9 h-9" :src="crypto.image">
                                    <div class="flex flex-col ml-2">
                                        <p class="text-sm font-semibold">{{ crypto.symbol }}</p>
                                        <a class="underline text-xs font-semibold text-indigo-500" href="">{{ crypto.name }}</a>
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
                                    <p class="text-sm font-bold">${{ crypto.current_price }}</p>
                                </div>
    
                                <!-- AMOUNT -->
                                <div class="flex flex-col w-4/12 sm:w-1/12">
                                    <p class="text-xs">Amount: </p>

                                    <div class="flex">
                                        <div v-if="(editing == true && editingIndex == index)">
                                            <input type="number" v-model="newAmount">
                                            <button @click="saveCryptoAmount(crypto, index)" class="bg-blue-900 font-bold text-sm text-white py-1 px-2">
                                                Save
                                            </button>
                                            <button @click="cancelEdit" class="bg-blue-900 font-bold text-sm text-white py-1 px-2">
                                                Cancel
                                            </button>
                                        </div>

                                        <div v-else>
                                            <p class="text-sm font-bold">{{ crypto.amount }}</p>
                                            <p class="text-sm font-bold ml-1">{{ crypto.symbol }}</p>
                                            <button @click='editCryptoAmount(crypto, index)' class="bg-blue-900 font-bold text-sm text-white py-1 px-2">
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

            <!-- PORTFOLIO DISTRIBUTION CHART-->
            <div class="bg-white" >
                <canvas class="w-3/12 mx-auto" id="doughnutChart"></canvas>
            </div>

            <!-- Portfolio Distribution Chart --> 
            <div class="bg-white">
                <canvas class="w-3/12 mx-auto" id="barChart"></canvas>
            </div>

        </div>
    </div>

    </layout>
</template>

<script>

// Layout
import Layout from '../../Layouts/AppLayout'

// Vue
import { ref, onMounted, watch } from 'vue'

// COMPONENTS
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'
import { Link } from '@inertiajs/inertia-vue3'

// INERTIA
import { Inertia } from '@inertiajs/inertia'

// Helpers
import { formatNumber } from '../../Helpers/FormatNumber'
import { priceColor } from '../../Helpers/PriceColor'

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
    Legend,
    } from 'chart.js'

import { generateDoughnutChartConf, updateDoughnutChart } from '../../Charts/DoughnutChart.js' 
import { generateBarChartConf, updateBarChart } from '../../Charts/BarChart.js'

// Register Chart dependencies
Chart.register(DoughnutController, BarController, BarElement, ArcElement, CategoryScale, LinearScale, Tooltip, Legend);

export default {
  components: {
    Link,
    Layout,
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
        let ids = props.cryptos.map(crypto => {
            return crypto.cg_id
        });

        let allIds = ids.join('%2C%20');

        // Request URL
        let cryptosInfoUrl = `${main_url}markets?vs_currency=${currency}&ids=${allIds}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change_percentage}`;

        // PORTFOLIO WORTH
        const portfolioTotalWorth = ref(0);
        const portfolioGrowth = ref(0);
        const portfolioGrowthPercentage = ref(0);

        // Edit Amount Variables
        const newAmount = ref(0); 
        const editingIndex = ref(0);
        const editing = ref(false);
        
        // METHODS
        // PORTFOLIO DATA
        const calculateTotalWorth = (cryptosData) => { 
            return cryptosData.reduce((total, crypto) => {
                return total + (crypto.current_price * crypto.amount)
            }, 0);
        }

        const calculateGrowth = (cryptosData) => {
            return formatNumber(cryptosData.reduce((growth, crypto) => {
                return growth + (crypto.price_change_24h * crypto.amount);
            }, 0));
        }

        const calculateGrowthPercentage = (portfolioTotalWorth, portfolioGrowth) => {
            return formatNumber((portfolioGrowth / (portfolioTotalWorth - portfolioGrowth)) * 100);
        }

        const calculatePortfolioData = (cryptosData) => {

            portfolioTotalWorth.value = calculateTotalWorth(cryptosData);
            portfolioGrowth.value = calculateGrowth(cryptosData);
            portfolioGrowthPercentage.value = calculateGrowthPercentage(portfolioTotalWorth.value, portfolioGrowth.value);
        }

        // CHARTS DATA
        const calculateCryptoDistribution = (cryptoData) => {

            let distribution = {
                percentages: [],
                cryptos: [],
            }

            let topDistribution = {
                percentages: [],
                cryptos: [],
            }

            let max = (cryptoData.length > 5)? 5 : cryptoData.length;
            
            cryptoData.forEach(crypto => {
                distribution.percentages.push(((crypto.current_price * crypto.amount) /  portfolioTotalWorth.value) * 100);
                distribution.cryptos.push(crypto.cg_id);
            });

            topDistribution.percentages = distribution.percentages.map(percentage => percentage)
                                                                  .sort((a, b) => b - a)
                                                                  .slice(0, max);

            for (let x = 0; x < topDistribution.percentages.length; x++) {
                for (let y = 0; y < distribution.percentages.length; y++) {

                    if (distribution.percentages[y] == topDistribution.percentages[x]) {
                        topDistribution.cryptos.push(distribution.cryptos[y]);
                        break;
                    }
                }
            }

            return topDistribution;
        }

        const calculateTopCryptos = () => {

            let top = {
                cryptos: [],
                percentages: []
            };

            let max = (cryptoData.value.length > 5)? 5 : cryptoData.value.length;
            
            top.percentages = cryptoData.value.map(crypto => crypto.price_change_percentage_7d)
                                              .sort((a, b) => b - a)
                                              .slice(0, max);

            for (let i = 0; i < max; i++) {
                for (const crypto of cryptoData.value) {

                    if (crypto.price_change_percentage_7d == top.percentages[i]) {
                        top.cryptos.push(crypto.cg_id);
                        break;
                    }
                }
            }

            return top;
        }

        const joinCryptoData = (cgData) => {

                let tempData = [];

                for (let cgCrypto of cgData) { 
                    for (let dbCrypto of props.cryptos) {

                    if (cgCrypto.id == dbCrypto.cg_id)
                        {
                            tempData.push({
                                cg_id: cgCrypto.id,
                                name: cgCrypto.name,
                                image: cgCrypto.image,
                                symbol: cgCrypto.symbol.toUpperCase(),
                                amount: formatNumber(dbCrypto.amount),
                                total_worth: formatNumber(dbCrypto.amount * cgCrypto.current_price),
                                created_at: dbCrypto.created_at,
                                current_price: formatNumber(cgCrypto.current_price), 
                                price_change_24h: formatNumber(cgCrypto.price_change_24h),
                                price_change_percentage_24h: formatNumber(cgCrypto.price_change_percentage_24h),
                                price_change_percentage_7d: formatNumber(cgCrypto.price_change_percentage_7d_in_currency),   
                        });

                        break;
                    }
                }
            }

            return tempData;
        }

        // EDIT METHODS
        const editCryptoAmount = (crypto, index) => {
            newAmount.value = crypto.amount;
            editing.value = true;
            editingIndex.value = index;
        }

        const cancelEdit = () => {
            editing.value = false;
        }

        const validAmount = (amount) => {
            return true;
        }

        const saveCryptoAmount = (crypto, index) => {

            let url = `/portfolio/cryptos/${crypto.cg_id}`;

            if (validAmount(newAmount.value)) {

                editing.value = false;
                props.cryptos[index].amount = newAmount;

                Inertia.put(url, {amount: newAmount.value});        
            }
        }

        // CYCLE HOOKS
        onMounted(() => {    

            // DATA OF ALL CRYPTOS INFORMATION
            axios.get(cryptosInfoUrl)
                 .then(res => {
                    
                    cryptoData.value = joinCryptoData(res.data);

                    // console.log('CG DATA: ', cryptoData.value);

                    // CALCULATE PORTFOLIO DATA
                    calculatePortfolioData(cryptoData.value);

                    // CALCULATE CHARTS DATA
                    const cryptoDistribution = calculateCryptoDistribution(cryptoData.value);
                    const topCryptos = calculateTopCryptos(cryptoData.value);

                    const doughnutHtmlElement = document.getElementById("doughnutChart");
                    doughnutChart = new Chart(doughnutHtmlElement, generateDoughnutChartConf(cryptoDistribution, cryptoDistribution.cryptos.length));
                                    
                    const barHtmlElement = document.getElementById('barChart');
                    barChart = new Chart(barHtmlElement, generateBarChartConf(topCryptos, topCryptos.cryptos.length));
                
                 })
                 .catch(e => console.log(e));
        });

        // WATCHERS
        watch(() => props.cryptos, () => {

            axios.get(cryptosInfoUrl)
                 .then((res) => {

                    cryptoData.value =  joinCryptoData(res.data);

                    // CALCULATE PORTFOLIO DATA
                    calculatePortfolioData(cryptoData.value);

                    // UPDATE CHARTS DATA
                    const cryptoDistribution = calculateCryptoDistribution(cryptoData.value);
                    const topCryptos = calculateTopCryptos(cryptoData.value);

                    updateDoughnutChart(doughnutChart, cryptoDistribution);
                    updateBarChart(barChart, topCryptos);                    
                 })
                 .catch(e => console.log(e));
        });

        return { 
            cryptoData, 
            portfolioTotalWorth, 
            portfolioGrowth,
            portfolioGrowthPercentage,
            formatNumber,
            priceColor,
            newAmount,
            editingIndex,
            editing,
            editCryptoAmount,
            cancelEdit,
            saveCryptoAmount,
        }
  },
}
</script>