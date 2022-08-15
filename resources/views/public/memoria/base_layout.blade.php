<!DOCTYPE html>
<html lang="en">

<head>
  @include('analytics')
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Gomodo</title>

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
  <link rel="icon" type="image/png" href="{{ asset('landing/img/Logo.png') }}">
  <link rel="manifest" href="{{ asset('landing-page/assets/icons/manifest.json') }}">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="{{ asset('landing-page/assets/icons/ms-icon-144x144.png') }}">
  <meta name="theme-color" content="#ffffff">
  <!-- Bootstrap Core CSS -->
  <link href="{{ asset('landing-page/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('landing-page/css/landing.css') }}" rel="stylesheet">
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="{{ Route('memoria.home') }}">
        <img src="{{ asset('landing/img/Logo-Gomodo.png') }}" alt="" style="width: 130px;">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="{{ Route('memoria.home') }}">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          {{--<li class="nav-item">--}}
            {{--<a class="nav-link" href="#how-it-works">Features</a>--}}
          {{--</li>--}}
          {{--<li class="nav-item">--}}
            {{--<a class="nav-link" href="#contact">Contact</a>--}}
          {{--</li>--}}
          {{--<li class="nav-item mr-2">--}}
            {{--<a class="nav-link" href="{{ Route('agent.register') }}">Register</a>--}}
          {{--</li>--}}
          <li class="nav-item">
            <button type="button" onclick="location.href='{{ Route('login') }}';" class="btn btn-outline-primary" href="https://{{env('APP_URL')}}/agent/login">Login</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="main-content">
    @yield('header')
    @yield('content')
  </div>
    
     <!-- Footer -->
  <footer class="py-5 bg-light">
    <div class="container">
      <div class="row">
        <div class="col-auto mx-auto">
          <img src="{{ asset('landing-page/assets/images/footer-banner-1.png') }}" alt="">
        </div>
        <div class="col-auto mx-auto">
          <img src="{{ asset('landing-page/assets/images/footer-banner-2.png') }}" alt="">
        </div>
      </div>
    </div>
  </footer>

    <!-- Bootstrap core JavaScript -->
  <script src="{{ asset('landing-page/vendor/jquery/jquery.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
  <script src="{{ asset('landing-page/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script type="text/javascript">
    $.validator.setDefaults({
        submitHandler: function() {
            var recaptcha = $('#g-recaptcha-response').val();
            if (recaptcha === '') {
              alert("Please verify captcha!");
            } else {
              $.post("sendmail", {
                _token:$('input[name="_token"]').eq(0).val(),
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                message: $('#message').val()
              },
              function(data,status){
                if (status === 'success') alert("Form submitted!");
                else alert("Failed to submit form!");
              });
            }
        }
    });

    $(document).ready(function() {
        $("#contactForm").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    number: true,
                    minlength: 9
                },
                message: {
                    required: true,
                    minlength: 30
                }
            },
            messages: {
                name: {
                    required: "Please enter your name",
                    minlength: "Your name must consist of at least 2 characters"
                },
                email: "Please enter a valid email address",
                phone: {
                    required: "Please enter your phone number",
                    number: "Please enter a valid phone number",
                    minlength: "Your phone number must consist of at least 9 characters"
                },
                message: {
                    required: "Please enter your message",
                    minlength: "Your message must consist of at least 30 characters"
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                // Add the `help-block` class to the error element
                error.addClass("help-block text-danger");

                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.parent("label"));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
            }
        });
    });
</script>
  <script async src="{{ asset('landing-page/js/validForm.js') }}"></script>
  @yield('additionalScript')
</body>
</html>