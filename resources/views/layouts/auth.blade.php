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
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/app.css">

</head>
<body class="">

<nav class="navbar navbar-expand-lg navbar-light bg-dark d-flex d-flex justify-content-end px-4 mb-2 bg-grey" style="box-shadow: 0px 2px 8px 0px">
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
                        <a class="nav-link fs-5 text-white" href="{{ route('dashboard.index') }}">Dashboard</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
@yield('content')
</body>
</html>

