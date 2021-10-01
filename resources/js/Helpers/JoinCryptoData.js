
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
    
    if (options.atl_date)
        crypto['atl_date'] = cgData.atl_date.slice(0, 10);

    if (options.ath)
        crypto['ath'] = formatNumber(cgData.ath);

    if (options.ath_date)
        crypto['ath_date'] = cgData.ath_date.slice(0, 10);

    if (options.rank)
        crypto['rank'] = cgData.market_cap_rank;
    
    if (options.circulating_supply)
        crypto['circulating_supply'] = cgData.circulating_supply;
    
    if (options.max_supply)
        crypto['max_supply'] = cgData.max_supply;

    if (options.high_24h)
        crypto['high_24h'] = cgData.high_24h;

    if (options.low_24h)
        crypto['low_24h'] = cgData.low_24h;

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