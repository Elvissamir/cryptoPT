
import { addDbCryptoData } from './AddDbCryptoData'
import { formatNumber } from './FormatNumber'

const joinCryptoData = (dbData, cgData, options) => {

    const crypto = {
        cg_id: cgData.id,
        name: cgData.name, 
        image: cgData.image,
        symbol: cgData.symbol.toUpperCase(),
        url: `/cryptos/${cgData.id}`,
        price: formatNumber(cgData.current_price),
    }

    addDbCryptoData(dbData, crypto, options);

    if (options.atl)
        crypto['atl'] = formatNumber(cgData.atl);

    if (options.ath)
        crypto['ath'] = formatNumber(cgData.atl);

    if (options.rank)
        crypto['rank'] = cgData.market_cap_rank;

    if (options.price_change_1h)
        crypto['price_change_1h'] = formatNumber(cgData.price_change_percentage_1h_in_currency);

    if (options.price_change_24h)
        crypto['price_change_24h'] = formatNumber(cgData.price_change_percentage_24h_in_currency);

    if (options.price_change_7d)
        crypto['price_change_7d'] = formatNumber(cgData.price_change_percentage_7d_in_currency);

    if (options.price_change_percentage_24h)
        crypto['price_change_percentage_24h'] = formatNumber(cgData.price_change_percentage_24h_in_currency);

    if (options.price_change_percentage_7d)
        crypto['price_change_percentage_7d'] = formatNumber(cgData.price_change_percentage_7d_in_currency);
        
    return crypto;
}


export {
    joinCryptoData,
}