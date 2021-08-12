
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
                            <p class="sm:text-xl font-bold text-green-300">${{ formatNumber(portfolioTotalWorth) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex">
                        <div class="ml-auto align-top order-last flex justify-start flex-col">
                            <div class="flex justify-end">
                                <p class="sm:text-sm text-xs my-auto">Growth % (24h): </p>
                                <p class="sm:text-sm text-sm font-bold ml-2 text-green-300">{{ formatNumber(portfolioGrowthPercentage) }}%</p>
                            </div>

                            <div class="flex justify-end">
                                <p class="sm:text-sm text-xs my-auto">Growth (24h):</p>
                                <p class="sm:text-sm text-sm font-bold ml-2 text-green-300">{{ formatNumber(portfolioGrowth) }}</p>
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
                                <a class="px-3 py-2 mb-1 bg-blue-900 rounded-md sm:text-sm text-xs text-white font-bold" href="">
                                    Add +
                                </a>
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
                                        <p class="text-sm font-semibold">{{ crypto.symbol.toUpperCase() }}</p>
                                        <a class="underline text-xs font-semibold text-indigo-500" href="">{{ crypto.name }}</a>
                                    </div>
                                </div>
    
                                <!-- DELETE BUTTON-->
                                <div class="flex w-4/12 sm:w-1/12 rounded justify-end sm:my-auto sm:order-6">
                                    <button class="flex p-2 h-6 w-6 justify-center items-center bg-indigo-900 text-white text-sm font-semibold rounded-full">
                                        X
                                    </button>
                                </div>
                                
                                <!-- ADDED AT -->
                                <div class="flex sm:flex-col sm:w-2/12 items-baseline w-full  mb-2 sm:mb-0">
                                    <p class="text-xs">Added: </p>
                                    <p class="text-xs font-bold ml-2 sm:text-sm sm:ml-0">{{ crypto.created_at }}</p>
                                </div>
    
                                <!-- PRICE -->
                                <div class="flex flex-col w-4/12 sm:w-1/12">
                                    <p class="text-xs">Price: </p>
                                    <p class="text-sm font-bold">${{ formatNumber(crypto.current_price) }}</p>
                                </div>
    
                                <!-- AMOUNT -->
                                <div class="flex flex-col w-4/12 sm:w-1/12">
                                    <p class="text-xs">Amount: </p>
                                    <div class="flex">
                                        <p class="text-sm font-bold">{{ Math.trunc(crypto.amount) }}</p>
                                        <p class="text-sm font-bold ml-1">{{ crypto.symbol.toUpperCase() }}</p>
                                    </div>
                                </div>
    
                                <!-- TOTAL -->
                                <div class="flex flex-col w-4/12 sm:w-1/12">
                                    <p class="text-xs">Total: </p>
                                    <p class="text-sm font-bold">${{ formatNumber(crypto.total_worth) }}</p>
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
            <div></div>

        </div>
    </div>

    </layout>
</template>

<script>
// Layout
import Layout from '../../Layouts/AppLayout'
// Vue
import { ref, onMounted, computed } from 'vue'

// Helpers
import { formatNumber } from '../../Helpers/FormatNumber'

// Charts
import {
    Chart,  
    DoughnutController, 
    ArcElement,
    Tooltip,
    Legend
    } from 'chart.js'

// Register Chart dependencies
Chart.register(DoughnutController, ArcElement, Tooltip, Legend);

export default {
  components: {
    Layout,
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
        let tempData = [];
        let cryptoData = ref([]);
        let chartData = ref([]);
        
        // Request Parameters
        let main_url = "https://api.coingecko.com/api/v3/coins/";
        let currency = 'usd';
        let order = "market_cap_desc";
        let per_page = "100";
        let page = 1;
        let days = 7;
        let interval = 'daily';
        let sparkline = false;
        let price_change_percentage = '7d%2C%2024h';
        let ids = props.cryptos.map(crypto => {
            return crypto.cg_id
        });

        let allIds = ids.join('%2C%20');

        // Join request url
        let cryptosInfoUrl = `${main_url}markets?vs_currency=${currency}&ids=${allIds}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change_percentage}`;
        
        /*

        PRICE HISTORY DATA

        const marketDataUrls = ids.map(id => {
            return `${main_url}/${id}/market_chart?vs_currency=${currency}&days=${days}&interval=${interval}`;
        });

        */

        // Cycle hooks
        onMounted(() => {    

            // DATA OF ALL CRYPTOS INFORMATION
            axios.get(cryptosInfoUrl)
                 .then(res => {
                    
                    console.log(res.data);
                    
                    for (let cgCrypto of res.data) { 
                        for (let dbCrypto of props.cryptos) {

                            if (cgCrypto.id == dbCrypto.cg_id)
                            {
                                tempData.push({
                                    id: cgCrypto.id,
                                    name: cgCrypto.name,
                                    image: cgCrypto.image,
                                    symbol: cgCrypto.symbol,
                                    amount: dbCrypto.amount,
                                    total_worth: dbCrypto.amount * cgCrypto.current_price,
                                    created_at: dbCrypto.created_at,
                                    current_price: cgCrypto.current_price, 
                                    price_change_24h: cgCrypto.price_change_24h,
                                    price_change_percentage: cgCrypto.price_change_percentage_24h,   
                                });

                                break;
                            }
                        }
                    }

                    cryptoData.value = tempData;
                    console.log('crypto data: ', cryptoData.value);

                        const generateRandomNumber = (min, max) => {
                            return Math.floor(Math.random() * (max - min)) + min;
                        };

                         const generateColors = (numberOfColors) => {
                            const colors = [];

                            for (let i = 0; i < numberOfColors; i++) {
                                let min = 0;
                                let max = 256;
                                let x = generateRandomNumber(min, max);
                                let y = generateRandomNumber(min, max);
                                let z = generateRandomNumber(min, max);

                                colors.push(`rgba(${x}, ${y}, ${z}, 0.9)`);
                            }
                                    
                            return colors;
                        };


                        const data = {
                            labels: portfolioCryptoDistribution.value.cryptos,
                            datasets: [{
                                data: portfolioCryptoDistribution.value.percentages,
                                backgroundColor: generateColors(),
                                hoverOffset: 4
                            }]
                        };

                        const config = {
                            type: 'doughnut',
                            data: data, 
                            options: {
                                plugins: {
                                    
                                }
                            }
                        };

                        // Create CHART
                        const ctx = document.getElementById("doughnutChart");
                        const doughnutChart = new Chart(ctx, config);
                 })
                 .catch(e => console.log(e));
        });

        // Computed
        const portfolioTotalWorth = computed(() => {

            return cryptoData.value.reduce((total, crypto) => {
                return total + (crypto.current_price * crypto.amount)
            }, 0);
        });

        const portfolioGrowth = computed(() => {
            
            return cryptoData.value.reduce((growth, crypto) => {
                return growth + crypto.price_change_24h
            }, 0);
        });

        const portfolioGrowthPercentage = computed(() => { 

            return (portfolioGrowth.value / portfolioTotalWorth.value) * 100;
        });

        const portfolioCryptoDistribution = computed(() => {

            let distribution = {
                percentages: [],
                cryptos: [],
            };

            cryptoData.value.forEach(crypto => {
                distribution.percentages.push(((crypto.current_price * crypto.amount) /  portfolioTotalWorth.value) * 100);
                distribution.cryptos.push(crypto.id);
            });

            return distribution;
        });


        return { 
            cryptoData, 
            portfolioTotalWorth, 
            portfolioGrowth,
            portfolioGrowthPercentage,
            portfolioCryptoDistribution,
            formatNumber,
            chartData,
        }
  },
}
</script>