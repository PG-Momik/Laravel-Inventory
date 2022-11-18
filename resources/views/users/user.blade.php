@extends('layouts.main')
@section('title', $user->name)
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
                                class="d-inline-flex px-4 align-items-end pb-1">
                                <a href="{{route('users.index')}}"
                                   class="text-decoration-none  nice-white-shadow">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Users</u></b>
                                    </span>
                                </a>
                                <span class="fs-3 ms-1 text-grey">/</span>
                                <a href="{{route('users.show', ['user'=>$user])}}"
                                   class="text-decoration-none fs-4 text-grey  nice-white-shadow">
                                    <u class="text-white">{{$user->name}}</u>
                                </a>
                            </span>
                        </div>

                        {{--Search Form--}}
                        <form action="{{route('users.search')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-8">
                                <input type="search" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search user" value=""
                                       style="max-height: 50px">
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
                            @can('create users')
                                <div class="col-lg-6 col-md-12">
                                    <a href="{{route('users.create')}}" class="no-underline">
                                        <button class="btn btn-md bg-blue text-white col-12 round-this">
                                            <i class="fa-solid fa-plus"></i> Add
                                        </button>
                                    </a>
                                </div>
                            @endcan
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

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
                            <dl class="row">
                                <h3>{{$user['name']}}</h3>
                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9">{{$user->email}}</dd>

                                <dt class="col-sm-3">Role</dt>
                                <dd class="col-sm-9">
                                    {{$user->roles[0]->name}}
                                </dd>
                                {{--                                {{dd($user)}}--}}

                                <dt class="col-sm-3">Email Verified</dt>
                                <dd class="col-sm-9">{!!  $user->email_verified_at?? "<span class='text-danger fw-2'>Not Verified</span>"!!}</dd>
                                <hr>

                                <dt class="col-sm-3">Number of Activities</dt>
                                <dd class="col-sm-9">
                                    {{$user->transactions_count}}
                                </dd>

                                <dt class="col-sm-3">Products Registered</dt>
                                <dd class="col-sm-9 row">
                                    @php($size = sizeof($user->registeredProducts))
                                    @foreach($user->registeredProducts as $products)
                                        @php($col = ceil(12/$size))
                                        <span class="d-block col-12 col-md-{{$col}}">
                                            <a href="{{route('products.show', ['product'=>$products])}}">{{$products->name}}</a>
                                        </span>
                                    @endforeach
                                </dd>
                                <dt class="col-sm-3"></dt>
                                <dd class="col-sm-9 row mx-0 px-0 justify-content-end">
                                    <div class="col-xl-4 col-md-6 col-12 row mx-0 g-2">
                                        @can('edit users')
                                            <a href="{{route('users.edit', ['user'=>$user])}}" class="no-underline">
                                                <button class="btn btn-md bg-outline-blue text-blue col-12 round-this">
                                                    <i class="fa-solid fa-pen"></i> Edit
                                                </button>
                                            </a>
                                        @endcan
                                        @can('trash users')
                                            <a href="" class="no-underline">
                                                <form action="{{route('users.destroy', ['user'=>$user])}}"
                                                      method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button
                                                        class="btn btn-md-3 bg-outline-yellow text-yellow col-12 round-this">
                                                        <i class="fa-solid fa-trash"></i> Trash
                                                    </button>
                                                </form>
                                            </a>
                                        @endcan

                                    </div>
                                </dd>

                            </dl>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection

