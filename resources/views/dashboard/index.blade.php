@extends('layouts.main')

@section('title', "Dashboard")

@section('activeDashboard', 'active-this')

@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a grad round-this border-black">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">
                        <div class="col-xl-4 col-lg-4">
                            <h1>Dashboard</h1>
                        </div>
                    </div>

                </div>

                {{--White card goes here--}}
                <div class="b-grad p-5 round-this">
                    <div class="bg-sm-grey round-this">

                        <div class="row mx-0 px-4 py-5">
                            @foreach($cardsValues  as $key=>$item)
                                <div class="col-lg-3 col-md-6 col-12 px-2">
                                    <div class="py-4 px-1 my-1 text-center shadow rounded-3  bg-white">
                                        <div
                                            class="fs-1 text-secondary">{{gettype($item)=='integer'?$item:number_format($item, 2)}}</div>
                                        <span class="text-secondary">{{sentenceCase(ucwords($key))}}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="px-4">
                            <div class="row mx-0">

                                {{--Line graph--}}
                                <div class="col-md-8 col-12 p-3">
                                    <div class="round-3 shadow p-3 bg-light">
                                        <div class="d-flex justify-content-between my-2 align-middle">
                                            <div class="fs-4"><span id="durationLabel">This months</span> transactions
                                            </div>
                                            <div class="text-center">
                                                <label for="durationSelect">View By</label>
                                                <select name="durationSelect" id="durationSelect" class="form-select"
                                                        autocomplete="off">
                                                    <option value="month">This Month</option>
                                                    <option value="annual">Annual</option>
                                                    <option value="overall">Overall</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="">
                                            <canvas id="lineChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                {{--Pie Chart--}}
                                <div class="col-md-4 col-12">
                                    <div class="">
                                        <div class="d-flex justify-content-between my-2 py-3">
                                            <span class="fs-4">In stock : <span
                                                    id="pieChartSource">Category</span></span>
                                            <span class="justify-text-middle pt-2">
                                                <button class="btn" id="toggleChartSource">
                                                    <i class="fa-solid fs-5 fa-arrow-right-arrow-left"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="w-100 px-3 border border-danger">
                                            <canvas id="pieChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
            integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
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


        //Utilities

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
                        arr.push(key);
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


    </script>
    <style>
        .red {
            background: rgb(82, 126, 82);
        }

        .red-text {
            color: rgb(255, 161, 207);
        }
    </style>
@endsection
