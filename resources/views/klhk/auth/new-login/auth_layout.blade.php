<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
     <!-- New -->
    <!-- Global stylesheets -->
    <link rel="shortcut icon" href="{{asset('explore-assets/images/logo-pesona.png')}}" type="image/x-icon">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ asset('klhk-asset/dest-operator/klhk_global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
	<link href="{{ asset('klhk-asset/dest-operator/klhk-assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{asset('css/intlTelInput.min.css')}}">
    <style>
        .loading {
            display: none;
            height: 100vh;
            position: fixed;
            background-color: rgba(0, 0, 0, 0.7);
            width: 100%;
            z-index: 999999;
            justify-content: center;
            align-items: center;
        }
        .loading .loading-content {
            display: flex;
            color: white;
            justify-content: center;
            align-items: center;
        }

        .loading .loading-content .spin,.loading-text{
            display: block;
            margin: 5px;
        }

        .loading.show{
            display: flex;
        }

        .login {
            background: url({{ asset('img/login-klhk.jpg') }}) no-repeat;
            background-size: cover;
        }

        a#backBtn {
            width: 3rem;
        }

        a#backBtn {
            border-radius: 0px;
            position: relative;
            /*margin-left: -10px;*/
            margin-left: 0px;
        }

        #backBtn {
            margin-bottom: 1rem;
            background-color: #4caf50!important;
            color: white;
        }

        .img-login{
            max-width: 100%;
        }
    </style>
    @yield('styles')
</head>

<body>
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
<!-- Page content -->
<div class="page-content login">
    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Content area -->
        <div class="content d-flex justify-content-center align-items-center">
            @yield('content')
        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->
</div>
<!-- /page content -->
</body>
<!-- Core JS files -->
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/ui/ripple.min.js') }}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>

<script src="{{ asset('klhk-asset/dest-operator/klhk-assets/js/app.js') }}"></script>
{{--<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/extra_sweetalert.js') }}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{ asset('js/intlTelInput.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<!-- /theme JS files -->

<script>

    var swalInit = swal.mixin({
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-light'
    });

    function loadingStart() {
        $('.loading').addClass('show');
        $('button').prop('disabled',true);
        $('button').attr('disabled',true);
    }

    function loadingFinish() {
        $('.loading').removeClass('show');
        $('button').prop('disabled',false);
        $('button').attr('disabled',false);
    }

    $(document).on('keyup paste change', 'input, select, textarea', function () {
        $(this).closest('.form-group').find('span.error').remove();
        $('input[name=email]').closest('.form-group').find('span.error').remove();
        $('#error_domain').find('span.error').remove();
    });

    $(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function (e) {
        if (!digitOnly(e)) {
            e.preventDefault();
        }
    })

    function digitOnly(e) {
        let allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'MetaLeft', 'MetaRight'];
        if (!allowed.includes(e.key)) {
            return false
        }
        return true
    }

</script>
@yield('script')
</html>
