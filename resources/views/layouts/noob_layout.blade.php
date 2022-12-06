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
            defer>
    </script>

    @livewireStyles
    @powerGridStyles
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>
<body class="bg-grey">

{{--NAVBAR--}}
<nav class="navbar navbar-expand-lg navbar-light bg-dark d-flex
     justify-content-end px-4 mb-2"
     style="box-shadow: 0 2px 8px 0">
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

                            <ul class="dropdown-menu dropdown-menu-end">
                                @forelse(auth()->user()->unReadNotifications as $notification)
                                    <li>
                                        <button class="dropdown-item unread"
                                                onclick="markAsRead(this,'{{$notification->id}}')">
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

                    <li class="nav-item pt-2 mx-1">
                        <input type="radio"
                               class="btn-check"
                               name="layout"
                               id="layout-btn-1"
                               value="1"
                               autocomplete="off"
                            @checked(auth()->user()->layout == 1)>
                        <label class="btn btn-outline-primary"
                               id="ratioBtnWrapper1"
                               for="layout-btn-1">
                            <i class="fa-solid fa-baby"></i>
                        </label>
                        <input type="radio"
                               class="btn-check"
                               name="layout"
                               id="layout-btn-2"
                               value="2"
                               autocomplete="off"
                            @checked(auth()->user()->layout == 2)>
                        <label class="btn btn-outline-danger"
                               id="ratioBtnWrapper2"
                               for="layout-btn-2">
                            <i class="fa-solid fa-person"></i>
                        </label>
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
{{--EOF NAVBAR--}}

<section class="grid-container">

    {{--SIDEBAR--}}
    <aside id="side-bar" class="grid-item">
        <div class="">
            <ul id="dashboard-links" class="nav text-secondary pt-3 ps-2 bg-light nice-shadow"
                style="border-radius: 20px; padding-bottom: 24px">
                <li class="d-links fs-5 justify-content-lg-start justify-content-center text-center
                    @yield('activeDashboard', '')">
                    <a href="{{route('dashboard.index')}}" class="m-0 my-1 nav-link d-lg-block text-purple">
                        <i class="fa-solid fa-chart-simple fs-4"></i><span
                            class="mx-2 d-none d-lg-inline">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item  d-links fs-5 justify-content-lg-start justify-content-center text-center
                    @yield('activeTransactions', '')">
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
    </aside>
    {{--EOF SIDE BAR--}}

    {{--RIGHT SIDE--}}
    <div class="grid-item">
        <div class="admin-grid">
            <div style="min-height: 440px" class="a bg-purple round-this border-black">
                <div class="bg-purple pt-3" style="border-radius: 20px ">

                    {{--TOP--}}
                    <div class="row mx-0 d-flex gx-5 align-items-center ">

                        {{--PAGE TITLE--}}
                        <div class="col-md-4 col-12">
                            <h1 class="">@yield('child-page-title')</h1>
                            <div>@yield('child-page-title-when-nested')</div>
                        </div>
                        {{--END OF PAGE TITLE--}}

                        {{--SEARCH FORM--}}
                        @yield('search-bar')
                        {{--EOF SEARCH FORM--}}
                    </div>
                    {{--EOF TOP--}}
                    <div class="row mx-0 px-2">
                        @yield('button-group')
                    </div>
                    {{--BUTTON GROUP--}}

                    {{--EOF BUTTON GROUP--}}

                    {{--WHITE CARD GOES HERE--}}
                    <div class="b grad border-bottom-1 mt-5" style="height:350px; border-radius: 20px">
                        <div style="width: 90%; margin: 0 auto;">
                            <div class="p-2 bg-light round-this shadow-this-down">

                                {{ alert() }}

                                {{--Pagination--}}
                                <div class="m-4 d-block">
                                    @yield('child-pagination')
                                </div>
                                {{--EOF Pagination--}}

                                {{--CONTENT GOES HERE--}}
                                @yield('content')
                                {{--CONTENT ENDS HERE--}}


                            </div>
                        </div>
                    </div>
                    {{--WHITE CARD ENDS HERE--}}

                </div>
            </div>
        </div>
    </div>
    {{--EOF RIGHT SIDE--}}

</section>

@livewireScripts
@powerGridScripts

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{asset('scripts/noob/utilities.js')}}"></script>

<script>
    let layoutOneBtn = document.getElementById('layout-btn-1')
    let layoutTwoBtn = document.getElementById('layout-btn-2')
    layoutOneBtn.addEventListener('click', function () {
        toggleLayoutTo(1)
    })
    layoutTwoBtn.addEventListener('click', function () {
        toggleLayoutTo(2)
    })

    function toggleLayoutTo(type) {
        let url = `/ajax/toggle-layout/{{auth()->id()}}/${type}`
        $.ajax(
            {
                url: url,
                success: function (result) {
                    if (result && confirm("Do you want to implement changes now?") === true) {
                        window.location.reload();
                    }

                }
            }
        );
    }

    function markAsRead(source, id) {
        let url = `/ajax/mark-as-read/${id}`;
        $.ajax(
            {
                url: url,
                success: function (result) {
                    if (result) {
                        window.location.reload()
                        //or re-render notification box rather than reload page.
                    }
                }
            }
        )
    }

</script>
@stack('other-scripts')

</html>
