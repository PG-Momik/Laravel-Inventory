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
                                <option
                                    value="{{$category->id}}">{{$category->name}}</option>
                            @empty
                                <option value="invalid">No category. Try refreshing.
                                </option>
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
                        <input type="number" name="purchaseQuantity" id="purchaseQuantity"
                               min=0
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
