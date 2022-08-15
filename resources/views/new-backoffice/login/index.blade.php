<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>BUPSHA</title>

  <!-- Global stylesheets -->
  <link rel="shortcut icon" href="{{asset('img/klhk.png')}}" />
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk_global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/bootstrap_limitless.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
  <!-- slick carousel -->
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/slick.css')}}" rel="stylesheet" type="text/css">
  <link href="{{asset('klhk-asset/dest-operator/klhk-assets/css/slick-theme.css')}}" rel="stylesheet" type="text/css">
  <!-- CUSTOM CSS GOMODO -->
  <link rel="stylesheet" href="{{asset('klhk-asset/dest-operator/css/back-office.css')}}">

  <!-- /global stylesheets -->



  <!-- /theme JS files -->
</head>
<body>

{{--<div id="login-header">--}}
{{--  <div class="card">--}}
{{--    <div class="card-header text-center">--}}
{{--      <img src="{{asset('img/klhk.png')}}" class="image-rounded" alt="klhk" width="100">--}}
{{--      <h2 class="text-center">KLHK</h2>--}}
{{--    </div>--}}
{{--    @foreach ($errors->all() as $error)--}}
{{--        <div class="alert alert-danger mx-3">--}}
{{--            {{$error}}--}}
{{--        </div>--}}
{{--    @endforeach--}}
{{--    <form class="card-body text-center" method="post" action="{{ route('admin:do.login') }}">--}}
{{--      {{ csrf_field() }}--}}
{{--      <span>Admin Login</span>--}}
{{--      <input type="email" class="form-control mb-2" name="email" placeholder="Email">--}}
{{--      <input type="password" class="form-control" name="password" placeholder="Kata Sandi">--}}
{{--      <input type="checkbox" name="remember" class="d-none" checked>--}}
{{--      <button class="btn btn-primary mt-5" type="submit">Masuk</button>--}}
{{--    </form>--}}
{{--    <div class="card-footer text-center">--}}
{{--      <span>Copyright Gomodo Technologies</span>--}}
{{--    </div>--}}
{{--  </div>--}}
{{--</div>--}}

<div class="page-content">
  <div class="content-wrapper">
    <div class="content d-flex justify-content-center align-items-center">
      <form class="login-form" method="post" action="{{ route('admin:do.login') }}">
        {{ csrf_field() }}
        <div class="card mb-0">
          <div class="card-body">
            <div class="text-center mb-3">
              <img src="{{asset('img/klhk.png')}}" class="image-rounded" alt="KLHK image" width="100">
              <h5 class="mb-0">Admin Login</h5>
              <span class="d-block text-muted">BUPSHA</span>
            </div>
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger mx-3">
                    {{$error}}
                </div>
            @endforeach
            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}">
              <div class="form-control-feedback">
                <i class="icon-user text-muted"></i>
              </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
              <input type="password" class="form-control" name="password" placeholder="Kata Sandi">
              <div class="form-control-feedback">
                <i class="icon-lock text-muted"></i>
              </div>
            </div>
            <input type="checkbox" name="remember" class="d-none" checked>
            <div class="form-group">
              <button class="btn btn-success btn-block" type="submit">
                Masuk
              </button>
            </div>
          </div>
            <div class="card-footer text-center">
              <span>Copyright Gomodo Technologies</span>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>

</body>
<!-- Core JS files -->
<script src="{{asset('limitless/global_assets/js/main/jquery.min.js')}}"></script>
<script src="{{asset('limitless/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('limitless/assets/js/dashboard-custom-hnd.js')}}"></script>
<script src="{{asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js')}}"></script>
<!-- /core JS files -->

<!-- Theme JS files -->

<script src="{{asset('limitless/assets/js/app.js')}}"></script>
<script src="{{asset('limitless/js/custom.js')}}"></script>
</html>
