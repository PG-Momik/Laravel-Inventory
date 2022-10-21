@extends('layouts.main')
@section('title', $product->name)
@section('activeProducts', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a round-this border-black grad">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    {{-- <div class="col-xl-12 col-lg-6 col-md-4 col-sm-3 col-2"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur eaque nesciunt omnis porro possimus quis? Cupiditate dignissimos ipsa iste, iusto, non pariatur, possimus quasi quo reprehenderit sint suscipit veniam!</div>--}}

                    <div class="row mx-0 d-flex gx-5 align-items-center">

                        <div class="col-xl-4 col-lg-4 p-0">
                            <span
                                class="d-inline-flex nice-white-shadow px-4 align-items-end pb-1">
                                <a href="{{route('products.index')}}" class="text-decoration-none">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Products</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <span class="fs-4 text-grey"><u>{{$product->name}}</u></span>
                            </span>
                        </div>
                        {{--                        Search Form--}}
                        <form action="{{route('products.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-8">
                                <input type="search" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
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
                </div>

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
                            <dl class="row">
                                <h3>{{$product['name']}}</h3>
                                <div class="col-md-8 col-12 row mx-0">
                                    <dt class="col-md-4 col-12"><i class="fa-solid fa-dollar-sign"></i> Price</dt>
                                    <dd class="col-md-8 col-12"><b>Rs.</b>{{$product->price}}</dd>

                                    <dt class="col-md-4 col-12"><i class="fa-sharp fa-solid fa-tags"></i> Categories</dt>
                                    <dd class="col-md-8 col-12">
                                        {{$product->category->name}}
                                    </dd>

                                    <dt class="col-md-4"><i class="fa-solid fa-boxes-stacked"></i> In Stock</dt>
                                    <dd class="col-md-8">{{$product->quantity}} Units</dd>
                                </div>

                                <div class="row mx-0 col-md-4 col-12 d-flex justify-content-center align-items-center">
                                    @if($product->image)
{{--                                        <img src="{{Storage::disk('localStorage')->url('axb.png')}}" alt="" width="300" height="200">--}}
                                        <img src="{{asset('storage/images/'.$product->image)}}" alt="" width="300" height="200">
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
                                    <dd class="col-sm-8">{{$product->user->name}}</dd>
                                    <dt class="col-sm-4 ">
                                        <i class="fa-solid fa-calendar-days"></i> Products Registered On
                                    </dt>
                                    <dd class="col-sm-8">
                                        {{$product->created_at}}
                                    </dd>
                                </div>

                                <dd class="col-12 row mx-0 px-0 justify-content-end">
                                    <div class="col-xl-3 col-md-4 col-12 row mx-0 g-2">
                                        <a href="{{route('products.edit', ['product'=>$product])}}"
                                           class="no-underline">
                                            <button class="btn btn-md bg-outline-blue text-blue col-12 round-this">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </button>
                                        </a>
                                        <a href="" class="no-underline">
                                            <form action="{{route('products.destroy', ['product'=>$product->id])}}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <button
                                                    class="btn btn-md-3 bg-outline-yellow text-yellow col-12 round-this">
                                                    <i class="fa-solid fa-trash"></i> Trash
                                                </button>

                                            </form>
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

