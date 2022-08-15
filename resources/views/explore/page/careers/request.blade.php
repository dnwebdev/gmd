@extends('explore.layout')

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
    {{--    <div class="container description">--}}
    {{--        --}}
    {{--        <br>--}}
    {{--    </div>--}}
    {{--    <div class="global-map-area section parallax" data-stellar-background-ratio="0.5">--}}
    {{--        <div class="container description">--}}
    {{--            <div class="text-left">--}}
    {{--                <h2>Careers at GOMODO!</h2>--}}
    {{--                <h3>"BE PART OF US, BE PART OF SOMETHING BIG"</h3>--}}
    {{--            </div>--}}
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
                                                <h2>{{trans('explore-lang.career.apply-for')}} {{$career->title}}</h2>
                                            </div>
                                        </div>
                                        {!! Form::open(['id'=>'applyCareer']) !!}
                                        <div class="row">
                                            <div class="col-lg-8 mb-30 mt-15">
                                                <div class="form-group">
                                                    <label for="full_name">{{trans('explore-lang.career.name')}}<span
                                                                class="required-color-red">*</span></label>
                                                    {!! Form::text('full_name',null,['class'=>'form-control']) !!}
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email<span
                                                                class="required-color-red">*</span></label>
                                                    {!! Form::email('email',null,['class'=>'form-control']) !!}
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone_number">{{trans('explore-lang.career.phone')}}
                                                        <span class="required-color-red">*</span></label>
                                                    {!! Form::tel('phone_number',null,['class'=>'form-control number']) !!}
                                                </div>
                                                <div class="form-group">
                                                    <label for="portfolio">Portfolio & CV <span
                                                                class="required-color-red">*</span></label>
                                                    <small>PDF only (max 2 MB)</small>
                                                    {!! Form::file('portfolio',['class'=>'form-control', 'accept' => 'application/pdf']) !!}
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-primary btn-apply">{{trans('explore-lang.career.apply-position')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
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

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    <script>
        tjq(document).on('submit', '#applyCareer', function (e) {
            tjq('.loading').addClass('show');
            e.preventDefault();
            let $this = tjq(this);
            $this.find('label.error').remove();
            let fD = new FormData();
            let files = tjq(this).find('input, textarea');
            tjq.each(files, function (i, e) {
                let f = jQuery(e);
                if (f.attr('type') === 'file') {
                    if (f[0].files.length > 0) {
                        fD.append(f.attr('name'), f[0].files[0]);
                    }
                } else {
                    fD.append(f.attr('name'), f.val());
                }
            });
            tjq.ajax({
                url: $this.attr('action'),
                data: fD,
                type: "POST",
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (data) {
                    tjq('.loading').removeClass('show');
                    swal.fire({
                        type: 'success',
                        title: '{!! trans('explore-lang.career.applied') !!}',
                        text: '{!! trans('explore-lang.career.success') !!}',
                        showConfirmButton: false,
                    }).then(setTimeout(function () {
                        location.href = data.result.redirect;
                    }, 5000));

                },
                error: function (e) {
                    tjq('.loading').removeClass('show');
                    if (e.status === 422) {
                        let dt = e.responseJSON;
                        let errors = dt.errors;
                        tjq.each(errors, function (i, e) {
                            tjq(document).find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                            tjq(document).find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>');
                        })
                    }
                }
            })
        })
    </script>
@stop