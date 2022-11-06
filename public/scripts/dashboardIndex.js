let lineChart = null;
let pieChart = null;

let durationSelect = document.getElementById('durationSelect');
let toggleChartSource = document.getElementById('toggleChartSource');

durationSelect.addEventListener("change", function () {
    ajaxLineGraphValues(durationSelect.value);
});

function toggle(current) {
    if (current === 'Category') {
        return 'Products';
    }
    if (current === 'Products') {
        return 'Category';
    }
}

toggleChartSource.addEventListener('click', function () {
    let source = document.getElementById('pieChartSource')
    let current = source.textContent;

    source.innerText = toggle(current);
    ajaxPieChartValues(toggle(current));
})

$(document).ready(function () {
    ajaxLineGraphValues();
    ajaxPieChartValues();
});

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
            labels = [2020, 2021, 2022, 2023];                         //need to fix this
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
                },
                {
                    label: 'Sales',
                    data: extractData(result[salesKey], ['day']),
                    fill: false,
                    borderColor: colorArray(),
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
                hoverOffset: 4,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'right',
                },
            },
        }
    })

}


