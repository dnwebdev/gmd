<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gomodo | @yield('title')</title>
    <link rel="stylesheet" href="{{asset('landing/bootstrap/css/bootstrap.min.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('landing/fonts/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('landing/fonts/ionicons.min.css')}}">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    <link rel="stylesheet" href="{{asset('landing/css/Article-List.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Brands.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Contact-Form-Clean.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Features-Boxed.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Footer-Dark.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Header-Dark.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Highlight-Blue.css')}}">
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">--}}
    {{--    <link rel="stylesheet" href="{{asset('landing/css/Lightbox-Gallery-1.css')}}">--}}
    {{--    <link rel="stylesheet" href="{{asset('landing/css/Lightbox-Gallery.css')}}">--}}
    <link rel="stylesheet" href="{{asset('landing/css/styles.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/Testimonials.css')}}">
    <link rel="stylesheet" href="{{asset('landing/css/register.css')}}">
    <link rel="stylesheet" href="{{asset('css/intlTelInput.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    {{--    CUSTOM--}}
    <link rel="stylesheet" href="{{ asset('css/inline-fix.css') }}">
    <style>
        .toggle-password {
            cursor: pointer;
        }

        /* Disable spin button */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .card-header {
            background-color: #2699FB !important;
        }

        .card-header > strong {
            color: white;
        }

        .card, .navbar, .pagination .page-item.active .page-link {
            box-shadow: 0 2px 5px 0 rgba(0, 0, 0, .16), 0 2px 10px 0 rgba(0, 0, 0, .12);
        }

        .card {
            margin-top: 6rem;
        }
        a#backBtn {
            width: 3rem;
        }
    </style>

    @yield('styles')
</head>
<body>
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
<div class="registration-form container-fluid d-flex justify-content-center">
    <div class="contact-clean box">
        <div class="card">
            <a id="backBtn" class="btn" href="{{ url('/') }}">
                <i class="fa fa-home"></i>
            </a>
            <div class="card-body px-lg-5 pt-0">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{asset('landing/js/jquery.min.js')}}"></script>
<script src="{{asset('landing/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('landing/js/float-panel.js')}}"></script>
<script src="{{asset('landing/js/bs-animation.js')}}"></script>
<script src="{{ asset('js/intlTelInput.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{asset('js/validation-hnd.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    function loadingStart() {
        $('.loading').addClass('show');
        $('button').prop('disabled', true);
        $('button').attr('disabled', true);
    }

    function loadingFinish() {
        $('.loading').removeClass('show');
        $('button').prop('disabled', false);
        $('button').attr('disabled', false);
    }

    $(document).on('keyup paste change', 'input, select, textarea', function () {
        $(this).closest('.form-group').find('label.error').remove();
        $('input[name=email]').closest('.form-group').find('label.error').remove();
        $('#error_domain').find('label.error').remove();
        $('#error_phone').find('label.error').remove();
    });
</script>
@yield('script')

</html>