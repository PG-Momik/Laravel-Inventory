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

    <style>
        .red {
            background: rgb(82, 126, 82);
        }

        .red-text {
            color: rgb(255, 161, 207);
        }
    </style>

    @push('other-scripts')
        <script src="{{asset('scripts/dashboardIndex.js')}}"></script>
    @endpush
@endsection
