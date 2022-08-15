@extends('klhk.customer.master.index')

@section('content')
    {!! Form::open(['autocomplete'=>'off']) !!}
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
                <li><a href="{{route('memoria.retrieve')}}">Retrieve Booking</a>
                </li>
                <li><a>{!! trans('retrieve_booking.booking_information') !!}</a></li>
            </ul>
        </div>
        <div id="product-booking" class="container pb-5">
            <div class="card">
                <div class="card-header" style="height: auto; padding: 20px;background-color: white">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="bold">{!! trans('retrieve_booking.booking_information') !!}</h3>
                            <div class="">Invoice No : {{$order->invoice_no}}</div>
                        </div>
                        <div class="col-sm-6 text-right">
                            <label for="">Status</label>
                            @if (isset($order->customer_manual_transfer) && $order->status == 0)
                                <button type="button" class="btn btn-warning" disabled>
                                    {{ $order->listManualTransfer()[$order->customer_manual_transfer->status] }}
                                </button>
                            @else
                                @php
                                    switch ($order->status){
                                        case '0':
                                            $btn = 'btn-amber';
                                        break;
                                        case '1':
                                            $btn = 'btn-success';
                                        break;
                                        case '2':
                                            $btn = 'btn-warning';
                                        break;
                                        case '3':
                                            $btn = 'btn-warning';
                                        break;
                                        case '4':
                                            $btn = 'btn-success';
                                        break;
                                        case '5':
                                            $btn = 'btn-danger';
                                        break;
                                        case '6':
                                            $btn = 'btn-danger';
                                        break;
                                        case '7':
                                            $btn = 'btn-danger';
                                        break;
                                        case '8':
                                            $btn = 'btn-warning';
                                        break;
                                        default:
                                            $btn = 'btn-default';
                                    }
                                @endphp

                                <button type="button"
                                        class="btn {{$btn}}" disabled>{{$order->list_status()[$order->status]}}</button>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <img class="img-fluid"
                                 src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                 alt="">
                        </div>
                        <div class="col-lg-10">
                            <h3>{{$product->product_name}}</h3>
                            <div class="box-product-tags py-3">
                                @if($product->tagValue)
                                    @forelse($product->tagValue->take(2) as $tag)
                                        @if($tag->tag)
                                            @if (App::getLocale() ==='id')
                                                <span class="badge badge-warning product-tags">{{$tag->tag->name_ind}}</span>
                                            @else
                                                <span class="badge badge-warning product-tags">{{$tag->tag->name}}</span>
                                            @endif

                                        @endif
                                    @empty
                                        <span class="badge badge-warning product-tags">Uncategorized</span>
                                    @endforelse
                                @endif
                            </div>
                            <div class="table-product">
                                @if($product->booking_type =='online')
                                    <span>
                                        <img src="{{asset('img/pin.png')}}" alt="">
                                    </span>
                                    <span class="mr-2 fs-smaller">
                                        @if(app()->getLocale() == 'id')
                                            {{ $product->city?$product->city->city_name:'-' }}
                                        @else
                                            {{ $product->city?$product->city->city_name_en:'-' }}
                                        @endif

                                    </span>
                                    <span>
                                        <img src="{{asset('img/calendar.png')}}" alt="">
                                    </span>
                                    <span class="mr-2 fs-smaller">
                                        {{\Carbon\Carbon::parse($order->order_detail->schedule_date)->format('d M Y')}}
                                    </span>
                                    <span>
                                        <img src="{{asset('img/person.png')}}" alt="">
                                    </span>
                                    <span class="mr-2 fs-smaller">
                                        {{ $order->order_detail->adult}}
                                        {{ optional($order->order_detail->unit)->name }}
                                    </span>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card mt-3" id="booking-guest">
                <div class="card-body">
                    <h3 class="bold">Guest</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="full_name" class="form-control" name="full_name" readonly
                                       value="{{$order->customer_info->first_name}}">
                                <label for="full_name">{!! trans('customer.book.full_name') !!} *</label>
                            </div>
                            <div class="md-form __parent_form">
                                <input type="email" id="email" class="form-control" name="email" readonly
                                       value="{{$order->customer_info->email}}">
                                <label for="email">Email *</label>
                            </div>
                            {{--                            <div class="md-form __parent_form input-group mb-3">--}}
                            {{--                                <div class="input-group-prepend">--}}
                            {{--                                    <select name="identity_number_type" id="" class="mdb-select">--}}
                            {{--                                        @if($order->customer_info->identity_number_type =='ktp')--}}
                            {{--                                            <option value="ktp">KTP</option>--}}
                            {{--                                        @else--}}
                            {{--                                            <option value="passport">Passport</option>--}}
                            {{--                                        @endif--}}

                            {{--                                    </select>--}}
                            {{--                                </div>--}}
                            {{--                                <input type="text" readonly value="{{$order->customer_info->identity_number}}"--}}
                            {{--                                       name="identity_number" class="form-control number md-form--with-placeholder"--}}
                            {{--                                       aria-label="Text input with dropdown button"--}}
                            {{--                                       placeholder="{{trans('customer.book.identity_number')}}">--}}
                            {{--                                --}}{{--                                <label for="identity_number">{{trans('customer.book.identity_number')}} *</label>--}}
                            {{--                            </div>--}}
                            {{--                            @if($order->customer_info->city)--}}
                            {{--                                <div class="md-form __parent_form">--}}
                            {{--                                    <input type="text" id="address" class="form-control" name="address" readonly--}}
                            {{--                                           value="{{$order->customer_info->city->state->country->country_name}}">--}}
                            {{--                                    <label for="address">{!! trans('order_provider.country') !!} *</label>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                        </div>
                        <div class="col-lg-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="phone_number" class="form-control" name="phone_number" readonly
                                       value="{{$order->customer->phone}}">
                                <label for="phone_number">{!! trans('customer.book.phone_number') !!} </label>
                            </div>
                            {{--                            <div class="md-form __parent_form">--}}
                            {{--                                <input type="text" id="address" class="form-control" name="address" readonly--}}
                            {{--                                       value="{{$order->customer_info->address}}">--}}
                            {{--                                <label for="address">{!! trans('customer.book.address') !!} *</label>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="md-form __parent_form">--}}
                            {{--                                <input type="text" id="emergency_number" readonly--}}
                            {{--                                       value="{{$order->customer_info->emergency_number}}" class="form-control number"--}}
                            {{--                                       name="emergency_number">--}}
                            {{--                                <label for="emergency_number">{{trans('customer.book.emergency_number')}} *</label>--}}
                            {{--                            </div>--}}
                            {{--                            @if($order->customer_info->city)--}}
                            {{--                                <div class="md-form __parent_form">--}}
                            {{--                                    <input type="text" id="address" class="form-control" name="address" readonly--}}
                            {{--                                           value="{{$order->customer_info->city->city_name}}">--}}
                            {{--                                    <label for="address">{!! trans('booking.city') !!} *</label>--}}
                            {{--                                </div>--}}
                            {{--                            @endif--}}
                        </div>
                    </div>
                    <h3 class="bold">{!! trans('customer.book.note_to_provider') !!}</h3>
                    {{--<p class="bold">{!! trans('customer.book.you_could') !!}</p>--}}
                    <div class="row">
                        <div class="col-12">
                            <div class="md-form __parent_form">
                                {{-- <textarea name="note" id="note" cols="30" rows="3"
                                          class="form-control md-textarea"
                                          readonly>{{$order->external_notes}}</textarea> --}}
                                <div class="external-notes" name="note">
                                    {!!$order->external_notes!!}
                                </div>
                                <label for="note"
                                       style="color: #757575; top: -1.5rem">{!! trans('customer.book.your_message') !!}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!empty($order->customer_manual_transfer))
                <div class="card mt-3">
                    <div class="card-body">
                        <h3 class="bold">{{ trans('retrieve_booking.bca_manual.title') }}</h3>
                        <div class="row mt-4">
                            <div class="col-lg-4">
                                <img class="img-fluid"
                                     src="{{asset($order->customer_manual_transfer->upload_document)}}"
                                     alt="">
                            </div>
                            <div class="col-lg-8">
                                <div class="md-form __parent_form">
                                    <input type="text" id="bank_name" class="form-control" name="bank_name" readonly
                                           value="{{$order->customer_manual_transfer->bank_name}}">
                                    <label for="bank_name">{!! trans('customer.bca_manual.sender_account_name') !!} </label>
                                </div>
                                <div class="md-form __parent_form">
                                    <input type="text" id="no_rekening" class="form-control" name="no_rekening" readonly
                                           value="{{$order->customer_manual_transfer->no_rekening}}">
                                    <label for="no_rekening">{!! trans('customer.bca_manual.account_number') !!} </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card mt-3" id="booking-price">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.price_details') !!}</h3>
                    @if($order->booking_type =='online')
                        <table class="table table-borderless mt-5 tbl-no-padding">
                            <tr>
                                <td>
                                    {{$order->order_detail->adult}}
                                    {{ optional($order->order_detail->unit)->name }}
                                </td>
                                <td class="text-right bold">
                                    {{$product->currency}} {{format_priceID($order->amount,'')}}
                                </td>
                            </tr>

                        </table>
                    @else
                        <table class="table table-borderless mt-5 tbl-no-padding">
                            @foreach($order->invoice_detail as $item)
                                <tr>
                                    <td>
                                        {{$item['qty']}}  {{$item['description']}}
                                    </td>
                                    <td class="text-right bold">
                                        {{$product->currency}} {{format_priceID($item['qty']* $item['price'],'')}}
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    @endif
                    @if($order->order_detail->discount_name && $order->order_detail->discount_amount>0)
                        <table class="table table-borderless tbl-no-padding">
                            <tr>
                                <td>
                                    {{$order->order_detail->discount_name}}
                                </td>
                                <td class="text-right bold">
                                    {{$product->currency}} {{format_priceID($order->product_discount,'-')}}
                                </td>
                            </tr>
                        </table>
                    @endif
                    @if($order->id_voucher)
                        <table class="table table-borderless tbl-no-padding">
                            <tr>
                                <td>
                                    {{'Voucher '.$order->voucher_code}}
                                </td>
                                <td class="text-right bold">
                                    {{$product->currency}} {{format_priceID($order->voucher_amount,'-')}}
                                </td>
                            </tr>
                        </table>
                    @endif
                    {{--{{dd($order->payment)}}--}}
                    @if ($order->fee > 0 || $order->fee_credit_card > 0)
                            <table class="table table-borderless tbl-no-padding">
                        @if($order->payment->payment_gateway =='Xendit Credit Card')
                                <tr>
                                    <td>
                                        {{ $payment->name_payment }} {{ trans('booking.charge') }} ({{ $payment->type == 'percentage' ? $payment->pricing_primary .'%' : 'IDR '. $payment->pricing_primary }})
                                    </td>
                                    <td class="text-right bold">

                                        {{$product->currency}} {{format_priceID($order->fee_credit_card,'')}}
                                    </td>
                                </tr>
                        @else
                                <tr>
                                    <td>
                                        {{ $payment->name_payment }} {{ trans('booking.charge') }} ({{ $payment->type == 'percentage' ? $payment->pricing_primary .'%' : 'IDR '. $payment->pricing_primary }})
                                    </td>
                                    <td class="text-right bold">
                                        {{$product->currency}} {{format_priceID($order->fee,'')}}
                                    </td>
                                </tr>
                        @endif
                            </table>
                    @endif
                </div>
                <div class="card-footer bg-white">
                    <table class="table table-borderless tbl-no-padding">
                        <tr>
                            <td>
                                <h3 class="bold">{!! trans('customer.book.grand_total') !!}</h3>
                            </td>
                            <td class="text-right bold fs-20" id="grandTotal">
                                {{$product->currency}} {{format_priceID($order->total_amount,'')}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card mt-3" id="choose-payment">
                <div class="card-body">
                    <h3 class="bold mb-4">{!! trans('customer.invoice.select_bank_transfer_method') !!}</h3>
                    <div class="custom-control custom-radio">
                        @if (isset($payment))
                            <input type="radio" class="custom-control-input" name="payment_method"
                                   value="{{ $payment->code_payment }}" checked>
                            <label class="custom-control-label" for="">
                                @if(app()->getLocale() == 'id')
                                    {{ $payment->name_payment }}
                                @else
                                    {{ $payment->name_payment_eng }}
                                @endif
                            </label>
                            <img src="{{ asset($payment->image_payment) }}" class="img-fluid list_payment midtrans-img"
                                 alt="">
                            @if($payment->code_payment === 'gopay')
                                <span class="badge badge-pill orange" data-toggle="tooltip" data-placement="top"
                                      title="{{ \trans('booking.tooltip_gopay') }}"><i class="fas fa-exclamation"
                                                                                       aria-hidden="true"></i></span>
                            @endif
                        @else
                            <input type="radio" class="custom-control-input" name="payment_method"
                                   value="redeem" checked>
                            <label class="custom-control-label" for="">Redeem Voucher</label>
                        @endif
                    </div>
                    <hr class="list-row">
                    {{--                    @php--}}
                    {{--                        if ($order->payment->payment_gateway ==='Xendit Virtual Account'){--}}
                    {{--                        $methods = ['virtual_account'=>'Bank Transfer'];--}}
                    {{--                        }elseif($order->payment->payment_gateway ==='Xendit Credit Card'){--}}
                    {{--                         $methods = [--}}
                    {{--                            'credit_card'=>'Credit Card'--}}
                    {{--                        ];--}}
                    {{--                        }elseif ($order->payment->payment_gateway ==='Xendit Alfamart'){--}}
                    {{--                         $methods = [--}}
                    {{--                            'alfamart'=>'Alfamart'--}}
                    {{--                        ];--}}
                    {{--                        }elseif ($order->payment->payment_gateway ==='Xendit Virtual Account OVO'){--}}
                    {{--                          $methods = [--}}
                    {{--                            'ovo'=>'OVO'--}}
                    {{--                        ];--}}
                    {{--                        }elseif ($order->payment->payment_gateway ==='Redeem Voucher'){--}}
                    {{--                          $methods = [--}}
                    {{--                            'redeem'=>'Redeem Voucher'--}}
                    {{--                        ];--}}
                    {{--                        }elseif ($order->payment->payment_gateway ==='Midtrans Indomaret'){--}}
                    {{--                          $methods = [--}}
                    {{--                            'indomaret'=>'Midtrans Indomaret'--}}
                    {{--                        ];--}}
                    {{--                        }elseif ($order->payment->payment_gateway ==='Midtrans Gopay'){--}}
                    {{--                          $methods = [--}}
                    {{--                            'indomaret'=>'Midtrans Gopay'--}}
                    {{--                        ];--}}
                    {{--                        }--}}
                    {{--                        else{--}}
                    {{--                          $methods = [--}}
                    {{--                            'credit_card'=> trans('customer.book.cod')--}}
                    {{--                        ];--}}
                    {{--                        }--}}
                    {{--                    @endphp--}}
                    {{--                    {!! Form::select('payment_method',$methods,null,['class'=>'mdb-select md-form']) !!}--}}
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white">
        <div class="container py-3">
            <div class="row">
                <div class="col-12 text-right">
                    @if($order->status =='0')
                        @if($order->payment->payment_gateway ==='Xendit Virtual Account' || $order->payment->payment_gateway ==='Xendit Virtual Account OVO' )
                            <a class="btn btn-primary"
                               href="{{route('invoice.virtual-account',['invoice'=>$order->invoice_no])}}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway ==='Xendit Credit Card')
                            <a class="btn btn-primary"
                               href="{{route('invoice.virtual-account',['invoice'=>$order->invoice_no])}}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway === 'Xendit Alfamart')
                            <a class="btn btn-primary"
                               href="{{route('invoice.alfamart',['invoice'=>$order->invoice_no])}}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway === 'Midtrans Indomaret')
                            @if($order->payment->response)
                                <a class="btn btn-primary"
                                   href="{{route('invoice.indomaret',['invoice'=>$order->invoice_no])}}" type="button"
                                   id="booking-pay-now">
                                    {!! trans('customer.book.pay_now') !!}
                                </a>
                            @else
                                <button type="button" class="btn btn-primary"
                                        id="paynow">{!! trans('customer.book.pay_now') !!}</button>
                            @endif
                        @elseif($order->payment->payment_gateway === 'Midtrans Gopay')
                            @if($order->payment->response)
                                <a class="btn btn-primary"
                                   href="{{route('invoice.gopay',['invoice'=>$order->invoice_no])}}" type="button"
                                   id="booking-pay-now">
                                    {!! trans('customer.book.pay_now') !!}
                                </a>
                            @else
                                <button type="button" class="btn btn-primary"
                                        id="paynow">{!! trans('customer.book.pay_now') !!}</button>
                            @endif
                        @elseif($order->payment->payment_gateway === 'Midtrans Virtual Account BCA')
                            @if($order->payment->response)
                                <a class="btn btn-primary"
                                   href="{{route('invoice.midtrans-virtual-account',['invoice'=>$order->invoice_no])}}" type="button"
                                   id="booking-pay-now">
                                    {!! trans('customer.book.pay_now') !!}
                                </a>
                            @else
                                <button type="button" class="btn btn-primary"
                                        id="paynow">{!! trans('customer.book.pay_now') !!}</button>
                            @endif
                        @elseif($order->payment->payment_gateway === 'Midtrans AkuLaku')
                            @if($order->payment->response)
                                <a class="btn btn-primary"
                                   href="{{route('invoice.akulaku',['invoice'=>$order->invoice_no])}}"
                                   type="button"
                                   id="booking-pay-now">
                                    {!! trans('customer.book.pay_now') !!}
                                </a>
                            @else
                                <button type="button" class="btn btn-primary"
                                        id="paynow">{!! trans('customer.book.pay_now') !!}</button>
                            @endif
                        @elseif($order->payment->payment_gateway ==='Xendit Kredivo' && $order->payment->status == 'INCOMPLETE')
                            <a class="btn btn-primary"
                               href="{{ $order->payment->invoice_url }}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway ==='Xendit DANA')
                            <a class="btn btn-primary"
                               href="{{route('invoice.dana',['invoice'=>$order->invoice_no])}}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway ==='Xendit LinkAja')
                            <a class="btn btn-primary"
                               href="{{route('invoice.linkaja',['invoice'=>$order->invoice_no])}}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway ==='Xendit OVO')
                            <a class="btn btn-primary"
                               href="{{route('invoice.ovo',['invoice'=>$order->invoice_no])}}" type="button"
                               id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </a>
                        @elseif($order->payment->payment_gateway ==='Manual Transfer BCA')
                            @if (empty($order->customer_manual_transfer) || $order->customer_manual_transfer->status == 'rejected_reupload')
                                <a class="btn btn-primary"
                                    href="{{route('invoice.bank-transfer',['invoice'=>$order->invoice_no])}}" type="button"
                                    id="booking-pay-now">
                                    {!! trans('customer.book.pay_now') !!}
                                </a>
                            @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
@section('scripts')
    <script type="text/javascript" src="https://app.{{env('APP_ENV')!='production'?"sandbox.":""}}midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        function formatMoney(n, c, d, t) {
            var c = isNaN(c = Math.abs(c)) ? 2 : c,
                d = d == undefined ? "." : d,
                t = t == undefined ? "," : t,
                s = n < 0 ? "-" : "",
                i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
                j = (j = i.length) > 3 ? j % 3 : 0;

            return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
        };
        $('.mdb-select').materialSelect();

         @if($order->status === 0 && $order->payment_list == 'Midtrans Payment')
            $(document).on('click', '#paynow', function () {
                midtrans();
            });
            @if(!$order->payment->response)
                midtrans();
            @endif

            function midtrans() {
                loadingStart();
                snap.pay('{{ $order->payment->token_midtrans }}', {
                    onSuccess: function (result) {
                        // changeResult('success', result);
                        // console.log(result.status_message);
                        // console.log(result);
                        $.ajax({
                            url: '{{ route('midtrans.notification') }}',
                            data: result,
                            type: 'POST',
                            success: function (data) {
                                loadingFinish();
                                window.location.href = data.result.redirect;
                            }
                        });
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
                                loadingFinish();
                                window.location.href = data.data.url;
                            }
                        });

                    },
                    onError: function (result) {
                        // changeResult('error', result);
                        console.log(result);
                        // toastr.error(result.status_message, '{{__('general.whoops')}}')
                        $.ajax({
                            url: '{{ route('check.midtrans') }}',
                            data: {
                                result: result,
                                invoice_no: '{{ $order->invoice_no }}'
                            },
                            error: function (e) {
                                loadingFinish();
                                location.reload();
                            }
                        });
                        // $("#payment-form").submit();
                    },
                    onClose: function (result) {
                        loadingFinish();
                        console.log(result);
                        console.log('customer closed the popup without finishing the payment');
                    },
                    gopayMode: 'auto'
                });
            }
        @endif
    </script>
@stop
