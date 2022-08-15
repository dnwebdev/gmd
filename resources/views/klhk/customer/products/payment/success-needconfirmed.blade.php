@extends('klhk.customer.master.index')
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
                        <a href="{{route('product.detail',['slug'=>$order->order_detail->product->unique_code])}}">{{$order->order_detail->product->product_name}}</a>
                    </li>
                @endif
                <li><a>{{ trans('success.manual_transfer.tab') }}</a></li>
            </ul>
        </div>
        <div id="payment-success" class="container pb-5">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="bg-white p-3">
                        <img src="{{asset('img/static/payment/envelope-success.png')}}" alt="">
                        <p class="mt-5 title-success">{{ trans('success.manual_transfer.thanks') }}</p>
                        <p>{{ trans('success.manual_transfer.opening') }} <span class="text-primary">{{$order->invoice_no}}</span> {{ trans('success.manual_transfer.send') }}
                            <span class="text-primary">{{$order->customer->email}}</span>. {{ trans('success.manual_transfer.next') }}</p>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 mb-3">
                                <div class="row bg-light-blue">
                                    <div class="col-lg-6 p-3 border-right">
                                        <img style="vertical-align: sub"
                                             src="{{asset('img/static/payment/whatsapp.png')}}" alt=""> {{ trans('success.manual_transfer.whatsapp') }}
                                    </div>
                                    <div class="col-lg-6 p-3">
                                        <img style="vertical-align: sub"
                                             src="{{asset('img/static/payment/envelope-alt.png')}}" alt=""> {{ trans('success.manual_transfer.email') }}
                                        {{$company->email_company?$company->email_company:'info@gomodo.tech'}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-primary embed-remove">
                                <a href="{{url('/')}}">
                                    <img src="{{asset('img/static/payment/arrow-left.png')}}" alt="">
                                    {{ trans('success.manual_transfer.back') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
