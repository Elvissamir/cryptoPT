
import { clearZeros } from './ClearZeros'

const formatToDecimal = function (value) {

    let value_string = value.toString().split('.');
    let intPart = value_string[0];
    let decPart = clearZeros(value_string[1]);

    return parseFloat(`${intPart}.${decPart}`);
}

export { formatToDecimal }