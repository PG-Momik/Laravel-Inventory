@extends('layouts.main')

@section('title', "Add Users")

@section('activeUsers', 'active-this')

@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a round-this border-black grad">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    {{-- <div class="col-xl-12 col-lg-6 col-md-4 col-sm-3 col-2 border-this"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur eaque nesciunt omnis porro possimus quis? Cupiditate dignissimos ipsa iste, iusto, non pariatur, possimus quasi quo reprehenderit sint suscipit veniam!</div>--}}

                    <div class="row mx-0 d-flex gx-5 align-items-center">

                        <div class="col-xl-4 col-lg-4 p-0">
                            <span
                                class="d-inline-flex nice-white-shadow px-4 align-items-end pb-1">
                                <a href="{{route('users.index')}}" class="text-decoration-none">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Users</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <span class="fs-4 text-grey"><u>{{$user['name']}}</u></span>
                            </span>
                        </div>
                        {{--                        Search Form--}}
                        <form action="{{route('users.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-8">
                                <input type="search" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search user" value="" style="max-height: 50px"/>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-4 col-4 row mx-0 justify-content-center">
                                <button type="submit" class="btn bg-outline-dark round-button m-1 height-40 width-40">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button type="button" class="btn bg-outline-white round-button m-1 height-40 width-40">
                                    <i class="fa-sharp fa-solid fa-rotate-left"></i>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
                            <form action="{{route('users.update', ['user'=>$user->id])}}" method="post">
                                @csrf
                                @method('put')

                                <div class="row my-2">
                                    <div class="col-md-6 position-relative">
                                        <x-input type="text" name="name" label="Name" id="name"
                                                 value="{{$user->name??''}}"/>
                                    </div>

                                    <div class="col-md-6 form-group">
                                        <x-input type="email" name="email" label="Email" id="email"
                                                 value="{{$user->email??''}}"/>
                                    </div>
                                </div>

                                <div class="row my-2">
                                    <div class="col-md-6 form-group">
                                        @php($roles = array('Choose Role.'=>'0', 'Admin'=>'1', 'Users'=>'2'))
                                        <x-input type="select" name="role_id" label="Role" id="role_id"
                                                 value="{{$user->role_id}}" :keyVal="$roles"/>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <x-input type="checkbox" name="checkbox" id="checkbox" label="Verify Email?" checked="{{$user->email_verified_at?'true':'false'}}"/>
                                    </div>
                                </div>

                                <hr>
                                <div class="col-12 row mx-0 px-0 justify-content-center">
                                    <div class="col-md-6 col-12 row mx-0 g-2">
                                        <button type="submit" class="btn btn-md bg-outline-green text-blue col-12 round-this">
                                            <i class="fa-solid fa-pen"></i> Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mt-5 round-this mx-4 h-100">
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

