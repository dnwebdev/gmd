@extends('customer.master.index')

@section('additionalStyle')
    <link href="{{asset('additional/select2/css/typography.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/textfield.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2.min.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/select2-bootstrap.css')}}" type="text/css" rel="stylesheet"/>
    <link href="{{asset('additional/select2/css/pmd-select2.css')}}" type="text/css" rel="stylesheet"/>
@endsection

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
                <li><a>{!! trans('customer.book.booking_form') !!}</a></li>
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
                                <span>
                                    <img src="{{asset('img/calendar.png')}}" alt="">
                                </span>
                                <span class="mr-2 fs-smaller">
                                     {{\Carbon\Carbon::parse($order->order_detail->schedule_date)->format('d M Y')}}
                                 </span>
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
            <div class="card mt-3" id="booking-guest">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.guest') !!}</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            {!! Form::hidden('invoice_no',$order->invoice_no) !!}
                            {!! Form::hidden('identity_number_type','ktp') !!}
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
                            {{-- <div class="md-form __parent_form input-group mb-3">
                                <div class="input-group-prepend">
                                    <select name="identity_number_type" id="" class="mdb-select">
                                        <option value="ktp">KTP</option>
                                        <option value="passport">Passport</option>
                                    </select>
                                </div>
                                <input type="text" name="identity_number"
                                       class="form-control number md-form--with-placeholder"
                                       aria-label="Text input with dropdown button"
                                       placeholder="{{trans('customer.book.identity_number')}}">
                            </div>
                            <div class="md-form __parent_form pmd-textfield pmd-textfield-floating-label">
                                <select class="select-with-search pmd-select2" searchable="Search here.."
                                        name="country">
                                    <option value="" disabled
                                            selected>{!! trans('customer.book.choose_country') !!}</option>
                                    @foreach(\App\Models\Country::all() as $country)
                                        <option value="{{$country->id_country}}" {{$country->id_country==102?'selected':''}}>{{$country->country_name}}</option>
                                    @endforeach
                                </select>
                            </div>--}}
                        </div>
                        <div class="col-lg-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="phone_number" class="form-control number" name="phone_number"
                                       readonly value="{{$order->customer_info->phone}}">
                                <label for="phone_number">{!! trans('customer.book.phone_number') !!} </label>
                            </div>
                            {{-- <div class="md-form __parent_form">
                                <input type="text" id="address" class="form-control" name="address">
                                <label for="address">{!! trans('customer.book.address') !!} </label>
                            </div>
                            <div class="md-form __parent_form">
                                <input type="text" id="emergency_number" class="form-control number"
                                       name="emergency_number">
                                <label for="emergency_number">{{trans('customer.book.emergency_number')}} </label>
                            </div>
                            <div class="md-form __parent_form pmd-textfield pmd-textfield-floating-label">
                                <select class="select-with-search pmd-select2 " searchable="Search here.." name="city">
                                    <option value="" disabled
                                            selected>{!! trans('customer.book.choose_city') !!}</option>
                                </select>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            @if($order->external_notes)
                <div class="card mt-3" id="booking-notes">
                    <div class="card-body">
                        <h3 class="bold">{!! trans('customer.book.note_to_customer') !!} :</h3>
                        <div class="row">
                            <div class="col-12">
                                <div class="md-form __parent_form mt-0">
                                    {{-- <textarea name="important_notes" id="" class="form-control p-3 sub-ajax" cols="30"
                                              readonly
                                              rows="10">{!!$order->external_notes!!}</textarea> --}}
                                    <div class="external-notes">
                                        {!!$order->external_notes!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{--            {{dd($order)}}--}}
            @if(!($order->payment && $order->payment->reference_number))
                <div class="card mt-3" id="promotion-code">
                    <div class="card-body">
                        <h3 class="bold">{!! trans('customer.book.having_promotion_code') !!}</h3>
                        <div class="row">
                            <div class="col">
                                <div class="md-form __parent_form">
                                    <input type="text" id="voucher_code" class="form-control" {{ $order->status != '6' ? '' : 'disabled' }}>
                                    {!! Form::hidden('voucher_code') !!}
                                    <label for="voucher_code">{!! trans('customer.book.promotion_code') !!}</label>
                                </div>
                            </div>
                            <div class="col-sm-auto mt-md-0 mt-3 voucher-button">
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-block" type="button" id="btn-apply-voucher" {{ $order->status != '6' ? '' : 'disabled' }}>
                                        {!! trans('customer.book.apply') !!}
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-auto mt-md-0 mt-3 voucher-button">
                                <div class="mt-3">
                                    <button class="btn btn-danger btn-block" type="button" id="btn-delete-voucher" {{ $order->status != '6' ? '' : 'disabled' }}>
                                        {!! trans('customer.book.delete') !!}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card mt-3" id="booking-price">
                <div class="card-body">
                    <h3 class="bold">{!! trans('customer.book.price_details') !!}</h3>
                    <table class="table table-borderless mt-5 tbl-no-padding">
                        {!! Form::hidden('booking_type','offline') !!}
                        @foreach($order->invoice_detail as $item)
                            <tr>
                                <td>
                                    {{$item['qty']}} {{$item['description']}}
                                </td>
                                <td class="text-right bold">
                                    {{format_priceID($item['price'] * $item['qty'],$product->currency)}}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    @if ($order->fee_credit_card > 0 || $order->fee > 0)
                        <table class="table table-borderless tbl-no-padding">
                            @if($order->payment->payment_gateway =='Xendit Credit Card')
                                <tr>
                                    <td>
                                        {{ $payment->name_payment }} {{ trans('booking.charge') }}
                                        ({{ $payment->type == 'percentage' ? $payment->pricing_primary .'%' : 'IDR '. number_format($payment->pricing_primary,0,'','.') }}
                                        )
                                    </td>
                                    <td class="text-right bold">
                                        {{$product->currency}} {{format_priceID($order->fee_credit_card,'')}}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        {{ $payment->name_payment }} {{ trans('booking.charge') }}
                                        ({{ $payment->type == 'percentage' ? $payment->pricing_primary .'%' : 'IDR '. number_format($payment->pricing_primary,0,'','.') }}
                                        )
                                    </td>
                                    <td class="text-right bold">
                                        {{$product->currency}} {{format_priceID($order->fee,'')}}
                                    </td>
                                </tr>
                            @endif
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
                    @if($order->voucher)
                        <table class="table table-borderless tbl-no-padding">
                            <tr>
                                <td>
                                    {{'Voucher '.$order->voucher->voucher_code}}
                                </td>
                                <td class="text-right bold">
                                    {{$product->currency}} {{format_priceID($order->voucher_amount,'-')}}
                                </td>
                            </tr>
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
                                {{format_priceID($order->total_amount,$product->currency)}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card mt-3" id="choose-payment">
                <div class="card-body">
                    <h3 class="bold mb-4">{!! trans('customer.book.payment_method') !!}</h3>
                    @if ($order->payment->reference_number || $order->payment->token_midtrans || $order->payment->response)
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
                                <img src="{{ asset($payment->image_payment) }}"
                                    class="img-fluid list_payment midtrans-img"
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
                    @else
                    @if ($order->status != '6')
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="md-form __parent_form" id="data">
                                    <input type="hidden" name="payment_list" value="" class="form-control">
                                    <input type="hidden" name="payment_method" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                        @foreach ($payment_list as $item2)
                            @foreach($item2->listPayments->when($product->booking_confirmation == 0, function($q){
                            return $q->where('code_payment', 'cod');
                            }, function($p){
                                return $p->reject(function($c){
                                    return $c->code_payment == 'cod';
                                });
                            })->when($company->domain_memoria !=='basecampadventureindonesia.gomodo.id' && (optional($company->kyc)->status !=='approved' || $product->allow_credit_card == '0'), function($j){
                                return $j->reject(function($o){
                                    return $o->code_payment == 'credit_card';
                                });
                            })->when((optional($company->transfer_manual)->status !=='approved'), function($a){
                                return $a->reject(function($b){
                                    return $b->code_payment == 'bca_manual';
                                });
                            }) as $item)
                                <div class="custom-control custom-radio">
                                    @if($product->booking_confirmation =='0' && $item->code_payment == 'cod')
                                        <input type="radio"
                                            class="custom-control-input {{ $item->categoryPayment->name_third_party_eng }}"
                                            id="{{ $item->code_payment }}" name="pricing_primary"
                                            @if ($item->code_payment == 'kredivo' && ($order->total_amount < 10000 || $order->total_amount > 30000000))
                                            disabled
                                            @endif
                                            value="{{ $item->pricing_primary }}">
                                        <label class="custom-control-label" for="{{ $item->code_payment }}">
                                            @if(app()->getLocale() == 'id')
                                                {{ $item->name_payment }}
                                            @else
                                                {{ $item->name_payment_eng }}
                                            @endif
                                        </label>
                                        <img src="{{ asset($item->image_payment) }}"
                                            class="img-fluid list_payment midtrans-img" alt="">
                                    @else
                                        <input type="radio"
                                            class="custom-control-input {{ $item->categoryPayment->name_third_party_eng }}"
                                            id="{{ $item->code_payment }}" name="pricing_primary"
                                            value="{{ $item->pricing_primary }}">
                                        <label class="custom-control-label" for="{{ $item->code_payment }}">
                                            @if(app()->getLocale() == 'id')
                                                {{ $item->name_payment }}
                                            @else
                                                {{ $item->name_payment_eng }}
                                            @endif
                                        </label>
                                        <img src="{{ asset($item->image_payment) }}"
                                            class="img-fluid list_payment midtrans-img"
                                            alt="">
                                        @if($item->code_payment === 'gopay')
                                            <span class="badge badge-pill orange" data-toggle="tooltip"
                                                data-placement="top"
                                                title="{{ \trans('booking.tooltip_gopay') }}"><i
                                                        class="fas fa-exclamation" aria-hidden="true"></i></span>
                                        @endif
                                    @endif
                                </div>
                                <hr class="list-row">
                            @endforeach
                        @endforeach
                    @endif
                    @endif
                </div>
            </div>
            
            <div class="card mt-3" id="kredivo_form" style="display: none;">
                <div class="card-body">
                    <img src="{{ asset('img/kredivo.png') }}" alt="Kredivo" width="150" />
                    <h3 class="mt-4">@lang('customer.kredivo.booking.personal_data_title')</h3>
                    <h6 class="mt-2">@lang('customer.kredivo.booking.personal_data_subtitle')</h6>
                    <div class="row">
                        @php
                         $full_name = explode(' ', $order->customer_info->first_name, 2);
                         $first_name = $full_name[0];
                         $last_name = $full_name[1] ?? $full_name[0]; 
                        @endphp
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_first_name" class="form-control" name="kredivo_first_name" value="{{ $first_name }}">
                                <label for="kredivo_first_name">@lang('customer.kredivo.form.first_name') *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_last_name" class="form-control" name="kredivo_last_name" value="{{ $last_name }}">
                                <label for="kredivo_last_name">@lang('customer.kredivo.form.last_name') *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_phone_number" class="form-control" name="kredivo_phone_number" value="{{ $order->customer_info->phone }}">
                                <label for="kredivo_phone_number">@lang('customer.kredivo.form.phone_number') *</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="md-form __parent_form">
                                <input type="email" id="kredivo_email" class="form-control" name="kredivo_email" value="{{ $order->customer_info->email }}">
                                <label for="kredivo_email">@lang('customer.kredivo.form.email') *</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="md-form __parent_form">
                                <textarea name="kredivo_address" id="kredivo_address" cols="30" rows="2"
                                          class="form-control md-textarea" maxlength="300"></textarea>
                                <label for="kredivo_address">@lang('customer.kredivo.form.address') *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pmd-textfield pmd-textfield-floating-label">
                                <label for="kredivo_state">@lang('customer.kredivo.form.state') *</label>
                                <select id="kredivo_state" name="kredivo_state" class="select-with-search pmd-select2">
                                    <option selected disabled value=""></option>
                                    @foreach ($states as $state)
                                    <option value="{{ $state['id_state'] }}">{{ $state['state_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="pmd-textfield pmd-textfield-floating-label">
                                <label for="kredivo_city">@lang('customer.kredivo.form.city') *</label>
                                <select id="kredivo_city" name="kredivo_city" class="select-with-search pmd-select2">
                                    <option selected disabled value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="md-form __parent_form">
                                <input type="text" id="kredivo_postal_code" class="form-control number" name="kredivo_postal_code" maxlength="5">
                                <label for="kredivo_postal_code">@lang('customer.kredivo.form.postal_code') *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="mdb-select md-form" name="kredivo_installment_duration">
                                <option value="" disabled selected>@lang('customer.kredivo.form.installment_duration') *</option>
                                @foreach (\App\Models\PaymentKredivo::$durations as $duration => $label)
                                    <option value="{{ $duration }}" {{ ($order->total_amount < 1000000 && $duration != '30_days') || ($order->total_amount > 3000000 && $duration == '30_days') || $order->total_amount > 30000000  ? 'disabled' : '' }}>{{  preg_replace_callback('/\{\{(.*)\}\}/', function($matches) {return trans('duration.'.$matches[1]);}, $label) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($order->status != '6')
        <div class="bg-white">
            <div class="container py-3">
                <div class="row">
                    <div class="col-12 text-md-right text-center">
                    @if ($order->payment->reference_number || $order->payment->token_midtrans || $order->payment->response)
                            @if($order->status =='0')
                                @if($order->payment->payment_gateway ==='Xendit Virtual Account' || $order->payment->payment_gateway ==='Xendit Virtual Account OVO' )
                                    <a class="btn btn-primary"
                                    href="{{route('invoice.virtual-account',['invoice'=>$order->invoice_no])}}"
                                    type="button"
                                    id="booking-pay-now">
                                        {!! trans('customer.book.pay_now') !!}
                                    </a>
                                @elseif($order->payment->payment_gateway ==='Xendit Credit Card')
                                    <a class="btn btn-primary"
                                    href="{{route('invoice.virtual-account',['invoice'=>$order->invoice_no])}}"
                                    type="button"
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
                                        href="{{route('invoice.indomaret',['invoice'=>$order->invoice_no])}}"
                                        type="button"
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
                                        href="{{route('invoice.midtrans-virtual-account',['invoice'=>$order->invoice_no])}}"
                                        type="button"
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
                                @elseif($order->payment->payment_gateway === 'Xendit OVO')
                                        <a class="btn btn-primary" 
                                        href="{{route('invoice.ovo',['invoice'=>$order->invoice_no])}}" type="button"
                                        id="booking-pay-now">
                                            {!! trans('customer.book.pay_now') !!}
                                        </a>
                                @elseif($order->payment->payment_gateway === 'Xendit DANA')
                                        <a class="btn btn-primary"
                                        href="{{route('invoice.dana',['invoice'=>$order->invoice_no])}}" type="button"
                                        id="booking-pay-now">
                                            {!! trans('customer.book.pay_now') !!}
                                        </a>
                                @elseif($order->payment->payment_gateway === 'Xendit LinkAja')
                                        <a class="btn btn-primary"
                                        href="{{route('invoice.linkaja',['invoice'=>$order->invoice_no])}}" type="button"
                                        id="booking-pay-now">
                                            {!! trans('customer.book.pay_now') !!}
                                        </a>
                                @elseif($order->payment->payment_gateway === 'Manual Transfer BCA')
                                    @if (empty($order->customer_manual_transfer) || $order->customer_manual_transfer->status == 'rejected_reupload')
                                        <a class="btn btn-primary"
                                        href="{{route('invoice.bank-transfer',['invoice'=>$order->invoice_no])}}"
                                        type="button"
                                        id="booking-pay-now">
                                            {!! trans('customer.book.pay_now') !!}
                                        </a>
                                    @endif
                                @endif
                            @endif
                        @else
                            <button class="btn btn-primary" type="button" id="booking-pay-now">
                                {!! trans('customer.book.pay_now') !!}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    {!! Form::close() !!}
@stop
@section('scripts')
    <script src="{{asset('additional/select2/js/global.js')}}"></script>
    <script src="{{asset('additional/select2/js/textfield.js')}}"></script>
    <script src="{{asset('additional/select2/js/select2.full.js')}}"></script>
    <script src="{{asset('additional/select2/js/pmd-select2.js')}}"></script>
    <script type="text/javascript" src="https://app.{{env('APP_ENV')!='production'?"sandbox.":""}}midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        var change = {
            additional: 0,
            voucher: 0
        };
        $(document).ready(function () {
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

        $(document).on("keydown", ".number", function (e) {
            // Allow: backspace, delete, tab, escape, enter and .(190)
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 187 && (e.shiftKey === true || e.metaKey === true)) ||
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode === 189 && (e.shiftKey === false || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 109) || (e.keyCode === 106 || e.keyCode === 110)) {
                e.preventDefault();
            }
        });
        {{--$(document).on('change', 'select[name=city]', function () {--}}
        {{--    let t = $(this);--}}
        {{--    let c = $('select[name=country]');--}}
        {{--    loadingStart();--}}
        {{--    c.find('option').remove();--}}
        {{--    $.ajax({--}}
        {{--        url: "{{route('city.byCountry')}}",--}}
        {{--        data: {id: t.val()},--}}
        {{--        dataType: 'json',--}}
        {{--        success: function (data) {--}}
        {{--            let html = '<option disabled selected>Choose your city</option>';--}}
        {{--            $.each(data, function (i, e) {--}}
        {{--                html += '<option value="' + e.id_city + '">' + e.city_name + '</option>';--}}
        {{--            })--}}
        {{--            c.append(html);--}}
        {{--            loadingFinish();--}}
        {{--        },--}}
        {{--        error:function (e) {--}}
        {{--            console.log(e);--}}
        {{--            loadingFinish();--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}

        $('select[name=city]').select2({
            theme: "bootstrap",
            ajax: {
                url: "{{route('city.byCountry')}}",
                dataType: 'json',
                delay: 250,
                type: 'GET',
                data: function (params) {
                    return {
                        country_id: $('select[name=country]').val(),
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },

            minimumInputLength: 3,
            templateResult: formatRepo2,
            templateSelection: formatRepoSelection2
        });

        function formatRepo2(repo) {
            if (repo.loading) {
                return 'Looking for cities';
            }

            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>City : " + repo.text + "</div>" +
                "<div class='select2-result-repository__title'>State :" + repo.state + "</div>" +
                "</div>";

            return markup;
        }

        function formatRepoSelection2(repo) {
            if (repo.city_name) {
                return repo.city_name
            } else {
                return repo.text;
            }

        }

        $(document).on('change', 'select[name=identity_number_type]', function () {
            let t = $(this);
            $('input[name=identity_number]').removeClass('number');
            if (t.val() === 'ktp') {
                $('input[name=identity_number]').addClass('number')
            }
        });

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
        @else
        $(document).on('click', '#booking-pay-now', function () {
            loadingStart();
            let f = $(this).closest('form');
            $('label.error').remove();
            $.ajax({
                url: "{{route('customer.pay')}}",
                type: 'POST',
                data: f.serialize(),
                dataType: 'json',
                success: function (data) {
                    console.log(data)
                    // window.location.href = window.location.origin + '/invoice/make_payment/' + data.data.invoice
                    window.location.href = data.data.url
                    loadingFinish();
                },
                error: function (e) {
                    console.log(e)
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            // if (i===0){
                            //     $(document).find('input[name=' + i + ']').focus();
                            //     $(document).find('select[name=' + i + ']').focus();
                            // }
                            $(document).find('input[name=' + i + ']').closest('.md-form').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name=' + i + ']').closest('.select-wrapper.md-form').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name=' + i + ']').closest('.pmd-textfield-floating-label').append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                    loadingFinish();
                    $('html, body').animate({
                        scrollTop: 0
                    }, 1000);
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                }
            })
        });

        const list_data = $('input[name=payment_method]');
        $(document).on('click', '#choose-payment input[type=radio]', function () {
            list_data.val($(this).attr('id'));
            const payment_list = $('input[name=payment_list]');
            if ($(this).hasClass('Xendit Payment')) {
                payment_list.val('Xendit Payment')
            } else if ($(this).hasClass('Midtrans Payment')) {
                payment_list.val('Midtrans Payment')
            } else if ($(this).hasClass('Manual Transfer')) {
                payment_list.val('Manual Transfer')
            } else {
                payment_list.val('COD')
            }
            $('#credit_card_charge').remove();
            $('#gopay_charge').remove();
            $('#indomaret_charge').remove();
            $('#dana_charge').remove();
            $('#linkaja_charge').remove();
            $('#bca_va_charge').remove();
            $('#ovo_live_charge').remove();
            $('#akulaku_charge').remove();
            change.additional = 0;
            calculatePricing();
        });

        {{--$(document).on('change', 'select[name=payment_method]', function () {--}}
        {{--    let select = $(this);--}}
        {{--    if (select.val() === 'credit_card') {--}}
        {{--        let current = parseFloat("{{$order->total_amount}}");--}}
        {{--        let grand = (current - change.voucher);--}}
        {{--        change.additional = Math.ceil(((100 / 97.1) * grand) - grand);--}}
        {{--        calculatePricing();--}}
        {{--        let tableBooking = $('#booking-price .card-body');--}}
        {{--        tableBooking.find('#creditcard_charge').remove();--}}
        {{--        let html = ' <table class="table table-borderless tbl-no-padding" id="creditcard_charge">\n' +--}}
        {{--            '                        <tr>\n' +--}}
        {{--            '                            <td>\n' +--}}
        {{--            '                                Credit Card Charge (2.9 %)' +--}}
        {{--            '                            </td>\n' +--}}
        {{--            '                            <td class="text-right bold" id="voucher_value">\n' +--}}
        {{--            '{{$product->currency}}' + ' ' + formatMoney(change.additional, 0, '.', '.')--}}
        {{--        '                            </td>\n' +--}}
        {{--        '                        </tr>\n' +--}}
        {{--        '                    </table>';--}}
        {{--        tableBooking.append(html);--}}

        {{--    } else {--}}
        {{--        $('#creditcard_charge').remove();--}}
        {{--        change.additional = 0;--}}
        {{--        calculatePricing();--}}
        {{--    }--}}
        {{--});--}}

        @foreach ($payment_list as $item2)
        @foreach($item2->listPayments as $item)
        @php
            $companyPayment = $item->companies()->where('id_company', $company->id_company)->first();
        @endphp
            $(document).on('click', '#{{ $item->code_payment }}', function () {
                let select = $(this);
                list_data.val('{{ $item->code_payment }}');
                let tableBooking = $('#booking-price .card-body');
                let tableHtml2 = $('#choose-payment #data');
                tableBooking.find('#{{ $item->code_payment }}_charge').remove();
                tableHtml2.find('#pricing').remove();
                calculatePricing();
                @if($item->pricing_primary > 0 || $item->pricing_primary > 0)
                    @if($companyPayment->pivot->charge_to === 1)
                        let html2 = '<div id="pricing">' +
                            '<input type="hidden" name="pricing_secondary" value="{{ $item->pricing_secondary }}" id="{{ $item->type_secondary }}">' +
                            '<input type="hidden" name="pricing_primary" value="{{ $item->pricing_primary }}" id="{{ $item->type }}">' +
                            '<input type="hidden" name="charge_to" value="{{ $companyPayment->pivot->charge_to }}">' +
                            '</div>';
                        tableHtml2.append(html2);
                        calculatePricing();
                        let html = ' <table class="table table-borderless tbl-no-padding" id="{{ $item->code_payment }}_charge">\n' +
                            '                        <tr>\n' +
                            '                            <td>\n' +
                            '                                {{ $item->name_payment }} {{ trans('booking.charge') }} ({{ $item->type == "percentage" ? $item->pricing_primary ."%" : "IDR ". number_format($item->pricing_primary,0,'','.') }}{{ $item->pricing_secondary > 0 ? ' + ' . ($item->secondary_type == "percentage" ? $item->pricing_secondary ."%" : "IDR ". number_format($item->pricing_secondary,0,'','.')) : ''}})' +
                            '                            </td>\n' +
                            '                            <td class="text-right bold" id="gopay_value">\n' +
                            '{{$product->currency}}' + ' ' + formatMoney(change.additional, 0, '.', '.') +
                            '                            </td>\n' +
                            '                        </tr>\n' +
                            '                    </table>';
                        tableBooking.append(html);
                    @endif
                @endif
            });

            @endforeach
        @endforeach

        function calculatePricing() {
            let current = parseFloat("{{$order->total_amount}}");
            let grand = (current - change.voucher);
            // let dataCard = $('input[name=pricing_primary]:checked').val();
            let charge_to = $('input[name=charge_to]').val();
            dataCard = $('input[name=pricing_primary]');
            dataSecondary = $('input[name=pricing_secondary]');
            // let list = ['credit_card', 'gopay', 'indomaret', 'dana', 'linkaja', 'bca_va'];
            // if (list.includes(list_data.val())) {
            // } else {
            //     change.additional = 0;
            // }
            if(charge_to === '1'){
                if(dataCard.attr('id') === 'percentage'){
                    change.additional = Math.ceil(((100 / (100 - dataCard.val())) * grand) - grand);
                    // change.additional += parseInt(dataSecondary.val());
                } else {
                    // change.additional = parseInt(dataCard.val()) + parseInt(dataSecondary.val());
                    change.additional = parseInt(dataCard.val());
                }

                if (dataSecondary.attr('id') == 'percentage') {
                    change.additional += Math.ceil(((100 / (100 - dataSecondary.val())) * grand) - grand);
                } else {
                    change.additional += parseInt(dataSecondary.val());
                }
            } else {
                change.additional = 0;
            }
            grand = grand + change.additional;
            $('#grandTotal').html('{{$product->currency}}' + ' ' + formatMoney(grand, 0, '.', '.'))
        }

        @endif

        $(document).on('keypress change search input paste cut', 'input, select', function (evt) {
            $(this).closest('.__parent_form').find('label.error').remove();
        });

        $(document).ready(function () {
            $("select[name=country]").select2({
                theme: "bootstrap",
            });
            $(".select-simple").select2({
                theme: "bootstrap",
                minimumResultsForSearch: Infinity,
            });
        })
        $('.mdb-select').materialSelect();
        $(document).on('click', '#btn-apply-voucher', function () {
            loadingStart();
            let tableBooking = $('#booking-price .card-body');
            tableBooking.find('#discount_voucher').remove();
            $('#voucher_code').closest('.md-form').find('label.error').remove();
            $('#grandTotal').html('{{$order->total_amount}}')
            $.ajax({
                url: '{{route('customer.check.voucher')}}',
                data: {
                    id_product: "{{$product->id_product}}",
                    voucher_code: $('#voucher_code').val(),
                    amount: "{{$order->total_amount}}"
                },
                success: function (data) {
                    let html = ' <table class="table table-borderless tbl-no-padding" id="discount_voucher">\n' +
                        '                        <tr>\n' +
                        '                            <td>\n' +
                        '                                Voucher Discount' +
                        '                            </td>\n' +
                        '                            <td class="text-right bold" id="voucher_value">\n' +
                        '                                ' + data.discount_text
                    '                            </td>\n' +
                    '                        </tr>\n' +
                    '                    </table>';
                    tableBooking.append(html);
                    $('input[name=voucher_code]').val($('#voucher_code').val())
                    $('#voucher_code').closest('.md-form').removeClass('failed').addClass('checked');
                    change.voucher = data.discount;
                    showDeleteVoucher();
                    calculatePricing();
                    loadingFinish();

                },
                error: function (e) {
                    tableBooking.find('#discount_voucher').remove();
                    change.voucher = 0;
                    calculatePricing();
                    $('#voucher_code').closest('.md-form').removeClass('success').addClass('failed');
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            $(document).find('input[name=' + i + ']').closest('.md-form').append('<label class="error">' + e[0] + '</label>');
                            $(document).find('select[name=' + i + ']').closest('.select-wrapper.md-form').append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                    loadingFinish();
                }
            })
        });

        // Delete Voucher Button
        $(document).on('click', '#btn-delete-voucher', function () {
            var el_voucher_code = $('#voucher_code');
            el_voucher_code.val('');
            $('input[name=voucher_code]').val('');
            el_voucher_code.closest('.md-form').find('label.error').remove();
            el_voucher_code.closest('.md-form').removeClass('failed');
            el_voucher_code.closest('.md-form').removeClass('checked');
            $('#booking-price .card-body').find('#discount_voucher').remove();
            change.voucher = 0;
            calculatePricing();
            hideDeleteVoucher();
        })

        $('.select-with-search').select2({
            theme: 'bootstrap'
        });
        $('#kredivo_state').on('change', function() {
            $.ajax({
                url : "{{ route('location.cities') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id_state: $(this).val()
                },
                success: function(response) {
                    $('#kredivo_city option:not(:first)').remove();
                    let select = $('#kredivo_city');
                    select.val('').change();
                    select.parent().removeClass('pmd-textfield-floating-label-completed');
                    $.each(response, function(key, value) {
                        let city_name = value.city_name{{ app()->getLocale() == 'en' ? '_en' : '' }}
                        select.append('<option value=' + value.id_city + '>' + city_name + '</option>');
                    });
                 }
            });
        });
        $(document).on('change', '#choose-payment input[type=radio]', function() {
            const kredivo_form = $('#kredivo_form')
            if ($(this).attr('id') == 'kredivo') {
                kredivo_form.show();
            } else {
                kredivo_form.hide();
            }
        });
    </script>
@stop
