@extends('klhk.dashboard.company.base_layout')

@section('title', 'Offline Order')

@section('additionalStyle')
    <link href="{{asset('additional/select2/css/typography.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/textfield.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2-bootstrap.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/pmd-select2.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('company/css/app.css') }}" rel="stylesheet">
    <style>
        .bg-light-blue {
            background-color: transparent !important;
        }
        .box-kayiz {
            -webkit-box-shadow: 0px 20px 40px 0px rgba(121, 121, 121, 0.21);
            box-shadow: 0px 20px 40px 0px rgba(121, 121, 121, 0.21);
        }
        /* Overwrite App.css temporary start */
        #offline-invoice h1.tab-title {
            color: #757575;
        }

        .select2-container--bootstrap.select2-container--disabled .select2-selection, .select2-container--bootstrap.select2-container--disabled .select2-selection--multiple .select2-selection__choice {
            background-color: transparent;
            cursor: default;
        }
    </style>
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
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('offline_invoice.page-1.detail') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <a href="{{ route('company.manual.index') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{trans('order_provider.order')}}
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
            <div class="div-tab active">
                <div class="row">
                    <div class="col-12 text-center py-3">
                        <h1 class="tab-title">{{ trans('offline_invoice.page-1.caption') }}</h1>
                    </div>
                </div>
                <form id="offline-form2" autocomplete="off">
                    <div class="container-fluid mt-3">
                        <div class="box-kayiz">
                            <div class="box-body">
                                <div class="col-12 text-right">
                                    @if (isset($order->customer_manual_transfer) && $order->status == 0)
                                        <span class="text-danger font-weight-bold text-uppercase badge-status">{{ $order->listManualTransfer()[$order->customer_manual_transfer->status] }}</span>
                                    @else
                                        @if($order->status =='1')
                                            <span class="text-success font-weight-bold ml-1 text-uppercase badge-status">{{$order->statusText}}</span>
                                        @elseif($order->status =='0')
                                            <span class="text-secondary font-weight-bold ml-1 text-uppercase badge-status">{{$order->statusText}}</span>
                                        @else
                                            <span class="text-danger font-weight-bold ml-1 text-uppercase badge-status">{{$order->statusText}}</span>
                                        @endif
                                    @endif
                                </div>
                                <h3>{{ trans('offline_invoice.page-2.info') }}</h3>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <input type="text" id="full_name" class="form-control sub-ajax" name="full_name"
                                                readonly value="{{$order->customer_info->first_name}}"
                                                autocomplete="off">
                                            <label for="full_name"
                                                class="">{{ trans('offline_invoice.page-2.full_name') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <input type="text" id="phone_number" class="form-control number sub-ajax"
                                                name="phone_number"
                                                readonly value="{{$order->customer_info->phone}}"
                                                autocomplete="off">
                                            <label for="phone_number"
                                                class="">{{ trans('offline_invoice.page-2.phone') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <input type="email" id="email" class="form-control sub-ajax" name="email"
                                                readonly value="{{$order->customer_info->email}}"
                                                autocomplete="off">
                                            <label for="email" class="">{{ trans('offline_invoice.page-2.email') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <form id="offline-form" autocomplete="off">
                    <div class="container-fluid mt-3">
                        <div class="box-kayiz">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="md-form __parent_form">
                                            <input type="text" id="invoice_name" class="form-control sub-ajax" readonly
                                                name="invoice_name"
                                                value="{{$order->order_detail->product->product_name}}"
                                                autocomplete="off">
                                            <label for="product_name"
                                                class="">{{ trans('offline_invoice.page-1.product_name') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="md-form __parent_form">
                                            <label for="">{{ trans('offline_invoice.page-1.deadline') }}</label>
                                            <input type="text"
                                                value="{{$order->payment->expiry_date}}"
                                                class="form-control sub-ajax" name="expired_date"
                                                readonly
                                                autocomplete="off" required>
                                            <label for="expired_date" class="date-label"><span
                                                        class="fa fa-calendar"></span></label>
                                            <input type="hidden" name="_token" class="sub-ajax" value="{{csrf_token()}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="parent-form-add">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            @foreach($order->invoice_detail as $invoice)
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="md-form __parent_form">
                                                            <input type="text" id="description[]" class="form-control"
                                                                name="description[]"
                                                                value="{{$invoice['description']}}" readonly
                                                                autocomplete="off">
                                                            <label for="description[]"
                                                                class="">{{ trans('offline_invoice.page-1.description') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="md-form __parent_form">
                                                            <input type="text" id="price[]" class="form-control number format-money"
                                                                name="price[]"
                                                                value="{{$invoice['price']}}" readonly
                                                                autocomplete="off">
                                                            <label for="price[]"
                                                                class="">{{ trans('offline_invoice.page-1.price') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="md-form __parent_form">
                                                            <input type="number" id="qty[]" class="form-control"
                                                                name="qty[]"
                                                                value="{{$invoice['qty']}}" readonly
                                                                autocomplete="off">
                                                            <label for="qty[]"
                                                                class="">{{ trans('offline_invoice.page-1.amount') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($order->order_detail->discount_name && $order->order_detail->discount_amount>0 || $order->external_notes)
                        <div class="box-kayiz mt-3">
                            <div class="box-body">
                                <div class="row discount">
                                    <div class="col-12">
                                        @if ($order->order_detail->discount_name && $order->order_detail->discount_amount>0)
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="md-form __parent_form">
                                                    <input type="text"
                                                            value="{{$order->order_detail->discount_name}}" readonly
                                                            class="form-control sub-ajax" name="discount_name">
                                                    <label for="">{{ trans('offline_invoice.page-1.discount') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="md-form __parent_form">
                                                    <select name="discount_type" id=""
                                                            class="select-simple pmd-select2 sub-ajax" disabled>
                                                        @if($order->order_detail->discount_amount_type=='1')
                                                            <option value="percentage">{{ trans('offline_invoice.page-1.percentage') }}</option>
                                                        @else
                                                            <option value="fixed">{{ trans('offline_invoice.page-1.fixed') }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="md-form __parent_form">
                                                    <input type="text" class="form-control number sub-ajax format-money"
                                                            value="{{$order->order_detail->discount_amount}}" readonly
                                                            name="discount_nominal">
                                                    <label for="">{{ trans('offline_invoice.page-1.nominal_discount') }}</label>
                                                </div>
                                            </div>

                                        </div>
                                        @endif
                                        @if ($order->external_notes)
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">{{trans('customer.book.note_to_customer')}}</label>
                                                <div class="md-form __parent_form mt-0">
                                                    <textarea name="important_notes" id="" class="form-control p-3 sub-ajax" cols="30" rows="10" readonly>{{ str_replace('<br/>', "\n", $order->external_notes) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="box-kayiz mt-3">
                            <div class="box-body text-secondary">
                                <div class="row mb-3">
                                    <div class="col bold">
                                        {{ trans('offline_invoice.page-1.total') }}
                                    </div>
                                    <div class="col-auto float-right bold grandtotal">
                                        {{format_priceID($order->amount,'IDR')}}
                                    </div>
                                </div>
                                @if($order->fee_credit_card >0)
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ trans('offline_invoice.page-1.cc_fee') }}
                                        </div>
                                        <div class="col-auto float-right bold grandtotal">
                                            {{format_priceID($order->fee_credit_card,'IDR')}}
                                        </div>
                                    </div>
                                @endif
                                @if($order->product_discount>0)
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ trans('offline_invoice.page-1.discount') }}
                                        </div>
                                        <div class="col-auto float-right bold grandtotal">
                                            {{format_priceID(-($order->product_discount),'IDR')}}
                                        </div>
                                    </div>
                                @endif
                                @if($order->voucher_amount>0)
                                    <div class="row mb-3">
                                        <div class="col">
                                            {{ 'Voucher : '.$order->voucher->voucher_code }}
                                        </div>
                                        <div class="col-auto float-right bold grandtotal">
                                            {{format_priceID(-($order->voucher_amount),'IDR')}}
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col bold">
                                        {{ trans('offline_invoice.page-1.grandtotal') }}
                                    </div>
                                    <div class="col-auto float-right bold grandtotal">
                                        {{format_priceID($order->total_amount,'IDR')}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if (!empty($order->customer_manual_transfer))
                <form id="offline-form3" autocomplete="off" method="POST">
                    {{ csrf_field() }}
                    <div class="container-fluid mt-3">
                        <div class="box-kayiz">
                            <div class="box-body">
                                <h3>Info Manual Transfer</h3>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="md-form __parent_form">
                                            <input type="text" class="form-control" disabled 
                                            value="{{ optional($order->customer_manual_transfer)->bank_name }}">
                                            <label>{!! trans('customer.bca_manual.sender_account_name') !!}</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="md-form __parent_form">
                                            <input type="text" class="form-control" disabled
                                            value="{{ optional($order->customer_manual_transfer)->no_rekening }}">
                                            <label>{!! trans('customer.bca_manual.account_number') !!}</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group mt-2">
                                            <label>{!! trans('customer.bca_manual.proof_payment') !!}</label>
                                            <div class="input-group">
                                                @if (!empty($order->customer_manual_transfer->upload_document))
                                                <img style="width: 80%" src="{{ asset($order->customer_manual_transfer->upload_document) }}" class="img-fluid" alt="">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label>{!! trans('customer.bca_manual.note_to_customer') !!} *</label>
                                            <div class="input-group">
                                                <textarea name="note_customer" class="form-control" id="" cols="30" rows="5"
                                                    {{ $order->customer_manual_transfer->status != 'need_confirmed' && $order->customer_manual_transfer->status != 'customer_reupload' ? 'disabled' : '' }}
                                                    >{!! $order->customer_manual_transfer->note_customer ? str_replace('<br/>',"\n",$order->customer_manual_transfer->note_customer) : ''  !!}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group mt-2">
                                            <label>Status *</label>
                                            <div class="input-group">
                                                <select name="status" class="form-control" id="status">
                                                    <option value="" selected>- {!! trans('customer.bca_manual.select_status') !!} -</option>
                                                    @foreach ($order->changeManualTransfer() as $key => $item)
                                                        <option value="{{ $key }}" {{ $key == $order->customer_manual_transfer->status ? 'selected' : '' }}>{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if (in_array($order->customer_manual_transfer->status, ['need_confirmed','customer_reupload']))
                                            <div class="form-group mt-4">
                                                <button type="button" id="btn-manual-transfer"
                                                class="btn btn-lg btn-primary">{!! trans('booking.submit') !!}</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

    <script>
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
            city: ''
        };
        let page = 1;

        // Disable input char in input type number
        $(document).on("keydown", ".number", function (e) {
            // Allow: backspace, delete, tab, escape, enter and .(190)
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 187 && (e.shiftKey === true || e.metaKey === true)) ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode === 189 && (e.shiftKey === false || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 109) || (e.keyCode === 106 || e.keyCode === 110)) {
                e.preventDefault();
            }
        });


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

        @if (!empty($order->customer_manual_transfer))
            $(document).on('click','#btn-manual-transfer', function () {
                loadingStart();
                let t = $(this);
                t.closest('form').find('span.error').remove();
                $.ajax({
                    url: '{{ route('company.order.status_manual_transfer', ['invoice_no' => $order->invoice_no]) }}',
                    type:'post',
                    dataType:'json',
                    data:t.closest('form').serialize(),
                    success:function (data) {
                        loadingFinish();
                        toastr.success(data.message,"Yey");
                        swal({
                            title: "Success",
                            text: data.message,
                            type: "success",
                        }).then(function () {
                            location.reload()
                        });
                    },
                    error:function (e) {
                        if (e.status !== undefined && e.status === 422) {
                            let errors = e.responseJSON.errors;
                            $.each(errors, function (i, el) {
                                t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                                t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                                t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                            })

                        }
                        loadingFinish();
                    }
                })
            })
        @endif

    </script>
@stop
