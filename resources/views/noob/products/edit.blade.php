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
        <a href="javascript:void(0)" class="fs-4 text-dark" style="color: white">
            <u>{{$product->name}}</u>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="javascript:void(0)" class="fs-4 text-grey"
           style="color: white"><u>edit</u></a>
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
    <div class="p-4 pt-1">
        <form action="{{route('products.update', ['product'=>$product])}}" method="post"
              enctype="multipart/form-data">
            @csrf
            @method('put')

            <div class="form-row  row  mx-0">
                <div class="col-md-6 mb-3">
                    <label for="productName">Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fa-solid fa-user py-1"></i>
                            </div>
                        </div>
                        <input type="text" class="form-control" id="productName"
                               placeholder="Product name"
                               value="{{$product->name??old(['name'])}}"
                               name="name"
                               required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="selectField">Category</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa-solid fa-angles-down py-1"></i>
                            </div>
                        </div>
                        <select class="form-select custom-select" name="category_id">
                            @foreach($categories   as $category)
                                {{$category->id}}
                                <option
                                    value="{{$category->id}}" {{$category->id!=$product->category->id??"selected"}}>
                                    {{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row row m-0">
                <div class="col-lg-3 col-12">
                    {{--Stock--}}
                    <div class="col-12">
                        <label for="productQuantity">In Stock</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <button type="button"
                                        class="input-group-text btn-danger text-white calcMinusBtn"
                                        id="" onclick="subtract(0)">
                                    <i class="fa-solid fa-minus py-1"></i>
                                </button>
                            </div>
                            <input type="text" class="form-control calcField" id="productQuantity"
                                   placeholder="Product Quantity"
                                   value="{{$product->quantity??old(['quantity'])}}"
                                   name="quantity"
                                   min="0"
                                   autocomplete="off"
                                   required>
                            <div class="input-group-prepend">
                                                    <span class="input-group-text btn-success calcPlusBtn"
                                                          onclick="add(0)">
                                                        <i class="fa-solid fa-plus py-1"></i>
                                                    </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <label for="">Product Description</label>
                    <textarea name="description" class="form-control" aria-label="With textarea"
                              rows="6">{{$product->description}}
                                            </textarea>
                </div>

                <div class="col-lg-3">
                    <p class="text-center">
                        <label for="imageUploadField">Click to change</label>
                    </p>
                    <div class="d-flex justify-content-center align-items-center"
                         id="imageUploadFacade">
                        <div class="text-center" id="imageWrapper">
                            @if($product->image)
                                <div>
                                    <img src="{{asset('storage/images/'.$product->image)}}"
                                         alt="Cannot render image."
                                         style="width: 180px"
                                         id="actualImage"
                                    >
                                    <input type="file"
                                           name="productImage"
                                           id="imageUploadField" style="opacity: 0">
                                </div>
                            @else
                                <div>
                                    <div class="">
                                        <img src="{{asset('images/camera.png')}}"
                                             id="actualImage" alt="No Image Available"
                                             style="width: 180px">
                                    </div>
                                    <div class="text-pm-grey" id="noImageText">No Image Available
                                    </div>
                                    <input type="file" name="productImage" id="imageUploadField"
                                           style="opacity: 0">

                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <hr>

                <div class="col-12 row mx-0 px-0 justify-content-center">
                    <div class="col-md-6 col-12 row mx-0 g-2">
                        <button type="button"
                                id="updateBtn"
                                class="btn btn-md bg-outline-green text-blue col-12 round-this">
                            <i class="fa-solid fa-pen"></i> Update
                        </button>
                    </div>
                </div>
            </div>


            <!--Modal : Product added-->
            <div class="modal fade" id="changesModal" tabindex="-1"
                 aria-labelledby="changesModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content py-1">
                        <div class="modal-header">
                            <h5 class="modal-title text-primary" id="productAddedModalLabel">
                                <span id="noChanges"></span>Change made to product quantity.
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>It seems you've made a <span id="transactionType"></span> of <span
                                    id="transactionUnits"></span> units.</p>
                            <input type="number" name='price' id="priceField"
                                   class="row m-0 col-12 form-control" autocomplete="off" required>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        #imageUploadFacade:hover {
            cursor: pointer;
        }
    </style>
    @push('other-scripts')
        <script src="{{asset('scripts/noob/productEdit.js')}}" defer></script>
    @endpush
@endsection

