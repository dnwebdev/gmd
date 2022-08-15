@extends('customer.master.index')

@section('content')
    <div class="bg-light-blue block-height">
        <div class="container pt-5">
            @if(request()->has('ref') && request('ref') ==='directory')
                @php
                    if (request()->isSecure()){
                        $prefix = 'https://';
                    }else{
                        $prefix = 'http://';
                    }
                @endphp
                <li><a href="{{$prefix.env('APP_URL')}}">Directory</a></li>
            @endif
            <ul class="breadcrumb">
                <li><a href="{{route('memoria.home')}}">{!! trans('customer.home.home') !!}</a></li>
            </ul>
        </div>
        <div id="retrieve" class="container pb-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body card-retrieve">
                            <div class="row">
                                @if($status =='ok')
                                    <div class="col-12">
                                        <div class="text-center">
                                            <img width="100" src="{{asset('img/1024px-Commons-emblem-success.svg.png')}}" alt="">
                                            <p>
                                                {{$message}}
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12">
                                        <div class="text-center">
                                            <img width="100" src="{{asset('img/497px-Error.svg.png')}}" alt="">
                                            <p>
                                                {{$message}}
                                            </p>
                                        </div>
                                    </div>

                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')


@stop
