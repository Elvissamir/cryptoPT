
const clearZeros = function (str) {

    let i = 0;

    for (i = str.length - 1; i > 1; i--) {
        if (str[i] != 0)
            break;
    }

    return str.slice(0, i + 1);
}

export { clearZeros }