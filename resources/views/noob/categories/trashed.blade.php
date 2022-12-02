@extends('layouts.noob_layout')

@section('title', "Categories")

@section('activeCategories', 'active-this')

@section('child-page-title', "Categories")

@section('search-bar')
    <x-search-bar :searchRoute="route('categories.search')" :searchKeyword="$searchKeyword??''" placeholder="Category">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('categories.create')" entity="categories">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('categories.trashed')" entity="categories">
    </x-view-trashed-entity-btn>
@endsection

@section('search-bar')
    <x-search-bar :searchRoute="route('products.search')" :searchKeyword="$searchKeyword??''" placeholder="product">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('products.create')" entity="products">
    </x-add-entity-btn>
    <x-all-entity-btn :route="route('products.trashed')" entity="products">
    </x-all-entity-btn>
@endsection

@section('content')

    <div class="row p-4 pt-0">
        <div class="table-responsive">
            <table class="table table-hover table-md">

                {{alert()}}

                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Products</th>
                    <th>Trashed</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($categories as $category)
                    <tr>
                        <td>{{$category->name}}</td>
                        <td>{{$category->products_count}}</td>
                        <td>{{$category->deleted_at->diffForHumans()}}</td>
                        <td class="d-flex" style="column-gap: 0.8vw">
                            @can('delete categories')
                                <a href="{{route('categories.delete', ['id'=>$category->id])}}"
                                   class="col no-underline btn btn-sm rounded-0 btn-outline-danger">
                                    <i class="fa-solid fa-delete-left"></i>
                                </a>
                            @endcan

                            @can('restore categories')
                                <a href="{{route('categories.restore', ['id'=>$category->id])}}"
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
    </div>

@endsection
