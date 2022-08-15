<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
         m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <li class="m-menu__item  {{Route::currentRouteNamed('admin:dashboard*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:dashboard')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">Dashboard</span>
                        </span>
                    </span>
                </a>
            </li>
            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">Master Data</h4>
                <i class="m-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{Request::is('back-office/ota/*')?'m-menu__item--open':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-earth-globe"></i><span
                            class="m-menu__link-text">OTA</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span
                                    class="m-menu__link"><span
                                        class="m-menu__link-text">OTA</span></span></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:ota.index*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:ota.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">List OTA</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:ota.list.index*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:ota.list.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Product List</span></a></li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:hhbk.*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:hhbk.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-interface-10"><span></span>
                    </i>
                    <span class="m-menu__link-text">HHBK</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:hhbk-distribution.*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:hhbk-distribution.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-interface-10"><span></span>
                    </i>
                    <span class="m-menu__link-text">HHBK Transaction</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.state*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.state.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-placeholder-3"><span></span>
                    </i>
                    <span class="m-menu__link-text">State</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.city*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.city.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-placeholder-3"><span></span>
                    </i>
                    <span class="m-menu__link-text">City</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.business-category*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.business-category.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-suitcase"><span></span>
                    </i>
                    <span class="m-menu__link-text">Business Category</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.guide-language*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.guide-language.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-interface-7"><span></span>
                    </i>
                    <span class="m-menu__link-text">Language</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.product-tag*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.product-tag.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-list"><span></span>
                    </i>
                    <span class="m-menu__link-text">Product Tag</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.addon*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.addon.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-interface-7">
                        <span></span>
                    </i><span class="m-menu__link-text">Addon</span>
                </a>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{Request::is('back-office/insurance/*')?'m-menu__item--open':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-interface-7"></i><span
                            class="m-menu__link-text">Insurance</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span
                                    class="m-menu__link"><span
                                        class="m-menu__link-text">Insurance</span></span></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:insurance.list.index*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:insurance.list.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">List Insurance</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:insurance.data-customer.index*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:insurance.data-customer.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Data Customer</span></a></li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.association*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.association.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-users"><span></span></i>
                    <span class="m-menu__link-text">Association</span>
                </a>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:voucher-gomodo*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:voucher-gomodo.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-price-tag"><span></span>
                    </i>
                    <span class="m-menu__link-text">Promo General</span>
                </a>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{Request::is('back-office/payment/*')?'m-menu__item--open':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-earth-globe"></i><span
                            class="m-menu__link-text">Payment</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true"><span
                                    class="m-menu__link"><span
                                        class="m-menu__link-text">Payment</span></span></li>
                        <li class="m-menu__item " aria-haspopup="true"><a
                                    href="{{route('admin:master.category-payment.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Category Payment</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a
                                    href="{{route('admin:master.list-payment.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">List Payment</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a
                                    href="{{route('admin:master.company-payment.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Company Payment</span></a></li>
                        <li class="m-menu__item " aria-haspopup="true"><a
                                    href="{{route('admin:master.provider-manual-transfer.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Provider Manual Payment</span></a></li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.unit_name*')?'m-menu__item--active':''}}" aria-haspopup="true">
                <a href="{{route('admin:master.unit_name.index')}}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-interface-3"><span></span>
                    </i>
                    <span class="m-menu__link-text">Unit Name</span>
                </a>
            </li>

            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">Solusi Pemasaran</h4>
                <i class="m-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:premium.premium*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:premium.premium.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-interface-7"><span></span></i><span
                            class="m-menu__link-text">Premium</span></a></li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:premium.promo-code*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:premium.promo-code.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-price-tag"><span></span></i><span
                            class="m-menu__link-text">Promo Code</span></a></li>

            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">Provider</h4>
                <i class="m-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:providers*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:providers.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-interface-7"><span></span></i><span
                            class="m-menu__link-text">List</span></a></li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:product*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:product.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-bag"><span></span></i><span
                            class="m-menu__link-text">Product</span></a></li>

            {{-- <li class="m-menu__item {{Route::currentRouteNamed('admin:master.transaction*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:master.transaction.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-shopping-basket"><span></span></i><span
                            class="m-menu__link-text">Order</span></a></li> --}}
            
            <li class="m-menu__item  m-menu__item--submenu {{Route::currentRouteNamed('admin:master.transaction*')?'m-menu__item--open m-menu__item--expanded m-menu__item--active':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-shopping-basket"></i><span
                            class="m-menu__link-text">Order</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent " aria-haspopup="true"><span
                                    class="m-menu__link"><span
                                        class="m-menu__link-text">Order</span></span></li>
                        <li class="m-menu__item" aria-haspopup="true"><a
                                    href="{{route('admin:master.transaction.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Online Order</span></a></li>
                        <li class="m-menu__item" aria-haspopup="true"><a
                                    href="{{route('admin:master.transaction-manual.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Manual Order</span></a></li>
                    </ul>
                </div>
            </li>

            <li class="m-menu__item  m-menu__item--submenu {{Route::currentRouteNamed('admin:kyc.*')?'m-menu__item--open m-menu__item--expanded m-menu__item--active':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-interface-9"></i><span
                            class="m-menu__link-text">KYC</span><i class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent " aria-haspopup="true"><span
                                    class="m-menu__link"><span
                                        class="m-menu__link-text">KYC</span></span></li>
                        <li class="m-menu__item {{URL::getRequest()->getRequestUri() =='/back-office/kyc?status=need_approval'?'m-menu__item--active':''}} " aria-haspopup="true"><a
                                    href="{{route('admin:kyc.index',['status'=>'need_approval'])}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Need Approval</span></a></li>
                        <li class="m-menu__item {{URL::getRequest()->getRequestUri() =='/back-office/kyc'?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:kyc.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Approved</span></a></li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{Route::currentRouteNamed('admin:b2b.*')?'m-menu__item--open m-menu__item--expanded m-menu__item--active':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-paper-plane"></i><span
                            class="m-menu__link-text">B2B Blog</span><i
                            class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link"><span class="m-menu__link-text">Blog Post</span></span>
                        </li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:b2b.category*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:b2b.category.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Category</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:b2b.tag*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:b2b.tag.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Tags</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:b2b.post*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:b2b.post.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Blog Post</span></a></li>
{{--                        <li class="m-menu__item " aria-haspopup="true"><a--}}
{{--                                    href="{{route('admin:directory.press-releases.index')}}"--}}
{{--                                    class="m-menu__link "><i--}}
{{--                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span--}}
{{--                                        class="m-menu__link-text">Press Releases</span></a></li>--}}
                    </ul>
                </div>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:master.finance*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:master.finance.index')}}"
                class="m-menu__link "><i class="m-menu__link-icon flaticon-bag"><span></span></i><span
                class="m-menu__link-text">Finance</span></a></li>

            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">Directory</h4>
                <i class="m-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.popular.city*')?'m-menu__item--active':''}} " aria-haspopup="true"><a href="{{route('admin:directory.popular.city.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-interface-7"><span></span></i><span
                            class="m-menu__link-text">Popular City</span></a></li>

            <li class="m-menu__item  m-menu__item--submenu
            {{
                Route::currentRouteNamed('admin:directory.category*')||
                Route::currentRouteNamed('admin:directory.tag*')||
                Route::currentRouteNamed('admin:directory.post*')||
                Route::currentRouteNamed('admin:directory.press-releases*')?
                'm-menu__item--open m-menu__item--expanded m-menu__item--active':''
            }}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-paper-plane"></i><span
                            class="m-menu__link-text">Content Blog</span><i
                            class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link"><span class="m-menu__link-text">Blog Post</span></span>
                        </li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.category*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:directory.category.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Category</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.tag*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:directory.tag.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Tags</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.post*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:directory.post.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Blog Post</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.press-releases*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:directory.press-releases.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Press Releases</span></a></li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{Route::currentRouteNamed('admin:directory.career.*')||Route::currentRouteNamed('admin:directory.job-applicant*')?'m-menu__item--open m-menu__item--expanded m-menu__item--active':''}}" aria-haspopup="true" m-menu-submenu-toggle="hover">
                <a href="javascript:;" class="m-menu__link m-menu__toggle"><i
                            class="m-menu__link-icon flaticon-interface-9"></i><span
                            class="m-menu__link-text">Career</span><i
                            class="m-menu__ver-arrow la la-angle-right"></i></a>
                <div class="m-menu__submenu "><span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  m-menu__item--parent" aria-haspopup="true">
                            <span class="m-menu__link"><span class="m-menu__link-text">Career</span></span>
                        </li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.career*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:directory.career.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Career</span></a></li>
                        <li class="m-menu__item {{Route::currentRouteNamed('admin:directory.job-applicant*')?'m-menu__item--active':''}}" aria-haspopup="true"><a
                                    href="{{route('admin:directory.job-applicant.index')}}"
                                    class="m-menu__link "><i
                                        class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i><span
                                        class="m-menu__link-text">Applicants</span></a></li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">Blasting</h4>
                <i class="m-menu__section-icon flaticon-more-v2"></i>
            </li>

            <li class="m-menu__item " aria-haspopup="true"><a href="{{route('admin:updates.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-interface-7"><span></span></i><span
                            class="m-menu__link-text">Info & Updates</span></a></li>
            <li class="m-menu__section ">
                <h4 class="m-menu__section-text">Setting</h4>
                <i class="m-menu__section-icon flaticon-more-v2"></i>
            </li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:seting.restrict*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:setting.restrict.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-interface-7"><span></span></i><span
                            class="m-menu__link-text">Restrict Domain</span></a></li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:seo*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:seo.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-interface-7"><span></span></i><span
                            class="m-menu__link-text">SEO Page</span></a></li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:bot*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:bot.index')}}"
                                                                                                                                            class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-customer"><span></span></i><span
                            class="m-menu__link-text">BOT Menu</span></a></li>
            <li class="m-menu__item {{Route::currentRouteNamed('admin:setting.admin*')?'m-menu__item--active':''}}" aria-haspopup="true"><a href="{{route('admin:setting.admin.index')}}"
                                                              class="m-menu__link "><i
                            class="m-menu__link-icon flaticon-user-settings"><span></span></i><span
                            class="m-menu__link-text">Admin</span></a></li>
        </ul>
    </div>
</div>
