@extends('layouts.noob_layout')

@section('title', "Categories")

@section('activeCategories', 'active-this')

@section('child-page-title', "Categories")

@section('search-bar')
    <x-search-bar :searchRoute="route('categories.search')" :searchKeyword="$searchKeyword??''" placeholder="Category">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('categories.create')" entity="categories">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('categories.trashed')" entity="categories">
    </x-view-trashed-entity-btn>
@endsection

@section('search-bar')
    <x-search-bar :searchRoute="route('products.search')" :searchKeyword="$searchKeyword??''" placeholder="product">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('products.create')" entity="products">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('products.trashed')" entity="products">
    </x-view-trashed-entity-btn>
@endsection

@section('content')

    <div class="row p-4 pt-0">
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
                        <div class="col d-xl-block d-none">P<span class="d-lg-inline d-none">urc.</span>
                            @
                        </div>
                        <div class="col d-xl-block d-none">S<span class="d-lg-inline d-none">ell.</span>
                            @
                        </div>
                        <div class="col">Action</div>
                    </div>

                    @forelse($category->products as $product)
                        <div class="list-group-item row mx-0 d-flex justify-content-between">
                            <div class="col">{{$product->name}}</div>
                            <div class="col">{{$product->quantity}}</div>
                            <div class="col d-xl-block d-none">
                                Rs.{{$product->latestPurchasePrice->value}}</div>
                            <div class="col d-xl-block d-none">
                                Rs.{{$product->latestSalesPrice->value}}</div>
                            <div class="col">
                                <a href="{{route('products.show', ['product'=>$product])}}"
                                   class="btn btn-sm btn-outline-primary rounded-0"><i
                                        class="fa-solid fa-eye px-3"></i></a>
                            </div>
                        </div>
                    @empty

                    @endforelse
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

