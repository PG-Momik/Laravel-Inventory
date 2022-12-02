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
        <a href="javascript:void(0)" class="fs-4 text-grey"
           style="color: white"><u>add</u></a>
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
        {{--Register Form--}}
        <form action="{{route('users.store')}}" method="post"
              class="container-fluid round-this needs-validation">
            @csrf

            <div class="row my-2">
                <div class="col-md-6 form-group">
                    <label for="name">Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-quote-left"></i>
                        </span>
                        <input type="text"
                               class="form-control
                                          @if($errors->has('name')) is-invalid @endif"
                               name="name" value="{{old('name')??''}}"
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
                               name="email" value="{{old('email')??''}}"
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
                            <option value="Invalid">Select role</option>
                            <option value="Admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-eye"></i>
                        </span>
                        <input type="password"
                               class="form-control
                               @if($errors->has('password')) is-invalid @endif"
                               name="password" placeholder="Default password (Password#123) if empty."
                               id="password"
                               aria-describedby="password">
                        <div class="invalid-feedback" id="password">
                            @error('password')
                            {{$message}}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-0 justify-content-around">
                <button type="submit" name="register-btn"
                        class="col-md-4 col-12 btn btn-outline-success py-2 mt-2">
                    Add
                </button>
                <button type="reset" name="clear-btn"
                        class="col-md-4 col-12 btn btn-outline-dark text-black py-2 mt-2">
                    Clear
                </button>
            </div>

        </form>

    </div>

@endsection
