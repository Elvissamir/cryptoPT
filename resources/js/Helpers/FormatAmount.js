
import { formatToDecimal } from './FormatToDecimal'

const formatAmount = function (value) {

    if (isNaN(value))
        return 0;

    if ((value % 1) == 0)
        return Math.trunc(value);
    
    return formatToDecimal(value);
}

export { formatAmount }