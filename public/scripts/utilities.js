//Utilities
document.onLoad = removeAlert();

function removeAlert() {
    setTimeout(function () {
        let alerts = document.getElementsByClassName('alert');
        for (let i = 0; i < alerts.length; i++) {
            alerts[i].remove()
        }
    }, 3000);

}

function extractData(result, skipables = false) {
    let arr = [];
    for (let key in result) {
        if (!skipables.includes(key)) {
            if (typeof result[key] === 'object') {
                let extracted = extractData(result[key], skipables);
                arr.push(extracted)
            } else {
                arr.push(result[key]);
            }
        }
    }
    return arr.flat();
}

function extractLabels(result, skipables = false) {
    let arr = [];
    for (let key in result) {
        if (!skipables.includes(key)) {
            if (typeof result[key] === 'object') {
                let extracted = extractLabels(result[key], skipables);
                arr.push(extracted)
            } else {
                arr.push(camelToSentence(key));
            }
        }
    }
    return arr.flat();
}

function colorArray(opacity = 0.6, iteration = 15) {
    let returnArr = [];
    for (let i = 0; i < iteration; i++) {
        let r = Math.floor(Math.random() * 256);
        let g = Math.floor(Math.random() * 256);
        let b = Math.floor(Math.random() * 256);
        returnArr.push(`rgba(${r}, ${g}, ${b}, ${opacity})`);
    }
    return returnArr;

}

function capitalizeWord(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function camelToSentence(string) {
    return string.replace(/([A-Z])/g, ' $1').replace(/^./, (str) => str.toUpperCase())
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function changeObjectNullToZero(result) {
    Object.keys(result).forEach(function (key) {
        if (result[key] === null) {
            result[key] = '0';
        }
    })
    return result;
}

function setToBaseURL(url) {
    url = url.split('/');
    for (let i = 0; i < url.length; i++) {
        if (url.length > max - 1) {
            url.pop();
        }
    }
    return url.join('/');
}

function extractQueryIfExists(currentUrlArray) {
    let size = currentUrlArray.length
    let query = '';
    for (let i = 0; i < size; i++) {
        if (currentUrlArray[i].includes("?page")) {
            let startIndex = currentUrlArray[i].indexOf("?");
            let endIndex = currentUrlArray[i].length;
            query = currentUrlArray[i].substring(startIndex, endIndex)
            currentUrlArray[i] = currentUrlArray[i].substring(0, startIndex)
        }
    }
    currentUrlArray[size - 1] = currentUrlArray[size - 1] + query;

    return currentUrlArray;
}
