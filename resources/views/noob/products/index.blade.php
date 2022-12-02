@extends('layouts.noob_layout')

@section('title', "Products")

@section('activeProducts', 'active-this')

@section('child-page-title', "Products")

@section('search-bar')
    <x-search-bar :searchRoute="route('products.search')" :searchKeyword="$searchKeyword??''" placeholder="Product">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('products.create')" entity="products">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('products.trashed')" entity="products">
    </x-view-trashed-entity-btn>
@endsection

@section('child-pagination', $products->links("pagination::bootstrap-5"))

@section('content')
    {{--White card goes here--}}
    <div class="p-4 pt-0">
        @can('viewAny products')
            {{--FILTER--}}
            <div class="col-12 text-end">
                <div class="dropdown text-start">
                    <button type="button"
                            class="btn btn-outline-primary rounded-0 mb-2 dropbtn"
                            title="Filter"
                            onclick="myFunction()">
                        <i class="fa-solid fa-sliders fs-5"></i>
                    </button>

                    <div id="myDropdown" class="row dropdown-content py-3 shadow-4">
                        <form action="" class="strict-324" id="filterForm">
                            <span><strong>Filter</strong></span>
                            <hr>

                            <div class="my-2">
                                <span>By start date:</span>
                                <div class="row m-0">
                                    <label class="mb-1 col-lg-4 col-12 p-0">
                                        <select name="" id="startYearField" class="form-select"
                                                autocomplete="off">
                                            <option value="0">Year</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                        </select>
                                    </label>

                                    <label class="mb-1 col-lg-4 col-12 p-0">
                                        <select name="" id="startMonthField" class="form-select"
                                                autocomplete="off">
                                            <option value="0">Month</option>
                                            <option value="1">Jan</option>
                                            <option value="2">Feb</option>
                                            <option value="3">Mar</option>
                                            <option value="4">Apr</option>
                                            <option value="5">May</option>
                                            <option value="6">Jun</option>
                                            <option value="7">Jul</option>
                                            <option value="8">Aug</option>
                                            <option value="9">Sep</option>
                                            <option value="10">Oct</option>
                                            <option value="11">Nov</option>
                                            <option value="12">Dec</option>
                                        </select>
                                    </label>
                                    <label class="mb-msg1 col-lg-4 col-12 p-0">
                                        <input type="number" id="startDayField" placeholder="Day"
                                               class="form-control"
                                               min="1" autocomplete="off">
                                    </label>
                                </div>
                            </div>

                            <div class="my-2">
                                <span>By end date:</span>
                                <div class="row m-0">
                                    <label class="mb-1 col-lg-4 col-12 p-0">
                                        <select name="" id="endYearField" class="form-select"
                                                autocomplete="off">
                                            <option value="0">Year</option>
                                            <option value="2021">2021</option>
                                            <option value="2022">2022</option>
                                        </select>
                                    </label>

                                    <label class="mb-1 col-lg-4 col-12 p-0">
                                        <select name="" id="endMonthField" class="form-select"
                                                autocomplete="off">
                                            <option value="0">Month</option>
                                            <option value="1">Jan</option>
                                            <option value="2">Feb</option>
                                            <option value="3">Mar</option>
                                            <option value="4">Apr</option>
                                            <option value="5">May</option>
                                            <option value="6">Jun</option>
                                            <option value="7">Jul</option>
                                            <option value="8">Aug</option>
                                            <option value="9">Sep</option>
                                            <option value="10">Oct</option>
                                            <option value="11">Nov</option>
                                            <option value="12">Dec</option>
                                        </select>
                                    </label>
                                    <label class="mb-msg1 col-lg-4 col-12 p-0">
                                        <input type="number" id="endDayField" placeholder="Day"
                                               class="form-control"
                                               min="1" autocomplete="off">
                                    </label>
                                </div>
                            </div>

                            <div class="my-2">
                                <label for="quantityField">By quantity:</label>
                                <input type="number" id='quantityField' placeholder="Quantity"
                                       class="form-control" autocomplete="off">
                            </div>

                            <div class="my-2">
                                <span>Category:</span>
                                <div class="row">
                                    @each('layouts.iterative.category_checkbox', $products, 'product')
                                </div>
                            </div>
                            <hr>

                        </form>
                    </div>
                </div>
            </div>
            {{--/FILTER--}}

            {{--Table--}}
            <div class="border-this">
                <table class="table table-hover table-responsive table-md">

                    <thead class="table-dark">
                    <tr>
                        <th>Name
                        <th>Category</th>
                        <th class="text-center">In stock</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody id="tableBody">
                    @each('layouts.iterative.product', $products, 'product', 'layouts.iterative.no_product')
                    </tbody>

                </table>
            </div>
            {{--/Table--}}
        @endCan
    </div>

    @push('other-scripts')
        <style>
            .strict-324 {
                width: 324px;
            }

            .dropdown {
                position: relative;
                display: inline-block;
            }

            .dropdown-content {
                display: none;
                position: absolute;
                background-color: #c0c0c0;
                min-width: 160px;
                overflow: auto;
                box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
                right: 0;
                z-index: 1;
            }

            .dropdown-content a {
                color: black;
                padding: 12px 16px;
                text-decoration: none;
                display: block;
            }

            .dropdown a:hover {
                background-color: #ddd;
            }

            .show {
                display: block;
            }
        </style>
        <script defer>
            /* When the user clicks on the button,
            toggle between hiding and showing the dropdown content */
            function myFunction() {
                document.getElementById("myDropdown").classList.toggle("show");
            }

            // Close the dropdown if the user clicks out-side of it
            window.onclick = function (event) {
                if (!event.target.matches('.dropbtn')) {
                    const dropdowns = document.getElementsByClassName("dropdown-content");
                    let i;
                    for (i = 0; i < dropdowns.length; i++) {
                        const openDropdown = dropdowns[i];
                        if (openDropdown.classList.contains('show')) {
                            openDropdown.classList.remove('show');
                        }
                    }
                }
            }

            filterForm = document.getElementById('filterForm');
            filterForm.addEventListener('change', function () {
                const startYear = document.getElementById('startYearField').value || 0;
                const startMonth = document.getElementById('startMonthField').value || 0;
                const startDay = document.getElementById('startDayField').value || 0;
                const endYear = document.getElementById('endYearField').value || 0;
                const endMonth = document.getElementById('endMonthField').value || 0;
                const endDay = document.getElementById('endDayField').value || 0;
                const quantity = document.getElementById('quantityField').value || 0;
                const checkedCategories = document.getElementsByClassName('categoryCheckbox');
                let category_ids = [];

                for (let i = 0; i < checkedCategories.length; i++) {
                    if (checkedCategories[i].checked)
                        category_ids.push(checkedCategories[i].value);
                }
                let filterParams = {startYear, startMonth, startDay, endYear, endMonth, endDay, quantity, category_ids};
                filterProducts(filterParams)
            })

            function filterProducts(filterParams) {
                let _token = "{{csrf_token()}}";
                $.ajax({
                    type: 'post',
                    url: '{{route('filterProducts')}}',
                    data: {
                        '_token': _token,
                        'filterParams': filterParams
                    },
                    success: function (data) {
                        if (data) {
                            let tableBody = document.getElementById('tableBody');
                            tableBody.innerHTML = '';
                            for (let i = 0; i < data.length; i++) {
                                tableBody.innerHTML = tableBody.innerHTML + `
                                    <tr>
                                        <td>${data[i].name}</td>
                                        <td>

                                            <a href="">${data[i].category.name}</a>
                                        </td>
                                        <td class="text-center">${data[i].quantity}</td>
                                        <td class="d-flex text-center" style="column-gap: 0.8vw">
                                            <a href=""
                                               class="col btn btn-sm btn-outline-primary rounded-0 px-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="" class="col no-underline">
                                                <form action=""
                                                      method="post">
                                                    @csrf
                                @method('delete')
                                <button
                                    class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 ">
                                    <i class="fa-solid fa-trash"></i>
                                </button>

                            </form>
                        </a>
                    </td>
                </tr>
`;
                            }
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection


