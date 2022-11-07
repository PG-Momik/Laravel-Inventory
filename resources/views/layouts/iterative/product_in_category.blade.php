<div class="list-group-item row mx-0 d-flex justify-content-between">
    <div class="col">{{$product->name}}</div>
    <div class="col">{{$product->quantity}}</div>
    <div class="col d-xl-block d-none">
        Rs.{{$product->latestPurchasePrice->value}}</div>
    <div class="col d-xl-block d-none">
        Rs.{{$product->latestSalesPrice->value}}</div>
    <div class="col">
        <a href="{{route('products.show', ['product'=>$product])}}"
           class="btn btn-sm btn-outline-primary rounded-0"><i
                class="fa-solid fa-eye px-3"></i></a>
    </div>
</div>
