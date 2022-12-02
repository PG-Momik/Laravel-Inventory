@extends('layouts.pro_layout')

@section('title', "Dashboard")
@section('activeDashboard', 'active')

@section('container-content')

    <div class="row">
        <div class="col-lg-4 col-12 order-1">
            <div class="row">
                @php($i=0)
                @foreach($cardsValues  as $key=>$item)
                    {{--<a href="{{$cardInitialRoutes[$i]}}"--}}

                    <div id="" class="col-12 col-mb-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold d-block mb-1">
                                        {{sentenceCase(ucwords($key))}}
                                    </span>
                                    <span class="dropdown">
                                        <button class="btn p-0" type="button"
                                                id="orederStatistics"
                                                data-bs-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                            <a class="dropdown-item" href="{{$cardInitialRoutes[$i]}}">
                                                View
                                            </a>
                                            <a class="dropdown-item" href="javascript:expandCard({{$i}});">
                                                Expand
                                            </a>
                                            <a class="dropdown-item" href="javascript:alert('Work in progress');">
                                                Share
                                            </a>
                                        </div>
                                    </span>
                                </div>


                                <h3 class="card-title card-value d-flex justify-content-between pt-3">
                                    <span class="">
                                        {{gettype($item)=='integer'?$item:number_format($item, 2)}}
                                        <span class="fs-6 text-muted">units</span>
                                    </span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    @php($i++)
                @endforeach
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4 justify-content-between">
            <div class="card mb-4">
                <div class="row mx-0 my-2 row-bordered">
                    <!-- Line graph container -->
                    <div class="col-md-8">
                        <div class="card-header d-flex justify-content-between align-middle py-2 mx-1">
                            <div class="fs-4"><span id="durationLabel">This months</span> transactions
                            </div>
                            <div class="text-center">
                                <label for="durationSelect">View By</label>
                                <select name="durationSelect" id="durationSelect"
                                        class="btn btn-sm btn-outline-primary dropdown-toggle"
                                        autocomplete="off">
                                    <option value="month">This Month</option>
                                    <option value="annual">Annual</option>
                                    <option value="overall">Overall</option>
                                </select>
                            </div>
                        </div>
                        <!-- Line graph goes here -->

                        <div class="px-2">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>

                    <!-- Pie chart container -->
                    <div class="col-md-4">
                        <div class="card-body">
                            <div class="text-center d-flex justify-content-between">
                                <label for="pieChartSelect">In Stock</label>
                                <div class="dropdown">
                                    <select name="" id="pieChartSelect"
                                            class="btn btn-sm btn-outline-primary dropdown-toggle">
                                        <option value="Category">Categories</option>
                                        <option value="Products">Products</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart goes here -->
                        <div class="">
                            <div class="w-100 px-4">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="d-flex flex-sm-row flex-column gap-3">

                                <div class="col-2 text-center">

                                    <p class="text-nowrap mb-2 fw-semibold text-dark">
                                        <u>You handled</u>
                                    </p>

                                    <div class="count fw-bold text-center w-100 fs-4 text-danger">
                                        <i class="fa-solid fa-arrow-up mx-1"></i> <span id="mySalesQuantity"></span>
                                    </div>
                                    <p class="text-nowrap mb-2 fw-semibold ">
                                        Sales
                                    </p>

                                    <div class="count fw-bold text-center w-100 fs-4 text-success">
                                        <i class="fa-solid fa-arrow-down mx-1"></i> <span
                                            id="myPurchaseQuantity"></span>
                                    </div>
                                    <p class="text-nowrap mb-2 fw-semibold ">
                                        Purchases
                                    </p>

                                    <div class="badge bg-label-warning border-this rounded-pill col-12 py-2">
                                        {{$currentYear}}
                                    </div>

                                </div>

                                <div class="col-10" style="height: 190px">
                                    <canvas id="myAnnualTransactionQuantity"></canvas>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Total Revenue -->
    </div>

    <div class="row">
        <!-- Order Statistics -->
        <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-0">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Order Statistics</h5>
                        <small class="text-muted">42.82k Total Sales</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="orederStatistics"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                            <a class="dropdown-item" href="javascript:void(0);">Expand</a>
                            <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                            <h2 class="mb-2">8,258</h2>
                            <span>Total Orders</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-primary"><i
                                                        class="bx bx-mobile-alt"></i></span>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Electronic</h6>
                                    <small class="text-muted">Mobile, Earbuds, TV</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">82.5k</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-success"><i
                                                        class="bx bx-closet"></i></span>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Fashion</h6>
                                    <small class="text-muted">T-shirt, Jeans, Shoes</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">23.8k</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-info"><i
                                                        class="bx bx-home-alt"></i></span>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Decor</h6>
                                    <small class="text-muted">Fine Art, Dining</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">849k</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded bg-label-secondary"><i
                                                        class="bx bx-football"></i></span>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Sports</h6>
                                    <small class="text-muted">Football, Cricket Kit</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">99</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Order Statistics -->

        <!-- Expense Overview -->
        <div class="col-md-6 col-lg-4 order-1 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#navs-tabs-line-card-income"
                                    aria-controls="navs-tabs-line-card-income"
                                    aria-selected="true">
                                Income
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Expenses</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab">Profit</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body px-0">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income"
                             role="tabpanel">
                            <div class="d-flex p-4 pt-3">
                                <div class="avatar flex-shrink-0 me-3">
                                    <img src="../proStuff/img/icons/unicons/wallet.png" alt="User"/>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Balance</small>
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1">$459.10</h6>
                                        <small class="text-success fw-semibold">
                                            <i class="bx bx-chevron-up"></i>
                                            42.9%
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div id="incomeChart"></div>
                            <div class="d-flex justify-content-center pt-4 gap-2">
                                <div class="flex-shrink-0">
                                    <div id="expensesOfWeek"></div>
                                </div>
                                <div>
                                    <p class="mb-n1 mt-1">Expenses This Week</p>
                                    <small class="text-muted">$39 less than last week</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Expense Overview -->

        <!-- Transactions -->
        <div class="col-md-6 col-lg-4 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Transactions</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="transactionID"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                            <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../proStuff/img/icons/unicons/paypal.png" alt="User"
                                     class="rounded"/>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">Paypal</small>
                                    <h6 class="mb-0">Send money</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">+82.6</h6>
                                    <span class="text-muted">USD</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../proStuff/img/icons/unicons/wallet.png" alt="User"
                                     class="rounded"/>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">Wallet</small>
                                    <h6 class="mb-0">Mac'D</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">+270.69</h6>
                                    <span class="text-muted">USD</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../proStuff/img/icons/unicons/chart.png" alt="User"
                                     class="rounded"/>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">Transfer</small>
                                    <h6 class="mb-0">Refund</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">+637.91</h6>
                                    <span class="text-muted">USD</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../proStuff/img/icons/unicons/cc-success.png" alt="User"
                                     class="rounded"/>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">Credit Card</small>
                                    <h6 class="mb-0">Ordered Food</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">-838.71</h6>
                                    <span class="text-muted">USD</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../proStuff/img/icons/unicons/wallet.png" alt="User"
                                     class="rounded"/>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">Wallet</small>
                                    <h6 class="mb-0">Starbucks</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">+203.33</h6>
                                    <span class="text-muted">USD</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="../proStuff/img/icons/unicons/cc-warning.png" alt="User"
                                     class="rounded"/>
                            </div>
                            <div
                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">Mastercard</small>
                                    <h6 class="mb-0">Ordered Food</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0">-92.45</h6>
                                    <span class="text-muted">USD</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Transactions -->
    </div>

    @push('other-scripts')
        <script src="{{asset('scripts/pro/dashboardIndex.js')}}"></script>
    @endpush

@endsection

