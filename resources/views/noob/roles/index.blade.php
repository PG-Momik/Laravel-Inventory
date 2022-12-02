@extends('layouts.noob_layout')

@section('title', "Roles")

@section('activeRoles', 'active-this')

@section('child-page-title', "Roles")

@section('content')
    {{--White card goes here--}}
    <div class="p-4">
        <div class="table-responsive border-this">
            <table class="table table-hover table-md">
                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>No. of Users</th>
                    <th class="text-center">Privileges</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @each('layouts.iterative.role', $roles, 'role')
                </tbody>
            </table>
        </div>
    </div>
@endsection
