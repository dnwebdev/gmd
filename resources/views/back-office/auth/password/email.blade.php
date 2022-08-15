<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{config('app.name')}}- Login </title>
    {{--<link class="main-stylesheet" href="{{asset('css/kayiz.css')}}" rel="stylesheet" type="text/css"/>--}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        @font-face {
            font-family: 'Lato';
            font-style: normal;
            font-weight: 400;
            src: local('Lato Regular'), local('Lato-Regular'), url(http://themes.googleusercontent.com/static/fonts/lato/v7/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
        }

        body {
            background: #448ed3;
            font-family: "Lato";
        }

        .wrap {
            width: 320px;
            height: auto;
            margin: auto;
            margin-top: 10%;
        }

        .wrap input {
            border: none;
            background: #fff;
            font-family: Lato;
            font-weight: 700;
            display: block;
            height: 40px;
            outline: none;
            width: calc(100% - 24px);
            margin: auto;
            padding: 6px 12px 6px 12px;
        }

        .bar {
            width: 100%;
            height: 1px;
            background: #fff;
        }

        .bar i {
            width: 95%;
            margin: auto;
            height: 1px;
            display: block;
            background: #d1d1d1;
        }

        .wrap input[type="email"] {
            border-radius: 7px 7px 0px 0px;
        }
        .wrap input[type="checkbox"] {
            display: inline-block;
            width: 20px;
            padding: 0px;
        }

        .wrap input[type="password"] {
            border-radius: 0px 0px 7px 7px;
        }

        .forgot_link {
            color: #83afdf;
            text-decoration: none;
            font-size: 11px;
            position: relative;
            left: 250px;
            top: -36px;
        }

        .wrap button {
            width: 100%;
            border-radius: 7px;
            background: #b6ee65;
            text-decoration: none;
            border: none;
            color: #51771a;
            margin-top: -5px;
            padding-top: 14px;
            padding-bottom: 14px;
            outline: none;
            font-size: 13px;
            border-bottom: 3px solid #307d63;
            cursor: pointer;
        }
        label{
            display: inline-block;
            margin-top: 12px;
            position: absolute;
        }

        .avatar {
            margin: auto;
            width: 88px;
            border-radius: 50%;
            height: 88px;
            background: #448ed3;
            position: relative;
            bottom: -15px;
        }
        .avatar img {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            margin: auto;
            border: 3px solid #fff;
            display: block;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="avatar">
        <img src="http://placehold.it/40">
    </div>
    {!! Form::open() !!}
    {!! Form::email('email',null,['placeholder'=>'email','required','style'=>'border-radius:0.25rem']) !!}
    <a href="{{url('/backoffice/login')}}" class="forgot_link">Back to Login</a>

    <button>Request Password</button>

    {!! Form::close() !!}
</div>
@include('javascript')
<script src="{{asset('back/vendors/base/vendors.bundle.js')}}" type="text/javascript"></script>
<script>
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
    if (typeof nlec !== 'undefined') {
        if (nlec.error !== undefined) {
            toastr.error(nlec.error);
        }
        if (nlec.success !== undefined) {
            toastr.success(nlec.success);
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
</body>
</html>