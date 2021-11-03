
 // Format price
 const formatPrice = function (value) {
            
    if (isNaN(value))
        return 0;
    
    if (value >= 0.01 || Math.sign(value) == -1)
        return parseFloat(value.toFixed(2));

    return value;
};


export { formatPrice }