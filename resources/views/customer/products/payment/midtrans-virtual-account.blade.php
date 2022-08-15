@extends('customer.master.index')

@section('additionalStyle')
    <style>
        .card-header .btn-link {
            text-decoration: none;
            width: 100%;
        }

        .img-fluid {
            padding-bottom: 2rem
        }

        @media (min-width: 320px) and (max-width: 991px) {
            #payment-virtual-account .card-body a.backHome {
                font-size: 9px;
            }

            #payment-virtual-account .col-12 {
                padding-right: 5px;
                padding-left: 5px;
            }

            #payment-virtual-account .card-payment ol {
                padding-left: 10px;
            }

            #payment-virtual-account .bank-list {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>
@endsection

@section('content')
    {{--{!! dd($res) !!}--}}
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
                <li><a href="{{route('memoria.home')}}">Home</a></li>
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
                {{--{{dd($res)}}--}}
                <div class="col-12 text-center py-3">
                    <h2 class="bold">
                        {{format_priceID($payment->amount)}}
                    </h2>
                    <div class="can-copy">
                        INVOICE <span class="bold data-copied">{{$payment->invoice_no}}</span> | <span
                                class="bold">{{$order->customer->first_name}}</span>
                    </div>
                    <div class="bold">
                        {{$product->product_name}}
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row py-3">
                        <div class="col-md-6 order-1 order-md-0 text-center text-md-left">
                            <a href="{{ $payment->response['pdf_url'] }}" target="_blank"
                               class="btn btn-primary retrieve waves-effect waves-light">Download</a> <br><br>
                            {{--                            <span class="caption">{!! trans('customer.invoice.choose_retail') !!}</span>--}}
                        </div>
                        <div class="col-md-6 text-md-right order-0 order-md-1  mb-lg-0 mb-3 text-center">
                            <a href="/"
                               class="backHome">{!! trans('customer.invoice.back_to') !!} {{$product->company->domain_memoria}}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="accordion" id="accordionExample">
                                <div class="card mt-1 mb-1">
                                    <div class="card-header"
                                         id="headingOne">
                                        <h5 class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            BCA Virtual Account
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse show"
                                         aria-labelledby="headingOne"
                                         data-parent="#accordionExample">
                                        <div class="card-body bank-list">
                                            <div class="col-12 text-center virtual-account can-copy">
                                                <h3> {!! trans('customer.bca.atm_bca.code') !!} #
                                                    <span class="data-copied">{{ $order->payment->response['bca_va_number']}}</span>
                                                </h3>
                                                <h3>Merchant Name
                                                    # PT Kadal Nusantara Perkasa</h3>
                                            </div>
                                            <div class="col-12 mb-3 text-center">
                                                <img class="img-fluid" width="120"
                                                     src="{{asset('img/static/payment/bca.png')}}" alt=""><br>
                                            </div>
                                            <div class="col-12">
                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="home-tab" data-toggle="tab"
                                                           href="#home" role="tab" aria-controls="home"
                                                           aria-selected="true">ATM BCA</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="profile-tab" data-toggle="tab"
                                                           href="#profile" role="tab" aria-controls="profile"
                                                           aria-selected="false">Klik BCA</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="contact-tab" data-toggle="tab"
                                                           href="#contact" role="tab" aria-controls="contact"
                                                           aria-selected="false">m-BCA</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                         aria-labelledby="home-tab">
                                                        <ol>
                                                            <li>{!! trans('customer.bca.atm_bca.step-0') !!}</li>
                                                            <li>{!! trans('customer.bca.atm_bca.step-1') !!}</li>
                                                            <li>{!! trans('customer.bca.atm_bca.step-2') !!}</li>
                                                            <li>{!! trans('customer.bca.atm_bca.step-3') !!}</li>
                                                            <li>{!! trans('customer.bca.atm_bca.step-4') !!}</li>
                                                            <li>{!! trans('customer.bca.atm_bca.step-5') !!}</li>
                                                        </ol>
                                                    </div>
                                                    <div class="tab-pane fade" id="profile" role="tabpanel"
                                                         aria-labelledby="profile-tab">
                                                        <ol>
                                                            <li>{!! trans('customer.bca.klik_bca.step-0') !!}</li>
                                                            <li>{!! trans('customer.bca.klik_bca.step-1') !!}</li>
                                                            <li>{!! trans('customer.bca.klik_bca.step-2') !!}</li>
                                                            <li>{!! trans('customer.bca.klik_bca.step-3') !!}</li>
                                                            <li>{!! trans('customer.bca.klik_bca.step-4') !!}</li>
                                                            <li>{!! trans('customer.bca.klik_bca.step-5') !!}</li>
                                                        </ol>
                                                    </div>
                                                    <div class="tab-pane fade" id="contact" role="tabpanel"
                                                         aria-labelledby="contact-tab">
                                                        <ol>
                                                            <li>{!! trans('customer.bca.m_bca.step-0') !!}</li>
                                                            <li>{!! trans('customer.bca.m_bca.step-1') !!}</li>
                                                            <li>{!! trans('customer.bca.m_bca.step-2') !!}</li>
                                                            <li>{!! trans('customer.bca.m_bca.step-3') !!}</li>
                                                            <li>{!! trans('customer.bca.m_bca.step-4') !!}</li>
                                                            <li>{!! trans('customer.bca.m_bca.step-5') !!}</li>
                                                        </ol>
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
@section('scripts')
    <script>
        let expired_in = parseInt("{{strtotime($payment->expiry_date)}}");
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

                },
                error: function (e) {
                    clearInterval(countdownTimer);
                    if (e.responseJSON.result !== undefined) {
                        window.location = e.responseJSON.result.redirect
                    }
                }
            })
        }
    </script>
@stop
