@extends('customer.master.index')
@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            <ul class="breadcrumb">
                <li><a href="{{route('company.dashboard')}}">Home</a></li>
                <li>
                    <a href="{{route('company.premium.index')}}">{{$orderAds->category_ads}}</a>
                </li>
                <li><a>{{ trans('premium.success_payment.success') }}</a></li>
            </ul>
        </div>
        <div id="payment-success" class="container pb-5">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="bg-white p-3">
                        <img src="{{asset('img/static/payment/envelope-success.png')}}" alt="">
                        <p class="mt-5 title-success">{{ trans('premium.success_payment.thanks_payment') }}</p>
                        <p>{{ trans('premium.success_payment.invoice_desc') }} <span class="text-primary">{{ $orderAds->no_invoice }}</span> {{ trans('premium.success_payment.mail_desc') }}
                            <span class="text-primary">{{ $companyEmail->email }}</span>. {{ trans('premium.success_payment.problem_desc') }}</p>
                        <p>{{ trans('premium.success_payment.thanks_desc') }}</p>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 mb-3">
                                <div class="row bg-light-blue">
                                    <div class="col-lg-6 p-3 border-right">

                                        <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="color:black">
                                        <img style="vertical-align: sub"
                                             src="{{asset('img/static/payment/whatsapp.png')}}" alt=""> {{ trans('premium.success_payment.whatsapp') }}
                                        Whatsapp
                                        </a> 


                                    </div>
                                    <div class="col-lg-6 p-3">
                                        <img style="vertical-align: sub"
                                             src="{{asset('img/static/payment/envelope-alt.png')}}" alt=""> {{ trans('premium.success_payment.email_at') }} store@gomodo.tech
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 text-primary">
                                <a href="{{ route('company.premium.index') }}">
                                    <img src="{{asset('img/static/payment/arrow-left.png')}}" alt="">
                                    {{ trans('premium.success_payment.back_home') }} {{ trans('sidebar_provider.premium_store') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

