@extends('customer.master.index')

@section('additionalStyle')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css"/>
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('dest-customer/lib/css/touchspin/jquery.bootstrap-touchspin.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <style>
        .bootstrap-datetimepicker-widget .datepicker-days table td.day:not(.disabled) {
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <!-- Breadcrumb -->
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
            <li><a>{{$product->product_name}}</a></li>
        </ul>
    </div>
    <!-- Content -->
    <div id="product-detail" class="container pb-5">
        <div class="row">
            <div class="col-md-8 left-side-detail">
                <div class="card">
                    <div class="card-header">
                        <div class="owl-carousel">
                            @forelse($product->image as $image)
                                <a data-fancybox="gallery" href="{{asset($image->image_src)}}" data-height="1000"><img class="card-img-top myImg" id="myImg" src="{{asset($image->image_src)}}"
                                     alt="Card image cap"></a>
                            @empty
                                <img class="card-img-top"
                                     src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                     alt="Card image cap">
                            @endforelse
                        </div>
                    </div>
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
                                @forelse($product->tagValue as $tag)
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
                        <table class="table-product">
                            <tr>
                                <td>
                                    <img src="{{asset('img/pin.png')}}" alt="">
                                </td>
                                <td colspan="3">
                                    @if(app()->getLocale() == 'id')
                                        {{ $product->city?$product->city->city_name:'-' }}
                                    @else
                                        {{ $product->city?$product->city->city_name_en:'-' }}
                                    @endif


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
                                        <span class="badge badge-warning bg-light-blue color-primary-blue">{{trans('product.until')}}</span>
                                    </td>
                                    <td>
                                        {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                    </td>
                                </tr>
                            @endif
                            @if($product->languages->count()>0)
                            <tr>
                                <td>
                                    <img src="{{asset('img/160-chat.png')}}" alt="">
                                </td>
                                <td colspan="3">
                                    {!! trans('customer.detail.guided_in') !!} {{implode(", ",$product->languages->pluck('language_name')->toArray())}}
                                </td>
                            </tr>
                            @endif
                        </table>
                        <hr>
                        <h3>{!! trans('customer.detail.about') !!} "{{$product->product_name}}"</h3>
                        <p class="card-text">{!! $product->long_description !!}</p>
                        <hr>
                        <h3>{!! trans('customer.detail.important_note') !!}</h3>
                        <p class="card-text">{!! $product->important_notes !!}</p>
                        <hr>
                        <div class="product-map embed-responsive embed-responsive-21by9">
                            <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDR7DFQIBGumoziD6B6a0n2EZgrKhQOWS4&q={{$product->latitude}},{{$product->longitude}}"
                                    width="600" height="450" frameborder="0" style="border:0"
                                    class="embed-responsive-item"
                                    allowfullscreen></iframe>
                        </div>
                        @if($product->itineraryCollection->count() >0)
                            @if($product->product_type && $product->product_type->product_type_name!='Other Activities')
                                <div class="block__item pt-3">
                                    <div class="block__inner">
                                        <h3>{!! trans('customer.detail.itinerary') !!}</h3>
                                        <div class="list">
                                            @forelse($product->itineraryCollection as $itinerary)
                                                <div class="list__item">
                                                    <div class="list__time">{{trans('customer.detail.day_itinerary').($loop->index+1)}}</div>
                                                    <div class="list__border"></div>
                                                    <div class="list__desc">
                                                        {!! $itinerary->itinerary !!}
                                                        <div class="border"></div>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                {!! Form::open(['url'=>route('product.book', array_filter(['slug'=>$product->unique_code, 'widget' => request()->has('widget')]))]) !!}
                <div class="card">
                    <script>
                        var disabled = [];
                    </script>
                    @if($product->status =='1' && (($product->availability =='0' && \Carbon\Carbon::parse($product->schedule[0]->start_date)->toDateTimeString()>=\Carbon\Carbon::now()->toDateTimeString()) || ($product->availability =='1' && \Carbon\Carbon::parse($product->schedule[0]->end_date)->toDateTimeString() >= \Carbon\Carbon::now()->toDateTimeString())))
                        {{-- Embed Detail Start --}}
                        <div class="row mt-3 embed-remove-d-none d-none">
                            <div class="col-embed-4 mb-3">
                                <div class="owl-carousel mt-1">
                                    @forelse($product->image as $image)
                                        <a data-fancybox="gallery-widget" href="{{asset($image->image_src)}}" data-height="1000"><img class="card-img-top myImg" id="myImg" src="{{asset($image->image_src)}}"
                                                alt="Card image cap"></a>
                                    @empty
                                        <img class="card-img-top"
                                                src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                                alt="Card image cap">
                                    @endforelse
                                </div>
                            </div>
                            <div class="col pr-0 pl-0">
                                <h3>
                                    {{$product->product_name}}
                                </h3>
                                <table class="table-product">
                                    <tr>
                                        <td>
                                            <img src="{{asset('img/pin.png')}}" alt="">
                                        </td>
                                        <td colspan="3">
                                            @if(app()->getLocale() == 'id')
                                                {{ $product->city?$product->city->city_name:'-' }}
                                            @else
                                                {{ $product->city?$product->city->city_name_en:'-' }}
                                            @endif


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
                                                <span class="badge badge-warning bg-light-blue color-primary-blue">{{trans('product.until')}}</span>
                                            </td>
                                            <td>
                                                {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                            </td>
                                        </tr>
                                    @endif
                                    {{-- @if($product->languages->count()>0)
                                    <tr>
                                        <td>
                                            <img src="{{asset('img/160-chat.png')}}" alt="">
                                        </td>
                                        <td colspan="3">
                                            {!! trans('customer.detail.guided_in') !!} {{implode(", ",$product->languages->pluck('language_name')->toArray())}}
                                        </td>
                                    </tr>
                                    @endif --}}
                                </table>
                            </div>
                        </div>
                        {{-- Embed Detail End --}}
                        <div class="card-body hide-notice-err embed-row">
                            <div class="embed-col-6">
                                @if($product->availability=='0')
                                    <div class="form-group">
                                        <label for="">{!! trans('customer.detail.departure_date') !!}</label>

                                        <input type="text" readonly class="disabled form-control"
                                            value="{{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('m/d/Y')}}"
                                            name="schedule_date">
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label for="">{!! trans('customer.detail.select_date') !!}</label>
                                        @php
                                            $disabled = [];
                                            if ($product->schedule[0]->sun =='0'){
                                                $disabled[] = 0;
                                            }
                                            if ($product->schedule[0]->mon =='0'){
                                                    $disabled[] = 1;
                                                }
                                            if ($product->schedule[0]->tue =='0'){
                                                    $disabled[] = 2;
                                                }
                                            if ($product->schedule[0]->wed =='0'){
                                                    $disabled[] = 3;
                                                }
                                            if ($product->schedule[0]->thu =='0'){
                                                    $disabled[] = 4;
                                                }
                                            if ($product->schedule[0]->fri =='0'){
                                                    $disabled[] = 5;
                                                }
                                            if ($product->schedule[0]->sat =='0'){
                                                    $disabled[] = 6;
                                                }
                                        @endphp
                                        <script>

                                            @foreach($disabled as $item)
                                            disabled.push({{$item}})
                                            // if (disabled.length === 7) {
                                            //     disabled = [];
                                            // }

                                            @endforeach
                                        </script>
                                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                name="schedule_date"
                                                {{--value="{{\Carbon\Carbon::now()->format('m/d/Y')}}"--}}
                                                data-target="#datetimepicker1" data-toggle="datetimepicker"/>
                                            <div class="input-group-append" data-target="#datetimepicker1"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="" id="togglePriceTier"
                                        class="cursor-p">{!! trans('customer.detail.price_tier') !!} <i
                                                class="fa fa-chevron-down"></i></label>
                                    <div class="box-price-tier" style="display: none">
                                        @foreach($product->pricing()->orderBy('price_from')->get() as $price)
                                            <table class="table table-borderless price-tier {{$loop->index%2==0?'bg-light-gray':'bg-light-blue'}}">
                                                <tr>
                                                    <td>{{$price->price_from}}
                                                        {!! trans('customer.detail.to') !!} {{$price->price_until}}

                                                        {{ optional($price->unit)->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{$price->currency.' '.number_format($price->price)}}
                                                        /
                                                        {{ optional($price->unit)->name }}
                                                    </th>
                                                </tr>
                                            </table>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">
                                        {{ optional($price->unit)->name }}

                                    </label>
                                    <input type="number" name="pax" class="form-control touchspin"
                                        value="{{$product->min_people}}" data-min="{{$product->min_people}}"
                                        data-max="{{ $product->max_order && ($product->max_order < $product->max_people) ? $product->max_order : $product->max_people }}">
                                </div>
                            </div>
                            <div class="embed-col-6">
                                @if($product->addon1 || $product->addon2 || $product->addon5 || $product->addon6)
                                    <div class="bg-light-blue p-3 include">
                                        <h3>{{ trans('product.include') }}</h3>
                                        <ul>
                                            @if($product->addon1)
                                                <li>{{ trans('product.price_home') }}</li>
                                            @endif
                                            @if($product->addon2)
                                                <li>{{ trans('product.price_eat') }}</li>
                                            @endif
                                            @if($product->addon5)
                                                <li>{{ trans('product.prices_include_pick_up') }}</li>
                                            @endif
                                            @if($product->addon6)
                                                <li>{{ trans('product.prices_include_pick_off') }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                                @if ($product->show_exclusion && ($product->addon3 ||$product->addon4
                                ||$product->addon7 || $product->addon8
                                ))
                                    <div class="bg-light-blue p-3 exclude">
                                        <h3>{{ trans('product.exclude') }}</h3>
                                        <ul>
                                            @if($product->addon3)
                                                <li>{{ trans('product.not_price') }}</li>
                                            @endif
                                            @if($product->addon4)
                                                <li>{{ trans('product.not_price_eat') }}</li>
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
                                    <div class="include text-center bg-light-blue mt-4 pb-3" id="ket" style="padding-top:1rem;display:none;">

                                    </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light-blue hide-notice-err">
                            <div class="text-center">
                                <h5>{!! trans('customer.detail.total_price') !!}</h5>
                                <h3 class="bold" id="total_price">IDR 0</h3>
                                <button class="btn btn-primary btn-block"
                                        id="btn-book">{!! trans('customer.detail.book') !!}</button>
                            </div>
                        </div>
                    @else
                        <div class="card-body bg-light-blue text-center hide-notice-err">
                            <span>{!! trans('customer.detail.not_active') !!}</span>
                        </div>
                    @endif
                    <div id="show-notice-err" class="card-body bg-light-blue text-center d-none">
                        <span>{!! trans('customer.detail.min_notice_alert') !!}</span>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="{{ asset('dest-customer/lib/js/momentjs-id.js') }}"></script>
    <script src="{{ asset('dest-customer/lib/js/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>
        let minDate;
        @php
            $schedule = $product->schedule->first();
            $now = \Carbon\Carbon::now()->startOfDay()->toDateString();
            if ($schedule->end_date>$now){
                $diff1 = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($schedule->start_date));
                $diff2 = \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($schedule->end_date));
                if ($schedule->start_date<$now){
                    $diff1 = 0-$diff1;
                }
                $array = [
                    'available'=>true,
                    'start_date'=>$schedule->start_date,
                    'end_date'=>$schedule->end_date,
                    'diff_start_date'=>$diff1,
                    'diff_end_date'=>$diff2
                ];
            }
            else {
                $array = [
                    'available'=>false,
                    'start_date'=>$schedule->start_date,
                    'end_date'=>$schedule->end_date,
                    'diff_start_date'=> 0,
                    'diff_end_date'=> 0
                ];
            }
        @endphp

        @if ($product->availability=='1' && $array['diff_start_date'] >= 0)
            @if ($product->minimum_notice <= $array['diff_end_date'] && $product->minimum_notice >= $array['diff_start_date'])
                minDate = moment().add("{{ $product->minimum_notice }}", 'days');
            @elseif($product->minimum_notice > $array['diff_end_date'])
                $('#show-notice-err').removeClass('d-none').addClass('d-block');
                $('.hide-notice-err').hide();
            @else
                minDate = moment("{{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('m/d/Y')}}", 'MM/DD/YYYY');
            @endif
        @elseif ($product->availability=='1' && $array['diff_start_date'] < 0)
            @if ($product->minimum_notice <= $array['diff_end_date'])
                minDate = moment().add("{{ $product->minimum_notice }}", 'days');
            @elseif($product->minimum_notice > $array['diff_end_date'])
                $('#show-notice-err').removeClass('d-none').addClass('d-block');
                $('.hide-notice-err').hide();
            @else
                minDate = moment("{{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('m/d/Y')}}", 'MM/DD/YYYY');
            @endif
        @endif

        $(function () {
            @if($product->availability=='1')
            @if(\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('m/d/Y')>=\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('m/d/Y'))

            try {
                $('#datetimepicker1').datetimepicker({
                    format: 'L',
                    date: minDate.toDate(),
                    minDate: minDate.set({hour:0,minute:0,second:0,millisecond:0}).toDate(),
                    maxDate: moment("{{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('m/d/Y')}}", 'MM/DD/YYYY').set({hour:23,minute:59,second:59,millisecond:0}).toDate(),
                    daysOfWeekDisabled: disabled,
                    locale: '{{ app()->getLocale() }}'
                })
            }catch (e) {
                $('#show-notice-err').removeClass('d-none').addClass('d-block');
                $('.hide-notice-err').hide();
            }
            @else
            let maxDate = moment("{{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('m/d/Y')}}", 'MM/DD/YYYY');
            if (minDate > maxDate && maxDate > moment()) {

                $('#show-notice-err').removeClass('d-none').addClass('d-block');
                $('.hide-notice-err').hide();
            } else {
                $('#datetimepicker1').datetimepicker({
                    format: 'L',
                    // date:moment('MM/DD/YYYY'),
                    minDate: minDate.set({hour:0,minute:0,second:0,millisecond:0}).toDate(),
                    maxDate: moment("{{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('m/d/Y')}}", 'MM/DD/YYYY').set({hour:23,minute:59,second:59,millisecond:0}).toDate(),
                    daysOfWeekDisabled: disabled,
                    locale: '{{ app()->getLocale() }}'
                });
            }
            @endif

            @endif
            $(".owl-carousel").owlCarousel({
                items: 1,
                lazyLoad: true,
                autoplay: true,
                autoplayHoverPause: true,
                nav: window.location === window.parent.location
            });
            $(document).on('click', '#togglePriceTier', function (e) {
                let t = $(this);
                if (!$('.box-price-tier').is(':visible')) {
                    t.find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                } else {
                    t.find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
                $('.box-price-tier').stop().slideToggle(700);

            });
            let touch = $("input[name='pax']");
            touch.TouchSpin({
                min: touch.data('min'),
                max: touch.data('max')
            });
            @if($product->status =='1' && (($product->availability =='0' && \Carbon\Carbon::parse($product->schedule[0]->start_date)->toDateTimeString()>=\Carbon\Carbon::now()->toDateTimeString()) || ($product->availability =='1' && \Carbon\Carbon::parse($product->schedule[0]->end_date)->toDateTimeString() >= \Carbon\Carbon::now()->toDateTimeString())))
            calculatePrice();
            @endif
            $(document).on('change', 'input[name=pax]', function () {
                let pax  = parseInt($(this).val());
                if (isNaN(pax)){
                    pax = 0;
                }
                $(this).val(pax)
                calculatePrice();
            });
            $(document).on('change.datetimepicker', '#datetimepicker1', function () {
                calculatePrice();
            });

            function loadingStartButton(ele) {
                ele.prop('disabled', true);
                ele.html('<i class="fa fa-spin fa-refresh"></i> {{ trans("general.loading") }}')
            }

            function loadingFinishButton(ele, text) {
                ele.prop('disabled', false);
                ele.html(text)
            }

            function calculatePrice() {
                toastr.remove()
                let date = moment($('input[name=schedule_date]').val(), 'MM/DD/YYYY').format('YYYY-MM-DD');
                let pax = $('input[name=pax]').val();
                let product = "{{$product->unique_code}}";
                loadingStartButton($('#btn-book'));
                $('#btn-book').attr('disabed', true).prop('disabled', true);
                $.ajax({
                    url: "{{route('schedule')}}",
                    data: {
                        product: product,
                        schedule_date: date,
                        pax: pax
                    },
                    success: function (data) {
                        $('#btn-book').removeAttr('disabed').prop('disabled', false);
                        $('#total_price').html(data.result.grand_total_text);
                        if (data.result.company_discount_price > 0){
                            $('#ket').html(data.result.pax + ' x ' + data.result.priceText+'<br>'+data.result.discount_label+'  :   '+data.result.company_discount_price_text).show()
                        }else{
                            $('#ket').html(data.result.pax + ' x ' + data.result.priceText).show()
                        }


                        loadingFinishButton($('#btn-book'), '{!! trans('customer.detail.book') !!}');
                        if (data.result.can_book == false){
                            zero_val();
                        }

                    },
                    error: function (data) {
                        $('#total_price').html('IDR 0');
                        $('#btn-book').attr('disabed', true).prop('disabled', true);
                        loadingFinishButton($('#btn-book'), '{!! trans('customer.detail.book') !!}');
                        zero_val();
                        if (data.status !== undefined) {
                            switch (data.status) {
                                case 500:
                                    toastr.error('Server is busy', '{{__('general.whoops')}}')
                                    break;
                                default:
                                    toastr.error(data.responseJSON.message, '{{__('general.whoops')}}')
                                    break;
                            }
                        }
                    }
                })
            }
        });

        function zero_val(){
            var zero_text = $('#total_price').text();
            var res = parseInt(zero_text.charAt(4));
            if (res === 0){
                $('#btn-book').prop('disabled', true);
            }
        }
    </script>
@stop
