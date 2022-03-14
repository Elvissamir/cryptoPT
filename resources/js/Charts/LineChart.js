import {  
    Chart,
    CategoryScale,
    LinearScale,
    LineElement,
    LineController,
    PointElement,
    Tooltip,
   } from 'chart.js'

Chart.register(
    CategoryScale, 
    LinearScale, 
    LineElement, 
    LineController, 
    PointElement,
    Tooltip);

const generateLineChartConf = (data) => {
    const lineData = {
        labels: ['7d', '6d', '5d', '4d', '3d', '2d', '1d', 'today'],
        datasets: [{
            data: data,
            fill: false,
            borderColor: 'rgb(0, 150, 250)',
            tension: 0.1,
        }]
    };
    
    const lineConf = {
        type: 'line',
        data: lineData, 
        options: {
            legend: {
                display: false
            }
        }
    };

    return lineConf;
}

const createLineChart = (element, data) => {
    return new Chart(element, generateLineChartConf(data))
}

export {
    generateLineChartConf,
    createLineChart,
}