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

Chart.register(DoughnutController, BarController, BarElement, ArcElement, CategoryScale, LinearScale, Tooltip);

export default Chart