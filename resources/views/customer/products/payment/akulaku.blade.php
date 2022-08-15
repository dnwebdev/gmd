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
                <li>
                    <a href="{{route('product.detail',['slug'=>$order->order_detail->product->unique_code])}}">{{$order->order_detail->product->product_name}}</a>
                </li>
                <li><a>AkuLaku</a></li>
            </ul>
        </div>
        <div id="payment-success" class="container pb-5">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="bg-white p-3">
                        @switch ($order->status)
                            @case('0')
                                <svg id="wrap" width="300" height="300">
  
                                  <!-- background -->
                                  <svg>
                                    <circle cx="150" cy="150" r="130" style="stroke: #EFEFEF; stroke-width:18; fill:transparent"/>
                                    <circle cx="150" cy="150" r="115" style="fill:#01b7f2"/>
                                    <path style="stroke: #01b7f2; stroke-dasharray:820; stroke-dashoffset:820; stroke-width:18; fill:transparent" d="M150,150 m0,-130 a 130,130 0 0,1 0,260 a 130,130 0 0,1 0,-260">
                                      <animate attributeName="stroke-dashoffset" dur="6s" to="-820" repeatCount="indefinite"/>
                                    </path>
                                  </svg>
                                  
                                  <!-- image -->
                                  <svg>
                                    <path id="hourglass" d="M150,150 C60,85 240,85 150,150 C60,215 240,215 150,150 Z" style="stroke: white; stroke-width:5; fill:white;" />
                                    
                                    <animateTransform xlink:href="#frame" attributeName="transform" type="rotate" begin="0s" dur="3s" values="0 150 150; 0 150 150; 180 150 150" keyTimes="0; 0.8; 1" repeatCount="indefinite" />
                                    <animateTransform xlink:href="#hourglass" attributeName="transform" type="rotate" begin="0s" dur="3s" values="0 150 150; 0 150 150; 180 150 150" keyTimes="0; 0.8; 1" repeatCount="indefinite" />
                                  </svg>
                                  
                                  <!-- sand -->
                                  <svg>
                                    <!-- upper part -->
                                    <polygon id="upper" points="120,125 180,125 150,147" style="fill:#01b7f2;">
                                      <animate attributeName="points" dur="3s" keyTimes="0; 0.8; 1" values="120,125 180,125 150,147; 150,150 150,150 150,150; 150,150 150,150 150,150" repeatCount="indefinite"/>
                                    </polygon>
                                    
                                    <!-- falling sand -->
                                    <path id="line" stroke-linecap="round" stroke-dasharray="1,4" stroke-dashoffset="200.00" stroke="#01b7f2" stroke-width="2" d="M150,150 L150,198">
                                      <!-- running sand -->
                                      <animate attributeName="stroke-dashoffset" dur="3s" to="1.00" repeatCount="indefinite"/>
                                      <!-- emptied upper -->
                                      <animate attributeName="d" dur="3s" to="M150,195 L150,195" values="M150,150 L150,198; M150,150 L150,198; M150,198 L150,198; M150,195 L150,195" keyTimes="0; 0.65; 0.9; 1" repeatCount="indefinite"/>
                                      <!-- last drop -->
                                      <animate attributeName="stroke" dur="3s" keyTimes="0; 0.65; 0.8; 1" values="#01b7f2;#01b7f2;transparent;transparent" to="transparent" repeatCount="indefinite"/>
                                    </path>
                                    
                                    <!-- lower part -->
                                    <g id="lower">
                                      <path d="M150,180 L180,190 A28,10 0 1,1 120,190 L150,180 Z" style="stroke: transparent; stroke-width:5; fill:#01b7f2;">
                                        <animateTransform attributeName="transform" type="translate" keyTimes="0; 0.65; 1" values="0 15; 0 0; 0 0" dur="3s" repeatCount="indefinite" />
                                      </path>
                                      <animateTransform xlink:href="#lower" attributeName="transform"
                                                    type="rotate"
                                                    begin="0s" dur="3s"
                                                    values="0 150 150; 0 150 150; 180 150 150"
                                                    keyTimes="0; 0.8; 1"
                                                    repeatCount="indefinite"/>
                                    </g>
                                    
                                    <!-- lower overlay - hourglass -->
                                    <path d="M150,150 C60,85 240,85 150,150 C60,215 240,215 150,150 Z" style="stroke: white; stroke-width:5; fill:transparent;">
                                      <animateTransform attributeName="transform"
                                                    type="rotate"
                                                    begin="0s" dur="3s"
                                                    values="0 150 150; 0 150 150; 180 150 150"
                                                    keyTimes="0; 0.8; 1"
                                                    repeatCount="indefinite"/>
                                    </path>
                                    
                                    <!-- lower overlay - frame -->
                                    <path id="frame" d="M100,97 L200, 97 M100,203 L200,203" style="stroke:transparent; stroke-width:0; stroke-linecap:round">
                                      <animateTransform attributeName="transform"
                                                    type="rotate"
                                                    begin="0s" dur="3s"
                                                    values="0 150 150; 0 150 150; 180 150 150"
                                                    keyTimes="0; 0.8; 1"
                                                    repeatCount="indefinite"/>
                                    </path>
                                  </svg>
                                  
                                </svg>
                                {{-- <p class="mt-3 title-success">@lang('customer.kredivo.status.process')</p> --}}
                                <p class="mt-3 title-success">{{ $res['message'] }}</p>
                                @break
                            @default
                                <img src="{{asset('img/static/payment/envelope-success.png')}}" alt="">
                                <p class="mt-5 title-success">{{ trans('success.thanks') }}</p>
                                <p>{{ trans('success.opening') }} <span class="text-primary">{{$order->invoice_no}}</span> {{ trans('success.sent') }}
                                    <span class="text-primary">{{$order->customer->email}}</span>. {{ trans('success.call_us') }}
                                </p>
                                <div class="row">
                                    <div class="col-md-8 offset-md-2 mb-3">
                                        <div class="row bg-light-blue">
                                            <div class="col-lg-6 p-3 border-right">

                                                <img style="vertical-align: sub"
                                                     src="{{asset('img/static/payment/whatsapp.png')}}" alt=""> {{ trans('success.whatsapp') }}


                                            </div>
                                            <div class="col-lg-6 p-3">
                                                <img style="vertical-align: sub"
                                                     src="{{asset('img/static/payment/envelope-alt.png')}}" alt=""> {{ trans('success.email') }}
                                                {{$company->email_company?$company->email_company:'info@gomodo.tech'}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="bg-white p-4">
                        <img src="{{ asset('img/static/payment/akulaku.png') }}" alt="Aku Laku" width="150" />
                        <div class="text-muted mt-3">
                            Waktu Pemesanan: {{ $order->created_at }}
                        </div>
                        <hr />
                        <div class="my-3">
                            <div class="text-muted">@lang('customer.kredivo.form.first_name')</div>
                            <h5>{{ $order->customer->first_name }}</h5>
                        </div>
                        <div class="my-3">
                            <div class="text-muted">@lang('customer.kredivo.form.phone_number')</div>
                            <h5>{{ $order->customer->phone }}</h5>
                        </div>
                        <div class="my-3">
                            <div class="text-muted">@lang('customer.kredivo.form.email')</div>
                            <h5>{{ $order->customer->email }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
    function checkOrder() {
        $.ajax({
          url: '{{route('invoice.check-order')}}',
          data: {
            'invoice_no': '{{$order->invoice_no}}'
          },
          success: function (data) {
          },
          error: function (e) {
            if (e.responseJSON.result !== undefined) {
              window.location = e.responseJSON.result.redirect
            }
          }
        })
    }
    setInterval(checkOrder, 1000);
</script>
@endsection

