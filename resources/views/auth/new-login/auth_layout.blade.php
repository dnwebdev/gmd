<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Gomodo | @yield('title')</title>
     <!-- New -->
    <!-- Global stylesheets -->
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
            background: url({{ asset('img/bg-gomodo.jpg') }}) no-repeat;
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
            background-color: #2196f3!important;
            color: white;
        }

        .img-login{
            max-width: 100%;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0 transparent, 0 1px 0 #2196f3;
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

    // Init -------------------------------------------------------------------------------------------

    var swalInit = swal.mixin({
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary',
        cancelButtonClass: 'btn btn-light'
    });

    // Event -------------------------------------------------------------------------------------------

    $(document).on('change keydown', 'input[type="number"], input[type="tel"], .number', function (e) {
        if (!digitOnly(e)) {
            e.preventDefault();
        }
    });
    $(document).on('keyup', '.alpha-num-only', function (e) {
        $(this).val($(this).val().replace(/[^A-Za-z0-9]+/g, ''))
    });

    // General Function -------------------------------------------------------------------------------------------

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

    function digitOnly(e) {
        let allowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'MetaLeft', 'MetaRight', 'v'];
        if (!allowed.includes(e.key)) {
            return false
        }
        return true
    }

    // Catch paste
    function catchPaste(evt, elem, callback) {
        if (navigator.clipboard && navigator.clipboard.readText) {
            // modern approach with Clipboard API
            navigator.clipboard.readText().then(callback);
        } else if (evt.originalEvent && evt.originalEvent.clipboardData) {
            // OriginalEvent is a property from jQuery, normalizing the event object
            callback(evt.originalEvent.clipboardData.getData('text'));
        } else if (evt.clipboardData) {
            // used in some browsers for clipboardData
            callback(evt.clipboardData.getData('text/plain'));
        } else if (window.clipboardData) {
            // Older clipboardData version for Internet Explorer only
            callback(window.clipboardData.getData('Text'));
        } else {
            // Last resort fallback, using a timer
            setTimeout(function() {
            callback(elem.value)
            }, 100);
        }
    }

</script>
@yield('script')
</html>
