@extends('dashboard.company.base_layout')
@section('title', 'Create New Product')
@section('additionalStyle')
    <!--dropify-->
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet"
          media="screen,projection">
    <link href="{{ asset('dest-operator/lib/css/chosen.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dest-operator/css/index.css') }}" rel="stylesheet">
{{--    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/jquery.timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}">
    <link href="{{ url('css/component-custom-switch.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dest-operator/css/product_create.css') }}" rel="stylesheet">
    <link href="{{ asset('css/product_company.css') }}" rel="stylesheet">

@endsection
@section('breadcrumb')
@endsection
@section('indicator_inventory')
    active
@stop
@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('product_provider.new_product') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <a href="{{ Route('company.product.index') }}" class="breadcrumb-item">
                        {{ trans('sidebar_provider.product') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('product_provider.product_detail') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->

<!-- main content -->
<div class="content pt-0" dashboard> 
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- KYC-Gamification -->
    <div data-template="kyc-gamification">@include('dashboard.company.kyc-gamification')</div>
    <!-- /kyc-gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        <div class="product">
            <div class="step3">
                <form id="form_ajax" method="POST" action="{{ Route('company.product.store') }}" novalidate autocomplete="off">
                    <input type="hidden" name="_method" id="_method" value="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="min_order" name="min_order" value="1"/>
                    <!-- <div class="dashboard-cta">
                    <button id="btn-submit" class="btn bg-green-klhk btn-cta" type="submit" name="action"  id="save" onclick="tinyMCE.triggerSave(true,true);" >Submit</button>
                    </div> -->
                    <!-- Product Detail -->
                    <div class="widget card" id="product-detail">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.product_detail') }}</h3>
                        </div>
                        <div class="widget-content">
                            <div class="widget-form">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-5">
                                        <div class="form-group">
                                            <label for="product_name">{{ trans('product_provider.product_name') }}
                                                <span class="text-danger">*</span></label>
                                            <input name="product_name" type="text" class="form-control"
                                                maxlength="100"
                                                data-validation="{{trans('product_provider.product_name_required')}}">
                                            <small class="error display-none" id="product-name-error"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="category" class="tooltips"
                                                title="{{ trans('product_provider.category_for_product') }}">{{ trans('product_provider.product_category') }}
                                                <span class="fa fa-info-circle fs-14"></span></label>
                                            <select name="product_category[]" class="form-control multiple-select2-max3" 
                                                    multiple="multiple" id="category">
                                                @foreach($product_category as $row)
                                                    <option value="{{ $row->id }}">{{ app()->getLocale()==='id'?$row->name_ind:$row->name }}</option>
                                                @endforeach
                                            </select>
                                        <label class="error d-none" id="category-long">{{ trans('product_provider.max_category_3') }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label for="country">{{ trans('product_provider.country') }}</label>
                                            <span class="text-danger">*</span>
                                            <select name="country" id="country_search" class="select2-product form-control"
                                                    data-validation="{{ trans('product_provider.country_required') }}"
                                                    required>
                                                <option selected disabled>{{ trans('product_provider.select_country') }}</option>
                                                @foreach (App\Models\Country::all() as $item)
                                                    <option value="{{ $item->id_country }}">{{ $item->country_name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="error display-none" id="country-error"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="state">{{ trans('product_provider.state') }}</label>
                                            <span class="text-danger">*</span>
                                            <select name="state" id="state_search" class="select2-product form-control"
                                                    data-validation="{{ trans('product_provider.state_required') }}"
                                                    required>
                                                <option selected
                                                        disabled>{{ trans('product_provider.select_state') }}</option>
                                            </select>
                                            <small class="error display-none" id="state-error"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="city">{{ trans('product_provider.city') }}</label>
                                            <span class="text-danger">*</span>
                                            <select name="city" id="city_search" class="select2-product form-control"
                                                    data-validation="{{ trans('product_provider.city_required') }}"
                                                    required>
                                                <option selected disabled>{{ trans('product_provider.select_city') }}</option>
                                            </select>
                                            <small class="error display-none" id="city-error"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="brief_description" class="tooltips"
                                                title="{{ trans('product_provider.short_description') }}">{{ trans('product_provider.brief_description') }}
                                                <span class="text-danger">*</span><span
                                                        class="fa fa-info-circle fs-14"></span></label>
                                            <input type="text" class="form-control" name="brief_description"
                                                maxlength="100"
                                                placeholder="{{ trans('product_provider.max_char_100') }}"
                                                data-validation="{{trans('product_provider.brief_required')}}">
                                            <small class="error display-none" id="brief-error"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="about" class="tooltips"
                                                title="{{ trans('product_provider.explain') }}">{{ trans('product_provider.about_this_product') }}
                                                <span class="text-danger">*</span><span
                                                        class="fa fa-info-circle fs-14"></span></label>
                                            <textarea id="long_description" class="form-control itinerary-input" rows="7"
                                                    name="long_description" length="300"
                                                    data-validation="{{trans('product_provider.long_description_required')}}"></textarea>
                                            <small class="error display-none" id="about-this-error"></small>
                                        </div>

                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-7">
                                        <div class="form-group">
                                            <label for="pmeeting">{{ trans('product_provider.activity_location') }}</label><br/>
                                            <input id="pac-input" class="col-sm-9" type="text"
                                                placeholder="{{ trans('product_provider.search_activity') }}">
                                        </div>
                                        <div id="mapGoogle"></div>
                                        <div class="form-group">
                                            <label for="guide_language">{{ trans('product_provider.guided_in') }}</label>
                                            <select class="form-control  multiple-select2-max5" name="guide_language[]"
                                                    multiple>
                                                @foreach(\App\Models\Language::all() as $item)
                                                    <option value="{{$item->id_language}}">{{$item->language_name}}</option>
{{--                                                        <option value="{{$item->id_language}}">{{$item->language_name}}</option>--}}
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group select-icon-dropdown">
                                            <label for="category">{{ trans('product_provider.type') }}</label>
                                            <select name="product_type" id="product_type" class="form-control">
                                                @foreach($product_type as $row)
                                                    <option value="{{ $row->id_tipe_product }}">{{ $row->product_type_name }}</option>
                                                @endforeach
                                            </select>
                                            <i class="fa fa-chevron-down"></i>
                                            <input type="hidden" name="long" id="long" value="">
                                            <input type="hidden" name="lat" id="lat" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Availbility -->
                    <div class="widget card" id="order-guest">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.product_availability') }}</h3>
                        </div>
                        <div class="widget-content">

                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="form-group  select-icon-dropdown">
                                        <label for="availability">{{ trans('product_provider.select_time') }} <span class="text-danger">*</span></label>
                                        <select name="availability" id="availability" class="form-control">
                                            <option value="1"
                                                    selected>{{ trans('product_provider.always_available') }}</option>
                                            <option value="0">{{ trans('product_provider.fixed_dates') }}</option>
                                        </select>
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-20" id="day_fix">
                                    <div class="form-group">
                                        <label for="availability">{{ trans('product_provider.select_day') }} <span class="text-danger">*</span></label>
                                        <div class="row days-group">
                                            <div class="col">
                                                
                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input all day" id="all.0" name="all[0]" onclick="toggle(this)" value="1">
                                                    <label class="custom-control-label label-all-day" for="all.0">{{ trans('product_provider.allday') }}</label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input sun day" 
                                                        id="sun.0" name="sun[0]" 
                                                        onclick="unCheck()" 
                                                        value="1" data-day="0">
                                                    <label class="custom-control-label label-all-day" for="sun.0">
                                                        {{ trans('product_provider.sun') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input mon day" 
                                                        id="mon.0" name="mon[0]"
                                                        onclick="unCheck()"
                                                        value="1" data-day="1">
                                                    <label class="custom-control-label label-all-day" for="mon.0">
                                                        {{ trans('product_provider.mon') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input tue day" 
                                                        id="tue.0" name="tue[0]"
                                                        onclick="unCheck()"
                                                        value="1" data-day="2"/>
                                                    <label class="custom-control-label label-all-day" for="tue.0">
                                                        {{ trans('product_provider.tue') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input wed day" 
                                                        id="wed.0" name="wed[0]"
                                                        onclick="unCheck()"
                                                        value="1" data-day="3"/>
                                                    <label class="custom-control-label label-all-day" for="wed.0">
                                                        {{ trans('product_provider.wed') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input thu day" 
                                                        id="thu.0" name="thu[0]"
                                                        onclick="unCheck()"
                                                        value="1" data-day="4"/>
                                                    <label class="custom-control-label label-all-day" for="thu.0">
                                                        {{ trans('product_provider.thu') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input fri day" 
                                                        id="fri.0" name="fri[0]"
                                                        onclick="unCheck()"
                                                        value="1" data-day="5"/>
                                                    <label class="custom-control-label label-all-day" for="fri.0">
                                                        {{ trans('product_provider.fri') }}
                                                    </label>
                                                </div>

                                                <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                    <input type="checkbox" class="custom-control-input sat day" 
                                                        id="sat.0" name="sat[0]"
                                                        onclick="unCheck()"
                                                        value="1" data-day="6"/>
                                                    <label class="custom-control-label label-all-day" for="sat.0">
                                                        {{ trans('product_provider.sat') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <small id="day-alert" class="error"
                                            data-validationMessage="{{ trans('product.day_validation') }}"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row listdate" id="dates"
                                data-delete="{{ trans('product_provider.remove_date') }}">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-12"><label
                                                    class="mt-3 mt-lg-0">{{ trans('product_provider.active_period') }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-field start_label">
                                                <label for="start_date"
                                                    id="tgl">{{ trans('product_provider.start_date') }} <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control date-picker"
                                                    name="start_date[0]"
                                                    placeholder="{{ trans('product_provider.start_date_placeholder') }}"
                                                    required autocomplete="off"
                                                    {{-- value="{{ \Carbon\Carbon::now()->addDays(1)->format('m/d/Y') }}" --}}
                                                    value=""
                                                    data-validation="{{trans('product_provider.start_date_required')}}"
                                                    readonly>
                                                <small class="error display-none" id="error_start_date"></small>
                                                {{-- <input type="text" name="start_date[0]" data-large-mode="true" data-large-default="true" id="start_date.0" class="datedrop start_date form-control" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018"/> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-6 end_label">
                                            <div class="input-field ">
                                                <label for="end_date">{{ trans('product_provider.end_date') }}</label>
                                                <input type="text" readonly class="form-control date-picker"
                                                    name="end_date[0]"
                                                    placeholder="{{ trans('product_provider.end_date_placeholder') }}"
                                                    required="required"
                                                    autocomplete="off"
                                                    {{-- value="{{ \Carbon\Carbon::now()->addDays(2)->format('m/d/Y') }}" --}}
                                                    value=""
                                                    data-validation="{{trans('product_provider.end_date_required')}}">
                                                <small class="error display-none" id="error_end_date"></small>
                                                {{-- <input type="text" name="end_date[0]" data-large-mode="true" data-large-default="true" id="end_date.0" class="datedrop end_date form-control" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018"/> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-12"><label
                                                    class="mt-3 mt-lg-0">{{ trans('product_provider.operational') }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group select-icon-dropdown">
                                                <label for="start_time">{{ trans('product_provider.start_time') }}</label>
                                                <input type="text" name="start_time[0]" id="start_time.0"
                                                    class="timepicker number start_time form-control"
                                                    value="07:00" maxlength="5"
                                                    required/>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group select-icon-dropdown">
                                                <label for="end_time">{{ trans('product_provider.end_time') }}</label>
                                                <input type="text" name="end_time[0]" id="end_time.0"
                                                    class="timepicker number end_time form-control" maxlength="5"
                                                    required/>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 empty_space display-none">
                                </div>

                                <!-- <div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
                                </div> -->
                            {{--<div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">--}}
                            {{--<button type="button" class="btn-success btn-sm btn-add add_date" id="add_date" style="margin-top:-10px; " >{{ trans('product_provider.add_date') }}</button>--}}
                            {{--</div>--}}
                            <!-- </div> -->
                                <hr class="full-hr mt-30">
                            </div>
                        </div>
                    </div>

                    <div class="widget card" id="duration_activity">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.duration_activity') }}</h3>
                        </div>
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="form-group">
                                        <label for="duration">{{ trans('product_provider.low_duration') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control max-hours" name="duration"
                                            data-validation="{{trans('product_provider.duration_required')}}">
                                        <small class="error display-none" id="min-duration-activity-error"></small>
                                        <label id="max_hours" class="error"
                                            data-translate="{{ trans('product_provider.max_hours') }}"><label>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                    <div class="form-group select-icon-dropdown">
                                        <label for="duration_type">{{ trans('product_provider.duration') }}</label>
                                        <select class="form-control" name="duration_type">
                                            @foreach($duration as $key => $row)
                                                <option value="{{$key}}">{{$row}}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            <hr class="full-hr">

                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                    <div class="form-group">
                                        <label for="minimum_notice" class="tooltips"
                                            title="{{ trans('product_provider.confirmation_deadline') }}">{{ trans('product_provider.min_notice') }}
                                            <span class="text-danger">*</span><span
                                                    class="fa fa-info-circle fs-14"></span></label>
                                        <input type="number" class="form-control max-3" id="minimum_notice" value=0
                                            name="minimum_notice" min="0"
                                            data-validation="{{trans('product_provider.min_notice_required')}}"/>
                                        <small class="error display-none" id="min-notice-error"></small>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3 display-none">
                                    <div class="form-group">
                                        <label for="booking_confirmation">Booking Confirmation</label>
                                        <select id="booking_confirmation" name="booking_confirmation"
                                                class="form-control" disabled hidden>
                                            <option value="0">Manually</option>
                                            <option value="1" selected>Automatically</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                    <div class="form-group select-icon-dropdown">
                                        <label for="status">{{ trans('product_provider.product_status') }}</label>
                                        <select id="status" name="status" class="form-control">
                                            <option value="1">{{ trans('product_provider.active') }}</option>
                                            <option value="0">{{ trans('product_provider.not_active') }}</option>
                                        </select>
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                    <div class="form-group multiple-form select-icon-dropdown">
                                        <label for="publish">{{ trans('product_provider.publish_product') }}</label>
                                        <select id="publish" name="publish" class="form-control">
                                            <option value="1">{{ trans('product_provider.yes') }}</option>
                                            <option value="0">{{ trans('product_provider.no') }}</option>
                                        </select>
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            <hr class="full-hr">
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group multiple-form">
                                        <label for="important_notes">{{ trans('product_provider.important_notes') }}</label>
                                        <textarea class="form-control itinerary-input" id="important_notes"
                                                name="important_notes"
                                                length="200"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Pricing -->
                    <div class="widget card product-pricing" id="product-pricing" data-valdiation="{{ trans('product_provider.product_pricing_validation') }}">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.product_pricing') }}</h3>
                        </div>
                        <div class="widget-content">
                            <div class="row display-none">
                                <div class="col-sm-12 col-md-12 col-lg-6 ">
                                    <div class="form-group">
                                        <label for="currency">{{ trans('product_provider.currency') }}</label>
                                        <select id="currency" name="currency" class="form-control">
                                            @foreach($list_currency as $key=>$row)
                                                <option value="{{ $key }}">{{ $row }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-6">
                                    <div class="form-group multiple-form">
                                        <label for="advertised_price">{{ trans('product_provider.advertised_price') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="advertised_price"
                                            name="advertised_price" value="0"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="row">
                                        <div class="col-lg-4 ">
                                            <div class="form-group select-icon-dropdown">
                                                <label for="">{{ trans('product_provider.payment_method') }} <span
                                                            class="text-danger">*</span></label>
                                                <select name="booking_confirmation" id="" class="form-control">
                                                    <option value="0">{{ trans('product_provider.cash') }}</option>
                                                    <option value="1" selected>Online</option>
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="row">
                                        <div class="col-lg-6 col-xl-4">
                                            <div class="form-group d-flex flex-column h-87">
                                                <label for="max_people" class="mb-auto"
                                                        {{-- title="{{ trans('product_provider.minimum_pax') }}" --}}
                                                        >
                                                        {{ trans('product_provider.quota') }} 
                                                        {{-- <label class="dynamic_pricing"></label> --}}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" min="1" class="form-control max-5" id="max_people"
                                                        name="max_people"
                                                        data-validation="{{trans('product_provider.max_people_required')}}" 
                                                        data-validation-min="{{trans('product_provider.max_people_else')}}"/>
                                                <small id="alert-max-people" class="error"></small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xl-4">
                                            <div class="form-group d-flex flex-column h-87 select-icon-dropdown">
                                                <label for="quota_type" class="mb-auto"
                                                        {{-- title="{{ trans('product_provider.maximum_pax') }}" --}}
                                                        >
                                                        {{ trans('product_provider.quota_type.label') }} 
                                                        {{-- <label class="dynamic_pricing"></label> --}}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select name="quota_type" class="form-control" id="quota_type" required>
                                                    @foreach (trans('product_provider.quota_type.values') as $key => $value)
                                                    <option value="{{ $key }}" {{ $loop->index == 0 ? 'selected' : '' }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="row">
                                        <div class="col-lg-6 col-xl-4">
                                            <div class="form-group d-flex flex-column h-87">
                                                <label for="min_people" class="mb-1 mb-auto"
                                                        title="{{ trans('product_provider.minimum_pax') }}">
                                                        {{ trans('product_provider.min') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control max-5" id="min_people"
                                                        name="min_people"
                                                        value="1" min="1"
                                                        data-validation="{{trans('product_provider.min_people_required')}}"
                                                        data-validation-min="{{trans('product_provider.min_people_else')}}"/>
                                                {{-- <small class="error display-none" id="min-people-error"></small> --}}
                                                <small id="alert-min-people" class="error"></small>
                                            </div>
                                        </div>
        
                                        <div class="col-lg-6 col-xl-4">
                                            <div class="form-group d-flex flex-column h-87">
                                                <label for="max_order" class="mb-1 mb-auto"
                                                        title="{{ trans('product_provider.maximum_pax') }}">
                                                        {{ trans('product_provider.max') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" class="form-control max-5" id="max_order"
                                                        name="max_order"
                                                        min="1"
                                                        data-validation="{{trans('product_provider.max_people_required')}}"
                                                        data-validation-max="{{trans('product_provider.max_people_else')}}"/>
                                                {{-- <small class="error display-none" id="max-people-error"></small> --}}
                                                <small id="alert-max-order" class="error"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <hr class="full-hr"> --}}

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="price_type" class="tooltips tooltipstered active d-block">{{ trans('product_provider.price_type') }}</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input mt-0 mb-1" name="price_type" type="radio" value="" id="fixed_price">
                                            <label class="form-check-label tooltips" for="fixed_price" title="{{ trans('product_provider.fixed_price_tooltip') }}">
                                                {{ trans('product_provider.fixed_price') }}
                                                <span class="fa fa-info-circle fs-14"></span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input mt-0 mb-1" name="price_type" type="radio" value="" id="price_tier">
                                            <label class="form-check-label tooltips" for="price_tier" title="{{ trans('product_provider.group_price_tooltip') }}">
                                                {{ trans('product_provider.group_price') }}
                                                <span class="fa fa-info-circle fs-14"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row pricelist" id="pricing"
                                data-pricetype="{{ trans('product_provider.unit_price') }}"
                                data-delete="{{ trans('product_provider.delete_price') }}">

                                <div class="col-lg-4 clone-display-control">
                                    <div class="form-group display select-icon-dropdown">
                                        <label for="pricetype">{{ trans('product_provider.unit_price') }}</label>
                                        {{-- <select name="price_type[]" id="price_type.0" class="pricetype form-control">
                                        <option value="1">Person</option>
                                        </select> --}}
                                        <select name="unit_name_id[]" class="form-control unit_name_id" id="pricing"
                                                required>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="fa fa-chevron-down"></i>
                                    </div>
                                </div>

                                <div class="col-lg-4 price-type-d-none">
                                    <div class="form-group">
                                        <label for="from" class="mb-1"
                                            title="{{ trans('product_provider.pax_from_tooltip') }}">{{ trans('product_provider.pax_from') }} <label class="dynamic_pricing"></label>
                                            {{-- <span class="fa fa-info-circle fs-14"></span> --}}
                                        </label>
                                        <span class="text-danger">*</span>
                                        <div class="price_from_label">{{ trans('product_provider.pax_from_label') }}</div>
                                        <div class="warning_label d-none"><i class="fa fa-exclamation-triangle"></i></div>
                                        <input type="number" id="price_from.0"
                                            class="form-control totalpeople price-from max-5"
                                            name="price_from[]" min="1" value="1"/>
                                        <small id="alert-price-from" 
                                            class="error"
                                            data-no-empty="{{ trans('product_provider.no_empty_input') }}"
                                            data-more-than-before="{{ trans('product_provider.more_than_before') }}"
                                            data-more-than-max="{{ trans('product_provider.more_than_max') }}"
                                            data-error-above="{{ trans('product_provider.error_above') }}">
                                        </small>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 col-lg-6 col-xl-2 d-none">
                                    <div class="form-group">
                                        <label for="until" class="tooltips mb-1"
                                            title="{{ trans('product_provider.pax_until_tooltip') }}">{{ trans('product_provider.pax_until') }}
                                            <span class="fa fa-info-circle fs-14"></span> <span
                                                    class="text-danger">*</span></label>
                                        <input type="number" id="price_until.0"
                                            class="form-control totalpeople price-until max-5"
                                            name="price_until[]" min="1"/>
                                        <small id="alert-price-until" class="error"></small>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="price.0">
                                            <span class="price-label" data-price-fix="{{ trans('product_provider.price') }}" data-price-group="{{ trans('product_provider.price_group') }}"></span>
                                            <label class="dynamic_pricing"></label>
                                            <span class="text-danger">*</span>
                                        </label> 
                                        <div class="warning_label d-none"><i class="fa fa-exclamation-triangle"></i></div>
                                        <input name="price[]" min="1" id="price.0"
                                                class="priceamount right-align form-control new_price number format-money" 
                                                data-error="false"
                                                type="text" required maxlength="10"
                                                data-validation="{{trans('product_provider.price_required')}}">
                                        <input name="price_type[]" type="hidden" value="1"
                                                class="form-control price_type">
                                        <small id="alert-price-amount" 
                                            class="error" 
                                            data-validation-no-zero="{{trans('product_provider.price_no_empty')}}"
                                            data-min-100="{{trans('product_provider.min_100')}}"
                                            data-ideal="{{trans('product_provider.ideal_tier')}}"
                                            data-error-above="{{trans('product_provider.error_above')}}">
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-12 col-lg-1 mb-2 delete-container">
                                    
                                </div>
                            </div>
                            <div class="box-clone"></div>
                            <div class="row">
                                <div class="col-lg-11">
                                    <button type="button" class="btn btn-success btn-sm btn-add add_price mt-4 float-right"><i class="fa fa-plus"></i>{{ trans('product_provider.add_price') }}</button>
                                </div>
                            </div>
                            {{--                                <hr class="full-hr">--}}
                            {{--                                <div class="row">--}}
                            {{--                                    <div class="col-sm-12 col-md-12 col-lg-4">--}}
                            {{--                                        <div class="form-group">--}}
                            {{--                                            <label for="discount_name" class="tooltips"--}}
                            {{--                                                   title="{{ trans('product_provider.discount_name_tooltip') }}">{{ trans('product_provider.discount_name') }}--}}
                            {{--                                                <span class="fa fa-info-circle fs-14"></span></label>--}}
                            {{--                                            <input type="text" class="form-control" id="discount_name"--}}
                            {{--                                                   name="discount_name" maxlength="100"/>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-sm-12 col-md-12 col-lg-4">--}}
                            {{--                                        <div class="form-group">--}}
                            {{--                                            <label for="discount_amount_type">{{ trans('product_provider.discount_amount_type') }}</label>--}}
                            {{--                                            <select id="discount_amount_type" name="discount_amount_type"--}}
                            {{--                                                    class="form-control">--}}
                            {{--                                                <option value="">{{ trans('product_provider.no_discount') }}</option>--}}
                            {{--                                                <option value="0">{{ trans('product_provider.fixed_amount') }}</option>--}}
                            {{--                                                <option value="1">{{ trans('product_provider.percentage') }}(%)--}}
                            {{--                                                </option>--}}
                            {{--                                            </select>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                    <div class="col-sm-12 col-md-12 col-lg-4">--}}
                            {{--                                        <div class="form-group">--}}
                            {{--                                            <label for="discount_amount">{{ trans('product_provider.discount_amount') }}</label>--}}
                            {{--                                            <div class="discount_amount_label"></div>--}}
                            {{--                                            <input type="text" class="form-control number format-money"--}}
                            {{--                                                   id="discount_amount"--}}
                            {{--                                                   name="discount_amount" min="0"--}}
                            {{--                                                   oninput="validity.valid||(value='');" maxlength="10" disabled/>--}}
                            {{--                                        </div>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                        </div>
                    </div>

                    <!-- custom information -->
                    <div class="widget card" id="custom-input">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.customer_information') }}</h3>
                        </div>
                        <div class="widget-content">
                            <div class="row custom-input-clone" data-custom-index="0">
                                <div class="col-sm-12 mb-3">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group select-icon-dropdown">
                                                <label for="custom-type-0">{{ trans('product_provider.custom_type') }}</label>
                                                <select name="custom_type[0]" class="form-control custom-type">
                                                    @foreach (\App\Models\CustomSchema::$types as $key => $value)
                                                        <option value="{{ $key }}">{{ trans('product_provider.custom_types.'.$key) }}</option>
                                                    @endforeach>
                                                </select>
                                                <i class="fa fa-chevron-down"></i>
                                            </div>

                                        </div>
                                        <div class="col-sm-4">
                                            <label for="custom-type" class="tooltips"
                                                title="{{ trans('product_provider.custom_required_by_tooltip') }}">{{ trans('product_provider.custom_required_by') }}
                                                <span class="fa fa-info-circle fs-14"></span></label><br/>
                                            @foreach (\App\Models\CustomSchema::$fill_types as $key => $value)
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input custom-fill-type"
                                                            type="radio" name="custom_fill_type[0]"
                                                            {{ $key == 'customer' ? 'checked' : '' }} value="{{ $key }}">
                                                        {{ trans('product_provider.custom_fill_types.'.$key) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button"
                                                    class="btn btn-primary btn-sm btn-add add_custom_input mt-40 d-block small">
                                                {{ trans('product_provider.custom_add_form') }}
                                            </button>
                                            <button type="button"
                                                    class="btn btn-danger btn-sm btn-add remove_custom_input mt-36 small display-none">
                                                {{ trans('product_provider.custom_remove_form') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 p-20 bg-white p-3 border mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="d-block">
                                                <span class="tooltips"
                                                    title="{{ trans('product_provider.custom_label_tooltip') }}">
                                                    {{ trans('product_provider.custom_label') }}
                                                    <i class="fa fa-info-circle fs-14"></i>
                                                </span>
                                                <input type="text" class="form-control w-100 custom-label"
                                                    name="custom_label[0]"
                                                    placeholder="{{ trans('product_provider.custom_label_placeholder')  }}"/>
                                            </label>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="d-block">
                                                <span class="tooltips"
                                                    title="{{ trans('product_provider.custom_description_tooltip') }}">
                                                    {{ trans('product_provider.custom_description') }}
                                                    <i class="fa fa-info-circle fs-14"></i>
                                                </span>
                                                <input type="text" class="form-control w-100 custom-description"
                                                    name="custom_description[0]"/>
                                            </label>
                                        </div>
                                        <div class="col-12 custom-values" style="display: none">
                                            <div class="row">
                                                <div class="col-md-3 custom-values-clone">
                                                    <label>
                                                        <span class="custom-desc custom-desc-choices"
                                                            style="display: none">{{ trans('product_provider.custom_option_name') }}</span>
                                                        <span class="custom-desc custom-desc-checkbox"
                                                            style="display: none;">{{ trans('product_provider.custom_checklist_name') }}</span>
                                                        <span class="custom-desc custom-desc-dropdown"
                                                            style="display: none;">{{ trans('product_provider.custom_dropdown_name') }}</span>
                                                        <div class="input-group">
                                                            <input class="form-control form-control-sm custom-value-input"
                                                                type="text" name="custom_values[0][]"/>
                                                            <div class="input-group-append custom-value-show-remove d-none">
                                                                <button class="btn btn-danger btn-sm remove-custom-values"
                                                                        type="button">X
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                                <div class="col-md-2 align-self-center">
                                                    <a href="javascript:void(0);" class="add-custom-values">
                                                        +
                                                        <span class="custom-desc custom-desc-choices">{{ trans('product_provider.custom_add_option') }}</span>
                                                        <span class="custom-desc custom-desc-checkbox">{{ trans('product_provider.custom_add_checklist') }}</span>
                                                        <span class="custom-desc custom-desc-dropdown">{{ trans('product_provider.custom_add_dropdown') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 text-muted">
                                            @foreach (collect(trans('product_provider.custom_descriptions'))->except(['document', 'photo']) as $key => $doc)
                                                <span class="custom-desc custom-desc-{{ $key }}" style="display: {{ $loop->index == 0 ? 'block' : 'none' }}">{{ $doc }}</span>
                                            @endforeach
                                            <div class="form-group custom-desc custom-desc-document"
                                                style="display: none;">
                                                <label for="exampleFormControlFile1">{{ trans('product_provider.custom_descriptions.document') }}</label>
                                                <input type="file" class="form-control-file" disabled>
                                            </div>
                                            <div class="custom-desc custom-desc-photo" style="display: none;">
                                                <input type="file" id="input-file-now" class="dropify" disabled/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end custom information -->

                    <div class="widget card" id="add-on">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.add') }}</h3>
                        </div>
                        <div class="widget-content">
                            <div class="row added-on">
                                <div class="col-sm-12 col-md-6 mt-1">
                                    {{-- <div class="display-flex">
                                        <label>{{ trans('product_provider.price_home') }}</label>
                                        <input type="hidden" id="addon3" name="addon3" value="1">
                                        <label class="switch-rounded">
                                            <input type="checkbox" id="addon1" name="addon1" value="0">
                                            <span class="slider"></span>
                                        </label>
                                    </div> --}}
                                    <div class="form-check form-check-switchery">
                                        <input type="hidden" id="addon3" name="addon3" value="1">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input-switchery-primary" data-fouc id="addon1" name="addon1" value="0">
                                            {{ trans('product_provider.price_home') }}
                                        </label>
                                    </div>
                                    <br>
                                    {{-- <div class="display-flex">
                                        <label for="food-included">{{ trans('product_provider.price_eat') }}</label>
                                        <input type="hidden" id="addon4" name="addon4" value="1">
                                        <label class="switch-rounded" id="foot-included">
                                            <input type="checkbox" id="addon2" name="addon2" value="0">
                                            <span class="slider"></span>
                                        </label>
                                    </div> --}}
                                    <div class="form-check form-check-switchery">
                                        <input type="hidden" id="addon4" name="addon4" value="1">
                                        <label class="form-check-label" id="foot-included">
                                            <input type="checkbox" class="form-check-input-switchery-primary" data-fouc id="addon2" name="addon2" value="0">
                                            {{ trans('product_provider.price_eat') }}
                                        </label>
                                    </div>
                                    <br>
                                    {{-- <div class="display-flex">
                                        <label>{{ trans('product_provider.prices_include_pick_up') }}</label>
                                        <input type="hidden" id="addon7" name="addon7" value="1">
                                        <label class="switch-rounded">
                                            <input type="checkbox" id="addon5" name="addon5" value="0">
                                            <span class="slider"></span>
                                        </label>
                                    </div> --}}
                                    <div class="form-check form-check-switchery">
                                        <input type="hidden" id="addon7" name="addon7" value="1">
                                        <label class="form-check-label" id="foot-included">
                                            <input type="checkbox" class="form-check-input-switchery-primary" data-fouc id="addon5" name="addon5" value="0">
                                            {{ trans('product_provider.prices_include_pick_up') }}
                                        </label>
                                    </div>
                                    <br>
                                    {{-- <div class="display-flex">
                                        <label>{{ trans('product_provider.prices_include_pick_off') }}</label>
                                        <input type="hidden" id="addon8" name="addon8" value="1">
                                        <label class="switch-rounded">
                                            <input type="checkbox" id="addon6" name="addon6" value="0">
                                            <span class="slider"></span>
                                        </label>
                                    </div> --}}
                                    <div class="form-check form-check-switchery">
                                        <input type="hidden" id="addon8" name="addon8" value="1">
                                        <label class="form-check-label" id="foot-included">
                                            <input type="checkbox" class="form-check-input-switchery-primary" data-fouc id="addon6" name="addon6" value="0">
                                            {{ trans('product_provider.prices_include_pick_off') }}
                                        </label>
                                    </div>

                                    <div class="form-group mt-3 ml-1">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="show_exclusion" name="show_exclusion" value="1">
                                            <label class="custom-control-label" for="show_exclusion">{{ trans('product_provider.show_exclusion') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 ml-1">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="vat" name="vat" value="1">
                                            <label class="custom-control-label" for="vat">@lang('product_provider.add_vat')</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label for="discount_name" class="tooltips"
                                                title="{{ trans('product_provider.discount_name_tooltip') }}">{{ trans('product_provider.discount_name') }}
                                                <span class="fa fa-info-circle fs-14"></span></label>
                                            <input type="text" class="form-control" id="discount_name"
                                                name="discount_name" maxlength="100"/>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group select-icon-dropdown">
                                            <label for="discount_amount_type">{{ trans('product_provider.discount_amount_type') }}</label>
                                            <select id="discount_amount_type" name="discount_amount_type"
                                                    class="form-control">
                                                <option value="">{{ trans('product_provider.no_discount') }}</option>
                                                <option value="0">{{ trans('product_provider.fixed_amount') }}</option>
                                                <option value="1">{{ trans('product_provider.percentage') }}(%)
                                                </option>
                                            </select>
                                            <i class="fa fa-chevron-down"></i>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="discount_amount">{{ trans('product_provider.discount_amount') }}</label>
                                            <div class="discount_amount_label"></div>
                                            <input type="text" class="form-control number format-money"
                                                id="discount_amount"
                                                name="discount_amount" min="0"
                                                oninput="validity.valid||(value='');" maxlength="10" disabled/>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @if($insurances->count()>0)
                    <!-- Asuransi -->
                        <div class="widget card">
                            <div class="widget-header">
                                <h3>Asuransi</h3>
                            </div>
                            <div class="widget-content">
                                @foreach($insurances as $insurance)
                                    <div class="row">

                                        <div class="col-md-4">
                                            <img src="{{$insurance->insurance_logo_url}}" alt="" class="img-fluid w-100">
                                        </div>
                                        <div class="col-md-8">
                                            <h3>{{$insurance->insurance_name}}</h3>
                                            <p>{{$insurance->insurance_description}}</p>
                                            <div class="form-check form-check-switchery">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input-switchery-primary" data-fouc
                                                           id="{{$insurance->insurance_slug}}" name="insurances[{{$insurance->id}}]" value="1">
                                                    {{__('product_provider.insurance_activate')}}
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- End Asuransi -->
                    @endif
                    <!-- Images -->
                    <div class="widget card" id="images">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.images') }}</h3>
                        </div>
                        <div class="widget-content">
                            <div class="widget-form">
                                <div class="custom-image-gallery">
                                    <div class="widget-image input-field col-sm-12 col-md-12 col-lg-6 col-xl-3 text-center border-product">
                                        <label class="custom-input-cropping-image">
                                            <input type="file" id="input-file-now" name="images[0]" class="cropping-image custom-file-input" data-allowed-file-extensions="jpg jpeg png"  accept="image/*" data-default-file="" data-max-file-size="2M"
                                            data-id="0" aria-describedby="image-name-0" data-type="images" data-array="true"/>
                                            <i class="fa fa-cloud-upload"></i>
                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                        </label>
                                        <div class="result-0 file-result">
                                                
                                        </div>
                                        <div class="text-center">
                                            <div class="input-group mb-3 display-none group-input-image-0 input-group-cropping-image">
                                                <input type="text" class="form-control label-data-name-file-0 overflow-hidden" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-0" data-id="0">
                                                        {{ trans('product_provider.remove_image') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-image input-field col-sm-12 col-md-12 col-lg-6 col-xl-3 text-center border-product">
                                        <label class="custom-input-cropping-image">
                                            <input type="file" id="input-file-now" name="images[1]" class="cropping-image custom-file-input" data-allowed-file-extensions="jpg jpeg png"  accept="image/*" data-default-file="" data-max-file-size="2M"
                                            data-id="1" aria-describedby="image-name-1" data-type="images" data-array="true"/>
                                            <i class="fa fa-cloud-upload"></i>
                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                        </label>
                                        <div class="result-1 file-result">
                                                
                                        </div>
                                        <div class="text-center">
                                            <div class="input-group mb-3 display-none group-input-image-1 input-group-cropping-image">
                                                <input type="text" class="form-control label-data-name-file-1 overflow-hidden" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-1" data-id="1">
                                                        {{ trans('product_provider.remove_image') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-image input-field col-sm-12 col-md-12 col-lg-6 col-xl-3 text-center border-product">
                                        <label class="custom-input-cropping-image">
                                            <input type="file" id="input-file-now" name="images[2]" class="cropping-image custom-file-input" data-allowed-file-extensions="jpg jpeg png"  accept="image/*" data-default-file="" data-max-file-size="2M"
                                            data-id="2" aria-describedby="image-name-2" data-type="images" data-array="true"/>
                                            <i class="fa fa-cloud-upload"></i>
                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                        </label>
                                        <div class="result-2 file-result">
                                            
                                        </div>
                                        <div class="text-center">
                                            <div class="input-group mb-3 display-none group-input-image-2 input-group-cropping-image">
                                                <input type="text" class="form-control label-data-name-file-2 overflow-hidden" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-2" data-id="2">
                                                        {{ trans('product_provider.remove_image') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="widget-image input-field col-sm-12 col-md-12 col-lg-6 col-xl-3 text-center border-product">
                                        <label class="custom-input-cropping-image">
                                            <input type="file" id="input-file-now" name="images[3]" class="cropping-image custom-file-input" data-allowed-file-extensions="jpg jpeg png"  accept="image/*" data-default-file="" data-max-file-size="2M"
                                            data-id="3" aria-describedby="image-name-3" data-type="images" data-array="true"/>
                                            <i class="fa fa-cloud-upload"></i>
                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                        </label>
                                        <div class="result-3 file-result">
                                            
                                        </div>
                                        <div class="text-center">
                                            <div class="input-group mb-3 display-none group-input-image-3 input-group-cropping-image">
                                                <input type="text" class="form-control label-data-name-file-3 overflow-hidden" readonly>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-3" data-id="3">
                                                        {{ trans('product_provider.remove_image') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <p>{{ trans('setting_provider.image_term2') }}</p> --}}
                            </div>
                        </div>
                    </div>
                    {{-- @include('klhk.dashboard.company.product.modal_cropping_image') --}}
                    <!-- Itinerary -->
                    <div class="widget card" id="itinerary_form">
                        <div class="widget-header">
                            <h3>{{ trans('product_provider.itinerary') }}</h3>
                            {{--                                <div class="widget-tools">--}}
                            {{--                ,                     <a id="add_day" class="btn btn-small">{{ trans('product_provider.add_day') }}</a>--}}
                            {{--                                </div>--}}
                        </div>
                        <div class="widget-content">
                            <div class="itinerary_box box_day clone-tiny-mce" id="itinerarylist"
                                data-translate="{{trans('product_provider.day')}}"
                                data-add-button="{{ trans('product_provider.add_day') }}">
                                <div class="form-group box_itin " id="box_itin.0">
                                    <label id="day-0" class="itin_day">{{ trans('product_provider.day') }} 1</label>
                                    <textarea id="itinerary_0" class="form-control itinerary-input" rows="4"
                                            name="itinerary[]" length="300"></textarea>
                                    {{--                                        <button type="button" class="btn-success btn-sm btn-add display-none"--}}
                                    {{--                                                id="add_days">{{ trans('product_provider.add_day') }}</button>--}}
                                </div>
                            </div>
                            <div id="container_day"></div>
                            <div id="button-add-delete">
                                <button class="btn btn-success" id="add_day"
                                        type="button">{{ trans('product_provider.add_day') }}</button>
                                <button class="btn btn-danger" id="remove-day"
                                        type="button">{{ trans('product_provider.delete_day') }}</button>
                            </div>

                        </div>
                    </div>
                    <div class="display-none">
                        <input type="hidden" name="permalink" type="text">
                    </div>
                    <div class="row">
                        <div class="dashboard-cta col text-right">
                            <button id="btn-submit" class="btn btn-primary btn-cta step4"
                                    type="button" name="action" data-submit="{{trans('product_provider.submit')}}"
                                    id="save" onclick="tinyMCE.triggerSave(true,true);"
                                    data-submit="{{ trans('product.success') }}">{{ trans('product_provider.submit') }}</button>
                        </div>
                    </div>
                    @if (\App\Models\Ota::where('ota_status', true)->exists())
                        <button id="btn-show-ota" type="button" data-toggle="modal" data-target="#ota-modal" class="d-none"
                                data-url="{{ route('company.product.update_ota') }}">modal ota
                        </button>
                        @include('dashboard.company.product.ota_modal')
                        @endif
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Intro Translate in check_pack.js --}}
<label id="intro_create_translate1" data-translate="{{ trans('intro.create1') }}" style="display: none"></label>
<label id="intro_create_translate2" data-translate="{{ trans('intro.setting2') }}"
        style="display: none"></label>
<label id="intro_create_translate3" data-translate="{{ trans('intro.setting3') }}"
        style="display: none"></label>
{{-- </div> --}}
@endsection
@section('additionalScript')
    <!-- Plugin -->
    <script type="text/javascript">
        jQuery(document).on('keypress', 'input#pac-input', function (e) {
            if (e.keyCode === 13) {
                return false;
            }
        });
        var marker;
        var map;
        var longitude = 0;
        var latitude = 0;

        function initMap() {
            map = new google.maps.Map(document.getElementById('mapGoogle'), {
                zoom: 13,
                center: {lat: latitude, lng: longitude}
            });

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: {lat: latitude, lng: longitude}
            });

            marker.addListener('click', handleEvent);
            marker.addListener('drag', handleEvent);
            marker.addListener('dragend', handleEvent);
            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });


            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0)
                    return;

                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {

                    if (!place.geometry)
                        return;

                    if (place.geometry.viewport)
                        bounds.union(place.geometry.viewport);
                    else
                        bounds.extend(place.geometry.location);

                });

                map.fitBounds(bounds);
                placeMarker(map.center);
                setLongLat();

            });

            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
                handleEvent(event)
                // setLongLat();
            });

            function placeMarker(location) {

                marker.setPosition(location);
                //map.setCenter(location);
                // setLongLat();
            }

            setLongLat();
        }
        function handleEvent(event) {
            document.getElementById('lat').value = event.latLng.lat();
            document.getElementById('long').value = event.latLng.lng();
        }
        function setLongLat() {
            document.getElementById('long').value = map.getCenter().lng();
            document.getElementById('lat').value = map.getCenter().lat();
        }

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        function changeToMap(el){
            var request = {
                query: el.find('option:selected').text(),
                fields: ['name', 'geometry'],
            };

            var service = new google.maps.places.PlacesService(map);

            service.findPlaceFromQuery(request, function(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    setLongLat();
                }
            });
        }
        $(document).on('change','select[name=city]', function () {
            changeToMap($(this))
        });
    </script>
    {{-- <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script> --}}
{{--    <script async defer--}}
{{--            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZUo9eiJlYJr8FLQBYmrbRh8Uzmdbwj50&callback=initMap&libraries=places"></script>--}}
    <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('materialize/js/plugins/tinymce/tinymce-charactercount.plugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dest-operator/lib/js/jquery.tablesorter.min.js') }}"></script>
    <script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
{{--    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
    <!-- Custom  -->
    <script type="text/javascript" src="{{ asset('js/check_pax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/product_company.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dest-operator/js/operator.js') }}"></script>

    <script>
        window.$ = jQuery;
        var croppedImageDataURL;
        var id;
        var canvas = $('.canvas-cropping-image'),
            context = canvas.get(0).getContext('2d'),
            $result = $('.result'),
            modalCropping = $('.modal-cropping');
        croperProductImage();
        function tinymceConfig(selector, max_chars = 1000) {
            return {
                selector: selector,
                menubar: false,
                content_style: "p {font-size: 1rem; }",
                plugins: ['charactercount', 'paste'],
                paste_as_text: true,
                elementpath: false,
                max_chars: max_chars, // max. allowed chars
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
            }
        }

        tinymce.init(tinymceConfig('.itin_input'));
        tinymce.init(tinymceConfig('.itinerary-input', 2000));

        function tinymce_updateCharCounter(el, len) {
            // $('#' + el.id).prev().find('.char_count').text(len + '/' + el.settings.max_chars); <-- Original
            $('#' + el.id).prev().find('.char_count').text('{!! trans('dashboard_provider.max_char') !!} ' + el.settings.max_chars);
        }

        function tinymce_getContentLength() {
            return tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
        }

        // $('.multiple-select2').chosen()
    </script>
    <script type="text/javascript">
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = (month) + "/" + (day) + '/' + now.getFullYear();
        var enddate = (month) + "/" + (day) + '/' + '2025';

        jQuery('#availability').change(function () {
            var dateVal = jQuery('#availability').find(':selected').val();
            if (dateVal == "0") {
                jQuery('.add_date').hide();
                jQuery('.remove_date').hide();
                jQuery('.start_date').val(today);
                jQuery('.remove_date').click();
                jQuery('.end_date').val(enddate);
                jQuery('.end_label').hide();
                jQuery('.empty_space').show();
                jQuery("#day_fix").hide();
                jQuery('.day').attr('checked', true).prop('checked', true);
                jQuery('#day-alert').html('');
                jQuery('.start_label').parent().removeClass('col-md-6').addClass('col-12');
            }
            if (dateVal == "1") {
                jQuery('.listdate').show();
                jQuery('.start_label').show();
                jQuery('.end_label').show();
                jQuery('.add_date').show();
                jQuery('.remove_date').show();
                jQuery('.start_date').val(today);
                jQuery('.empty_space').hide();
                jQuery('.end_date').val(today);
                jQuery("#day_fix").show();
                jQuery('.start_label').parent().removeClass('col-12').addClass('col-md-6');
            }
        });

        jQuery('#addon1').change(
            function () {
                if (jQuery(this).is(':checked')) {
                    jQuery(this).val('1');
                    jQuery('#addon3').val('0');
                } else {
                    jQuery(this).val('0');
                    jQuery('#addon3').val('1');
                }
            });

        jQuery('#addon2').change(
            function () {
                if (jQuery(this).is(':checked')) {
                    jQuery(this).val('1');
                    jQuery('#addon4').val('0');
                } else {
                    jQuery(this).val('0');
                    jQuery('#addon4').val('1');
                }
            });

        jQuery('#addon5').change(
            function () {
                if (jQuery(this).is(':checked')) {
                    jQuery(this).val('1');
                    jQuery('#addon7').val('0');
                } else {
                    jQuery(this).val('0');
                    jQuery('#addon7').val('1');
                }
            });

        jQuery('#addon7').change(
            function () {
                if (jQuery(this).is(':checked')) {
                    jQuery(this).val('1');
                    jQuery('#addon5').val('0');
                } else {
                    jQuery(this).val('0');
                    jQuery('#addon5').val('1');
                }
            });

        jQuery('#addon6').change(
            function () {
                if (jQuery(this).is(':checked')) {
                    jQuery(this).val('1');
                    jQuery('#addon8').val('0');
                } else {
                    jQuery(this).val('0');
                    jQuery('#addon8').val('1');
                }
            });

        window.jQuery = window.$ = jQuery;

        function toggle(source) {
            checkboxes = document.getElementsByClassName('day');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }

        function unCheck() {
            checkboxes = document.getElementsByClassName('day');
            document.getElementById('all.0').checked = true;
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                if (checkboxes[i].checked == false) {
                    document.getElementById('all.0').checked = false;
                }
            }
        }

        function dayCount() {
            var disable_day = [];
            $('.day').each(function (i, e) {
                if (i != 0) {
                    if (!$(e).prop('checked')) {
                        disable_day.push($(e).attr('data-day'));
                    }
                }
            });
            // $('.date-picker').datepicker('destroy').datepicker({
            //     format: 'mm/dd/yyyy',
            //     autoclose: true,
            //     todayHighlight: false,
            //     startDate: '+1',
            //     daysOfWeekDisabled: disable_day
            // })
            $('.date-picker').datepicker('destroy').datepicker({
                startDate: '+1',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: false,
                daysOfWeekDisabled: disable_day,
                @if(app()->getLocale() == 'id')
                    language: 'id'
                @else
                    language: 'en'
                @endif
            });
        }
        // Timepicker initialization
        $('input.timepicker').timepicker({
            timeFormat: 'HH:mm',
            scrollbar: true,
            startTime: '07:00',
            dynamic: false
        });

        // Show Hide Itinerary by Activity
        $(document).ready(function () {
            // Chosen
            $('.multiple-select2-max5').select2({
                "language": {
                    "noResults": function(){
                        return "Tidak ada hasil";
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                maximumSelectionLength: 5
            });

            $('.multiple-select2-max3').select2({
                "language": {
                    "noResults": function(){
                        return "Tidak ada hasil";
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                maximumSelectionLength: 3,
                placeholder: '{!! trans("product_provider.product_category_placeholder") !!}'
            });
            $('.date-picker').datepicker({
                startDate: '+1',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: false,
                @if(app()->getLocale() == 'id')
                    language: 'id'
                @else
                    language: 'en'
                @endif
            });

            dayCount();

            $('input[name="start_date[0]"]')
            // .datepicker('setStartDate', '+1d')
                .on('changeDate', function (e) {
                    let endDate = $('input[name="end_date[0]"]')
                    let nextWeek = new Date(e.date.valueOf())
                    nextWeek.setDate(nextWeek.getDate() + 7)
                    let end_date = new Date(e.date.valueOf());
                    end_date.setDate(end_date.getDate() + 1);
                    endDate.removeAttr('disabled')
                    endDate.attr('readonly', true)
                    endDate.datepicker('update', nextWeek)
                    endDate.datepicker('setStartDate', end_date)
                })

            // $('#minimum_notice').bind('input', function (e) {
            //     if (e.target.value == '') {
            //         return
            //     }
            //     var curDate = new Date()
            //     var startDate = $('input[name="start_date[0]"]').val()
            //     var endDate = $('input[name="end_date[0]"]').val()

            //     curDate.setDate(curDate.getDate() + parseInt(e.target.value))
            //     // console.log(curDate);
            //     var newStartDate = new Date(startDate)
            //     var newEndDate = new Date(endDate)

            //     if (curDate > newStartDate) {
            //         newStartDate = curDate
            //     }

            //     var start_date = $('input[name="start_date[0]"]')
            //     var end_date = $('input[name="end_date[0]"]')

            //     var date = ("0" + newStartDate.getDate()).slice(-2)
            //     var mount = ("0" + (newStartDate.getMonth() + 1)).slice(-2)
            //     var year = newStartDate.getFullYear()
            //     var newDateValue = mount + '/' + date + '/' + year
            //     start_date.val(newDateValue)
            //     start_date.datepicker('setStartDate', newStartDate)

            //     if (newStartDate > newEndDate) {
            //         newEndDate = newStartDate
            //         var dateEnd = ("0" + newEndDate.getDate()).slice(-2)
            //         var mountDate = ("0" + (newEndDate.getMonth() + 1)).slice(-2)
            //         var yearDate = newEndDate.getFullYear()
            //         var oldDateValue = mountDate + '/' + dateEnd + '/' + yearDate
            //         end_date.val(oldDateValue)
            //         end_date.datepicker('setStartDate', newEndDate)
            //     }
            // })

            // $('[name="unit_name_id[]"]').change(function(){
            //     var textSelectedDynamic = $(this).text()

            // })

            // Select2
            $('.select2-product').select2({
                placeholder: "Select",
                allowClear: false,
            });

            $('#country_search').on('change', function () {
                let country = $('#country_search').val()
                let state = $('#state_search');
                changeToMap($(this))
                $.getJSON('{{ route('location.states') }}', {id_country: country})
                    .done(function (data) {
                        $('#state_search option').remove();
                        $('#city_search option').remove();
                        // state.select2('destroy');
                        $('#city_search').append(new Option('Select City / Kota',''))
                        // state.append(new Option('Select State / Provinsi',''))
                        @if(app()->getLocale() =='id')
                        $.each(data, function (index, item) {
                            state.append(new Option(item.state_name, item.id_state))
                        })
                        @else
                        $.each(data, function (index, item) {
                            state.append(new Option(item.state_name_en, item.id_state))
                        })
                        @endif
                        //   state.append(html);
                        state.select2().trigger('change');
                    });
            });

            $('#state_search').on('change', function () {
                changeToMap($(this))
                let state = $('#state_search').val();
                let city = $('#city_search');
                $('#city_search option').remove();
                city.select2('destroy');
                city.append(new Option('Select City / Kota',''))
                if (state !== ''){
                    $.getJSON('{{ route('location.cities') }}', {id_state: state})
                        .done(function (data) {

                            @if(app()->getLocale() =='id')
                            $.each(data, function (index, item) {
                                city.append(new Option(item.city_name, item.id_city))
                                // html+='<option>'+item.city_name+'</option>';
                            })
                            @else
                            $.each(data, function (index, item) {
                                city.append(new Option(item.city_name_en, item.id_city))
                                // html+='<option>'+item.city_name+'</option>';
                            })
                            @endif
                            // city.append(html);
                            city.select2();
                        });
                }else{
                    city.select2();
                }

            });

            // // Geolocation
            // getLocation();
            //
            // function getLocation() {
            //     if (navigator.geolocation) {
            //         navigator.geolocation.getCurrentPosition(showPosition);
            //     }
            // }

            function showPosition(position) {
                longitude = position.coords.longitude;
                latitude = position.coords.latitude;
                initMap();
            }

            jQuery('.dropify').dropify({
                messages: {
                    'default': "{{ trans('kyc.default1') }} <br><span style='font-size:.8rem'>{{ trans('kyc.default2') }}</span>",
                    'error': "{{ trans('kyc.error') }}"
                }
            });

            // Product Category
            var last_valid_selection = null;
            
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                $(document).on('change', '#category', function (e) {
                    let t = $(this);
                    let thisValue = t.val();
                    let category = $('#category-long');
                    if (thisValue.length > 3) {
                        last_valid_selection = thisValue.slice(0, 3);
                        t.val(last_valid_selection);
                        category.removeClass('d-none');
                    } else if (thisValue.length <= 3) {
                        last_valid_selection = thisValue;
                        category.addClass('d-none');
                    } else {
                        last_valid_selection = null;
                    }
                    console.log(thisValue);
                });
            }

            showHideItinerary();
            $('#product_type').on('change', function () {
                showHideItinerary();
            });

            // NEXT PAGE
            var multipage_test = RegExp('multipage', 'gi').test(window.location.search);
            if (multipage_test) {
                StartCreateIntro();
            }
        });

        function showHideItinerary() {
            if ($('#product_type').val() == 1 || $('#product_type').val() == 8) {
                $('#itinerary_form').css('display', 'none');
            } else {
                $('#itinerary_form').css('display', 'block');
            }
        }

        // Initialize plugin after dom loaded
        document.addEventListener('DOMContentLoaded', function() {
            switcheryInit();
        });
        function switcheryInit() {
            // Initialize multiple switches
            var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery-primary'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html, { color: '#2196F3' });
            });
        }
    </script>
@endsection

@push('script')
    <script >
        let intervalGoogle = setInterval(function () {
            console.log('waiting for render ... ')
            if (google!== undefined){
                initMap();
                clearInterval(intervalGoogle);
            }
        },100)
    </script>
@endpush

