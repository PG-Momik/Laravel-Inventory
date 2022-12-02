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

@section('child-pagination', $categories->links("pagination::bootstrap-5"))

@section('content')
    <div class="p-4 pt-0">
        <div class="table-responsive border-this">
            <table class="table table-hover table-md">

                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Created On</th>
                    <th>Products</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @each('layouts.iterative.category', $categories, 'category', 'layouts.iterative.no_category')
                </tbody>
            </table>
        </div>
    </div>
    {{--    --}}{{--White card goes here--}}

    {{--    --}}{{--Expandable Row--}}
    {{--    <div class="row mx-0 p-4" id="expandable-row">--}}
    {{--        @foreach($cardsValues  as $key=>$item)--}}
    {{--            <div id="card-{{$loop->index}}"--}}
    {{--                 class="row mx-0 col-lg-3 col-md-6 col-12 px-2 expandable card-{{$loop->index}}">--}}
    {{--                <div class="px-1 my-2 text-center shadow rounded-3 bg-light">--}}
    {{--                    <div>--}}
    {{--                        <div class="justify-content-start d-flex my-0 py-0">--}}
    {{--                            <button class="mx-0 btn link-secondary expand-card-btn px-2 out">--}}
    {{--                                <i class="fa-solid fa-arrow-right fs-5"></i>--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                        <div class="row mx-0 my-2" id="wrapper-{{$loop->index}}">--}}
    {{--                            <a href="{{$cardInitialRoutes[$loop->index]}}"--}}
    {{--                               class="text-center text-decoration-none col shadow-on-hover curvy-sides mx-1">--}}
    {{--                                <div class="fs-1 text-secondary">--}}
    {{--                                    {{gettype($item)=='integer'?$item:number_format($item, 2)}}--}}
    {{--                                </div>--}}
    {{--                                <span class="text-secondary">{{sentenceCase(ucwords($key))}}</span>--}}
    {{--                            </a>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            @php($loop->index++)--}}
    {{--        @endforeach--}}
    {{--    </div>--}}
    {{--    --}}{{--/Expandable Row--}}

    {{--    --}}{{--Charts--}}
    {{--    <div class="px-4">--}}
    {{--        <div class="row mx-0">--}}

    {{--            --}}{{--Line graph--}}
    {{--            <div class="col-lg-8 col-12 p-3">--}}
    {{--                <div class="round-3 shadow p-3 bg-light">--}}
    {{--                    <div class="d-md-flex d-block justify-content-md-between my-2 align-middle">--}}
    {{--                        <div class="fs-5 text-wrap">--}}
    {{--                            <span id="durationLabel">This months</span> transactions--}}
    {{--                        </div>--}}
    {{--                        <div class="text-center">--}}
    {{--                            <label for="durationSelect">View By</label>--}}
    {{--                            <select name="durationSelect" id="durationSelect"--}}
    {{--                                    class="form-select"--}}
    {{--                                    autocomplete="off">--}}
    {{--                                <option value="month">This Month</option>--}}
    {{--                                <option value="annual">Annual</option>--}}
    {{--                                <option value="overall">Overall</option>--}}
    {{--                            </select>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="">--}}
    {{--                        <canvas id="lineChart"></canvas>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            --}}{{--/Line graph--}}

    {{--            --}}{{--Pie Chart--}}
    {{--            <div class="col-lg-4 col-12">--}}
    {{--                <div class="">--}}
    {{--                    <div class="d-flex justify-content-between my-2 py-3">--}}
    {{--                        <span class="fs-5">In stock :--}}
    {{--                            <span id="pieChartSource">Category</span>--}}
    {{--                        </span>--}}
    {{--                        <span class="justify-text-middle pt-2">--}}
    {{--                            <button class="btn" id="toggleChartSource">--}}
    {{--                                <i class="fa-solid fs-5 fa-arrow-right-arrow-left"></i>--}}
    {{--                            </button>--}}
    {{--                        </span>--}}
    {{--                    </div>--}}
    {{--                    <div class="w-100 px-3 border border-danger" style="max-width: 400px">--}}
    {{--                        <canvas id="pieChart"></canvas>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            --}}{{--/Pie Chart--}}

    {{--        </div>--}}
    {{--    </div>--}}
    {{--    --}}{{--/Charts--}}

    {{--    --}}{{--White card ends here--}}
@endsection



