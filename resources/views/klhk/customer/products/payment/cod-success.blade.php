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
                <li>
                    <a href="{{route('product.detail',['slug'=>$order->order_detail->product->unique_code])}}">{{$order->order_detail->product->product_name}}</a>
                </li>
                <li><a>{{trans('payment.success_cod.payment_on_site')}}</a></li>
            </ul>
        </div>
        <div id="payment-success" class="container pb-5">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="bg-white p-3">
                        <img src="{{asset('img/cod.png')}}" alt="">
                        <p class="mt-5 title-success">{{trans('payment.success_cod.title')}}</p>
                        <p>{{trans('payment.success_cod.description',['company'=>$company->company_name])}}</p>
                        <p>{{trans('payment.success_cod.sub_description')}}</p>
                        <div class="row">
                            <div class="col-md-8 offset-md-2 mb-3">
                                <div class="row bg-light-blue">
                                    <div class="col-lg-6 p-3 border-right">

                                        <img style="vertical-align: sub"
                                             src="{{asset('img/static/payment/whatsapp.png')}}" alt=""> {{trans('payment.success_cod.whatsapp')}}

                                    </div>
                                    <div class="col-lg-6 p-3">
                                        <img style="vertical-align: sub"
                                             src="{{asset('img/static/payment/envelope-alt.png')}}" alt=""> {{trans('payment.success_cod.email',['email'=>$company->email_company?$company->email_company:'info@gomodo.tech'])}}

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-primary embed-remove">
                                <a href="{{url('/')}}">
                                    <img src="{{asset('img/static/payment/arrow-left.png')}}" alt="">
                                    {{trans('payment.success_cod.back')}}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row embed-remove" id="list-product">
                @if($relateds->count()>0)
                    <div class="col-12 mt-5 mb-5">
                        <h2 class="bold">You Might Like These From {{$company->company_name}}</h2>
                    </div>

                    @foreach($relateds as $product)
                        <div class="col-lg-4 col-md-6 col-product">
                            <a href="{{route('product.detail',['id_product'=>$product->unique_code])}}">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                         alt="Card image cap">
                                    @if($product->discount_amount>0)
                                        <div class="special-price"><h2>{!! trans('product.special_price') !!}</h2></div>
                                            <div class="bedge-discount"><h2 class="percent">
                                                @if($product->discount_amount_type =='1')
                                                    {!! trans('product.discount') !!}
                                                    {{$product->discount_amount.'%'}}
                                                    {!! trans('product.off') !!}
                                                @else
                                                    {!! trans('product.save') !!}
                                                    {{$product->currency}} {{format_priceID($product->discount_amount,'')}}
                                                @endif
                                                <span class="off">/
                                                    {{ optional($product->price->unit)->name }}
                                                </span>
                                            </h2>
                                        </div>
                                    @endif
                                    <div class="card-body">
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
                                        <h3>
                                            {{$product->product_name}}
                                        </h3>
                                        <p class="card-text">{!!  $product->brief_description !!}</p>

                                        <table class="table-product">
                                            <tr>
                                                <td>
                                                    <img src="{{asset('img/pin.png')}}" alt="">
                                                </td>
                                                <td colspan="3">
                                                    {{ $product->city?$product->city->city_name:'-' }}
                                                </td>

                                            </tr>
                                            <tr>
                                                <td>
                                                    <img src="{{asset('img/wall-clock.png')}}" alt="">
                                                </td>
                                                <td colspan="3">
                                                    @if($product->duration)
                                                        {{ $product->duration }} 
                                                        @if($product->duration_type_text == 'Hours')
                                                            {!! trans('product.hours') !!}
                                                        @else
                                                            {!! trans('product.days') !!}
                                                        @endif
                                                    @else
                                                        {!! trans('product.info_later') !!}  
                                                    @endif
                                                </td>

                                            </tr>
                                            @if($product->availability =='0')
                                                <tr>
                                                    <td>
                                                        <img src="{{asset('img/calendar.png')}}" alt="">
                                                    </td>
                                                    <td colspan="3">
                                                        {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        <img src="{{asset('img/calendar.png')}}" alt="">
                                                    </td>
                                                    <td>
                                                        {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning bg-light-blue color-primary-blue">{!! trans('customer.home.until') !!}</span>
                                                    </td>
                                                    <td>
                                                        {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                                    </td>
                                                </tr>
                                            @endif

                                        </table>

                                    </div>
                                    <div class="bg-light-blue text-center card-footer">
                                        <p>{!! trans('customer.home.start_from') !!}</p>
                                        <p class="bold fs-22">
                                            {{ $product->currency }} {{ number_format($product->pricing()->orderBy('price','asc')->first()->price,0) }}
                                            /
                                            {{ optional($product->price->unit)->name }}
                                        </p>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@stop

