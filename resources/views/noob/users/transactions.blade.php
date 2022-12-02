@extends('layouts.noob_layout')

@section('title', "Users")

@section('activeUsers', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('users.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Users</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="{{route('users.show', ['user'=>$user])}}"
           class="fs-4 text-grey" style="color: white">
            <u>{{$user['name']}}</u>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <span class="fs-4 text-light"><u>transaction</u></span>
    </span>
@endsection

@section('search-bar')
    <x-search-bar :searchRoute="route('users.search')" :searchKeyword="$searchKeyword??''" placeholder="User">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('users.create')" entity="users">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('users.trashed')" entity="users">
    </x-view-trashed-entity-btn>
@endsection

@section('child-pagination')
    {{ $transactions->links("pagination::bootstrap-5")}}
@endsection

@section('content')
    <div class="p-4 pt-0">
        <ol class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center col-12 row m-0 bg-dark text-white">
                <div class="me-auto col-md-2 col-12">
                    <div class="fw-bold ">Item</div>
                </div>
                <div class="col-md-10 col-12 d-flex justify-content-center text-center">
                    <span class=" col-sm-4 col-md-4 col-12 py-1">Price</span>
                    <span class=" col-sm-3 col-md-3 col-12 py-1">Units</span>
                    <span class=" col-sm-3 col-md-3 col-12 py-1">Total</span>
                    <span class=" col-sm-2 col-md-2 col-12 py-1">Action</span>
                </div>
            </li>
            @foreach($transactions as $transaction)

                @php
                    $total    = 0;
                    $price    = 0;
                    $discount = 0;
                    $quantity = $transaction->quantity;
                    $alertClass = '';

                    if($transaction->type == $transaction::TYPE['purchase']){
                        $price = $transaction->purchasePriceDuringTransaction->value;
                        $alertClass = 'list-group-item-success';
                    }else{
                        $price = $transaction->salesPriceDuringTransaction->value;
                        $discount = $transaction->discount;
                        $alertClass = 'list-group-item-danger';
                    }
                    $beforeDiscount = $quantity*$price;
                    $total = $beforeDiscount - (($beforeDiscount/100) * $discount);
                @endphp


                <li class="list-group-item d-flex justify-content-between align-items-center {{$alertClass}} col-12 row m-0 py-1">
                    <div class="me-auto col-md-2 col-12">
                        <div class="fw-bold">
                            <a href="{{route('products.show', ['product'=>$transaction->product_id])}}">
                                {{$transaction->product->name}}
                            </a>
                        </div>
                        <span class="">{{$transaction->created_at->diffForHumans()}}</span>
                    </div>
                    <div class="col-md-10 col-12 d-flex justify-content-center text-center">
                        <span class="col-sm-4 col-md-4 col-12 py-1">{{$price}} ({{$discount}} % off)</span>
                        <span class="col-sm-3 col-md-3 col-12 py-1">{{$quantity}} </span>
                        <span class="col-sm-3 col-md-3 col-12 py-1">{{$total}} </span>
                        <span class="col-sm-2 col-md-2 col-12 py-1">
                            <a href="{{route('transactions.show', ['transaction'=>$transaction])}}"
                               class="w-100 btn btn-sm btn-outline-primary rounded-0">
                                View
                            </a>
                        </span>
                    </div>
                </li>

            @endforeach
        </ol>
    </div>

@endsection
