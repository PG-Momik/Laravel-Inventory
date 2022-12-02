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
    <x-view-trashed-entity-btn :route="route('products.trashed')" entity="products">
    </x-view-trashed-entity-btn>
@endsection

@section('content')

    <div class="p-4 pt-0">
        <form action="{{route('categories.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="row align-middle">
                <div class="mb-2 col-lg-8 col-12">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>

                <div class="col-lg-4 col-12 pt-4">
                    <button type="reset"
                            class="btn btn-outline-dark px-5 nice-round col-sm-12 col-lg-auto mb-2">
                        Reset
                    </button>
                    <button type="submit"
                            class="btn btn-outline-primary px-5 nice-round col-sm-12 col-lg-auto mb-2">
                        Add
                    </button>
                </div>
            </div>
        </form>
    </div>
    <style>
        #imageUploadFacade:hover {
            cursor: pointer;
        }
    </style>

@endsection

