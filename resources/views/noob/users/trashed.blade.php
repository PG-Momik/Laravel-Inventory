@extends('layouts.noob_layout')

@section('title', "Trashed Users")

@section('activeUsers', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('users.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Users</u></b>
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
    <x-search-bar :searchRoute="route('users.trashed')" :searchKeyword="$searchKeyword??''" placeholder="Trashed User">
    </x-search-bar>
@endsection

@section('button-group')
    <x-add-entity-btn :route="route('users.create')" entity="users">
    </x-add-entity-btn>
    <x-all-entity-btn :route="route('users.trashed')" entity="users">
    </x-all-entity-btn>
@endsection

@section('content')
    <div class="p-4 pt-0">
        <table class="table table-hover table-md">

            <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Trashed</th>
                <th class="text-center">Action</th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->deleted_at->diffForHumans()}}</td>
                    <td class="d-flex" style="column-gap: 0.8vw">
                        @can('delete users')
                            <a href="{{route('users.delete', ['id'=>$user->id])}}"
                               class="col no-underline btn btn-sm rounded-0 btn-outline-danger">
                                <i class="fa-solid fa-delete-left"></i>
                            </a>
                        @endcan
                        @can('restore users')
                            <a href="{{route('users.restore', ['id'=>$user->id])}}"
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
