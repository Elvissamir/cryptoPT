
 // Format the number to show in the view
 const formatNumber = function (value) {
            
    if (isNaN(value))
        return 0;

    if ((value % 1) != 0) {

        let string = value.toString().split(".")[1];

        if (string.length > 2)
            return Math.round(value * 100) / 100;
    }

    return Math.trunc(value);
};


export { formatNumber }