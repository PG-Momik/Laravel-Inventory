@extends('layouts.main')
@section('title', 'Products')
@section('activeProducts', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 440px" class="a bg-purple round-this border-black">
                <div class="bg-purple px-5 pt-3 pb-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                            <h1>Products</h1>
                        </div>

                        {{--Search Form--}}
                        <form action="{{route('products.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <input type="search" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search product"
                                       value="{{$searchKeyword??''}}"
                                       style="max-height: 50px">
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-4 col-4 row mx-0 justify-content-center">
                                <button type="submit" class="btn bg-outline-dark round-button m-1 height-40 width-40">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button type="reset" class="btn bg-outline-white round-button m-1 height-40 width-40">
                                    <i class="fa-sharp fa-solid fa-rotate-left"></i>
                                </button>
                            </div>

                        </form>

                    </div>

                    {{--Button Group--}}
                    <div class="row mx-0 d-flex gx-5">
                        <div class="col-xl-4 col-lg-6 row mx-0">
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('products.create')}}" class="no-underline">
                                    <button class="btn btn-md bg-blue text-white col-12 round-this">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('products.trashed')}}" class="no-underline">
                                    <button class="btn btn-md-3 bg-yellow text-white col-12 round-this">
                                        <i class="fa-solid fa-trash"></i> Trashed
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>


                {{--White card goes here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">

                            {{--Pagination--}}
                            <div class="row  text-dark">
                                <div class="col-lg-11">
                                    {{$products->links("pagination::bootstrap-5")}}
                                </div>

                                <div class="col-lg-1 text-end">
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
                            </div>

                            {{--Table--}}
                            <table class="table table-hover table-md">

                                {{ alert() }}

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
                    </div>
                </div>

            </div>
        </div>

    </div>
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

    <script>
        /* When the user clicks on the button,
        toggle between hiding and showing the dropdown content */
        dropdownBtnIcon = document.getElementById('dropdownBtnIcon')
        dropdownBtnIcon.addEventListener('click', () => myFunction());

        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks out-side of it
        window.onclick = function (event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>

    <script defer>
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
@endsection
