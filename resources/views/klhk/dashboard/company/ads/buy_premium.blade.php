<!-- BUY PREMIUM -->
<div class="dashboard-ads">
  <div class="widget gxp-head">
    <div class="container-fluid form-inline">
      <p id="header-gxp">{{ trans('premium.gxp.gxp1') }}{{ $company->company_name }}</p>
      <span id="balance-gxp" class="my-auto"><img src="{{asset('dest-operator/img/gxp-img.svg')}}" class="tooltips" title="{!! trans('premium.gxp.toooltip_about') !!}">IDR {{ number_format($gxp_sum['gxp'], 0) }} <a href="{{ route('company.premium.gxp') }}" id="to-gxp-page">{{ trans('premium.gxp.gxp2') }}</a></span>
    </div>
  </div>
  <!-- BNNER BAHASA INDONESIA -->
  <div id="carouselBannerDekstopIndonesiaAds" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner_-_premium2-01.jpg')}}" alt="First slide">
      </div>
      {{-- <div class="carousel-item">
        <!-- LINK THIS IMAGE! -->
        <a href="https://medium.com/gomodo-user-guides-english/gomodo-boost10x-capai-10x-transaksi-dapatkan-voucher-premium-store-senilai-total-rp-300-ribu-d79eb8463d9d" target="_blank"><img class="d-block w-100" src="{{asset('dest-operator/img/banner-2-indo.png')}}" alt="Second slide"></a>
      </div> --}}
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner_-_premium-01.jpg')}}" alt="First slide">
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselBannerDekstopIndonesiaAds" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselBannerDekstopIndonesiaAds" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <!-- BANNER BAHASA INDONESIA MOBILE -->
  <div id="carouselBannerMobileIndonesiaAds" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner-mobile-ads-indo1.png')}}" alt="First slide">
      </div>
      <div class="carousel-item">
        <!-- LINK THIS IMAGE! -->
        <a href="https://medium.com/gomodo-user-guides-english/gomodo-boost10x-capai-10x-transaksi-dapatkan-voucher-premium-store-senilai-total-rp-300-ribu-d79eb8463d9d" target="_blank"><img class="d-block w-100" src="{{asset('dest-operator/img/banner-mobile-ads-indo2.png')}}" alt="Second slide"></a>
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner-mobile-ads-indo3.png')}}" alt="First slide">
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselBannerMobileIndonesiaAds" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselBannerMobileIndonesiaAds" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  
  <!-- BANNER BAHASA INGGRIS -->
  <div id="carouselBannerDesktopEnglishAds" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner-1-eng.png')}}" alt="First slide">
      </div>
      <div class="carousel-item">
        <!-- LINK THIS IMAGE! -->
        <a href="https://medium.com/gomodo-user-guides-english/gomodo-boost10x-capai-10x-transaksi-dapatkan-voucher-premium-store-senilai-total-rp-300-ribu-d79eb8463d9d" target="_blank"><img class="d-block w-100" src="{{asset('dest-operator/img/banner-2-eng.png')}}" alt="Second slide"></a>
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner-3-eng.png')}}" alt="First slide">
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselBannerDesktopEnglishAds" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselBannerDesktopEnglishAds" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <!-- BANNER BAHASA INGGRIS MOBILE -->
  <div id="carouselBannerMobileInggrisAds" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner-mobile-ads-eng1.png')}}" alt="First slide">
      </div>
      <div class="carousel-item">
        <!-- LINK THIS IMAGE! -->
        <a href="https://medium.com/gomodo-user-guides-english/gomodo-boost10x-capai-10x-transaksi-dapatkan-voucher-premium-store-senilai-total-rp-300-ribu-d79eb8463d9d"><img class="d-block w-100" src="{{asset('dest-operator/img/banner-mobile-ads-eng2.png')}}" alt="Second slide"></a>
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('dest-operator/img/banner-mobile-ads-eng3.png')}}" alt="First slide">
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselBannerMobileInggrisAds" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselBannerMobileInggrisAds" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
  <nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-item nav-link {{(Request::has('tab') && Request::get('tab')==='my-feature')||!Request::has('tab')?'active':''}}" id="nav-available-feauture" data-toggle="tab" href="#nav-feauture" role="tab" aria-controls="nav-feauture" aria-selected="true">{{ trans('premium.tab.feature_available') }}</a>
      <a class="nav-item nav-link {{ Request::has('tab') && Request::get('tab')==='my-premium'?'active':''}}" id="nav-my-premium" data-toggle="tab" href="#nav-mypremium" role="tab" aria-controls="nav-mypremium" aria-selected="false">{{ trans('premium.tab.my_premium') }}</a>
      <a class="nav-item nav-link {{ Request::has('tab') && Request::get('tab')==='my-voucher'?'active':''}}" id="nav-my-voucher" data-toggle="tab" href="#nav-myvoucher" role="tab" aria-controls="nav-myvoucher" aria-selected="false">{{ trans('premium.tab.my_voucher') }}</a>
      <a href="#" class="float-right faq-section" data-toggle="modal" data-target="#modalFaqPremium"><i class="fa fa-question-circle"></i> FAQ {{ trans('premium.banner.faq') }}</a>
      @include('klhk.dashboard.company.ads.modal_faq_premium')
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade {{(Request::has('tab') && Request::get('tab')==='my-feature')||!Request::has('tab')?'show active':''}}" id="nav-feauture" role="tabpanel" aria-labelledby="nav-available-feature">
      @include('klhk.dashboard.company.ads.feature_premium')
    </div>
    <div class="tab-pane fade {{ Request::has('tab') && Request::get('tab')==='my-premium'?'show active':'' }}" id="nav-mypremium" role="tabpanel" aria-labelledby="nav-my-premium">
      @include('klhk.dashboard.company.ads.detail_premium')
    </div>
    <div class="tab-pane fade {{ Request::has('tab') && Request::get('tab')==='my-voucher'?'show active':'' }}" id="nav-myvoucher" role="tabpanel" aria-labelledby="nav-myvoucher">
      @include('klhk.dashboard.company.ads.my_voucher')
    </div>
  </div>
</div>
