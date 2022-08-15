@extends('public.agents.admiria.base_layout')
@section('additionalStyle')
    <style>
        .featured-products .card {
            height: auto;
        }
    </style>
@endsection
@section('main_content')
    <!--START HOME-->
    <section id="content" class="home">
        @if(isset($company->banner))
            <div class="hero half" id="hero-home"
                 style="background: url('{{ asset(strpos($company->banner, 'dest-operator') !== false ? $company->banner : 'uploads/banners/'.$company->banner ) }}') no-repeat center; margin-top: -80px; background-size: cover; margin-bottom: 3rem; height:425px;">
                @else
                    <div class="hero half" id="hero-home"
                         style="background: url({{ asset('dest-customer/img/home-banner.png') }}) no-repeat center; margin-top: -80px; background-size: cover; margin-bottom: 3rem;">
                    @endif
                    <!-- <div class="container">
                            <div class="row">
                                <div class="col">
                                    {{--@if(isset($company->quote))--}}
                    {{--<h2 class="quote">{{$company->quote}}</h2>--}}
                    {{--@else--}}
                    {{--<h2 class="quote">Be wild, <br/>Be Inspired!</h2>--}}
                    {{--@endif--}}
                            </div>
                        </div>
                    </div> -->
                    </div>

                    <div class="heading" id="welcome">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-sm col-md-8 text-center">
                                    @if(isset($company->email_verified) AND $company->email_verified==1)
                                        <h2 class="verified">{{__('home.welcome')}}
                                            <strong>{{ Session::get('company_name') }}!</strong></h2>
                                    @else
                                        <h2>{{__('home.welcome')}} <strong>{{ Session::get('company_name') }}!</strong>
                                        </h2>
                                    @endif
                                    @if(isset($company->short_description))
                                        <p>{{$company->short_description}}</p>
                                    @else
                                        <p>{{__('home.description')}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(sizeof($newproduct)>0)
                        <div class="featured-products">
                            <div class="container">
                                <div class="row">
                                    @foreach($newproduct as $row)
                                        <div class="col-sm-12 col-md-4 sh">
                                            <div class="featured card">
                                                <div class="card-img" style="height: 18rem;">
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
                                                                          style="margin-right: 5px;">{{ $tag->tag->name ? $tag->tag->name : "" }}</span>
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
                                                                <span class="fa fa-clock-o"></span>
                                                                @if ($row->duration_type_text == 0)
                                                                    {{ $row->duration }} {{ trans('product_provider.hours') }}
                                                                @else
                                                                    {{ $row->duration }} {{ trans('product_provider.day') }}
                                                                @endif
                                                                {{-- {{ $row->duration.' '.$row->duration_type_text }} --}}
                                                            </h5>
                                                        @else
                                                            <h5 class="featured-time">
                                                                <span class="fa fa-clock-o"></span>
                                                                {{__('home.tba')}}
                                                            </h5>
                                                        @endif
                                                        <h5 class="featured-time">
                                                            <span class="fa fa-calendar"></span>
                                                            @if($row->availability != '0')
                                                                {{--Tersedia {{sizeof($row->schedule)}} Periode Booking<br>--}}
                                                                {{--@foreach($row->schedule as $item)--}}
                                                                {{--<span class="fa"></span> --}}
                                                                {{ date('d M Y',strtotime($row->schedule[0]->start_date)) }}
                                                                {{ trans('home.to') }} {{ date('d M Y',strtotime($row->schedule[0]->end_date)) }}
                                                                {{--<br>--}}
                                                                {{--@endforeach--}}
                                                            @else
                                                                {{--@if($row->schedule[0]->start_date == $row->schedule[0]->end_date)--}}
                                                                {{ date('d M Y',strtotime($row->schedule[0]->start_date)) }}
                                                                {{--@else--}}
                                                                {{--{{ date('d M Y',strtotime($row->schedule[0]->start_date)) }} to {{ date('d M Y',strtotime($row->schedule[0]->end_date)) }}--}}
                                                                {{--@endif--}}
                                                            @endif
                                                        </h5>
                                                        <h6 class="mt-3 desc-title">{{ trans('home.desc') }}</h6>
                                                        <p style="
                                                            height: 80px;
                                                            font-size: smaller;
                                                            margin-bottom: 45px;">
                                                            {!! $row->brief_description !!}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="price-label">
                                                    <span>{{__('home.start_from')}}: </span>
                                                    <h5>{{ $row->currency }} {{ number_format($row->pricing()->orderBy('price','asc')->first()->price,0) }}
                                                        / Pax</h5>
                                                </div>
                                                <a href="{{ Route('memoria.detail',[$row->unique_code.'-'.$row->permalink]) }}"
                                                   class="featured-link">{{ $row->product_name }}</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if($hasMore)
                                    <div class="row">
                                        <div class="col">
                                            <div class="featured-more text-right">
                                                <a href="{{ Route('memoria.more') }}?find=new-product">{{__('home.more',['total'=>($totalProduct-3)])}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
        @endif

    </section>
    <!--END HOME-->
@endsection

@section('additionalScript')
    <script src="{{asset('js/sh.min.js')}}"></script>
    <script type="text/javascript">
        'use strict';
        var jQ = jQuery.noConflict();
        jQ(function () {
            jQ('.sh .featured.card .card-content').matchHeight();
        });
    </script>
@endsection
