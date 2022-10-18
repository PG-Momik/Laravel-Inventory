@extends('layouts.auth')
@section('title', 'Register')
@section('content')
    <section class="container">
        <h1>Register</h1>
        <form action="{{route('register')}}" method="POST" autocomplete="off">
            @csrf

            @php
                $isInvalid = "";

                if($errors->any()){
                    $isInvalid = "is-invalid";
                }
            @endphp

            <div class="form-group my-2">
                <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Name">
                @error('name')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="form-group my-2">
                <input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="Email">
                @error('email')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <div class="form-group my-2">
                <input type="password" name="password" class="form-control" value="{{old('password')}}"
                       placeholder="Password">
                @error('password')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>

            <div class="form-group my-2">
                <input type="password" name="password_confirmation" class="form-control"
                       value="{{old('password_confirmation')}}" placeholder="Repeat Password">
                @error('password_confirmation')
                <small class="text-danger">{{$message}}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </section>
@endsection
