@extends('klhk.dashboard.company.base_layout')

@section('title', 'Offline Order')

@section('additionalStyle')
    <link href="{{asset('additional/select2/css/typography.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/textfield.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2-bootstrap.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/pmd-select2.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css')}}" rel="stylesheet">
    <link href="{{asset('company/css/app.css')}}" rel="stylesheet"> 
    <style>
        .bg-light-blue {
            background-color: transparent !important;
        }

        .box-kayiz {
            -webkit-box-shadow: 0px 20px 40px 0px rgba(121, 121, 121, 0.21);
            box-shadow: 0px 20px 40px 0px rgba(121, 121, 121, 0.21);
        }

        .credit-card-tooltip {
            font-size: 1rem;
            width: auto;
            height: auto;
        }

        .parent-form-add .btn-add-detail {
            padding: 8px 10px 4px!important;
        }

        .next .footer-product {
            display: flex;
            justify-content: center;
            align-items: center
        }

        .next .footer-product .flex-1 {
            flex: 1
        }

        .next .footer-product a {
            text-decoration: none
        }

        /* Overwrite App.css temporary start */
        #offline-invoice h1.tab-title {
            color: #757575;
        }
        #offline-invoice .date, #offline-invoice .md-form .form-control {
            border: none;
            border-bottom: 1px solid #ced4da;
            border-bottom-width: 1px;
            border-bottom-style: solid;
            border-bottom-color: rgb(206, 212, 218);
        }

        /* KLHK */
        #offline-invoice #r_product_name.review {
            color: #333333;
        } 
        #offline-invoice  #r_due-date {
            color: #333333;
        } 

        @media screen and (max-width: 990px) {
            .footer-product .btn-block {
                padding: 0 0.5rem;
            }
            #offline-invoice .step-wrapper p {
                width: 80px;
            }
            #offline-invoice .step-wrapper .step-indicator.tie:before {
                width: 1rem;
                right: 59px;
            }
            #offline-invoice .step-wrapper {
                margin: 0;
            }
        }
    </style>
@stop

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
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('offline_invoice.caption') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <a href="{{ route('company.manual.index') }}" class="breadcrumb-item">
                        </i> {{trans('order_provider.order')}}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('order_provider.order_offline') }}</span>
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

        <div class="bg-light-blue" id="offline-invoice">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="progress-padding text-center">
                            <div class="step-wrapper">
                                <div class="step-indicator active bg-green-klhk">
                                    <i class="fa fa-check"></i>
                                </div>
                                <p>{{ trans('offline_invoice.progress-1') }}</p>
                            </div>
                            <div class="step-wrapper">
                                <div class="step-indicator tie">
                                    <i class="fa fa-check"></i>
                                </div>
                                <p>{{ trans('offline_invoice.progress-2') }}</p>
                            </div>
                            <div class="step-wrapper ">
                                <div class="step-indicator tie">
                                    <i class="fa fa-check"></i>
                                </div>
                                <p>{{ trans('offline_invoice.progress-3') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="div-tab active">
                <form id="offline-form" autocomplete="off">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center py-3">
                                <h1 class="tab-title">{{ trans('offline_invoice.page-1.caption') }}</h1>
                            </div>
                        </div>
                        <div class="box-kayiz">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h3>{{ trans('offline_invoice.page-1.create') }}</h3>
                                        <div class="md-form __parent_form">
                                            <input type="text" id="invoice_name" class="form-control sub-ajax"
                                                name="invoice_name"
                                                autocomplete="off" maxlength="100">
                                            <label for="product_name" class="">{{ trans('offline_invoice.page-1.product_name') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3>{{ trans('offline_invoice.page-1.deadline') }}</h3>
                                                <div class="md-form __parent_form">
                                                    <input type="text" id="invoice_date"
                                                        value="{{\Carbon\Carbon::tomorrow()->toDateString()}}"
                                                        class="form-control date-picker date sub-ajax"
                                                        name="expired_date"
                                                        autocomplete="off" required>
                                                    <label for="expired_date" class="date-label"><span
                                                                class="fa fa-calendar"></span></label>
                                                    <input type="hidden" name="_token" class="sub-ajax"
                                                        value="{{csrf_token()}}">
                                                </div>
                                            </div>
                                            @if ($company->kyc && $company->kyc->status=='approved'||$company->domain_memoria ==='basecampadventureindonesia.gomodo.id')
                                                <div class="col-12">
                                                    <input type="checkbox" value="1"
                                                        name="allow_credit_card" id="" class="sub-ajax">
                                                    <label for="">{{trans('offline_invoice.page-1.allow_credit_card')}} <i
                                                                class="fa fa-question-circle-o tooltips credit-card-tooltip"
                                                                title="{{ trans('offline_invoice.page-1.settlement') }}"></i></label>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <h3>{{ trans('offline_invoice.page-1.detail') }}</h3>
                                <div class="box-clone"></div>
                                <div class="parent-form-add">
                                    <div class="row">
                                        <div class="col-lg-11">
                                            <div class="row done init">
                                                <div class="col-lg-4">
                                                    <div class="md-form __parent_form">
                                                        <input type="text" id="description[]" class="form-control"
                                                            name="description[]"
                                                            autocomplete="off" maxlength="100">
                                                        <label for="description[]"
                                                            class="">{{ trans('offline_invoice.page-1.description') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="md-form __parent_form">
                                                        <input type="text" id="price[]" class="form-control number format-money"
                                                            name="price[]"
                                                            autocomplete="off" maxlength="9">
                                                        <label for="price[]"
                                                            class="">{{ trans('offline_invoice.page-1.price') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="md-form __parent_form">
                                                        <input type="number" id="qty[]" class="form-control number max-5"
                                                            name="qty[]"
                                                            autocomplete="off">
                                                        <label for="qty[]"
                                                            class="">{{ trans('offline_invoice.page-1.amount') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-1">
                                            <div class="md-form __parent_form">
                                                <button type="button" class="btn bg-green-klhk btn-sm btn-add-detail float-right" ><i class="fa fa-plus f-small"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-kayiz mt-3">
                            <div class="box-body">
                                <div class="row discount">
                                    <div class="col-11">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="md-form __parent_form">
                                                    <input type="text" class="form-control sub-ajax" name="discount_name" maxlength="100">
                                                    <label for="">{{ trans('offline_invoice.page-1.discount') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="md-form __parent_form">
                                                    <select name="discount_type" id=""
                                                            class="select-simple pmd-select2 sub-ajax">
                                                        <option value="percentage">{{ trans('offline_invoice.page-1.percentage') }}</option>
                                                        <option value="fixed">{{ trans('offline_invoice.page-1.fixed') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="md-form __parent_form">
                                                    <input type="text" class="form-control number sub-ajax format-money"
                                                        name="discount_nominal" maxlength="10">
                                                    <label for="">{{ trans('offline_invoice.page-1.nominal_discount') }}</label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">{{trans('customer.book.note_to_customer')}}</label>
                                                <div class="md-form __parent_form mt-0">
                                                    <textarea name="important_notes" id="" class="form-control p-3 sub-ajax"
                                                            cols="30" rows="10" maxlength="300"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="box-kayiz mt-3">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col bold text-secondary">
                                        {{ trans('offline_invoice.page-1.total') }}
                                    </div>
                                    <div class="col-auto float-right bold grandtotal text-secondary">
                                        IDR 0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-kayiz mt-3">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="footer-product">
                                                <div class="col-sm-auto float-right">
                                                    <button class="btn bg-green-klhk f-small btn-block" id="toStep2">{{ trans('offline_invoice.page-1.next') }}<i class="fa fa-chevron-right f-small"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="div-tab">
                <form id="offline-form2" autocomplete="off">
                    <div class="container-fluid">
                        <div class="box-kayiz">
                            <div class="box-body row">
                                <h3 class="col-12 mb-3 text-center">{{ trans('offline_invoice.page-2.preview') }}</h3>
                                <div class="col-12 col-sm-8">
                                    <label for="r_product_name"
                                        class="">{{ trans('offline_invoice.page-1.product_name') }}</label>
                                    <br>
                                    <span class="riview text-secondary" id="r_product_name"></span>
                                </div>
                                <div  class="col-12 col-sm-4">
                                    <label for="r_due-date">{{ trans('offline_invoice.page-1.deadline') }}</label><br>
                                    <span id="r_due-date"></span>
                                </div>
                                <div class="col-12 mt-5 overflow-auto">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ trans('offline_invoice.page-1.description') }}</th>
                                                <th scope="col">{{ trans('offline_invoice.page-1.amount') }}</th>
                                                <th scope="col">{{ trans('offline_invoice.page-1.price') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="appended-table-riview">
    {{--                                        <tr>--}}
    {{--                                            <th class="row-first" scope="row"></th>--}}
    {{--                                            <td class="row-second">Otto</td>--}}
    {{--                                            <td class="row-third">@mdo</td>--}}
    {{--                                        </tr>--}}
    {{--                                        <tr>--}}
    {{--                                            <th scope="row">2</th>--}}
    {{--                                            <td>Thornton</td>--}}
    {{--                                            <td>@fat</td>--}}
    {{--                                        </tr>--}}

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-kayiz  mt-3">
                            <div class="box-body">
                                <h3>{{ trans('offline_invoice.page-2.info') }}</h3>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <input type="text" id="full_name" class="form-control sub-ajax" name="full_name"
                                                autocomplete="off" maxlength="50">
                                            <label for="full_name"
                                                class="">{{ trans('offline_invoice.page-2.full_name') }} *</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <input type="text" id="phone_number" class="form-control phone number sub-ajax"
                                                name="phone_number"
                                                autocomplete="off" maxlength="13">
                                            <label for="phone_number"
                                                class="">{{ trans('offline_invoice.page-2.phone') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <input type="email" id="email" class="form-control sub-ajax" name="cust_email"
                                                autocomplete="off" maxlength="50"/>
                                            <label for="email" class="">{{ trans('offline_invoice.page-2.email') }}
                                                *</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-kayiz mt-3">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col bold text-secondary">
                                        {{ trans('offline_invoice.page-1.total') }}
                                    </div>
                                    <div class="col-auto float-right bold grandtotal text-secondary">
                                        IDR 0
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </form>
                <div class="container-fluid next">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-kayiz mt-3">
                                <div class="box-body">
                                    <div class="row footer-product">
                                        <div class="col-lg">
                                            <a href="" class="f-small"><i
                                                        class="fa fa-close f-small"></i>{{ trans('offline_invoice.page-2.cancel') }}
                                            </a>
                                        </div>
                                        <div class="col-lg-auto float-right">
                                            <a href="" class="f-small back" id="previous-button"><i
                                                        class="fa fa-chevron-left f-small"></i>{{ trans('offline_invoice.page-2.previous') }}
                                            </a>
                                        </div>
                                        <div class="col-lg-auto float-right">
                                            <button class="btn bg-green-klhk f-small btn-block" id="toStep3"><img
                                                        class="mail-send"
                                                        src="{{ asset('assets/app/media/img/files/mail-send.svg') }}">{{ trans('offline_invoice.page-2.send') }}
                                                <i
                                                        class="fa fa-chevron-right f-small"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="div-tab">
                <form id="offline-form" autocomplete="off">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center py-3">
                                <h1 class="tab-title">{{ trans('offline_invoice.page-3.caption') }}</h1>
                            </div>
                        </div>
                        <div class="box-kayiz">
                            <div class="box-body">
                                <img id="invoice-posted" src="{{ asset('assets/app/media/img/files/invoice-posted.svg') }}"
                                    alt="">
                            </div>
                            <h2 class="desc-title text-dark">{{ trans('offline_invoice.page-3.caption_content') }}</h2>
                            <h3 class="desc-content">{{ trans('offline_invoice.page-3.desc_content') }}
                                <br>{{ trans('offline_invoice.page-3.desc_content2') }}</h3>
                        </div>
                    </div>
                </form>
                <div class="container-fluid next">
                    <div class="row">
                        <div class="col-12">
                            <div class="box-kayiz mt-3">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="footer-product">
                                                <div class="flex-1">
                                                    <a href="{{route('company.manual.index')}}" class="f-small float-right">{{ trans('offline_invoice.page-3.inv_list') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('additionalScript')
    <script src="{{asset('additional/select2/js/global.js')}}"></script>
    <script src="{{asset('additional/select2/js/textfield.js')}}"></script>
    <script src="{{asset('additional/select2/js/select2.full.js')}}"></script>
    <script src="{{asset('additional/select2/js/pmd-select2.js')}}"></script>
    <script>
        // Data -------------------------------------------------------------------------------------------

        let endPercent = '';
        let order = {
            _token: '{{csrf_token()}}',
            details: [],
            invoice_name: '',
            expired_date: '{{\Carbon\Carbon::tomorrow()->toDateString()}}',
            discount_name: null,
            discount_amount_type: 'percentage',
            discount_amount: null,
            full_name: '',
            phone_number: '',
            email: '',
            address: '',
            country: '',
            city: '',
            allow_credit_card: 0,
            important_notes: ''
        };
        let total = 0;
        let page = 1;
        let submit = false;
        let date = new Date();
        date.setDate(date.getDate()+1);

        // Init -------------------------------------------------------------------------------------------

        $(".select-simple").select2({
            theme: "bootstrap",
            width: '100%',
            minimumResultsForSearch: Infinity,
        });

        $($('.select-simple')[1]).select2({
            theme: "bootstrap",
            width: '100%',
            placeholder: '{{ trans('offline_invoice.page-2.country') }}',
        });

        $($('.select-simple')[2]).select2({
            theme: "bootstrap",
            width: '100%',
            placeholder: '{{ trans('offline_invoice.page-2.city') }}',
        });

        // Datepicker
        @if(app()->getLocale() == 'id')
        $('.date-picker').datepicker({
            startDate: date,
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: false,
            language: 'id'
        });
        @else
        $('.date-picker').datepicker({
            startDate: date,
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: false,
            language: 'en'
        });
        @endif

        // $('.date-picker').datepicker({
        //     startDate: date,
        //     format: 'yyyy-mm-dd',
        //     autoclose: true,
        //     todayHighlight: false,
        // })

        // Events -------------------------------------------------------------------------------------------

        $(document).on('click', '.btn-add-detail', function () {
            let t = $(this);
            let row = t.closest('.row');
            row.find('label.error').remove();

            if (row.find('input[name="description[]"]').val() === '' || row.find('input[name="price[]"]').val() === '' || row.find('input[name="qty[]"]').val() === '') {
                if (row.find('input[name="description[]"]').val() === '') {
                    row.find('input[name="description[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                }
                if (row.find('input[name="price[]"]').val() === '') {
                    row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                }
                if (row.find('input[name="qty[]"]').val() === '') {
                    row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                }
            } else {
                if (parseFloat(row.find('input[name="price[]"]').val()) < 1 || parseFloat(row.find('input[name="qty[]"]').val()) < 1) {
                    if (parseFloat(row.find('input[name="price[]"]').val()) < 1) {
                        row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>')
                    }
                    if (parseFloat(row.find('input[name="qty[]"]').val()) < 1) {
                        row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>')
                    }
                } else {
                    let html = '';
                    html += ' <div class="row done"> <div class="col-lg-11">\n' +
                        '                                <div class="row">\n' +
                        '                                    <div class="col-lg-4">\n' +
                        '                                        <div class="md-form __parent_form">\n' +
                        '                                            <input type="text" id="description" value="' + t.closest('.row').find('input[name="description[]"]').val() + '" class="form-control" name="description[]"\n' +
                        '                                                   autocomplete="off">\n' +
                        '                                            <label for="description" class="active"> {{ trans('offline_invoice.page-1.description') }} </label>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                    <div class="col-lg-4">\n' +
                        '                                        <div class="md-form __parent_form">\n' +
                        '                                            <input type="text" id="price" value="' + t.closest('.row').find('input[name="price[]"]').val() + '" class="form-control number format-money" name="price[]"\n' +
                        '                                                   autocomplete="off" maxlength="9">\n' +
                        '                                            <label for="price" class="active">{{ trans('offline_invoice.page-1.price') }}</label>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                    <div class="col-lg-4">\n' +
                        '                                        <div class="md-form __parent_form">\n' +
                        '                                            <input type="number" id="amount" value="' + t.closest('.row').find('input[name="qty[]"]').val() + '" class="form-control number" name="qty[]"\n' +
                        '                                                   autocomplete="off">\n' +
                        '                                            <label for="amount" class="active">{{ trans('offline_invoice.page-1.amount') }}</label>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-lg-1">\n' +
                        '                                <div class="md-form __parent_form">\n' +
                        '                                    <button type="button" class="btn btn-danger btn-sm btn-remove-detail float-right"\n' +
                        '                                            style="padding: 10px 10px 4px"><i\n' +
                        '                                                class="fa fa-trash f-small"></i></button>\n' +
                        '                                </div>\n' +
                        '                            </div></div>';
                    $('.box-clone').append(html);
                    t.closest('.row').find('input').val('')
                    t.closest('.row').find('label').removeClass('active')
                    calculate();
                    $(document).find('.init label.error').remove();
                }
            }
        });

        // Remove Button
        $(document).on('click', '.btn-remove-detail', function () {
            $(this).closest('.row').remove();
            calculate();
        });

        // Remove Label Eror when Change Value
        $(document).on('change keydown paste focus', '.form-control', function () {
            $(this).closest('.md-form').find('label.error').remove();
        });

        // Call Calculate Function when Change Value
        $(document).on('change paste', '.done .form-control, .discount input, .discount select ', function () {
            calculate();
        });

        // Next to Step2 button
        $(document).on('click', '#toStep2', function () {
            let appendedToTable = $('#appended-table-riview');
            appendedToTable.html('');
            $(document).find('.error').remove();
            let lengthDone = $(document).find('.done').length;

            if (validate()) {
                page = page + 1;
            } else {
                // if (lengthDone === 0) {
                //     $(document).find('input[name="description[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.save') }}</label>')
                //     $(document).find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.save') }}</label>')
                //     $(document).find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.save') }}</label>')
                // }
                toastr.error('{{ trans('offline_invoice.error.must_completed') }}','{{__('general.whoops')}}')
            }

            for(let i = 0; i < lengthDone; i++){
                let tableAppend  =  '<tr class="dynamic-data">' +
                    '<th class="row-first" id="rf'+i+'"></th>'+
                    '<td class="row-second" id="rs'+i+'"></th>'+
                    '<td class="row-third format-money" id="rt'+i+'"></th>'+
                    '</tr>';
                appendedToTable.append(tableAppend);
            }

            let productName = $('#invoice_name').val();
            let invoiceDate = $('#invoice_date').val();
            $('#r_product_name').text(productName);
            $('#r_due-date').text(invoiceDate);

            let totalPriceRiview = 0;
            let discountAppend = '<tr id="discount-dynamic">' +
                '<th colspan="2">{{ trans("offline_invoice.page-2.discount") }}</th>'+
                '<td><span id="discount-price-riview"></span></td>'+
                '</tr>';
            let inputDiscount = $('input[name="discount_nominal"]').val();
            if(inputDiscount !== ''){
                appendedToTable.append(discountAppend);
            }else{
                $('#discount-dynamic').remove();
            }

            let totalAppend =   '<tr id="price-dynamic">' +
                '<th colspan="2">Total</th>'+
                '<td><span id="total-price-riview"></span></td>'+
                '</tr>';
            appendedToTable.append(totalAppend);

            $(document).find('.done').each(function (i, e) {
                separator();
                let element = $(e);
                let descriptionValue =element.find('input[name="description[]"]').val();
                let priceValue = element.find('input[name="price[]"]').val();
                let quantityValue = element.find('input[name="qty[]"]').val();
                let discountValue = parseInt($(document).find('input[name=discount_nominal]').val());
                totalPriceRiview += parseInt(priceValue * quantityValue);
                $('#total-price-riview').text(total.formatMoney());
                $('#discount-price-riview').text(discountValue + endPercent);
                $('#rf'+i+'').text(descriptionValue);
                $('#rs'+i+'').text(quantityValue);
                $('#rt'+i+'').text(parseInt(priceValue).formatMoney());
            });
        });

        $('#previous-button').on('click', function () {
            $('.dynamic-data').remove();
            $('#price-dynamic').remove();
            $('#discount-dynamic').remove();
        });

        // Next to Step3 button
        $(document).on('click', '#toStep3', function () {
            $(document).find('.error').remove();
            if (validate_2() && email_validation()) {
                if (!submit) {
                    loadingStart();
                    submit = true;
                    $.ajax({
                        url: "{{route('company.post.manual-order')}}",
                        data: order,
                        type: 'POST',
                        dataType: 'json',
                        success: function (data) {
                            loadingFinish();
                            submit = false;
                            // toastr.success(data.message, 'Success');
                            console.log('toastr success:', data.message)
                            $($('.step-wrapper')[2]).find('.step-indicator').addClass('active');
                            $('#toStep3').closest('.div-tab').removeClass('active');
                            $($('.div-tab')[2]).addClass('active');
                            order = {
                                _token: '{{csrf_token()}}',
                                details: [],
                                invoice_name: '',
                                expired_date: '{{\Carbon\Carbon::tomorrow()->toDateString()}}',
                                discount_name: null,
                                discount_amount_type: 'percentage',
                                discount_amount: null,
                                full_name: '',
                                phone_number: '',
                                email: '',
                                address: '',
                                country: '',
                                city: ''
                            };
                            page = 1;
                        },
                        error: function (e) {
                            loadingFinish();
                            submit = false;
                            if (e.status !== undefined && e.status === 422) {
                                let errors = e.responseJSON.errors;
                                console.log(errors);
                                $.each(errors, function (i, el) {
                                    $(document).find('input[name=' + i + ']').closest('.md-form').append('<label class="error">' + el[0] + '</label>')
                                    $(document).find('textarea[name=' + i + ']').closest('.md-form').append('<label class="error">' + el[0] + '</label>')
                                })
                                $('a#previous-button').click();

                            } else {
                                toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                            }
                        }
                    })
                }
            } else {
                toastr.error('{{ trans('offline_invoice.error.must_completed') }}','{{__('general.whoops')}}')
            }
        })

        // Back Button
        $(document).on('click', '.back', function (e) {
            e.preventDefault();
            $($('.step-wrapper')[1]).find('.step-indicator').removeClass('active');
            $($('.div-tab')[0]).addClass('active');
            $($('.div-tab')[1]).removeClass('active');
        })
        
        // Functions -------------------------------------------------------------------------------------------

        // Loader
        function loadingStart() {
            $('.loading').addClass('show');
            $('button').attr('disabled', true);
            $('button').prop('disabled', true);
        }

        function loadingFinish() {
            $('.loading').removeClass('show');
            $('button').attr('disabled', false);
            $('button').prop('disabled', false);
        }

        // Calculate Function
        function calculate() {
            $(document).find('label.error').remove();
            order.discount_name = null;
            order.discount_amount_type = 'percentage';
            order.discount_amount = null;
            order.important_notes = $('textarea[name=important_notes]').val().replace(/\r\n|\r|\n/g,"<br/>");
            order.allow_credit_card = $('input[name=allow_credit_card]').is(':checked') ? 1 : 0;
            let price = 0;
            let valid = true;
            let detail = [];
            separator();
            if ($(document).find('.done').length > 1) {
                $(document).find('.done').each(function (i, e) {
                    let row = $(e);
                    if (row.find('input[name="description[]"]').val() === '' || row.find('input[name="price[]"]').val() === '' || row.find('input[name="qty[]"]').val() === '') {
                        if (row.find('input[name="description[]"]').val() === '') {
                            row.find('input[name="description[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                            valid = false;
                        }
                        if (row.find('input[name="price[]"]').val() === '') {
                            row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                            valid = false;
                        }
                        if (row.find('input[name="qty[]"]').val() === '') {
                            row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                            valid = false;
                        }
                    } else {
                        if (parseFloat(row.find('input[name="price[]"]').val()) < 1 || parseFloat(row.find('input[name="qty[]"]').val()) < 1) {
                            if (parseFloat(row.find('input[name="price[]"]').val()) < 1) {
                                row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>')
                                valid = false;
                            }
                            if (parseFloat(row.find('input[name="qty[]"]').val()) < 1) {
                                row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>')
                                valid = false;
                            }
                        } else {
                            price += (row.find('input[name="price[]"]').val() * row.find('input[name="qty[]"]').val())
                            detail.push({
                                description: row.find('input[name="description[]"]').val(),
                                price: parseFloat(row.find('input[name="price[]"]').val()),
                                qty: parseInt(row.find('input[name="qty[]"]').val())
                            })
                        }
                    }
                })
                order.details = detail;
                if ($('input[name=discount_name]').val() === '' || $('input[name=discount_nominal]').val() === '') {

                } else {
                    if ($('input[name=discount_name]').val() === '') {
                        $('input[name=discount_name]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                        valid = false;
                    }
                    if ($('input[name=discount_nominal]').val() === '') {
                        $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                        valid = false;
                    } else {
                        if (parseFloat($('input[name=discount_nominal]').val()) < 1) {
                            $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>');
                            valid = false;
                        }
                    }
                }
                if ($('input[name=discount_name]').val() !== '' && $('input[name=discount_nominal]').val() !== '') {
                    if ($('select[name=discount_type]').val() === 'percentage') {
                        if (parseInt($('input[name=discount_nominal]').val()) >= 100) {
                            $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">Too Much</label>');
                            valid = false;
                        } else {
                            order.discount_name = $('input[name=discount_name]').val();
                            order.discount_amount = parseFloat($('input[name=discount_nominal]').val());
                            order.discount_amount_type = $('select[name=discount_type]').val();
                            price = price - (parseInt($('input[name=discount_nominal]').val()) / 100) * price;
                        }
                        endPercent = ' %';
                    } else {
                        if ($('input[name=discount_nominal]').val() >= price) {
                            valid = false;
                            $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">Too Much</label>');
                        } else {
                            price = price - $('input[name=discount_nominal]').val();
                            order.discount_name = $('input[name=discount_name]').val();
                            order.discount_amount = parseFloat($('input[name=discount_nominal]').val());
                            order.discount_amount_type = $('select[name=discount_type]').val();
                        }
                        endPercent = '';
                    }
                } else if ($('input[name=discount_name]').val() === '' && $('input[name=discount_nominal]').val() !== '') {
                    $('input[name=discount_name]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                    valid = false;
                } else if ($('input[name=discount_name]').val() !== '' && $('input[name=discount_nominal]').val() === '') {
                    $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                    valid = false;
                }

                order.invoice_name = $('input[name=invoice_name]').val();
                order.expired_date = $('input[name=expired_date]').val();
                $('.grandtotal').html('IDR ' + price.formatMoney());
                total = price;
                unSeparator();
                return valid;
            } else {
                let row = $('.btn-add-detail').closest('.row');
                if (row.find('input[name="description[]"]').val() === '' || row.find('input[name="price[]"]').val() === '' || row.find('input[name="qty[]"]').val() === '') {
                    if (row.find('input[name="description[]"]').val() === '') {
                        row.find('input[name="description[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                    }
                    if (row.find('input[name="price[]"]').val() === '') {
                        row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                    }
                    if (row.find('input[name="qty[]"]').val() === '') {
                        row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                    }
                    $('.grandtotal').html('IDR ' + 0);
                    unSeparator();
                    return false;
                } else {
                    $(document).find('.done').each(function (i, e) {
                        let row = $(e);
                        if (row.find('input[name="description[]"]').val() === '' || row.find('input[name="price[]"]').val() === '' || row.find('input[name="qty[]"]').val() === '') {
                            if (row.find('input[name="description[]"]').val() === '') {
                                row.find('input[name="description[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                                valid = false;
                            }
                            if (row.find('input[name="price[]"]').val() === '') {
                                row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                                valid = false;
                            }
                            if (row.find('input[name="qty[]"]').val() === '') {
                                row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>')
                                valid = false;
                            }
                        } else {
                            if (parseFloat(row.find('input[name="price[]"]').val()) < 1 || parseFloat(row.find('input[name="qty[]"]').val()) < 1) {
                                if (parseFloat(row.find('input[name="price[]"]').val()) < 1) {
                                    row.find('input[name="price[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>')
                                    valid = false;
                                }
                                if (parseFloat(row.find('input[name="qty[]"]').val()) < 1) {
                                    row.find('input[name="qty[]"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>')
                                    valid = false;
                                }
                            } else {
                                price += (row.find('input[name="price[]"]').val() * row.find('input[name="qty[]"]').val())
                                detail.push({
                                    description: row.find('input[name="description[]"]').val(),
                                    price: parseFloat(row.find('input[name="price[]"]').val()),
                                    qty: parseInt(row.find('input[name="qty[]"]').val())
                                })
                            }
                        }
                    });
                    order.details = detail;
                    if ($('input[name=discount_name]').val() === '' || $('input[name=discount_nominal]').val() === '') {

                    } else {
                        if ($('input[name=discount_name]').val() === '') {
                            $('input[name=discount_name]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                            valid = false;
                        }
                        if ($('input[name=discount_nominal]').val() === '') {
                            $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                            valid = false;
                        } else {
                            if (parseFloat($('input[name=discount_nominal]').val()) < 1) {
                                $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.value1') }}</label>');
                                valid = false;
                            }
                        }
                    }
                    if ($('input[name=discount_name]').val() !== '' && $('input[name=discount_nominal]').val() !== '') {
                        if ($('select[name=discount_type]').val() === 'percentage') {
                            if (parseInt($('input[name=discount_nominal]').val()) >= 100) {
                                $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">Too Much</label>');
                                valid = false;
                            } else {
                                order.discount_name = $('input[name=discount_name]').val();
                                order.discount_amount = parseFloat($('input[name=discount_nominal]').val());
                                order.discount_amount_type = $('select[name=discount_type]').val();
                                price = price - (parseInt($('input[name=discount_nominal]').val()) / 100) * price;
                                endPercent = ' %';
                            }
                        } else {
                            if ($('input[name=discount_nominal]').val() >= price) {
                                valid = false;
                                $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">Too Much</label>');
                            } else {
                                price = price - $('input[name=discount_nominal]').val();
                                order.discount_name = $('input[name=discount_name]').val();
                                order.discount_amount = parseFloat($('input[name=discount_nominal]').val());
                                order.discount_amount_type = $('select[name=discount_type]').val();
                            }
                            endPercent = '';
                        }
                    } else if ($('input[name=discount_name]').val() === '' && $('input[name=discount_nominal]').val() !== '') {
                        $('input[name=discount_name]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                        valid = false;
                    } else if ($('input[name=discount_name]').val() !== '' && $('input[name=discount_nominal]').val() === '') {
                        $('input[name=discount_nominal]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                        valid = false;
                    }

                    order.invoice_name = $('input[name=invoice_name]').val();
                    order.expired_date = $('input[name=expired_date]').val();
                    $('.grandtotal').html('IDR ' + price.formatMoney());
                    total = price;
                    unSeparator();
                    return valid;
                }

            }
        }

        // Step Validation
        function validate() {
            let valid = true;
            if ($('input[name=invoice_name]').val() === '') {
                $('input[name=invoice_name]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                valid = false;
            }
            if ($('input[name=expired_date]').val() === '') {
                $('input[name=expired_date]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                valid = false;
            }
            if (valid && calculate()) {
                $($('.step-wrapper')[1]).find('.step-indicator').addClass('active');
                $('.div-tab').removeClass('active');
                $($('.div-tab')[1]).addClass('active');
            }
            return (valid && calculate());
        }

        function validate_2() {
            let valid = true;
            if ($('input[name=full_name]').val() === '') {
                $('input[name=full_name]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                valid = false;
            }
            if ($('input[name="cust_email"]').val() === '') {
                $('input[name="cust_email"]').closest('.md-form').append('<label class="error">{{ trans('offline_invoice.error.field') }}</label>');
                valid = false;
            }
            order.full_name = $('input[name=full_name]').val();
            order.phone_number = $('#phone_number').val();
            order.email = $('input[name="cust_email"]').val();
            return valid;
        }

        // E-Mail Validation
        function email_validation() {
            let valid = true;
            let $email = $('input[name="cust_email"]').val();
            let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!re.test($email)) {
                $('input[name="cust_email"]').closest('.md-form').append('<label class="error">Invalid email address</label>');
                valid = false;
            }
            return valid;
        }
    </script>
@stop
