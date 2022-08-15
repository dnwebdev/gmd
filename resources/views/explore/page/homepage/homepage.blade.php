@extends('explore.layout')

@section('content')
    <section id="content" class="tour">
        <div id="slideshow" class="slideshow-bg full-screen">
            <div class="flexslider">
                <ul class="slides">
                    <li>
                        <div class="slidebg"
                             style="background-image: url({{asset('explore-assets/images/homepage/hero-1.jpeg')}});background-size: cover;"></div>
                    </li>
                    <li>
                        <div class="slidebg"
                             style="background-image: url({{asset('explore-assets/images/homepage/hero-2.jpeg')}});background-size: cover;"></div>
                    </li>
                    <li>
                        <div class="slidebg"
                             style="background-image: url({{asset('explore-assets/images/homepage/hero-3.jpeg')}});background-size: cover;"></div>
                    </li>
                </ul>
            </div>
            <div class="container" id="homepage">
                <div class="table-wrapper full-width">
                    <div class="table-cell">
                        <div class="heading box">
                            <h1 class="title">{{trans('explore-lang.content.homepage.1')}}</h1>
                            <h3 class="sub-title">{{trans('explore-lang.content.homepage.2')}}</h3>
                        </div>
                        <div class="row">
                            <div class="search-box col-sm-8 col-sm-offset-2">
                                <form action="{{route('explore.search',['type'=>'all-destination'])}}">
                                    <div class="row">
                                        <div class="col-md-8 form-group">
                                            <input type="text" class="input-text full-width" name="q"
                                                   placeholder="{{trans('explore-lang.content.header.search')}}" maxlength="70">
                                        </div>
                                        <!-- s -->
                                        <div class="col-md-4 row">
                                            <div class="col-sm-12 form-group">
                                                <button class="button btn-medium full-width uppercase sky-blue1"><i class="fa fa-search"></i> &nbsp;&nbsp;{{trans('explore-lang.content.homepage.3')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="section global-map-area padding-bottom-0">
            <div class="container">
                <div class="row add-clearfix">
                    <div class="col-sm-6 col-md-3">
                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp" style="display: inline-flex!important;">
                            <img class="image-header-layout" src="{{asset('explore-assets/images/icon/list.svg')}}" alt="author">
{{--                            <i class="soap-icon-friends"></i>--}}
                            <div class="description">
                                <h4>{{trans('explore-lang.content.header.1')}}</h4>
                                <p>{{trans('explore-lang.content.header.2')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp"
                             data-animation-delay="0.3" style="display: inline-flex!important;">
                            <img class="image-header-layout" src="{{asset('explore-assets/images/icon/money-bag.svg')}}">
{{--                            <i class="soap-icon-insurance"></i>--}}
                            <div class="description">
                                <h4>{{trans('explore-lang.content.header.3')}}</h4>
                                <p>{{trans('explore-lang.content.header.4')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp"
                             data-animation-delay="0.6" style="display: inline-flex!important;">
                            <img class="image-header-layout" src="{{asset('explore-assets/images/icon/shopping-cart.svg')}}" align="">
{{--                            <i class="soap-icon-insurance"></i>--}}
                            <div class="description">
                                <h4>{{trans('explore-lang.content.header.5')}}</h4>
                                <p>{{trans('explore-lang.content.header.6')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp"
                             data-animation-delay="0.9" style="display: inline-flex!important;">
                            <img class="image-header-layout" src="{{asset('explore-assets/images/icon/network.svg')}}">
{{--                            <i class="soap-icon-insurance"></i>--}}
                            <div class="description">
                                <h4>{{trans('explore-lang.content.header.7')}}</h4>
                                <p>{{trans('explore-lang.content.header.8')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section white-bg">
            <div class="container">
                <div class="text-center description block">
                    <h1>{{trans('explore-lang.content.homepage.4')}}</h1>
                    <p>{{trans('explore-lang.content.homepage.5')}}</p>
                </div>
                <div class="tour-packages row add-clearfix image-box">
                    @foreach($top_states as $top_city)
                        <div class="col-sm-6 col-md-4">
                            <article class="box animated" data-animation-type="fadeInLeft">
                                <figure>
                                    <a href="{{route('explore.search',['type'=>'city','city'=>$top_city->id_state])}}">
                                        @if($top_city->state_image)
                                            <img src="{{File::exists(public_path($top_city->state_image)) ? asset($top_city->state_image):'http://placehold.it/1440x893'}}" alt="">
                                        @else
                                            <img src="http://placehold.it/1440x893" alt="">
                                        @endif
                                        <figcaption>
                                            @if(app()->getLocale() == 'id')
                                                <h2 class="caption-title">{{$top_city->state_name}}</h2>
                                            @else
                                                <h2 class="caption-title">{{$top_city->state_name_en}}</h2>
                                            @endif


                                        </figcaption>
                                    </a>
                                </figure>
                            </article>
                        </div>
                    @endforeach
                </div>
                <div class="btn_explore_des">
                    <a href="{{route('explore.all-destination')}}" class="button full-width sky-blue1 btn_cus">
                        <i class="soap-icon-departure point_icon"></i>
                        {{trans('explore-lang.content.homepage.6')}}
                    </a>
                </div>
            </div>
        </div>
        <div class="section popular-activities new_bg">
            <div class="container">
                <div class="text-center description block">
                    <h1>{{trans('explore-lang.content.homepage.7')}}</h1>
                    <p>{{trans('explore-lang.content.homepage.8')}}</p>
                </div>
                @php
                    if (request()->isSecure()){
                        $prefix = 'https://';
                    }else{
                        $prefix = 'http://';
                    }
                @endphp
                <div id="activities_slide" class="tour-guide image-carousel style2 flexslider animated"
                     data-animation="slide" data-item-width="270" data-item-margin="30" data-animation-type="fadeInUp">
                    <ul class="slides image-box">
                        @foreach($top_products as $p)
                            @php($product = \App\Models\Product::find($p->id_product))
                            <li>
                                <a class="card_product_theme" href="{{
                                    $product->company->domain_memoria?
                                    $prefix.$product->company->domain_memoria.'/product/detail/'.$product->unique_code.'?ref=directory&ref-url='.url()->current():
                                    null

                                    }}" target="_blank">
                                    <article class="box">
                                        <figure>
                                            <img class="img-product"
                                                 src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                                 alt="">
                                        </figure>
                                        <div class="details">
                                            <h4 class="box-title">{{ str_limit($product->product_name,30) }}</h4>
                                            <hr>
                                            <div class="card_short_desc">{!!  str_limit($product->brief_description,50) !!}
                                            </div>
                                            <ul class="features check">
                                                @if(app()->getLocale() == 'id')
                                                    <li>{{$product->city->city_name}}</li>
                                                @else
                                                    <li>{{$product->city->city_name_en}}</li>
                                                @endif


                                                <li> @if($product->duration)
                                                        {{ $product->duration }} 
                                                        @if($product->duration_type_text == 'Hours')
                                                            {!! trans('product.hours') !!}
                                                        @else
                                                            {!! trans('product.days') !!}
                                                        @endif
                                                    @else
                                                        {!! trans('product.info_later') !!}  
                                                    @endif</li>
                                                @if ($product->schedule->count()>0)
                                                    @if($product->availability =='0')
                                                        <li> {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}</li>
                                                    @else
                                                        <li>
                                                            {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                                            - {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                                        </li>
                                                    @endif
                                                @else
                                                    <li>
                                                        {!! trans('product.info_later') !!}
                                                    </li>
                                                @endif
                                            </ul>
                                            <hr>
                                            <span>{{trans('booking.start_from')}}: </span>
                                            @if($product->pricing->count()>0)

                                                <div class="price"> {{ $product->currency }} {{ str_limit(number_format($product->pricing()->orderBy('price','asc')->first()->price,0),15) }}</div>
                                            @else
                                                <div class="price"> -</div>
                                            @endif
                                        </div>
                                    </article>
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>

        <div class="global-map-area section parallax" data-stellar-background-ratio="0.5" style="padding-bottom: 40px;">
            <div class="container description">
                <div class="text-center">
                    <h2>{!! trans('explore-lang.content.total_trans') !!}</h2>
                </div>
                {{-- <div class="text-center">
                    <h2>Amazing Activities Ideas for <em>you!</em></h2>
                </div>
                <br>
                <div class="row">
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <a href="#">
                            <div class="icon-box style4 animated" data-animation-type="slideInRight"
                                 data-animation-delay="0">
                                <i class="soap-icon-adventure yellow-color"></i>
                                <h4 class="box-title">Adventure</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <a href="#">
                            <div class="icon-box style4 animated" data-animation-type="slideInRight"
                                 data-animation-delay="0.3">
                                <i class="soap-icon-beach yellow-color"></i>
                                <h4 class="box-title">Beach</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <a href="#">
                            <div class="icon-box style4 animated" data-animation-type="slideInRight"
                                 data-animation-delay="0.6">
                                <i class="soap-icon-party yellow-color"></i>
                                <h4 class="box-title">Party</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <a href="#">
                            <div class="icon-box style4 animated" data-animation-type="slideInRight"
                                 data-animation-delay="0.9">
                                <i class="soap-icon-playplace yellow-color"></i>
                                <h4 class="box-title">Golf</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <a href="#">
                            <div class="icon-box style4 animated" data-animation-type="slideInRight"
                                 data-animation-delay="1.2">
                                <i class="soap-icon-couples yellow-color"></i>
                                <h4 class="box-title">Romance</h4>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <a href="#">
                            <div class="icon-box style4 animated" data-animation-type="slideInRight"
                                 data-animation-delay="1.5">
                                <i class="soap-icon-ski yellow-color"></i>
                                <h4 class="box-title">Ski</h4>
                            </div>
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="section new_bg">
            <div class="container">
                <div class="text-center description block">
                    <h1>{{trans('explore-lang.content.homepage.9')}}</h1>
                    <p>{{trans('explore-lang.content.homepage.10')}}</p>
                </div>
                <div id="activities_slide" class="tour-guide image-carousel style2 flexslider animated"
                     data-animation="slide" data-item-width="270" data-item-margin="30" data-animation-type="fadeInUp">
                    <ul class="slides image-box">
                        @foreach($recommended_products as $recommended_product)
                            @php($product = \App\Models\Product::find($recommended_product->id_product))
                            <li>
                                <a class="card_product_theme" href="{{
                                    $product->company->domain_memoria?
                                    $prefix.$product->company->domain_memoria.'/product/detail/'.$product->unique_code.'?ref=directory&ref-url='.url()->current():
                                    null

                                    }}" target="_blank">
                                    <article class="box">
                                        <figure>
                                            <img class="img-product"
                                                 src="{{asset($product->main_image=='img2.jpg'?'img/no-product-image.png':'uploads/products/thumbnail/'.$product->main_image)}}"
                                                 alt="">
                                        </figure>
                                        <div class="details">
                                            <h4 class="box-title">{{ str_limit($product->product_name,30) }}</h4>
                                            <hr>
                                            <div class="card_short_desc">{!!  str_limit($product->brief_description,50) !!}
                                            </div>
                                            <ul class="features check">
                                                @if(app()->getLocale() == 'id')
                                                    <li>{{$product->city->city_name}}</li>
                                                @else
                                                    <li>{{$product->city->city_name_en}}</li>
                                                @endif


                                                <li> @if($product->duration)
                                                        {{ $product->duration }} 
                                                        @if($product->duration_type_text == 'Hours')
                                                            {!! trans('product.hours') !!}
                                                        @else
                                                            {!! trans('product.days') !!}
                                                        @endif
                                                    @else
                                                        {!! trans('product.info_later') !!}  
                                                    @endif</li>
                                                @if ($product->schedule->count()>0)
                                                    @if($product->availability =='0')
                                                        <li> {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}</li>
                                                    @else
                                                        <li>
                                                            {{\Carbon\Carbon::parse($product->schedule[0]->start_date)->format('d M Y')}}
                                                            - {{\Carbon\Carbon::parse($product->schedule[0]->end_date)->format('d M Y')}}
                                                        </li>
                                                    @endif
                                                @else
                                                    <li>
                                                        {!! trans('product.info_later') !!}
                                                    </li>
                                                @endif
                                            </ul>
                                            <hr>
                                            <span>{{trans('booking.start_from')}}: </span>
                                            @if($product->pricing->count()>0)

                                                <div class="price"> {{ $product->currency }} {{ str_limit(number_format($product->pricing()->orderBy('price','asc')->first()->price,0),15) }}</div>
                                            @else
                                                <div class="price"> -</div>
                                            @endif
                                        </div>
                                    </article>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="global-map-area empty_bg" style="display: none;">
            <div class="container">
                <div class="add-clearfix">
                        {{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp">--}}
                        {{--                            <i class="soap-icon-friends"></i>--}}
                        {{--                            <div class="description">--}}
                        {{--                                <h4>Plan Your Tours</h4>--}}
                        {{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        <div class="icon-box style6 animated small-box">
                            {{--                            <i class="soap-icon-friends"></i>--}}
                            <div class="description logo_asoc_container row">
                                <div class="col-auto">
                                    <img class="image-association aeli"
                                        src="{{asset('explore-assets/images/association/aeli-image.png')}}"
                                        alt="association image">
                                </div>
                                <div class="col-auto">
                                    <img class="image-association"
                                        src="{{asset('explore-assets/images/association/asita-image.png')}}"
                                        alt="association image">
                                </div>
                                <div class="col-auto">
                                    <img class="image-association"
                                        src="{{asset('explore-assets/images/association/aspi-image.png')}}"
                                        alt="association image">
                                </div>
                                <div class="col-auto">
                                    <img class="image-association"
                                        src="{{asset('explore-assets/images/association/iatta-image.png')}}"
                                        alt="association image">
                                </div>
                                <div class="col-auto">
                                    <img class="image-association ipi"
                                        src="{{asset('explore-assets/images/association/ipi-image.png')}}"
                                        alt="association image">
                                </div>
                            </div>
                        </div>

                    {{--                    <div class="col-2">--}}
                    {{--                        --}}{{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp">--}}
                    {{--                        --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                        --}}{{--                            <div class="description">--}}
                    {{--                        --}}{{--                                <h4>Plan Your Tours</h4>--}}
                    {{--                        --}}{{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                        --}}{{--                            </div>--}}
                    {{--                        --}}{{--                        </div>--}}
                    {{--                        <div class="icon-box style6 animated small-box">--}}
                    {{--                            --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <img class="image-association" src="{{asset('explore-assets/images/association/asita-image.png')}}" alt="association image" style="margin-top: 2.4rem">--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--                    <div class="col-2">--}}
                    {{--                        --}}{{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp">--}}
                    {{--                        --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                        --}}{{--                            <div class="description">--}}
                    {{--                        --}}{{--                                <h4>Plan Your Tours</h4>--}}
                    {{--                        --}}{{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                        --}}{{--                            </div>--}}
                    {{--                        --}}{{--                        </div>--}}
                    {{--                        <div class="icon-box style6 animated small-box">--}}
                    {{--                            --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <img class="image-association" src="{{asset('explore-assets/images/association/aspi-image.png')}}" alt="association image" style="margin-top: 3.3rem">--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--                    <div class="col-2">--}}
                    {{--                        --}}{{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp">--}}
                    {{--                        --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                        --}}{{--                            <div class="description">--}}
                    {{--                        --}}{{--                                <h4>Plan Your Tours</h4>--}}
                    {{--                        --}}{{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                        --}}{{--                            </div>--}}
                    {{--                        --}}{{--                        </div>--}}
                    {{--                        <div class="icon-box style6 animated small-box">--}}
                    {{--                            --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <img class="image-association" src="{{asset('explore-assets/images/association/iatta-image.png')}}" alt="association image" style="margin-top: 3.3rem">--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--                    <div class="col-2">--}}
                    {{--                        --}}{{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp">--}}
                    {{--                        --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                        --}}{{--                            <div class="description">--}}
                    {{--                        --}}{{--                                <h4>Plan Your Tours</h4>--}}
                    {{--                        --}}{{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                        --}}{{--                            </div>--}}
                    {{--                        --}}{{--                        </div>--}}
                    {{--                        <div class="icon-box style6 animated small-box">--}}
                    {{--                            --}}{{--                            <i class="soap-icon-friends"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <img class="image-association" src="{{asset('explore-assets/images/association/ipi-image.png')}}" alt="association image" style="margin-top: 1.5rem">--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    {{--                    <div class="col-sm-6 col-md-3">--}}
                    {{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp"--}}
                    {{--                             data-animation-delay="0.3">--}}
                    {{--                            <i class="soap-icon-insurance"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <h4>Low Rate Packages</h4>--}}
                    {{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-sm-6 col-md-3">--}}
                    {{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp"--}}
                    {{--                             data-animation-delay="0.6">--}}
                    {{--                            <i class="soap-icon-insurance"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <h4>Travel Insurance</h4>--}}
                    {{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                    {{--                    <div class="col-sm-6 col-md-3">--}}
                    {{--                        <div class="icon-box style6 animated small-box" data-animation-type="slideInUp"--}}
                    {{--                             data-animation-delay="0.9">--}}
                    {{--                            <i class="soap-icon-guideline"></i>--}}
                    {{--                            <div class="description">--}}
                    {{--                                <h4>Travel Guidelines</h4>--}}
                    {{--                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>

@stop

@section('scripts')
    <!-- Flex Slider -->
    <script type="text/javascript"
            src="{{asset('explore-assets/components/flexslider/jquery.flexslider-min.js')}}"></script>
    <script type="text/javascript" src="{{asset('explore-assets/components/flexslider/shCore.js')}}"></script>
    <script type="text/javascript" src="{{asset('explore-assets/components/flexslider/shBrushJScript.js')}}"></script>


    <script type="text/javascript">
      tjq("#slideshow .flexslider").flexslider({
        animation: "fade",
        controlNav: false,
        animationLoop: true,
        directionNav: false,
        slideshow: true,
        slideshowSpeed: 5000,
      });

      tjq('.main-header').toggleClass('main-header-black');

      // tjq(".flexslider").flexslider({
      //     animation: "fade",
      //     controlNav: true,
      //     animationLoop: true,
      //     directionNav: true,
      //     slideshow: true,
      //     slideshowSpeed: 5000,
      //     minItems : 1,
      //     maxItems: 1,
      // });

      // (function() {
      //
      //     // store the slider in a local variable
      //     let $window = tjq(window),
      //         flexslider = { vars:{} };
      //
      //     // tiny helper function to add breakpoints
      //     function getGridSize() {
      //         return (window.innerWidth < 600) ? 1 :
      //             (window.innerWidth < 900) ? 3 : 4;
      //     }
      //     console.log(getGridSize());
      //
      //     tjq(function() {
      //         SyntaxHighlighter.all();
      //     });
      //
      //     $window.load(function() {
      //         tjq('.flexslider').flexslider({
      //             animation: "slide",
      //             animationLoop: false,
      //             itemWidth: 210,
      //             itemMargin: 5,
      //             minItems: getGridSize(), // use function to pull in initial value
      //             maxItems: getGridSize() // use function to pull in initial value
      //         });
      //     });
      //
      //     // check grid size on resize event
      //     $window.resize(function() {
      //         let gridSize = getGridSize();
      //         flexslider.vars.minItems = gridSize;
      //         flexslider.vars.maxItems = gridSize;
      //     });
      // }());
    </script>
@stop
