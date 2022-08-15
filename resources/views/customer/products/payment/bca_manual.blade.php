@extends('customer.master.index')
@section('additionalStyle')
    <link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet"
          media="screen,projection">
    <style>
        #preview-frame {
            width: 100%;
        }

        .card .card-header img {
            height: auto;
        }

        #booking-pay-now {
            margin: auto;
            display: block;
        }
    </style>
@stop
@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                @if(request()->has('ref') && request('ref') ==='directory')
                    @php
                        if (request()->isSecure()){
                        $prefix = 'https://';
                        }else{
                        $prefix = 'http://';
                        }
                        $url = $prefix.env('APP_URL');
                        if (request()->has('ref-url')){
                        $ex = explode($prefix.env('APP_URL'),request('ref-url'));
                        if (count($ex)===2){
                        $url .= $ex[1];
                        }
                        }
                    @endphp
                    <li><a href="{{$url}}">Directory</a></li>
                @endif
                <li><a href="{{route('memoria.home')}}">{!! trans('customer.home.home') !!}</a></li>
                @if ($order->booking_type == 'online')
                    <li>
                        <a href="{{route('product.detail',['slug'=>$product->unique_code])}}">{{$product->product_name}}</a>
                    </li>
                @endif
                <li><a>Invoice</a></li>
            </ul>
        </div>
        <div id="payment-virtual-account" class="container pb-5">
            <div class="row">
                <div class="col-12 py-3 text-center">
                    <p class="fs-14">{!! trans('customer.invoice.payment_expired_in') !!} :</p>
                </div>
                <div class="col-12 text-center">
                    <div class="count-down">
                        <div class="number days">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.day') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number hours">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.hour') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number minutes">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.minute') !!}
                        </div>
                    </div>
                    <div class="count-down">
                        <div class="number seconds">
                            00
                        </div>
                        <div class="desc">
                            {!! trans('customer.invoice.second') !!}
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center py-3">
                    <h2 class="bold">
                        {{ format_priceID($order->total_amount) }}
                    </h2>
                    <div class="can-copy">
                        INVOICE <span class="bold data-copied">{{ $order->invoice_no }}</span> | <span
                                class="bold">{{ $order->customer_info->email }}</span>
                    </div>
                    <div class="bold">
                        {{$product->product_name}}
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row py-3">
                        <div class="col-md-6 order-1 order-lg-0 text-center text-lg-left">
                            <span class="caption">Pilih BCA</span>
                        </div>
                        <div class="col-md-6 text-lg-right order-0 order-lg-1  mb-lg-0 mb-3 text-center">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="accordion" id="accordionExample2">
                                <div class="card mt-1 mb-1">
                                    <div class="card-header" id="headingOne">
                                        <h5 class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <img width="120px" class="img-fluid"
                                                 src="{{asset('img/static/payment/bca.png')}}" alt="">
                                        </h5>

                                    </div>
                                    <div id="collapseOne" class="collapse show"
                                         aria-labelledby="headingTwoMobilebankingBRI"
                                         data-parent="headingOne">
                                        <div class="card-body">
                                            <div class="col-12 text-center virtual-account pb-4 can-copy">
                                                <h3>{!! trans('customer.bca_manual.account_number') !!} # <span
                                                            class="data-copied">{{ $company->transfer_manual->no_rekening }}</span>
                                                </h3>
                                                <h3>{!! trans('customer.bca_manual.name_rekening') !!}
                                                    # {{ $company->transfer_manual->name_rekening }}</h3>
                                            </div>
                                            <div class="col-12">
                                                <p class="mb-1 pl-3">{!! trans('customer.bca_manual.description.intro') !!}</p>
                                                <ol>
                                                    <li>
                                                        {!! trans('customer.bca_manual.description.step-1') !!}
                                                    </li>
                                                    <li>
                                                        {!! trans('customer.bca_manual.description.step-2') !!} <br>
                                                    </li>
                                                    <li>
                                                        {!! trans('customer.bca_manual.description.step-3') !!} <br>
                                                    </li>
                                                    <li>
                                                        {!! trans('customer.bca_manual.description.step-4') !!}
                                                    </li>
                                                    <li>
                                                        {!! trans('customer.bca_manual.description.step-5') !!}
                                                    </li>
                                                </ol>
                                            </div>
                                            <div class="col-12 col-md-6 offset-md-3" style="margin-top: 3rem!important">
                                                <h3>{!! trans('customer.bca_manual.form_payment') !!}</h3>
                                                {!! Form::open(['autocomplete'=>'off']) !!}
                                                <div class="md-form __parent_form">
                                                    <input type="text" id="bank_name" class="form-control"
                                                           name="bank_name"
                                                           value="{{ optional($order->customer_manual_transfer)->bank_name }}">
                                                    <label for="bank_name">{!! trans('customer.bca_manual.sender_account_name') !!}
                                                        <span class="text-danger">*</span></label>
                                                    <input type="hidden" name="invoice_no"
                                                           value="{{ $order->invoice_no }}">
                                                    {{-- <label for="full_name">{!! trans('customer.book.full_name') !!} *</label> --}}
                                                </div>
                                                <div class="md-form __parent_form">
                                                    <input type="text" id="no_rekening" class="form-control number"
                                                           name="no_rekening"
                                                           value="{{ optional($order->customer_manual_transfer)->no_rekening }}">
                                                    <label for="no_rekening">{!! trans('customer.bca_manual.account_number') !!}
                                                        <span class="text-danger">*</span> </label>
                                                </div>
                                                <div class="md-form __parent_form">
                                                    <div class="col-12 col-md-6 offset-md-3">
                                                        <input type="file" class="dropify" name="upload_document"
                                                               data-max-file-size="2M"
                                                               data-allowed-file-extensions="png jpeg jpg"
                                                               @if(optional($order->customer_manual_transfer)->upload_document)
                                                               data-default-file="{{ asset(optional($order->customer_manual_transfer)->upload_document) }}"
                                                                @endif
                                                        />
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary mt-3" type="button" id="booking-pay-now">
                                                    {!! trans('customer.book.upload_payment') !!}
                                                </button>
                                                {!! Form::close() !!}
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

@section('additionalStyle')
    <style>
        .card .card-header img {
            height: auto;
        !important;
        }

        .modal-content {
            height: auto;
        !important;
        }
    </style>
@stop
@section('scripts')
    <script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
    <script>
        let expired_in = parseInt("{{strtotime($order->payment->expiry_date)}}");
        let skrg = parseInt("{{strtotime(date('Y-m-d H:i:s'))}}");
        let countdownTimer;
        let seconds = (expired_in - skrg);
        if (expired_in > skrg) {
            countdownTimer = setInterval('timer()', 1000);
        } else {
            checkOrder();
        }

        function pad(n) {
            return (n < 10 ? "0" + n : n);
        }

        function timer() {
            let days = Math.floor(seconds / 24 / 60 / 60);
            let hoursLeft = Math.floor((seconds) - (days * 86400));
            let hours = Math.floor(hoursLeft / 3600);
            let minutesLeft = Math.floor((hoursLeft) - (hours * 3600));
            let minutes = Math.floor(minutesLeft / 60);
            let remainingSeconds = seconds % 60;
            checkOrder();
            $('.days').html(pad(days));
            $('.hours').html(pad(hours));
            $('.minutes').html(pad(minutes));
            $('.seconds').html(pad(remainingSeconds));
            if (seconds === 0) {
                clearInterval(countdownTimer);
            } else {
                seconds--;
            }
        }

        function checkOrder() {
            let data = {
                'invoice_no': '{{$order->invoice_no}}'
            };
            $.ajax({
                url: '{{route('invoice.check-order')}}',
                data: data,
                success: function (data) {
                    if (data.result !== null) {
                        clearInterval(countdownTimer);
                        window.location = data.result.redirect;
                    }
                },
                error: function (e) {
                    clearInterval(countdownTimer);
                    if (e.responseJSON.result !== undefined) {
                        window.location = e.responseJSON.result.redirect
                    }
                }
            })
        }

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

        $('.dropify').dropify({
            messages: {
                'default': "{!! trans('customer.bca_manual.use') !!}",
                'error':   "{{ trans('kyc.error') }}"
            }
        });

        let submit = false;
        $(document).on('click', '#booking-pay-now', function () {
            if (!submit) {
                loadingStart();
                let f = $(this).closest('form');
                let form_data = new FormData(f[0]);
                $('label.error').remove();
                submit = true;
                $.ajax({
                    url: "{{route('customer.transfer')}}",
                    type: 'POST',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function (data) {
                        window.location.href = data.data.url
                        loadingFinish();
                        submit = false;
                        clearInterval(countdownTimer)
                    },
                    error: function (e, textstatus) {
                        if (textstatus == 'timeout') {
                            return toastr.error('Timeout', '{{__('general.whoops')}}')
                        }
                        if (e.status === 422) {
                            $.each(e.responseJSON.errors, function (i, e) {
                                let selector = '[name=' + i + ']';
                                if (i.indexOf('.') != -1) {
                                    selector = '#' + i.replace(/\./g, '-');
                                }
                                $(document).find('input' + selector + ', textarea' + selector + ', div' + selector).closest('.md-form').append('<label class="error">' + e[0] + '</label>');
                            })
                        }
                        loadingFinish();
                        $('html, body').animate({
                            scrollTop: 0
                        }, 1000);
                        // if (e.responseJSON.result !== undefined) {
                        //     window.location = e.responseJSON.result.redirect
                        // }

                        toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                        submit = false;
                    }
                })
            }
        });
    </script>
@stop