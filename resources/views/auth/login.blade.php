@extends('layouts.auth')
@section('title', 'Login')
@section('content')
    <section class="container">
        <h1>Login</h1>
        <form action="{{route('login') }}" method="POST">
            @csrf

            @php
                $isInvalid = "";
                if($errors->any()){
                    $isInvalid = "is-invalid";
                }
            @endphp

            <span class="text-danger">
            @error('email')
                <p class="alert alert-danger">{{$message}}</p>
            @enderror
        </span>

            <div class="form-group my-2">
                <input type="email" name="email" class="form-control {{$isInvalid}}" placeholder="Email">
            </div>

            <div class="form-group my-2">
                <input type="password" name="password" class="form-control {{$isInvalid}}" placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary my-2">Login</button>
        </form>
    </section>
@endsection
