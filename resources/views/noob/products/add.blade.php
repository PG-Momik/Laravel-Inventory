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
        <a href="javascript:void(0)" class="fs-4 text-grey"
           style="color: white"><u>add</u></a>
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
        <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')

            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="mb-2">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="">Category</label>
                        <select name="category" id="" class="form-select">
                            <option value="invalid" selected>Select Category</option>

                            @forelse($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @empty
                                <option value="invalid">No categories found.</option>
                            @endforelse

                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="">Quantity</label>
                        <input type="text" name="quantity" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="">Purchase Price</label>
                        <input type="text" name="purchasePrice" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="">Sales Price</label>
                        <input type="text" name="salesPrice" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="">Active Discount</label>
                        <input type="text" name="discount" class="form-control">
                    </div>

                </div>

                <div class="col-lg-6 col-12">
                    <div class="mb-2">
                        <label for="">Description</label>
                        <textarea name="description" id="" cols="30" rows="10"
                                  class="form-control"></textarea>
                    </div>
                    <div class="mb-2">
                        <div class="text-center m-0 p-0">
                            <label for="" class="">Click to add image.</label>
                        </div>
                        <div class="d-flex justify-content-center align-items-center"
                             id="imageUploadFacade">
                            <div class="text-center" id="imageWrapper">

                                <div class="">
                                    <div class="">
                                        <img src="{{asset('images/camera.png')}}"
                                             id="actualImage" alt="No Image Available"
                                             style="max-height:80px">
                                    </div>
                                    <input type="file" name="productImage" id="imageUploadField"
                                           class=""
                                           style="opacity: 0">

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-12 text-lg-end text-center">
                <button type="reset"
                        class="btn btn-outline-dark px-5 nice-round col-sm-12 col-lg-auto mb-2">
                    Reset
                </button>
                <button type="submit"
                        class="btn btn-outline-primary px-5 nice-round col-sm-12 col-lg-auto mb-2">
                    Add
                </button>
            </div>
        </form>
    </div>


    <style>
        #imageUploadFacade:hover {
            cursor: pointer;
        }
    </style>

@endsection

