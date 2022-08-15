<header id="header" class="navbar-static-top">
    {{--    --}}{{-- Loading --}}
    {{--    <div class="loading">--}}
    {{--        <div class="loading-content">--}}
    {{--            <div class="spin">--}}
    {{--                <i class="fa fa-circle-o-notch fa-spin"></i>--}}
    {{--            </div>--}}
    {{--            <div class="loading-text">--}}
    {{--                Loading ...--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{-- <div class="topnav hidden-xs">
        <div class="container">
            <ul class="quick-menu pull-right">
                <li><a href="{{url('agent/login')}}" target="_blank">Provider Sign In</a></li>
                <li><a href="{{url('register-provider')}}" target="_blank">Provider Sign Up</a></li>
                <li class="ribbon">
                    <a href="#">{{app()->getLocale()==='en'?"English":"Indonesia"}}</a>
                    <ul class="menu mini"> --}}
    {{--                        <li class="{{app()->getLocale()==='en'?"active":""}}"><a id="english" href="#" data-value="en" title="English">English</a></li>--}}
    {{-- <li class="{{app()->getLocale()==='id'?"active":""}}"><a id="indonesia" href="#" data-value="id" title="Indonesia">Indonesia</a></li>
</ul>
</li>
</ul>
</div>
<form action="{{route('general:change-language')}}" id="landing-change-language" method="POST">
{!! csrf_field() !!}
<input type="hidden" name="lang">
</form>
</div> --}}

    <div class="main-header {{(isset($is_home)&& $is_home)?'':'not_home'}}">
        <a href="#mobile-menu-01" data-toggle="collapse" class="mobile-menu-toggle">
            Mobile Menu Toggle
        </a>
        <div class="container">
            <h1 class="gomodo_logo logo navbar-brand">
                <a href="{{route('memoria.home')}}" title="Gomodo">
                    <img src="{{asset('explore-assets/images/logo-pesona.png')}}" class="logo-gomodo" alt="Pesona - Perhutanan Sosial Nusantara"/>
                    <span class="gomodo_text">Pesona</span>
                </a>
            </h1>
            <nav id="main-menu" role="navigation">
                <ul class="menu">
                    <li class="menu-item-has-children text-white">
                        <a href="{{route('memoria.home')}}">{{ trans('explore-lang.header.home') }}</a>
                    </li>
                    {{-- <li class="text-black {{(request()->route()->getName() =='explore.search' && Route::current()->parameter('type')=='transports')?'active':''}}"> --}}
                    {{--  <a href="{{route('explore.search',['type'=>'transports'])}}">{{ trans('explore-lang.header.transport') }}</a> --}}
                    </li>
                    {{--                    <li class="menu-item-has-children">--}}
                    {{--                        <a href="all_inspiration.html">Inspiration</a>--}}
                    {{--                    </li>--}}
                    <li class="text-white {{(request()->route()->getName() =='explore.search' && Route::current()->parameter('type')=='all-activities')?'active':''}}">
                        <a href="{{route('explore.search',['type'=>'all-activities'])}}">{{ trans('explore-lang.header.explore_act') }}</a>
                    </li>
                    <li class=" text-white {{request()->route()->getName() =='explore.all-destination'?'active':''}}">
                        <a href="{{route('explore.all-destination')}}">{{ trans('explore-lang.header.explore_dest') }}</a>
                    </li>
                    <li class="text-white {{request()->route()->getName() =='explore.help'?'active':''}}">
                        <a href="{{route('explore.help')}}">{{ trans('explore-lang.header.help') }}</a>
                    </li>
                    @auth('web')
                        <li class="text-white">
                            <a href="{{route('company.dashboard')}}">{{__('explore-klhk-lang.header.partner')}}</a>
                        </li>
                    @else
                        <li class="provider-login-nav text-white">
                            <a href="">{{__('explore-klhk-lang.header.partner')}}</a>
                        </li>
                    @endauth
                    {{-- <li class="language">
                        <div class="current-language">
                            <div class="flag-current-language">
                                <img class="img-circle" src="{{asset('landing/img/idn.png')}}">
                            </div>
                        </div>

                        {{-- <div class="language-options">
                            <div class="description-language-options">
                                English
                            </div>
                            <img class="img-circle" src="{{asset('landing/img/uk.png')}}">
                        </div> --}}

                        
                        
                    {{-- </li> --}}
                    <li class="ribbon">
                        <a href="#">{!! app()->getLocale()=== 'en'? '<img src="'.asset('landing/img/uk.png').'" class="flag-image" alt="english" width="15px;"> EN' : '<img src="'.asset('landing/img/idn.png').'" class="flag-image" alt="english" width="15px;"> ID' !!}</a>
                        <ul class="menu-mini">
                            <li class="{{app()->getLocale()=== 'en'?"active":""}}">
                                <a href="#" id="english" data-value="en" title="English"><img class="img-circle" src="{{asset('landing/img/uk.png')}}" width="15px"> EN</a>
                            </li>
                            <li class="{{app()->getLocale() === 'id'?"active":""}}">
                                <a href="#" class="text-black" id="indonesia" data-value="id" title="Indonesia"><img class="img-circle" src="{{asset('landing/img/idn.png')}}" width="15px"> ID</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <form action="{{ route('general:change-language') }}" id="landing-change-language" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="lang">
            </form>
        </div>

        <nav id="mobile-menu-01" class="mobile-menu collapse">
            <ul id="mobile-primary-menu" class="menu">
                <li class="menu-item-has-children">
                    <a href="{{route('memoria.home')}}">{{ trans('explore-lang.header.home') }}</a>
                </li>
                <li class="{{(request()->route()->getName() =='explore.search' && Route::current()->parameter('type')=='transports')?'active':''}}">
                    <a href="{{route('explore.search',['type'=>'transports'])}}">{{ trans('explore-lang.header.transport') }}</a>
                </li>
                {{--                    <li class="menu-item-has-children">--}}
                {{--                        <a href="all_inspiration.html">Inspiration</a>--}}
                {{--                    </li>--}}
                <li class="{{(request()->route()->getName() =='explore.search' && Route::current()->parameter('type')=='all-activities')?'active':''}}">
                    <a href="{{route('explore.search',['type'=>'all-activities'])}}">{{ trans('explore-lang.header.explore_act') }}</a>
                </li>
                <li class="{{request()->route()->getName() =='explore.all-destination'?'active':''}}">
                    <a href="{{route('explore.all-destination')}}">{{ trans('explore-lang.header.explore_dest') }}</a>
                </li>
                <li class="{{request()->route()->getName() =='explore.help'?'active':''}}">
                    <a href="{{route('explore.help')}}">{{ trans('explore-lang.header.help') }}</a>
                </li>
                @auth('web')
                    <li class="">
                        <a href="{{route('company.dashboard')}}">Partner</a>
                    </li>
                @else
                    <li class="provider-login-nav">
                        <a href="">Partner</a>
                    </li>
                @endauth

            </ul>

            <ul class="mobile-topnav container">
                <li class="ribbon language menu-color-skin">
                        {{-- <a href="#">{!! app()->getLocale()=== 'en'? 'EN' : 'ID' !!}</a>
                        <ul class="menu-mini">
                            <li class="{{app()->getLocale()=== 'en'?"active":""}}">
                                <a href="#" id="english" data-value="en" title="English">EN</a>
                            </li>
                            <li class="{{app()->getLocale() === 'id'?"active":""}}">
                                <a href="#" class="text-black" id="indonesia" data-value="id" title="Indonesia">ID</a>
                            </li>
                        </ul> --}}
                    <a href="#">{{app()->getLocale()==='en'?"English":"Indonesia"}}</a>
                    <ul class="menu mini">
                        <li class="{{app()->getLocale()==='en'?"active":""}}"><a href="#" id="indonesia" data-value="en" title="English">English</a></li>
                        <li class="{{app()->getLocale()==='id'?"active":""}}"><a href="#" id="indonesia" data-value="id" title="Indonesia">Indonesia</a></li>

                    </ul>
                </li>
            </ul>
        </nav>
        <form action="{{ route('general:change-language') }}" id="landing-change-language" method="POST">
                {!! csrf_field() !!}
                <input type="hidden" name="lang">
            </form>
    </div>
</header>
<form class="d-none" method="post" action="{{ route('general:change-language') }}" id="change-language">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="lang" value="id" />
</form>
