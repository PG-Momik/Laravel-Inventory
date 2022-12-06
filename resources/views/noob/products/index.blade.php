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
            <div class="col-12 text-end mb-2">
                <div class="dropdown dropstart">
                    <button class="btn btn-outline-primary dropdown" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" title="Filter">
                        <i class="fa-solid fa-sliders fs-5"></i>
                    </button>

                    <form action="" id="filterForm">
                        <ul class="dropdown-menu dropdown-menu-dark p-3" style="width: 400px">
                            <li>
                                <span for="quantityField">Quantity (>=):</span>
                                <input type="number" id="quantityField" class="form-control"
                                       placeholder="Greater or equal quantity" autocomplete="off" min="0">
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <span>Category:</span>
                                <div class="row">
                                    @each('layouts.iterative.category_checkbox', $categories, 'category')
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <span>Start date:</span>
                                <input type="date" id="startDateField" class="form-control" autocomplete="off">
                            </li>
                            <li>
                                <span>End date:</span>
                                <input type="date" id="endDateField" class="form-control" autocomplete="off">
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        </ul>
                    </form>
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
            let filterForm = document.getElementById('filterForm');

            filterForm.addEventListener('keyup', function () {
                const values = getFieldValues();
                filterProducts(values);
            })
            filterForm.addEventListener('change', function () {
                const values = getFieldValues();
                filterProducts(values);
            })

            function getFieldValues() {
                const startDate = document.getElementById('startDateField').value || 0;
                const endDate = document.getElementById('endDateField').value || 0;
                const quantity = document.getElementById('quantityField').value || 0;
                const checkedCategories = document.getElementsByClassName('categoryCheckbox');
                let category_ids = [];

                for (let i = 0; i < checkedCategories.length; i++) {
                    if (checkedCategories[i].checked)
                        category_ids.push(checkedCategories[i].value);
                }

                return {startDate, endDate, quantity, category_ids};
            }

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
                                            <a href="../categories/${data[i].category_id}">${data[i].category.name}</a>
                                        </td>
                                        <td class="text-center">${data[i].quantity}</td>
                                        <td class="d-flex text-center" style="column-gap: 0.8vw">
                                            <a href="../products/${data[i].id}"
                                               class="col btn btn-sm btn-outline-primary rounded-0 px-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="" class="col no-underline">
                                                <form action="../products/${data[i].id}"
                                                      method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 ">
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
            {{--function generateProductRow(data) {--}}
            {{--    let tRow = document.createElement('tr');--}}
            {{--    let col1 = generateCol(data.name);--}}
            {{--    let col2 = generateCol(data.category.name, ['text-center']);--}}
            {{--    let col3 = generateCol(data.quantity)--}}
            {{--    let col4 = generateCol(generateActions(data, 'products'));--}}
            {{--    tRow.appendChild(col1);--}}
            {{--    tRow.appendChild(col2);--}}
            {{--    tRow.appendChild(col3);--}}
            {{--    tRow.appendChild(col4);--}}

            {{--}--}}

            {{--function generateCol(colData, classArray = []) {--}}
            {{--    let column = document.createElement('td');--}}
            {{--    for (let i = 0; i < classArray.length; i++) {--}}
            {{--        column.classList.add(classArray[i]);--}}
            {{--    }--}}
            {{--    column.innerHTML = colData;--}}
            {{--    return column;--}}
            {{--}--}}
            {{--function generateActions(data, object){--}}
            {{--    return generateViewAction(data, object) + generateDeleteAction(data, object);--}}
            {{--}--}}

            {{--function generateViewAction(data, object){--}}
            {{--    return  "<a href='' class='col btn btn-sm btn-outline-primary rounded-0 px-2'><i class='fa-solid fa-eye'></i></a>";--}}
            {{--}--}}
            {{--function generateDeleteAction(data, object){--}}
            {{--    return "<a href='' class='col no-underline'><form action='' method='post'> @csrf @method('delete') <button class='btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12'><i class='fa-solid fa-trash'></i></button></form></a>"--}}
            {{--}--}}
        </script>
    @endpush
@endsection


