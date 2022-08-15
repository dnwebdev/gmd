<!DOCTYPE html>
<html lang="en">
    <head>
        @include('analytics')
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <title>{{ Session::get('company_name') }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->

        <link rel="shortcut icon" href="{{ asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo )}}">

        <!-- JQuery -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

        
        <link href="{{ asset('dest-customer/lib/css/bootstrap.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('dest-customer/lib/css/fontface.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('dest-customer/css/frontpage.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
        @yield('additionalStyle')
        @if($company->color_company)
        <style>
        .right-nav .searchtop .retreive a {
            background: #{{$company->color_company}} !important;
        }
        .navigation .mobile-nav .retreive a {
            background: #{{$company->color_company}} !important;
        }
        .price-label {
            color: #{{$company->color_company}} !important;
        }
        .card:focus, .card:hover {
            border-bottom-color: #{{$company->color_company}} !important;
        }
        .featured-more a {
            background: #{{$company->color_company}} !important;
        }
        .footer-content {
            background: #{{$company->color_company}} !important;
        }
        .widget-header {
            background: #{{$company->color_company}} !important;
        }
        .btn {
            background: #{{$company->color_company}} !important;
            border: 0px !important;
        }
        .swal2-popup .swal2-styled.swal2-confirm {
            background-color: #{{$company->color_company}} !important;
        }
        .product-tabs .nav-tabs .nav-link.active, .product-tabs .nav-tabs .nav-link:focus {
            border-top-color: #{{$company->color_company}} !important;
            color: #{{$company->color_company}} !important;
        }
        button:focus,.bootstrap-select .dropdown-toggle:focus {
            outline: none!important;
            box-shadow: none!important;
        }
        </style>
        @endif
        <style>
        .wrap-nospace {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .quote {
            text-shadow: 2px 2px #000 !important;
        }
        </style>
        @if($company->font_color_company)
        <style>
        .right-nav .searchtop .retreive a {
            color: #{{$company->font_color_company}} !important;
        }
        .featured-more a {
            color: #{{$company->font_color_company}} !important;
        }
        .footer-content {
            color: #{{$company->font_color_company}} !important;
        }
        .footer-content p {
            color: #{{$company->font_color_company}} !important;
        }
        .footer-content .footer-contact ul li a {
            color: #{{$company->font_color_company}} !important;
        }
        .widget-header h3 {
            color: #{{$company->font_color_company}} !important;
        }
        .btn-primary {
            color: #{{$company->font_color_company}} !important;
        }
        .swal2-popup .swal2-styled.swal2-confirm {
            color: #{{$company->font_color_company}} !important;
        }
        #checkout-footer .btn-checkout {
            color: #{{$company->font_color_company}} !important;
        }
        </style>
                @endif
        <style>
            .bootstrap-select{
                width: 3.5rem!important;
                outline:none!important;
            }
            
            @media only screen and (max-width: 320px) {
                .mobile-nav {
                    padding: 0 1.482rem!important;
                }
            }
            @media only screen and (min-width: 300px) and (max-width: 767px){
                body.ready #header .bootstrap-select .btn{
                    color:#000000!important;
                }

                #maincontent{
                    padding: 1rem 0;
                }

                #maincontent .container .btn{
                    font-size:10px;
                }
                #desktop-view{
                    display:none;
                }
            }
            @media only screen and (min-width: 720px){
                #mobile-view{
                    display:none;
                }
            }

            body.ready #header {
                background: rgba(255, 255, 255, 0.9);
                -webkit-transform: translateY(0);
                transform: translateY(0);
            }
        </style>

    </head>

    <body data-spy="scroll" data-target=".mainmenu" data-offset="140">
    <div id="wrapper">
        <header id="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col no-pading">
                    <div class="d-flex align-items-center flex-wrap">
                    <div class="navigation d-flex align-items-center">
                    @if(isset($company->logo))
                        <div class="logo">
                            <a href="{{ Route('memoria.home') }}"><img src="{{ asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo ) }}" alt="{{ Session::get('company_name') }}" style="max-height:55px;"></a>
                        </div>
                    @else
                        <div class="logo">
                            <a href="{{ Route('memoria.home') }}"><img src="{{ asset('dest-operator/img/logo1.png') }}" alt="{{ Session::get('company_name') }}" style="max-height:55px;"></a>
                        </div>
                    @endif

                        <form action="{{ route('general:change-language') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="mobile-nav">
                            <span class="retreive">
                                <a href="{{ Route('memoria.retrieve') }}">{{__('home.retrieve')}}</a>
                            </span>

                            <select class="selectpicker lang" name="lang">
                                <option
                                @if(app()->getLocale() =='id')
                                    selected
                                @endif
                                value="id" data-content='<span>IDN</span>' data-display='static'> </option>
                                <option 
                                @if(app()->getLocale() =='en')
                                    selected
                                @endif
                                value="en" data-content='<span>ENG</span>' data-display='static'> </option>
                            </select>
                        </div>
                        </form>
                        
                    </div>
                    <div class="right-nav opened" style="margin-left: 35.4rem;">
                        <form action="{{ route('general:change-language') }}" method="POST">
                        {{ csrf_field() }}
                            <div class="searchtop lang_btn">
                                <a class="btn btn-primary" href="{{ Route('memoria.retrieve') }}">{{__('home.retrieve')}}</a>

                                <select class="selectpicker lang" name="lang">
                                    <option 
                                    @if(app()->getLocale() =='id')
                                        selected
                                    @endif
                                     value="id" data-content='<span><img src="../img/idn-flag.png" alt=""></span>&nbsp;&nbsp;&nbsp; Indonesia'></option>
                                    <option
                                    @if(app()->getLocale() =='en')
                                        selected
                                    @endif
                                     value="en" data-content='<span><img src="../img/uk-flag.png" alt=""></span> &nbsp;&nbsp;&nbsp; English'> </option>
                                </select>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        </header>
        
        @yield('main_content')
        <footer id="footer">
            <div class="footer-content">
            <div class="container">
                <div class="row">
                <div class="col">
                    <h3>{{__('home.about')}}</h3>
                </div>
                </div>
                <div class="row">
                <div class="col-sm-12 col-md-8">
                    @if($company->about)
                    <p>{{$company->about}}</p>
                    @else
                    <p>{{__('home.about_description')}}</p>
                    @endif
                    @if($company->address_company || $company->phone_company)
                    <hr>
                    <div class="footer-address">
                    <ul class="d-flex flex-wrap">
                        @if($company->address_company)
                        <li>
                        <span class="fa fa-home"></span>
                        {{$company->address_company}}
                        </li>
                        @endif
                        @if($company->phone_company)
                        <li>
                        <span class="fa fa-phone"></span>
                        {{$company->phone_company}}
                        </li>
                        @endif
                    </ul>
                    </div>
                    @endif
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="footer-contact">
                    <ul>
                        @if($company->email_company)
                        <li>
                        <a href="mailto:{{$company->email_company}}">
                            <span class="fa fa-envelope"></span>
                            {{$company->email_company}}
                        </a>
                        </li>
                        @endif
                            @if($company->facebook_company && $company->facebook_company !==null && $company->facebook_company !=='')
                        <li>
                        <a href="http://facebook.com/{{$company->facebook_company}}">
                            <span class="fa fa-facebook"></span>
                            @if(strlen($company->facebook_company)>20)
                            Our Facebook
                            @else
                            facebook.com/{{$company->facebook_company}}
                            @endif
                        </a>
                        </li>
                        @endif
                        @if($company->twitter_company && $company->twitter_company !==null && $company->twitter_company !=='')
                        <li>
                        <a href="http://instagram.com/{{$company->twitter_company}}">
                            <span class="fa fa-instagram"></span>
                            {{'@'.$company->twitter_company}}
                        </a>
                        </li>
                        @endif
                    </ul>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <div class="footer-copyright">
            <div class="container">
                <div class="row">
                <div class="col">
                    <div class="copyright text-center">
                    {{__('home.powered')}} <a href="https://mygomodo.com"><img src="{{ asset('landing-page/assets/images/logo.png') }}" alt="GOMODO" style="height: 24px !important;"></a>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </footer>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        
        <script src="{{ asset('dest-customer/js/client.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/core.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            toastr.options = {
                "positionClass": "toast-bottom-right",
            };
        </script>
        @yield('additionalScript')

        @if (session()->has('error_message'))
            <script>
                toastr.error("{{session()->get('error_message')}}", '{{__('general.whoops')}}')
            </script>
        @endif

        <script>
            window.jQuery = window.$ = jQuery;
            $(document).ready(function() {
                $('.lang').on('change', function(){
                    $(this).closest('form').submit();
                });
            });
        </script>
    </div>
    <!-- </body></html> -->
    </body>
</html>
