<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{config('app.name')}} | Login</title>
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
    <link href="/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="/assets/demo/default/media/img/logo/favicon.ico"/>
</head>

<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">

<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1"
         id="m_login" style="background-image: url(/img/static/bg-login.png);">
        <div class="m-grid__item m-grid__item--fluid m-login__wrapper" style="margin: 0;padding: 3% 2rem 1rem 2rem">
            <div class="m-login__container">
                <div class="m-login__logo">
                    <a href="#">
                        <img src="/assets/app/media/img/logos/logo-1.png">
                    </a>
                </div>
                <div class="m-login__signin" style="
                        background-color: #211b1b;
                        padding: 15px;
                        border-radius: 30px;
                        box-shadow: 10px 10px 40px rgba(0,0,0,0.7);
                ">
                    <div class="m-login__head">
                        <h3 class="m-login__title">Reset Password</h3>
                    </div>
                    {!! Form::open(['class'=>'m-login__form m-form']) !!}
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="text" placeholder="Email" name="email"
                               value="{{request('email')}}"
                               autocomplete="off" readonly>
                        <input class="form-control m-input" type="hidden" placeholder="Email" name="token"
                               value="{{request('token')}}"
                               autocomplete="off" readonly>
                        @if($errors->has('email'))
                            {!! '<label class="text-danger">'.$errors->first('email').'</label>' !!}
                        @endif
                        @if($errors->has('token'))
                            {!! '<label class="text-danger">'.$errors->first('token').'</label>' !!}
                        @endif
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input" type="password"
                               placeholder="Password" name="password">
                        @if($errors->has('password'))
                            {!! '<label class="text-danger">'.$errors->first('password').'</label>' !!}
                        @endif
                    </div>
                    <div class="form-group m-form__group">
                        <input class="form-control m-input m-login__form-input--last" type="password"
                               placeholder="Password Confirmation" name="password_confirmation">
                    </div>
                    <div class="row m-login__form-sub">
                        <div class="col m--align-right m-login__form-right">
                            <a href="/back-office/login" class="m-link">Login</a>
                        </div>
                    </div>
                    <div class="m-login__form-action">
                        <button id=""
                                class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary">
                            Reset
                        </button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<script src="/assets/snippets/custom/pages/user/login.js" type="text/javascript"></script>
</body>
</html>