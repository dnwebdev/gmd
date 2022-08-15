@extends('customer.master.index')

@section('additionalStyle')
    <link href="{{asset('additional/select2/css/typography.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/textfield.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2.min.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2-bootstrap.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/pmd-select2.css')}}" type="text/css" rel="stylesheet"/>
    <style>
        .select2-container--bootstrap.select2-container--focus .select2-selection,
        .select2-container--bootstrap .select2-selection--single,
        .select2-container--bootstrap.select2-container--open .select2-selection {
            height: 39px !important;
        }

        .md-form .select2.select2-container + label {
            top: -14px !important;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
        }

        #three-ds-container {
            width: 550px;
            height: 450px;
            line-height: 200px;
            position: fixed;
            top: 25%;
            left: 40%;
            margin-top: -30px;
            margin-left: -150px;
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
            z-index: 11; /* 1px higher than the overlay layer */
        }

        @media all and (max-width: 480px) {
            #three-ds-container, #three-ds-container > iframe {
                width: 100%;
            }

            #three-ds-container {
                top: 13%;
            }
        }

        @media (min-width: 320px) and (max-width: 992px) {
            #payment-virtual-account .card-body a.backHome {
                font-size: 11px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                <li><a href="{{route('memoria.home')}}">{!! trans('customer.home.home') !!}</a></li>
                @if ($order->booking_type == 'online')
                    <li><a href="{{route('product.detail',['slug'=>$product->unique_code])}}">{{$product->product_name}}</a>
                    </li>
                @endif
                <li><a>Credit Card payment</a></li>
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
                {{--{{dd($res)}}--}}
                <div class="col-12 text-center py-3">
                    <h2 class="bold">
                        {{format_priceID($res['amount'])}}
                    </h2>
                    <div class="can-copy">
                        INVOICE <span class="bold data-copied">{{$res['external_id']}}</span> | <span
                                class="bold">{{$res['payer_email']}}</span>
                    </div>
                    <div class="bold">
                        {{$product->product_name}}
                    </div>
                </div>
            </div>
            <form role="form" id="payment-form" method="POST" action="javascript:void(0);">
                <div class="card">
                    <div class="card-body">
                        <div class="row py-3">
                            <div class="col-md-6 order-1 order-lg-0 text-center text-md-left">
                                <span class="caption">Credit Card</span>
                            </div>
                            <div class="col-md-6 text-md-right order-0 order-md-1  mb-lg-0 mb-3 text-center">
                                <a href="/"
                                   class="backHome embed-remove">{!! trans('customer.invoice.back_to') !!}  {{$product->company->domain_memoria}}</a>
                            </div>
                        </div>
                        <div class="row py-3 mt-3">
                            <div class="col-md-8">
                                <div class="md-form __parent_form">
                                    <input type="text" id="card-number"
                                           {{--                                           value="4000000000000002"--}}
                                           value=""
                                           class="form-control numeric"
                                           name="card_number">
                                    <label for="card-number">{{ trans('customer.invoice.cc_number') }} *</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="md-form __parent_form">
                                    {!! Form::select('payment_method',$months,12,['class'=>'select-simple pmd-select2','id'=>'card-exp-month']) !!}
                                    <label for="phone_number">{{ trans('customer.invoice.month') }} *</label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="md-form __parent_form">
                                    {!! Form::select('payment_method',$years,\Carbon\Carbon::now()->addYear()->format('Y'),['class'=>'select-simple pmd-select2','id'=>'card-exp-year']) !!}
                                    <label for="phone_number">{{ trans('customer.invoice.year') }} *</label>
                                </div>
                            </div>
                        </div>
                        <div class="row py-1">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="md-form __parent_form">
                                            <input type="text" id="card-cvn"
                                                   {{--                                                   value="123123" class="form-control number"--}}
                                                   value="" class="form-control numeric"
                                                   name="phone_number">
                                            <label for="phone_number">CVV *</label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="md-form __parent_form">
                                            <div style="height: 40px; display: flex; justify-content: start; align-items: center">
                                                {{ trans('customer.invoice.3digits') }}
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 visa-master">
                                <img src="{{asset('img/static/payment/visa_logo@2x.png')}}" alt="">
                                <img src="{{asset('img/static/payment/mastercard_logo@2x.png')}}" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 offset-md-3  py-5 my-3">
                                <button class="btn btn-primary btn-block ml-0">{{ trans('customer.invoice.pay') }}</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto mb-2">
                                <img src="{{asset('img/static/payment/lock.png')}}" alt="" class="lock">
                                <span class="secure">{{ trans('customer.invoice.secure_payments') }}</span>
                            </div>
                            <div class="col-auto ml-auto">
                                <img src="{{asset('img/static/payment/logo-comodo.png')}}" alt="" class="comodo-logo">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="mt-5 mr-auto ml-auto">
                    Powered By <img src="{{asset('img/static/payment/xendit-logo.png')}}" alt="" class="xendit-logo">
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="overlay" style="display: none;"></div>
    <div id="three-ds-container" style="display: none;">
        <i id="close-3ds" style="position: absolute;right: 10px;top: 10px;cursor:pointer;" class="fa fa-close"></i>
        <iframe height="450" width="550" id="sample-inline-frame" name="sample-inline-frame"></iframe>
    </div>
@stop



@section('scripts')
    <script src="https://js.xendit.co/v1/xendit.min.js"></script>
    <script src="{{asset('additional/select2/js/global.js')}}"></script>
    <script src="{{asset('additional/select2/js/textfield.js')}}"></script>
    <script src="{{asset('additional/select2/js/select2.full.js')}}"></script>
    <script src="{{asset('additional/select2/js/pmd-select2.js')}}"></script>
    @if(app()->environment()=='production')
        <script>Xendit.setPublishableKey('xnd_public_production_OY6JfL5whL2uxpNueOZNTzKWNNWjqNF+xnLp+Rxn/GXT/7KhCwNygA==');</script>
    @else
        <script>Xendit.setPublishableKey('xnd_public_development_Oo2CfL5whL2uxpNueOZNTzKWNNWjqNF+xnLp+Rxn/GXT/7KhCwNygA==');</script>
    @endif


    <script>
        let expired_in = parseInt("{{strtotime($res['expiry_date'])}}");
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
            if (remainingSeconds % 10 === 1) {
                checkOrder();
            }
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

                },
                error: function (e) {
                    console.log(e)
                    if (e.responseJSON.result !== undefined) {
                        window.location = e.responseJSON.result.redirect
                    }
                }
            })
        }

        $('form').on('focus', 'input[type=number]', function (e) {
            $(this).on('mousewheel.disableScroll', function (e) {
                e.preventDefault()
            })
        })
        $(document).on('click', '#close-3ds', function () {
            $('#three-ds-container').hide();
            $('.overlay').hide();
        });
        $('form').on('blur', 'input[type=number]', function (e) {
            $(this).off('mousewheel.disableScroll')
        })
        $(document).ready(function () {

            $(document).on('change paste keydown', 'input, select', function () {
                $(this).closest('.md-form').find('label.error').remove();
            });

            $("select[name=country]").select2({
                theme: "bootstrap",
            });
            $(".select-simple").select2({
                theme: "bootstrap",
                minimumResultsForSearch: Infinity,
            });
            let $form = $('#payment-form');

            function getTokenData() {
                if ($form.find('#card-cvn').val()) {
                    return {
                        amount: "{{$order->total_amount}}",
                        card_number: $form.find('#card-number').val(),
                        card_exp_month: $form.find('#card-exp-month').val(),
                        card_exp_year: $form.find('#card-exp-year').val(),
                        card_cvn: $form.find('#card-cvn').val(),
                        is_multiple_use: false,
                        should_authenticate: true
                    };
                }

                return {
                    amount: "{{$order->total_amount}}",
                    card_number: $form.find('#card-number').val(),
                    card_exp_month: $form.find('#card-exp-month').val(),
                    card_exp_year: $form.find('#card-exp-year').val(),
                    is_multiple_use: false,
                    should_authenticate: true
                };
            }

            $form.submit(function (event) {
                event.preventDefault();
                if (validateCreditCard()) {
                    $form.find('button').prop('disabled', true);
                    let tokenData = getTokenData();
                    console.log(tokenData)
                    loadingStart();
                    $('#three-ds-container').find('#sample-inline-frame').empty();
                    Xendit.card.createToken(tokenData, xenditResponseHandler);
                }

                return false;
            });

            function validateCreditCard() {
                let valid = true;
                $form.find('label.error').remove();
                if (!Xendit.card.validateCardNumber($form.find('#card-number').val())) {
                    $form.find('#card-number').closest('.md-form').append('<label class="error">Credit Card invalid</label>');
                    valid = false;
                }
                if (!Xendit.card.validateExpiry($form.find('#card-exp-month').val(), $form.find('#card-exp-year').val())) {
                    $form.find('#card-exp-month').closest('.md-form').append('<label class="error">Expiry Date Invalid</label>');
                    valid = false;
                }
                if (!Xendit.card.validateCvn($form.find('#card-cvn').val())) {
                    $form.find('#card-cvn').closest('.md-form').append('<label class="error">{{ trans('customer.invoice.invalid') }}</label>');
                    valid = false;
                } else {
                    if ($form.find('#card-cvn').val() === '') {
                        $form.find('#card-cvn').closest('.md-form').append('<label class="error"> {{ trans('customer.invoice.required') }}</label>');
                        valid = false;
                    } else if ($form.find('#card-cvn').val().length < 3) {
                        $form.find('#card-cvn').closest('.md-form').append('<label class="error"> {{ trans('customer.invoice.minimum-3') }}</label>');
                        valid = false;
                    }
                }

                return valid;

            }

            function xenditResponseHandler(err, creditCardCharge) {
                $form.find('button').prop('disabled', false);
                if (validateCreditCard()) {
                    if (err) {
                        loadingFinish();
                        toastr.error(err.message, '{{__('general.whoops')}}')
                    } else {
                        if (creditCardCharge.status === 'APPROVED' || creditCardCharge.status === 'VERIFIED') {
                            creditCardCharge.external_id = "{{$order->invoice_no}}";
                            creditCardCharge._token = "{{csrf_token()}}";
                            creditCardCharge.card_cvn = $form.find('#card-cvn').val();


                            loadingStart();
                            $.ajax({
                                url: "{{url('/test/post-cc')}}",
                                data: creditCardCharge,
                                type: 'POST',
                                dataType: 'json',
                                success: function (data) {
                                    toastr.success(data.message, 'Congrats');
                                    checkOrder();
                                    loadingFinish();
                                    $('.overlay').hide();
                                    $('#three-ds-container').hide();
                                },
                                error: function (e) {
                                    if (e.responseJSON.result !== undefined) {
                                        toastr.error(e.responseJSON.message, e.responseJSON.result.message);
                                    } else {
                                        toastr.error(e.responseJSON.message, '{{__('general.whoops')}}');
                                    }
                                    loadingFinish();
                                    $('.overlay').hide();
                                    $('#three-ds-container').hide();
                                }
                            })
                        } else if (creditCardCharge.status === 'IN_REVIEW') {

                            window.open(creditCardCharge.payer_authentication_url, 'sample-inline-frame');
                            $('.overlay').show();
                            $('#three-ds-container').show();
                            loadingFinish();
                        } else if (creditCardCharge.status === 'FRAUD') {
                            loadingFinish();
                            console.log(creditCardCharge);
                        } else if (creditCardCharge.status === 'FAILED') {
                            loadingFinish();
                            let a = creditCardCharge.failure_reason.replace('_', ' ').toLowerCase();
                            toastr.error(a.charAt(0).toUpperCase() + a.slice(1), '{{__('general.whoops')}}')
                        }
                    }


                }
            }
        })
    </script>
    <script>2
        jQuery(document).on('keydown', '.numeric', function (e) {
            // $(this).val(parseInt($(this).val()));
            // var char_length = $(this).val().length;
            var Allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
            if (!Allowed.includes(e.key)) {
                e.preventDefault();
            }
        });
        $(document).on('change keydown', '#card-cvn', function (e) {
            var char = $(this).val();
            var Allowed = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];
            if (!Allowed.includes(e.key)) {
                e.preventDefault();
            }
            var AllowedAdditional = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight'];
            if (char.length > 3 && !AllowedAdditional.includes(e.key)){
                e.preventDefault();
            }
        })
    </script>
@stop
