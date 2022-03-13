
const getIdsFromArray = (cryptos) => {
    return cryptos.map(crypto => crypto.cg_id);
}

export default getIdsFromArray