<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Gomodo | Dashboard</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <link href="{{asset('assets/vendors/base/vendors.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/demo/default/base/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{{asset('css/kayiz.css')}}">
    <link rel="shortcut icon" href="{{asset('landing/img/Logo.png')}}"/>
    @yield('styles')
</head>
<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
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
<div class="m-grid m-grid--hor m-grid--root m-page">
    @include('back-office.layout.header')
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
        <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i
                    class="la la-close"></i></button>
        @include('back-office.layout.sidebar')
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
            <div class="m-subheader ">
                @yield('subheader')
            </div>
            <div class="m-content">
                @yield('content')

            </div>
        </div>
    </div>
    @include('back-office.layout.footer')
</div>

<div id="m_scroll_top" class="m-scroll-top">
    <i class="la la-arrow-up"></i>
</div>
@include('javascript')

<script src="{{asset('assets/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/demo/default/base/scripts.bundle.js')}}" type="text/javascript"></script>
<script>
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

</script>
<script src="{{asset('assets/vendors/custom/fullcalendar/fullcalendar.bundle.js')}}" type="text/javascript"></script>

<script src="{{asset('assets/app/js/dashboard.js')}}" type="text/javascript"></script>
<script>

    Number.prototype.formatMoney = function (places, symbol, thousand, decimal, front) {
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "$";
        thousand = thousand || ",";
        decimal = decimal || ".";
        let number = this,
            negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "";
        let j = i.length;
        if (j > 3) {
            j = j % 3;
        } else {
            j = 0;
        }

        front = front || 0;
        if (front === 0) {

            return negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "") + " " + symbol;
        } else {
            return symbol + ' ' + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
        }
    };
    $(".number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .(190)
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode == 187 && (e.shiftKey === true || e.metaKey === true)) ||
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            (e.keyCode == 189 && (e.shiftKey === false || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 109) || (e.keyCode == 106 || e.keyCode == 110)) {
            e.preventDefault();
        }
    });

    $(document).on('keyup paste change', '.price', function () {
        let t  =$(this);
        let p = parseFloat(t.val().replace(/[^0-9-]/g, ''));
        if (t.hasClass('nullable')){
            if (isNaN(p)){
                t.val('')
            }else{
                t.val(p.formatMoney(0, '', '.', '', 1))
            }

        }else{
            t.val(p.formatMoney(0, '', '.', '', 1))
        }
    });
    $(document).on('keyup paste change', 'input, select, textarea', function () {
        $(this).closest('.form-group').find('label.error').remove()
    });

    $(document).ready(function () {
        $(".price").each(function (i) {
            let t  =$(this);
            let p = parseFloat(t.val().replace(/[^0-9-]/g, ''));
            if (t.hasClass('nullable')){
                if (isNaN(p)){
                    t.val('')
                }else{
                    t.val(p.formatMoney(0, '', '.', '', 1))
                }

            }else{
                t.val(p.formatMoney(0, '', '.', '', 1))
            }
        });
    });
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    if (typeof gomodo !== 'undefined') {
        if (gomodo.error !== undefined) {
            toastr.error(gomodo.error,'{{__('general.whoops')}}');
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
</script>
@if ($errors->any())

    @foreach ($errors->all() as $error)
        <script>
            toastr.error("{{$error}}",'{{__('general.whoops')}}')
        </script>
    @endforeach

@endif
@yield('scripts')
</body>

</html>