<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      
    <!-- Bootstrap CSS -->
    <!--<link rel="stylesheet" href="{{ asset('themes/gogreen/css/bootstrap.min.css') }}" crossorigin="anonymous">-->
    

    <!--
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">-->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    
    <link href="https://fonts.googleapis.com/css?family=Hind:400,500" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('themes/gogreen/css/style.css') }}" crossorigin="anonymous">

    
@yield('additionalStyle')
</head>
<body>
    
    <div style="margin-top: -30px">
      <div class="container" style="margin-bottom: 0px ;">            
         <nav class="navbar navbar-expand-lg navbar-light bg-faded">
              <a class="navbar-brand" href="#"><img width="100px" src="{{ Session::get('company_logo') }}" alt="logo" style="width: 60px; height: 40px" /></a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div id="navbarNavDropdown" class="navbar-collapse collapse">
                  <ul class="navbar-nav mr-auto">
                    
                  </ul>
                  <ul class="navbar-nav">
                      <li class="nav-item dropdown">
                          <a class="nav-link" style="color: black" href="#">Activity</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" style="color: black" href="#">Tour</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" style="color: black" href="#">About</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link" style="color: black" href="#">Contact</a>
                      </li>
                  </ul>
              </div>
          </nav>
      </div>
    </div>

    @yield('main_content')
        
    <footer style="background-color: #033b32; width: 100%; padding: 0px">
      <div class="container" style="background-color: #033b32; margin: 0px auto; ">
          <nav class="navbar navbar-expand-lg navbar-light bg-faded" align="center" >
                <div class="div_space">
                    <a class="navbar-brand" href="#" align="center"><img width="100px" src="{{ Session::get('company_logo') }}" alt="logo" style="width: 60px; height: 40px" /></a>
                </div>
                
                <div id="navbarNavDropdown" class="navbar-collapse" ><!--collapse dihapus jadi menunya bisa keliatan-->
                    <ul class="navbar-nav mr-auto">
                      
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">                            
                            <a class="nav-link" style="color: white" href="{{ url('/login') }}">&nbsp; Retrieving Booking &nbsp;</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: white" href="{{ url('/login') }}">&nbsp; Pembayaran &nbsp;</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: white" href="{{ url('/register') }}">&nbsp; Syarat & Ketentuan &nbsp;</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" style="color: white" href="{{ url('/register') }}">&nbsp;Kebijakan Privasi &nbsp;</a>
                        </li>
                    </ul>
                </div>
          </nav>

      </div>
    </footer>
    
    <!--
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" ></script>
    <script src="{{ asset('themes/gogreen/js/bootstrap.js') }}" ></script>
    -->

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>


    <script src="{{ asset('themes/gogreen/js/main.js') }}"></script>
    <script>
        function myFunction() {
            var x = document.getElementById("myTopnav");
            if (x.className === "topnav") {
                x.className += " responsive";
            } else {
                x.className = "topnav";
            }
        }
    </script>
    @yield('additionalScript')
  </body>
</html>