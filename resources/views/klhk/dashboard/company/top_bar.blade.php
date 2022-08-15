<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light navbar-static">

  <!-- Header with logos -->
  <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
    <div class="navbar-brand navbar-brand-md">
      <a href="index.html" class="d-inline-block">
        <!-- <img src="/global_assets/images/logo_light.png" alt=""> -->
      </a>
    </div>
    
    <div class="navbar-brand navbar-brand-xs">
      <a href="index.html" class="d-inline-block">
        {{-- <img src="/global_assets/images/logo_icon_light.png" alt=""> --}}
      </a>
    </div>
  </div>
  <!-- /header with logos -->


  <!-- Mobile controls -->
  <div class="d-flex flex-1 d-md-none">
    <div class="navbar-brand mr-auto">
      <a href="index.html" class="d-inline-block">
        {{-- <img src="/global_assets/images/logo_dark.png" alt=""> --}}
      </a>
    </div>	

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
      <i class="icon-tree5"></i>
    </button>

    <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
      <i class="icon-paragraph-justify3"></i>
    </button>
  </div>
  <!-- /mobile controls -->


  <!-- Navbar content -->
  <div class="collapse navbar-collapse" id="navbar-mobile">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
          <i class="icon-paragraph-justify3"></i>
        </a>
      </li>
    </ul>

    <!-- <span class="badge bg-indigo-400 badge-pill ml-md-3 mr-md-auto">Hi, Admin Kece</span> -->
    @include('klhk.dashboard.company.partial.language')
  </div>
  <!-- /navbar content -->
  
</div>
<!-- /main navbar -->
