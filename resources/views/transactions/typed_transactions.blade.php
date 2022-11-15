@extends('layouts.main')

@section('title', "Transactions")

@section('activeTransactions', 'active-this')

@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">

                <!--Active Page-->
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    <div class="col-xl-4 col-lg-4">
                           <span class="d-inline-flex pb-1
                                px-4 align-items-end">
                                <a href="{{route('transactions.index')}}" class="text-decoration-none nice-white-shadow">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Transactions</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <a href="#" class="text-decoration-none nice-white-shadow">
                                    <u class="fs-4 text-white">{{$type}}</u>
                                </a>
                            </span>
                    </div>
                </div>

                <!--White card-->
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">

                            <div class="col-12">{{$transactions->links("pagination::bootstrap-5")}}</div>

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
                                        <td>{{$transaction->product->name}}</td>
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
