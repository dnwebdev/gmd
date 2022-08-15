<nav class="navbar navbar-dark navbar-expand-lg container-fluid" id="top-navbar">
    <div class="container">
        <a class="navbar-brand text-white" href="{{ route('memoria.home') }}">
            <img src="{{asset('landing/img/Logo.png')}}" class="logo"> <span class="gomodo-text">GOMODO</span>
        </a>
        <button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon fs-14"></span>
        </button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="navbar-nav ml-auto nav-left">
                <li class="nav-item " role="presentation">
                    <a class="nav-link link-landing nav-home"
                        data-destination="#topmost">{{trans('landing.navbar.home')}}</a>
                </li>
                <li class="nav-item " role="presentation">
                    <a class="nav-link link-landing"
                        data-destination="#section1">{{trans('landing.navbar.why_gomodo')}}</a>
                </li>
                <li class="nav-item " role="presentation">
                    <a class="nav-link link-landing"
                        data-destination="#section3">{{trans('landing.navbar.feature')}}</a>
                </li>
                <li class="nav-item " role="presentation">
                    <a class="nav-link link-landing"
                        data-destination="#section4">{{trans('landing.navbar.contact')}}</a>
                </li>
            </ul>
            <form class="form-inline mr-auto" target="_self">

            </form>
            <ul class="navbar-nav ml-auto">
                @if(Auth::guest('web'))
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" href="{{route('login')}}">{{trans('landing.navbar.login')}}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="btn btn-light action-button" role="button"
                            href="{{route('memoria.register')}}">{{trans('landing.navbar.sign_up')}}</a>
                    </li>
                @else
                    <li class="nav-item" role="presentation">
                        <a class="btn btn-light action-button" role="button"
                            href="{{route('memoria.register')}}">{{-- {{auth('web')->user()->company->company_name}} --}}{{ trans('landing.navbar.my_dashboard') }}</a>
                    </li>
                @endif
                <li class="nav-item pl-3 pr-2" id="landing-language" role="presentation">
                    <div class="current-language">
                        <div class="description-current-language">
                            @if(app()->getLocale() =='id')
                                Indonesia
                            @else
                                English
                            @endif
                        </div>

                        <div class="flag-current-language">
                            @if(app()->getLocale() =='id')
                                <img class="img-circle" src="{{asset('landing/img/idn.png')}}">
                            @else
                                <img class="img-circle" src="{{asset('landing/img/uk.png')}}">
                            @endif
                        </div>

                        <div class="caret-language">
                            <i class="fa fa-caret-down text-white"></i>
                        </div>
                    </div>
                    <div class="language-option">
                        <ul>
                            @if(app()->getLocale() =='id')
                                <li data-value="en" class="pick-lang">
                                    <div class="box-language">
                                        <div class="description-language">
                                            English
                                        </div>
                                        <div class="flag-language">
                                            <img src="{{asset('landing/img/uk.png')}}">
                                        </div>

                                    </div>
                                </li>
                            @else
                                <li data-value="id" class="pick-lang">
                                    <div class="box-language">
                                        <div class="description-language">
                                            Indonesia
                                        </div>
                                        <div class="flag-language">
                                            <img src="{{asset('landing/img/idn.png')}}">
                                        </div>

                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <form action="{{route('general:change-language')}}" id="landing-change-language" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="lang">
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>