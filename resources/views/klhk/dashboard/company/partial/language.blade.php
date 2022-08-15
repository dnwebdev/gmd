<form id="language" action="{{ route("general:change-language") }}" method="POST">
  {{ csrf_field() }}
  <ul class="navbar-nav">
    <li class="nav-item dropdown">
      @if(app()->getLocale() == 'en')
      <a class="navbar-nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/images/lang/gb.png') }}" class="img-flag mr-2" alt="">
        English
      </a>
      @else
      <a class="navbar-nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/images/lang/ind.png') }}" class="img-flag mr-2" alt="">
        Indonesia
      </a>
      @endif

      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
        @if(app()->getLocale() == 'en')
        <span class="img-flag dropdown-item language-selected indonesia" data-selected="id"><img src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/images/lang/ind.png') }}" alt=""> Indonesia</span>
        @else
        <span class="img-flag dropdown-item language-selected english" data-selected="en"><img src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/images/lang/gb.png') }}" alt=""> English</span>
        @endif
      </div>

      <select class="selectpicker d-none" id="lang" name="lang">
        <option 
          @if(app()->getLocale() =='id')
            selected
          @endif
        value="id" data-content='<span><img src="/img/idn-flag.png" alt=""></span>&nbsp;&nbsp;&nbsp;Indonesia'> </option>
        <option value="en"
          @if(app()->getLocale() =='en')
            selected
          @endif
        data-content='<span><img src="/img/uk-flag.png" alt=""></span>&nbsp;&nbsp;&nbsp;English'> </option>
      </select>
    </li>

    <!-- <li class="nav-item dropdown dropdown-user">
      <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
        <img src="/global_assets/images/placeholders/placeholder.jpg" class="rounded-circle mr-2" height="34" alt="">
        <span>Admin Kece</span>
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
        <a href="#" class="dropdown-item"><i class="icon-cog5"></i> Account settings</a>
        <a href="#" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
      </div>
    </li> -->
  </ul>
</form>

<script>
  $(document).on('click', '.language-selected', function(){
    var it = $(this);
    $(document).find('#lang').val(it.attr('data-selected'));
    $('form#language').submit();
  })
</script>