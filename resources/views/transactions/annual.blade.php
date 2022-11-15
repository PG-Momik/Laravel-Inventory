@extends('layouts.main')

@section('title', "Yearly Transaction")

@section('activeTransactions', 'active-this')

@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 440px" class="a bg-purple round-this border-black">
                <div class="bg-purple px-5 pt-3 pb-4" style="border-radius: 20px">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                           <span class="d-inline-flex pb-1
                                px-4 align-items-end">
                                <a href="{{route('transactions.index')}}"
                                   class="text-decoration-none nice-white-shadow">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Transactions</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <a href="{{route('yesterdays-transactions')}}"
                                   class="text-decoration-none nice-white-shadow">
                                    <u class="fs-4 text-white">Yearly</u>
                                </a>
                            </span>
                        </div>

                    </div>

                </div>


                {{--White card goes here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">

                            {{--Pagination--}}

                            <div class="row px-0 mt-1 mb-2">
                                {{--DROPDOWN--}}
                                <div class="d-flex col-lg-4 col-12">
                                    <select name="" id="typeSelect" class="form-select col height-40">
                                        <option value="" @selected(empty($type))>All</option>
                                        <option value="purchase" @selected($type=='purchase')>Purchases</option>
                                        <option value="sale" @selected($type=='sale')>Sales</option>
                                    </select>

                                    <select name="" id="yearSelect" class="form-select col height-40">
                                        <option value="" class="disable" disabled>Select year</option>
                                        @for($i=$oldestYear; $i<=$year; $i++)
                                            <option value="{{$year}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                {{--PAGINATION--}}
                                <div class="col-lg-8 col-12">
                                    {{$transactions->links("pagination::bootstrap-5")}}
                                </div>
                            </div>

                            <div class="table-responsive">


                                <table class="table table-hover">

                                    {{ alert() }}

                                    <thead class="table-dark">
                                    <tr>
                                        <th>Transaction</th>
                                        <th>Quantity</th>
                                        <th>Product</th>
                                        <th>Discount</th>
                                        <th>Price</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr class="align-middle">
                                            <td>
                                                <div class="{{$transaction->type==="Purchase"
                                                    ?"text-success"
                                                    :"text-danger"}}">
                                                    <strong>{{$transaction->type}}</strong>
                                                </div>
                                                <small>{{$transaction->created_at->format('jS \of F, Y')}}</small>
                                            </td>
                                            <td>{{$transaction->quantity}}</td>
                                            <td>{{$transaction->product->name}}</td>
                                            <td>{{$transaction->discount}}</td>
                                            <td>{{$transaction->type=="Purchase"
                                            ? $transaction->purchasePriceDuringTransaction->value
                                            : $transaction->salesPriceDuringTransaction->value}}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('transactions.show', ['transaction'=>$transaction])}}"
                                                   class="px-4 rounded-0 btn btn-outline-primary btn-sm">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{route('generate-pdf', ['transaction'=>$transaction])}}"
                                                   class="px-4 rounded-0 btn btn-outline-dark btn-sm">
                                                    <i class="fa-solid fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @push('other-scripts')
        <script>
            let yearSelect = document.getElementById('yearSelect');
            let max = 7

            yearSelect.addEventListener('change', function () {
                let currentUrl = window.location.href;
                currentUrl = currentUrl.split('/');

                if (currentUrl.length > max) {
                    currentUrl = setToBaseURL(currentUrl);
                }
                currentUrl[max - 2] = monthSelect.value;
                currentUrl = extractQueryIfExists(currentUrl);

                let redirectURL = currentUrl.join('/');
                window.location.replace(redirectURL);
            });
        </script>
        <script src="{{asset('scripts/transactionTypedSelect.js')}}"></script>
    @endpush
@endsection
