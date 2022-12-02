@extends('layouts.noob_layout')

@section('title', "Transactions")

@section('activeTransactions', 'active-this')

@section('child-page-title', "Transactions")

@section('content')


    {{--White card goes here--}}

    {{--Navigation Button Group--}}
    <div class="row mb-4 mt-4 px-4 d-flex align-middle">

        <div class="col-lg-2 col-md-3 col-12 ">
            <span class="fs-4">Navigate:</span>
        </div>

        {{ showDropdownNavigation($dropdownOptions) }}

    </div>
    {{--Navigation Button Group--}}

    {{--Button trig-ger modal--}}
    <div class="row mb-4 px-4 justify-content-between">
        {{--QR Button--}}
        <x-transaction-qr-btn></x-transaction-qr-btn>
        {{--/QR Button--}}

        {{--Make Transaction Btn--}}
        <x-transaction-make-btn></x-transaction-make-btn>
        {{--Make Transaction Btn--}}
    </div>
    {{--/Button trigger modal--}}

    <div class="row mb-4 px-4">
        {{--Recent sales--}}
        <div class="col-md-6 col-12">
            <x-ten-recent-transactions :tenTransactions=$tenRecentPurchases type="sales"></x-ten-recent-transactions>

        </div>
        {{--/Recent sales--}}

        {{--Recent purchases--}}
        <div class="col-md-6 col-12">
            <x-ten-recent-transactions :tenTransactions=$tenRecentSales type="purchase"></x-ten-recent-transactions>
        </div>
        {{--/Recent purchases--}}
    </div>

    {{--Transaction Modal--}}
    <x-transaction-make-modal :categories=$categories></x-transaction-make-modal>
    {{--/Transaction Modal--}}

    {{--Camera Modal--}}
    <x-transaction-qr-modal></x-transaction-qr-modal>
    {{--/Camera Modal--}}

    {{--White card goes here--}}
    <style>
        #preview {
            width: 100%;
            height: 100%;
        }
    </style>
    @push('other-scripts')
        <script src="{{asset('scripts/noob/transactionIndex.js')}}" defer></script>
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script src="{{asset('scripts/noob/qrScripts.js')}}"></script>
    @endpush
@endsection
