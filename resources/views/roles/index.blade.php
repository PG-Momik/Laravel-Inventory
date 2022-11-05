@extends('layouts.main')

@section('title', "Roles")
@section('activeRoles', 'active-this')

@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 440px" class="a bg-purple round-this border-black">
                <div class="bg-purple px-5 pt-3 pb-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">
                        <div class="col-xl-4 col-lg-4">
                            <h1>Roles</h1>
                        </div>
                    </div>

                </div>


                {{--White card goes here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
                            <table class="table table-hover table-md">

                                {{ alert() }}

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
                </div>

            </div>
        </div>

    </div>

@endsection
