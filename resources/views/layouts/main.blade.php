<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"
            defer></script>


    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>
<body class="">
{{--NAVBAR--}}
<nav class="navbar navbar-expand-lg navbar-light bg-dark d-flex d-flex justify-content-end px-4 mb-2 bg-grey"
     style="box-shadow: 0px 2px 8px 0px">
    <div class="d-flex justify-content-end">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        <div class="dropdown nav-link">
                            <a class="btn text-white dropdown" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa-solid fa-bell fs-5"></i>
                                @if(sizeof(auth()->user()->unReadNotifications)>0)
                                    <span class="badge bg-danger">
                                        {{sizeof(auth()->user()->unReadNotifications)}}
                                    </span>
                                @endif
                            </a>

                            <ul class="dropdown-menu  dropdown-menu-end">
                                @forelse(auth()->user()->unReadNotifications as $notification)
                                    <li>
                                        <button class="dropdown-item unread">
                                            {{$notification->markAsRead()}}
                                            {{$notification->data}}
                                        </button>
                                    </li>
                                @empty
                                    <li>
                                        <a class="dropdown-item disabled" href="#">
                                            No notifications.
                                        </a>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </li>
                @endauth

                <li class="nav-item">
                    <a class="nav-link fs-5 text-white" href="{{route('home')}}">Home</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link fs-5 text-white" href="{{route('login')}}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-5 text-white" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    @php
                        $nameCollection = explode(' ', auth()->user()->name);
                    @endphp
                    <li class="nav-item dropdown">
                        <a class="btn text-white dropdown fs-5"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <span class="">{{$nameCollection[0]}}</span>
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('dashboard.logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<section class="grid-container">

    {{--SIDEBAR--}}
    <div id="side-bar" class="grid-item">
        <div class="">
            <ul id="dashboard-links" class="nav text-secondary pt-3 ps-2 bg-white nice-shadow"
                style="border-radius: 20px; padding-bottom: 24px">
                <li class="d-links fs-5 justify-content-lg-start justify-content-center text-center
                    @yield('activeDashboard', '')">
                    <a href="{{route('dashboard.index')}}" class="m-0 my-1 nav-link d-lg-block text-purple">
                        <i class="fa-solid fa-chart-simple fs-4"></i><span
                            class="mx-2 d-none d-lg-inline">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center
                    @yield('activeTransactions')">
                    <a href="{{route('transactions.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-cash-register"></i>
                        <span class="mx-2 d-none d-lg-inline">Transaction</span>
                    </a>
                </li>
                <li class="nav-item d-links fs-4 justify-content-lg-start justify-content-center text-center
                    @yield('activeRoles', '') ">
                    <a href="{{route('roles.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-user-tag fs-4"></i>
                        <span class="mx-2 d-none d-lg-inline">Roles</span>
                    </a>
                </li>
                <li class="nav-item d-links fs-4 justify-content-lg-start justify-content-center text-center
                    @yield('activeUsers', '') ">
                    <a href="{{route('users.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-users fs-4"></i>
                        <span class="mx-2 d-none d-lg-inline">Users</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center
                    @yield('activeProducts')">
                    <a href="{{route('products.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-boxes-stacked fs-4"></i>
                        <span class="mx-2 d-none d-lg-inline">Products</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center
                    @yield('activeCategories', '')">
                    <a href="{{route('categories.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-tags fs-4"></i><span class="mx-2 d-none d-lg-inline">Categories</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @yield('right-side')

</section>

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('scripts/utilities.js')}}"></script>

@stack('other-scripts')

</html>
