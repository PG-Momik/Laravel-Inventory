let purchasePriceLineChart = null;
let salesPriceLineChart = null;

let lookBackDays = document.getElementById('lookBackDays');
let lookBackBtn = document.getElementById('lookBackBtn');

lookBackDays.addEventListener("keypress", function (event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        lookBackBtn.click();
    }
});

lookBackBtn.addEventListener('click', function () {
    ajaxLineGraphValues('purchase', lookBackDays.value);
    ajaxLineGraphValues('sales', lookBackDays.value);
})

$(document).ready(function () {
    ajaxLineGraphValues('purchase', lookBackDays.value);
    ajaxLineGraphValues('sales', lookBackDays.value);
});

function ajaxLineGraphValues(type, days = '') {
    let url = `/product-${type}-prices-line-graph/${days}`
    $.ajax({
        url: url,
        success: function (result) {
            type === 'purchase' ? drawPurchaseLineGraph(result) : drawSalesLineGraph(result)
        }
    });
}

function drawPurchaseLineGraph(result) {
    const ctx = document.getElementById("myPurchaseGraph").getContext('2d');
    let days = lookBackDays.value;

    if (lookBackDays.value.length === 0) {
        days = "7"
    }

    if (purchasePriceLineChart != null) {
        purchasePriceLineChart.destroy();
    }
    purchasePriceLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: extractLabels(result, []),
            datasets: [
                {
                    label: 'Purchase Prices',
                    data: extractData(result, []),
                    borderColor: colorArray(),
                    fill: true,
                }
            ]
        },
        options: {
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Price'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: `Purchase instances in ${days} days`
                    }
                }
            }
        }
    })
}

function drawSalesLineGraph(result) {
    const ctx = document.getElementById("mySalesGraph").getContext('2d');
    let days = lookBackDays.value;

    if (lookBackDays.value.length === 0) {
        days = "7"
    }

    if (salesPriceLineChart != null) {
        salesPriceLineChart.destroy();
    }
    salesPriceLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: extractLabels(result, []),
            datasets: [
                {
                    label: 'Sales Prices',
                    data: extractData(result, []),
                    fill: true,
                    borderColor: colorArray(),
                }
            ]
        },
        options: {
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Price'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: `Sales instances in ${days} days`
                    }
                }
            }
        }
    })
}

