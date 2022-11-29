@extends('layouts.main')

@section('title', "Add products")

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
                                <span class="fs-4 text-grey"><u>{{$product['name']}}</u></span>
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
                                       placeholder="Search product" value="" style="max-height: 50px"/>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-4 col-4 row mx-0 justify-content-center">
                                <button type="submit" class="btn bg-outline-dark round-button m-1 height-40 width-40">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button type="button" class="btn bg-outline-white round-button m-1 height-40 width-40">
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


                                <!--Modal : Product Update-->
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
                                                <p>
                                                    It seems you've made a
                                                    <span id="transactionType"></span>
                                                    of
                                                    <span id="transactionUnits"></span> units.</p>
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
                    </div>

                    <div class="mt-5 round-this mx-4 h-100">
                    </div>

                </div>

            </div>
        </div>
    </div>

    <style>
        #imageUploadFacade:hover {
            cursor: pointer;
        }
    </style>
    @push('other-scripts')
        <script src="{{asset('scripts/productEdit.js')}}" defer></script>
    @endpush
@endsection

