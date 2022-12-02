@php
    $neededKey = 'salesPriceDuringTransaction';
    $displayText = 'Sales';
    $bgColor = "bg-red";
    $textColor = "text-danger";
    $arrowIcon = "<i class='fa-solid fa-arrow-up mx-2'></i>";

    //This error doesnt matter. $type will be passed from component class
    if($type == App\Models\TransactionType::PURCHASE){
        $bgColor = "bg-green";
        $displayText = 'Purchases';
        $textColor = "text-success";
        $neededKey = 'purchasePriceDuringTransaction';
        $arrowIcon = "<i class='fa-solid fa-arrow-down mx-2'></i>";
    }
@endphp

<div class="accordion-item {{$bgColor}}">
    <h2 class="accordion-header" id="heading{{$displayText}}">
        <button class="accordion-button {{$textColor}} collapsed" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapse{{$displayText}}" aria-expanded="true"
                aria-controls="collapse{{$displayText}}">
            {!! $arrowIcon !!}Recent {{$displayText}}
        </button>
    </h2>
    <div id="collapse{{$displayText}}" class="accordion-collapse collapse"
         aria-labelledby="heading{{$displayText}}" data-bs-parent="#accordionExample">
        <div class="accordion-body">
            <ul class="list-group">
                @forelse($tenTransactions as $transaction)
                    <li class="list-group-item {{$bgColor}} border-light">
                        <a href="{{route('transactions.show', ['transaction'=>$transaction])}}"
                           class="text-decoration-none text-dark">
                            <div class="row d-flex justify-content-between">
                                <div class="col-lg-6 text-dark">
                                    <strong>{!! $transaction->product->name??"<small class='text-danger'>Deleted Product.</small>" !!}</strong>
                                </div>
                                <div class="col-lg-6">x{{$transaction->quantity}} Qty</div>
                                <div class="col-lg-6">{{$transaction->discount}}% off</div>
                                <div class="col-lg-6">
                                    @Rs.{{$transaction->$neededKey->value}}
                                </div>
                            </div>
                            <small class="text-dark">
                                {{$transaction->created_at->format('l jS \of F')}}
                            </small>
                        </a>
                    </li>
                @empty
                    <li class="list-group-item bg-red border-light text-light">
                        No {{$displayText}} yet.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<p class="d-flex justify-content-end py-2 px-2">
    <a href="{{route('show-transactions', ['type'=>$type])}}">View all</a>
</p>
