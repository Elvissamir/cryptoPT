
<template>
    <Layout>
   
    <div class="flex w-full">
        <div class="flex bg-white w-11/12 mx-auto">
            <div class="flex flex-col w-11/12 mx-auto">
                <!-- CRYPTO SYMBOL -->
                <div class="flex w-8/12 sm:w-2/12">
                    <img class="w-9 h-9" :src="coin.image">
                    <div class="flex flex-col ml-2">
                        <p class="text-sm font-semibold">{{ coin.symbol }}</p>
                        <a class="underline text-xs font-semibold text-indigo-500" href="">{{ coin.name }}</a>
                    </div>
                </div>

                <!-- ADD OR DELETE BUTTON -->
                <div class="flex">
                    <div v-if="coin.inPortfolio">
                        <DeleteCryptoBtn :cg_id="coin.cg_id"></DeleteCryptoBtn> 
                    </div>

                    <div v-else>
                        <AddCryptoBtn :crypto="coin"></AddCryptoBtn> 
                    </div>
                </div>

                <!-- RANK -->
                <div class="flex">
                    <p>Rank: </p>
                    <p>{{ coin.rank }}</p>
                </div>

                <!-- PRICE -->
                <div class="flex">
                    <p>Price: </p>
                    <p>{{ coin.price }}</p>    
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
                        <p :class="[priceColor(coin.price_change_24h), 'font-bold']">
                            {{ coin.price_change_24h }}%
                        </p>
                    </div>

                    <div class="flex">
                        <p>Change 7d:</p>
                        <p :class="[priceColor(coin.price_change_7d), 'font-bold']">
                            {{ coin.price_change_7d }}%
                        </p>
                    </div>
                </div>

                <!-- AMOUNT OWNED --> 
                <div class="flex">
                    <p>Amount: </p>
                    <div>
                        <div v-if="coin.inPortfolio">
                            <p>
                                {{ coin.amount }}
                            </p>

                            <p>
                                {{ coin.symbol }}
                            </p>
                        </div>
                        <p v-else>-</p>
                    </div>
                </div>


                <!-- ALL TIME HIGH AND LOW -->
                <div class="flex">
                    <div class="flex">
                        <p>Ath</p>
                        <p>{{ coin.ath }}</p>
                    </div>

                    <div class="flex">
                        <p>Atl</p>
                        <p>{{ coin.atl }}</p>
                    </div>
                </div>

                <!-- 7d DAYS GRAPH -->
                <div class="flex">
                    <canvas id="lineChart"></canvas>
                </div>

            </div>
        </div>

    </div>

    </Layout>
</template>

<script>

// Layout
import Layout from "../../Layouts/AppLayout";

import { ref, onMounted } from 'vue'

// COMPONENTS
import DeleteCryptoBtn from '../../Components/DeleteCryptoBtn.vue'
import AddCryptoBtn from '../../Components/AddCryptoBtn.vue'

// HELPERS
import { formatNumber } from '../../Helpers/FormatNumber'
import { priceColor } from '../../Helpers/PriceColor'

// CHART
import { Chart, LineElement, LineController, LinearScale, CategoryScale, PointElement } from 'chart.js'
import { generateLineChartConf } from '../../Charts/LineChart';

Chart.register(LineElement, LineController, LinearScale, CategoryScale, PointElement);

export default {
    components: {
        Layout,
        AddCryptoBtn,
        DeleteCryptoBtn,
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
        const coin = ref({});

        // CHART DATA
        const chartData = ref([]);

        // HOOKS
        onMounted(() => {

            getCryptoData(cryptoDataUrl);

            generateLineChart(chartDataUrl);
        })

        const getCryptoData = (url) => {

            axios.get(url)
                .then(res => {
                    
                    const cgCryptoData = res.data[0];

                    console.log('cgCdata: ', cgCryptoData);

                    coin.value = {
                        cg_id: cgCryptoData.id,
                        name: cgCryptoData.name,
                        image: cgCryptoData.image,
                        symbol: cgCryptoData.symbol.toUpperCase(),
                        rank: cgCryptoData.market_cap_rank,
                        atl: formatNumber(cgCryptoData.atl),
                        ath: formatNumber(cgCryptoData.ath),
                        price: formatNumber(cgCryptoData.current_price), 
                        price_change_1h: formatNumber(cgCryptoData.price_change_percentage_1h_in_currency),
                        price_change_24h: formatNumber(cgCryptoData.price_change_percentage_24h_in_currency),
                        price_change_7d: formatNumber(cgCryptoData.price_change_percentage_7d_in_currency),   
                    }

                    if (props.crypto.amount !== null) {

                        coin.value['inPortfolio'] = true;
                        coin.value['amount'] = formatNumber(props.crypto.amount);
                    }
                })
                .catch(e => console.log(e));
        }

        const generateLineChart = (url) => {
             axios.get(url)
                .then(res => {

                    console.log(res.data);

                    chartData.value = res.data.prices.map(price => price[1]);

                    const conf = generateLineChartConf(chartData.value);
                    const htmLineElement = document.getElementById('lineChart');

                    const lineChart = new Chart(htmLineElement, conf);
                })
                .catch(e => console.log(e));
        }

        return {
            coin,
            priceColor,
        }
    },
}
</script>