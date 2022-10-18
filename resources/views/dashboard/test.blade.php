@extends('layouts.main')
@section('title', 'Users')
@section('right-side')
    <div class="col-lg-8">
        <div class="px-5">
            <h1 class="mt-2">Test</h1>
            <form action="" method="post" class="row">
                @csrf
                <div class="col-lg-10">
                    <input type="text" name="search-field" class="form-control" value="{{$searchKeyword}}">
                </div>
                <div class="col-2 d-flex justify-content-between">
                    <button type="submit" class="btn btn-outline-primary px-3">Search</button>
                    <a href="" class="">
                        <button type="button" class="btn btn-outline-dark px-3">Reset</button>
                    </a>
                </div>
            </form>

            <div class="row my-2">
                <div class="col-lg-2">
                    <a href="/{{app()->getLocale()}}/customer/add" class="no-underline">
                        <button class="btn btn-md btn-primary col-12">
                            Add
                        </button>
                    </a>
                </div>
                <div class="col-lg-8"></div>
                <div class="col-lg-2">
                    <a href="/{{app()->getLocale()}}/customer/trashed" class="no-underline">
                        <button class="btn btn-md btn-warning col-12">
                            Trashed Data
                        </button>
                    </a>
                </div>
            </div>

            <div class="row">
                {{$users->links("pagination::bootstrap-5")}}
            </div>

            @php
                $isRegister = Session()->has('register_msg');
                $isRestore = Session()->has('restore_msg');
                $isAdd = Session()->has('add_msg');
                $isTrash = Session()->has('trash_msg');
                $isDelete = Session()->has('delete_msg');

                switch ([$isRegister, $isRestore, $isAdd, $isTrash, $isDelete]){
                    case [1,0,0,0,0]:
                            echo "<p class='alert alert-primary'>".session()->get('register_msg').'</p>';
                        break;
                    case [0,1,0,0,0]:
                            echo "<p class='alert alert-success'>".session()->get('restore_msg').'</p>';
                        break;
                    case [0,0,1,0,0]:
                            echo "<p class='alert alert-success'>".session()->get('add_msg').'</p>';
                        break;
                    case [0,0,0,1,0]:
                            echo "<p class='alert alert-warning'>".session()->get('trash_msg').'</p>';
                        break;
                    case [0,0,0,0,1]:
                            echo "<p class='alert alert-danger'>".session()->get('delete_msg').'</p>';
                        break;
                }
    //        @endphp

            <table class="table table-hover text-center">
                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Transaction Made</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->roles->name}}</td>
                        <td>{{$user->transactions_count}}</td>
                        <td class="row d-flex" style="column-gap: 0.8vw">
                            <a href="" class="col btn btn-sm btn-outline-primary rounded-0 px-2">View</a>
                            <a href="" class="col btn btn-sm btn-outline-warning rounded-0 px-2">Trash</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection
