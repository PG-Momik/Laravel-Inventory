@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
      data-assets-path="../proStuff/"
      data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>@yield('title')</title>


    <meta name="description" content=""/>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../proStuff/img/favicon/favicon.ico"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"/>

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../proStuff/vendor/fonts/boxicons.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Core CSS -->
    <link rel="stylesheet" href="../proStuff/vendor/css/core.css" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="../proStuff/vendor/css/theme-default.css" class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="../proStuff/css/demo.css"/>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../proStuff/vendor/libs/perfect-scrollbar/perfect-scrollbar.css"/>

    <link rel="stylesheet" href="../proStuff/vendor/libs/apex-charts/apex-charts.css"/>

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../proStuff/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../proStuff/js/config.js"></script>
    @livewireStyles
    @powerGridStyles
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

            <!--Brand Logo-->
            <div class="app-brand demo">
                <a href="index.html" class="app-brand-link">
                    <span class="app-brand-text demo menu-text fw-bolder">IMS</span>
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>
            <!--/Brand Logo-->
            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">

                <!-- Dashboard -->
                <li class="menu-item @yield('activeDashboard')">
                    <a href="{{route('dashboard.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Dashboard</div>
                    </a>
                </li>

                <!-- Layouts -->
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">Mode</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item" id="layout-btn-1">
                            <a href="javascript:void(0);" class="menu-link">
                                <div data-i18n="Without menu">Baby Mode</div>
                            </a>
                        </li>
                        <li class="menu-item" id="layout-btn-2">
                            <a href="javascript:void(0);" class="menu-link">
                                <div data-i18n="Without navbar">Not Baby Mode</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Transactions -->
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Transactions</span></li>

                <!-- Yesterdays -->
                <li class="menu-item  @yield('activeYesterday')">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Account Settings">Yesterday's</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item @yield('activeYesterday-view-all')">
                            <a href="{{route('yesterdays-transactions')}}" class="menu-link">
                                <div data-i18n="Account">All</div>
                            </a>
                        </li>
                        <li class="menu-item @yield('activeYesterday-view-purchases')">
                            <a href="{{route('yesterdays-transactions', ['type'=>'purchase'])}}" class="menu-link">
                                <div data-i18n="Notifications">Purchases</div>
                            </a>
                        </li>
                        <li class="menu-item @yield('activeYesterday-view-sales')">
                            <a href="{{route('yesterdays-transactions', ['type'=>'sales'])}}" class="menu-link">
                                <div data-i18n="Connections">Sales</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /Yesterdays -->

                <!-- /Monthly-->
                <li class="menu-item @yield('activeMonthly')">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Account Settings">Monthly</div>
                    </a>
                    @php($month  = Carbon::now()->format('m'))
                    <ul class="menu-sub">
                        <li class="menu-item @yield('activeMonthly-view-all')">
                            <a href="{{route('monthly-transactions', ['month'=>$month])}}"
                               class="menu-link">
                                <div data-i18n="Account">All</div>
                            </a>
                        </li>
                        <li class="menu-item @yield('activeMonthly-view-purchases')">
                            <a href="{{route('monthly-transactions', ['month'=>$month,'type'=>'purchase'])}}"
                               class="menu-link">
                                <div data-i18n="Notifications">Purchases</div>
                            </a>
                        </li>
                        <li class="menu-item @yield('activeMonthly-view-sales')">
                            <a href="{{route('monthly-transactions', ['month'=>$month,'type'=>'sales'])}}"
                               class="menu-link">
                                <div data-i18n="Connections">Sales</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /Monthly-->

                <!-- Yearly-->
                <li class="menu-item @yield('activeYearly')">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Account Settings">Yearly</div>
                    </a>
                    @php($year  = Carbon::now()->format('Y'))
                    <ul class="menu-sub">
                        <li class="menu-item @yield('activeYearly-view-all')">
                            <a href="{{route('yearly-transactions', ['year'=>$year])}}" class="menu-link">
                                <div data-i18n="Account ">All</div>
                            </a>
                        </li>
                        <li class="menu-item @yield('activeYearly-view-purchases')">
                            <a href="{{route('yearly-transactions', ['year'=>$year, 'type'=>'purchase'])}}"
                               class="menu-link">
                                <div data-i18n="Notifications">Purchases</div>
                            </a>
                        </li>
                        <li class="menu-item @yield('activeYearly-view-sales')">
                            <a href="{{route('yearly-transactions', ['year'=>$year, 'type'=>'sales'])}}"
                               class="menu-link">
                                <div data-i18n="Connections">Sales</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /Yearly-->

                <!-- Roles -->
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Roles</span></li>

                <li class="menu-item @yield('activeRoles')">
                    <a href="{{route('roles.index')}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Account Settings">All</div>
                    </a>
                </li>

                <li class="menu-item @yield('activeRoles-admin')">
                    <a href="{{route('roles.show', ['role'=>1])}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Account Settings">Admin</div>
                    </a>
                </li>
                <li class="menu-item @yield('activeRoles-user')">
                    <a href="{{route('roles.show',['role'=>2])}}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Account Settings">User</div>
                    </a>
                </li>
                <!-- /Roles -->

                <!-- Users -->
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Users</span></li>

                <li class="menu-item  @yield('activeUsers-view-no-trashed')">
                    <a href="{{route('users.index')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">All</div>
                    </a>
                </li>
                <li class="menu-item @yield('activeUsers-view-with-trashed')">
                    <a href="{{route('users.trashed')}}@yield('activeMonthly-all')" class="menu-link">
                        <div data-i18n="Input groups">Trashed</div>
                    </a>
                </li>

                <!-- /Users -->

                <!-- Products -->
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Products</span></li>

                <li class="menu-item  @yield('activeProducts-view-no-trashed')">
                    <a href="{{route('products.index')}}" class="menu-link">
                        <div data-i18n="Basic Inputs">All</div>
                    </a>
                </li>
                <li class="menu-item @yield('activeProducts-view-with-trashed')">
                    <a href="{{route('products.trashed')}}" class="menu-link">
                        <div data-i18n="Input groups">Trashed</div>
                    </a>
                </li>

                <!-- /Products -->

                <!-- Categories -->
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Categories</span></li>

                <li class="menu-item  @yield('activeCategories-view-no-trashed')">
                    <a href="forms-basic-inputs.html" class="menu-link">
                        <div data-i18n="Basic Inputs">All</div>
                    </a>
                </li>
                <li class="menu-item @yield('activeCategories-view-with-trashed')">
                    <a href="forms-input-groups.html" class="menu-link">
                        <div data-i18n="Input groups">Trashed</div>
                    </a>
                </li>
                <!-- /Categories -->

            </ul>

        </aside>
        <!-- / Menu -->

        <!-- Layout right container -->
        <div class="layout-page">

            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar navbar-expand-xl
                 navbar-detached align-items-center bg-navbar-theme"
                 id="layout-navbar">
                <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                        <i class="bx bx-menu bx-sm"></i>
                    </a>
                </div>
                @yield('additional-controls')
                <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <!-- User -->
                        <li class="nav-item navbar-dropdown dropdown-user dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                               data-bs-toggle="dropdown">
                                <div class="avatar avatar-online">
                                    <img src="../proStuff/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle"/>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar avatar-online">
                                                    <img src="../proStuff/img/avatars/1.png" alt
                                                         class="w-px-40 h-auto rounded-circle"/>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="fw-semibold d-block">John Doe</span>
                                                <small class="text-muted">Admin</small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-user me-2"></i>
                                        <span class="align-middle">My Profile</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bx bx-cog me-2"></i>
                                        <span class="align-middle">Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                      <span class="d-flex align-items-center align-middle">
                                        <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                        <span class="flex-grow-1 align-middle">Billing</span>
                                        <span
                                            class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                                      </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="auth-login-basic.html">
                                        <i class="bx bx-power-off me-2"></i>
                                        <span class="align-middle">Log Out</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!--/ User -->
                    </ul>
                </div>
            </nav>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('container-content')
                </div>
                <!-- /Content -->
            </div>
            <!-- / Content Wrapper -->

        </div>
        <!-- / Layout right container -->
    </div>
</div>
<!-- / Layout wrapper -->

@livewireScripts
@powerGridScripts

</body>


<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"
        integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="{{asset('scripts/noob/utilities.js')}}"></script>

<script src="../proStuff/vendor/libs/popper/popper.js"></script>
<script src="../proStuff/vendor/js/bootstrap.js"></script>
<script src="../proStuff/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

<script src="../proStuff/vendor/js/menu.js"></script>
<!-- endbuild -->

<!-- Vendors JS -->
<script src="../proStuff/vendor/libs/apex-charts/apexcharts.js"></script>

<!-- Main JS -->
<script src="../proStuff/js/main.js"></script>

<!-- Page JS -->
<script src="../proStuff/js/dashboards-analytics.js"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

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
        $.ajax({
            url: url,
            success: function (result) {
                if (result && confirm("Do you want to implement changes now?") === true) {
                    window.location.reload();
                }

            }
        });
    }
</script>
@stack('other-scripts')

</html>

