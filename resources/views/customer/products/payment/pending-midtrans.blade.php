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
                <li><a href="{{route('memoria.home')}}">Home</a></li>
                <li>
                    <a href="{{route('product.detail',['slug'=>$product->unique_code])}}">{{$order->order_detail->product->product_name}}</a>
                </li>
                <li><a>Cek Payment</a></li>
            </ul>
        </div>
        <div id="payment-success" class="container pb-5">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="bg-white p-3">
                        <img src="{{asset('img/static/payment/envelope-success.png')}}" alt="">
                        <p class="mt-5 title-success">{{ trans('success.thanks') }}</p>
                        <p>Silahkan melakukan pembayaran dengan nomor invoice <span
                                    class="text-primary">{{$order->invoice_no}}</span> dan sudah kami kirimikan detail
                            order ke alamat email anda <span class="text-primary">{{$order->customer->email}}</span>.
                        </p>
                        <div class="row">
                            <div class="col-md-8 offset-md-4 mb-3">
                                <div class="row">
                                    <div class="col-lg-6 p-3">
                                        <button class="btn btn-primary retrieve waves-effect waves-light" id="paynow">
                                            Bayar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-primary">
                                <a href="{{url('/')}}">
                                    <img src="{{asset('img/static/payment/arrow-left.png')}}" alt="">
                                    {{ trans('success.back') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript" src="https://app.{{env('APP_ENV')!='production'?"sandbox.":""}}midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        $(document).on('click', '#paynow', function () {
            midtrans();
        });
        midtrans();

        function midtrans() {
            snap.pay('{{ $order->payment->token_midtrans }}', {

                onSuccess: function (result) {
                    // changeResult('success', result);
                    console.log(result.status_message);
                    console.log(result);
                    // $("#payment-form").submit();
                },
                onPending: function (result) {
                    console.log(result);
                    // changeResult('pending', result);
                    // console.log(result.status_message);

                    $.ajax({
                        url: '{{ route('check.midtrans') }}',
                        data: {
                            result: result,
                            invoice_no: '{{ $order->invoice_no }}'
                        },
                        success: function (data) {
                            window.location.href = data.data.url;
                        }
                    });

                },
                onError: function (result) {
                    // changeResult('error', result);
                    console.log(result.status_message);
                    // $("#payment-form").submit();
                }
            });
        }
    </script>
@stop

