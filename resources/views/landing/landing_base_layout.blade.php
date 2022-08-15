<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="ScreenOrientation" content="autoRotate:disabled">
    <title>Gomodo | {{ trans('landing.title') }}</title>
    <link rel="stylesheet" href="landing/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="landing/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="landing/fonts/ionicons.min.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing/css/Article-List.css">
    <link rel="stylesheet" href="landing/css/Brands.css">
    <link rel="stylesheet" href="landing/css/Contact-Form-Clean.css">
    <link rel="stylesheet" href="landing/css/Features-Boxed.css">
    <link rel="stylesheet" href="landing/css/Footer-Dark.css">
    <link rel="stylesheet" href="landing/css/Header-Dark.css">
    <link rel="stylesheet" href="landing/css/Highlight-Blue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <link rel="stylesheet" href="landing/css/Lightbox-Gallery-1.css">
    <link rel="stylesheet" href="landing/css/Lightbox-Gallery.css">
    <link rel="stylesheet" href="landing/css/styles.css">
    <link rel="stylesheet" href="landing/css/Testimonials.css">
    <link rel="stylesheet" href="landing/css/one-platform.css">
    <link rel="stylesheet" href="landing/css/language.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css"/>
    <link rel="stylesheet" href="landing/css/stay-update-modal.css">
    <link rel="stylesheet" href="landing/css/inline-fix.css"> {{-- let this css in bottom --}}
    <link rel="shortcut icon" href="landing/img/favicon.png" type="image/x-icon">
    <link rel="icon" href="landing/img/favicon.png" type="image/x-icon">
    <meta name="Description"
          content="Gomodo has industry leading online booking, point of sale, mobile booking, integrated payments, waivers, and more. See what Gomodo can do for your business.">
    <meta name="Keywords"
          content="booking online website, integrated payments, scheduling system, customer booking software">
    <meta property="og:image" content="landing/img/favicon.png"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image:alt" content="Gomodo | {{ trans('landing.title') }}"/>
    <meta property="og:url" content="{{url('/')}}"/>
    <meta property="fb:app_id" content="887163081476612"/>
    <meta property="og:title" content="Gomodo | {{ trans('landing.title') }}"/>
    <meta property="og:description"
          content="Gomodo has industry leading online booking, point of sale, mobile booking, integrated payments, waivers, and more. See what Gomodo can do for your business."/>
    <meta name="twitter:card" content="summary"/>
    <script type='application/ld+json'>
        {"@context":"https://schema.org","@type":"Organization","url":"https://www.mygomodo.com/","sameAs":["http://www.facebook.com/gomodo","https://www.linkedin.com/company/gomodo","https://youtube.com/gomodo","https://twitter.com/gomodo"],"@id":"https://www.mygomodo.com/","name":"Gomodo","logo":"https://www.mygomodo.com/assets/img/logo-gomodo.png"}

    </script>
    <!-- Drip -->
    <script type="text/javascript">
        var _dcq = _dcq || [];
        var _dcs = _dcs || {};
        _dcs.account = '6532846';

        (function () {
            var dc = document.createElement('script');
            dc.type = 'text/javascript';
            dc.async = true;
            dc.src = '//tag.getdrip.com/6532846.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(dc, s);
        })();
    </script>
    <!-- end Drip -->
    @if(env('APP_ENV')=='production')
        <script type="text/javascript" src="//script.crazyegg.com/pages/scripts/0088/4562.js" async="async"></script>
        @include('analytics')
    @endif
    @yield('additionalStyle')
</head>

<body>

@include('landing.header')

@yield('content')

@include('landing.footer')
<div id="backtop">&#9650;</div>
<script src="landing/js/jquery.min.js"></script>
<script src="landing/bootstrap/js/bootstrap.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="landing/js/float-panel.js"></script>
<script src="landing/js/bs-animation.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    $(document).on('click', '.address .title', function (e) {
        $(this).closest('.mb-2').find('.content').stop().slideToggle('slow')
        $(this).closest('.mb-2').find('.fa').stop().toggleClass('fa-caret-up')
    });
    $(document).on('click', '.compare .title,.compare .content', function () {
        if ($(this).hasClass('title')) {
            $(this).closest('li').find('.content').fadeToggle('fast')
            $(this).closest('li').find('.title').hide()
        } else {
            $(this).closest('li').find('.content').hide()
            $(this).closest('li').find('.title').fadeToggle('fast')
        }

    });
    toastr.options = {
        "positionClass": "toast-bottom-left",
    };
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    })

    function recaptchaCallback() {
        $('#hiddenRecaptcha').valid();
    };
    $('#form-contact-us').validate({
        ignore: ".ignore",
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            message: {
                required: true
            },
            hiddenRecaptcha: {
                required: function () {
                    if (grecaptcha.getResponse() == '') {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        },
        messages: {
            name: '{{trans('landing.validation.required')}}',
            email: '{{trans('landing.validation.required')}}',
            message: '{{trans('landing.validation.required')}}',
            hiddenRecaptcha: '{{trans('landing.validation.required')}}'
        },
        errorPlacement: function (error, element) {
            error.insertAfter($(element));
        }
    });
    window.onscroll = function () {
        myFunction()
    };

    $('#form-contact-us').on('submit', function (e) {
        e.preventDefault();
        let t = $(this);
        var btn_submit = $('#btn-contact-submit');
        btn_submit.button('loading');
        btn_submit.prop('disabled', true);
        if (t.valid()) {
            $.ajax({
                type: 'POST',
                url: "{{route('memoria.sendmail')}}",
                data: t.serialize(),
                dataType: 'json',
                success: function (data) {
                    $('#form-contact-us')[0].reset();
                    grecaptcha.reset();
                    toastr.success(data.message, 'Congrats')
                    btn_submit.button('reset');
                    btn_submit.prop('disabled', false);
                },
                error: function (e) {
                    $('#form-contact-us')[0].reset();
                    grecaptcha.reset();
                    toastr.error('{{__('general.whoops')}}')
                    btn_submit.button('reset');
                    btn_submit.prop('disabled', false);
                }
            })
        } else {
            btn_submit.prop('disabled', false);
        }
    });

    // Get the header
    var header = document.getElementById("top-navbar");

    // Get the offset position of the navbar
    var sticky = header.offsetTop;

    // Add the "sticky" class to the header when reach its scroll position. Remove "sticky" when leave the scroll position
    function myFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("fixed");
        } else {
            header.classList.remove("fixed");
        }
    }

    $(document).on("click", ".link-landing", function (event) {
        console.log($(this).data('destination'));
        let $div = $($(this).data('destination'));
        console.log($div)
        $('html, body').stop();
        $('html, body').animate({
            scrollTop: $div.offset().top - 20
        }, 1000);
    });

    $(document).on('keypress change search input paste cut', 'input, select', function (evt) {
        let t = $(this);
        if (t.val() !== '') {
            t.addClass('filled')
        } else {
            t.removeClass('filled')
        }
    })
    $(document).on('click', '.current-language', function () {
        $('.language-option').slideToggle('slow')
    });
    $(document).on('click', '.box-language', function () {
        let form = $('#landing-change-language');
        form.find('input[name=lang]').val($(this).parent().data('value'));
        form.submit();
    })
    $(document).ready(function(){
        $('#stayUpToDate').modal('show');
    })
</script>

@yield('additionalScript')

</body>

</html>