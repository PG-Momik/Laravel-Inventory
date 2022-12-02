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
        <a href="{{route('users.show', ['user'=>$user])}}"
           class="fs-4 text-grey" style="color: white">
            <u>{{$user['name']}}</u>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <span class="fs-4 text-light"><u>edit</u></span>
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
        <form action="{{route('users.update', ['user'=>$user])}}" method="post">
            @csrf
            @method('put')

            <div class="row my-2">
                <div class="col-md-6 position-relative">
                    <label for="name">Name</label>
                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-quote-left"></i>
                                            </span>
                        <input type="text"
                               class="form-control
                                           @if($errors->has('name')) is-invalid @endif"
                               name="name" value="{{$user->name??old('name')}}"
                               id="name"
                               aria-describedby="name" required>
                        <div class="invalid-feedback" id="name">
                            @error('name')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label for="email">Email</label>
                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                        <input type="email"
                               class="form-control
                                           @if($errors->has('email')) is-invalid @endif"
                               name="email" value="{{$user->email??old('email')}}"
                               id="email"
                               aria-describedby="email" required>
                        <div class="invalid-feedback" id="email">
                            @error('email')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row my-2">
                <div class="col-md-6 form-group">
                    <label for="role">Role</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user-tag"></i>
                        </span>
                        <select name="role" id="role"
                                class="form-select
                                @if($errors->has('role')) is-invalid @endif">
                            <option value="Invalid" disabled>
                                Select role
                            </option>
                            <option value="1"
                                @selected($user->roles[0]->name == "Admin")>
                                Admin
                            </option>
                            <option value="2"
                                @selected($user->roles[0]->name == "User")>
                                User
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 form-group">
                    <label for="role">Email verification</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-question"></i>
                        </span>
                        <div class="btn-group" role="group"
                             aria-label="Basic radio toggle button group">
                            <input type="radio"
                                   class="btn-check"
                                   name="verified"
                                   id="btnradio1"
                                   value="verified"
                                   autocomplete="off"
                                @checked(!empty($user->email_verified_at))>
                            <label class="btn btn-outline-primary"
                                   id="ratioBtnWrapper1"
                                   for="btnradio1">Verified</label>

                            <input type="radio"
                                   class="btn-check"
                                   name="verified"
                                   id="btnradio2"
                                   value="unverified"
                                   autocomplete="off"
                                @checked(empty($user->email_verified_at))>
                            <label class="btn btn-outline-danger" id="ratioBtnWrapper2"
                                   for="btnradio2">Not Verified</label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>
            <div class="col-12 row mx-0 px-0 justify-content-center">
                <div class="col-md-6 col-12 row mx-0 g-2">
                    <button type="submit"
                            class="btn btn-md bg-outline-green text-blue col-12">
                        <i class="fa-solid fa-pen"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

