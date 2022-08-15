<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light navbar-static">

    <!-- Header with logos -->
    <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
        <div class="navbar-brand navbar-brand-md">
            <a href="#" class="d-inline-block">
                <!-- <img src="../../../../global_assets/images/logo_light.png" alt=""> -->
            </a>
        </div>

        <div class="navbar-brand navbar-brand-xs">
            <a href="#" class="d-inline-block">
                <img src="{{asset('limitless/global_assets/images/logo_icon_light.png')}}" alt="">
            </a>
        </div>
    </div>
    <!-- /header with logos -->


    <!-- Mobile controls -->
    <div class="d-flex flex-1 d-md-none">
        <div class="navbar-brand mr-auto">
            <a href="{{ url('/') }}" class="d-inline-block">
               <i class="icon-home text-success"></i>
            </a>
        </div>

{{--        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">--}}
{{--            <i class="icon-tree5"></i>--}}
{{--        </button>--}}

        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
    <!-- /mobile controls -->


    <!-- Navbar content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

{{--        <span class="badge bg-indigo-400 badge-pill ml-md-3 mr-md-auto d-none">Hi, Admin Kece</span>--}}

        <ul class="navbar-nav">
{{--            <li class="nav-item dropdown">--}}
{{--                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">--}}
{{--                    <img src="{{asset('limitless/global_assets/images/lang/gb.png')}}" class="img-flag mr-2" alt="">--}}
{{--                    English--}}
{{--                </a>--}}

{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <a href="#" class="dropdown-item english active"><img src="{{asset('limitless/global_assets/images/lang/gb.png')}}" class="img-flag" alt=""> English</a>--}}
{{--                    <a href="#" class="dropdown-item ukrainian"><img src="{{asset('limitless/global_assets/images/lang/ua.png')}}" class="img-flag" alt=""> Українська</a>--}}
{{--                    <a href="#" class="dropdown-item deutsch"><img src="{{asset('')}}" class="img-flag" alt=""> Deutsch</a>--}}
{{--                    <a href="#" class="dropdown-item espana"><img src="../../../../global_assets/images/lang/es.png" class="img-flag" alt=""> España</a>--}}
{{--                    <a href="#" class="dropdown-item russian"><img src="../../../../global_assets/images/lang/ru.png" class="img-flag" alt=""> Русский</a>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="nav-item dropdown dropdown-user" id="topbar-admin">--}}
{{--                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">--}}
{{--                    <img src="{{ auth('admin')->user()->admin_avatar }}" class="rounded-circle mr-2" height="34" alt="">--}}
{{--                    <span>{{ auth('admin')->user()->admin_name }}</span>--}}
{{--                </a>--}}

{{--                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                    <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>--}}
{{--                    <a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>--}}
{{--                    <a href="{{ route('admin:logout') }}" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>--}}
{{--                </div>--}}
{{--            </li>--}}
        </ul>
    </div>
    <!-- /navbar content -->

</div>
