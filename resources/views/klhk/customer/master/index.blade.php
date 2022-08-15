<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta property="og:image" content="{{$company->logo_url}}"/>
    <meta property="og:image:alt" content="{{$company->company_name}}"/>
    @if ($company->title)
        <title>{{$company->title}}</title>
        <meta name="title" content="{{$company->title}}">
        <meta property="og:title" content="{{$company->title}}"/>

    @elseif(isset($product))
        <title>{{$product->product_name}}</title>
        <meta name="title" content="{{$product->product_name}}">
        <meta property="og:title" content="{{$product->product_name}}"/>
    @else
        <title>{{$company->company_name}}</title>
        <meta name="title" content="{{$company->company_name}}">
        <meta property="og:title" content="{{$company->company_name}}"/>
    @endif


    @if($company->description)
        <meta name="description" content="{{$company->description}}">
        <meta property="og:description"
              content="{{$company->description}}"/>
    @elseif(isset($product))
        <meta property="og:description"
              content="{{$product->brief_description}}"/>
        <meta name="description" content="{{$product->brief_description}}">
    @endif
    <meta name="author" content="KLHK">
    <meta property="fb:app_id" content="887163081476612"/>
    <meta name="keywords" content="{{$company->keywords}}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    <link rel="stylesheet" href="{{asset('landing/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link href="{{asset('css/material.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="shortcut icon"
          href="{{ asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo )}}">
    <link rel="stylesheet" href="{{ asset('css/badge-company.css') }}">
    <link rel="stylesheet" href="{{ asset('css/badge-discount.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-inline-fix.css') }}"> {{-- Custome CSS --}}
    <link rel="stylesheet" href="{{ asset('explore-assets/css/custom-klhk.css') }}"> {{-- Custom KLHK CSS --}}
    @yield('additionalStyle')
    @if(env('APP_ENV') ==='production')
        @include('analytics')
        @if($company->ga_code)
        <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{$company->ga_code}}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }

                gtag('js', new Date());

                gtag('config', '{{$company->ga_code}}');
            </script>
        @endif
    @endif

    <style>
        .address {
            color: #fff;
            padding-bottom: 0.8rem;
            text-align: justify;
        }

        .address p {
            margin-bottom: 0px;
        }

        footer .powered {
            height: auto;
        }

        footer .phone-company span {
            display: inline-flex;
        }

        footer .phone-company {
            margin-top: 1rem;
        }

        footer .phone-company img {
            width: 20px;
        }

        footer p.about, footer .address #address, footer .social {
            font-weight: bold;
            margin-bottom: 0.6rem;
            margin-top: 0;
        }

        footer .address #address {
            margin-bottom: 0.95rem;
        }

        footer .pt-5 {
            padding-top: 2.5rem !important;
        }

        nav.navbar + section {
            margin-top: 49px !important;
        }

        #btn-navbar-toggle {
            cursor: pointer;
            background: none;
            border: none;
            display: none;
        }

        .relative img {
            width: 1.3rem;
        }

        .fa.fa-bars {
            color: #2699FB;
            font-size: 1.5rem;
        }

        .owl-nav .owl-next.disabled, .owl-nav .owl-prev.disabled {
            display: none
        }

        @media only screen and (max-width: 480px) {
            #btn-navbar-toggle {
                display: block;
            }

            .collapse.navbar-collapse {
                background: #f1f8fd;
                margin-left: -1rem;
                margin-right: -1rem;
                border-top: 1px solid #dcdbdb;
            }

            footer .address #address {
                margin-top: 35px;
            }
        }

    </style>

</head>
<body class="provider">
<div class="loading">
    <div class="loading-content">
        <div class="spin">
            <i class="fa fa-circle-o-notch fa-spin"></i>
        </div>
        <div class="loading-text">
            {{ trans("general.loading") }}
        </div>
    </div>
</div>
<nav class="navbar navbar-dark navbar-expand-md bg-style-1 container-fluid navbar-fixed-top" id="topNavbar">
    <div class="container">
        <a href="{{url('/')}}" class="navbar-brand">
            <img src="{{$company->logo_url}}" alt="">
        </a>
        <div class="profile-info">
            <h3 id="company-name">{{$company->company_name}}</h3>
        </div>
        <span class="relative">
                @if($company->verified_provider=='1')
                <img src="{{asset('img/correct.svg')}}" alt="" class="checked">
            @endif
            </span>
        <button id="btn-navbar-toggle" data-target="#navcol-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bars"></span>
        </button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item text-center">
                    @if (!$orderAds)
                        <a href="{{route('memoria.retrieve')}}"
                           class="btn btn-primary retrieve">{!! trans('retrieve_booking.retrieve_booking') !!}</a>
                    @endif
                </li>
                <li class="nav-item text-center" id="landing-language" role="presentation">
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
                                <img class="img-circle" src="{{asset('landing/img/idn.png')}}" alt="">
                            @else
                                <img class="img-circle" src="{{asset('landing/img/uk.png')}}" alt="">
                            @endif
                        </div>
                        <div class="caret-language">
                            <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="language-option" style="display: none;">
                        <ul>
                            @if(app()->getLocale() =='id')
                                <li data-value="en" class="pick-lang">
                                    <div class="box-language">
                                        <div class="description-language">
                                            English
                                        </div>
                                        <div class="flag-language">
                                            <img src="{{asset('landing/img/uk.png')}}" alt="">
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
                                            <img src="{{asset('landing/img/idn.png')}}" alt="">
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

<section id="content">
    @yield('content')
</section>
<footer class="container-fluid bg-dark">
    <div class="container pt-5">
        <div class="row">
            <div class="col-md-4 text-white mb-md-0 mb-3 text-justify">
                @if ($company->about)
                    <p class="text-white about">
                        {!! trans('customer.home.about_us') !!}
                    </p>
                    <p class="text-white order-0 about-us">
                        {!! $company->about !!}
                    </p>
                @endif
            </div>
            <div class="col-md-4 order-1 order-lg-1 d-flex">
                <div class="address">
                    @if($company->address_company)
                        <p id="address"> {{ucwords(strtolower(trans('landing.address')))}}</p>
                        {!! $company->address_company !!}
                    @endif
                    @if($company->phone_company)
                        {{-- <div class=" {{$company->about?'':'mt-5'}}"> --}}
                        <div class="phone-company">
                                <span class="text-white"><img src="{{asset('img/048-telephone-1.png')}}" width="16"
                                                              alt="">  <span>{{$company->phone_company}}</span></span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4 order-1 order-lg-2 mb-md-0 mb-5">
                <ul class="social">
                    <p>{{ trans('setting_provider.social_media') }}</p>
                    @if($company->email_company)
                        <li>
                            <a href="{{'mailto:'.$company->email_company}}" class="text-white font-weight-normal">
                                <img src="{{asset('img/159-email.png')}}" alt=""> {{$company->email_company}}
                            </a>

                        </li>
                    @endif
                    @if($company->facebook_company)
                        @php
                            $ex = explode('://',$company->facebook_company);
                            $link = 'https://facebook.com/'.$company->facebook_company;
                            $name = $company->facebook_company;
                            if (count($ex)>1){
                                if ($ex[0] =='http' || $ex[0] == 'https'){
                                $link = $company->facebook_company;
                                $name = array_last(explode('/', trim(str_before($company->facebook_company, '?'), '/')));
                                }
                            }
                        @endphp
                        <li>
                            <a href="{{$link}}" class="text-white font-weight-normal" target="_blank">
                                <img src="{{asset('img/099-facebook.png')}}" alt=""> {{$name}}
                            </a>
                        </li>
                    @endif
                    @if($company->twitter_company)
                        @php
                            $ex = explode('://',$company->twitter_company);
                            $link = 'https://instagram.com/'.$company->twitter_company;
                            $name = $company->twitter_company;
                            if (count($ex)>1){
                                if ($ex[0] =='http' || $ex[0] == 'https'){
                                $link = $company->twitter_company;
                                $name = array_last(explode('/', trim(str_before($company->twitter_company, '?'), '/')));
                                }
                            }
                        @endphp
                        <li>
                            <a href="{{$link}}" class="text-white font-weight-normal" target="_blank">
                                <img src="{{asset('img/080-instagram.png')}}" alt=""> {{$name}}
                            </a>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mt-3">
                <div class="powered">
                    <span>POWERED BY</span> <span class="bold"><a href="#">GOMODO</a></span>
                </div>
            </div>
        </div>
    </div>
</footer>
@include('javascript')
<script src="{{asset('landing/js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
{{-- <script src="{{asset('landing/bootstrap/js/bootstrap.min.js')}}"></script> conflict with material.js --}}
{{--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.1/js/mdb.min.js"></script>--}}
<script type="text/javascript" src="{{asset('js/public/material.js')}}"></script>
<script>
    // For Gomodo Booking Widget
    if (window.location !== window.parent.location) {
        var doc = $(document);
        doc.find('nav,footer,.breadcrumb, .left-side-detail, .embed-remove').remove();
        doc.find('.embed-col-6').addClass('col-embed-6');
        doc.find('.embed-row').addClass('row');
        doc.find('.embed-remove-d-none').removeClass('d-none');
    }
</script>
@if($company->phone_company)
    <!-- WhatsHelp.io widget -->
    <script type="text/javascript">
        if (window.location === window.parent.location) {
            (function () {
                var options = {
                    @if($company->phone_company[0] == 0)
                    whatsapp: {{"62".ltrim($company->phone_company, "0")}}, // WhatsApp number
                    @else
                    whatsapp: {{$company->phone_company}}, // WhatsApp number
                    @endif
                    call_to_action: "{{__('home.contact_us')}}", // Call to action
                    position: "left", // Position may be 'right' or 'left'
                };
                var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
                var s = document.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = url + '/widget-send-button/js/init.js';
                s.onload = function () {
                    WhWidgetSendButton.init(host, proto, options);
                };
                var x = document.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            })();
        }
    </script>
    <!-- /WhatsHelp.io widget -->

@endif
<script>
    $(document).on('click', '#btn-navbar-toggle', function () {
        let t = $(this);
        $(t.data('target')).slideToggle(100);
    });

    function loadingStart() {
        $('.loading').addClass('show');
    }

    function loadingFinish() {
        $('.loading').removeClass('show');
    }

    toastr.options = {
        "positionClass": "toast-bottom-right",
    };
    $(document).on('click', '.current-language', function () {
        $('.language-option').slideToggle(300)
    });
    $(document).on('click', '.box-language', function () {
        let form = $('#landing-change-language');
        form.find('input[name=lang]').val($(this).parent().data('value'));
        form.submit();
    })
    $(document).on('click', '.can-copy', function () {
        let t = $(this);
        let $temp = $("<input>");
        $("body").append($temp);
        $temp.val(t.find('.data-copied').text()).select();
        document.execCommand("copy");
        $temp.remove();
        toastr.success(t.find('.data-copied').text(), 'Copied');
    })
    if (typeof gomodo !== 'undefined') {
        if (gomodo.error !== undefined) {
            toastr.error(gomodo.error, '{{__('general.whoops')}}');
        }
        if (gomodo.success !== undefined) {
            toastr.success(gomodo.success);
        }
        if (gomodo.warning !== undefined) {
            toastr.warning(gomodo.warning);
        }
        if (gomodo.info !== undefined) {
            toastr.info(gomodo.info);
        }
    }
    // Prevent Char in Input Type Number
    $('input[type="number"]').on('change keydown', function onChange(e) {
        if (e.metaKey == false) { // Enable metakey
            if (e.keyCode > 13 && e.keyCode < 48 && e.keyCode != 39 && e.keyCode != 37 || e.keyCode > 57) {
                e.preventDefault(); // Disable char. Enable arrow
            }
            ;
            if (e.shiftKey === true) { // Disable symbols
                if (e.keyCode > 46 && e.keyCode < 65) {
                    e.preventDefault();
                }
            }
        }
    })
    // disable mousewheel on a input number field when in focus
    // (to prevent Cromium browsers change the value when scrolling)
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault()
        })
    })
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll')
    })

    function showDeleteVoucher() {
        $('#btn-delete-voucher').closest('.voucher-button').css('display', 'block');
        $('#btn-apply-voucher').closest('.voucher-button').css('display', 'none');
        $('#voucher_code').prop('disabled', true);
    }

    function hideDeleteVoucher() {
        $('#btn-apply-voucher').closest('.voucher-button').css('display', 'block');
        $('#btn-delete-voucher').closest('.voucher-button').css('display', 'none');
        $('#voucher_code').prop('disabled', false);
    }
</script>
@if ($errors->any())

    @foreach ($errors->all() as $error)
        <script>
            toastr.error("{{$error}}", '{{__('general.whoops')}}')
        </script>
    @endforeach

@endif
@yield('scripts')
</body>
</html>
