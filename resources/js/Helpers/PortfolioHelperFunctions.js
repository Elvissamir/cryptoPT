
import { formatPercentage } from './FormatPercentage';
import { formatPrice } from './FormatPrice'

const calculateTotalWorth = (cryptosData) => { 
    return formatPrice(cryptosData.reduce((total, crypto) => {
        return total + (crypto.price * crypto.amount)
    }, 0));
}

const calculateGrowth = (cryptosData) => {
    return formatPrice(cryptosData.reduce((growth, crypto) => {
        return growth + (crypto.price_change_24h * crypto.amount);
    }, 0));
}

const calculateGrowthPercentage = (portfolioTotalWorth, portfolioGrowth) => {
    return formatPercentage((portfolioGrowth / (portfolioTotalWorth - portfolioGrowth)) * 100);
}

const calculateCryptoDistribution = (cryptoData, portfolioTotalWorth) => {

    let distribution = {
        percentages: [],
        cryptos: [],
    }

    let topDistribution = {
        percentages: [],
        cryptos: [],
    }

    let max = (cryptoData.length > 5)? 5 : cryptoData.length;
    
    cryptoData.forEach(crypto => {
        distribution.percentages.push(formatPrice(((crypto.price * crypto.amount) /  portfolioTotalWorth) * 100));
        distribution.cryptos.push(crypto.symbol.toUpperCase());
    });

    topDistribution.percentages = distribution.percentages.map(percentage => percentage)
                                                          .sort((a, b) => b - a)
                                                          .slice(0, max);

    for (let x = 0; x < topDistribution.percentages.length; x++) {
        for (let y = 0; y < distribution.percentages.length; y++) {

            if (distribution.percentages[y] == topDistribution.percentages[x]) {
                topDistribution.cryptos.push(distribution.cryptos[y]);
                break;
            }
        }
    }

    return topDistribution;
}

const calculateTopCryptos = (cryptoData) => {

    let top = {
        percentages: [],
        cryptos: [],
    };

    let max = (cryptoData.length > 5)? 5 : cryptoData.length;

    top.percentages = cryptoData.map(crypto => crypto.price_change_percentage_7d)
                                      .sort((a, b) => b - a)
                                      .slice(0, max);

    for (let i = 0; i < max; i++) {
        for (const crypto of cryptoData) {

            if (crypto.price_change_percentage_7d == top.percentages[i]) {
                top.cryptos.push(crypto.symbol.toUpperCase());
                break;
            }
        }
    }

    return top;
}


export {
    calculateTotalWorth,
    calculateGrowth,
    calculateGrowthPercentage,
    calculateCryptoDistribution,
    calculateTopCryptos,
}