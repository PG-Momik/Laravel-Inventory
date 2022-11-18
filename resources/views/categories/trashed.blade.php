@extends('layouts.main')
@section('title', 'Trashed Categories')
@section('activeCategories', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a bg-purple round-this">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">

                    {{--Top--}}
                    <div class="row mx-0 d-flex gx-5  align-items-center">

                        <div class="col-xl-4 col-lg-4">
                            <h1>Trashed Categories</h1>
                        </div>

                        {{--Search Form--}}
                        <form action="{{route('categories.trashed')}}"
                              method="post"
                              class="col-xl-8 col-lg-8 row mx-0 align-items-center">
                            @csrf
                            @method('post')

                            <div class="col-xl-2 col-lg-2 col-0"></div>

                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                                <input type="text" name="search-field"
                                       class="form-control round-this px-3 col border-0 height-40"
                                       placeholder="Search category" value="{{$searchKeyword}}"
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
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('categories.index')}}" class="no-underline">
                                    <button class="btn btn-md bg-dark text-white col-12 round-this">
                                        <i class="fa-solid fa-list"></i> All
                                    </button>
                                </a>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <a href="{{route('categories.trashed')}}" class="no-underline">
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
                                {{$categories->links("pagination::bootstrap-5")}}
                            </div>

                            {{--                            Table--}}
                            <table class="table table-hover table-md">

                                {{alert()}}

                                <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Products</th>
                                    <th>Trashed</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->products_count}}</td>
                                        <td>{{$category->deleted_at->diffForHumans()}}</td>
                                        <td class="d-flex" style="column-gap: 0.8vw">
                                            @can('delete categories')
                                                <a href="{{route('categories.delete', ['id'=>$category->id])}}"
                                                   class="col no-underline btn btn-sm rounded-0 btn-outline-danger">
                                                    <i class="fa-solid fa-delete-left"></i>
                                                </a>
                                            @endcan

                                            @can('restore categories')
                                                <a href="{{route('categories.restore', ['id'=>$category->id])}}"
                                                   class="col no-underline btn btn-sm rounded-0 btn-outline-success">
                                                    <i class="fa-solid fa-rotate-left"></i>
                                                </a>
                                            @endcan
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
