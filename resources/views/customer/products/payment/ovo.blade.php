@extends('customer.master.index')
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
                {{--{{dd($res)}}--}}
                <div class="col-12 text-center py-3">
                    <h2 class="bold">
                        {{format_priceID($order->total_amount)}}
                    </h2>
                    <div class="can-copy">
                        INVOICE <span class="bold data-copied">{{$order->invoice_no}}</span> | <span
                                class="bold">{{$order->customer_info->email}}</span>
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
                                                 src="{{asset('img/static/payment/ovo.png')}}" alt="">
                                        </h5>

                                    </div>
                                    <div id="collapseOne"
                                         class="collapse show"
                                         aria-labelledby="headingTwoMobilebankingBRI"
                                         data-parent="headingOne">
                                        <div class="card-body">
                                            <div class="col-12 text-center virtual-account can-copy pb-3">
                                                <h3>{!! trans('customer.ovo.title') !!}</h3>
                                            </div>
                                            <div class="col-12 col-md-6 offset-md-3">
                                                <ol style="padding-inline-start: 15px">
                                                    @foreach (trans('customer.ovo.content') as $content)
                                                        <li>
                                                            {{$content}} 
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </div>
                                            <div class="col-12 col-md-6 offset-md-3">
                                                <div class="alert alert-primary alert-dismissible fade show" id="alert-ovo" role="alert" style="display:none;">
                                                    <strong>Hai {{ $order->customer_info->first_name }},</strong> {!! trans('payment.validation-ovo.check_phone') !!}
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 0.10rem 1.25rem;">
                                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                                    </button>
                                                </div>
                                                <br>
                                                {!! Form::open() !!}
                                                <div class="md-form __parent_form">
                                                    {!! Form::label('phone_number',trans('booking.phone')) !!}
                                                    {!! Form::number('phone',$order->customer_info->phone,['class'=>'form-control']) !!}
                                                </div>
                                                <div class="md-form __parent_form text-right">
                                                    <button type="button" class="btn btn-primary" id="btn-ovo">
                                                        {!! trans('customer-klhk.book.pay_now') !!}
                                                    </button>
                                                </div>
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

{{--    <div class="modal" id="ovo" tabindex="-1" role="dialog">--}}
{{--        <div class="modal-dialog modal-sm" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">OVO</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <p>Please check your OVO Application.</p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
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

        function loadingStartButton(e) {
            e.prop('disabled', true);
            e.html('<i class="fa fa-spin fa-refresh"></i> {{ trans("general.loading") }}')
        }

        function loadingFinishButton(e, text) {
            e.prop('disabled', false);
            e.html(text)
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

        $(document).on('click', '#btn-ovo', function (e) {
            let phone = $('input[name=phone]').val();
            loadingStartButton($('#btn-ovo'));
            $('label.error').remove();
            if(phone){
                $('#alert-ovo').show();
            }
            $.ajax({
                type: "POST",
                data: {
                    "_token": "{{csrf_token()}}",
                    "phone": phone
                },
                dataType: 'json',
                success: function (data) {
                    // loadingFinishButton($('#btn-ovo'), '{!! trans('customer-klhk.book.pay_now') !!}');
                    // $('#alert-ovo').hide();
                },
                error: function (e) {
                    loadingFinishButton($('#btn-ovo'), '{!! trans('customer-klhk.book.pay_now') !!}');
                    $('#alert-ovo').hide();
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.md-form').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name=' + i + ']').closest('.select-wrapper.md-form').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name=' + i + ']').closest('.pmd-textfield-floating-label').append('<label class="error">' + e[0] + '</label>');
                        })
                    }

                    toastr.error(e.responseJSON.message);
                }
            })
        });

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
