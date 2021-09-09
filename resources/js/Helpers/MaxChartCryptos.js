
const maxCryptos = (cryptArr) => {
    return (cryptArr.length > 5)? 5 : cryptArr.length;
}

export {
    maxCryptos
}