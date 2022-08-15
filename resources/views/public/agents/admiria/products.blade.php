@extends('public.agents.admiria.base_layout')

@section('main_content')

    <section id="content">
        <div id="maincontent">
            <div class="container">
                <form method="GET" action="{{ Route('memoria.more') }}">
                    <div id="desktop-view" class="row">
                        <div class="col-12 col-lg-3">
                            <input type="text" class="form-control round third-color mb-3"
                                   placeholder="{{__('more.keyword')}}" name="keyword"
                                   value="{{ app('request')->get('keyword') }}"/>
                        </div>
                        <div class="col-12 col-lg-3">
                            <select class="form-control round third-color custom-caret" name="city">
                                <option value="">{{__('more.place')}}</option>
                                @foreach($destination as $row)
                                    @if($row->id_city)
                                        <option {{ $row->id_city == $city ? 'selected' : '' }} value="{{ $row->id_city }}">{{ $row->city->city_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-lg-3">
                            <button class="btn btn-block outline round third-color text-white" id="btn_order_price"><i
                                        class="icon-arrowlong-down"></i> {{__('more.from_lowest')}}</button>
                        </div>

                        <div class="col-12 col-lg-2">
                            <button class="btn btn-block outline round third-color text-white" id="btn_order_name"><i
                                        class="icon-sort"></i> {{__('booking.name')}} A-Z
                            </button>
                        </div>

                        <div class="col-12 col-sm-1">
                            <button class="btn btn-block outline round third-color text-white" id="btn_order_name"
                                    type="submit"><i class="fa fa-search"></i></button>
                        </div>

                        <input type="hidden" name="product_type" value="{{ app('request')->get('product_type') }}">
                        <input type="hidden" name="month" value="{{ app('request')->get('month') }}">
                        <input type="hidden" name="find" value="{{ app('request')->get('find') }}">

                        <input type="hidden" name="order_by" value="" id="order_by">
                        <input type="hidden" name="order" value="" id="order">
                    </div>
                </form>

                <form method="GET" action="{{ Route('memoria.more') }}">
                    <div id="mobile-view" class="row">
                        <div class="col-6">
                            <input type="text" class="form-control round third-color mb-3"
                                   placeholder="{{__('more.keyword')}}" name="keyword"
                                   value="{{ app('request')->get('keyword') }}"/>
                        </div>
                        <div class="col-6">
                            <select class="form-control round third-color custom-caret" name="city">
                                <option value="">{{__('more.place')}}</option>
                                @foreach($destination as $row)
                                    @if($row->id_city)
                                        <option {{ $row->id_city == $city ? 'selected' : '' }} value="{{ $row->id_city }}">{{ $row->city->city_name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-6">
                            <button class="btn btn-block outline round third-color text-white" id="btn_order_price"><i
                                        class="icon-arrowlong-down"></i> {{__('more.from_lowest')}}</button>
                        </div>

                        <div class="col-6">
                            <button class="btn btn-block outline round third-color text-white" id="btn_order_name"><i
                                        class="icon-sort"></i> {{__('booking.name')}} A-Z
                            </button>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-block outline round third-color text-white" id="btn_order_name"
                                    type="submit">Search</button>
                        </div>

                        <input type="hidden" name="product_type" value="{{ app('request')->get('product_type') }}">
                        <input type="hidden" name="month" value="{{ app('request')->get('month') }}">
                        <input type="hidden" name="find" value="{{ app('request')->get('find') }}">

                        <input type="hidden" name="order_by" value="" id="order_by">
                        <input type="hidden" name="order" value="" id="order">
                    </div>
                </form>

                <div class="row mt-4">
                    @foreach($products as $row)
                        <div class="col-sm-12 col-md-6 col-lg-4 sh">
                            <div class="featured card">
                                <div class="card-img">
                                    @if($row->main_image !='img2.jpg')
                                        <img src="{{ asset('uploads/products/thumbnail/'.$row->main_image) }}"
                                             alt="FEATURED"
                                             style="object-fit: cover; background-size: unset;width: 100%;height: 18rem">
                                    @else
                                        <img src="{{ asset('img/no-product-image.png') }}"
                                             alt="FEATURED"
                                             style="object-fit: cover; background-size: unset;width: 100%;height: 18rem">
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div class="featured-category">
                                        @if(count($row->tagValue))
                                            @php($tagCount=0)
                                            @foreach($row->tagValue as $tag)
                                                @if($tagCount < 2)
                                                    <span class="cat-tag"
                                                          style="margin-right: 5px; margin-top: 10px; display: inline-block;">{{ $tag->tag->name ? $tag->tag->name : "" }}</span>
                                                @endif
                                                @php($tagCount+=1)
                                            @endforeach
                                        @else
                                            <span class="cat-tag">Uncategorized</span>
                                        @endif
                                    </div>
                                    <h5 class="featured-title">{{ str_limit($row->product_name,30) }}</h5>
                                    <div class="featured-info">
                                        @if($row->city)
                                            <h5 class="featured-location">
                                                <span class="fa fa-map-marker"></span>
                                                {{ $row->city->city_name }}
                                            </h5>
                                        @else
                                            <h5 class="featured-location">
                                                <span class="fa fa-map-marker"></span>
                                                Indonesia
                                            </h5>
                                        @endif
                                        @if($row->duration)
                                            <h5 class="featured-time">
                                                @if ($row->duration_type_text == 0)
                                                <span class="fa fa-clock-o"></span>{{ $row->duration }} {{ trans('product_provider.hours') }}
                                                @else
                                                <span class="fa fa-clock-o"></span>{{ $row->duration }} {{ trans('product_provider.day') }}
                                                @endif
                                            </h5>
                                        @else
                                            <h5 class="featured-time">
                                                <span class="fa fa-clock-o"></span>
                                                {{__('home.tba')}}
                                            </h5>
                                        @endif
                                        <h5 class="featured-time">
                                            <span class="fa fa-calendar"></span>
                                            {{--@if(sizeof($row->schedule) > 1 and $row->availability != '0')--}}
                                                {{--Tersedia {{sizeof($row->schedule)}} Periode Booking--}}
                                            {{--@else--}}
                                                {{--@if($row->schedule[0]->start_date == $row->schedule[0]->end_date)--}}
                                                    {{--{{ date('d M Y',strtotime($row->schedule[0]->start_date)) }}--}}
                                                {{--@else--}}
                                                    {{--{{ date('d M Y',strtotime($row->schedule[0]->start_date)) }}--}}
                                                    {{--to {{ date('d M Y',strtotime($row->schedule[0]->end_date)) }}--}}
                                                {{--@endif--}}
                                            {{--@endif--}}
                                            @if($row->availability != '0')
                                                {{--Tersedia {{sizeof($row->schedule)}} Periode Booking<br>--}}
                                                @foreach($row->schedule as $item)
                                                    {{--<span class="fa"></span> --}}
                                                    {{ date('d M Y',strtotime($row->schedule[0]->start_date)) }}
                                                    {{ trans('home.to') }} {{ date('d M Y',strtotime($row->schedule[0]->end_date)) }}
                                                    <br>
                                                @endforeach
                                            @else
                                                {{--@if($row->schedule[0]->start_date == $row->schedule[0]->end_date)--}}
                                                {{ date('d M Y',strtotime($row->schedule[0]->start_date)) }}
                                                {{--@else--}}
                                                {{--{{ date('d M Y',strtotime($row->schedule[0]->start_date)) }} to {{ date('d M Y',strtotime($row->schedule[0]->end_date)) }}--}}
                                                {{--@endif--}}
                                            @endif
                                        </h5>
                                        
                                        <h6 class="mt-3" style="color: rgba(0,0,0,.54);">{{ trans('home.desc') }}</h6>
                                        <p style="height:80px">
                                            {{$row->brief_description}}
                                        </p>
                                        
                                    </div>
                                </div>
                                <div class="price-label">
                                    <span>{{__('home.start_from')}}: </span>
                                    <h5>{{ $row->currency }} {{ number_format($row->pricing()->orderBy('price','asc')->first()->price,0) }} / Pax</h5>
                                </div>
                                <a href="{{ Route('memoria.detail',[$row->unique_code.'-'.$row->permalink]) }}"
                                   class="featured-link">{{ $row->product_name }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{--@if ($products->hasMorePages())--}}
                    <div class="more-product">
                        {!! $products->links() !!}
                    </div>
                {{--@endif--}}
            </div>
        </div>
    </section>
    <style>
        .more-product .pagination{
            flex-wrap: wrap;
        }
        .more-product .pagination li{
            background-color: #{{$company->color_company??'0893cf'}};
            padding: 8px 16px;
            margin: 3px;
        }
        .more-product .pagination li a{
            color: #{{$company->font_color_company??'fff'}};
        }
        .more-product .pagination li.active, .more-product .pagination li.disabled {
            background-color: #cecece;
            color: grey;
        }
    </style>
    <style>
        /*.card .card-content{*/
            /*background-color:white;*/
        /*}*/
        /*.card-img {*/
            /*height: 18rem;*/
        /*}*/
        .card .card-img img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

    </style>

@endsection

@section('additionalScript')
    <script src="{{asset('js/sh.min.js')}}"></script>
    <script type="text/javascript">
        'use strict';

        var jQ = jQuery.noConflict();
        jQ(document).ready(function ($) {
            $('#btn_order_price').click(function () {
                $('#order_by').val('price');
                $('#order').val('asc');
            });

            $('#btn_order_name').click(function () {
                $('#order_by').val('name');
                $('#order').val('asc');
            });
        });
        jQ(function() {
            jQ('.sh .featured.card .card-content').matchHeight();
        });
    </script>
@endsection