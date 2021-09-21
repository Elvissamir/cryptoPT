
import { chartColors } from "./ChartColors";
import { maxCryptos } from '../Helpers/MaxChartCryptos';

const generateDoughnutChartConf = (portfolioCryptoDistribution) => {

    const max = maxCryptos(portfolioCryptoDistribution.cryptos);

    const doughnutChartColors = chartColors.backgroundColors.map(color => color).slice(0, max);

    // DOUGHNUT
    const dataDoughnut = {
        labels: portfolioCryptoDistribution.cryptos,
        datasets: [{
            data: portfolioCryptoDistribution.percentages,
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


const updateDoughnutChart = (chart, portfolioCryptoDistribution) => {

    chart.data.labels = portfolioCryptoDistribution.cryptos;

    const max = maxCryptos(portfolioCryptoDistribution.cryptos);
    
    const doughnutChartColors = chartColors.backgroundColors.map(color => color)
                                                            .slice(0, max);

    chart.data.datasets.forEach(dataset => {
        dataset.data = portfolioCryptoDistribution.percentages;
        dataset.backgroundColor = doughnutChartColors;
    });

    chart.update();
}

export {
    generateDoughnutChartConf, 
    updateDoughnutChart,
}