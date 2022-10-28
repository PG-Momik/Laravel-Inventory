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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous" defer></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">




</head>
<body class="">
<nav class="navbar navbar-expand-lg navbar-light bg-dark d-flex d-flex justify-content-end px-4 mb-2 bg-grey"
     style="box-shadow: 0px 2px 8px 0px">
    <div class="d-flex justify-content-end">
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
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

                    <li class="nav-item">
                        <a class="nav-link fs-5 text-white" href="{{ route('profile.index') }}">Profile</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<section class="grid-container">


    {{--    SIDEBAR--}}
    <div id="side-bar" class="grid-item">
        <div class="">
            <ul id="dashboard-links" class="nav text-secondary pt-3 ps-2 bg-white nice-shadow"
                style="border-radius: 20px; padding-bottom: 24px">
                <li class="d-links fs-5 justify-content-lg-start justify-content-center text-center @yield('activeDashboard', '')">
                    <a href="{{route('dashboard.index')}}" class="m-0 my-1 nav-link d-lg-block text-purple">
                        <i class="fa-solid fa-chart-simple fs-4"></i><span
                            class="mx-2 d-none d-lg-inline">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center @yield('activeTransactions')">
                    <a href="{{route('transactions.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-cash-register"></i><span class="mx-2 d-none d-lg-inline">Transaction</span>
                    </a>
                </li>
                <li class="nav-item d-links fs-4 justify-content-lg-start justify-content-center text-center @yield('activeUsers', '') ">
                    <a href="{{route('users.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-users fs-4"></i><span class="mx-2 d-none d-lg-inline">Users</span>
                    </a>
                </li>
                <li class="nav-item d-links fs-4 justify-content-lg-start justify-content-center text-center @yield('activeRoles', '') ">
                    <a href="{{route('roles.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-user-tag fs-4"></i><span class="mx-2 d-none d-lg-inline">Roles</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center @yield('activeProducts')">
                    <a href="{{route('products.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-boxes-stacked fs-4"></i><span
                            class="mx-2 d-none d-lg-inline">Products</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center @yield('activeCategories', '')">
                    <a href="{{route('categories.index')}}" class="m-0 my-1 nav-link text-purple">
                        <i class="fa-solid fa-tags fs-4"></i><span class="mx-2 d-none d-lg-inline">Categories</span>
                    </a>
                </li>

                <li class="nav-item d-links fs-5 justify-content-lg-start justify-content-center text-center">
                    <a href="{{ route('dashboard.logout') }}" class="m-0 my-1 nav-link text-purple"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa-solid fa-door-open fs-4"></i> <span class="mx-2 d-none d-lg-inline">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

    @yield('right-side')
</section>

</body>
<script src="{{asset('scripts/app.js')}}"></script>
</html>
