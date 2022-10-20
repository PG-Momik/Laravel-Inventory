{{--<pre>--}}

{{--</pre>--}}
{{--{{$user->name}}--}}
{{--@foreach($user->transactions as $transaction)--}}
{{--    {{$transaction->id}}--}}
{{--    <br>--}}
{{--    {{$transaction->type}}--}}
{{--    <br>--}}
{{--    {{$transaction->created_at}}--}}
{{--    <br>--}}
{{--    {{$transaction->product_id}}--}}
{{--    <br>1--}}
{{--@endforeach--}}

@extends('layouts.main')
@section('title', 'Users')
@section('activeUsers', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                            <h1>Users</h1>
                        </div>

                        {{--Search Form--}}
                        <form class="col-xl-8 col-lg-8 row mx-0 align-items-center" action="{{route('users.index')}}"
                              method="post">
                            @csrf
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
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('users.index')}}" class="no-underline">
                                    <button class="btn btn-md bg-blue text-white col-12 round-this">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <a href="/customer/trashed" class="no-underline">
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


                            {{--                            Table--}}
                            @if(Session()->has('success'))
                                <p class="alert alert-success">{{session()->get('success')}}</p>
                            @endif
                            @if(Session()->has('warning'))
                                <p class="alert alert-warning">{{session()->get('warning')}}</p>
                            @endif
                            @if(Session()->has('error'))
                                <p class="alert alert-fail">{{session()->get('error')}}</p>
                            @endif

                            <div class="col-12 text-dark">
                                {{--                                Pagination Fix here--}}
                            </div>

                            <ol class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center col-12 row m-0 bg-dark text-white">
                                    <div class="me-auto col-md-2 col-12">
                                        <div class="fw-bold fs-4">Item</div>
                                    </div>
                                    <div class="col-md-10 col-12 d-flex justify-content-center text-center">
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-4">Price per unit</span>
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-4">Discount</span>
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-4">Quantity</span>
                                        <span class=" col-sm-6 col-md-3 col-12 py-1 fs-4">Total</span>
                                    </div>
                                </li>
                                @foreach($user->transactions as $transaction)
                                    <li class="list-group-item d-flex justify-content-between align-items-center
                                        @if($transaction->type=="Added"){{'list-group-item-success'}}@else{{'list-group-item-danger'}}@endif col-12 row m-0">
                                        <div class="me-auto col-md-2 col-12">
                                            <div class="fw-bold fs-4"><a href="{{route('products.show', ['product'=>$transaction->product_id])}}">{{$transaction->records->name}}</a></div>
                                            <span class="">{{$transaction->created_at}}</span>
                                        </div>
                                        <div class="col-md-10 col-12 d-flex justify-content-center text-center">
                                            <span
                                                class=" col-sm-6 col-md-3 col-12 py-1 fs-4">{{$transaction->records->price}}</span>
                                            <span
                                                class=" col-sm-6 col-md-3 col-12 py-1 fs-4">{{$transaction->records->discount}}</span>
                                            <span
                                                class=" col-sm-6 col-md-3 col-12 py-1 fs-4">{{$transaction->quantity}} </span>
                                            @php
                                                $subTotal      = $transaction->quantity * $transaction->records->price ;
                                                $discount      = ($transaction->records->discount / 100 ) * $transaction->records->price;
                                                $discountTotal = $discount * $transaction->quantity;
                                                $total         = $subTotal - $discountTotal;
                                            @endphp
                                            <span class=" col-sm-6 col-md-3 col-12 py-1 fs-4">
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
