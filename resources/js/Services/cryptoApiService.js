import httpService from './httpService.js'

const coingeckoApiEndpoint = "https://api.coingecko.com/api/v3/coins/";
const currency = 'usd';
const order = "market_cap_desc";
const per_page = 10;
const page = 1;
const sparkline = false;
const days = 7;
const interval = 'daily';
const price_change = "1h%2C24h%2C7d";
const price_change_percentage = '7d%2C%2024h';

const generateUrlFromIdArray = (ids) => {
    let allIds = ids.join('%2C%20');
    return `${coingeckoApiEndpoint}markets?vs_currency=${currency}&ids=${allIds}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change_percentage}`;
}

const generateUrlFromId = (id) => {
    return `${coingeckoApiEndpoint}/markets?vs_currency=${currency}&ids=${id}&order=${order}&per_page=1&page=1&${sparkline}&price_change_percentage=${price_change}`;
}

const generateCryptoRanksUrl = (page) => {
    return `${coingeckoApiEndpoint}/markets?vs_currency=${currency}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change}`;
}

const generateCryptoChartUrl = (id) => {
    return `${coingeckoApiEndpoint}/${id}/market_chart?vs_currency=${currency}&days=${days}&interval=${interval}`;
}

const getCryptoById = (id) => {
    return httpService.get(generateUrlFromId(id))
}

const getCryptoChartDataById = (id) => {
    return httpService.get(generateCryptoChartUrl(id))
}

const getCryptosById = (cryptoArr) => {
    return httpService.get(generateUrlFromIdArray(cryptoArr))
}

const getCryptoRankList = (page) => {
    return httpService.get(generateCryptoRanksUrl(page))
}

export {
    getCryptoById,
    getCryptoChartDataById,
    getCryptosById,
    getCryptoRankList,
}