@extends('layouts.main')
@section('title', 'Products')
@section('activeProducts', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                            <h1>Products</h1>
                        </div>

                        {{--Search Form--}}
                        <form action="{{route('products.index')}}" method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            <div class="col-xl-2 col-lg-2 col-0"></div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <input type="search" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search product" value="{{$searchKeyword}}" style="max-height: 50px">
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
                                <a href="{{route('products.create')}}" class="no-underline">
                                    <button class="btn btn-md bg-blue text-white col-12 round-this">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </a>
                            </div>
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


                {{--White card goes here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">

                            {{--                            Pagination--}}
                            <div class="col-12 text-dark">
                                {{$products->links("pagination::bootstrap-5")}}
                            </div>

                            {{--                            Table--}}
                            <table class="table table-hover table-md">
                                @if(Session()->has('success'))
                                    <p class="alert alert-success">{{session()->get('success')}}</p>
                                @endif
                                @if(Session()->has('warning'))
                                    <p class="alert alert-warning">{{session()->get('warning')}}</p>
                                @endif
                                @if(Session()->has('error'))
                                    <p class="alert alert-fail">{{session()->get('error')}}</p>
                                @endif

                                <thead class="table-dark">
                                <tr>
                                    <th>Name
                                    <th>Category</th>
                                    <th>In stock</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->name}}</td>
                                        <th><a href="{{route('categories.show', ['category'=>$product->category->id])}}">{{$product->category->name}}</a></th>
                                        <td>{{$product->quantity}}</td>
                                        <td>{{$product->price}}</td>
                                        <td>{{$product->discount}}</td>
                                        <td class="d-flex" style="column-gap: 0.8vw">
                                            <a href="{{route('products.show', ['product'=>$product])}}"
                                               class="col btn btn-sm btn-outline-primary rounded-0 px-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="" class="col no-underline">
                                                <form action="{{route('products.destroy', ['product'=>$product])}}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button
                                                        class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 ">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                </form>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5 round-this mx-4 h-100">
                    </div>

                </div>


            </div>
        </div>


    </div>

@endsection
