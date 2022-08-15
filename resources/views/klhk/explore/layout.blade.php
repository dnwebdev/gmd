<!DOCTYPE html>
<!--[if IE 8]>
<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>
<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->
<html> <!--<![endif]-->
<head>


    @if (isset($blog) && $blog!==null)

        @if (App::getLocale() === 'id')
            <title>{{$blog->title_ind}}</title>
        @else
            <title>{{$blog->title_eng}}</title>
        @endif
        <meta name="keywords" content="@if($blog->tagValue)
        @forelse($blog->tagValue as $tag)
        @if($tag->tagBlogPost)
        @if (App::getLocale() ==='id'){{$tag->tagBlogPost->tag_name_ind}},
        @else{{$tag->tagBlogPost->tag_name_eng}},@endif
        @endif
        @empty
        @endforelse
        @endif"/>
        @if (App::getLocale() === 'id')
            <meta name="description" content="{!! strip_tags(str_limit($blog->description_ind,150)) !!}">
        @else
            <meta name="description" content="{{$blog->description_eng}}">
        @endif
        <meta name="author" content="{{ $blog->author->admin_name }}">
        <meta property="og:image" content="{{ asset($blog->image_blog) }}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:image:alt" content="KLHK | {{ trans('landing.title') }}"/>
        <meta property="og:url" content="{{url('/explore/blog', [$blog->slug])}}"/>
        <meta property="fb:app_id" content="887163081476612"/>
        @if (App::getLocale() === 'id')
            <meta property="og:title" content="{{$blog->title_ind}}"/>
        @else
            <meta property="og:title" content="{{$blog->title_eng}}"/>
        @endif
        @if (App::getLocale() === 'id')
            <meta property="og:description" content="{!! strip_tags(str_limit($blog->description_ind,150)) !!}"/>
        @else
            <meta property="og:description" content="{!! strip_tags(str_limit($blog->description_eng,150)) !!}"/>
        @endif
        <meta name="twitter:card" content="summary"/>
    @else
        <title>Pesona | {{trans('explore-klhk-lang.content.homepage.1')}}</title>
        <meta name="keywords" content="pesona, kups, bupsha, hutan kemasyarakatan, ekowisata"/>
        <meta name="description" content="Pesona adalah platform layanan pemberdayaan digital bagi penyedia
        ekowisata di seluruh Indonesia yang berada di bawah Kementrian Lingkungan Hidup dan Kehutanan. Direktori bisnis
        ekowisata ini digunakan untuk membantu usaha di bidang ekowisata untuk tumbuh dan berkembang dengan menyediakan
         teknologi perangkat lunak yang dapat digunakan oleh para penyedia ekowisata untuk dapat bersaing secara digital.">
        <meta name="author" content="KLHK">
        <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta property="og:image" content="{{asset('explore-assets/images/logo-pesona.png')}}"/>
        <meta property="og:type" content="website"/>
        <meta property="og:image:alt" content="Pesona | {{trans('explore-klhk-lang.content.homepage.1')}}"/>
        <meta property="og:url" content="{{url('/')}}"/>
        <meta property="fb:app_id" content="887163081476612"/>
        <meta property="og:title" content="Pesona | {{trans('explore-klhk-lang.content.homepage.1')}}"/>
        <meta property="og:description" content="Pesona adalah platform layanan pemberdayaan digital bagi penyedia
        ekowisata di seluruh Indonesia yang berada di bawah Kementrian Lingkungan Hidup dan Kehutanan. Direktori bisnis
        ekowisata ini digunakan untuk membantu usaha di bidang ekowisata untuk tumbuh dan berkembang dengan menyediakan
         teknologi perangkat lunak yang dapat digunakan oleh para penyedia ekowisata untuk dapat bersaing secara digital.
"/>
        <meta name="twitter:card" content="summary"/>
    @endif

<!-- Meta Tags -->
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('explore-assets/images/logo-pesona.png')}}" type="image/x-icon">
    <!-- Theme Styles -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('explore-assets/css/bootstrap.min.css')}}"> 
    <link rel="stylesheet" href="{{asset('explore-assets/css/font-awesome.min.css')}}">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{asset('explore-assets/css/animate.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

    <!-- Main Style -->
    <link id="main-style" rel="stylesheet" href="{{asset('explore-assets/css/style.css')}}">

    <!-- Updated Styles -->
    <link rel="stylesheet" href="{{asset('explore-assets/css/updates.css')}}">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{asset('explore-assets/css/custom.css')}}">

    <!-- Responsive Styles -->
    <link rel="stylesheet" href="{{asset('explore-assets/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('explore-assets/css/custom-klhk.css') }}"> {{-- Custom KLHK CSS --}}

    <!-- CSS for IE -->
    <!--[if lte IE 9]>
    <link rel="stylesheet" type="text/css" href="{{asset('explore-assets/css/ie.css')}}"/>
    <![endif]-->

    @yield('css')

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script type='text/javascript' src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script type='text/javascript' src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
    <![endif]-->
    @if (env('APP_ENV')==='production')
        @include('analytics')
        <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0088/4562.js" async="async"></script>
    @endif
</head>
<body>
<div id="page-wrapper">
    {{-- Loading --}}
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

    <div id="provider-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div class="provider-login">
                <h1>{{ trans('explore-klhk-lang.partner.login') }}</h1>
                <a href="{{route('login')}}">
                    <button class="button btn-medium uppercase sky-blue1">{{ trans('explore-lang.partner.login_b') }}</button>
                </a>
            </div>
            <div class="provider-signup">
                <h1>{{ trans('explore-klhk-lang.partner.register') }}</h1>
                @if(app()->environment() == 'production')
                    <a href="http://{{env('B2B_DOMAIN')}}">
                        <button class="button btn-medium uppercase sky-blue1">{{ trans('explore-lang.partner.register_b') }}</button>
                    </a>
                @else
                    <a href="{{route('memoria.partner')}}">
                        <button class="button btn-medium uppercase sky-blue1">{{ trans('explore-lang.partner.register_b') }}</button>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- header start here -->
    @include('klhk.explore.header')

<!-- section content start here -->
    @yield('content')


<!-- footer start here -->
    @include('klhk.explore.footer')
</div>
@include('javascript')
<!-- Javascript -->
<script type="text/javascript" src="{{asset('explore-assets/js/jquery-1.11.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('explore-assets/js/jquery.noconflict.js')}}"></script>
<script type="text/javascript" src="{{asset('explore-assets/js/modernizr.2.7.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('explore-assets/js/jquery-migrate-1.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('explore-assets/js/jquery.placeholder.js')}}"></script>
<script type="text/javascript" src="{{asset('explore-assets/js/jquery-ui.1.10.4.min.js')}}"></script>
<script type="text/javascript"
        src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<!-- Twitter Bootstrap -->
<script type="text/javascript" src="{{asset('explore-assets/js/bootstrap.js')}}"></script>


<!-- parallax -->
<script type="text/javascript" src="{{asset('explore-assets/js/jquery.stellar.min.js')}}"></script>

<!-- waypoint -->
<script type="text/javascript" src="{{asset('explore-assets/js/waypoints.min.js')}}"></script>

<!-- load page Javascript -->
<script type="text/javascript" src="{{asset('explore-assets/js/theme-scripts_klhk.js')}}"></script>

<script>
    tjq(document).ready(function () {
        if (localStorage.getItem('sidebar') == 1) {
            tjq('#sidebar-recent-post-blog').toggleClass('active');
            tjq('#sidebar-popular-post-blog').removeClass('active');
            tjq('#recent-posts').toggleClass('in active');
            tjq('#popular-posts').removeClass('in active');
        }
    });
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
    tjq('#main-menu ul li').not('.provider-login-nav, li.language, .ribbon').on('click', function (e) {
        tjq('.loading').addClass('show');
    });
    // tjq('.ribbon').click(function(e){
    //     e.preventDefault();
    //     tjq('.loading').removeClass('show');
    // });
    tjq(document).on('submit', 'form#homepage-newsletter', function (e) {
        e.preventDefault();
        loadingStart('form#homepage-newsletter');
        let data = tjq(this).serialize();
        tjq('form#homepage-newsletter').find('span.error').remove();
        tjq('form#homepage-newsletter').find('span.success').remove()
        tjq.ajax({
            url: "{{route('explore.subscribe')}}",
            data: data,
            success: function (res) {
                loadingEnd('form#homepage-newsletter', '{{trans('explore-lang.footer.subscribe')}}');
                tjq('form#homepage-newsletter').append(`<span class="success">${res.message}</span>`);
            },
            error: function (err) {
                let e;
                if (err.status === 422) {
                    e = err.responseJSON.errors.email[0];

                } else {
                    e = err.responseJSON.message
                }
                loadingEnd('form#homepage-newsletter', '{{trans('explore-lang.footer.subscribe')}}');
                tjq('form#homepage-newsletter').append(`<span class="error">${e}</span>`)
            }
        })
    });

    tjq('.recent-post-blog').click(function () {
        localStorage.setItem('sidebar', 1);
    });

    tjq('.popular-post-blog').click(function () {
        localStorage.removeItem('sidebar');
    });

    function loadingStart(place) {
        var loaderButton = tjq(place).find('.loading-button');
        loaderButton.addClass('loader');
        loaderButton.attr('disabled', true);
        loaderButton.html('<span class="lds-dual-ring"></span>Loading');
    }

    function loadingEnd(place, initialText) {
        var loaderButton = tjq(place).find('.loading-button');
        loaderButton.removeClass('loader');
        loaderButton.attr('disabled', false);
        loaderButton.html(initialText);
    }

    tjq(document).on('click', '#english, #indonesia', function () {
        let form = tjq('#landing-change-language');
        form.find('input[name=lang]').val(tjq(this).data('value'));
        form.submit();
    });

    // Provider Login Modal
    tjq(document).on('click', '.provider-login-nav', function (e) {
        e.preventDefault();
        tjq('#provider-modal').css('display', 'block');
    })
    tjq(document).on('click', '.close', function () {
        tjq('#provider-modal').css('display', 'none');
    });
    tjq(document).on('click', 'li.language', function (e) {
        e.preventDefault();
    });
    tjq(document).ready(function () {
        tjq('.provider-login-nav').removeClass('active');
    });
    tjq(document).on('click', function (event) {
        if (!tjq(event.target).closest(".modal-content, .provider-login-nav").length) {
            tjq('#provider-modal').css('display', 'none');
        }
    });

    // Prevent Char in Input Type Number
    tjq(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function (e) {
        if (!digitOnly(e)) {
            e.preventDefault();
        }
    });

    function digitOnly(e) {
        let allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight'];
        if (!allowed.includes(e.key)) {
            return false
        }
        return true
    }

    // Disable Paste in input type number
    tjq(document).on('paste', 'input[type="number"], input[type="tel"], .number', function (e) {
        e.preventDefault();
    });
    // Disable Scroll in input type number
    tjq(document).on('wheel', 'input[type="number"], input[type="tel"], .number', function (e) {
        tjq(this).blur();
    });
</script>
@yield('scripts')
</body>
</html>