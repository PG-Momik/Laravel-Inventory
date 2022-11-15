/** --------------------FOR DASHBOARD INITIAL PAGE LOAD-------------------- */

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

/** --------------------END DASHBOARD INITIAL-------------------- */

/** --------------------FOR DASHBOARD CARD INTERACTION-------------------- */

let layoutTemplates = [
    [1, 2, 3, 4],
    [2, 1, 3, 4],
    [3, 4, 1, 2],
    [4, 3, 2, 1]
]
let expandCardBtn = document.getElementsByClassName('expand-card-btn');

for (let i = 0; i < expandCardBtn.length; i++) {
    expandCardBtn[i].addEventListener('click', function () {
        if (expandCardBtn[i].classList.contains('over')) {
            contract(expandCardBtn[i], i)
        } else {
            expand(expandCardBtn[i], i)
        }
    })
}

function spinArrow(arrow) {
    arrow.classList.toggle('over');
    arrow.classList.toggle('out');
    arrow.blur();
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

function expand(element, i) {
    resetLayout();

    let card = document.getElementById(`card-${i}`)
    card.classList.remove('col-lg-3')
    card.classList.remove('col-md-6')

    spinArrow(element);

    applyLayout(layoutTemplates[i], i)

    ajaxCallForCard(i);

}

function removeAjaxedCard() {
    const cards = document.querySelectorAll('.ajaxed-card');
    cards.forEach(card => {
        card.remove();
    });
}

function contract(element, i) {
    spinArrow(element);
    resetLayout();
}

function applyLayout(arr, i) {
    for (let j = 0; j < arr.length; j++) {
        document.getElementById(`card-${j}`).style.order = arr[j];
        if (j !== i) {
            let card = document.getElementById(`card-${j}`)
            card.classList.remove('col-lg-3')
            card.classList.remove('col-md-6')
            card.classList.add('col-lg-4');
        }
    }

}

function resetLayout() {
    removeAjaxedCard();
    let expandable = document.getElementsByClassName('expandable');
    for (let i = 0; i < expandable.length; i++) {
        let card = document.getElementById(`card-${i}`)
        let arrow = document.getElementsByClassName('expand-card-btn')[i];
        card.style.order = i + 1;
        card.setAttribute("class", "");
        card.classList.add("row", "mx-0",
            "col-12", "px-2",
            "expandable", "card-0",
            "col-lg-3", "col-md-6")
        arrow.classList.remove('over')
        arrow.classList.add('out')
    }
}

function fillCard(index, result) {

    if (index === 2 || index === 3) {
        /** Ajax response for card 2 and 3 are nested 1 lever deeper in object */
        let childKey = Object.keys(result)
        childKey = childKey.toString();
        result = result[childKey]
    }

    const size = Object.keys(result).length;
    const keys = Object.keys(result);
    const space = document.getElementById(`wrapper-${index}`);

    /** Create card for each value in result */
    for (let i = 0; i < size; i++) {
        let url = buildURL(index, i, keys[i]);
        let anchorTag = document.createElement('a');
        anchorTag.setAttribute("href", `${url}`);
        anchorTag.classList.add("text-center", "text-decoration-none",
            "col", "shadow-on-hover", "curvy-sides", "ajaxed-card", "mx-1");

        let div1 = document.createElement('div');
        div1.classList.add("fs-1", "text-secondary");
        /** Show no decimal points if index is greater than 1 */
        div1.textContent = index <= 1 ? Number(result[keys[i]]).toFixed(2) : result[keys[i]];

        let span1 = document.createElement('span');
        span1.classList.add("text-secondary");
        /** Add "Year : " prefix if index is greater than 1 */
        span1.textContent = index <= 1 ? camelToSentence(keys[i]) : "Year : " + camelToSentence(keys[i]);

        anchorTag.appendChild(div1);
        anchorTag.appendChild(span1);
        space.appendChild(anchorTag);
    }

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

/** --------------------END OF DASHBOARD CARD INTERACTION-------------------- */
