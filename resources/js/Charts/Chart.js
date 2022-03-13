import {
    Chart,
    BarController,
    DoughnutController,
    ArcElement,
    BarElement,
    Tooltip,
    LinearScale,
    CategoryScale,
    LineElement, 
    LineController, 
    PointElement
} from 'chart.js'

Chart.register(
    DoughnutController, 
    BarController, 
    BarElement, 
    ArcElement, 
    CategoryScale, 
    LinearScale, 
    LineElement, 
    LineController, 
    LinearScale, 
    CategoryScale, 
    PointElement,
    Tooltip);

export default Chart