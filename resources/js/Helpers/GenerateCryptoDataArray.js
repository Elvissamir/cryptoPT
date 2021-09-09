
import { joinCryptoData } from "./JoinCryptoData";
import { convertArrayToObj } from './ConvertArrayToObj';

const generateCryptoDataArray = (dbArr, cgArr, options) => {

    let cryptosArr = [];

    const dbData = convertArrayToObj(dbArr);

    cgArr.forEach(crypto => {
        cryptosArr.push(joinCryptoData(dbData, crypto, options));
    });

    return cryptosArr;
}

export {
    generateCryptoDataArray,
}