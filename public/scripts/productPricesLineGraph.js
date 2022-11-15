let lineChart = null;

let lookBackDays  = document.getElementById('lookBackDays');
let lookBackBtn  = document.getElementById('lookBackBtn');

lookBackBtn.addEventListener('click', function(){
    ajaxLineGraphValues(lookBackDays.value)
})

$(document).ready(function () {
    ajaxLineGraphValues();
});

function ajaxLineGraphValues(days = '') {
    let url = `/product-prices-line-graph/${days}`
    $.ajax({
        url: url,
        success: function (result) {
            drawLineGraph(result)
        }
    });

}

function drawLineGraph(result) {
    if (lineChart != null) {
        lineChart.destroy();
    }

    const ctx = document.getElementById('myGraph').getContext('2d');
    lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: extractLabels(result['purchases'], []) ,
            datasets: [
                {
                    label: 'Purchase Prices',
                    data: extractData(result, ['sales']),
                    fill: false,
                    borderColor: colorArray(),
                },
                {
                    label: 'Sale Prices',
                    data: extractData(result, ['purchases']),
                    fill: false,
                    borderColor: colorArray(),
                }
            ]
        },

    })

}
