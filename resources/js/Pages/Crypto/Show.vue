
<template>
    <div>
 <Layout>

    <ModalWindow :showModal="showAddForm">
        <AddCryptoForm @close-form="disableAddCryptoForm" :crypto="cryptoToAdd"></AddCryptoForm>   
    </ModalWindow>

    <div class="flex w-full">
        <div class="flex bg-white w-11/12 mx-auto">
            <div class="flex flex-col w-11/12 mx-auto">
                <!-- CRYPTO SYMBOL -->
                <div class="flex w-8/12 sm:w-2/12">
                    <img class="w-9 h-9" :src="coin.image">
                    <div class="flex flex-col ml-2">
                        <p class="text-sm font-semibold">{{ coin.symbol }}</p>
                        <Link class="underline text-xs font-semibold text-indigo-500" :href="coin.url">{{ coin.name }}</Link>
                    </div>
                </div>

                <!-- ADD OR DELETE BUTTON -->
                <div class="flex">
                    <DeleteCryptoBtn v-if="coin.inPortfolio" :cg_id="coin.cg_id"></DeleteCryptoBtn> 
                    
                    <AddCryptoBtn v-else @open-add-form="activeAddCryptoForm" :crypto="coin"></AddCryptoBtn> 
                </div>

                <!-- RANK -->
                <div class="flex">
                    <p>Market Cap Rank: </p>
                    <p class="ml-1">{{ coin.rank }}#</p>
                </div>

                <!-- PRICE -->
                <div class="flex">
                    <p>Price: </p>
                    <p class="ml-1">${{ coin.price }}</p>    
                </div> 

                <!-- PRICE CHANGES --> 
                <div class="flex">
                    <div class="flex">
                        <p>Change 1h:</p>
                        <p :class="[priceColor(coin.price_change_1h), 'font-bold']">
                            {{ coin.price_change_1h }}%
                        </p>
                    </div>

                    <div class="flex">
                        <p>Change 24h:</p>
                        <p :class="[priceColor(coin.price_change_24h), 'font-bold', 'ml-1']">
                            {{ coin.price_change_24h }}%
                        </p>
                    </div>

                    <div class="flex">
                        <p>Change 7d:</p>
                        <p :class="[priceColor(coin.price_change_7d), 'font-bold', 'ml-1']">
                            {{ coin.price_change_7d }}%
                        </p>
                    </div>
                </div>

                <!-- AMOUNT OWNED --> 
                <div class="flex">
                    <p>Amount: </p>
                    <div class="ml-1">
                        <div class="flex" v-if="coin.inPortfolio">
                            <p>
                                {{ coin.amount }}
                            </p>

                            <p>
                                {{ coin.symbol }}
                            </p>
                        </div>
                        
                        <div v-else>
                            <p>-</p>
                        </div>
                    </div>
                </div>

                <!-- ALL TIME HIGH AND LOW -->
                <div class="flex">
                    <div class="flex">
                        <p>Ath: </p>
                        <p class="ml-1">${{ coin.ath }}</p>
                    </div>

                    <div class="flex">
                        <p>Atl: </p>
                        <p class="ml-1">${{ coin.atl }}</p>
                    </div>
                </div>

                <!-- 7d DAYS GRAPH -->
                <div class="flex flex-col">
                    <div>
                        <h2 class="sm:text-lg font-semibold">
                            7 Days Price Chart
                        </h2>
                    </div>
                    <canvas id="lineChart"></canvas>
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

// COMPONENTS
import { Link } from '@inertiajs/inertia-vue3'
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'
import AddCryptoBtn from '../../Components/AddCryptoBtn.vue'
import AddCryptoForm from '../../Components/AddCryptoForm.vue'
import ModalWindow from '../../Components/ModalWindow'

// HELPERS
import { priceColor } from '../../Helpers/PriceColor'

// CHART
import { Chart, LineElement, LineController, LinearScale, CategoryScale, PointElement } from 'chart.js'
import { generateLineChartConf } from '../../Charts/LineChart';
import { joinCryptoData } from '../../Helpers/JoinCryptoData';

Chart.register(LineElement, LineController, LinearScale, CategoryScale, PointElement);

export default {
    components: {
        Layout,
        Link,
        AddCryptoForm,
        AddCryptoBtn,
        DeleteCryptoBtn,
        ModalWindow,
    },
    props: {
        crypto: {
            type: Object,
            required: true,
        }
    },
    setup(props) {

        // URL 
        const baseUrl = ' https://api.coingecko.com/api/v3/coins';
        const currency = 'usd';
        const cryptoId = props.crypto.cg_id;
        const order = 'market_cap_desc';
        const sparkline = false;
        const price_change = '1h%2C24h%2C7d';
        const days = 7;
        const interval = 'daily';

        const cryptoDataUrl = `${baseUrl}/markets?vs_currency=${currency}&ids=${cryptoId}&order=${order}&per_page=1&page=1&${sparkline}&price_change_percentage=${price_change}`;
        const chartDataUrl = `${baseUrl}/${cryptoId}/market_chart?vs_currency=${currency}&days=${days}&interval=${interval}`;

        // CRYPTO DATA
        let coin = ref({
            url: ''
        });

        const options = {
            price_change_1h: true, 
            price_change_24h: true,
            ath: true,
            atl: true,
            rank: true,
            price_change_percentage_7d: true,
        };

        // CHART DATA
        const chartData = ref([]);

        // ADD CRYPTO FORM STATE AND DATA
        const showAddForm = ref(false);
        const cryptoToAdd = ref({});

        // HOOKS
        onMounted(() => {
            getCryptoData(cryptoDataUrl);
            generateLineChart(chartDataUrl);
        })

        const activeAddCryptoForm = (crypto) => {
            cryptoToAdd.value = crypto;
            showAddForm.value = true;
        }

        const disableAddCryptoForm = () => {
            showAddForm.value = false;
        }

        const getCryptoData = (url) => {

            axios.get(url)
                .then(res => {

                    const cryptoName = props.crypto.cg_id;

                    let cryptoObj = {};
                    cryptoObj[cryptoName] = props.crypto;

                    coin.value = joinCryptoData(cryptoObj, res.data[0], options);                 
                })
                .catch(e => console.log(e));
        }

        const generateLineChart = (url) => {
             axios.get(url)
                .then(res => {

                    chartData.value = res.data.prices.map(price => price[1]);

                    const conf = generateLineChartConf(chartData.value);
                    const htmLineElement = document.getElementById('lineChart');

                    const lineChart = new Chart(htmLineElement, conf);
                })
                .catch(e => console.log(e));
        }

        watch(() => props.crypto, () => {
            
            console.log('watcher props: ', props.crypto);

            showAddForm.value = false;
            getCryptoData(cryptoDataUrl);
        });

        return {
            coin,
            priceColor,
            showAddForm,
            cryptoToAdd,
            activeAddCryptoForm,
            disableAddCryptoForm,
        }
    },
}
</script>