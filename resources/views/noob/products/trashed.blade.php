@extends('layouts.noob_layout')

@section('title', "Trashed products")

@section('activeProducts', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('products.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Products</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="javascript:void(0)"
           class="fs-4 text-grey" style="color: white">
            <u>trashed</u>
        </a>
    </span>
@endsection

@section('search-bar')
    <x-search-bar :searchRoute="route('products.trashed')" :searchKeyword="$searchKeyword??''"
                  placeholder="Trashed product">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('products.create')" entity="products">
    </x-add-entity-btn>
    <x-all-entity-btn :route="route('products.trashed')" entity="products">
    </x-all-entity-btn>
@endsection

@section('content')
    <div class="p-4 pt-0">

        <table class="table table-hover">
            <thead class="table-dark">
            <tr>
                <th>Name
                <th>Category</th>
                <th>In stock</th>
                <th>Trashed</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($products as $product)
                <tr>
                    <td>{{$product->name}}</td>
                    <td>
                        <a href="{{route('categories.show', ['category'=>$product->category])}}">{{$product->category->name}}</a>
                    </td>
                    <td>{{$product->quantity}}</td>
                    <td>{{$product->deleted_at->diffForHumans()}}</td>
                    <td class="d-flex" style="column-gap: 0.8vw">
                        @can('delete products')
                            <a href="{{route('products.delete', ['id'=>$product->id])}}"
                               class="col no-underline btn btn-sm rounded-0 btn-outline-danger">
                                <i class="fa-solid fa-delete-left"></i>
                            </a>
                        @endcan
                        @can('restore products')
                            <a href="{{route('products.restore', ['id'=>$product->id])}}"
                               class="col no-underline btn btn-sm rounded-0 btn-outline-success">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
