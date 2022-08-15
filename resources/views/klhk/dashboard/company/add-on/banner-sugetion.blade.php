<!-- promo carousel/ use slick carousel -->
<div class="row dashboard-promo--container">
  <div class="col-lg-12">
    <div class="card">
      <div class="dashboard-promo">
          <div style="padding: .5rem">
            <a href="{{ route('company.premium.index') }}"><img src="{{asset('dest-operator/img/banner_-_premium2-01.jpg')}}" style="width: 100%; max-width: 100%;"/></a>
          </div>
          <div style="padding: .5rem">
            <a href="{{ route('company.premium.index') }}"><img src="{{asset('dest-operator/img/banner_-_premium-01.jpg')}}" style="width: 100%; max-width: 100%;"/></a>
          </div>
          {{-- <div style="padding: .5rem">
            <a href="{{ route('company.premium.index') }}"><img src="{{ asset('klhk-asset/dest-operator/klhk-assets/img/banner-3-indo.png') }}" style="width: 100%; max-width: 100%;"/></a>
          </div> --}}
        </div>
    </div>
  </div>
</div> 

<!-- carousel widget -->
<script>
    $(document).ready(function(){
      var goPromo = true; // change to false if no promo
      $('.dashboard-promo').slick({
          infinite: true,
          slidesToShow: 2,
          slidesToScroll: 1,
          responsive: [
            { breakpoint: 640, settings: {
                rows: 1,
                slidesToShow: 1,
                slidesToScroll: 1
            }}
          ]
        });
      if(!goPromo) {
        $('.dashboard-promo--container').css('display', 'none');
      } 
    })
  </script>