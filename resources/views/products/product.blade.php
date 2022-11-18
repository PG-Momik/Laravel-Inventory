@extends('layouts.main')
@section('title', $product->name)
@section('activeProducts', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a round-this border-black grad">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    <div class="row mx-0 d-flex gx-5 align-items-center">

                        <div class="col-xl-4 col-lg-4 p-0">
                            <span
                                class="d-inline-flex px-4 align-items-end pb-1">
                                <a href="{{route('products.index')}}" class="text-decoration-none nice-white-shadow">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Products</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <a href="{{route('products.show', ['product'=>$product])}}"
                                   class="fs-4 text-grey nice-white-shadow">
                                    <u class="text-white">{{$product->name}}</u>
                                </a>
                            </span>
                        </div>
                        {{--Search Form--}}
                        <form action="{{route('products.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-8">
                                <input type="search" name="search-field"
                                       class="form-control round-this px-3 col my-border-0 height-40"
                                       placeholder="Search product" value=""
                                       style="max-height: 50px">
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
                            @can('create products')
                                <div class="col-lg-6 col-md-12">
                                    <a href="{{route('products.create')}}" class="no-underline">
                                        <button class="btn btn-md bg-blue text-white col-12 round-this">
                                            <i class="fa-solid fa-plus"></i> Add
                                        </button>
                                    </a>
                                </div>
                            @endcan
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('products.trashed')}}" class="no-underline">
                                    <button class="btn btn-md-3 bg-yellow text-white col-12 round-this">
                                        <i class="fa-solid fa-trash"></i> Trashed
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
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
                                        {{$product->category->name}}
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
                                    <dd class="col-sm-8">{{$product->registrant->name}}</dd>
                                    <dt class="col-sm-4 ">
                                        <i class="fa-solid fa-calendar-days"></i> Products Registered On
                                    </dt>
                                    <dd class="col-sm-8">
                                        {{$product->created_at}}
                                    </dd>
                                </div>

                                <hr class="mt-2">

                                {{--BOTTOM SECTION--}}
                                <dd class="col-12 row mx-0 px-0 justify-content-between">
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
                                        @can('edit users')
                                            <a href="{{route('products.edit', ['product'=>$product])}}"
                                               class="btn btn-md bg-outline-blue text-blue col-12 round-this my-1">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </a>
                                        @endcan

                                        @can('trash users')
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

                            </dl>
                        </div>
                    </div>

                </div>
            </div>

        </div>


    </div>

    @push('other-scripts')
        <script src="{{asset('scripts/qrDownload.js')}}"></script>

        <script src="{{asset('scripts/productPricesLineGraph.js')}}"></script>
    @endpush
@endsection

