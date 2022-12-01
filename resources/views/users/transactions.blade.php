@extends('layouts.main')
@section('title', 'Users')
@section('activeUsers', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this border-black">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                            <h1>Users Transactions</h1>
                        </div>

                        {{--Search Form--}}
                        <form action="{{route('users.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <input type="text" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search user" value="" style="max-height: 50px">
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
                            @can('create users')
                                <div class="col-lg-6 col-md-12">
                                    <a href="{{route('users.create')}}" class="no-underline">
                                        <button class="btn btn-md bg-blue text-white col-12 round-this">
                                            <i class="fa-solid fa-plus"></i> Add
                                        </button>
                                    </a>
                                </div>
                            @endcan
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('users.trashed')}}" class="no-underline">
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


                            {{--Table--}}
                            {{alert()}}

                            <div class="col-12 text-dark">
                                {{$transactions->links("pagination::bootstrap-5")}}
                            </div>

                            <ol class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center col-12 row m-0 bg-dark text-white">
                                    <div class="me-auto col-md-2 col-12">
                                        <div class="fw-bold fs-5">Item</div>
                                    </div>
                                    <div class="col-md-10 col-12 d-flex justify-content-center text-center">
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-5">Price</span>
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-5">Discount</span>
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-5">Units</span>
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-5">Total</span>
                                    </div>
                                </li>
                                @foreach($transactions as $transaction)

                                    @php
                                        $total    = 0;
                                        $price    = 0;
                                        $discount = 0;
                                        $quantity = $transaction->quantity;
                                        $alertClass = '';

                                        if($transaction->type == App\Models\TransactionType::PURCHASE){
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
                                            <span
                                                class=" col-sm-6 col-md-3 col-12 py-1">{{$price}}</span>
                                            <span
                                                class=" col-sm-6 col-md-3 col-12 py-1">{{$discount}}</span>
                                            <span
                                                class=" col-sm-6 col-md-3 col-12 py-1">{{$quantity}} </span>
                                            <span class=" col-sm-6 col-md-3 col-12 py-1">
                                                {{$total}}
                                            </span>
                                        </div>
                                    </li>

                                @endforeach
                            </ol>
                        </div>
                    </div>

                    <div class="mt-5 round-this mx-4 h-100">
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection
