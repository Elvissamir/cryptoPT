import {  
    Chart,
    DoughnutController,
    ArcElement,
    Tooltip,
   } from 'chart.js'
import { chartColors } from "./ChartColors";
import { maxCryptos } from '../Helpers/MaxChartCryptos';

Chart.register( 
    DoughnutController, 
    ArcElement , 
    Tooltip
);

const generateDoughnutChartConf = (data) => {

    const max = maxCryptos(data.cryptos);

    const doughnutChartColors = chartColors.backgroundColors.map(color => color).slice(0, max);

    // DOUGHNUT
    const dataDoughnut = {
        labels: data.cryptos,
        datasets: [{
            data: data.percentages,
            backgroundColor: doughnutChartColors,
            hoverOffset: 4
        }]
    };
    
    const doughnutConf = {
        type: 'doughnut',
        data: dataDoughnut, 
        options: {
            responsive:true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    };

    return doughnutConf;
}

const createDoughnutChart = (element, data) => {
    return new Chart(element, generateDoughnutChartConf(data));
}

const updateDoughnutChart = (chart, portfolioCryptoDistribution) => {

    chart.data.labels = portfolioCryptoDistribution.cryptos;

    const max = maxCryptos(portfolioCryptoDistribution.cryptos);
    
    const doughnutChartColors = chartColors.backgroundColors.map(color => color).slice(0, max);

    chart.data.datasets.forEach(dataset => {
        dataset.data = portfolioCryptoDistribution.percentages;
        dataset.backgroundColor = doughnutChartColors;
    });

    chart.update();
}

export {
    generateDoughnutChartConf, 
    createDoughnutChart,
    updateDoughnutChart,
}