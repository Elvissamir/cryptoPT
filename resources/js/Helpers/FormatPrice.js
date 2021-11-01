
 // Format price
 const formatPrice = function (value) {
            
    if (isNaN(value))
        return 0;
    
    if (value >= 0.01)
        return parseFloat(value.toFixed(2));

    return value;
};


export { formatPrice }