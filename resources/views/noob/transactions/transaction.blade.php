@extends('layouts.noob_layout')

@section('title', "Transactions")

@section('activeTransactions', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('transactions.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Transactions</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="{{route('transactions.show', ['transaction'=>$transaction])}}" class="fs-4 text-grey"
           style="color: white"><u>{{$transaction->type}}</u></a>
    </span>
@endsection

@section('content')
    <div class="p-4">
        <dl class="row m-0">

            <div class="fs-4 mx-0">
                <span>
                    <strong
                        class="{{$transaction->type=="Purchase"?'text-success':'text-danger'}}">
                        {{$transaction->type}}
                    </strong>
                </span>
                <span>of</span>
                <span>{!!$transaction->product->name??"<small class='text-danger'>Deleted Product</small>."!!}</span>
            </div>

            <small class="mb-3">
                {{$transaction->created_at->format('l jS \of F')}}
            </small>


            <div class="col-md-6 col-12 row m-0">

                <dt class="col-lg-6 col-12">Product</dt>
                <dd class="col-lg-6 col-12">
                    @if($transaction->product)
                        <a href="{{route('products.show', ['product'=>$transaction->product])}}">
                            {{$transaction->product->name}}
                        </a>
                    @else
                        <small class="text-danger">
                            Deleted Product.
                        </small>
                    @endif
                </dd>

                <dt class="col-lg-6 col-12">Category</dt>
                <dd class="col-lg-6 col-12">
                    {{--Make sure product property is not empty--}}
                    @if($transaction->product && $transaction->product->category)
                        <a href="{{route('categories.show', ['category'=>$transaction->product->category])}}">
                            {{$transaction->product->category->name}}
                        </a>
                    @else
                        <small class="text-danger">
                            Deleted Category.
                        </small>
                    @endif
                </dd>

                <dt class="col-lg-6 col-12">Transaction by</dt>
                <dd class="col-lg-6 col-12">
                    @if($transaction->user)
                        <a href="{{route('users.show', ['user'=>$transaction->user->id])}}">
                            {{$transaction->user->name}}
                        </a>
                    @else
                        <small class="text-danger">
                            Deleted User.
                        </small>
                    @endif

                </dd>
                <hr>

                <dt class="col-lg-6 col-12">Purchased At</dt>
                <dd class="col-lg-6 col-12">
                    Rs. {{$transaction->purchasePriceDuringTransaction->value}}
                </dd>

                @if($transaction->type == App\Models\TransactionType::SALE)
                    <hr>
                    <dt class="col-lg-6 col-12">Sold For:</dt>
                    <dd class="col-lg-6 col-12">
                        Rs. {{$transaction->salesPriceDuringTransaction->value}}
                    </dd>
                @endif

                <dt class="col-lg-6 col-12">Quantity</dt>
                <dd class="col-lg-6 col-12">
                    {{$transaction->quantity}} units
                </dd>

                @if($transaction->type == App\Models\TransactionType::SALE)
                    <dt class="col-lg-6 col-12">Applied Discount</dt>
                    <dd class="col-lg-6 col-12">
                        {{$transaction->discount}} %
                    </dd>
                @endif


                <dt class="col-lg-6 col-12">Total</dt>
                <dd class="col-lg-6 col-12">
                    @php
                        $total    = 0;
                        $price    = 0;
                        $discount = 0;
                        $quantity = $transaction->quantity;

                        if($transaction->type == App\Models\TransactionType::PURCHASE){
                            $price = $transaction->purchasePriceDuringTransaction->value;
                        }else{
                            $price = $transaction->salesPriceDuringTransaction->value;
                            $discount = $transaction->discount;
                        }
                        $beforeDiscount = $quantity*$price;
                        $total = $beforeDiscount - (($beforeDiscount/100) * $discount);
                    @endphp
                    Rs. {{ $total }}
                </dd>
            </div>

            <div class="col-md-6 col-12 row m-0">

                <div class="text-justify">
                    <span class=""><strong>Product Desc:</strong></span>
                    <span class="">
                        @if($transaction->product)
                            {{$transaction->product->description}}
                        @else
                            <small class="text-danger">Delete Product.</small>
                        @endif
                    </span>
                </div>
                <hr>

                <dt class="col-lg-6 col-12">In Stock</dt>
                <dd class="col-lg-6 col-12">
                    @if($transaction->product)
                        {{$transaction->product->quantity}} units.
                    @else
                        <small class="text-danger">Delete Product.</small>
                    @endif
                </dd>

                <dt class="col-lg-6 col-12">Active Sales Discount</dt>
                <dd class="col-lg-6 col-12">
                    @if($transaction->product)
                        {{$transaction->product->discount}}  %
                    @else
                        <small class="text-danger">Delete Product.</small>
                    @endif
                </dd>

            </div>


            <dt class="col-sm-3"></dt>
            <dd class="col-sm-9 row mx-0 px-0 justify-content-end">
                <div class="col-xl-4 col-md-6 col-12 row mx-0 g-2">
                    <a href="{{route('generate-pdf', ['transaction'=>$transaction])}}"
                       class="no-underline">
                        <button class="btn btn-md btn-outline-dark col-12 round-this">
                            <i class="fa-solid fa-download"></i> Download Pdf
                        </button>
                    </a>
                </div>
            </dd>

        </dl>
    </div>
@endsection
