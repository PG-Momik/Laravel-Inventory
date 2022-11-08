@extends('layouts.main')
@section('title', $category->name)
@section('activeCategories', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a round-this border-black grad">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    {{-- <div class="col-xl-12 col-lg-6 col-md-4 col-sm-3 col-2"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur eaque nesciunt omnis porro possimus quis? Cupiditate dignissimos ipsa iste, iusto, non pariatur, possimus quasi quo reprehenderit sint suscipit veniam!</div>--}}

                    <div class="row mx-0 d-flex gx-5 align-items-center">

                        <div class="col-xl-4 col-lg-4 p-0">
                            <span
                                class="d-inline-flex nice-white-shadow px-4 align-items-end pb-1">
                                <a href="{{route('categories.index')}}" class="text-decoration-none">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Category</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <span class="fs-4 text-grey"><u>{{$category->name}}</u></span>
                            </span>
                        </div>

                    </div>
                </div>

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
                            <div class="row">
                                <div class="col-lg-6 col-12 px-2 ">
                                    <div class="d-block shadow p-3 rounded">
                                        <h1>Category: {{$category->name}}</h1>
                                        <hr>
                                        <div class="d-flex justify-content-center">
                                            <div class="strict-200">
                                                <canvas id="myChart" class=""></canvas>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" id="seeDetailed" class="btn fs-5"
                                                        onclick="ajaxDoughnutValue(true)">
                                                    <i class="fa-solid fa-eye"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-12 px-2 my-2">
                                    <div class="shadow p-3 rounded py-3">
                                        <h2>Products with this category:</h2>
                                        <hr>
                                        <div class="list-group">
                                            <div
                                                class="list-group-item row mx-0 d-flex justify-content-between bg-dark text-light">
                                                <div class="col">Name</div>
                                                <div class="col">Stock:</div>
                                                <div class="col d-xl-block d-none">
                                                    P<span class="d-lg-inline d-none">urc.</span>@
                                                </div>
                                                <div class="col d-xl-block d-none">
                                                    S<span class="d-lg-inline d-none">ell.</span>@
                                                </div>
                                                <div class="col">Action</div>
                                            </div>

                                            @each('layouts.iterative.product_in_category' , $category->products, 'product', 'no_product_in_category')

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

    <style>
        .strict-200 {
            width: 400px;
            height: 400px;
        }
    </style>

    @push('other-scripts')


        <script>

            let myChart = null;
            let toggleBtn = document.getElementById('seeDetailed');

            toggleBtn.addEventListener('click', function () {

                if (toggleBtn.getAttribute('onclick') === "ajaxDoughnutValue(true)") {
                    toggleBtn.setAttribute('onclick', "ajaxDoughnutValue()");
                    toggleBtn.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
                } else {
                    toggleBtn.setAttribute('onclick', "ajaxDoughnutValue(true)");
                    toggleBtn.innerHTML = '<i class="fa-solid fa-eye"></i>'

                }
            })

            $(document).ready(function () {
                ajaxDoughnutValue();
            });

            function ajaxDoughnutValue(detailed = false) {
                let url = "{{route('category-stats', ['id'=>$category->id])}}";

                if (detailed) {
                    url = "{{route('category-stats', ['id'=> $category->id, 'detailed'=>true])}}";
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (result) {
                        console.log(result);
                        drawDoughnut(result)

                    }
                })
            }

            function drawDoughnut(result) {

                if (myChart != null) {
                    myChart.destroy();
                }

                result = JSON.parse(result);
                const ctx = document.getElementById('myChart').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: extractLabels(result, []),
                        datasets: [{
                            backgroundColor: colorArray(),
                            borderColor: colorArray(1),
                            data: extractData(result, []),
                            hoverOffset: 16,
                        }]
                    },

                })
            }

        </script>

    @endpush

@endsection

