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
                                <span class="fs-4 text-grey"><u>Add</u></span>
                            </span>
                        </div>
                        {{--Search Form--}}
                        <form action="{{route('products.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8  row mx-0 align-items-center">
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
                            <form action="{{route('products.store')}}"
                                  class="needs-validation" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                @method('post')

                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        {{--<Name>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Name<span class="text-danger">*</span></label>
                                                <input type="text" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       id="name">
                                                <div class="invalid-feedback">
                                                    @error('name')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>

                                        </div>
                                        {{--</Name>--}}

                                        {{--<Category>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Category<span class="text-danger">*</span></label>
                                                <select name="category" id="category"
                                                        class="form-select @error('category') is-invalid @enderror">
                                                    <option value="invalid" selected>Select Category</option>

                                                    @forelse($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @empty
                                                        <option value="invalid">No categories found.</option>
                                                    @endforelse

                                                </select>

                                                <div class="invalid-feedback">
                                                    @error('category')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</Category>--}}

                                        {{--<Quantity>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Quantity<span class="text-danger">*</span></label>
                                                <input type="text" id="quantity" name="quantity"
                                                       class="form-control @error('quantity') is-invalid @enderror">
                                                <div class="invalid-feedback">
                                                    @error('quantity')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</Quantity>--}}

                                        {{--<PurchasePrice>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Purchase Price<span class="text-danger">*</span></label>
                                                <input type="text" id="Purchase Price" name="purchasePrice"
                                                       class="form-control @error('purchasePrice') is-invalid @enderror">
                                                <div class="invalid-feedback">
                                                    @error('purchasePrice')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</PurchasePrice>--}}

                                        {{--<SalesPrice>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Sales Price<span class="text-danger">*</span></label>
                                                <input type="text" id="salesPrice" name="salesPrice"
                                                       class="form-control @error('salesPrice') is-invalid @enderror">
                                                <div class="invalid-feedback">
                                                    @error('salesPrice')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</SalesPrice>--}}

                                        {{--<ActiveDiscount>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Active Discount</label>
                                                <input type="text" id="discount" name="discount" class="form-control">
                                                <div class="invalid-feedback">
                                                    @error('discount')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</ActiveDiscount>--}}
                                    </div>

                                    <div class="col-lg-6 col-12">
                                        {{--<Description>--}}
                                        <div class="has-validation">
                                            <div class="mb-2">
                                                <label for="">Description</label>
                                                <textarea name="description" id="" cols="30" rows="10"
                                                          class="form-control">
                                                </textarea>
                                                <div class="invalid-feedback">
                                                    @error('description')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</Description>--}}

                                        {{--<Image>--}}
                                        <div class="has-validation">
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
                                                <div class="invalid-feedback">
                                                    @error('image')
                                                    {{$message}}
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--</Image>--}}
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

@endsection

