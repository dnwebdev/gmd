@extends('klhk.dashboard.company.base_layout')


@section('title', __('sidebar_provider.settings'))

@section('additionalStyle')
    <link href="{{ asset('klhk-asset/dest-operator/lib/css/chosen.min.css') }}" rel="stylesheet">
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet"
          media="screen,projection">
    <link href="{{ asset('klhk-asset/css/profile-inline-fix.css') }}" rel="stylesheet">
@endsection

@section('tab_breadcrumb')
@endsection

@section('indicator_mycompany')
    active
@endsection

@section('content')
    <!-- Page header -->
    <div data-template="main_content_header">
        <div class="page-header" style="margin-bottom: 0;">
            <div class="page-header-content header-elements-md-inline">
                <div class="page-title">
                    <h5>
                        {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('setting_provider.settings') }}
                        {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                    </h5>
                </div>

                <div class="header-elements py-0">
                    <div class="breadcrumb">
                        <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                            <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                        </a>
                        <span class="breadcrumb-item active">{{ trans('setting_provider.settings') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page header -->
    <!-- main content -->
    <div class="content pt-0" dashboard>

        <!-- Gamification -->
        <div data-template="gamification-modal">@include('klhk.dashboard.company.gamification-modal')</div>
        <!-- /gamification -->
        <!-- KYC-Gamification -->
        <div data-template="kyc-gamification">@include('klhk.dashboard.company.kyc-gamification')</div>
        <!-- /kyc-gamification -->
        <!-- Banner Sugestion -->
        <div data-template="banner-sugetion"></div>
        <!-- /banner Sugestion -->
        <div data-template="widget">

            <div class="step1">
                <form id="form_ajax_setting" method="POST" action="{{ Route('company.profile.update') }}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="widget card" id="operator-logo">
                                <div class="widget-header widget-collapse" id="header-operator-logo"
                                     data-toggle="collapse" data-target="#content-operator-logo">
                                    <h3>{{ trans('setting_provider.business_logo') }}</h3>
                                    {{-- <i class="float-right fa fa-chevron-down rotate text-white" id="content-operator-logo-chevron"></i> --}}
                                    <i class="chevron"></i>

                                </div>
                                <div class="widget-content collapse" id="content-operator-logo">
                                    <div class="widget-form row">
                                        <div class="col-lg-6 col-md-12 col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-radio-control custom-radio">
                                                    <input type="radio" id="custom-logo" name="operator-logo"
                                                           value="custom"
                                                           class="custom-control-input"
                                                            {{!in_array($company->logo,['dest-operator/img/logo1.png','dest-operator/img/logo2.png','dest-operator/img/logo3.png','dest-operator/img/logo4.png','dest-operator/img/logo5.png'])?'checked':null}}>
                                                    <label class="custom-control-label"
                                                           for="custom-logo">{{ trans('setting_provider.custom_logo') }}</label>
                                                    <div class="custom-block is-open">
                                                        <div class="custom-logo-block d-lg-flex align-items-end justify-content-center">
                                                            <div class="custom-logo-image position-relative text-center">
                                                                @if(in_array($company->logo,['dest-operator/img/logo1.png','dest-operator/img/logo2.png','dest-operator/img/logo3.png','dest-operator/img/logo4.png','dest-operator/img/logo5.png']))
                                                                    {{-- <input type="file" name="logo"
                                                                        id="input-file-now"
                                                                        class=" dropify" data-allowed-file-extensions="jpg jpeg png"
                                                                        data-disable-remove="true"> --}}
                                                                    <label class="custom-input-cropping-image">
                                                                        <input type="file" name="logo" class="cropping-image
                                                                custom-file-input"
                                                                               data-allowed-file-extensions="jpg jpeg png"
                                                                               data-disable-remove="true"
                                                                               data-id="0"
                                                                               data-ratio="1"
                                                                               data-array="false"
                                                                               data-type="logo"/>
                                                                        <i class="fa fa-cloud-upload"></i>
                                                                        <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                                    </label>
                                                                    <div class="result-0 file-result-ratio-one">

                                                                    </div>
                                                                    <div class="text-center">
                                                                        <div class="input-group mb-3 display-none group-input-image-0 input-group-cropping-image">
                                                                            <input type="text"
                                                                                   class="form-control label-data-name-file-0 overflow-hidden"
                                                                                   readonly>
                                                                            <div class="input-group-append">
                                                                                <button type="button"
                                                                                        class="btn btn-danger btn-remove-image-cropper remove-crop-image-0"
                                                                                        data-id="0">
                                                                                    {{ trans('product_provider.remove_image') }}
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    @if(count(explode('/', $company->logo)) > 1)
                                                                        <label class="custom-input-cropping-image">
                                                                            <input type="file" name="logo" class="cropping-image
                                                                        custom-file-input"
                                                                                   data-allowed-file-extensions="jpg jpeg png"
                                                                                   data-disable-remove="true"
                                                                                   data-id="0"
                                                                                   data-ratio="1"
                                                                                   data-array="false"
                                                                                   data-type="logo"/>
                                                                            <i class="fa fa-cloud-upload"></i>
                                                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                                        </label>
                                                                        <div class="result-0 file-result-ratio-one">
                                                                            <img src="{{ $company->logo ? asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo ) : '' }}"
                                                                                 alt="logo">
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <div class="input-group mb-3 {{ empty($company->logo) ? 'display-none' : '' }} group-input-image-0 input-group-cropping-image">
                                                                                <input type="text"
                                                                                       class="form-control label-data-name-file-0 overflow-hidden"
                                                                                       value="img-logo" readonly>
                                                                                <div class="input-group-append">
                                                                                    <button type="button"
                                                                                            class="btn btn-danger btn-remove-image-cropper remove-crop-image-0"
                                                                                            data-id="0">
                                                                                        {{ trans('product_provider.remove_image') }}
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <label class="custom-input-cropping-image">
                                                                            <input type="file" class="cropping-image
                                                                        custom-file-input" name="logo"
                                                                                   data-allowed-file-extensions="jpg jpeg png"
                                                                                   data-disable-remove="true"
                                                                                   data-id="0"
                                                                                   data-ratio="1"
                                                                                   data-array="false"
                                                                                   data-type="logo"/>
                                                                            <i class="fa fa-cloud-upload"></i>
                                                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                                        </label>
                                                                        <div class="result-0 file-result-ratio-one">
                                                                            <img src="{{ $company->logo ? asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo ) : '' }}"
                                                                                 alt="logo">
                                                                        </div>
                                                                        <div class="text-center">
                                                                            <div class="input-group mb-3 {{ empty($company->logo) ? 'display-none' : '' }} group-input-image-0 input-group-cropping-image">
                                                                                <input type="text"
                                                                                       class="form-control label-data-name-file-0 overflow-hidden"
                                                                                       value="img-logo" readonly>
                                                                                <div class="input-group-append">
                                                                                    <button type="button"
                                                                                            class="btn btn-danger btn-remove-image-cropper remove-crop-image-0"
                                                                                            data-id="0">
                                                                                        {{ trans('product_provider.remove_image') }}
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                                <input type="hidden" name="_token"
                                                                       value="{{csrf_token()}}">
                                                            </div>
                                                            <div class="modal fade modal-"></div>
                                                        </div>
                                                        <div class="custom-logo-helper">
                                                            <p>{{ trans('setting_provider.image_term1') }}</p>
                                                            {{-- <p>{{ trans('setting_provider.image_term2') }}</p> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-radio-control custom-radio">
                                                    <input type="radio" id="default-logo" name="operator-logo"
                                                           value="default"
                                                           class="custom-control-input"
                                                            {{in_array($company->logo,['dest-operator/img/logo1.png','dest-operator/img/logo2.png','dest-operator/img/logo3.png','dest-operator/img/logo4.png','dest-operator/img/logo5.png'])?'checked':null}}>
                                                    <label class="custom-control-label"
                                                           for="default-logo">{{ trans('setting_provider.default_logo') }}</label>
                                                    <div class="custom-block is-open">
                                                        <div class="custom-default-logo">
                                                            <label for="dlogo-1">
                                                                <input type="radio" id="dlogo-1"
                                                                       value="dest-operator/img/logo1.png"
                                                                       name="default-logo" {{$company->logo=='dest-operator/img/logo1.png'?'checked':null}}>
                                                                <img src="{{ asset('dest-operator/img/logo1.png') }}"
                                                                     alt="CAMPING">
                                                            </label>
                                                            <label for="dlogo-2">
                                                                <input type="radio" id="dlogo-2"
                                                                       value="dest-operator/img/logo2.png"
                                                                       name="default-logo" {{$company->logo=='dest-operator/img/logo2.png'?'checked':null}}>
                                                                <img src="{{ asset('dest-operator/img/logo2.png') }}"
                                                                     alt="DIVER">
                                                            </label>
                                                            <label for="dlogo-3">
                                                                <input type="radio" id="dlogo-3"
                                                                       value="dest-operator/img/logo3.png"
                                                                       name="default-logo" {{$company->logo=='dest-operator/img/logo3.png'?'checked':null}}>
                                                                <img src="{{ asset('dest-operator/img/logo3.png') }}"
                                                                     alt="SURFING">
                                                            </label>
                                                            <label for="dlogo-4">
                                                                <input type="radio" id="dlogo-4"
                                                                       value="dest-operator/img/logo4.png"
                                                                       name="default-logo" {{$company->logo=='dest-operator/img/logo4.png'?'checked':null}}>
                                                                <img src="{{ asset('dest-operator/img/logo4.png') }}"
                                                                     alt="WATERSPORT">
                                                            </label>
                                                            <label for="dlogo-5">
                                                                <input type="radio" id="dlogo-5"
                                                                       value="dest-operator/img/logo5.png"
                                                                       name="default-logo" {{$company->logo=='dest-operator/img/logo5.png'?'checked':null}}>
                                                                <img src="{{ asset('dest-operator/img/logo5.png') }}"
                                                                     alt="HIKING">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget card" id="banner">
                                <div class="widget-header widget-collapse" id="header-banner" data-toggle="collapse"
                                     data-target="#content-operator-banner">
                                    <h3>{{ trans('setting_provider.banner') }}</h3>
                                    {{-- <i class="float-right fa fa-chevron-down rotate text-white" id="content-operator-banner-chevron"></i> --}}
                                    <i class="chevron"></i>
                                </div>
                                <div class="widget-content collapse" id="content-operator-banner">
                                    <div class="widget-form row">
                                        <div class="col-lg-6 col-md-12 col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-radio-control custom-radio">
                                                    <input type="radio" id="custom-banner" name="operator-banner"
                                                           value="custom"
                                                           class="custom-control-input"
                                                            {{!in_array($company->banner,['dest-operator/img/banner1.jpg','dest-operator/img/banner2.jpg','dest-operator/img/banner3.jpg','dest-operator/img/banner4.jpg'])?'checked':null}}>
                                                    <label class="custom-control-label"
                                                           for="custom-banner">{{ trans('setting_provider.custom_banner') }}</label>
                                                    <div class="custom-block is-open">
                                                        <div class="custom-banner-holder position-relative text-center">
                                                            @if(in_array($company->banner,['dest-operator/img/banner1.jpg','dest-operator/img/banner2.jpg','dest-operator/img/banner3.jpg','dest-operator/img/banner4.jpg']))
                                                                <label class="custom-input-cropping-image">
                                                                    <input type="file" name="banner" name="banner"
                                                                           class="cropping-image custom-file-input"
                                                                           data-allowed-file-extensions="jpg jpeg png"
                                                                           data-id="1" data-ratio="1.78"
                                                                           data-type="banner" data-array="false">
                                                                    <i class="fa fa-cloud-upload"></i>
                                                                    <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                                </label>
                                                                <div class="result-1 file-result">
                                                                </div>
                                                                <div class="text-center">
                                                                    <div class="input-group mb-3 display-none group-input-image-1 input-group-cropping-image">
                                                                        <input type="text"
                                                                               class="form-control label-data-name-file-1 overflow-hidden"
                                                                               readonly>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                    class="btn btn-danger btn-remove-image-cropper remove-crop-image-1"
                                                                                    data-id="1">
                                                                                {{ trans('product_provider.remove_image') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <label class="custom-input-cropping-image">
                                                                    <input type="file" name="banner"
                                                                           class="cropping-image custom-file-input"
                                                                           data-allowed-file-extensions="jpg jpeg png"
                                                                           data-id="1" data-ratio="1.78"
                                                                           data-type="banner" data-array="false">
                                                                    <i class="fa fa-cloud-upload"></i>
                                                                    <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                                </label>
                                                                <div class="result-1 file-result">
                                                                    @php
                                                                        $count = count(explode('/', $company->banner));
                                                                    @endphp
                                                                    @if ($count > 1)
                                                                        <img src="{{ $company->banner ? asset(strpos($company->banner, 'dest-operator') !== false ? $company->banner : 'uploads/banners/'.$company->banner ) : '' }}"
                                                                             alt="">
                                                                    @else
                                                                        <img src="{{ $company->banner ? asset('uploads/banners/'.$company->banner) : '' }}"
                                                                             alt="Image">
                                                                    @endif
                                                                </div>
                                                                <div class="text-center">
                                                                    <div class="input-group mb-3 {{ empty($company->banner) ? 'display-none' : '' }} group-input-image-1 input-group-cropping-image">
                                                                        <input type="text"
                                                                               class="form-control label-data-name-file-1 overflow-hidden"
                                                                               value="image-banner" readonly>
                                                                        <div class="input-group-append">
                                                                            <button type="button"
                                                                                    class="btn btn-danger btn-remove-image-cropper remove-crop-image-1"
                                                                                    data-id="1">
                                                                                {{ trans('product_provider.remove_image') }}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                        </div>
                                                        <div class="custom-logo-block d-lg-flex align-items-end">
                                                            <div class="custom-logo-helper">
                                                                <p>{{ trans('setting_provider.image_term3') }}</p>
                                                                {{-- <p>{{ trans('setting_provider.image_term2') }}</p> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-6 col-12">
                                            <div class="form-group">
                                                <div class="custom-radio-control custom-radio">
                                                    <input type="radio" id="default-banner" name="operator-banner"
                                                           value="default"
                                                           class="custom-control-input"
                                                            {{in_array($company->banner,['dest-operator/img/banner1.jpg','dest-operator/img/banner2.jpg','dest-operator/img/banner3.jpg','dest-operator/img/banner4.jpg'])?'checked':null}}>
                                                    <label class="custom-control-label"
                                                           for="default-banner">{{ trans('setting_provider.default_banner') }}</label>
                                                    <div class="custom-block is-open">
                                                        <div class="custom-default-banner">
                                                            <label for="dbanner-1">
                                                                <input type="radio" id="dbanner-1"
                                                                       value="dest-operator/img/banner1.jpg"
                                                                       {{$company->banner =='dest-operator/img/banner1.jpg'?'checked':null}}  name="default-banner">
                                                                <img src="{{ asset('dest-operator/img/banner1.jpg') }}"
                                                                     alt="SURFING">
                                                            </label>
                                                            <label for="dbanner-2">
                                                                <input type="radio" id="dbanner-2"
                                                                       value="dest-operator/img/banner2.jpg"
                                                                       {{$company->banner =='dest-operator/img/banner2.jpg'?'checked':null}} name="default-banner">
                                                                <img src="{{ asset('dest-operator/img/banner2.jpg') }}"
                                                                     alt="SURFING">
                                                            </label>
                                                            <label for="dbanner-3">
                                                                <input type="radio" id="dbanner-3"
                                                                       value="dest-operator/img/banner3.jpg"
                                                                       {{$company->banner =='dest-operator/img/banner3.jpg'?'checked':null}} name="default-banner">
                                                                <img src="{{ asset('dest-operator/img/banner3.jpg') }}"
                                                                     alt="SURFING">
                                                            </label>
                                                            <label for="dbanner-4">
                                                                <input type="radio" id="dbanner-4"
                                                                       value="dest-operator/img/banner4.jpg"
                                                                       {{$company->banner =='dest-operator/img/banner4.jpg'?'checked':null}} name="default-banner">
                                                                <img src="{{ asset('dest-operator/img/banner4.jpg') }}"
                                                                     alt="SURFING">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget card" id="operator-settings">
                                <div class="widget-header widget-collapse" id="header-operator-settings"
                                     data-toggle="collapse" data-target="#content-operator-settings">
                                    <h3>{{ trans('setting_provider.business_information') }}</h3>
                                    {{-- <i class="float-right fa fa-chevron-down rotate text-white" id="content-operator-settings-chevron"></i> --}}
                                    <i class="chevron"></i>

                                </div>
                                <div class="widget-content collapse" id="content-operator-settings">
                                    <div class="widget-form">
                                        <div class="form-group">
                                            <label for="company_name">{{ trans('setting_provider.operator_name') }}</label>
                                            <input type="text"
                                                   placeholder="{{ trans('setting_provider.operator_name') }}"
                                                   class="form-control" name="company_name"
                                                   value="{{ $company->company_name }}" maxlength="100"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="domain_memoria">{{ trans('setting_provider.website_address') }}
                                                <a href="http://{{ $company->domain_memoria }}" class="ml-1"
                                                   target="_blank">{{ trans('setting_provider.view_website') }}</a></label>
                                            <input type="text" id="domain_memoria" name="domain_memoria"
                                                   class="form-control" value="{{ $company->domain_memoria }}"
                                                   readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="short_description" class="tooltips"
                                                   title="Maximum 150 Characters">{{ trans('setting_provider.short_description') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <textarea name="short_description"
                                                      placeholder="{{ trans('setting_provider.company_short_description') }}"
                                                      id="short_description"
                                                      rows="3"
                                                      class="form-control"
                                                      maxlength="150">{{ $company->short_description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="about" class="tooltips"
                                                   title="Maximum 300 Characters">{{ trans('setting_provider.about_your_business') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <textarea name="about" id="about"
                                                      placeholder="{{ trans('setting_provider.this_will_be') }}"
                                                      rows="6"
                                                      class="form-control itin_input">{{ $company->about }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="address_company" class="tooltips"
                                                   title="Maximum 300 Characters">{{ trans('setting_provider.address_company') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <input type="text" id="pac-input" placeholder="{{ trans('setting_provider.office_location') }}">
                                            <div id="map"></div>
                                            {!! Form::hidden('lat',$company->lat,['id'=>'lat']) !!}
                                            {!! Form::hidden('long',$company->long,['id'=>'long']) !!}
                                            {!! Form::hidden('google_place_id',$company->google_place_id,['id'=>'google_place_id']) !!}
                                        </div>
                                        <div class="form-group">
                                        <textarea name="address_company" id="address_company"
                                                  placeholder="{{ trans('setting_provider.this_will_be') }}"
                                                  rows="6"
                                                  class="form-control mt-3 itin_input_notoolbar">{{ $company->address_company }}</textarea>
                                        </div>
                                        <div class="form-group multiple">
                                            <label for="email_company" class="tooltips"
                                                   title="All notifications will be sent to this email">{{ trans('setting_provider.business_email') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-envelope"></span></span>
                                                <input type="email"
                                                       placeholder="{{ trans('setting_provider.input_your_email_address') }}"
                                                       class="form-control no-space" name="email_company"
                                                       id="email_company"
                                                       value="{{ $company->email_company }}" maxlength="100">
                                            </div>
                                        </div>
                                        <div class="form-group multiple">
                                            <label for="phone_company" class="tooltips"
                                                   title="Started with country code, example 6280123456789">{{ trans('setting_provider.whatsaap_enable_phone_number') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-phone"></span></span>
                                                <input name="phone_company"
                                                       placeholder="{{ trans('setting_provider.use_country') }}"
                                                       type="text" class="form-control number" name="phone_company"
                                                       id="phone_company" value="{{ $company->phone_company }}"
                                                       maxlength="15">
                                            </div>
                                        </div>
                                        <div class="form-group multiple">
                                            <label for="social">{{ trans('setting_provider.social_media') }}</label>
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-facebook"></span></span>
                                                <input type="text"
                                                       placeholder="{{ trans('setting_provider.input_your_username_only') }}"
                                                       class="form-control" name="facebook_company"
                                                       id="facebook_company" placeholder='facebook'
                                                       value="{{ $company->facebook_company }}" maxlength="50">
                                            </div>
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-instagram"></span></span>
                                                <span class="form-icon"
                                                      style="margin-left: 22px;"><span> | </span></span>
                                                <span class="form-icon"
                                                      style="margin-left: 41px;top: 7px;font-size: 1.1rem;"><span>@</span></span>
                                                <input type="text"
                                                       placeholder="{{ trans('setting_provider.input_your_username_only') }}"
                                                       class="form-control no-space" name="twitter_company"
                                                       id="twitter_company" placeholder='instagram'
                                                       value="{{ $company->twitter_company }}" maxlength="50"
                                                       style="padding-left: 5rem;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Hide Temporary --}}
                                {{-- <div class="widget card" id="featured-product" style="display:none">
                                    <div class="widget-header">
                                        <h3>Featured Product</h3>
                                    </div>
                                    <div class="widget-content">
                                        <div class="widget-form">
                                            <div class="form-group">
                                                <label for="featured1">Featured product slot 1</label>
                                                <select name="featured1" id="featured1" class="form-control">
                                                    <option value="1">Expendable Bromo</option>
                                                    <option value="2">Ubud Ville</option>
                                                    <option value="3">City Tour</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="featured2">Featured product slot 2</label>
                                                <select name="featured2" id="featured2" class="form-control">
                                                    <option value="2">Ubud Ville</option>
                                                    <option value="1">Expendable Bromo</option>
                                                    <option value="3">City Tour</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="featured3">Featured product slot 3</label>
                                                <select name="featured3" id="featured3" class="form-control">
                                                    <option value="3">City Tour</option>
                                                    <option value="1">Expendable Bromo</option>
                                                    <option value="2">Ubud Ville</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="widget card seo" id="seo">
                                <div class="widget-header widget-collapse" data-toggle="collapse"
                                     data-target="#content-seo" id="seo-header">
                                    <h3 class="tooltips"
                                        title="{{ trans('setting_provider.seo_tooltip') }}">SEO<span
                                                class="fa fa-info-circle"></span></h3>
                                    {{-- <i class="float-right fa fa-chevron-down rotate text-white" id="content-seo-chevron"></i> --}}
                                    <i class="chevron"></i>

                                </div>
                                <div class="widget-content collapse" id="content-seo">
                                    <div class="widget-form">
                                        <div class="form-group">
                                            <label for="title" class="tooltips"
                                                   title="{{ trans('setting_provider.site_title_tooltip') }}">{{ trans('setting_provider.site_title') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <input name="title" type="text"
                                                   value="{{ $company->title }}"
                                                   class="form-control"
                                                   placeholder="{{ trans('setting_provider.max_70') }}"
                                                   maxlength="70">
                                        </div>
                                        <div class="form-group">
                                            <label for="description" class="tooltips"
                                                   title="{{ trans('setting_provider.site_description_tooltip') }}">{{ trans('setting_provider.site_description') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            <textarea name="description" id="description" cols="30" rows="4"
                                                      class="form-control"
                                                      placeholder="{{ trans('setting_provider.max_150') }}"
                                                      maxlength="150">{{$company->description}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="keywords" class="tooltips"
                                                   title="{{ trans('setting_provider.site_keyword_tooltip') }}">{{ trans('setting_provider.site_keyword') }}
                                                <span class="fa fa-info-circle"></span></label>
                                            {{-- <textarea type="text" name="keywords" class="form-control"
                                                      maxlength="500">{{$company->keywords}}</textarea> --}}
                                            {{-- SEO Keyword Select2 Style --}}
                                            <select name="keywords[]" class="form-control select-multiple-comma-max-100"
                                                    multiple="multiple" id="keyword" style="width: 100%;">
                                                @if($company->keywords !=='' && $company->keywords !==null)
                                                    @foreach(explode(',',$company->keywords) as $keyword)
                                                        <option value="{{$keyword}}" selected>{{$keyword}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget card bank" id="bank">
                                <div class="widget-header widget-collapse" id="header-bank" data-toggle="collapse"
                                     data-target="#content-bank">
                                    <h3>{{ trans('bank_provider.bank_acount_details') }}</h3>
                                    {{-- <i class="float-right fa fa-chevron-down rotate text-white" id="content-bank-chevron"></i> --}}
                                    <i class="chevron"></i>

                                </div>
                                <div class="widget-content collapse" id="content-bank">
                                    <div class="widget-form">
                                        @if ($company->bank && $company->bank->checkRequest)
                                            <div class="alert alert-info text-center" role="alert">
                                                <strong>{{ trans('bank_provider.announcement') }}</strong><br>
                                                {{ trans('bank_provider.change_account') }}
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="bank">{{ trans('bank_provider.select_bank') }}</label>
                                            <select name="bank" class="form-control select2"
                                                    placeholder="Select Bank">
                                                @foreach (\App\Models\CompanyBank::$banks as $key => $value)
                                                    <option value="{{ $key }}" {{ $company->bank? $company->bank->bank == $key ? 'selected' : '':'' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="bank_account_name">{{ trans('bank_provider.account_name') }}</label>
                                            <input name="bank_account_name" type="text"
                                                   autocomplete="off"
                                                   value="{{ $company->bank?$company->bank->bank_account_name:'' }}"
                                                   class="form-control" maxlength="50">
                                        </div>
                                        <div class="form-group">
                                            <label for="bank_account_number">{{ trans('bank_provider.account_number') }}</label>
                                            <input name="bank_account_number" type="text"
                                                   autocomplete="off"
                                                   value="{{ $company->bank?$company->bank->bank_account_number:'' }}"
                                                   class="form-control number" id="account" maxlength="25">
                                        </div>
                                        <div class="form-group bank-document-container text-center">
                                            <label for="bank_account_document"
                                                   clas>{{ trans('bank_provider.bank_document') }}</label><br>
                                            {{-- <input name="bank_account_document"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify"
                                                id="input-file-now2"
                                                data-allowed-file-extensions="png jpg jpeg"
                                                @if($company->bank)
                                                data-default-file="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}"
                                                @endif
                                                data-max-file-size="2M"
                                                data-show-remove="false"> --}}
                                            <label class="custom-input-cropping-image">
                                                <input type="file" name="bank_account_document"
                                                       class="custom-file-input cropping-image"
                                                       data-allowed-file-extensions="jpg jpeg png"
                                                       {{-- @if($company->bank)
                                                       data-default-file="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}"
                                                       @endif --}}
                                                       data-array="false"
                                                       data-id="2"
                                                       data-type="bank_account_document"
                                                       data-ratio="1.78"/>
                                                <i class="fa fa-cloud-upload"></i>
                                                <span>{{ trans('premium.facebook.label.upload') }}</span>
                                            </label>
                                            <div class="result-2 file-result bank-image">
                                                @if (!empty($company->bank->bank_account_document))
                                                    <img src="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}"
                                                         alt="">
                                                @endif
                                            </div>
                                        </div>
                                    @if ($list_payment->status == 1)
                                        <div class="form-check form-check-switchery">
                                            <label class="form-check-label" id="foot-included">
                                                <label class="form-check-label" id="foot-included">
                                                    <input type="checkbox" class="form-check-input-switchery-primary"
                                                        data-fouc id="request-active" name="allow_bca" value="bca_manual"
                                                        {{  optional($company->transfer_manual)->status == 'approved' ? 'checked' : '' }}>
                                                    {{ trans('setting_provider.manual_transfer.title') }}
                                                </label>
                                        </div>
                                        <div class="request-bca {{  optional($company->transfer_manual)->status == 'approved' ? '' : 'display-none' }}">
                                            <div class="alert alert-info" role="alert">
                                                {{ trans('setting_provider.manual_transfer.info') }}
                                            </div>
    
                                            <div class="custom-control custom-checkbox custom-control-inline form-check mb-3 show-for-bca" style="display: none">
                                                <input id="use_data" name="use_data" type="checkbox" class="custom-control-input" />
                                                <label class="custom-control-label label-all-day" for="use_data">
                                                    {{ trans('setting_provider.use_saved_bank') }}
                                                </label>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label
                                                    for="name_rekening">{{ trans('setting_provider.manual_transfer.account_holder_name') }}</label>
                                                <input name="name_rekening" type="text" autocomplete="off"
                                                    value="{{ optional($company->transfer_manual)->status == 'approved' ? $company->transfer_manual->name_rekening: '' }}"
                                                    class="form-control" maxlength="50">
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    for="no_rekening">{{ trans('setting_provider.manual_transfer.destination_account_number') }}</label>
                                                <input name="no_rekening" type="text" autocomplete="off"
                                                    value="{{ optional($company->transfer_manual)->status == 'approved' ? $company->transfer_manual->no_rekening:'' }}"
                                                    class="form-control number" maxlength="25">
                                            </div>
                                            <div class="form-group bank-document-container text-center">
                                                <label
                                                    for="upload_document">{{ trans('bank_provider.bank_document') }}</label><br>
                                                <label class="custom-input-cropping-image hide-for-use-data">
                                                    <input type="file" name="upload_document"
                                                        class="custom-file-input cropping-image"
                                                        data-allowed-file-extensions="jpg jpeg png" data-array="false"
                                                        data-id="3" data-type="upload_document" data-ratio="1.78" />
                                                    <i class="fa fa-cloud-upload"></i>
                                                    <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                </label>
                                                <div class="result-3 file-result bank-image">
                                                    @if (!empty($company->transfer_manual->upload_document))
                                                        @if (optional($company->transfer_manual)->status == 'approved' && $company->bank)
                                                            <img src="{{ asset('uploads/bank_manual/'.$company->transfer_manual->upload_document) }}"
                                                            alt="" class="hide-for-use-data">
                                                        @else
                                                            <img src="" alt="">
                                                        @endif
                                                    @endif
                                                    <img src="{{ $company->bank?asset('uploads/bank_document/'.$company->bank->bank_account_document):'' }}"
                                                        alt="" class="show-for-use-data" style="display: none">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            </div>
                            <div class="widget card password" id="operator-settings">
                                <div class="widget-header widget-collapse" id="header-operator-settings-password"
                                     data-toggle="collapse" data-target="#content-password">
                                    <h3 class="tooltips"
                                        title="{{ trans('setting_provider.password_tooltip') }}">{{ trans('setting_provider.security_information') }}
                                        <span
                                                class="fa fa-info-circle"></span></h3>
                                    {{-- <i class="float-right fa fa-chevron-down rotate text-white" id="content-password-chevron"></i> --}}
                                    <i class="chevron"></i>

                                </div>
                                <div class="widget-content collapse" id="content-password">
                                    <div class="widget-form">
                                        <div class="form-group">
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-lock"></span></span>
                                                <input type="password"
                                                       placeholder="{{ trans('setting_provider.old_password') }}"
                                                       class="form-control" name="old_password" id="old_password" autocomplete="new-password">
                                                <span toggle="#old_password"
                                                      class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-lock"></span></span>
                                                <input type="password"
                                                       placeholder="{{ trans('setting_provider.new_password') }}"
                                                       class="form-control" name="password" id="password"
                                                       maxlength="16" autocomplete="new-password">
                                                <span toggle="#password"
                                                      class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-with-icon">
                                                <span class="form-icon"><span class="fa fa-lock"></span></span>
                                                <input type="password"
                                                       placeholder="{{ trans('setting_provider.new_password_confirmation') }}"
                                                       class="form-control" name="password_confirmation"
                                                       id="password_confirmation" maxlength="16" autocomplete="new-password">
                                                <span toggle="#password_confirmation"
                                                      class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard-cta float-right">
                        <button id="btn-submit" class="btn bg-green-klhk btn-cta step2" type="submit" name="action"
                                id="save"
                                onclick="onJSButtonclick()">{{ trans('setting_provider.submit') }}
                        </button>
                        <a class="btn bg-green-klhk btn-cta" href="http://{{ $company->domain_memoria }}"
                           target="_blank">{{ trans('setting_provider.preview') }}</a>
                    </div>
                    {{-- @include('klhk.dashboard.company.product.modal_cropping_image')  --}}
                </form>
            </div>
        </div>
    </div>
    {{-- Intro Js translate in form_ajax.js --}}
    <label id="intro_create_translate" data-translate="{{ trans('intro.create_btn') }}" style="display: none"></label>
    <label id="intro_setting_translate1" data-translate="{{ trans('intro.setting1') }}" style="display: none"></label>
    <label id="intro_setting_translate2" data-translate="{{ trans('intro.setting2') }}" style="display: none"></label>
    <label id="intro_setting_translate3" data-translate="{{ trans('intro.setting3') }}" style="display: none"></label>
@stop


@section('additionalScript')
    <!-- Plugin -->
    <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('materialize/js/plugins/tinymce/tinymce-charactercount.plugin.js') }}"></script>
    {{--    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
    <script src="{{ asset('js/jscolor.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script> --}}

    <!-- Custom  -->
    <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('klhk-asset/dest-operator/js/operator.js') }}"></script>
    {{--        <script async defer--}}
    {{--                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZUo9eiJlYJr8FLQBYmrbRh8Uzmdbwj50&libraries=places&callback=renderGoogleMap"></script>--}}
            <script src="{{asset('js/gmap.js')}}"></script>

    <script type="text/javascript">
        // Data -------------------------------------------------------------------------------------------

        window.$ = jQuery;
        // var canvasRatioOne = $('.canvas-cropping-image-ratio-one'),
        //     context = canvasRatioOne.get(0).getContext('2d'),
        //     // $resultRatioOne = $('.result-crop-ratio-one');


        // croppingImageRatioOne();

        // var croppedImageDataURL;
        // var id;
        // var canvas = $('.canvas-cropping-image'),
        //         context = canvas.get(0).getContext('2d'),
        //         $result = $('.result'),
        //         modalCropping = $('.modal-cropping');

        // Init -------------------------------------------------------------------------------------------

        $('#content-bank .select2').select2({
            placeholder: "Select Bank",
            allowClear: false,
            width: '100%'
        });

        bca_check()
        croperProductImage();

        jQuery('.select2multiple').select2({
            tags: true
        });

        tinymce.init({
            selector: '.itin_input',
            menubar: false,
            content_style: "p {font-size: 1rem; }",
            plugins: ['charactercount', 'paste'],
            paste_as_text: true,
            elementpath: false,
            max_chars: 300, // max. allowed chars
            setup: function (ed) {
                var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
                ed.on('keydown', function (e) {
                    if (allowedKeys.indexOf(e.keyCode) != -1) return true;
                    if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    return true;
                });
                ed.on('keyup', function (e) {
                    tinymce_updateCharCounter(this, tinymce_getContentLength());
                });
            },
            init_instance_callback: function () { // initialize counter div
                $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
                tinymce_updateCharCounter(this, tinymce_getContentLength());
            },
            paste_preprocess: function (plugin, args) {
                var editor = tinymce.get(tinymce.activeEditor.id);
                var len = editor.contentDocument.body.innerText.length;
                var OriginalString = args.content;
                var text = OriginalString.replace(/(<([^>]+)>)/ig, "");
                if (len + text.length > editor.settings.max_chars) {
                    alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
                    args.content = text.slice(0, editor.settings.max_chars);
                } else {
                    tinymce_updateCharCounter(editor, len + text.length);
                }
            }
        });

        tinymce.init({
            selector: '.itin_input_notoolbar',
            menubar: false,
            content_style: "p {font-size: 1rem; }",
            plugins: ['charactercount', 'paste'],
            paste_as_text: true,
            toolbar: false,
            height: 70,
            elementpath: false,
            max_chars: 300, // max. allowed chars
            setup: function (ed) {
                var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
                ed.on('keydown', function (e) {
                    if (allowedKeys.indexOf(e.keyCode) != -1) return true;
                    if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                    return true;
                });
                ed.on('keyup', function (e) {
                    tinymce_updateCharCounter(this, tinymce_getContentLength());
                });
            },
            init_instance_callback: function () { // initialize counter div
                $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
                tinymce_updateCharCounter(this, tinymce_getContentLength());
            },
            paste_preprocess: function (plugin, args) {
                var editor = tinymce.get(tinymce.activeEditor.id);
                var len = editor.contentDocument.body.innerText.length;
                var OriginalString = args.content;
                var text = OriginalString.replace(/(<([^>]+)>)/ig, "");
                if (len + text.length > editor.settings.max_chars) {
                    alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
                    args.content = text.slice(0, editor.settings.max_chars);
                } else {
                    tinymce_updateCharCounter(editor, len + text.length);
                }
            }
        });

        jQuery('.dropify').dropify({
            messages: {
                'default': "{{ trans('kyc.default1') }} <br><span style='font-size:.8rem'>{{ trans('kyc.default2') }}</span>",
                'error': "{{ trans('kyc.error') }}"
            }
        });

        form_ajax(jQuery('#form_ajax_setting'), function (e) {
            if (e.status == 200) {
                if ($('input[name="allow_bca"]').prop("checked") == true) {
                    swal({
                        title: '{{ trans('setting_provider.manual_transfer.title_alert') }}',
                        html: 
                            '{{ trans('setting_provider.manual_transfer.account_holder_name') }}' + $(document).find('input[name="name_rekening"]').val() + '<br>' +
                            '{{ trans('setting_provider.manual_transfer.destination_account_number') }}' + $(document).find('input[name="no_rekening"]').val(),
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#4caf50',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ trans('setting_provider.manual_transfer.yes') }}',
                        cancelButtonText: '{{ trans('setting_provider.manual_transfer.no') }}'
                    }).then((result) => {
                        if (result.value) {
                            swal({
                                title: "Success",
                                html: e.message,
                                type: "success",
                            }).then(function () {
                                location.reload()
                            });
                        }
                    })
                } else{
                    swal({
                        title: "Success",
                        html: e.message,
                        type: "success",
                    }).then(function () {
                        location.reload()
                    });
                }

            } else {
                swal({
                    title: "Oops...",
                    text: e.message,
                    type: "error",
                }).then(function () {
                });
            }
        });

        jQuery(".chosen-select").chosen();

        // Event -------------------------------------------------------------------------------------------

        document.addEventListener('DOMContentLoaded', function() {
            switcheryInit();
        });

        $(document).on('click', '#use_data', function() {
            use_data_check($(this).is(':checked'), 'use data')
        })

        jQuery(document).on('click', '#custom-logo', function () {
            jQuery('input[name=default-logo]').attr('checked', false).prop('checked', false).parent();
            jQuery('input[name=default-logo]').parent().addClass('not-selected');
        });
        jQuery(document).on('click', '#custom-banner', function () {
            jQuery('input[name=default-banner]').attr('checked', false).prop('checked', false);
            jQuery('input[name=default-banner]').parent().addClass('not-selected');
        });
        jQuery(document).on('change', 'input[name=banner]', function () {
            jQuery('#custom-banner').trigger('click')
        });
        jQuery(document).on('change', 'input[name=logo]', function () {
            jQuery('#custom-logo').trigger('click')
        });
        jQuery(document).on('click', '#default-logo', function () {
            jQuery('name[logo]').val('')
        });
        jQuery(document).on('change click', '#default-logo, input[name="default-logo"]', function () {
            default_logo_check()
        });
        function default_logo_check() {
            if ($('#default-logo').is(':checked')) {
                $('.custom-logo-image').find('.btn-remove-image-cropper').trigger('click')
            }
        }
        jQuery(document).on('click', '#default-banner', function () {
            jQuery('name[banner]').val('')
        });
        jQuery(document).on('change', 'input[name=operator-logo]', function () {
            let t = jQuery(this);
            if (t.val() === 'default') {
                t.parent().find('input[name=default-logo]').eq(0).attr('checked', true).prop('checked', true).parent().removeClass('not-selected');
            }
        });
        jQuery(document).on('change', 'input[name=operator-banner]', function () {
            let t = jQuery(this);
            if (t.val() === 'default') {
                t.parent().find('input[name=default-banner]').eq(0).attr('checked', true).prop('checked', true).parent().removeClass('not-selected');
            }
        });

        // Toggle password
        jQuery(".toggle-password").click(function () {
            jQuery(this).toggleClass("fa-eye fa-eye-slash");
            var input = jQuery(jQuery(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        $(document).on('keyup', '.no-space', function () {
            $(this).val($(this).val().replace(/\s/g, ''))
        })

        $(document).on('change', '#request-active', function() {
            if ($(this).is(':checked')) {
                $(document).find('.request-bca').show();
            }else{
                $(document).find('.request-bca').hide();
            }
        });

        // $(document).ready(function () {
        //     // Intro JS Next Page
        //     if (RegExp('multipage', 'gi').test(window.location.search)) {
        //         StartSettingIntro()
        //     }
        // })

        // $(document).on('ready', function (){
        //     renderGoogleMap('map')
        // });

        // $('.widget-collapse').click(function(){
        //     if($(this).next().hasClass('show')){
        //         $(this).find('.rotate').removeClass('down')
        //     } else {
        //         $(this).find('.rotate').addClass('down')
        //     }
        // })

        // Functions -------------------------------------------------------------------------------------------
        
        function switcheryInit() {
            // Initialize multiple switches
            var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery-primary'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html, { color: '#2196F3' });
            });
        }
        
        function bca_check() {
            if ($('[name=bank').val() === 'BCA') {
                $('.show-for-bca').show()

                let condition = $('[name=bank_account_number]').val() === $('[name=no_rekening]').val() && $('[name=bank_account_name]').val() === $('[name=name_rekening]').val();
                if (condition) {
                    $(document).find('input[name=use_data]').prop('checked', true)
                } else {
                    $(document).find('input[name=use_data]').prop('checked', false)
                }
                use_data_check(condition)
            } else {
                $('.show-for-bca').hide()
            }
        }

        function use_data_check(condition, el) {
            let name_val = $('[name=bank_account_name]').val(),
                number_val = $('[name=bank_account_number]').val(),
                new_name = $('[name=name_rekening]'),
                new_number = $('[name=no_rekening]'),
                hide_use_data = $('.hide-for-use-data'),
                show_use_data = $('.show-for-use-data');

            if (condition) {
                if (el === 'use data') {
                    new_name.val(name_val)
                    new_number.val(number_val)
                }
                new_name.attr('readonly', true)
                new_number.attr('readonly', true)
                hide_use_data.hide()
                show_use_data.show()
            } else {
                if (el === 'use data') {
                    new_name.val('')
                    new_number.val('')
                }
                new_name.attr('readonly', false)
                new_number.attr('readonly', false)
                hide_use_data.show()
                show_use_data.hide()
            }
        }

        function tinymce_updateCharCounter(el, len) {
            // $('#' + el.id).prev().find('.char_count').text(len + '/' + el.settings.max_chars);
            $('#' + el.id).prev().find('.char_count').text('{!! trans('dashboard_provider.max_char') !!} ' + el.settings.max_chars);
        }

        function tinymce_getContentLength() {
            return tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
        }

        // Intro JS
        function checkJSNetConnection() {
            var x = navigator.onLine;
            return x;
        }

        function onJSButtonclick() {
            if (checkJSNetConnection() == true) {
                tinyMCE.triggerSave(true, true);
            } else {
                alert("{!! trans('setting_provider.connection') !!}");
            }
        }
    </script>
@endsection
@push('script')
    <script >
        let intervalGoogle = setInterval(function () {
            console.log('waiting for render ... ')
            if (google!== undefined){
                renderGoogleMap();
                clearInterval(intervalGoogle);
            }
        },100)
    </script>
@endpush
