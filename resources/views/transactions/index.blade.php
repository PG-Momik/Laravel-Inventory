@extends('layouts.main')

@section('title', "Transactions")

@section('activeTransactions', 'active-this')

@section('right-side')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">

                <!--Active Page-->
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    <div class="row mx-0 d-flex gx-5  align-items-center">
                        <div class="col-xl-4 col-lg-4">
                            <h1>Transactions</h1>
                        </div>
                    </div>
                </div>

                <!--White card-->
                <div class="p-5">
                    <div class="row bg-white round-this  p-4 g-3">

                        @if(Session()->has('success'))
                            <p class="alert alert-success">{{session()->get('success')}}</p>
                        @endif
                        @if(Session()->has('warning'))
                            <p class="alert alert-warning">{{session()->get('warning')}}</p>
                        @endif
                        @if(Session()->has('error'))
                            <p class="alert alert-fail">{{session()->get('error')}}</p>
                        @endif

                        <!--Button trigger modal-->
                        <div class="col-12 d-flex justify-content-center">
                            <ul class="col-lg-8 col-12 list-group gy-2">

                                <button type="button" class="btn btn-dark py-3 fs-5" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                    <i class="fa-solid fa-plus mx-2"></i> Make Transaction
                                </button>
                            </ul>
                        </div>

                        <!--Recent Sales-->
                        <div class="col-md-6 col-12">
                            <div class="accordion-item bg-red">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button text-danger collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                        <i class="fa-solid fa-arrow-up mx-2"></i> Recent Sales
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse"
                                     aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse($tenRecentSales as $sale)
                                                <li class="list-group-item bg-red border-light">
                                                    <a href="{{route('transactions.show', ['transaction'=>$sale])}}"
                                                       class="text-decoration-none text-dark">
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col-lg-6 text-dark">
                                                                <strong>{{$sale->product->name}}</strong></div>
                                                            <div class="col-lg-6">x{{$sale->quantity}} Qty</div>
                                                            <div class="col-lg-6">{{$sale->discount}}% off</div>
                                                            <div class="col-lg-6">
                                                                @Rs.{{$sale->salesPriceDuringTransaction->value}}
                                                            </div>
                                                        </div>
                                                        <small class="text-dark">
                                                            {{$sale->created_at->format('l jS \of F')}}
                                                        </small>
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="list-group-item bg-red border-light text-light">
                                                    No Sales yet.
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <p class="d-flex justify-content-end py-2 px-2">
                                <a href="{{route('show-transactions', ['type'=>'sale'])}}">View all</a>
                            </p>

                        </div>

                        <!--Recent Purchases-->
                        <div class="col-md-6 col-12">
                            <div class="accordion-item bg-green">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button text-success collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="true"
                                            aria-controls="collapseTwo">
                                        <i class="fa-solid fa-arrow-up mx-2"></i> Recent Purchases
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse"
                                     aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @forelse($tenRecentPurchases as $purchase)
                                                <li class="list-group-item bg-green border-light">
                                                    <a href="{{route('transactions.show', ['transaction'=>$purchase])}}"
                                                       class="text-decoration-none text-dark">
                                                        <div class="row d-flex justify-content-between">
                                                            <div class="col-lg-6 text-dark">
                                                                <strong>{{$purchase->product->name}}</strong></div>
                                                            <div class="col-lg-6">x{{$purchase->quantity}} Qty</div>
                                                            <div class="col-lg-6">{{$purchase->discount}}% off</div>
                                                            <div class="col-lg-6">
                                                                @Rs.{{$purchase->salesPriceDuringTransaction->value}}
                                                            </div>
                                                        </div>
                                                        <small class="text-dark">
                                                            {{$purchase->created_at->format('l jS \of F')}}
                                                        </small>
                                                    </a>
                                                </li>
                                            @empty
                                                <li class="list-group-item bg-red border-light text-light">
                                                    No Purchases yet.
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <p class="d-flex justify-content-end py-2 px-2">
                                <a href="{{route('show-transactions', ['type'=>'purchase'])}}">View all</a>
                            </p>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade"
                             id="exampleModal"
                             tabindex="-1"
                             aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Fill Transaction Details</h5>
                                        <button type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                                aria-label="Close">
                                        </button>
                                    </div>

                                    <form action="{{route('transactions.store')}}"
                                          method="POST"
                                          id="transactionForm"
                                          class="row g-3 needs-validation modal-body" novalidate>
                                        @csrf
                                        @method('post')

                                        <div class="col-12 form-row my-2">
                                            <label for="categorySelect" class="form-label">Category</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="">
                                                    <i class="fa-solid fa-tag"></i>
                                                </span>
                                                <select name="categoryId"
                                                        id="categorySelect"
                                                        class="form-select"
                                                        autocomplete="off">
                                                    <option value="invalid" selected>Select category.</option>
                                                    @forelse ($categories as $category)
                                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @empty
                                                        <option value="invalid">No category. Try refreshing.</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 form-row hidden my-2 ">
                                            <label for="productSelect" class="form-label">Product</label>
                                            <div class="input-group">
                                                    <span class="input-group-text">
                                                        <i class="fa-solid fa-inbox"></i>
                                                    </span>
                                                <select name="productId"
                                                        id="productSelect"
                                                        class="form-select"
                                                        autocomplete="off">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 form-row hidden my-2">
                                            <label for="transactionTypeSelect" class="form-label">Type</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="fa-solid fa-plus-minus"></i>
                                                </span>
                                                <select name="transactionType" id="transactionTypeSelect"
                                                        class="form-select"
                                                        autocomplete="off">
                                                    <option value="invalid" selected>
                                                        Chose transaction type
                                                    </option>
                                                    <option value="1">Purchase</option>
                                                    <option value="2">Sales</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 form-row hidden row mx-0 px-0 my-2">
                                            <div class="col-6">
                                                <label for="salesPrice">Price</label>
                                                <input type="number" name='salesPrice' id='salesPrice' min=0
                                                       class="form-control money-field" autocomplete="off">
                                            </div>
                                            <div class="col-3">
                                                <label for="salesQuantity">Quantity</label>
                                                <input type="number" name="salesQuantity" id='salesQuantity' min=0
                                                       class="form-control quantity-field" autocomplete="off">
                                            </div>
                                            <div class="col-3">
                                                <label for="salesDiscount">Discount</label>
                                                <input type="number" name="discount" id='salesDiscount' min=0
                                                       class="form-control discount-field" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-12 form-row hidden row mx-0 px-0 my-2">
                                            <div class="col-6">
                                                <label for="purchasePrice">Price</label>
                                                <input type="number" name='purchasePrice' id="purchasePrice" min=0
                                                       class="form-control money-field" autocomplete="off">
                                            </div>
                                            <div class="col-6">
                                                <label for="purchaseQuantity">Quantity</label>
                                                <input type="number" name="purchaseQuantity" id="purchaseQuantity" min=0
                                                       class="form-control quantity-field" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-12 hidden">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                       id="confirmCheck" autocomplete="off" required>
                                                <label class="form-check-label" for="confirmCheck">
                                                    Confirm Transaction
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer hidden">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                            <button type="button" id="submitBtn" class="btn btn-primary">
                                                Save changes
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="{{asset('scripts/transactionIndex.js')}}" defer></script>
@endsection
