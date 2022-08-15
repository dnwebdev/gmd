@extends('klhk.explore.layout')

@section('content')
    <div class="page-title-container">
        <div class="container">
            <div class="page-title pull-left">
                <h2 class="entry-title">{{$title}}</h2>
            </div>
            <ul class="breadcrumbs pull-right">
                <li><a href="{{route('memoria.home')}}">{{trans('explore-lang.header.home')}}</a></li>
                <li class="active">{{$title}}</li>
            </ul>
        </div>
    </div>
    <div class="header-career-img">
        <img src="{{asset('explore-assets/images/header-career.jpg')}}" alt="image photo"/>
        <h1>{{trans('explore-lang.career.h1')}}</h1>
        <h2>"{{trans('explore-lang.career.h2')}}"</h2>
    </div>
{{--    <div class="global-map-area section parallax" data-stellar-background-ratio="0.5">--}}
{{--        <div class="container description">--}}
{{--            --}}
{{--            <br>--}}
{{--        </div>--}}
{{--    </div>--}}
    <section id="content">
        <div class="container">
            <div id="main">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="bordered">
                                <div class="row">
                                    <div class="col-lg-12 text-left header-career ">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h2>{{$career->title}}</h2>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h5>{{trans('explore-lang.career.location')}} : {{$career->location}}</h5>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 mb-30 mt-15">
                                                <a href="{{route('explore.careers.request',['id'=>$career->id])}}" class="btn btn-primary btn-apply-career">{{trans('explore-lang.career.apply-position')}}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                {!! $career->description !!}
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
@section('css')
    <link rel="stylesheet" href="{{asset('explore-assets/css/career.css')}}">
@stop

