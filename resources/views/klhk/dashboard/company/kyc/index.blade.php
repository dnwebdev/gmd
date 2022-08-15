@extends('klhk.dashboard.company.base_layout')

@section('title', __('general.kyc'))

@section('additionalStyle')
    {{-- <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet"
          media="screen,projection"> --}}
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }

        textarea.form-control {
            height: 5rem;
        }
        label.error {
            color: red;
            font-size: smaller;
        }
        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: .5rem;
            color: #6f6f6f;
            background: #f3f3f3;
        }
        .kyc-front-page .widget-content {
            margin-left: auto!important;
            margin-right: auto!important;
        }
    </style>
@endsection

@section('breadcrumb')
@endsection

@section('indicator_order')
    active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{trans('general.kyc')}}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{trans('general.kyc')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->
<!-- main content -->
<div class="content pt-0 kyc-section" dashboard>
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('klhk.dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        <div class="alert show-after-front-page {{ auth()->user()->company->kyc ? '' : 'd-none' }}">
            {{ trans('kyc.alert') }}
        </div>
        <div class="row">
            <div class="col">
                <div id="front_page_kyc" class="widget card kyc-front-page {{ auth()->user()->company->kyc ? 'd-none' : '' }}">
                    <div class="widget-content m-3">
                        <div class="img-fluid w-100 text-center">
                            <img src="{{ asset('klhk-asset/dest-operator/img/upgrade-premium.png') }}" alt="premium" class="img-fluid premium-image">
                        </div>
                        <div class="h2 text-center font-weight-bold title">{!! trans('kyc.front-page.title') !!}</div>
                        <p>{!! trans('kyc.front-page.desc') !!}</p>
                        {{-- <div class="row">
                            <div class="col">
                                <div class="h4">{!! trans('kyc.front-page.pre_list') !!}</div>
                            </div>
                        </div> --}}
                        <div class="row mb-2">
                            <div class="col-auto">
                                <img src="{{ asset('klhk-asset/dest-operator/img/green-star.png') }}" alt="" class="kyc-front-image-list mt-1">
                            </div>
                            <div class="col">
                                <p class="font-weight-bold">{!! trans('kyc.front-page.list.0.caption') !!}</p>
                                <p>{!! trans('kyc.front-page.list.0.desc') !!}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <img src="{{ asset('klhk-asset/dest-operator/img/green-card.png') }}" alt="" class="kyc-front-image-list mt-1">
                            </div>
                            <div class="col">
                                <p class="font-weight-bold">{!! trans('kyc.front-page.list.1.caption') !!}</p>
                                <p>{!! trans('kyc.front-page.list.1.desc') !!}</p>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <img src="{{ asset('klhk-asset/dest-operator/img/green-person.png') }}" alt="" class="kyc-front-image-list mt-1">
                            </div>
                            <div class="col">
                                <p class="font-weight-bold">{!! trans('kyc.front-page.list.2.caption') !!}</p>
                                <p>{!! trans('kyc.front-page.list.2.desc') !!}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col text-center">
                                <button type="button" class="btn bg-green-klhk mt-3" id="next_to_complete_form">{!! trans('kyc.front-page.complete_now') !!}</button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::open(['files'=>true]) !!}
                @if(auth('web')->user()->company->ownership_status === 'corporate')
                    <div id="cv" class="widget card show-after-front-page {{ auth()->user()->company->kyc ? '' : 'd-none' }}">
                        <div class="widget-header">
                            <h3>{{ trans('kyc.caption1') }}</h3>
                        </div>
                        <div class="widget-content">
                            @if(auth('web')->user()->company->kyc)
                                <div class="form-group text-right">
                                    <span class="small">
                                        Status :
                                    </span>
                                    @if(auth('web')->user()->company->kyc->status =='approved')
                                        <span class="text-success font-weight-bold ml-1 text-uppercase badge-status">{{trans('kyc.status.'.auth('web')->user()->company->kyc->status)}}</span>
                                    @elseif(auth('web')->user()->company->kyc->status=='rejected')
                                        <span class="text-danger font-weight-bold ml-1 text-uppercase badge-status">{{trans('kyc.status.'.auth('web')->user()->company->kyc->status)}}</span>
                                    @else
                                        <span class="text-secondary font-weight-bold ml-1 text-uppercase badge-status">{{trans('kyc.status.'.auth('web')->user()->company->kyc->status)}}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="intro">{{ trans('kyc.intro') }}</p>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.company_npwp') }}<span class="text-danger">*</span></label>
                                        <input name="company_tax_number"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->company_tax_number)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->company_tax_number)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.company_act') }}<span class="text-danger">*</span></label>
                                        <input name="company_establishment_deed"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->company_establishment_deed)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->company_establishment_deed)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.company_certificate') }}<span class="text-danger">*</span></label>
                                        <input name="company_register_certification"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->company_register_certification)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->company_register_certification)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.company_domicile') }}<span class="text-danger">*</span></label>
                                        <input name="company_domicile"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->company_domicile)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->company_domicile)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.business_license') }}<span class="text-danger">*</span></label>
                                        <input name="company_business_license"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->company_business_license)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->company_business_license)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.identity_card') }}<span class="text-danger">*</span></label>
                                        <input name="owner_identity_card"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->owner_identity_card)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->owner_identity_card)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.tax_number') }}<span class="text-danger">*</span></label>
                                        <input name="owner_tax_number"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->owner_tax_number)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->owner_tax_number)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div id="person" class="widget card show-after-front-page {{ auth()->user()->company->kyc ? '' : 'd-none' }}">
                        <div class="widget-header">
                            <h3>{{ trans('kyc.caption2') }}</h3>
                        </div>
                        <div class="widget-content">
                            @if(auth('web')->user()->company->kyc)
                                <div class="form-group text-right">
                                    <span class="small">
                                        Status :
                                    </span>
                                    @if(auth('web')->user()->company->kyc->status =='approved')
                                        <span class="text-success font-weight-bold ml-1 text-uppercase badge-status">{{trans('kyc.status.'.auth('web')->user()->company->kyc->status)}}</span>
                                    @elseif(auth('web')->user()->company->kyc->status=='rejected')
                                        <span class="text-danger font-weight-bold ml-1 text-uppercase badge-status">{{trans('kyc.status.'.auth('web')->user()->company->kyc->status)}}</span>
                                    @else
                                        <span class="text-secondary font-weight-bold ml-1 text-uppercase badge-status">{{trans('kyc.status.'.auth('web')->user()->company->kyc->status)}}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <p class="intro">{{ trans('kyc.intro') }}</p>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.b_identity_card') }}<span class="text-danger">*</span></label>
                                        <input name="identity_card"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->identity_card)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->identity_card)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.family_card') }}<span class="text-danger">*</span></label>
                                        <input name="family_card"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->family_card)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->family_card)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.tax_id') }}<span class="text-danger">*</span></label>
                                        <input name="tax_number"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->tax_number)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->tax_number)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="false">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.police_certificate') }}</label>
                                        <input name="police_certificate"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->police_certificate)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->police_certificate)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="true">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.bank_statement') }}</label>
                                        <input name="bank_statement"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->bank_statement)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->bank_statement)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="true">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label>{{ trans('kyc.owner_photo') }}</label>
                                        <input name="photo"
                                                type="file"
                                                accept="image/x-png,image/jpg,image/jpeg"
                                                class="dropify" data-allowed-file-extensions="jpg jpeg png"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->photo)
                                                data-default-file="{{asset(auth('web')->user()->company->kyc->photo)}}"
                                                @endif
                                                data-allowed-file-extensions="png jpg jpeg"
                                                data-max-file-size="2M"
                                                data-show-remove="true">
                                        <input type="hidden" name="deleted_files[]">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>{{ trans('kyc.phone_number') }}<span class="text-danger">*</span></label>
                                        <input type="text" name="phone_number"
                                                @if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->phone_number)
                                                value="{{auth('web')->user()->company->kyc->phone_number}}"
                                                @endif
                                                class="form-control number"
                                                placeholder="{{trans('kyc.phone_number')}}" maxlength="15">
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label>{{ trans('kyc.office_address') }}<span class="text-danger">*</span></label>
                                        <textarea rows="3" id="company-address" name="address" type="text" style="background-color: #fafafa"
                                                    placeholder="{{ trans('kyc.office_address') }}"
                                                    class="form-control" maxlength="255">@if(auth('web')->user()->company->kyc && auth('web')->user()->company->kyc->address){{auth('web')->user()->company->kyc->address}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row show-after-front-page {{ auth()->user()->company->kyc ? '' : 'd-none' }}">
                    <div class="col-12 text-right">
                        <button class="btn bg-green-klhk" type="button"
                                id="btn-submit-kyc">{!! trans('kyc.verif') !!}</button>
                    </div>
                </div>
                {!! Form::close() !!}
                {{-- Succes --}}
                <div id="kyc_success" class="widget card kyc-success-page d-none">
                    <div class="widget-content m-3">
                        <div class="img-fluid w-100 text-center">
                            <img src="{{ asset('dest-operator/img/kyc-submit-success.png') }}" alt="success" class="img-fluid premium-image">
                        </div>
                        <div class="h2 text-center font-weight-bold title">{!! trans('kyc.submit_success.title') !!}</div>
                        <div class="row">
                            <div class="col text-center">
                                <a href="{{ route('company.dashboard') }}" class="btn bg-green-klhk mt-3" id="back_to_dashboard">{!! trans('kyc.submit_success.button') !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additionalScript')
    {{-- <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script> --}}
    <script>
        // Dropify Clear
        $(document).on('click', '.dropify-clear', function(){
            let parent = $(this).closest('.form-group');
            let file = parent.find('input[type="file"]').attr('name');
            parent.find('input[name="deleted_files[]"]').val(file);
        })
        $(document).on('change', 'input[type="file"]', function(){
            $(this).closest('.form-group').find('input[name="deleted_files[]"]').val('');
        })

        // Loader 
        function loadingStart() {
            jQuery('.loading').addClass('show');
            $('button').attr('disabled', true);
            $('button').prop('disabled', true);
        }

        function loadingFinish() {
            jQuery('.loading').removeClass('show');
            $('button').attr('disabled', false);
            $('button').prop('disabled', false);
        }

        // Dropify
        jQuery(document).ready(function () {
            jQuery('.dropify').dropify({
                messages: {
                    'default': "{{ trans('kyc.default1') }} <br><span style='font-size:.8rem'>{{ trans('kyc.default2') }}</span>",
                    'error':   "{{ trans('kyc.error') }}"
                }
            });
        })
        // Remove error when change value
        $(document).on('change keyup', 'input, textarea', function () {
            $(this).closest('.form-group').find('label.error').remove();
        });

        // Button Submit KYC
        jQuery(document).on('click', '#btn-submit-kyc', function () {
            loadingStart();
            let fD = new FormData();
            fD.append('_token', jQuery('input[name=_token]').val());
            let files = jQuery(this).closest('form').find('input, textarea')
            jQuery.each(files, function (i, e) {
                let f = jQuery(e);
                if (f.attr('type') === 'file') {
                    if (f[0].files.length > 0) {
                        fD.append(f.attr('name'), f[0].files[0]);
                    }
                } else {
                    fD.append(f.attr('name'), f.val());
                }
            })
            $('label.error').remove();
            $.ajax({
                url: '',
                data: fD,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function (data) {
                    $('.kyc-success-page').removeClass('d-none');
                    $('.show-after-front-page').addClass('d-none');
                    loadingFinish();
                },
                error: function (e) {
                    loadingFinish();
                    if (e.status === 422) {
                        let dt = e.responseJSON;
                        let errors = dt.errors;
                        jQuery.each(errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}');
                }
            });
        });

        $(document).on('click', '#next_to_complete_form', function(){
            $('.kyc-front-page').addClass('d-none');
            $('.show-after-front-page').removeClass('d-none');
        })

    </script>
@endsection
