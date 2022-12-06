@extends('layouts.noob_layout')

@section('title', "Roles")

@section('activeRoles', 'active-this')

@section('child-page-title-when-nested')
    <span class="d-inline-flex align-items-end pb-1">
        <a href="{{route('roles.index')}}" class="text-decoration-none">
            <span class="fs-3 p-0 text-dark">
                <b><u>Roles</u></b>
            </span>
        </a>
        <span class="fs-3 ms-1 text-grey">/</span>
        <a href="{{route('roles.show', ['role'=>$role])}}" class="fs-4 text-grey"
           style="color: white"><u>{{$role->name}}</u></a>
    </span>
@endsection

@section('search-bar')
@endsection

@section('button-group')
@endsection

@section('content')

    <div class="row p-4">
        <div class="col-lg-6 col-12 px-2">
            <div class="shadow p-3 rounded">
                <h2>Privileges</h2>
                <table class="table table-bordered w-equal text-center">

                    <tr class="bg-dark text-white">
                        <th></th>
                        <th>Add</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>

                    <tr>
                        <td>User</td>
                        <td>
                            <i class="fa-solid fa-check fs-5"></i>
                        </td>
                        <td>
                            <i class="fa-solid fa-check fs-5"></i>
                        </td>
                        <td>
                            {!! $role->id==1?'<i class="fa-solid fa-check fs-5"></i>':'<i class="fa-solid fa-xmark fs-5"></i>'!!}
                        </td>
                    </tr>
                    <tr>
                        <td>Product</td>
                        <td>
                            <i class="fa-solid fa-check fs-5"></i>
                        </td>
                        <td>
                            <i class="fa-solid fa-check fs-5"></i>
                        </td>
                        <td>
                            {!! $role->id==1?'<i class="fa-solid fa-check fs-5"></i>':'<i class="fa-solid fa-xmark fs-5"></i>'!!}
                        </td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>
                            <i class="fa-solid fa-check fs-5"></i>
                        </td>
                        <td>
                            {!! $role->id==1?'<i class="fa-solid fa-check fs-5"></i>':'<i class="fa-solid fa-xmark fs-5"></i>'!!}
                        </td>
                        <td>
                            {!! $role->id==1?'<i class="fa-solid fa-check fs-5"></i>':'<i class="fa-solid fa-xmark fs-5"></i>'!!}
                        </td>
                    </tr>
                    <tr>
                        <td>Transaction</td>
                        <td>
                            <i class="fa-solid fa-check fs-5"></i>
                        </td>
                        <td>
                            {!! $role->id==1?'<i class="fa-solid fa-check fs-5"></i>':'<i class="fa-solid fa-xmark fs-5"></i>'!!}
                        </td>
                        <td>
                            {!!'<i class="fa-solid fa-xmark fs-5"></i>'!!}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-lg-6 col-12 px-2 my-2">
            <div class="shadow p-3 rounded py-3">
                <h2>Users with this role:</h2>
                <hr>
                <div class="list-group">
                    @forelse($role->users as $user)
                        <div
                            class="list-group-item row mx-0 px-0 d-flex justify-content-between">
                            <span class="col-md-6 col-12">{{$user->name}}</span>
                            <span class="col-md-6 col-12 text-end">
                                    <a href="{{route('users.show', ['user'=>$user])}}"
                                       class="btn btn-sm btn-outline-primary rounded-0">
                                        <i class="fa-solid fa-eye px-4"></i>
                                    </a>
                                    @can('update roles')
                                    @if($role->id == 1)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-0"
                                                onclick="demoteUser(this, {{$user->id}})">
                                                Demote
                                        </button>
                                    @else
                                        <button type="button"
                                                class="btn btn-sm btn-outline-success rounded-0"
                                                onclick="promoteUser(this, {{$user->id}})">
                                                Promote
                                        </button>
                                    @endif
                                @endcan
                            </span>
                        </div>
                    @empty
                        <div class="list-group-item">No User</div>
                    @endforelse
                </div>
                <br>
                <br>
                <hr>
                <div class="text-light">
                    <canvas id="myChart" class="text-light"></canvas>
                </div>

            </div>
        </div>
    </div>

    @push('other-scripts')

        <script src="{{asset('scripts/noob/utilities.js')}}"></script>

        <script>
            let myChart = null;

            $(document).ready(function () {
                ajaxBarValues()
            });

            function demoteUser(target, uid) {
                let parentSpan = target.parentNode;
                let parentWrapper = parentSpan.parentNode;
                $.ajax({
                    type: 'get',
                    url: `demote/${uid}`,
                    success: function (result) {
                        if (result) {
                            parentWrapper.classList.add('slowly-disappear');
                            setTimeout(function () {
                                parentWrapper.remove();
                                ajaxBarValues();
                            }, 1000)
                        }
                    }
                });
            }

            function promoteUser(target, uid) {
                let parentSpan = target.parentNode;
                let parentWrapper = parentSpan.parentNode;
                $.ajax({
                    type: 'get',
                    url: `promote/${uid}`,
                    success: function (result) {
                        if (result) {
                            parentWrapper.classList.add('slowly-disappear');
                            setTimeout(function () {
                                parentWrapper.remove();
                                ajaxBarValues();
                            }, 1000)
                        }
                    }
                });
            }

            function ajaxBarValues() {
                let url = "{{route('roles-stats')}}";

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (result) {
                        drawBar(result)

                    }
                })
            }

            function drawBar(result = '') {
                if (myChart != null) {
                    myChart.destroy();
                }

                const ctx = document.getElementById('myChart').getContext('2d');
                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: extractLabels(result, []),
                        datasets: [
                            {
                                label: 'Role Population',
                                barPercentage: 0.5,
                                backgroundColor: colorArray(),
                                data: extractData(result, []),
                                hoverOffset: 16,
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                    }

                })
            }
        </script>

    @endpush

@endsection

