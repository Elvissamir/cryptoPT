

const priceColor = (value) => {
    if (Math.sign(value) >= 0)
        return 'text-green-400';
    return 'text-red-400';
}


export {
    priceColor,
}