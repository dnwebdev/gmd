<!doctype html>
<html lang="en">
<head>
  @include('analytics')
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Gomodo">

  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('landing-page/assets/icons/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('landing-page/assets/icons/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('landing-page/assets/icons/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('landing-page/assets/icons/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('landing-page/assets/icons/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('landing-page/assets/icons/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('landing-page/assets/icons/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('landing-page/assets/icons/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('landing-page/assets/icons/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png') }}" href="{{ asset('landing/img/favicon.png') }}">
  <link rel="manifest" href="{{ asset('landing-page/assets/icons/manifest.json') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{ asset('landing-page/assets/icons/ms-icon-144x144.png') }}">
  <meta name="theme-color" content="#ffffff">

  <title>Login | Gomodo for Business</title>
  <link href="{{ asset('dest-operator/lib/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('dest-operator/lib/css/fontface.min.css') }}" rel="stylesheet">
  <link href="{{ asset('dest-operator/css/index.css') }}" rel="stylesheet">
  <link href="{{ asset('dest-operator/css/login.css') }}" rel="stylesheet">
  <style>
    /*LOADING*/
    .loading {
        display: none;
        height: 100vh!important;
        position: fixed!important;
        background-color: rgba(0, 0, 0, 0.7)!important;
        width: 100%!important;
        z-index: 999999!important;
        justify-content: center!important;
        align-items: center!important;
    }
    .loading .loading-content {
        display: flex!important;
        color: white!important;
        justify-content: center!important;
        align-items: center!important;
    }

    .loading .loading-content .spin,.loading-text{
        display: block!important;
        margin: 5px!important;
    }

    .loading.show{
        display: flex!important;
    }
    .loading .loading-content{
        margin-top: 50vh!important;
    }
  </style>
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
  <div class="hero full" id="login">
    <div class="panel login-panel">
      <div class="logo">
        <a href="{{Route('memoria.home')}}"><img src="{{ asset('landing/img/Logo-Gomodo.png') }}" style="height: 32px !important;" alt="GOMODO"></a>
        <h2>{{trans('landing.login.gomodo_for_bussiness')}}</h2>
      </div>
      <div class="form login-form">
        <form method="POST" action={{ route('login') }} >
        {{ csrf_field() }}
          @if($errors)
            @foreach ($errors->all() as $error)
                <div class="pink-text">{{ $error }}</div>
            @endforeach
          @endif
          <div class="form-group">
            <div class="form-with-icon">
              <span class="form-icon"><span class="fa fa-user-circle-o"></span></span>
              <input class="form-control" autofocus="autofocus" id="email" type="text" name="email" placeholder="Email" aria-label="Email" required/>
            </div>
          </div>
          <div class="form-group">
            <div class="password-viewer form-with-icon">
              <span class="form-icon"><span class="fa fa-lock"></span></span>
              <input type="password" class="form-control" id="password" name="password" placeholder="{{trans('landing.register.password')}}" aria-label="Password" required />
              <span class="form-icon password-viewer-icon"><span class="fa fa-eye"></span></span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">{{trans('landing.navbar.login')}}</button>
          <div class="text-center">
            <p><a href="{{ route('password.request') }}">{{trans('landing.login.forgot_password')}}</a></p>
          </div>
          <small class="form-text text-center">
              {{trans('landing.login.dont_have_account')}}
              <a href="{{route('memoria.register')}}">{{trans('landing.login.sign_up')}}</a>
          </small>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('dest-operator/lib/js/jquery-slim.min.js') }}"></script>
  <script src="{{ asset('dest-operator/lib/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('dest-operator/js/operator.js') }}"></script>
  <script src="{{ asset('dest-operator/js/login.js') }}"></script>
  <script>
      localStorage.clear();
      jQuery(document).on('submit', 'form', function(){
        jQuery('.loading').show();
      })
  </script>
</body>