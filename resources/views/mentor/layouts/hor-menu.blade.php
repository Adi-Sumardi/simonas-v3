<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('images/simonas_logo.png')}}" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('images/simonas_logo.png')}}" alt="" height="60">
                    </span>
                </a>

                <a class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('images/simonas_logo.png')}}" alt="" height="40">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('images/simonas_logo.png')}}" alt="" height="60">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <div class="topnav">
                <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                    <div class="collapse navbar-collapse" id="topnav-menu-content">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="/mentor" id="topnav-dashboard" role="button" aria-expanded="false">
                                    Dashboard
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="topnav-components" role="button" aria-expanded="false">
                                    Data Kegiatan Mentor
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Data Warga Asrama
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-pages">
                                    <a href="/mentor-warga-asgj" class="dropdown-item">ASGJ</a>
                                    <a href="/mentor-warga-asg" class="dropdown-item">ASG</a>
                                    <a href="/mentor-warga-aws" class="dropdown-item">AWS</a>
                                    <a href="/mentor-warga-aspuri" class="dropdown-item">ASPURI</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link arrow-none" href="{{ url('logout') }}" onclick="event.preventDefault(); localStorage.clear();  document.getElementById('logout-form').submit();" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block d-lg-none ml-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0" aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ml-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen"></i>
                </button>
            </div>
            @if (\illuminate\Support\Facades\Auth::user())
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-xl-inline-block ml-1">{{\illuminate\Support\Facades\Auth::user()->name}}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle mr-1"></i> Lock screen</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="{{ url('logout')}}"
                    onclick="event.preventDefault(); localStorage.clear(); document.getElementById('logout-form').submit();">
                    <i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> Logout</a>
                    <form id="logout-form" action="{{ url('/logout')}}" method="POST" class="d-none">
                        {{ csrf_field() }}
                    </form>                
                </div>
            </div>
            @else
            @endif
        </div>
    </div>
</header>