import Chart from './Chart'
import { chartColors } from "./ChartColors";
import { maxCryptos } from '../Helpers/MaxChartCryptos';

const generateBarChartConf = (data) => {
    const max =  maxCryptos(data.cryptos);

    const barChartColors = {
        backgroundColors: chartColors.backgroundColors.map(color => color).slice(0, max),
        borderColor: chartColors.borderColors.map(color => color).slice(0, max),
    }
        
     // BAR
    const dataBar = {
        legend: 'Top 5 Cryptos % growth',
        labels: data.cryptos,
        datasets: [{
            data: data.percentages,
            backgroundColor: barChartColors.backgroundColors,
            borderColor: barChartColors.borderColor,
            borderWith: 1,
        }]
    };
    
    const barConf = {
        type: 'bar',
        data: dataBar, 
        options: {
            scales: {
                y: {
                    beginAtZero: true
                },
            },
            legend: {
                display: false
            }
        }
    };

    return barConf;
}

const createBarChart = (element, data) => {
    return new Chart(element, generateBarChartConf(data));
}

const updateBarChart = (chart, topCryptos) => {

    chart.data.labels = topCryptos.cryptos;

    const max =  maxCryptos(topCryptos.cryptos);

    const barChartColors = chartColors.backgroundColors.map(color => color)
                                                                .slice(0, max);
    chart.data.datasets.forEach(dataset => {
        dataset.data = topCryptos.percentages;
        dataset.backgroundColor = barChartColors;
        dataset.borderColor = chartColors.borderColors.map(color => color).slice(0, max);
    });
    
    chart.update();
}


export {
    generateBarChartConf,
    createBarChart,
    updateBarChart,    
}
