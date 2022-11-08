@extends('layouts.main')
@section('title', $role->name)
@section('activeRoles', 'active-this')
@section('right-side')

    <div class="grid-item">

        <div class="admin-grid">
            <div style="min-height: 460px" class="a round-this border-black grad">
                <div class="bg-purple px-5 pt-3 py-4" style="border-radius: 20px 20px 0 0">
                    {{-- <div class="col-xl-12 col-lg-6 col-md-4 col-sm-3 col-2"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid consectetur eaque nesciunt omnis porro possimus quis? Cupiditate dignissimos ipsa iste, iusto, non pariatur, possimus quasi quo reprehenderit sint suscipit veniam!</div>--}}

                    <div class="row mx-0 d-flex gx-5 align-items-center">

                        <div class="col-xl-4 col-lg-4 p-0">
                            <span
                                class="d-inline-flex nice-white-shadow px-4 align-items-end pb-1">
                                <a href="{{route('roles.index')}}" class="text-decoration-none">
                                    <span class="fs-3 p-0 text-dark">
                                        <b><u>Role</u></b>
                                    </span>
                                </a>
                                    <span class="fs-3 ms-1 text-grey">/</span>
                                <span class="fs-4 text-grey"><u>{{$role->name}}</u></span>
                            </span>
                        </div>

                    </div>
                </div>

                {{--White Card Goes Here--}}
                <div class="b grad" style="height:350px; border-radius: 0 0 20px 20px">
                    <div style="width: 80%; margin: 0 auto;">
                        <div class="p-5 bg-white round-this shadow-this-down">
                            <div class="row">
                                <div class="col-lg-6 col-12 px-2">
                                    <div class="shadow p-3 rounded">
                                        <h1>Role: {{$role->name}}</h1>
                                        <hr>
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
                        </div>
                    </div>

                </div>
            </div>

        </div>


    </div>

    <style>

        .slowly-disappear {
            visibility: hidden;
            transition: all 0.8s ease-out;
            opacity: 0
        }

    </style>

    @push('other-scripts')

        <script src="{{asset('scripts/utilities.js')}}"></script>

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

