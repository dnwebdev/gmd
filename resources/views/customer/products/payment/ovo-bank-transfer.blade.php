@extends('customer.master.index')

@section('additionalStyle')
    <style>
        .card-header .btn-link {
            text-decoration: none;
            width: 100%;
        }
        @media (min-width: 320px) and (max-width: 991px){
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
                    <li><a href="{{route('product.detail',['slug'=>$product->unique_code])}}">{{$product->product_name}}</a>
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
            <div class="card">
                <div class="card-body">
                    <div class="row py-3">
                        <div class="col-md-6 order-1 order-md-0 text-center text-md-left">

                        </div>
                        <div class="col-md-6 text-md-right order-0 order-md-1  mb-lg-0 mb-3 text-center">
                            <a href="/"
                               class="backHome embed-remove">{!! trans('customer.invoice.back_to') !!} {{$product->company->domain_memoria}}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                                @foreach($res['available_banks'] as $available_bank)
                                    @if($available_bank['bank_code'] ==='BNI')
                                    <div class=" mt-1 mb-1">
                                        <div id="collapseOne{{$available_bank['bank_code']}}" class=""
                                             aria-labelledby="headingOne{{$available_bank['bank_code']}}"
                                             data-parent="#accordionExample">
                                            <div class=" bank-list mt-5">

                                                <div class="col-12 text-center virtual-account can-copy">
                                                    <h3>Virtual Account # <span
                                                                class="data-copied">{{$available_bank['bank_account_number']}}</span>
                                                    </h3>
                                                    <h3>{!! trans('customer.invoice.virtual_account_name') !!}
                                                        # {{$available_bank['account_holder_name']}}</h3>
                                                </div>

                                                    <div class="col-12 mt-3">
                                                        <div class="accordion" id="accordionMenu">
                                                            <div class="card card-payment mt-1 mb-1">
                                                                <div class="card-header text-center" id="headingTwoOVO">
                                                                    <div class="" style="width: 150px; margin: 0 auto">
                                                                        <img width="120px" class="img-fluid" style="width: 100%;height: auto"
                                                                             src="{{asset('img/static/payment/ovo.png')}}" alt="">
                                                                    </div>

                                                                </div>
                                                                <div id="collapseTwoOVO" class=""
                                                                     aria-labelledby="headingTwoOVO"
                                                                     data-parent="#accordionMenu">
                                                                    <div class="card-body">
                                                                        <div class="col-12">
                                                                            <ol>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-1') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-2') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-3') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-4') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-5a') !!}
                                                                                    <span class="font-weight-bold">{{$available_bank['bank_account_number']}}</span>{!! trans('customer.bank_transfer.bni.ovo.step-5b') !!}
                                                                                </li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-6') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-7') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-8') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-9') !!}</li>
                                                                                <li>{!! trans('customer.bank_transfer.bni.ovo.step-10') !!}</li>
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
                                    @endif
                                @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
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
                    if (e.responseJSON.result !== undefined) {
                        window.location = e.responseJSON.result.redirect
                    }
                }
            })
        }
    </script>
@stop
