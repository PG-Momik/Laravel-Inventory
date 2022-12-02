@extends('layouts.noob_layout')

@section('title', "Transactions")

@section('activeTransactions', 'active-this')

@section('child-page-title', "Transactions")

@section('child-pagination', $transactions->links("pagination::bootstrap-5"))

@section('content')

    <div class="row p-4 pt-0 mt-1 mb-2">
        {{--DROPDOWN--}}
        <div class="d-flex col-lg-4 col-12 mb-2">
            <select name="" id="typeSelect" class="form-select col height-40">
                <option value="" @selected(empty($type))>All</option>
                <option value="purchase" @selected($type=='purchase')>Purchases</option>
                <option value="sale" @selected($type=='sale')>Sales</option>
            </select>

            <div name="" id="" class="col">
            </div>
        </div>
        {{--/DROPDOWN--}}
        <div class="table-responsive">


            <table class="table table-hover">

                {{ alert() }}

                <thead class="table-dark">
                <tr>
                    <th>Trans<span class="d-md-inline d-none">action</span></th>
                    <th>Q<span class="d-md-inline d-none">uanti</span>ty</th>
                    <th>Product</th>
                    <th>Dis<span class="d-md-inline d-none">count</span></th>
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
                        <td>
                            @if($transaction->product)
                                {{$transaction->product->name}}
                            @else
                                <small class="text-danger">Deleted Product.</small>
                            @endif
                        </td>
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
    @push('other-scripts')
        <script>
            let max = 6
        </script>
        <script src="{{asset('scripts/noob/transactionTypedSelect.js')}}"></script>
    @endpush
@endsection
