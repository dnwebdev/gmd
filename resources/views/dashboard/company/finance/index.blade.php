@extends('dashboard.company.base_layout')
@section('additionalStyle')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('dest-operator/css/index.css') }}">
    {{--    <link rel="stylesheet" href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}">--}}
    {{--    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">--}}
    {{--    <link href="{{ url('css/component-custom-switch.min.css') }}" rel="stylesheet">--}}
    <link href="{{ asset('dest-operator/reskin-assets/css/components.min.css') }}" rel="stylesheet">
    <style>
        .btn-buy-now {
            margin-top: 2rem !important;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .nav-time.nav-tabs .nav-link.active {
            background-color: #3c96f3;
            border-color: #3c96f3;
            color: white;
            border-radius: 3px;
            height: 100%;
        }

        .nav-time.nav-tabs .nav-link {
            margin: 0 10px;
            border-top: 0;
            border-bottom: 3px solid #a6ceec;
            height: 40px!important;
            line-height: 3;
        }

        .nav-time.nav-tabs .nav-item:first-child .nav-link {
            margin-left: 0;
        }

        .nav-time.nav-tabs .nav-item:last-child .nav-link {
            margin-right: 0;
        }

        .nav-time.nav-tabs .nav-link:hover {
            border-bottom: 3px solid #3c96f3;
        }

        .nav-tabs-bottom .nav-link.active:before {
            display: none
        }

        .wizard > .steps {
            display: none;
        }

        .wizard > .actions {
            text-align: center;
        }

        /*.wizard > .actions > ul > li[aria-disabled="true"] {*/
        /*    float: left;*/
        /*    display: none;*/
        /*}*/

        /*.wizard > .actions > ul > li[aria-disabled="false"] {*/
        /*    float: left;*/
        /*}*/

        .nav-time {
            border: 0px solid #ddd;
        }

        .form-control.finance {
            border: 1px solid #a6ceec !important;
            padding: 1.5rem 1rem;
            border-radius: 4px;
        }

        .custom-file-finance {
            border: 1px solid #a6ceec !important;
            padding: 3px 1rem;
            border-radius: 4px;
        }

        .custom-file-label {
            border: 0 !important;
            margin-left: 2.5rem;
        }

        .custom-file-input:lang({{ app()->getLocale() }}) ~ .custom-file-label::after {
            content: "{{ __('premium.fb_ig.form.document_ads.placeholder') }}";
            background: #3c96f3;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            line-height: 2.2;
            border: 0 !important;
            font-size: 11px;
            margin-top: 2px;
        }

        .custom-file-input:lang({{ app()->getLocale() }}) ~ .button-disabled.custom-file-label::after {
            opacity: .7;
        }

        .no-image {
            font-family: Lato;
            font-size: 12px;
            font-weight: normal;
            font-stretch: normal;
            font-style: italic;
            line-height: 1.5;
            letter-spacing: normal;
            text-align: left;
            color: #64697a;
            margin-left: 0.5rem;
        }

        ul > li > a[href="#previous"], .btn-back-finance button {
            background: transparent!important;
            color: grey;
        }

        ul > li > a[href="#previous"]:hover, .btn-back-finance button:hover {
            box-shadow: none
        }

        a[href="#next"] {
            color: #fff!important;
        }
        .error-help-block {
            color: #dc3545 !important;
            display: block;
        }

        .success .col {
            max-width: 700px;
        }

        ul[aria-label="Pagination"] {
            position: relative;
        }

        ul[aria-label="Pagination"] > li:first-child, .btn-back-finance {
            position: absolute;
            left: 0;
            top: 0;
            margin-left: 0!important;
        }

        /* a[href="#previous"] < li {
            position: absolute;
            left: 0;
        } */

        /*a:focus, a:hover {*/
        /*    color: #fff!important;*/
        /*}*/
        /*.btn-light.focus, .btn-light:focus, .btn-light:hover, .btn-light:not([disabled]):not(.disabled).active, .btn-light:not([disabled]):not(.disabled):active, .show>.btn-light.dropdown-toggle {*/
        /*    color: #ffffff;*/
        /*}*/

        .custom-file-finance .custom-file-label {
            box-shadow: none!important;
            line-height: 1.5;
        }

        .form-control-feedback img {
            max-width: 25px;
        }

        .custom-file-label {
            line-height: 1.5;
        }

        @media screen and (max-width: 767px) {
            .wizard>.content>.body {
                padding: 0 1.5rem;
            }
            .wizard>.content {
                margin-bottom: 40px;
            }
            .wizard>.actions {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
            .nav-time {
                display: block;
            }

            .conclusion-table tr td {
                padding: .3125rem!important;
            }

            .nav-time.nav-tabs .nav-item .nav-link, .nav-time.nav-tabs .nav-item:last-child .nav-link, .nav-time.nav-tabs .nav-item:first-child .nav-link {
                margin: 0;
                margin-bottom: 5px;
            }

            .custom-file-input:lang({{ app()->getLocale() }}) ~ .custom-file-label::after {
                font-family: "FontAwesome";
                content: "\f093";
                text-align: center;
            }

            ul[aria-label="Pagination"] > li:first-child, .btn-back-finance {
                top: 50px;
                left: 50%;
                transform: translateX(-50%);
            }

            .wizard>.actions>ul>li {
                margin-left: 0!important;
                width: 100%;
                margin-bottom: 50px;
            }

            .wizard>.actions>ul>li>a[href="#next"], .wizard>.actions>ul>li>a[href="#previous"], .wizard>.actions>ul>li.btn-back-finance>button, a[href="#finish"] {
                width: 100%;
                position: relative;
            }

            a[href="#next"] .icon-arrow-right14, a[href="#previous"] .icon-arrow-left13, .btn-back-finance .icon-arrow-left13 {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
            }
            a[href="#previous"] .icon-arrow-left13,  .btn-back-finance .icon-arrow-left13 {
                right: unset;
                left: 10px;
            }
        }
    </style>
@endsection
@section('title', ucfirst(trans('sidebar_provider.financing')))

@section('breadcrumb')
@stop

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
                        {{ucfirst(trans('sidebar_provider.financing'))}}
                    </h5>
                </div>

                <div class="header-elements py-0">
                    <div class="breadcrumb">
                        <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                            <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                        </a>
                        <span class="breadcrumb-item active">{{ucfirst(trans('sidebar_provider.financing'))}}</span>
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
        @if ($company->kyc && $company->kyc->status == 'approved')
            <div class="card card-body border-top-primary banner front-page">
                <h3 class="text-center">{!! trans('finance.title_header') !!}</h3>

                <img class="img-fluid text-center" style="margin: auto"
                    src="{{ asset('img/finance/il-kembangkan-bisnis.png') }}" alt="">
                <br>
                <p class="col-lg-6 text-center justify-content-center"
                style="margin: auto">{!! trans('finance.content_header') !!}</p>
            </div>

            <div class="loan-funds front-page">
                @forelse($type_finance as $item)
                    <div class="card card-body border-top-primary">
                        <div class="row">
                            <div class="col-12 col-lg-3 align-self-center text-center mb-3">
                                <img class="img-fluid" src="{{ asset($item->image) }}"
                                    height="75"/>
                            </div>
                            <div class="col-12 col-lg-6 text-title-ads">
                                <h4><strong>{{ \App::getLocale() == 'id' ? $item->title_id : $item->title_en }}</strong>
                                </h4>
                                <p>{{ \App::getLocale() == 'id' ? $item->content_id : $item->content_en }}</p>
                            </div>
                            <div class="col-12 col-lg-3 text-center justify-content-center">
                                @if ($item->id == '1')
                                    <button class="btn btn-primary btn-buy-now" id="btn-loan" data-id="{{ $item->id }}"
                                            type="button"
                                            style="margin-bottom:1rem;">{{ \App::getLocale() == 'id' ? $item->button_id : $item->button_en }}
                                    </button>
                                @else
                                    <button class="btn btn-primary btn-buy-now" disabled data-id="{{ $item->id }}"
                                            type="button"
                                            style="margin-bottom:1rem;">{{ \App::getLocale() == 'id' ? $item->button_id : $item->button_en }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No Content</p>
                @endforelse
            </div>

            {{-- Hide temporary --}}
            {{-- tabs --}}
            {{--        <div class="history front-page">--}}
            {{--            <h2>Riwayat Peminjaman Dana</h2>--}}
            {{--            <div class="card-body">--}}
            {{--                <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0">--}}
            {{--                    <li class="nav-item"><a href="#bottom-divided-tab1" class="nav-link active"--}}
            {{--                                            data-toggle="tab">Semua</a>--}}
            {{--                    </li>--}}
            {{--                    <li class="nav-item"><a href="#bottom-divided-tab2" class="nav-link"--}}
            {{--                                            data-toggle="tab">Menunggu Konfirmasi</a></li>--}}
            {{--                    <li class="nav-item"><a href="#bottom-divided-tab2" class="nav-link"--}}
            {{--                                            data-toggle="tab">Disetujui</a></li>--}}
            {{--                    <li class="nav-item"><a href="#bottom-divided-tab2" class="nav-link"--}}
            {{--                                            data-toggle="tab">Ditolak</a></li>--}}
            {{--                </ul>--}}
            {{--                <div class="tab-content">--}}
            {{--                    <div class="tab-pane fade show active" id="bottom-divided-tab1">--}}
            {{--                        <div class="row">--}}
            {{--                            <div class="col-12 col-lg-4">--}}
            {{--                                <div class="card card-body">--}}
            {{--                                    <div>--}}
            {{--                                        <h6 class="text-right" style="margin-right: 0.1rem;">DISETUJUI--}}
            {{--                                            <img class="img-fluid text-right" style="margin: auto 0 0.10rem 0.8rem;"--}}
            {{--                                                 src="{{ asset('img/finance/16-px-setuju.png') }}" alt="">--}}
            {{--                                        </h6>--}}
            {{--                                    </div>--}}
            {{--                                    <h1 class="text-primary font-weight-bold mt-2">Rp. 10.000.000</h1>--}}
            {{--                                    <div class="row mt-3" style="margin-bottom: -1rem">--}}
            {{--                                        <div class="col-6 col-lg-6">--}}
            {{--                                            <p class="font-size-sm">6/01/2020</p>--}}
            {{--                                            <h6 class="font-weight-500">Pinjaman DANA</h6>--}}
            {{--                                        </div>--}}
            {{--                                        <div class="col-6 col-lg-6 text-right mt-1">--}}
            {{--                                            <img src="{{ asset('img/finance/dot_biru.svg') }}" alt="">--}}
            {{--                                        </div>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-12 col-lg-4">--}}
            {{--                                <div class="card card-body">--}}
            {{--                                    <div>--}}
            {{--                                        <h6 class="text-right" style="margin-right: 0.1rem;">DITOLAK--}}
            {{--                                            <img class="img-fluid text-right" style="margin: auto 0 0.10rem 0.8rem;"--}}
            {{--                                                 src="{{ asset('img/finance/16-px-tolak.png') }}" alt="">--}}
            {{--                                        </h6>--}}
            {{--                                    </div>--}}
            {{--                                    <h1 class="text-danger font-weight-bold mt-2">Rp. 10.000.000</h1>--}}
            {{--                                    <div class="row mt-3" style="margin-bottom: -1rem">--}}
            {{--                                        <div class="col-6 col-lg-6">--}}
            {{--                                            <p class="font-size-sm">6/01/2020</p>--}}
            {{--                                            <h6 class="font-weight-500">Pinjaman DANA</h6>--}}
            {{--                                        </div>--}}
            {{--                                        <div class="col-6 col-lg-6 text-right mt-1">--}}
            {{--                                            <img src="{{ asset('img/finance/dot_merah.svg') }}" alt="">--}}
            {{--                                        </div>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="col-12 col-lg-4">--}}
            {{--                                <div class="card card-body">--}}
            {{--                                    <div>--}}
            {{--                                        <h6 class="text-right" style="margin-right: 0.1rem;">MENUNGGU KONFIRMASI--}}
            {{--                                            <img class="img-fluid text-right" style="margin: auto 0 0.10rem 0.8rem;"--}}
            {{--                                                 src="{{ asset('img/finance/16-px-menunggu.png') }}" alt="">--}}
            {{--                                        </h6>--}}
            {{--                                    </div>--}}
            {{--                                    <h1 class="text-warning font-weight-bold mt-2">Rp. 10.000.000</h1>--}}
            {{--                                    <div class="row mt-3" style="margin-bottom: -1rem">--}}
            {{--                                        <div class="col-6 col-lg-6">--}}
            {{--                                            <p class="font-size-sm">6/01/2020</p>--}}
            {{--                                            <h6 class="font-weight-500">Pinjaman DANA</h6>--}}
            {{--                                        </div>--}}
            {{--                                        <div class="col-6 col-lg-6 text-right mt-1">--}}
            {{--                                            <img src="{{ asset('img/finance/dot_orange.svg') }}" alt="">--}}
            {{--                                        </div>--}}
            {{--                                    </div>--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                    <div class="tab-pane fade" id="bottom-divided-tab2">--}}
            {{--                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium assumenda beatae, dolor--}}
            {{--                        enim, error est explicabo incidunt ipsa labore natus pariatur perferendis qui quidem quis rem,--}}
            {{--                        suscipit vel veritatis voluptatem.--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--        </div>--}}

            <div class="card form-loan" style="display: none">
                <div class="card-header bg-white header-elements-inline" style="border: 0">
                    <h3 class="card-title">{!! trans('finance.step-2.title') !!}</h3>
                </div>
                {{--            <form class="wizard-form steps-validation" action="#" data-fouc>--}}
                    <form class="wizard-form steps-validation" id="steps-validate" enctype="multipart/form-data" data-fouc>
                    {{ csrf_field() }}
                    <input type="hidden" name="type_finance" value="1">
                    <h6></h6>
                    <fieldset>
                        <div class="row mt-2">
                            <div class="col-lg-12">
                                <p>{!! trans('finance.step-1.description') !!}</p>
                                <div class="form-group">
                                    <label class="font-weight-bold">{!! trans('finance.step-1.request_amount') !!} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control number format-money finance finance-input" id="amount"
                                        placeholder="IDR" maxlength="10">
                                    <input type="hidden" class="form-control amount" name="amount">
                                </div>

                                <label class="font-weight-bold">{!! trans('finance.step-1.loan_term') !!}</label>
                                <div class="card-body p-0">
                                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified nav-time">
                                        @forelse($time_finance as $item)
                                            <li class="nav-item nav-mount"><a href="#time-{{ $item->id }}"
                                                                            data-id="{{ $item->id }}"
                                                                            class="nav-link {{ Request::has('tab') && Request::get('tab')== $item->duration_time ?'active':''}}"
                                                                            data-toggle="tab"
                                                                            href="#{{ $item->duration_time }}" role="tab"
                                                                            aria-controls="nav-mypremium"
                                                                            aria-selected="false">{{ \App::getLocale() == 'id' ? $item->name_time_id : $item->name_time_en }}</a>
                                            </li>
                                            {{--                                    <li class="nav-item nav-mount"><a href="#bottom-justified-tab2" class="nav-link"--}}
                                            {{--                                                                      data-toggle="tab">2 Bulan</a></li>--}}

                                        @empty
                                            <p>empty</p>
                                        @endforelse
                                    </ul>
                                    <input type="hidden" class="form-control time_finance" name="time_finance">
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <h6></h6>
                    <fieldset>
                        <div class="row mt-2">
                            {{-- Personal --}}
                            <div class="col-lg-12">
                                <p>{!! trans('finance.step-2.description') !!}</p>
                                @if ($company->ownership_status == 'personal')
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input use-current-data" value="1">
                                                <input type="hidden" class="kyc" value="0" name="use_kyc">
                                                {!! trans('finance.step-2.documents.personal_check') !!}
                                            </label>
                                        </div>
                                    </div>
                                    @foreach (trans('finance.step-2.documents.personal') as $document)
                                        <label for="" class="d-block mt-3">{{ $document['title'] }}</label>
                                        <div class="input-group text-left custom-file-finance">
                                            <div class="custom-file input-file-container">
                                                <input type="file" class="custom-file-input loan-document" id="{{ $document['name'] }}" name="{{ $document['name'] }}"
                                                    accept="image/x-png,image/gif,image/jpeg,image/jpeg"
                                                    lang="{{ app()->getLocale() }}">
                                                <div class="form-control-feedback done" style="display: none">
                                                    <img src="{{ asset('img/finance/32-px-cek.png') }}" alt="">
                                                </div>
                                                <label class="custom-file-label" for="{{ $document['name'] }}"></label>
                                                <div class="form-control-feedback upload">
                                                    <img src="{{ asset('img/finance/32-px-belum.png') }}" alt="">
                                                    <label for="" class="no-image">{{ trans('finance.validation.not_upload_yet') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Corporate --}}
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input use-current-data" value="1">
                                                <input type="hidden" class="kyc" value="0" name="use_kyc">
                                                {!! trans('finance.step-2.documents.corporate_check') !!}
                                            </label>
                                        </div>
                                    </div>
                                    @foreach (trans('finance.step-2.documents.corporate') as $document)
                                        <label for="" class="d-block mt-3">{{ $document['title'] }}</label>
                                        <div class="input-group text-left custom-file-finance">
                                            <div class="custom-file input-file-container">
                                                <input type="file" class="custom-file-input loan-document" id="{{ $document['name'] }}" name="{{ $document['name'] }}"
                                                    accept="image/x-png,image/gif,image/jpeg,image/jpeg"
                                                    lang="{{ app()->getLocale() }}">
                                                <div class="form-control-feedback done" style="display: none">
                                                    <img src="{{ asset('img/finance/32-px-cek.png') }}" alt="">
                                                </div>
                                                <label class="custom-file-label" for="{{ $document['name'] }}"></label>
                                                <div class="form-control-feedback upload">
                                                    <img src="{{ asset('img/finance/32-px-belum.png') }}" alt="">
                                                    <label for="" class="no-image">{{ trans('finance.validation.not_upload_yet') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </fieldset>
                    <h6></h6>
                    <fieldset>
                        <hr>
                        <div class="row mt-2">
                            <div class="col-md-9 ml-md-auto">
                                <table cellpadding="15" cellspacing="5" class="conclusion-table">
                                    @foreach (trans('finance.step-3.tnc_table') as $tnc_table)
                                        <tr class="p-1">
                                            <td>{!! $tnc_table['text'] !!}</td>
                                            <td>:</td>
                                            <td>
                                                <span class="ml-2" id="{!! $tnc_table['id'] !!}">
                                                @if (isset($tnc_table['value']))
                                                    {!! $tnc_table['value'] !!}
                                                @else
                                                    -
                                                @endif
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-9 ml-md-auto">
                                <div class="form-group">
                                    <div class="form-check">
                                        {{-- hide temporary --}}
                                        {{-- <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" />
                                            {!! trans('finance.step-3.tnc_text') !!}
                                        </label> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="card submited" style="display: none">
                <div class="card-header bg-white header-elements-inline" style="border: 0">
                    <h3 class="card-title">{!! trans('finance.step-2.title') !!}</h3>
                </div>
                {{--            <form class="wizard-form steps-validation" action="#" data-fouc>--}}
                <div class="row success pt-3 pb-5">
                    <div class="col text-center mx-auto">
                        <h3>{{ trans('finance.success.title') }}</h3>
                        <p>{{ trans('finance.success.description') }}</p>
                        <button type="button" onclick="window.location.reload()" class="btn btn-primary mt-3">{{ trans('finance.finish') }}</button>
                    </div>
                </div>
            </div>
        @else
            <div class="card card-body border-top-primary banner front-page">
                <h3 class="text-center">{!! trans('finance.not_kyc.title') !!}</h3>

                <img class="img-fluid text-center" style="margin: auto; max-width: 300px"
                    src="{{ asset('img/finance/un_kyc.svg') }}" alt="">
                <br>
                <p class="col-lg-6 text-center justify-content-center"
                style="margin: auto">{!! trans('finance.not_kyc.description') !!}</p>
                <a href="{{ Route('company.kyc.index') }}" class="btn btn-primary mt-3 mx-auto" style="width: fit-content">{!! trans('finance.not_kyc.button') !!}</a>
            </div>
        @endif
    </div>

    {{--    <div id="modal_default" class="modal fade" tabindex="-1">--}}
    {{--        <div class="modal-dialog">--}}
    {{--            <div class="modal-content">--}}
    {{--                <div class="modal-header">--}}
    {{--                    <h5 class="modal-title">Basic modal</h5>--}}
    {{--                    <button type="button" class="close" data-dismiss="modal">&times;</button>--}}
    {{--                </div>--}}

    {{--                <div class="modal-body">--}}
    {{--                    <h6 class="font-weight-semibold">Text in a modal</h6>--}}
    {{--                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem. Praesent--}}
    {{--                        commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue--}}
    {{--                        laoreet rutrum faucibus dolor auctor.</p>--}}

    {{--                    <hr>--}}

    {{--                    <h6 class="font-weight-semibold">Another paragraph</h6>--}}
    {{--                    <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in,--}}
    {{--                        egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>--}}
    {{--                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel--}}
    {{--                        augue laoreet rutrum faucibus dolor auctor.</p>--}}
    {{--                </div>--}}

    {{--                <div class="modal-footer">--}}
    {{--                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>--}}
    {{--                    <button type="button" class="btn bg-primary">Save changes</button>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

@endsection
@section('additionalScript')
    <script src="{{ asset('dest-operator/reskin_global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dest-operator/reskin_global_assets/js/demo_pages/form_inputs.js') }}"></script>
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.5/js/bootstrap-select.js"></script>--}}
    {{--    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.min.js') }}"></script>--}}
    {{--    <script src="{{ asset('js/form_wizard.js') }}"></script>--}}
    <script src="{{ asset('js/steps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    <script>
        // Data -------------------------------------------------------------------------------------------
        var identity = []

        // Init -------------------------------------------------------------------------------------------
        const form = $('.steps-validation').show();
        $('#steps-validate').steps({
            headerTag: 'h6',
            bodyTag: 'fieldset',
            titleTemplate: '<span class="number">#index#</span> #title#',
            labels: {
                previous: '<i class="icon-arrow-left13 mr-2" /> {{ __('general.back') }}',
                next: '{{ __('general.next') }} <i class="icon-arrow-right14 ml-2" />',
                finish: '{!! trans('finance.submit') !!}'
            },
            transitionEffect: 'fade',
            autoFocus: true,
            onStepChanging: function (event, currentIndex, newIndex) {

                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }

                let ignore = ':disabled,:hidden';
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {

                    // To remove error styles
                    form.find('.body:eq(' + newIndex + ') label.error').remove();
                    form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                }
                switch (currentIndex) {
                    case 0:
                        ignore = ignore + ':not(.steps-validation .time_finance, .steps-validation .amount)';
                        break;
                    case 1:
                        ignore = ignore + ':not(.steps-validation .kyc)';
                        break;
                }

                form.validate().settings.ignore = ignore;
                return form.valid();
            },
            onStepChanged: function(e, currentIndex, priorIndex) {
                let btn_back_finance = $(document).find('.btn-back-finance');
                let btn_back_step = $(document).find('[aria-label="Pagination"] li:first-child');

                // For back button
                if (currentIndex === 0) {
                    btn_back_finance.show();
                    btn_back_step.hide();
                } else {
                    btn_back_finance.hide();
                    btn_back_step.show();
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ':disabled';
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                // loadingStart();
                let url = '{{ route('store.finance') }}';
                let fD = new FormData();
                $('.loading').addClass('show');
                $.ajax({
                    url : url,
                    type: 'POST',
                    dataType: 'json',
                    data: new FormData($(this)[0]),
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        // loadingFinish();
                        $('.submited').show();
                        $('.form-loan').hide();
                        $('.loading').removeClass('show');
                    },
                    error: function (e) {
                        // loadingFinish();
                        if (e.status === 422) {
                            let dt = e.responseJSON;
                            let errors = dt.errors;
                            jQuery.each(errors, function (i, e) {
                                $(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                                $(document).find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                            })
                        }
                        console.log(e);
                        $('.loading').removeClass('show');
                        if (e.responseJSON.message !== undefined) {
                            toastr.error(e.responseJSON.message, '{{__('general.whoops')}}');
                        }
                    }
                });
            }
        });

        // Event -------------------------------------------------------------------------------------------
        $(document).on('click', '#btn-loan', function () {
            $(document).find('.front-page').hide();
            $(document).find('.form-loan').show();
            $(document).find('.actions.clearfix ul').append('<li class="btn-back-finance"><button class="btn btn-light" type="button" id="btn_back_finance"><i class="icon-arrow-left13 mr-2"></i>{{ trans("finance.previous") }}</button></li>');
            $(document).find('[aria-label="Pagination"] li:first-child').hide();
        });

        $(document).on('click', '#btn_back_finance', function () {
            $(document).find('.front-page').show();
            $(document).find('.form-loan').hide();
            $(this).parent().remove();
        });

        $(document).on('click', '.btn-buy-now', function () {
            var this_id = $(this).data('id');
            $(document).find('input[name=type_finance').val(this_id);
        });

        $(document).ready(function changeFileName() {
            $(document).find('input[type="file"]').change(function (e) {
                let fileName = e.target.files[0].name;
                $(document).find('#file-name').val(fileName);
            })
        });

        $(document).on('change', '.use-current-data', function(){
            if(this.checked) {
                let url = '{{ route('check-finance-kyc') }}',
                    data = $(this).val();
                $(document).find('input[name=use_kyc]').val(1);
                use_kyc_ajax(url, data, 'check')
            }else{
                $(document).find('input[name=use_kyc]').val(0);
                remove_data(identity)
            }
        })

        $(document).on('change', '.custom-file-input', function (e) {
            let file_name = e.target.files[0].name;
            let input_container = $(this).closest('.input-file-container');
            // input_container.find('span.error-help-block').remove();
            input_container.find('.upload').hide();
            input_container.find('.done').show();
            input_container.find("label[for='" + $(this).attr('id') + "']").text(file_name);
        });

        $(document).on('change', '.finance-input', function(){
            let this_val = $(this).val();
            $(document).find('#request_amount').text('IDR ' + this_val)
        })

        $(document).on('click', '.nav-time', function () {
            let it = $(this),
                nav = it.find('a.active').data('id'),
                nav_text = it.find('a.active').text(),
                loan_term = '';
            
            $(document).find('span.error-help-block').remove();
            $(document).find('input[name=time_finance]').val(nav);
            $(document).find('#loan_term').text(nav_text);
        });

        $(document).on('change keydown', '#amount', function () {
            separator();
            $(document).find('input[name="amount"]').val($(this).val());
        });

        // General Function -------------------------------------------------------------------------------------------

        function use_kyc_ajax(url, data, type) {
            $('.loading').addClass('show');
            $.ajax({
                url: url,
                data: data,
                success: function(e) {
                    $('.loading').removeClass('show');
                    console.clear();
                    let data = e.data;

                    @if ($company->ownership_status == 'personal')
                        $.each(data, function(i, e) {
                            if (i === 'identity_card') {
                                inputData('ktp', e)
                                identity.push('ktp')
                            } else if (i === 'tax_number') {
                                inputData('npwp', e)
                                identity.push('npwp')
                            } else if (i === 'family_card') {
                                inputData('family_card', e)
                                identity.push('family_card')
                            }
                        })
                    @else
                        $.each(data, function(i, e) {
                            if (i === 'owner_identity_card') {
                                inputData('ktp', e)
                                identity.push('ktp')
                            } else if (i === 'company_tax_number') {
                                inputData('npwp', e)
                                identity.push('npwp')
                            } else if (i === 'company_business_license') {
                                inputData('siup', e)
                                identity.push('siup')
                            } else if (i === 'company_establishment_deed') {
                                inputData('founding_deed', e)
                                identity.push('founding_deed')
                            } else if (i === 'company_register_certification') {
                                inputData('company_signatures', e)
                                identity.push('company_signatures')
                            }
                        })
                    @endif
                },
                error: function(e) {
                    console.log(e)
                }
            })
        }

        function inputData(id, address) {
            let label = $('label[for=' + id + ']'),
                input_container = label.closest('.input-file-container'),
                input = input_container.find('input'),
                index = address.lastIndexOf('/'),
                filename = address.slice(index + 1);

            $('#' + id + '-error').text('')
            input.prop('disabled', true)
            input_container.find('.custom-file-label').addClass('button-disabled')
            label.text(filename)
            input_container.find('.upload').hide();
            input_container.find('.done').show();
        }

        function remove_data(id_list) {
            $('.input-file-container input').prop('disabled', false)
            id_list.map(function(e) {
                let label = $('label[for=' + e + ']'),
                    container = label.closest('.input-file-container');

                container.find('.custom-file-label').removeClass('button-disabled')
                label.text('');
                container.find('.form-control-feedback.upload').show();
                container.find('.form-control-feedback.done').hide();
            })
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $(document).find('.img-facebook-ads').attr('src', e.target.result);
                    $(document).find('img.step-5').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // $(document).on('change', '#image-file-ads', function () {
        //     readURL(this);
        // });

    </script>
    {!! JsValidator::formRequest('App\Http\Requests\FinanceRequest') !!}
@endsection
