@extends('layouts.main')
@section('title', 'Users')
@section('activeUsers', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                            <h1>Users</h1>
                        </div>

                        {{--Search Form--}}
                        <form action="{{route('users.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <input type="text" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search user" value="{{$searchKeyword}}" style="max-height: 50px">
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-4 col-4 row mx-0 justify-content-center">
                                <button type="submit" class="btn bg-outline-dark round-button m-1 height-40 width-40">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button type="reset" class="btn bg-outline-white round-button m-1 height-40 width-40">
                                    <i class="fa-sharp fa-solid fa-rotate-left"></i>
                                </button>
                            </div>
                        </form>

                    </div>

                    {{--Button Group--}}
                    <div class="row mx-0 d-flex gx-5">
                        <div class="col-xl-4 col-lg-6 row mx-0">
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('users.create')}}" class="no-underline">
                                    <button class="btn btn-md bg-blue text-white col-12 round-this">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('users.trashed')}}" class="no-underline">
                                    <button class="btn btn-md-3 bg-yellow text-white col-12 round-this">
                                        <i class="fa-solid fa-trash"></i> Trashed
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                {{--White card goes here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">

                            {{--                            Pagination--}}
                            <div class="col-12 text-dark">
                                {{$users->links("pagination::bootstrap-5")}}
                            </div>

                            {{--                            Table--}}
                            <table class="table table-hover table-md">

                                {{ alert()}}

                                <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-center">Activities</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->role->name}}</td>
                                        <td class="text-center">
                                            <a href="{{route('users.transactions', ['id'=>$user->id])}}">
                                                {{$user->transactions_count}}
                                            </a>
                                        </td>
                                        <td class="d-flex text-center" style="column-gap: 0.8vw">
                                            <a href="{{route('users.show', ['user'=>$user])}}"
                                               class="col btn btn-sm btn-outline-primary rounded-0 px-2">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="" class="col no-underline">
                                                <form action="{{route('users.destroy', ['user'=>$user->id])}}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button
                                                        class="btn btn-sm bg-outline-yellow rounded-0 text-yellow col-12 px-4">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                </form>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-5 round-this mx-4 h-100">
                    </div>

                </div>


            </div>
        </div>


    </div>

@endsection
