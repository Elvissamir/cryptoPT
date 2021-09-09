

const generateLineChartConf = (cryptoData) => {
    
    // LINE
    const lineData = {
        labels: ['7d', '6d', '5d', '4d', '3d', '2d', '1d', 'today'],
        datasets: [{
            data: cryptoData,
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


export {
    generateLineChartConf,
}