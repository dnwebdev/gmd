@extends('customer.master.index')

@section('additionalStyle')
    <style>
        .card-header .btn-link {
            text-decoration: none;
            width: 100%;
        }
        .invoice-id {
            position: absolute;
            right: 0;
            top: 0;
        }
        .last-transaction {
            position: relative; 
            margin-bottom: 0;
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
            .invoice-id {
                position: relative;
                display: block;
                margin-bottom: 5px;
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
                    <p class="fs-14 last-transaction">
                        <span class="invoice-id">
                            INVOICE {{$res['external_id']}}
                        </span>
                        {!! trans('customer.invoice.payment_expired_in') !!} :
                    </p>
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
                    @foreach($res['available_retail_outlets'] as $available_bank)
                    <div class="payment-code">
                        {!! trans('customer.invoice.payment_code') !!} <span class="font-weight-bold">{{$available_bank['payment_code']}}</span>
                        <br>
                        {!! trans('customer.invoice.merchant_name') !!} <span class="font-weight-bold">Gomodo Technologies</span>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row py-3">
                        <div class="col-md-6 order-1 order-md-0 text-center text-md-left">
                            <span class="caption">{!! trans('customer.alfamart.title') !!}</span>
                        </div>
                        <div class="col-md-6 text-md-right order-0 order-md-1  mb-lg-0 mb-3 text-center">
                            <a href="/"
                               class="backHome embed-remove">{!! trans('customer.invoice.back_to') !!} {{$product->company->domain_memoria}}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @foreach($res['available_retail_outlets'] as $available_bank)
                                <div class="card-body bank-list">
                                    @if($available_bank['retail_outlet_name'] ==='ALFAMART')
                                        <div class="col-12 mb-3 text-center">
                                            <img class="img-fluid" width="120" src="{{asset('img/static/payment/Alfamart_logo.svg')}}" alt="">
                                        </div>
                                        <div class="col-12">
                                            <ol>
                                                <li>
                                                    {!! trans('customer.alfamart.step-1') !!}.
                                                </li>
                                                <li>
                                                    {!! trans('customer.alfamart.step-2') !!}.
                                                </li>
                                                <li>
                                                    {!! trans('customer.alfamart.step-3a') !!} <span class="font-weight-bold">{{$available_bank['payment_code']}}</span> {!! trans('customer.alfamart.step-3b') !!} <span class="font-weight-bold">{{format_priceID($res['amount'])}}</span>.
                                                </li>
                                                <li>
                                                    {!! trans('customer.alfamart.step-4') !!}.
                                                </li>
                                                <li>
                                                    {!! trans('customer.alfamart.step-5') !!}.
                                                </li>
                                            </ol>
                                        </div>
                                    @endif
                                </div>
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
