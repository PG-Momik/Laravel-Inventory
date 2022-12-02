@extends('layouts.noob_layout')

@section('title', "Products")

@section('activeProducts', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('products.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Products</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="{{route('products.show', ['product'=>$product])}}" class="fs-4 text-grey"
           style="color: white"><u>{{$product['name']}}</u></a>
    </span>
@endsection

@section('search-bar')
    <x-search-bar :searchRoute="route('products.search')" :searchKeyword="$searchKeyword??''" placeholder="product">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('products.create')" entity="products">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('products.trashed')" entity="products">
    </x-view-trashed-entity-btn>
@endsection

@section('content')
    <div class="p-4 pt-0">
        <dl class="row">
            <h3>{{$product['name']}}</h3>
            <div class="col-md-8 col-12 row mx-0">
                <dt class="col-md-4 col-12"><i class="fa-solid fa-dollar-sign"></i> Purchase Price
                </dt>
                <dd class="col-md-8 col-12"><b>Rs.</b>{{$product->latestPurchasePrice->value}}</dd>

                <dt class="col-md-4 col-12"><i class="fa-solid fa-dollar-sign"></i> Sales Price</dt>
                <dd class="col-md-8 col-12"><b>Rs.</b>{{$product->latestSalesPrice->value}}</dd>

                <dt class="col-md-4 col-12"><i class="fa-sharp fa-solid fa-tags"></i> Categories
                </dt>
                <dd class="col-md-8 col-12">
                    {!!$product->category->name??"<small class='text-danger'>Deleted User.</small>" !!}
                </dd>

                <dt class="col-md-4"><i class="fa-solid fa-boxes-stacked"></i> In Stock</dt>
                <dd class="col-md-8">{{$product->quantity}} Units</dd>
            </div>

            <div class="row col-md-4 col-12 d-flex
                                justify-content-center align-items-center
                                mx-0 px-0 my-3">
                @if($product->image)
                    {{--<img src="{{Storage::disk('localStorage')->url('axb.png')}}" alt="" width="300" height="200">--}}
                    <img src="{{asset('storage/images/'.$product->image)}}" alt=""
                         style="width: 200px ; height:200px" class=" px-0">
                @else
                    <div class="text-center"><i class="fa-solid fa-image fs-1"></i></div>
                    <div class="text-center">No Image Available</div>
                @endif
            </div>

            <hr>

            <div class="row mx-0 col-sm-8 col-12">
                <dt class="col-sm-4 "><i class="fa-solid fa-percent"></i> Active Discount</dt>
                <dd class="col-sm-8">
                    {{$product->discount}} %
                </dd>

                <dt class="col-sm-4 ">
                    <i class="fa-solid fa-person"></i> Products Registered By
                </dt>
                <dd class="col-sm-8">{!! $product->registrant->name??"<small class='text-danger'>Deleted User.</small>"!!}</dd>
                <dt class="col-sm-4 ">
                    <i class="fa-solid fa-calendar-days"></i> Products Registered On
                </dt>
                <dd class="col-sm-8">
                    {{$product->created_at}}
                </dd>
            </div>

            {{--/TOP SECTION--}}
            <hr class="mt-2">

            {{--BOTTOM SECTION--}}
            <dd class="col-12 row mx-0 px-0 justify-content-between px-3">
                <div class="col-lg-8">
                    <div class="d-flex row align-items-bottom">
                        <div class="form col-11">
                            <label for="lookBackDays">Look back (n) days.</label>
                            <input type="text" id="lookBackDays"
                                   placeholder="Enter n days. Default(7)" class="form-control"
                                   value="7">
                        </div>
                        <button id="lookBackBtn" class="btn btn-outline-success col-1 mt-4">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>
                    <div>
                        <canvas id="myPurchaseGraph" class="my-1"></canvas>
                        <hr>
                        <canvas id="mySalesGraph" class="my-1"></canvas>
                    </div>
                </div>
                <div class="col-lg-2 col-12 align-items-middle">
                    @can('update products')
                        <a href="{{route('products.edit', ['product'=>$product])}}"
                           class="btn btn-md bg-outline-blue text-blue col-12 round-this my-1">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                    @endcan

                    @can('trash products')
                        <form action="{{route('products.destroy', ['product'=>$product->id])}}"
                              method="post"
                              class="my-1">
                            @csrf
                            @method('delete')
                            <button
                                class="btn btn-md-3 bg-outline-yellow text-yellow col-12 round-this">
                                <i class="fa-solid fa-trash"></i> Trash
                            </button>
                        </form>
                    @endcan

                    <div class="text-center mt-2">
                        {!! QrCode::size(160)->generate($product->id) !!}
                        <button id="downloadPNG"
                                class="btn btn-md bg-outline-dark col-12 round-this my-2"
                                onclick="drawToCanvas({{$product->id}})">
                            <i class="fa-solid fa-download"></i>QR
                        </button>
                        <canvas id="canvas" class="d-none"></canvas>
                    </div>
                </div>
            </dd>
            {{--/BOTTOM SECTION--}}
        </dl>
    </div>
    @push('other-scripts')
        <script src="{{asset('scripts/noob/qrDownload.js')}}"></script>

        <script src="{{asset('scripts/noob/productPricesLineGraph.js')}}"></script>
    @endpush
@endsection

