
 // Format the number to show in the view
 const formatNumber = function (value) {
            
    if (isNaN(value))
        return 0;

    if ((value % 1) != 0)
        if (value.toString().split(".")[1].length > 2)
            return Math.round(value * 100) / 100;
    return value;
};


export { formatNumber }