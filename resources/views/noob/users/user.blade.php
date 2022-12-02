@extends('layouts.noob_layout')

@section('title', "Users")

@section('activeUsers', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('users.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Users</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="{{route('users.show', ['user'=>$user])}}" class="fs-4 text-grey" style="color: white"><u>{{$user['name']}}</u></a>
    </span>
@endsection

@section('search-bar')
    <x-search-bar :searchRoute="route('users.search')" :searchKeyword="$searchKeyword??''" placeholder="User">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('users.create')" entity="users">
    </x-add-entity-btn>
    <x-view-trashed-entity-btn :route="route('users.trashed')" entity="users">
    </x-view-trashed-entity-btn>
@endsection

@section('content')
    <div class="p-4">
        <dl class="row">
            <h3>{{$user['name']}}</h3>
            <dt class="col-sm-3">Email</dt>
            <dd class="col-sm-9">{{$user->email}}</dd>

            <dt class="col-sm-3">Role</dt>
            <dd class="col-sm-9">
                {{$user->roles[0]->name}}
            </dd>
            {{--                                {{dd($user)}}--}}

            <dt class="col-sm-3">Email Verified</dt>
            <dd class="col-sm-9">{!!  $user->email_verified_at?? "<span class='text-danger fw-2'>Not Verified</span>"!!}</dd>
            <hr>

            <dt class="col-sm-3">Number of Activities</dt>
            <dd class="col-sm-9">
                {{$user->transactions_count}}
            </dd>

            <dt class="col-sm-3">Products Registered</dt>
            <dd class="col-sm-9 row">
                @php($size = sizeof($user->registeredProducts))
                @foreach($user->registeredProducts as $products)
                    @php($col = ceil(12/$size))
                    <span class="d-block col-md-{{$col}} col-6">
                        <a href="{{route('products.show', ['product'=>$products])}}"
                       class="text-wrap">{{$products->name}}</a>
                    </span>
                @endforeach
            </dd>
            <dt class="col-sm-3"></dt>
            <dd class="col-sm-9 row mx-0 px-0 justify-content-end">
                <div class="col-lg-3 col-md-6 col-12 row mx-0 g-2">
                    @can('edit users')
                        <a href="{{route('users.edit', ['user'=>$user])}}" class="no-underline">
                            <button class="btn btn-md bg-outline-blue text-blue col-12 rounded-0">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                        </a>
                    @endcan
                    @can('trash users')
                        <a href="" class="no-underline">
                            <form action="{{route('users.destroy', ['user'=>$user])}}"
                                  method="post">
                                @csrf
                                @method('delete')
                                <button
                                    class="btn btn-md bg-outline-yellow text-yellow col-12 rounded-0">
                                    <i class="fa-solid fa-trash"></i> Trash
                                </button>
                            </form>
                        </a>
                    @endcan

                </div>
            </dd>

        </dl>
    </div>
@endsection
