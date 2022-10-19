@extends('layouts.main')

@section('title', "Roles")
@section('activeProfile', 'active-this')

@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    <div class="row mx-0 d-flex gx-5  align-items-center">
                        <div class="col-xl-4 col-lg-4">
                            <h1>Roles</h1>
                        </div>
                        <form class="col-xl-8 col-lg-8 row mx-0 align-items-center" action="{{route('users.index')}}"
                              method="post">
                            @csrf
                            <div class="col-xl-2 col-lg-2 col-0"></div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <input type="text" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search user" value="" style="max-height: 50px">
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
                    <div class="row mx-0 d-flex gx-5">
                        <div class="col-xl-4 col-lg-6 row mx-0">
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('users.index')}}" class="no-underline">
                                    <button class="btn btn-md bg-blue text-white col-12 round-this">
                                        <i class="fa-solid fa-plus"></i> Add
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <a href="/customer/trashed" class="no-underline">
                                    <button class="btn btn-md-3 bg-yellow text-white col-12 round-this">
                                        <i class="fa-solid fa-trash"></i> Trashed
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <img src="images/sampleDash.jpg" alt="" style="width: 100%">
                </div>
            </div>

        </div>


    </div>

@endsection
