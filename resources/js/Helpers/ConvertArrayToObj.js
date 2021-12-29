
const convertArrayToObj = (arr) => {
    
    const dataObj = {};
    
    arr.forEach(item => {
        dataObj[item.cg_id] = item; 
    });

    return dataObj;
}

export {
    convertArrayToObj,
}