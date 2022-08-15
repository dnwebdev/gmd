@extends('public.agents.admiria.base_layout')

@section('additionalStyle')
    <link href="{{ asset('dest-customer/css/product.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dest-customer/lib/css/touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css"
          rel="stylesheet" media="screen,projection">
    <link href="{{ asset('themes/admiria/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet"
          type="text/css">

    <style>
        .alert.alert-info ul {
            margin-top: 0;
            margin-block-end: 0;
            padding-inline-start: 15px;
        }

        .input-group > .custom-file, .input-group > .custom-select, .input-group > .form-control {
            border-left: none;
            text-align: center;
        }
    </style>
@endsection

@section('main_content')
    <section id="content">
        <div id="maincontent">
            <div class="product-detail">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-md-7 col-lg-8 order-1 order-md-1" id="main-content">
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">

                                            @forelse($product->image as $row)

                                                <li data-target="#carouselExampleIndicators"
                                                    data-slide-to="{{$loop->index}}"></li>
                                            @empty
                                                <li data-target="#carouselExampleIndicators"
                                                    data-slide-to="0" class="active"></li>
                                            @endforelse
                                        </ol>
                                        <div class="carousel-inner product-image">
                                            @forelse($product->image as $row)
                                                <div class="carousel-item {{$loop->first?'active':''}}">
                                                    @if (File::exists('uploads/products/thumbnail/'.$row->url))
                                                        <img class="d-block w-100"
                                                             src="{{ asset('uploads/products/thumbnail/'.$row->url) }}"
                                                             actual-image="{{asset('uploads/products/'.$row->url) }}"
                                                             alt="Slide">
                                                    @else
                                                        <img class="d-block w-100"
                                                             src="{{ asset('img/no-product-image.png') }}"
                                                             actual-image="{{ asset('img/no-product-image.png') }}"
                                                             alt="Slide">
                                                    @endif

                                                </div>
                                            @empty
                                                <div class="carousel-item active">
                                                    <img class="d-block w-100"
                                                         src="{{ asset('img/no-product-image.png') }}"
                                                         actual-image="{{ asset('img/no-product-image.png') }}"
                                                         alt="Slide">
                                                </div>
                                            @endforelse
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleIndicators"
                                           role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon"
                                                              aria-hidden="true">
                                                        </span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleIndicators"
                                           role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon"
                                                              aria-hidden="true">
                                                        </span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6">

                                    <h1>{{ $product->product_name }}</h1>
                                    <div class="product-meta">
                                        <h4>{{__('product.guided_by')}} {{$company->company_name}}</h4>
                                    </div>
                                    <hr/>
                                    <div class="product-demography">
                                        <div class="cat-tag">
                                            @if(count($product->tagValue))
                                                @foreach($product->tagValue as $row)
                                                    <span style="margin-right: 5px;margin-top: 5px; display: inline-block">{{ $row->tag->name ? $row->tag->name : "" }}</span>
                                                @endforeach
                                            @else
                                                <span>Uncategorized</span>
                                            @endif
                                        </div>
                                        @if($product->city)
                                            <h3>
                                                <span class="fa fa-map-marker"></span>{{$product->city->city_name.', '.$product->city->state->state_name}}
                                            </h3>
                                        @else
                                            <h3><span class="fa fa-map-marker"></span>Indonesia</h3>
                                        @endif
                                        @if($product->availability =='0')
                                            <h3><span
                                                        class="fa fa-calendar"></span>
                                                {{ date('d M Y',strtotime($product->schedule[0]->start_date)) }}</h3>
                                        @else
                                            <h3><span
                                                        class="fa fa-calendar"></span>
                                                {{ date('d M Y',strtotime($product->schedule[0]->start_date)) }}
                                                {{ trans('home.to') }} {{ date('d M Y',strtotime($product->schedule[0]->end_date)) }}</h3>
                                        @endif
                                        @if($product->availability =='0')
                                            <h3><span
                                                        class="fa fa-clock-o"></span>
                                                {{ date('H:i',strtotime($product->schedule[0]->start_time)) }}</h3>
                                            @else
                                            <h3><span
                                                        class="fa fa-clock-o"></span>
                                                {{ date('H:i',strtotime($product->schedule[0]->start_time)) }}
                                                {{ trans('home.to') }} {{ date('H:i',strtotime($product->schedule[0]->end_time)) }}</h3>
                                        @endif
                                        @if($product->duration)
                                            <h3>
                                                @if ($product->duration_type_text == 'Hours')
                                                    <span class="fa fa-hourglass"></span>{{$product->duration}} {{ $product->duration >1?  trans('product_provider.hours'): trans('product_provider.hour')}}
                                                @else
                                                    <span class="fa fa-hourglass"></span>{{$product->duration}} {{ $product->duration >1?  trans('product_provider.days'): trans('product_provider.day')}}
                                                @endif
                                            </h3>
                                        @else
                                            <h3>
                                                <span class="fa fa-hourglass"></span>@lang('product.info_later')
                                            </h3>
                                        @endif
                                        <h3><span class="fa fa-comments-o"></span>{{__('product.guided_in')}} @if($product->guide_language) {{$product->guide_language}} @else
                                                Bahasa Indonesia @endif</h3>
                                    </div>

                                        {{--@if($company->domain)--}}
                                            {{--<h3 class="hide-desktop"><span class="fa fa-globe"></span> <a--}}
                                                        {{--href="#">{{$company->domain}}</a></h3>--}}
                                        {{--@endif--}}
                                </div>

                                {{--</div>--}}
                            </div>
                            {{--<hr/>--}}
                            {{--<div class="product-short-description">--}}
                            {{--<p class="wrap-nospace">{{$product->brief_description}}</p>--}}
                            {{--</div>--}}
                            <hr/>
                            <div class="product-description">
                                <h2>{{__('product.about')}} {{ $product->product_name }}</h2>
                                <p class="wrap-nospace">{!!html_entity_decode($product->long_description)!!}</p>
                            </div>
                            @if($product->important_notes)
                                <hr/>
                                <div class="product-description">
                                    <h2>{{ trans('product.important_note') }}</h2>
                                    <p class="wrap-nospace">{!!$product->important_notes!!}</p>
                                </div>
                            @endif
                            @if($product->latitude != '0' && $product->longitude !=0)
                                <hr/>
                                <div class="product-meeting-point">
                                    <h2>{{__('product.meeting')}}</h2>
                                    <div class="product-map embed-responsive embed-responsive-21by9">
                                        <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyD3rfV14WCZO6iNH5iX37OltWufEx7AK4k&q={{$product->latitude}},{{$product->longitude}}"
                                                width="600" height="450" frameborder="0" style="border:0"
                                                class="embed-responsive-item"
                                                allowfullscreen></iframe>
                                    </div>
                                </div>
                            @endif
                            @if(count($product->itineraryCollection) > 0)
                                <hr/>
                                <div class="product-itineraries">
                                    <h2>{{ trans('product.itinerary') }}</h2>
                                    <div class="product-tabs">
                                        <ul class="nav nav-tabs" id="itineraryTab" role="tablist">
                                            @php $i = 1 @endphp
                                            @foreach($product->itineraryCollection as $k => $v)
                                                <li class="nav-item">
                                                    @if($i==1)
                                                        <a class="nav-link active"
                                                           id="{{ trans('product_provider.day') }}-{{ $i }}-tab"
                                                           data-toggle="tab" href="#itinerary-day-{{ $i }}"
                                                           role="tab"
                                                           aria-controls="{{ trans('product_provider.day') }}-{{ $i }}"
                                                           aria-selected="true">{{ trans('product_provider.day') }} {{ $i }}</a>
                                                    @else
                                                        <a class="nav-link"
                                                           id="{{ trans('product_provider.day') }}-{{ $i }}-tab"
                                                           data-toggle="tab"
                                                           href="#itinerary-day-{{ $i }}" role="tab"
                                                           aria-controls="{{ trans('product_provider.day') }}-{{ $i }}"
                                                           aria-selected="true">{{ trans('product_provider.day') }} {{ $i }}</a>
                                                    @endif
                                                </li>
                                                @php $i++ @endphp
                                            @endforeach
                                        </ul>
                                        <div class="tab-content" id="itineraryTabContent"
                                             style="word-break: break-all">
                                            @php $i = 1 @endphp
                                            @foreach($product->itineraryCollection as $k => $v)
                                                @if($i==1)
                                                    <div class="tab-pane fade show active" id="itinerary-day-{{$i}}"
                                                         role="tabpanel" aria-labelledby="day-{{$i}}-tab">
                                                        {!!$v->itinerary!!}
                                                    </div>
                                                @else
                                                    <div class="tab-pane fade" id="itinerary-day-{{$i}}"
                                                         role="tabpanel"
                                                         aria-labelledby="day-{{$i}}-tab">
                                                        {!!$v->itinerary!!}
                                                    </div>
                                                @endif
                                                @php $i++ @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <hr/>
                            <form action="{{ Route('memoria.inquiry') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" id="product" name="product" value="{{ $product->unique_code }}"
                                       required/>
                                <input type="hidden" id="url" value="{{  Route('memoria.validate_schedule') }}"
                                />
                                <div class="product-book">
                                    <button class="btn btn-primary">{{__('booking.special_request')}}</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-12 col-md-5 col-lg-4 order-2 order-md-2 right-section">
                            <div class="product-preview">
                                <form action="{{ Route('memoria.book') }}" method="POST">
                                    {{ csrf_field() }}

                                    <input type="hidden" id="product" name="product"
                                           value="{{ $product->unique_code }}"
                                           required/>

                                    @if ($product->availability == '0')
                                        <div class="form-group fixeddatae" style="display: none;">
                                            <label class="label-product">{{__('product.select_booking_period')}}</label>
                                            <select class="range_schedule_select form-control">
                                                @foreach($product->schedule as $k=>$sch)
                                                    <?php
                                                    $disable_day = '';
                                                    if (!$sch->sun) {
                                                        $disable_day .= '0';
                                                    }

                                                    if (!$sch->mon) {
                                                        $disable_day .= '1';
                                                    }

                                                    if (!$sch->tue) {
                                                        $disable_day .= '2';
                                                    }

                                                    if (!$sch->wed) {
                                                        $disable_day .= '3';
                                                    }

                                                    if (!$sch->thu) {
                                                        $disable_day .= '4';
                                                    }

                                                    if (!$sch->fri) {
                                                        $disable_day .= '5';
                                                    }

                                                    if (!$sch->sat) {
                                                        $disable_day .= '6';
                                                    }
                                                    ?>
                                                    <option value="$sch->id_schedule"
                                                            start_date="{{ date('m/d/Y',strtotime($sch->start_date)) }}"
                                                            end_date="{{ date('m/d/Y',strtotime($sch->end_date)) }}"
                                                            disable_day="{{ $disable_day }}">{{ date('M d, Y',strtotime($sch->start_date)).' - '.date('M d, Y',strtotime($sch->end_date)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else

                                    @endif
                                    <div id="info" class="alert alert-info" role="alert" style="display: none"
                                         data-info="{{ trans('product.booking_now') }}"
                                         data-slot="{{ trans('product.slot_again') }}"></div>

                                    <div id="alert" class="alert alert-danger"
                                         data-sorry="{{ trans('product.sorry') }}" role="alert"
                                         style="display: none"></div>

                                    <div class="product-preview"
                                         style="position: relative!important; top : 0!important;">
                                        <div class="form-group field">
                                            <label class="label-product"
                                                   for="datepicker">{{__('product.select_date')}}</label>
                                            @if($product->availability =='1')
                                                <input type="text" class="datepicker form-control" name="schedule"
                                                       autocomplete="off"
                                                       data-date="" id="datepicker" style="padding-left: 15px"
                                                       url="{{ Route('memoria.validate_schedule') }}">
                                            @else
                                                <input type="text" readonly class="form-control" name="schedule"
                                                       value="{{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('m/d/Y')}}"
                                                       data-date="" id="datepicker2"
                                                       url="{{ Route('memoria.validate_schedule') }}">
                                            @endif
                                            <div id="schedule_message"></div>
                                        </div>
                                    </div>
                                    {{--@if(sizeof($product_pricing) > 1)--}}
                                    <div class="product-preview"
                                         style="position: relative!important; top : 0!important;">
                                    <!-- <table class="col-12">
                                                    <tbody>
                                                    <tr>
                                                        @php($i = 1)
                                    @foreach($product_pricing as $price)
                                        @if($price->price_from || $price->price_until)
                                            <td style="padding: 10px; background-color: @if($i % 2 == 0) #fcfcfc @else #efefef @endif;">
                                                                    <span style="color: #{{$company->color_company}}; font-size: 14px;">{{$price->currency.' '.number_format($price->price)}}
                                                    /Pax</span></td>
@endif
                                        @php($i += 1)
                                    @endforeach
                                            </tr>
                                            <tr>
@php($i = 1)
                                    @foreach($product_pricing as $price)
                                        @if($price->price_from || $price->price_until)
                                            <td style="padding: 10px; background-color: @if($i % 2 == 0) #fcfcfc @else #efefef @endif; font-size: 12px;">
                                                                    @if($price->price_from && $price->price_until)
                                                {{$price->price_from.' to '.$price->price_until}}
                                                        Pax
@if($price->price_type == 1) {{optional($price->unit)->name}} @endif
                                                @elseif($price->price_from)
                                                        &ge; {{$price->price_from}} Pax
                                                                            @if($price->price_type == 1)
                                                {{optional($price->unit)->name}} @endif
                                                @elseif($price->price_until)
                                                        &le; {{$price->price_until}} Pax
                                                                                @if($price->price_type == 1)
                                                    {{optional($price->unit)->name}} @endif
                                            @else
                                                Lainnya
@endif
                                                    </td>
@endif
                                        @php($i += 1)
                                    @endforeach
                                            </tr>
                                            </tbody>
                                        </table> -->
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                            <tr style="background-color: #fff">
                                                <th scope="col">{{ trans('product.pax') }}</th>
                                                <th scope="col">{{ trans('product.price') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($product_pricing as $price)
                                                <tr>

                                                    <th style="padding: 10px; background-color: @if($i % 2 == 0) #ffffff @else #efefef @endif; font-size: 14px;">
                                                        @if($price->price_from && $price->price_until)
                                                            {{$price->price_from.' to '.$price->price_until}}
                                                            Pax
                                                            @if($price->price_type == 1) {{optional($price->unit)->name}} @endif
                                                            @elseif($price->price_from)
                                                            &ge; {{$price->price_from}} Pax
                                                            @if($price->price_type == 1)
                                                            {{optional($price->unit)->name}} @endif
                                                            @elseif($price->price_until)
                                                            &le; {{$price->price_until}} Pax
                                                            @if($price->price_type == 1)
                                                                {{optional($price->unit)->name}} @endif
                                                        @else
                                                            Lainnya
                                                        @endif
                                                    </th>

                                                    <th style="padding: 10px; background-color: @if($i % 2 == 0) #ffffff @else #efefef @endif;">
                                                                    <span style="color: #{{$company->color_company}}; font-size: 14px;">{{$price->currency.' '.number_format($price->price)}}
                                                                        /Pax</span></th>
                                                </tr>
                                            @endforeach
                                            <!-- <tr>
                                                        
                                                        @php($i = 1)
                                            @foreach($product_pricing as $price)
                                                @if($price->price_from || $price->price_until)
                                                    <th style="padding: 10px; background-color: @if($i % 2 == 0) #fcfcfc @else #efefef @endif;">
                                                                    <span style="color: #{{$company->color_company}}; font-size: 14px;">{{$price->currency.' '.number_format($price->price)}}
                                                            /Pax</span></th>
@endif
                                                @php($i += 1)
                                            @endforeach
                                                    </tr>
                                                    <tr>
@php($i = 1)
                                            @foreach($product_pricing as $price)
                                                @if($price->price_from || $price->price_until)
                                                    <th style="padding: 10px; background-color: @if($i % 2 == 0) #fcfcfc @else #efefef @endif; font-size: 12px;">
                                                                    @if($price->price_from && $price->price_until)
                                                        {{$price->price_from.' to '.$price->price_until}}
                                                                Pax
@if($price->price_type == 1) {{optional($price->unit)->name}} @endif
                                                        @elseif($price->price_from)
                                                                &ge; {{$price->price_from}} Pax
                                                                            @if($price->price_type == 1)
                                                        {{optional($price->unit)->name}} @endif
                                                        @elseif($price->price_until)
                                                                &le; {{$price->price_until}} Pax
                                                                                @if($price->price_type == 1)
                                                            {{optional($price->unit)->name}} @endif
                                                    @else
                                                        Lainnya
@endif
                                                            </th>
@endif
                                                @php($i += 1)
                                            @endforeach

                                                    </tr> -->
                                            </tbody>
                                        </table>
                                        {{--</div>--}}
                                        {{--@endif--}}
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12 no-padding2">
                                                <!-- Adult -->
                                                <div class="form-group field">
                                                    <label class="label-product" for="adult">
                                                        {{-- <span class="fa fa-male"></span> --}}
                                                        {{ optional($product_pricing->unit)->name }}
                                                        {{-- {{__('product.adult')}} --}}
                                                    </label>
                                                    <input name="adult" id="adult" value="1" min="0" type="text" class="adt form-control" required>
                                                </div>
                                            </div>
                                            <!-- Child -->
                                            {{-- <div class="col-sm-12 col-md-12 col-lg-6 no-padding2">
                                                <div class="form-group field">
                                                    <label class="label-product" for="children">
                                                        <span class="fa fa-child"></span>
                                                        {{__('product.children')}}
                                                    </label>
                                                    <input name="children" id="children"
                                                           value="0"
                                                           type="text" class="chd form-control" required readonly>
                                                </div>
                                            </div> --}}
                                        </div>
                                        @if ($product->addon1 || $product->addon2 || $product->addon3 || $product->addon4 || $product->addon5 || $product->addon6 || $product->addon7 || $product->addon8)
                                            <div class="alert alert-info" role="alert">
                                                <ul>
                                                    @if($product->addon1)
                                                        <li>{{ trans('product.price_home') }}</li>
                                                    @endif
                                                    @if($product->addon2)
                                                        <li>{{ trans('product.price_eat') }}</li>
                                                    @endif
                                                    @if($product->addon3)
                                                        <li>{{ trans('product.not_price') }}</li>
                                                    @endif
                                                    @if($product->addon4)
                                                        <li>{{ trans('product.not_price_eat') }}</li>
                                                    @endif
                                                    @if($product->addon5)
                                                        <li>{{ trans('product.prices_include_pick_up') }}</li>
                                                    @endif
                                                    @if($product->addon6)
                                                        <li>{{ trans('product.prices_include_pick_off') }}</li>
                                                    @endif
                                                    @if($product->addon7)
                                                        <li>{{ trans('product.prices_exclude_pick_up') }}</li>
                                                    @endif
                                                    @if($product->addon8)
                                                        <li>{{ trans('product.prices_exclude_pick_off') }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="product-tools d-flex align-items-center justify-content-end">
                                            @if ($product->status == 1)
                                                <div class="product-book mr-2 text-right">
                                                    <div>{{ trans('product.total_price') }}</div>
                                                    <strong>{{ $product->currency }} <span id="total_price"
                                                                                           class="schedule_amount">{{ number_format(0,0) }}</span></strong>
                                                </div>
                                                <div class="product-book">

                                                    <button class="btn select_product btn-primary btn-book form-control"
                                                            id="book-now"
                                                            style="color: #ffffff;">{{__('product.book')}}</button>

                                                </div>
                                            @else
                                                <div class="alert alert-warning text-center text-md-left"
                                                     style="width: 100%">
                                                    {{trans('notification.product.not_active')}}
                                                </div>
                                            @endif
                                        </div>
                                        @foreach($product->pricing as $price)
                                            @if($price->price_type==0)
                                                <div id="general_price"
                                                     style="display: none">{{$price->price}}</div>
                                            @elseif($price->price_type==1)
                                                <div id="adult_price"
                                                     style="display: none">{{$price->price}}</div>
                                            @elseif($price->price_type==2)
                                                <div id="children_price"
                                                     style="display: none">{{$price->price}}</div>
                                            @endif
                                        @endforeach
                                    </div>
                                </form>
                                <hr/>
                                <div class="product-sharing d-flex justify-content-between">
                                    <div class="share-social">
                                        {{-- <ul>
                                            <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                                            <li><a href="#"><span class="fa fa-instagram"></span></a></li>
                                            <!-- <li><a href="#"><span class="fa fa-ellipsis-h"></span></a></li> -->
                                        </ul> --}}
                                    </div>
                                    <!-- <div class="add-wishlist">
                                    <a href="#" class="btn-wishlist">Add to Wishlist <span class="fa fa-heart-o"></span></a>
                                    </div> -->
                                </div>
                                <!-- <div class="product-tools d-flex align-items-center justify-content-end">
                                Share this activity!
                                <div class="addthis_inline_share_toolbox ml-2 text-right"></div>
                                </div> -->
                            <!-- @if($company->facebook_company || $company->twitter_company)
                                <hr />
                                <div class="product-sharing d-flex justify-content-between">
                                    <div class="share-social">
                                        <ul>
@if($company->facebook_company)
                                    <li><a href="http://www.facebook.com/{{$company->facebook_company}}"><span class="fa fa-facebook"></span></a></li>
                                @endif
                                @if($company->twitter_company)
                                    <li><a href="http://instagram.com/{{$company->twitter_company}}"><span class="fa fa-instagram"></span></a></li>
                                @endif
                                        </ul>
                                    </div>
                                </div>
@endif -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Check if children and infant pricing available -->
    <?php
    $child = false;
    $infant = false;
    foreach ($product->pricing as $price) {
        if ($price->price_type == 2) {
            $child = true;
        } else if ($price->price_type == 3) {
            $infant = true;
        }
    }
    ?>
    @if($child)
        <input type="hidden" id="child_available" value=1/>
    @else
        <input type="hidden" id="child_available" value=0/>
    @endif
    <input type="hidden" id="min_notice" value="{{ $product->minimum_notice }}"/>
    {{-- <input type="hidden" id="min_people" value="{{ $product->min_people ? $product->min_people : 1 }}" /> --}}
    <input type="hidden" id="max_people" value="{{ $product->max_people ? $product->max_people : 999 }}"/>

@endsection

@section('additionalScript')
    <!-- Plugin -->
    <script type="text/javascript"
            src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5c153e743c387751"></script>
    <script type="text/javascript"
            src="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('dest-customer/lib/js/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script>
        jQuery('#adult').keypress(function (event) {
            if (event.keyCode === 10 || event.keyCode === 13) {
                event.preventDefault();
            }
        });
    </script>
    <script src="{{ asset('themes/admiria/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>

    <!-- Custom -->
    <script src="{{ asset('dest-customer/js/validate.js') }}"></script>
@endsection
