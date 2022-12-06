@extends('layouts.noob_layout')

@section('title', "Users")

@section('activeUsers', 'active-this')

@section('child-page-title', "Users")

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

@section('child-pagination', $users->links("pagination::bootstrap-5"))

@section('content')
    {{--White card goes here--}}
    <div class="p-4 pt-0">
        <div class="table-responsive border-this">
            <table class="table">
                <thead class="table-dark">
                <tr class="">
                    <th class="">Name</th>
                    <th class="">Role</th>
                    <th class="text-center">
                        Trans<span class="d-none d-md-inline">actions</span></th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="">{{$user->name}}</td>
                        <td class="">{{$user->roles[0]->name}}</td>
                        <td class="text-center">
                            <a href="{{route('users.transactions', ['id'=>$user->id])}}">
                                {{$user->transactions_count}}
                            </a>
                        </td>

                        <td class="d-flex text-center" style="column-gap: 0.5em">
                            <a href="{{route('users.show', ['user'=>$user])}}"
                               class="w-50 rounded-0 py-1 btn btn-outline-primary">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if(auth()->user()->can('trash users') && auth()->id() != $user->id)
                                <form action="{{route('users.destroy', ['user'=>$user])}}"
                                      method="post" class="w-50">
                                    @csrf
                                    @method('delete')
                                    <button type="submit"
                                            class="w-100 rounded-0 py-1 btn btn-outline-warning">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="4" class="text-center"><b>No user exists.</b></td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

