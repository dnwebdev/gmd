<header id="header" class="dashboard-nav">
<!-- Main sidebar -->
<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- Sidebar nav -->
        <div data-template="side_navbar">
            <!-- User menu -->
            <div class="sidebar-user-material">
                <div class="sidebar-user-material-body" style="background: url({{ asset('klhk-asset/dest-operator/klhk_global_assets/images/backgrounds/user_bg3.jpg')}}) center center no-repeat; background-size: cover;">
                <div class="card-body text-center">
                    @if($company->logo)
                        <a href="http://{{ $company->domain_memoria }}" target="_blank"><img src="{{ asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo ) }}" style="height: 100px !important;" alt="avatar"></a>
                    @else
                        <a href="http://{{ $company->domain_memoria }}" target="_blank"><img src="{{ asset('img/no-product-image.png') }}" class="img-fluid rounded-lg shadow-1 mb-3" width="80" height="80" alt=""></a>
                    @endif
                    <h6 class="mb-0 mt-1 text-white text-shadow-dark">{{$company->company_name}}</h6>
                </div>
                </div>
            </div>
            <!-- /user menu -->
            
            <!-- Main navigation -->
            <div class="card card-sidebar-mobile">
                <ul class="nav nav-sidebar" data-nav-type="accordion">
            
                <!-- Main -->
                <!-- <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li> -->
                <li class="nav-item">
                    <a href="{{ Route('company.dashboard') }}" class="nav-link {{ Request::is('company/dashboard') ? 'active' : '' }}">
                    <i class="icon-home4"></i>
                    <span>
                        {{ trans('sidebar_provider.dashboard') }}
                        {{-- <span class="d-block font-weight-normal opacity-50">No active orders</span> --}}
                    </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ Route('company.order.index') }}" class="nav-link {{ Request::is('company/order') || Request::is('company/order/*') || Request::is('company/order/edit') ? 'active' : '' }}">
                        <i class="icon-basket"></i> 
                        <span>{{ trans('sidebar_provider.order') }}</span> <span class="badge count-update-order badge-danger badge-pill ml-auto"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ Route('company.product.index') }}" class="nav-link {{ Request::is('company/product') || Request::is('company/product/*') ? 'active' : '' }}">
                        <i class="icon-price-tag3"></i> 
                        <span>{{ trans('sidebar_provider.product') }}</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu {{ Request::is('company/manual-order') || Request::is('company/manual-order/*') || Request::is('company/voucher') || Request::is('company/voucher/create') || Request::is('company/voucher/*/edit') || Request::is('company/premium') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link"><i class="icon-puzzle"></i> <span>{{ trans('sidebar_provider.feature') }}</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ trans('sidebar_provider.feature') }}">
                        <li class="nav-item"><a href="{{route('company.manual.index')}}" class="nav-link {{ Request::is('company/manual-order') || Request::is('company/manual-order/*') ? 'active' : '' }}">{{ trans('sidebar_provider.e_invoice') }}</a></li>
                        <li class="nav-item"><a href="{{ Route('company.voucher.index') }}" class="nav-link {{ Request::is('company/voucher') || Request::is('company/voucher/create') || Request::is('company/voucher/*/edit') ? 'active' : '' }}">{{ trans('sidebar_provider.voucher') }}</a></li>
                        <li class="nav-item"><a href="{{ Route('company.premium.index') }}" class="nav-link {{ Request::is('company/premium') ? 'active' : '' }}">{{ trans('sidebar_provider.premium_store') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item d-none">
                    <a href="{{ Route('company.distribution.index') }}" class="nav-link {{ Request::is('company.distribution.index') ? 'active' : '' }}">
                        <i class="icon-share2" style="margin-left: .1rem;"></i> 
                        <span>{{ trans('sidebar_provider.distribution') }}</span>
                    </a>
                </li>
                <li class="nav-item d-none">
                    <a href="{{ Route('company.product.index') }}" class="nav-link {{ Request::is('company/product') ? 'active' : '' }}">
                        {{-- <i class="icon-plus-circle2"></i>  --}}
                        <img src="{{ asset('dest-operator/img/health_square.png')}}" class="support" alt="">
                        <span>{{ trans('sidebar_provider.insurance') }}</span>
                    </a>
                </li>
                <li class="nav-item d-none">
                    <a href="{{ Route('company.finance.index') }}" class="nav-link {{ Request::is('company/finance') ? 'active' : '' }}">
                        <i class="icon-wallet"></i>
                        <span>{{ucfirst(trans('sidebar_provider.financing'))}}</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu {{ Request::is('company/kyc') || Request::is('company/bank') || Request::is('company/bank/*') || Request::is('company/withdraw') || Request::is('company/profile') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        {{-- <i class="icon-user"></i>  --}}
                        <img src="{{ asset('dest-operator/img/user_circle.png')}}" class="support" alt="">
                        <span>{{ trans('sidebar_provider.account_and_setting') }}</span>
                    </a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ trans('sidebar_provider.account_and_setting') }}">
                        <li class="nav-item"><a href="{{ Route('company.kyc.index') }}" class="nav-link {{ Request::is('company/kyc') ? 'active' : '' }}">{{trans('general.kyc')}}</a></li>
                        <li class="nav-item"><a href="{{ Route('company.bank.index') }}" class="nav-link {{ Request::is('company/bank') || Request::is('company/bank/*') ? 'active' : '' }}">{{ trans('sidebar_provider.bank_account') }}</a></li>
                        <li class="nav-item"><a href="{{ Route('company.withdraw.index') }}" class="nav-link {{ Request::is('company/withdraw') ? 'active' : '' }}">{{ trans('sidebar_provider.withdrawal') }}</a></li>
                        <li class="nav-item"><a href="{{ Route('company.profile') }}" class="nav-link {{ Request::is('company/profile') ? 'active' : '' }}">{{ trans('sidebar_provider.settings') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu {{ Request::is('company/help') || Request::is('company/article') || Request::is('company/updates') ? 'nav-item-expanded nav-item-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="icon-help"></i> 
                        {{-- <img src="{{ asset('dest-operator/img/support.png')}}" class="support" alt=""> --}}
                        <span>{{ trans('sidebar_provider.help_and_feedback') }}</span>
                    </a>
                    <ul class="nav nav-group-sub" data-submenu-title="{{ trans('sidebar_provider.help_and_feedback') }}">
                        <li class="nav-item"><a href="{{ Route('company.help.index') }}" class="nav-link {{ Request::is('company/help') ? 'active' : '' }}">{{ trans('sidebar_provider.help') }}</a></li>
                        {{-- <li class="nav-item"><a href="{{ Route('company.blog.index') }}" class="nav-link {{ Request::is('company/article') ? 'active' : '' }}">{{ trans('sidebar_provider.articles') }}</a></li> --}}
                        <li class="nav-item"><a href="{{ Route('company.update.index') }}" class="nav-link {{ Request::is('company/updates') ? 'active' : '' }}">{{ trans('sidebar_provider.updates') }} <span class="badge count-update-all badge-danger badge-pill ml-auto"></span></a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#logout_modal">
                        <i class="icon-switch"></i>
                        <span>{{ trans('sidebar_provider.log_out') }}</span>
                    </a>
                </li>
                
                
                <!-- /layout -->
                    
                </ul>
            </div>
            <!-- /main navigation -->
        </div>
        <!-- /sidebar nav -->

    </div>
    <!-- /sidebar content -->
</div>
<!-- /main sidebar -->

  <script>
    // Sidebar navigation
    var _navigationSidebar = function() {
  
    // Define default class names and options
    var navClass = 'nav-sidebar',
        navItemClass = 'nav-item',
        navItemOpenClass = 'nav-item-open',
        navLinkClass = 'nav-link',
        navSubmenuClass = 'nav-group-sub',
        navSlidingSpeed = 250;
  
    // Configure collapsible functionality
    $('.' + navClass).each(function() {
      $(this).find('.' + navItemClass).has('.' + navSubmenuClass).children('.' + navItemClass + ' > ' + '.' + navLinkClass).not('.disabled').on('click', function (e) {
        e.preventDefault();
  
        // Simplify stuff
        var $target = $(this),
          $navSidebarMini = $('.sidebar-xs').not('.sidebar-mobile-main').find('.sidebar-main .' + navClass).children('.' + navItemClass);
  
        // Collapsible
        if($target.parent('.' + navItemClass).hasClass(navItemOpenClass)) {
          $target.parent('.' + navItemClass).not($navSidebarMini).removeClass(navItemOpenClass).children('.' + navSubmenuClass).slideUp(navSlidingSpeed);
        }
        else {
          $target.parent('.' + navItemClass).not($navSidebarMini).addClass(navItemOpenClass).children('.' + navSubmenuClass).slideDown(navSlidingSpeed);
        }
  
        // Accordion
        if ($target.parents('.' + navClass).data('nav-type') == 'accordion') {
          $target.parent('.' + navItemClass).not($navSidebarMini).siblings(':has(.' + navSubmenuClass + ')').removeClass(navItemOpenClass).children('.' + navSubmenuClass).slideUp(navSlidingSpeed);
        }
      });
    }); 
  
    // Disable click in disabled navigation items
    $(document).on('click', '.' + navClass + ' .disabled', function(e) {
      e.preventDefault();
    });
  
    // Scrollspy navigation
      $('.nav-scrollspy')
      .find('.' + navItemClass)
      .has('.' + navSubmenuClass)
      .children('.' + navItemClass + ' > ' + '.' + navLinkClass)
      .off('click');
  };
  
  // Navbar navigation
    var _navigationNavbar = function() {
  
      // Prevent dropdown from closing on click
      $(document).on('click', '.dropdown-content', function(e) {
        e.stopPropagation();
      });
  
      // Disabled links
      $('.navbar-nav .disabled a, .nav-item-levels .disabled').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
      });
  
      // Show tabs inside dropdowns
      $('.dropdown-content a[data-toggle="tab"]').on('click', function(e) {
        $(this).tab('show');
      });
    };
  
    // call function
    _navigationSidebar();
    _navigationNavbar();
  </script>
</header>


