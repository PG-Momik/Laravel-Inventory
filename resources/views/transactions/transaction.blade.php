@extends('layouts.main')
@section('title', 'Transaction')
@section('activeTransactions', 'active-this')
@section('right-side')
    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a round-this border-black grad">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    {{-- <div class="col-xl-12 col-lg-6 col-md-4 col-sm-3 col-2 border-this"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur eaque nesciunt omnis porro possimus quis? Cupiditate dignissimos ipsa iste, iusto, non pariatur, possimus quasi quo reprehenderit sint suscipit veniam!</div>--}}

                    <div class="row mx-0 d-flex gx-5 align-items-center">

                        <div class="col-xl-4 col-lg-4 p-0">
                            <span
                                class="d-inline-flex nice-white-shadow px-4 align-items-end pb-1">
                                <a href="{{route('transactions.index')}}" class="text-decoration-none">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Transactions</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <span class="fs-4 text-grey"><u>{{$transaction->product->name}}</u></span>
                            </span>
                        </div>

                    </div>
                </div>

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="px-4 py-3 bg-white round-this shadow-this-down">
                            <dl class="row">

                                <div class="my-3">
                                    <div class="fs-4">
                                        <span>
                                            <strong
                                                class="{{$transaction->type=="Purchase"?'text-success':'text-danger'}}">
                                                {{$transaction->type}}
                                            </strong>
                                        </span>
                                        <span>of</span>
                                        <span>{{$transaction->product->name}}</span>
                                    </div>
                                    <div>
                                        {{$transaction->created_at->format('l jS \of F')}}
                                    </div>
                                </div>

                                <div class="col-md-6 col-12 row m-0">

                                    <dt class="col-lg-6 col-12">Product</dt>
                                    <dd class="col-lg-6 col-12">
                                        <a href="{{route('products.show', ['product'=>$transaction->product])}}">
                                            {{$transaction->product->name}}
                                        </a>
                                    </dd>

                                    <dt class="col-lg-6 col-12">Category</dt>
                                    <dd class="col-lg-6 col-12">
                                        <a href="{{route('categories.show', ['category'=>$transaction->product])}}">
                                            {{$transaction->product->category->name}}
                                        </a>
                                    </dd>

                                    <dt class="col-lg-6 col-12">Transaction by</dt>
                                    <dd class="col-lg-6 col-12">
                                        <a href="{{route('users.show', ['user'=>$transaction->product])}}">
                                        {{$transaction->user->name}}
                                        </a>
                                    </dd>
                                    <hr>

                                    <dt class="col-lg-6 col-12">Purchased At</dt>
                                    <dd class="col-lg-6 col-12">
                                        Rs. {{$transaction->purchasePriceDuringTransaction->value}}
                                    </dd>

                                    @if($transaction->type == $transaction::TYPE['sales'])
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

                                    @if($transaction->type == $transaction::TYPE['sales'])
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

                                            if($transaction->type == $transaction::TYPE['purchase']){
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
                                        <span class="">{{$transaction->product->description}}</span>
                                    </div>
                                    <hr>

                                    <dt class="col-lg-6 col-12">In Stock</dt>
                                    <dd class="col-lg-6 col-12">{{$transaction->product->quantity}} units</dd>

                                    <dt class="col-lg-6 col-12">Active Sales Discount</dt>
                                    <dd class="col-lg-6 col-12">{{$transaction->product->discount}} %</dd>

                                </div>


                                <dt class="col-sm-3"></dt>
                                <dd class="col-sm-9 row mx-0 px-0 justify-content-end">
                                    <div class="col-xl-4 col-md-6 col-12 row mx-0 g-2">
                                        <a href="{{route('generate-pdf', ['transaction'=>$transaction])}}"
                                           class="no-underline">
                                            <button class="btn btn-md bg-outline-blue text-blue col-12 round-this">
                                                <i class="fa-solid fa-download"></i> Download Pdf
                                            </button>
                                        </a>
                                    </div>
                                </dd>

                            </dl>
                        </div>
                    </div>

                    <div class="mt-5 round-this mx-4 h-100">
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

