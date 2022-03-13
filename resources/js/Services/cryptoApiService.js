import httpService from './httpService.js'

const coingeckoApiEndpoint = "https://api.coingecko.com/api/v3/coins/";
const currency = 'usd';
const order = "market_cap_desc";
const per_page = 10;
const page = 1;
const sparkline = false;
const price_change = "1h%2C24h%2C7d";
const price_change_percentage = '7d%2C%2024h';

const generateUrlFromIds = (ids) => {
    let allIds = ids.join('%2C%20');

    return `${coingeckoApiEndpoint}markets?vs_currency=${currency}&ids=${allIds}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change_percentage}`;
}

const generateCryptoRanksUrl = (page) => {
    return `${coingeckoApiEndpoint}/markets?vs_currency=${currency}&order=${order}&per_page=${per_page}&page=${page}&sparkline=${sparkline}&price_change_percentage=${price_change}`;
}

const getCryptosById = (cryptoArr) => {
    return httpService.get(generateUrlFromIds(cryptoArr))
}

const getCryptoRankList = (page) => {
    return httpService.get(generateCryptoRanksUrl(page))
}

export {
    getCryptosById,
    getCryptoRankList,
}