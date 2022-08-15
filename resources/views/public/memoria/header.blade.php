@section('header')
<!-- Header -->
<header>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" role="listbox">
        <!-- Slide One -->
        <div class="carousel-item active" style="background-image: url('landing-page/assets/images/carousel-1.jpg')">
          <div class="carousel-caption text-left">
            <h3>Welcome to</h3>
            <h1>GOMODO</h1>
            <p class="mb-0">A simple and free to use website builder and booking management software</p>
            <p>for small and medium businesses providing tours and activities.</p>
            <p>
              <a class="btn btn-lg btn-primary" href="#background" role="button">Learn More</a>
            </p>
          </div>
        </div>
        <!-- Slide Two -->
        <div class="carousel-item" style="background-image: url('landing-page/assets/images/carousel-2.jpg')">
          <div class="carousel-caption text-left">
            <h3>Create tours and activities to sell in your booking section</h3>
            <p>Quickly create items to display on your new website</p>
          </div>
        </div>
        <!-- Slide Three -->
        <div class="carousel-item" style="background-image: url('landing-page/assets/images/carousel-3.jpg')">
          <div class="carousel-caption text-left">
            <h3>Immediately receive online bookings and payments!</h3>
            <p>Customers are able to pay for your tours and activities online with various payment options, or if you prefer,
              let them pay you with cash onsite.</p>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </header>
<!-- /.intro-header -->
@endsection