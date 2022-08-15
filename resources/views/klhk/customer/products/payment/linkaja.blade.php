@extends('klhk.customer.master.index')
@section('additionalStyle')
    <style>
        #preview-frame {width: 100%;}
        .card .card-header img {
            height: auto;
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
                                <span class="caption">Pilih E-wallet</span>
                            </div>
                            <div class="col-md-6 text-lg-right order-0 order-lg-1  mb-lg-0 mb-3 text-center">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="accordion" id="accordionExample2">
                                        <div class="card mt-1 mb-1">
                                            <div class="card-header"
                                                 id="headingOne">
                                                <h5 class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapseOne"
                                                    aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    {{--                                                    {{$available_ewallet['ewallet_type']}}--}}
                                                    <img width="120px" class="img-fluid"
                                                         src="{{asset('img/static/payment/linkaja.png')}}" alt="">
                                                </h5>

                                            </div>
                                            <div id="collapseOne"
                                                 class="collapse show"
                                                 aria-labelledby="headingTwoMobilebankingBRI"
                                                 data-parent="headingOne">
                                                <div class="card-body">
                                                          <iframe id="testimonials" name="testimonials" allowtransparency="true" scrolling="no" src="{{ $res['checkout_url'] }}" allowfullscreen></iframe>
{{--                                                    <div class="embed-responsive embed-responsive-16by9">--}}
{{--                                                    </div>--}}
{{--                                                    <div class="col-12 col-md-6 offset-md-3">--}}
{{--                                                    </div>--}}
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
        .modal-content{
            height: auto;!important;
        }
    </style>
@stop
@section('scripts')
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

          },
          error: function (e) {
            if (e.responseJSON.result !== undefined) {
              window.location = e.responseJSON.result.redirect
            }
          }
        })
      }
      // var calcHeight = function() {
      //   $('#preview-frame').height($(window).height());
      // }
      if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
          $('#testimonials').css({"width":"100%","min-height":"auto","border":"none","overflow-y":"hidden","overflow-x":"hidden"})
      } else{
          $('#testimonials').css({"width":"100%","min-height":"900px","border":"none","overflow-y":"hidden","overflow-x":"hidden"})
      }
        // $(document).ready(function() {
        //     calcHeight();
        // });
      //
      //    $(window).resize(function() {
      //      calcHeight();
      //    }).load(function() {
      //      calcHeight();
      //    });
    </script>
@stop
