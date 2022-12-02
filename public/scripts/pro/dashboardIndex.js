/** --------------------FOR DASHBOARD INITIAL PAGE LOAD-------------------- */

let lineChart = null;
let pieChart = null;
let myLineChart = null;

let durationSelect = document.getElementById('durationSelect');
let pieChartSelect = document.getElementById('pieChartSelect');

$(document).ready(function () {
    ajaxLineGraphValues();
    ajaxPieChartValues();
    ajaxMyAnnualTransactionValues();
});

durationSelect.addEventListener("change", function () {
    ajaxLineGraphValues(durationSelect.value);
});

pieChartSelect.addEventListener('change', function () {
    console.log(pieChartSelect.value);
    ajaxPieChartValues(toggle(toggle(pieChartSelect.value)));
})

function expandCard(index) {
    ajaxCallForCard(index)
}

function fillCard(index, result) {
    removeAjaxedCard()

    if (index === 2 || index === 3) {
        /** Ajax response for card 2 and 3 are nested 1 lever deeper in object */
        let childKey = Object.keys(result)
        childKey = childKey.toString();
        result = result[childKey]
    }

    let size = Object.keys(result).length;
    let keys = Object.keys(result);
    let iconClasses = ['fa-arrow-down', 'fa-arrow-up'];
    let colorClasses = ['text-success', 'text-danger'];
    let space = document.getElementsByClassName('card-value')[index];
    let parentSpan = document.createElement('span');

    parentSpan.classList.add('ajaxed-card');
    parentSpan.setAttribute('style', 'border-left: 4px solid #39adf5');

    for (let i = 0; i < size; i++) {
        let url = buildURL(index, i, keys[i]);
        let anchorTag = document.createElement('a');

        anchorTag.setAttribute("href", `${url}`);
        anchorTag.classList.add("mx-2", 'fs-5', colorClasses[i]);
        anchorTag.innerHTML = `<i class='fa-solid ${iconClasses[i]} mx-2'></i>`
        anchorTag.innerHTML += Number(result[keys[i]]).toFixed(2);

        parentSpan.appendChild(anchorTag);
    }
    space.appendChild(parentSpan);


    function buildURL(index, i, key) {
        let transactionType = ['purchase', 'sale'];
        let url = "transactions";
        if (index === 0) {
            url = `${url}/yesterday/${transactionType[i]}`
        } else if (index === 1) {
            url = `${url}/monthly/${transactionType[i]}`
        } else if (index === 2) {
            url = `${url}/year/${key}/${transactionType[0]}`
        } else if (index === 3) {
            url = `${url}/year/${key}/${transactionType[1]}`
        }

        return url;
    }
}

function toggle(current) {
    if (current === 'Category') {
        return 'Products';
    }
    if (current === 'Products') {
        return 'Category';
    }
}

function drawMyLineGraph(result) {
    if (myLineChart != null) {
        myLineChart.destroy();
    }

    const ctx = document.getElementById('myAnnualTransactionQuantity').getContext('2d');
    const labels = extractLabels(result['purchases'], ['sales']);
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Sales',
                data: extractData(result['sales'], ['purchases']),
                fill: true,
                borderColor: 'rgba(255,7,7,0.8)',
                backgroundColor: colorArray(0.2, 1),
                tension: 0.2
            },
            {
                label: 'Purchases',
                data: extractData(result['purchases'], ['sales']),
                fill: true,
                borderColor: "rgba(32,255,7,0.8)",
                backgroundColor: colorArray(0.2, 1),
                tension: 0.2
            },
        ],
    };
    myLineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            bezierCurve: true,
            scales: {
                y: {
                    ticks: {
                        display: false
                    },
                    grid: {
                        display: false
                    }
                },
                x: {
                    ticks: {
                        display: false
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    })

}

function revealMyTransactionQuantity(result) {
    let purchaseArr = extractData(result['purchases'], ['sales'])
    let salesArr = extractData(result['sales'], ['purchases'])
    let purchaseSum = purchaseArr.reduce((partialSum, a) => partialSum + Number(a), 0);
    let salesSum = salesArr.reduce((partialSum, a) => partialSum + Number(a), 0);
    counterAnimate('#myTransactionQuantity', 0, purchaseSum + salesSum, 800);
    counterAnimate('#myPurchaseQuantity', 0, purchaseSum, 800);
    counterAnimate('#mySalesQuantity', 0, salesSum, 800);
}

function ajaxLineGraphValues(type = '') {
    let url = `/dashboard/data-for-line-graph/${type}`
    $.ajax({
        url: url,
        success: function (result) {
            drawLineGraph(result, type);
        }

    });

}

function ajaxPieChartValues(type = '') {

    let url = `/dashboard/data-for-pie-chart/${type}`
    $.ajax({
        url: url,
        success: function (result) {
            drawPieChart(result, type);
        }

    })
}

function ajaxMyAnnualTransactionValues(year = '') {
    let url = `/dashboard/data-for-my-annual-transaction/${year}`
    $.ajax({
        url: url,
        success: function (result) {
            console.log(result);
            drawMyLineGraph(result);
            revealMyTransactionQuantity(result);
        }
    })
}

function ajaxCallForCard(i) {
    let url = `dashboard/detailed-data-for-card/${i}`;
    $.ajax({
        url: url,
        success: function (result) {
            fillCard(i, result)
        }
    });
}

function removeAjaxedCard() {
    const cards = document.querySelectorAll('.ajaxed-card');
    cards.forEach(card => {
        card.remove();
    });
}

function drawLineGraph(result, type = "month") {
    let purchaseKey, salesKey, labels;
    switch (type) {
        case 'annual':
            purchaseKey = 'monthlyPurchases';
            salesKey = 'monthlySales';
            labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            document.getElementById("durationLabel").textContent = "This years"
            break;
        case 'overall':
            purchaseKey = 'annualPurchases';
            salesKey = 'annualSales';
            labels = extractLabels(result, ['annualSales']);                         //need to fix this
            document.getElementById("durationLabel").textContent = "Overall"
            break;
        default:
            purchaseKey = 'dailyPurchases';
            salesKey = 'dailySales';
            labels = Array.from({length: 32}, (_, i) => i + 1)
            document.getElementById("durationLabel").textContent = "This months"
            break;
    }

    if (lineChart != null) {
        lineChart.destroy();
    }

    const ctx = document.getElementById('lineChart').getContext('2d');
    lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Purchases',
                    data: extractData(result[purchaseKey], ['day']),
                    fill: false,
                    borderColor: colorArray(),
                    tension: 0.1
                },
                {
                    label: 'Sales',
                    data: extractData(result[salesKey], ['day']),
                    fill: false,
                    borderColor: colorArray(),
                    tension: 0.1
                }
            ]
        },

    })

}

function drawPieChart(result, type) {

    result = changeObjectNullToZero(result);

    const ctx = document.getElementById('pieChart').getContext('2d');

    if (pieChart != null) {
        pieChart.destroy();
    }

    pieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: extractLabels(result, []),
            datasets: [{
                backgroundColor: colorArray(0.6, 30),
                data: extractData(result, []),
                hoverOffset: 10,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'null',
                },
            },
        }
    })

}

/** --------------------END DASHBOARD INITIAL-------------------- */
