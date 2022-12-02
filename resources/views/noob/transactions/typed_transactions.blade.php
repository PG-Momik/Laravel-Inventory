@extends('layouts.noob_layout')

@section('title', "Transactions")

@section('activeTransactions', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('transactions.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Transactions</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="{{route('show-transactions', ['type'=>App\Models\TransactionType::ALL[lcfirst($type)]])}}"
           class="fs-4 text-grey"
           style="color: white">
            <u>{{$type}}</u>
        </a>
    </span>
@endsection

@section('content')
    <div class="p-4">
        <table class="table">
            <thead>
            <tr class="table-dark">
                <th scope="col">Date</th>
                <th scope="col">Product</th>
                <th scope="col" class="text-center">Transaction ID</th>
                <th scope="col" class="text-center">Quantity</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $transaction)
                <tr class="fs-6">
                    <td>{{$transaction->created_at->toDateString()}}</td>
                    <td>
                        @if($transaction->product)
                            {{$transaction->product->name}}
                        @else
                            <small class="text-danger">Deleted Product.</small>
                        @endif
                    </td>
                    <td class="text-center">{{$transaction->id}}</td>
                    <td class="text-center">{{$transaction->quantity}}</td>
                    <td class="text-center">
                        <a href="{{route('transactions.show', ['transaction'=>$transaction])}}"
                           class="btn btn-outline-primary border-primary rounded-0">
                            <i class="fa-solid fa-eye px-4"></i>
                        </a>
                        <a href="{{route('generate-pdf', ['transaction'=>$transaction])}}"
                           class="btn btn-outline-dark border-dark rounded-0">
                            <i class="fa-solid fa-download px-4"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No records found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    </div>
    </div>

    </div>
    </div>

    </div>

@endsection
