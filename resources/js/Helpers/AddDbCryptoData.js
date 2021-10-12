import { formatNumber } from "./FormatNumber";

const addDbCryptoData = (dbData, crypto, options) => {
    
    if (dbData[crypto.cg_id]) {
        if (dbData[crypto.cg_id].amount) {
        
            crypto['inPortfolio'] = true;
            crypto['amount'] = dbData[crypto.cg_id].amount;

            if (options.created_at)
                crypto['created_at'] = dbData[crypto.cg_id].created_at;
    
            if (options.total_worth)
                crypto['total_worth'] = formatNumber(dbData[crypto.cg_id].amount * crypto.price);
        }
    }
    else {
       crypto['inPortfolio'] = false;
    }
}

export {
    addDbCryptoData,
}