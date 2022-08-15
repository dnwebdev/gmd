@extends('klhk.dashboard.company.base_layout')
@section('title', 'Edit Product')
@section('additionalStyle')
    <!--dropify-->
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet"
          media="screen,projection">
{{--    <link href="{{ asset('materialize/js/plugins/sweetalert/dist/sweetalert.css') }}" type="text/css" rel="stylesheet">--}}
    <link href="{{ asset('klhk-asset/dest-operator/css/index_klhk.css') }}" rel="stylesheet">
{{--    <link rel="stylesheet" href="{{ asset('klhk-asset/css/sweetalert2.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('klhk-asset/css/jquery.timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}">
    <link href="{{ url('css/component-custom-switch.min.css') }}" rel="stylesheet">
    <link href="{{ asset('klhk-asset/dest-operator/css/product_edit_klhk.css') }}" rel="stylesheet">
    <link href="{{ asset('klhk-asset/css/product_company.css') }}" rel="stylesheet">
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
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('product_provider.product_detail') }}
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
                    <span class="breadcrumb-item active">{{ trans('product_provider.edit_product') }}</span>
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
    

            <div class="product">
                <div class="">
                    <div class="row">
                        <div class="col">
                            <form id="form_ajax" method="POST"
                                action="{{ Route('company.product.update',$product->id_product) }}"
                                enctype="multipart/form-data">
                                <input type="hidden" name="_method" id="_method" value="PUT">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="product" value="{{ $product->id_product}}"/>
                                <input type="hidden" id="min_order" name="min_order" value="{{ $product->min_order }}"/>

                                <!-- <div class="dashboard-cta">
                                <button id="btn-submit" class="btn bg-green-klhk btn-cta" type="submit" name="action"  id="save" onclick="tinyMCE.triggerSave(true,true);" >Submit</button>
                                <button class="btn bg-green-klhk btn-cta" type="button" name="preview" id="preview" >Preview</button>
                                </div> -->

                                <input type="hidden" name="unique_code" id="sku" type="text"
                                    value="{{ $product->unique_code }}">

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
                                                            value="{{ $product->product_name }}" maxlength="100"
                                                            data-validation="{{trans('product_provider.product_name_required')}}">
                                                        <small class="error display-none" id="product-name-error"></small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="category" class="tooltips"
                                                            title="{{ trans('product_provider.category_for_product') }}">{{ trans('product_provider.product_category') }}
                                                            <span class="fa fa-info-circle fs-14"></span></label>
                                                        <select name="product_category[]" class="form-control multiple-select2-max3 form-control-uniform"
                                                                multiple="multiple" id="category" data-fouc>
                                                            @foreach ($product_category as $item)
                                                                <option value="{{ $item->id }}"
                                                                        @foreach ($product->tagValue as $itemTag)
                                                                        @if ($itemTag->tag_product_id == $item->id)
                                                                        selected
                                                                        @endif
                                                                        @endforeach
                                                                >{{ app()->getLocale()==='id'?$item->name_ind:$item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <label class="error d-none" id="category-long">{{ trans('product_provider.max_category_3') }}</label>                                                
                                                    </div>

                                                    <div class="form-group d-none">
                                                        <label for="country">{{ trans('product_provider.country') }}</label>
                                                        <span class="text-danger">*</span>
                                                        <select name="country" id="country_search" class="select2-product form-control"
                                                                data-validation="{{ trans('product_provider.country_required') }}"
                                                                required>
                                                            @foreach (App\Models\Country::all() as $item)
                                                                <option value=""></option>
                                                                <option value="{{ $item->id_country }}"
                                                                        @if ($product->city && $item->id_country == $product->city->state->country->id_country)
                                                                        selected
                                                                        @endif
                                                                >{{ $item->country_name }}</option>
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
                                                            @foreach ($state as $item)
                                                                <option value="{{ $item->id_state }}"
                                                                        @if ($item->id_state == $product->city->state->id_state)
                                                                        selected
                                                                        @endif
                                                                >{{ app()->getLocale() =='id'?$item->state_name:$item->state_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="error display-none" id="state-error"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="city">{{ trans('product_provider.city') }}</label>
                                                        <span class="text-danger">*</span>
                                                        <select name="city" id="city_search" class="select2-product form-control"
                                                                data-validation="{{ trans('product_provider.city_required') }}"
                                                                required>
                                                            @foreach ($city as $item)
                                                                <option value="{{ $item->id_city }}"
                                                                        @if ($item->id_city == $product->city->id_city)
                                                                        selected
                                                                        @endif
                                                                >{{ app()->getLocale() =='id'?$item->city_name:$item->city_name_en }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="error display-none" id="city-error"></small>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="brief_description" class="tooltips"
                                                            title="{{ trans('product_provider.short_description') }}">{{ trans('product_provider.brief_description') }}
                                                            <span class="text-danger">*</span><span class="fa fa-info-circle fs-14"></span></label>
                                                        <input type="text" class="form-control" name="brief_description"
                                                            maxlength="100"
                                                            value="{{ $product->brief_description }}" placeholder="{{ trans('product_provider.max_char_100') }}"
                                                            data-validation="{{trans('product_provider.brief_required')}}">
                                                        <small class="error display-none" id="brief-error"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pabout" class="tooltips"
                                                            title="{{ trans('product_provider.explain') }}">{{ trans('product_provider.about_this_product') }}
                                                            <span class="text-danger">*</span><span class="fa fa-info-circle fs-14"></span></label>
                                                        <textarea id="long_description" class="itinerary-input form-control" rows="7"
                                                                name="long_description"
                                                                length="300" data-validation="{{trans('product_provider.long_description_required')}}">{!!($product->long_description)!!} </textarea>
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
                                                        {{--{{dd($product->languages->pluck('id_language')->toArray())}}--}}
                                                        <select class="form-control multiple-select2-max5" name="guide_language[]"
                                                                multiple>
                                                            @foreach(\App\Models\Language::all() as $item)
                                                                <option value="{{$item->id_language}}" {{in_array($item->id_language,$product->languages->pluck('id_language')->toArray())?'selected':''}}>{{$item->language_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group select-icon-dropdown">
                                                        <label for="category">{{ trans('product_provider.type') }}</label>
                                                        <select name="product_type" id="product_type" class="form-control">
                                                            @foreach($product_type as $row)
                                                                <option value="{{ $row->id_tipe_product }}" {{ $product->id_product_type == $row->id_tipe_product ? 'selected' : '' }}>{{ $row->product_type_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fa fa-chevron-down"></i>
                                                        <input type="hidden" name="long" id="long"
                                                            value="{{ $product->longitude }}">
                                                        <input type="hidden" name="lat" id="lat"
                                                            value="{{ $product->latitude }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget card" id="order-guest">
                                    <div class="widget-header">
                                        <h3>{{ trans('product_provider.product_availability') }}</h3>
                                    </div>
                                    <div class="widget-content">
                                        @foreach($product->schedule as $key => $row)
                                        <input type="hidden" name="schedule_id[{{$key}}]" value="{{ $row->id_schedule }}" />
                                        @if ($key===0)
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                                <div class="form-group select-icon-dropdown">
                                                    <label for="availability">{{ trans('product_provider.select_time') }} <span class="text-danger">*</span></label>
                                                    <select name="availability" id="availability" class="form-control">
                                                        <option value="1" @if($product->availability == '1') selected @endif>
                                                            {{ trans('product_provider.always_available') }}
                                                        </option>
                                                        <option value="0" @if($product->availability == '0') selected @endif>
                                                            {{ trans('product_provider.fixed_dates') }}
                                                        </option>
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
                                                                id="sun.{{$key}}" name="sun[{{$key}}]"
                                                                onclick="unCheck()" 
                                                                value="1" data-day="0" {{ ($product->schedule[$key]->sun) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="sun.{{$key}}">
                                                                {{ trans('product_provider.sun') }}
                                                            </label>
                                                        </div>

                                                        <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                            <input type="checkbox" class="custom-control-input mon day" 
                                                                id="mon.{{$key}}" name="mon[{{$key}}]"
                                                                onclick="unCheck()"
                                                                value="1" data-day="1" {{ ($product->schedule[$key]->mon) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="mon.{{$key}}">
                                                                {{ trans('product_provider.mon') }}
                                                            </label>
                                                        </div>

                                                        <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                            <input type="checkbox" class="custom-control-input tue day" 
                                                                id="tue.{{$key}}" name="tue[{{$key}}]"
                                                                onclick="unCheck()"
                                                                value="1" data-day="2" {{ ($product->schedule[$key]->tue) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="tue.{{$key}}">
                                                                {{ trans('product_provider.tue') }}
                                                            </label>
                                                        </div>

                                                        <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                            <input type="checkbox" class="custom-control-input wed day" 
                                                                id="wed.{{$key}}" name="wed[{{$key}}]"
                                                                onclick="unCheck()"
                                                                value="1" data-day="3" {{ ($product->schedule[$key]->wed) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="wed.{{$key}}">
                                                                {{ trans('product_provider.wed') }}
                                                            </label>
                                                        </div>

                                                        <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                            <input type="checkbox" class="custom-control-input thu day" 
                                                                id="thu.{{$key}}" name="thu[{{$key}}]"
                                                                onclick="unCheck()"
                                                                value="1" data-day="4" {{ ($product->schedule[$key]->thu) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="thu.{{$key}}">
                                                                {{ trans('product_provider.thu') }}
                                                            </label>
                                                        </div>

                                                        <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                            <input type="checkbox" class="custom-control-input fri day" 
                                                                id="fri.{{$key}}" name="fri[{{$key}}]"
                                                                onclick="unCheck()"
                                                                value="1" data-day="5" {{ ($product->schedule[$key]->fri) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="fri.{{$key}}">
                                                                {{ trans('product_provider.fri') }}
                                                            </label>
                                                        </div>

                                                        <div class="custom-control custom-checkbox custom-control-inline form-check">
                                                            <input type="checkbox" class="custom-control-input sat day" 
                                                                id="sat.{{$key}}" name="sat[{{$key}}]"
                                                                onclick="unCheck()"
                                                                value="1" data-day="6" {{ ($product->schedule[$key]->sat) ? 'checked' : '' }}/>
                                                            <label class="custom-control-label label-all-day" for="sat.{{$key}}">
                                                                {{ trans('product_provider.sat') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small id="day-alert" class="error" data-validationMessage="{{ trans('product.day_validation') }}"></small>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row listdate"
                                                data-delete="{{ trans('product_provider.remove_date') }}"
                                                {{ ($key == 0) ? "id=dates" : '' }} @if($product->availability == '0') class="display-none @endif ">

                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-12"><label class="mt-3 mt-lg-0">{{ trans('product_provider.active_period') }}</label></div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-{{ $product->availability == 0 ? '12' : 'md-6' }}">
                                                        <div class="input-field start_label">
                                                            <label for="start_date">{{ trans('product_provider.start_date') }} <span class="text-danger">*</span></label>
                                                            <input type="text" name="start_date[]" placeholder="{{ trans('product_provider.start_date_placeholder') }}"
                                                                class="form-control date-picker" autocomplete="off" readonly
                                                                value="{{ date('m/d/Y',strtotime($product->first_schedule->start_date)) }}"
                                                                id="start"
                                                                data-validation="{{trans('product_provider.start_date_required')}}">
                                                            <small class="error display-none" id="error_start_date"></small>
                                                            {{-- <input type="text" name="start_date[]" data-large-mode="true" data-large-default="true" id="start_date.{{$key}}" class="datedrop start_date form-control" data-default-date="{{ date('m/d/Y',strtotime($row->start_date)) }}" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018"/> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 end_label" style="display: {{ $product->availability == 0 ? 'none' : 'block' }}">
                                                        <div class="input-field ">
                                                            <label for="end_date">{{ trans('product_provider.end_date') }} <span class="text-danger">*</span></label>
                                                            <input type="text" name="end_date[]" placeholder="{{ trans('product_provider.end_date_placeholder') }}" 
                                                                class="form-control date-picker" autocomplete="off" readonly
                                                                value="{{ date('m/d/Y',strtotime($product->first_schedule->end_date)) }}"
                                                                data-validation="{{trans('product_provider.end_date_required')}}">
                                                            <small class="error display-none" id="error_end_date"></small>
                                                            {{-- <input type="text" name="end_date[]" data-large-mode="true" data-large-default="true" id="end_date.{{$key}}" class="datedrop end_date form-control" data-default-date="{{ date('m/d/Y',strtotime($row->end_date)) }}" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018"/> --}}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="row">
                                                    <div class="col-12"><label class="mt-3 mt-lg-0">{{ trans('product_provider.operational') }}</label></div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group select-icon-dropdown">
                                                            <label for="nationality">{{ trans('product_provider.start_time') }}</label>
                                                            <input type="text" name="start_time[]" id="start_time.{{$key}}"
                                                                class="timepicker number start_time form-control" maxlength="5"
                                                                value="{{ date('H:i',strtotime($product->schedule[$key]->start_time)) }}"/>
                                                            <i class="fa fa-chevron-down"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group select-icon-dropdown">
                                                            <label for="nationality">{{ trans('product_provider.end_time') }}</label>
                                                            <input type="text" name="end_time[]" id="end_time.{{$key}}"
                                                                class="timepicker number end_time form-control" maxlength="5"
                                                                value="{{ date('H:i',strtotime($product->schedule[$key]->end_time)) }}"/>
                                                            <i class="fa fa-chevron-down"></i>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3 empty_space display-none">
                                            </div>
                                            
                                            @if($key == 0)
                                                {{--<div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">--}}
                                                {{--<button type="button" class="btn-success btn-sm btn-add add_date"--}}
                                                {{--id="add_date"--}}
                                                {{--style="margin-top:-10px;">{{ trans('product_provider.add_date') }}</button>--}}
                                                {{--</div>--}}
                                            @else
                                                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                                                    <button type="button" class="btn btn-danger btn-sm btn-add remove_date mt-minus-10">{{ trans('product_provider.remove_date') }}</button>
                                                </div>
                                            @endif
                                        </div>
                                        @endif
                                        @endforeach
                                        
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
                                                    <label for="duration">{{ trans('product_provider.low_duration') }} <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control max-hours" name="duration"
                                                            value="{{ $product->duration }}"
                                                            data-validation="{{trans('product_provider.duration_required')}}">
                                                    <small class="error display-none" id="min-duration-activity-error"></small>
                                                    <label id="max_hours" class="error" data-translate="{{ trans('product_provider.max_hours') }}"><label>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                                <div class="form-group select-icon-dropdown">
                                                    <label for="duration_type">{{ trans('product_provider.duration') }}</label>
                                                    <select class="form-control" name="duration_type">
                                                        @foreach($product->list_duration() as $key=>$row)
                                                            <option value="{{ $key }}" {{ ($product->duration_type==$key) ? 'selected' :'' }}>{{ $row }}</option>
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
                                                        <span class="text-danger">*</span><span class="fa fa-info-circle fs-14"></span></label>
                                                    <input type="number" class="form-control max-3" id="minimum_notice"
                                                            name="minimum_notice" value="{{ $product->minimum_notice }}"
                                                            data-validation="{{trans('product_provider.min_notice_required')}}"/>
                                                    <small class="error display-none" id="min-notice-error"></small>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3 display-none">
                                                <div class="form-group select-icon-dropdown">
                                                    <label for="booking_confirmation">Booking Confirmation</label>
                                                    <select id="booking_confirmation" name="booking_confirmation"
                                                            class="form-control" disabled>
                                                        @foreach($product->list_booking_confirmation() as $key=>$row)
                                                            <option value="{{ $key }}" {{ ($product->booking_confirmation==$key) ? 'selected' : '' }}>{{ $row }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down"></i>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="form-group select-icon-dropdown">
                                                    <label for="status">{{ trans('product_provider.product_status') }}</label>
                                                    <select id="status" name="status" class="form-control">
                                                        @foreach($product->list_status() as $key=>$row)
                                                            <option value="{{ $key }}" {{ ($product->status==$key) ? 'selected' :'' }}>{{ $row }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down"></i>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
                                                <div class="form-group multiple-form select-icon-dropdown">
                                                    <label for="publish">{{ trans('product_provider.publish_product') }}</label>
                                                    <select name="publish" id="publish" class="form-control">
                                                        <option {{ $product->publish ? 'selected' : ''}} value="1">{{ trans('product_provider.yes') }}</option>
                                                        <option {{ !$product->publish ? 'selected' : ''}} value="0">{{ trans('product_provider.no') }}</option>
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
                                                                length="200">{{ $product->important_notes }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="widget card product-pricing" id="product-pricing"  data-valdiation="{{ trans('product_provider.product_pricing_validation') }}">
                                    <div class="widget-header">
                                        <h3>{{ trans('product_provider.product_pricing') }}</h3>
                                    </div>

                                    <div class="widget-content">
                                        <div class="row display-none">
                                            <div class="col-sm-12 col-md-12 col-lg-6">
                                                <div class="form-group select-icon-dropdown">
                                                    <label for="currency">{{ trans('product_provider.currency') }}</label>
                                                    <select id="currency" name="currency" class="form-control">
                                                        <option value="">Select Currency</option>
                                                        @foreach($product->list_currency() as $row)
                                                            <option value="{{ $row }}" {{ ($product->currency==$row) ? 'selected=selected' :'' }}>{{ $row }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="fa fa-chevron-down"></i>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-12 col-lg-6">
                                                <div class="form-group multiple-form">
                                                    <label for="advertised_price">{{ trans('product_provider.advertised_price') }}
                                                        <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="advertised_price"
                                                        name="advertised_price" value="{{ $product->advertised_price }}"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 mb-3">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="form-group select-icon-dropdown">
                                                            <label for="payment_method">{{ trans('product_provider.payment_method') }} <span
                                                                        class="text-danger">*</span></label>
                                                            <select name="booking_confirmation" id="" class="form-control">
                                                                <option value="0" {{$product->booking_confirmation=='0'?'selected':''}}>{{ trans('product_provider.cash') }}</option>
                                                                <option value="1" {{$product->booking_confirmation=='1'?'selected':''}}>Online</option>
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
                                                                    name="max_people" value="{{ $product->max_people }}" 
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
                                                                <option value="{{ $key }}" {{ $product->quota_type == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                                            <label for="min_people" class="mb-auto"
                                                                    {{-- title="{{ trans('product_provider.minimum_pax') }}" --}}
                                                                    >
                                                                    {{ trans('product_provider.min') }} 
                                                                    {{-- <label class="dynamic_pricing"></label> --}}
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="number" min="1" class="form-control max-5" id="min_people"
                                                                    name="min_people" value="{{ $product->min_people }}" 
                                                                    data-validation="{{trans('product_provider.min_people_required')}}" 
                                                                    data-validation-min="{{trans('product_provider.min_people_else')}}"/>
                                                            <small id="alert-min-people" class="error"></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-xl-4">
                                                        <div class="form-group d-flex flex-column h-87">
                                                            <label for="max_order" class="mb-auto"
                                                                    {{-- title="{{ trans('product_provider.maximum_pax') }}" --}}
                                                                    >
                                                                    {{ trans('product_provider.max') }} 
                                                                    {{-- <label class="dynamic_pricing"></label> --}}
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="number" min="1" class="form-control max-5" id="max_order"
                                                                    name="max_order" value="{{ $product->max_order ?? $product->max_people }}" 
                                                                    data-validation="{{trans('product_provider.max_people_required')}}"
                                                                    data-validation-max="{{trans('product_provider.max_people_else')}}"/>
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

                                        @foreach($product->pricing as $key=>$row)
                                            <div class="row pricelist"
                                                {{ ($key == 0) ? "id=pricing" : '' }} data-delete="{{ trans('product_provider.delete_price') }}"
                                                data-pricetype="{{ trans('product_provider.unit_price') }}">
                                                <div class="col-lg-4 clone-display-control">
                                                    <div class="form-group display select-icon-dropdown">
                                                        <label for="pricetype">{{ trans('product_provider.unit_price') }}</label>
                                                        @if($key==0)
                                                            <select name="unit_name_id[]" class="form-control unit_name_id"
                                                                    id="display_id"
                                                                    required>
                                                                {{-- @foreach($product->list_display() as $a)
                                                                    <option value="{{ $a }}" {{ (optional($row->unit)->name == $a) ? 'selected' : '' }}>{{ $a }}</option>
                                                                @endforeach --}}
                                                                @foreach ($units as $unit)
                                                                    <option value="{{ $unit->id }}" {{ $row->unit_name_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <i class="fa fa-chevron-down"></i>
                                                        @else
                                                            <input type="text" class="form-control result-clone"
                                                                name="unit_name_id[]" readonly>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-lg-4 price-type-d-none">
                                                    <div class="form-group">
                                                        <label for="price_from.{{$key}}" 
                                                            {{-- class="tooltips"  --}}
                                                            {{-- title="{{ trans('product_provider.pax_from_tooltip') }}" --}}
                                                            >{{ trans('product_provider.pax_from') }} <label class="dynamic_pricing"></label>
                                                            {{-- <span class="fa fa-info-circle fs-14"></span> --}}
                                                        </label>
                                                        <span class="text-danger">*</span>
                                                        <div class="price_from_label">{{ trans('product_provider.pax_from_label') }}</div>
                                                        <div class="warning_label d-none"><i class="fa fa-exclamation-triangle"></i></div>
                                                        <input type="number" value="{{ $row->price_from }}"
                                                            id="price_from.{{$key}}" class="form-control totalpeople price-from max-5"
                                                            name="price_from[]"/>
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
                                                        <label for="until" class="tooltips" title="{{ trans('product_provider.pax_until_tooltip') }}">{{ trans('product_provider.pax_until') }}<span class="fa fa-info-circle fs-14"></span></label>
                                                        <span class="text-danger">*</span>
                                                        <input type="number" value="{{ $row->price_until }}"
                                                            id="price_until.{{$key}}" class="form-control totalpeople price-until max-5"
                                                            name="price_until[]"/>
                                                        <small id="alert-price-until" class="error"></small>
                                                    </div>
                                                </div>

                                                <div class="col-lg-3">
                                                    <div class="form-group">
                                                        <label for="price.{{$key}}">
                                                            <span class="price-label" data-price-fix="{{ trans('product_provider.price') }}" data-price-group="{{ trans('product_provider.price_group') }}"></span>
                                                            <label class="dynamic_pricing"></label>
                                                            <span class="text-danger">*</span>
                                                        </label> 
                                                        <div class="warning_label d-none"><i class="fa fa-exclamation-triangle"></i></div>
                                                        <input name="price[]" value="{{ $row->price }}" id="price.{{$key}}"
                                                                class="priceamount right-align form-control new_price number format-money" 
                                                                data-error="false"
                                                                type="text" maxlength="10" required data-validation="{{trans('product_provider.price_required')}}">
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

                                                @if($key == 0)
                                                    <div class="col-md-12 col-lg-1 mb-2 delete-container">
                                                        
                                                    </div>
                                                @else
                                                    <div class="col-md-12 col-lg-1 mb-2">
                                                        <button type="button" class="btn btn-danger btn-sm btn-add remove_price mt-36 float-right float-xl-left"><i class="fa fa-trash"></i><span class="d-md-inline-block d-lg-none">{{ trans('product_provider.delete_price') }}</span></button> 
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                        <div class="box-clone"></div>
                                        <div class="row">
                                            <div class="col-lg-11">
                                                <button type="button" class="btn btn-success btn-sm btn-add add_price mt-4 float-right"><i class="fa fa-plus"></i>{{ trans('product_provider.add_price') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- custom information -->
                                <div class="widget card" id="custom-input">
                                    <div class="widget-header">
                                        <h3>{{ trans('product_provider.customer_information') }}</h3>
                                    </div>
                                    <div class="widget-content">
                                        @foreach ($custom_schemas as $custom_schema)
                                            <input type="hidden" class="custom-id" name="custom_id[{{ $loop->index }}]" value="{{ $custom_schema->id }}" />
                                            <div class="row custom-input-clone" data-custom-index="{{ $loop->index }}">
                                                <div class="col-sm-12 mb-3">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group select-icon-dropdown">
                                                                <label for="custom-type" class="tooltips" title="{{ trans('product_provider.custom_type_tooltip') }}">
                                                                    {{ trans('product_provider.custom_type') }}
                                                                    <span class="fa fa-info-circle fs-14"></span>
                                                                </label>
                                                                <select name="custom_type[{{ $loop->index }}]" class="form-control custom-type">
                                                                    @foreach (\App\Models\CustomSchema::$types as $key => $value)
                                                                        <option value="{{ $key }}" {{ $custom_schema->type_custom == $key ? 'selected' : '' }}>{{ trans('product_provider.custom_types.'.$key) }}</option>
                                                                    @endforeach>
                                                                </select>
                                                                <i class="fa fa-chevron-down"></i>
                                                            </div>

                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label for="custom-type" class="tooltips" title="{{ trans('product_provider.custom_required_by_tooltip') }}">{{ trans('product_provider.custom_required_by') }} <span class="fa fa-info-circle fs-14"></label><br />
                                                            @foreach (\App\Models\CustomSchema::$fill_types as $key => $value)
                                                                <div class="form-check form-check-inline">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input custom-fill-type" type="radio" name="custom_fill_type[{{ $loop->parent->index }}]" {{ $custom_schema->fill_type == $key ? 'checked' : '' }} value="{{ $key }}">
                                                                        {{ trans('product_provider.custom_fill_types.'.$key) }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <button type="button" class="btn btn-success btn-sm btn-add add_custom_input mt-40  small" style="display: {{ $loop->iteration == $loop->count ? 'block' : 'none' }}">
                                                                {{ trans('product_provider.custom_add_form') }}
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm btn-add remove_custom_input mt-40 small" style="display: {{ $loop->iteration != $loop->count ? 'block' : 'none' }}" {!! $custom_schema->id ? 'data-id="'.$custom_schema->id.'"' : '' !!}>
                                                                {{ trans('product_provider.custom_remove_form') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 p-20 bg-white p-3 border mb-3">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label class="d-block">
                                                                <span class="tooltips" title="{{ trans('product_provider.custom_label_tooltip') }}">
                                                                    {{ trans('product_provider.custom_label') }}
                                                                    <span class="fa fa-info-circle fs-14"></span>
                                                                </span>
                                                                <input type="text" class="form-control w-100 custom-label" name="custom_label[{{ $loop->index }}]" value="{{ $custom_schema->label_name }}" placeholder="{{ trans('product_provider.custom_label_placeholder')  }}" />
                                                            </label>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label class="d-block">
                                                                <span class="tooltips" title="{{ trans('product_provider.custom_description_tooltip') }}">
                                                                    {{ trans('product_provider.custom_description') }}
                                                                    <span class="fa fa-info-circle fs-14"></span>
                                                                </span>
                                                                <input type="text" class="form-control w-100 custom-description" name="custom_description[{{ $loop->index }}]" value="{{ $custom_schema->description }}" maxlength="300"/>
                                                            </label>
                                                        </div>
                                                        <div class="col-12 custom-values" style="display: {{ in_array($custom_schema->type_custom, ['choices', 'checkbox', 'dropdown']) ? 'block' : 'none' }}">
                                                            <div class="row">
                                                                @foreach ($custom_schema->value as $value)
                                                                    <div class="col-md-3 custom-values-clone">
                                                                        <label>
                                                                            <span class="custom-desc custom-desc-choices" style="display: {{ $custom_schema->type_custom == 'choices' ? 'block' : 'none' }}">{{ trans('product_provider.custom_option_name') }}</span>
                                                                            <span class="custom-desc custom-desc-checkbox" style="display: {{ $custom_schema->type_custom == 'checkbox' ? 'block' : 'none' }}">{{ trans('product_provider.custom_checklist_name') }}</span>
                                                                            <span class="custom-desc custom-desc-dropdown" style="display: {{ $custom_schema->type_custom == 'dropdown' ? 'block' : 'none' }}">{{ trans('product_provider.custom_dropdown_name') }}</span>
                                                                            <div class="input-group">
                                                                                <input class="form-control form-control-sm custom-value-input" type="text" name="custom_values[{{ $loop->parent->index }}][]" value="{{ $value }}" />
                                                                                <div class="input-group-append custom-value-show-remove {{ $loop->index == 0 ? 'd-none' : 'd-flex' }}">
                                                                                    <button class="btn btn-danger btn-sm remove-custom-values" type="button">X</button>
                                                                                </div>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                                <div class="col-md-2 align-self-center">
                                                                    <a href="javascript:void(0);" class="add-custom-values">
                                                                        + 
                                                                        <span class="custom-desc custom-desc-choices" style="display: {{ $custom_schema->type_custom == 'choices' ? 'inline' : 'none' }}">{{ trans('product_provider.custom_add_option') }}</span>
                                                                        <span class="custom-desc custom-desc-checkbox" style="display: {{ $custom_schema->type_custom == 'checkbox' ? 'inline' : 'none' }}">{{ trans('product_provider.custom_add_checklist') }}</span>
                                                                        <span class="custom-desc custom-desc-dropdown" style="display: {{ $custom_schema->type_custom == 'dropdown' ? 'inline' : 'none' }}">{{ trans('product_provider.custom_add_dropdown') }}</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 text-muted">
                                                            @foreach (collect(trans('product_provider.custom_descriptions'))->except(['document', 'photo']) as $key => $doc)
                                                                <span class="custom-desc custom-desc-{{ $key }}" style="display: {{ $custom_schema->type_custom == $key ? 'block' : 'none' }}">{{ $doc }}</span>
                                                            @endforeach
                                                            <div class="form-group custom-desc custom-desc-document" style="display: {{ $custom_schema->type_custom == 'document' ? 'block' : 'none' }}">
                                                                <label for="exampleFormControlFile1">{{ trans('product_provider.custom_descriptions.document') }}</label>
                                                                <input type="file" class="form-control-file" disabled>
                                                        </div>
                                                        <div class="custom-desc custom-desc-photo" style="display: {{ $custom_schema->type_custom == 'photo' ? 'block' : 'none' }};">
                                                            <input type="file" id="input-file-now" class="dropify" disabled />
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
                                                    <input type="hidden" id="addon3"
                                                        name="addon3" {{ $product->addon3 === 1 ? 'value=1' : '' }}>
                                                    <label class="switch-rounded">
                                                        <input type="checkbox" id="addon1"
                                                            name="addon1" {{ ($product->addon1 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        <span class="slider"></span>
                                                    </label>
                                                </div> --}}
                                                <div class="form-check form-check-switchery">
                                                    <input type="hidden" id="addon3" name="addon3" {{ $product->addon3 === 1 ? 'value=1' : '' }}>
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input-switchery" data-fouc id="addon1" name="addon1" {{ ($product->addon1 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        {{ trans('product_provider.price_home') }}
                                                    </label>
                                                </div>
                                                <br>
                                                {{-- <div class="display-flex">
                                                    <label for="food-included">{{ trans('product_provider.price_eat') }}</label>
                                                    <input type="hidden" id="addon4"
                                                        name="addon4" {{ $product->addon4 === 1 ? 'value=1' : '' }}>
                                                    <label class="switch-rounded" id="foot-included">
                                                        <input type="checkbox" id="addon2"
                                                            name="addon2" {{ ($product->addon2 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        <span class="slider"></span>
                                                    </label>
                                                </div> --}}
                                                <div class="form-check form-check-switchery">
                                                    <input type="hidden" id="addon4" name="addon4" {{ $product->addon4 === 1 ? 'value=1' : '' }}>
                                                    <label class="form-check-label" id="foot-included">
                                                        <input type="checkbox" class="form-check-input-switchery" data-fouc id="addon2" name="addon2" {{ ($product->addon2 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        {{ trans('product_provider.price_eat') }}
                                                    </label>
                                                </div>
                                                <br>
                                                {{-- <div class="display-flex">
                                                    <label>{{ trans('product_provider.prices_include_pick_up') }}</label>
                                                    <input type="hidden" id="addon7"
                                                        name="addon7" {{ $product->addon7 === 1 ? 'value=1' : '' }}>
                                                    <label class="switch-rounded">
                                                        <input type="checkbox" id="addon5"
                                                            name="addon5" {{ ($product->addon5 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        <span class="slider"></span>
                                                    </label>
                                                </div> --}}
                                                <div class="form-check form-check-switchery">
                                                    <input type="hidden" id="addon7" name="addon7" {{ $product->addon7 === 1 ? 'value=1' : '' }}>
                                                    <label class="form-check-label" id="foot-included">
                                                        <input type="checkbox" class="form-check-input-switchery" data-fouc id="addon5" name="addon5" {{ ($product->addon5 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        {{ trans('product_provider.prices_include_pick_up') }}
                                                    </label>
                                                </div>
                                                <br>
                                                {{-- <div class="display-flex mb-5">
                                                    <label>{{ trans('product_provider.prices_include_pick_off') }}</label>
                                                    <input type="hidden" id="addon8"
                                                        name="addon8" {{ $product->addon8 === 1 ? 'value=1' : '' }}>
                                                    <label class="switch-rounded">
                                                        <input type="checkbox" id="addon6"
                                                            name="addon6" {{ ($product->addon6 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        <span class="slider"></span>
                                                    </label>
                                                </div> --}}
                                                <div class="form-check form-check-switchery">
                                                    <input type="hidden" id="addon8" name="addon8" {{ $product->addon8 === 1 ? 'value=1' : '' }}>
                                                    <label class="form-check-label" id="foot-included">
                                                        <input type="checkbox" class="form-check-input-switchery" data-fouc id="addon6" name="addon6" {{ ($product->addon6 === 1) ? 'checked'.' '.'value=1' : '' }}>
                                                        {{ trans('product_provider.prices_include_pick_off') }}
                                                    </label>
                                                </div>

                                                <div class="form-group mt-3 ml-1">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="show_exclusion" name="show_exclusion" {{ $product->show_exclusion ? 'checked' : '' }} value="1">
                                                        <label class="custom-control-label" for="show_exclusion">{{ trans('product_provider.show_exclusion') }}</label>
                                                    </div>
                                                </div>

                                                <div class="form-group mt-3 ml-1">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="vat" name="vat" {{ $product->vat ? 'checked' : '' }} value="1">
                                                        <label class="custom-control-label" for="vat">@lang('product_provider.add_vat')</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-6 row">
                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="discount_name" class="tooltips"
                                                            title="{{ trans('product_provider.discount_name_tooltip') }}">{{ trans('product_provider.discount_name') }}
                                                            <span class="fa fa-info-circle fs-14"></span></label>
                                                        <input type="text" class="form-control" id="discount_name"
                                                            name="discount_name" value="{{ $product->discount_name }}"
                                                            maxlength="100"/>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-6">
                                                    <div class="form-group select-icon-dropdown">
                                                        <label for="discount_amount_type">{{ trans('product_provider.discount_amount_type') }}</label>
                                                        <select id="discount_amount_type" name="discount_amount_type"
                                                                class="form-control">
                                                            <option value="">{{ trans('product_provider.no_discount') }}</option>
                                                            @foreach($product->list_amount_type() as $key=>$row)
                                                                <option value="{{ $key }}" {{ ($product->discount_amount_type===$key) ? 'selected' : '' }}>
                                                                    {{ $row }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <i class="fa fa-chevron-down"></i>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="discount_amount">{{ trans('product_provider.discount_amount') }}</label>
                                                        <div class="discount_amount_label"></div>
                                                        <input type="text" class="form-control number format-money"
                                                            id="discount_amount"
                                                            name="discount_amount" value="{{ $product->discount_amount }}"
                                                            min="0" oninput="validity.valid||(value='');" maxlength="10"/>
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
                                                            <input type="checkbox" class="form-check-input-switchery" data-fouc
                                                                   id="{{$insurance->insurance_slug}}" {{$product->insurances()->where('id',$insurance->id)->first()?'checked':''}} name="insurances[{{$insurance->id}}]" value="1">
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

                                <div class="widget card">
                                    <div class="widget-header">
                                        <h3>{{ trans('product_provider.images') }}</h3>
                                    </div>

                                    <div class="widget-content">
                                        <div class="widget-form row">
                                            <div class="custom-image-gallery">
                                                @for ($i = 0; $i < 4; $i++)
                                                    <div class="widget-image input-field col-sm-12 col-md-12 col-lg-6 col-xl-3 text-center border-product">

                                                        <label class="custom-input-cropping-image">
                                                            <input type="file" id="input-file-now" name="images[{{$i}}]" class="cropping-image custom-file-input" data-allowed-file-extensions="jpg jpeg png" accept="image/*" data-default-file="" data-max-file-size="2M"
                                                            data-id="{{ $i }}" aria-describedby="image-name-{{ $i }}" data-type="images" data-array="true"/>
                                                            <i class="fa fa-cloud-upload"></i>
                                                            <span>{{ trans('premium.facebook.label.upload') }}</span>
                                                        </label>
                                                        <div class="result-{{ $i }} file-result">
                                                            @if (isset($product->image[$i]))
                                                                <img src="{{ asset('uploads/products/'.$product->image[$i]->url) }}" />
                                                            @endif
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="input-group mb-3 {{ empty($product->image[$i]->url) ? 'display-none' : '' }} group-input-image-{{ $i }} input-group-cropping-image">
                                                            <input type="text" class="form-control label-data-name-file-{{ $i }} overflow-hidden" value="img-{{ $i }}" readonly>
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-{{ $i }}" data-id="{{ $i }}">
                                                                        {{ trans('product_provider.remove_image') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor 
                                                <div class="d-none" id="remove-image"></div>
                                            </div>
                                        </div>
                                        {{-- <p>{{ trans('setting_provider.image_term2') }}</p> --}}
                                    </div>
                                </div>

                                {{-- @include('klhk.dashboard.company.product.modal_cropping_image') --}}

                                <div class="widget card" id="itinerary_form">
                                    <div class="widget-header">
                                        <h3>{{ trans('product_provider.itinerary') }}</h3>
                                    </div>
                                    <div class="widget-content">
                                        @php $itin_count =count($product->itineraryCollection)@endphp
                                        @php $i = 1 @endphp
                                        @if($itin_count>0)
                                            @foreach($product->itineraryCollection as $key=>$row)
                                                <div class="itinerary_box box_day {{ $loop->index > 0 ? 'remove-itinerary-box' : '' }}"
                                                        data-translate="{{trans('product_provider.day')}}"
                                                        data-delete="{{ trans('product_provider.delete_day') }}" {{ ($key == 0) ? "id=itinerarylist" : '' }}>
                                                    <div class="form-group box_itin" id="box_itin.{{ $key }}">
                                                        <label id="day-{{ $key }}"
                                                                class="itin_day">{{ trans('product_provider.day') }} {{ $i }}</label>
                                                        <textarea id="itinerary_{{ $key }}" class="form-control itinerary-input"
                                                                    name="itinerary[]"
                                                                    length="300">{!!html_entity_decode($row->itinerary)!!}</textarea>
                                                    </div>
                                                    <div id="container_day"></div>
                                                </div>
                                                @php $i++ @endphp
                                            @endForeach
                                        @else
                                            <div class="itinerary_box box_day" id="itinerarylist"
                                                    data-translate="{{trans('product_provider.day')}}"
                                                    data-delete="{{ trans('product_provider.delete_day') }}">
                                                <div class="form-group box_itin" id="box_itin.0">
                                                    <label id="day-0" class="itin_day">{{ trans('product_provider.day') }}
                                                        1</label>
                                                    <textarea id="itinerary_0" class="form-control itinerary-input" rows="4"
                                                                name="itinerary[]" length="300"></textarea>
                                                    <button type="button" class="btn btn-success btn-sm btn-add display-none"
                                                            id="add_days">{{ trans('product_provider.add_day') }}</button>
                                                    {{--<div class="clear" style="clear: both;"></div>--}}
                                                </div>
                                            </div>
                                            <div id="container_day"></div>
                                        @endif
                                        <div id="button-add-delete">
                                            <button class="btn btn-success" id="add_day" type="button">{{ trans('product_provider.add_day') }}</button>
                                            <button class="btn btn-danger" id="remove-day" type="button">{{ trans('product_provider.delete_day') }}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="dashboard-cta col text-right">
                                        <button id="btn-submit" class="btn bg-green-klhk btn-cta step4" type="button" name="action" id="save" data-submit="{{trans('product_provider.submit')}}"
                                            onclick="tinyMCE.triggerSave(true,true);">{{ trans('product_provider.submit') }}</button>
                                        {{-- @if (\App\Models\Ota::where('ota_status', true)->exists())
                                            <button id="btn-show-ota" class="btn bg-green-klhk btn-cta step4" type="button" name="show-ota"
                                                    id="save" data-submit="{{trans('product_provider.submit')}}"
                                                    onclick="tinyMCE.triggerSave(true,true);"
                                                    data-toggle="modal" data-target="#ota-modal">{{ trans('product_provider.submit') }}</button>
                                        @else
                                            <button id="btn-submit" class="btn bg-green-klhk btn-cta step4" type="button" name="action" id="save" data-submit="{{trans('product_provider.submit')}}"
                                            onclick="tinyMCE.triggerSave(true,true);">{{ trans('product_provider.submit') }}</button>
                                        @endif  --}}
                                        <button class="btn bg-green-klhk btn-cta" type="button" name="preview"
                                                id="preview">{{ trans('product_provider.preview') }}</button>
                                    </div>
                                </div>
                                @if (\App\Models\Ota::where('ota_status', true)->exists())
                                    <button id="btn-show-ota" type="button" data-toggle="modal" data-target="#ota-modal" class="d-none" data-url="{{ route('company.product.update_ota') }}">modal ota</button>
                                    @include('klhk.dashboard.company.product.ota_modal', ['data' => $product->ota->pluck('id'), 'approved' => $product->ota->where('pivot.status', 1)->pluck('id'), 'rejected' => $product->ota->where('pivot.status', 2)->pluck('id')])
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('additionalScript')

    <script type="text/javascript">
        jQuery(document).on('keypress', 'input#pac-input', function (e) {
            if (e.keyCode === 13) {
                return false;
            }
        });
        var marker;
        var map;
        var longitude = "{{ $product->longitude ? $product->longitude : 0}}";
        var latitude = "{{ $product->latitude ? $product->latitude :  0}} ";

        longitude = parseFloat(longitude);
        latitude = parseFloat(latitude);

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

            marker.addListener('click', toggleBounce);
            marker.addListener('click', toggleBounce);
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
    <!-- Plugin -->
    <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('materialize/js/plugins/tinymce/tinymce-charactercount.plugin.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dest-operator/lib/js/jquery.tablesorter.min.js') }}"></script>
{{--    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
    <script src="{{ asset('js/jquery.timepicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="{{asset('dest-operator/lib/js/bootstrap-datepicker.id.js')}}" charset="UTF-8"></script>

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

        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = (month) + "/" + (day) + '/' + now.getFullYear();
        var enddate = (month) + "/" + (day) + '/' + '2025';
        jQuery('.listdate').show();
        jQuery(document).ready(function () {

                @if($product->availability == '0')
            {

                jQuery('.add_date').hide();
                jQuery('.remove_date').hide();
                jQuery('.remove_date').click();
                jQuery('.end_label').hide();
                jQuery('.empty_space').show();
                jQuery("#day_fix").hide();
                jQuery('.day').attr('checked', true).prop('checked', true);
                jQuery('#day-alert').html('');
                // jQuery('input[name="start_date[]"]').prop('disabled', false);
                // dayCount();
            }

                @endif
                @if($product->availability == '1')
            {
                jQuery('.listdate').show();
                jQuery('.start_label').show();
                jQuery('.end_label').show();
                jQuery('.add_date').show();
                jQuery('.remove_date').show();
                jQuery('.empty_space').hide();
                jQuery("#day_fix").show();
                // dayCount();
            }
            @endif

            // Active period show hide
            if (jQuery('#availability').val() == 0) {
                jQuery('.start_label').parent().removeClass('col-md-6').addClass('col-12');
            } else if (jQuery('#availability').val() == 0) {
                jQuery('.start_label').parent().removeClass('col-12').addClass('col-md-6');
            }

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

            jQuery('.dropify').dropify({
                messages: {
                    'default': "{{ trans('kyc.default1') }} <br><span style='font-size:.8rem'>{{ trans('kyc.default2') }}</span>",
                    'error': "{{ trans('kyc.error') }}"
                }
            });
            jQuery('.dropify').change(function () {
                let img = jQuery(this).data('default-file');
                if (img.length > 0) {
                    let input = "<input type='hidden' name='remove_image[]' class='remove_image' value='" + img + "'>";
                    jQuery("#remove-image").append(input);
                }
            });
            jQuery(".dropify-clear").click(function (events) {
                let data_prev = jQuery(this).prev();
                let input = "<input type='hidden' name='remove_image[]' class='remove_image' value='" + data_prev.data("default-file") + "'>";
                jQuery("#remove-image").append(input);
            })

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
                    }else if(thisValue.length <= 3){
                        last_valid_selection = thisValue;
                        category.addClass('d-none');
                    }else{
                        last_valid_selection = null;
                    }
                    console.log(thisValue);
                });
            }


        });

        window.$ = jQuery;
        $(document).ready(function () {
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
            $('.select2-product').select2({
                placeholder: "Select",
                allowClear: false
            });

            $('#country_search').on('change', function () {
                let country = $('#country_search').val()
                let state = $('#state_search');
                changeToMap($(this))
                $.getJSON('{{ route('location.states') }}', {id_country: country})
                    .done(function (data) {
                        $('#state_search option').remove();
                        $('#city_search option').remove();
                        state.select2('destroy');
                        $('#city_search').append(new Option('Select City / Kota',''))
                        state.append(new Option('Select State / Provinsi',''))
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

        });

        window.$ = jQuery;
        $(document).ready(function () {
            @if(app()->getLocale() == 'id')
            $('.date-picker').datepicker({
                // startDate : '+1',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: false,
                language: 'id'
            });
            @else
            $('.date-picker').datepicker({
                // startDate : '+1',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: false,
                language: 'en'
            });
            @endif
            $('input[name="start_date[]"]')
                .on('changeDate', function (e) {
                    var endDate = $('input[name="end_date[]"]')
                    var newDate = new Date(e.date.valueOf())
                    endDate.removeAttr('disabled')
                    endDate.attr('readonly', true)
                    endDate.val(moment(newDate).add(7,'days').format('MM/DD/YYYY'))
                    endDate.datepicker('setStartDate', moment(newDate).add(1,'days').format('MM/DD/YYYY'))
                });

            var selectName = $('#display_id').find("option:selected").text();
            $('.result-clone').val(selectName);
        })

        dayCount();

        function dayCount() {
            var disable_day = [];
            $('.day').each(function (i, e) {
                if (i != 0) {
                    if (!$(e).prop('checked')) {
                        disable_day.push($(e).attr('data-day'));
                    }
                }
            })
            // $('.date-picker').datepicker('destroy').datepicker({
            //     format: 'mm/dd/yyyy',
            //     autoclose: true,
            //     todayHighlight: false,
            //     daysOfWeekDisabled: disable_day
            // })
            @if(app()->getLocale() == 'id')
            $('.date-picker').datepicker('destroy').datepicker({
                // startDate : '+1',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: false,
                daysOfWeekDisabled: disable_day,
                language: 'id'
            });
            @else
            $('.date-picker').datepicker('destroy').datepicker({
                // startDate : '+1',
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: false,
                daysOfWeekDisabled: disable_day,
                language: 'en'
            });
            @endif
        }

        $(document).ready(function () {
            checkboxes = document.getElementsByClassName('day');
            document.getElementById('all.0').checked = true;
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                if (checkboxes[i].checked == false) {
                    document.getElementById('all.0').checked = false;
                }
            }
        })

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

        // INTRO JS NEXT PAGE
        var multipage_test = RegExp('multipage', 'gi').test(window.location.search);
        if (multipage_test) {
            var intro = introJs();
            intro.setOption('doneLabel', '{{ trans('intro.mywebsite_btn') }}').setOption('keyboardNavigation', false).setOptions({
                steps: [
                    {
                        intro: "<span class='fa fa-check-circle' style='display: flex;color: #0094d1;padding: 29px;font-size: 5rem;margin: auto;transform: translate(-60%,-15%);'></span><br>{{ trans('intro.edit1') }} <br> {{ trans('intro.edit2') }}",
                    }
                ]
            }).start().oncomplete(function () {
                window.open("http://{{ auth()->user()->company->domain_memoria }}");
            });  //change this to whatever function you need to call to run the intro
            // Auto scroll to top
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
            // Disable scroll when intro js start
            $('body').css('overflow', 'hidden');
            intro.onexit(function () {
                $('body').css('overflow', 'auto');
            });
        }

        // Show Hide Itinerary by Activity
        $(document).ready(function () {
            showHideItinerary();
            $('#product_type').on('change', function () {
                showHideItinerary();
            });
        });

        function showHideItinerary() {
            if ($('#product_type').val() == 1 || $('#product_type').val() == 8) {
                $('#itinerary_form').css('display', 'none');
            } else {
                $('#itinerary_form').css('display', 'block');
            }
        }

        // Timepicker initialization
        $('input.timepicker').timepicker({
            timeFormat: 'HH:mm',
            scrollbar: true,
            startTime: '07:00',
            dynamic: false
        });

        // Initialize plugin after dom loaded
        document.addEventListener('DOMContentLoaded', function() {
            switcheryInit();
        });
        function switcheryInit() {
            // Initialize multiple switches
            var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html);
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

