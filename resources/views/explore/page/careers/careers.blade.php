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
    {{--    OLD--}}
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
                                    <div class="col-xs-12 header-career text-center">
                                        <h2><strong>{{trans('explore-lang.career.current-opening')}}</strong></h2>
                                    </div>
                                    <div class="render-career">

                                    </div>
                                </div>

                                <div class="row list-career load-more-career">
                                    <div class="col-lg-12 text-center">
                                        <button class="btn btn-primary btn-load-more">{{trans('explore-lang.career.see-more')}}</button>
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
    <script>
        let page = 1;
        render();

        tjq('.btn-load-more').on('click', function (e) {
            tjq('.loading').show();

            if (e.ctrlKey || e.shiftKey) {
                tjq('.loading').hide();
            }
        });

        function render() {
            tjq('.loading').addClass('show');
            let data = {
                page: page,
            };
            if (page !== undefined || page !== '' || page !== null) {
                tjq.ajax({
                    url: "{{route('explore.careers.render')}}",
                    type: "POST",
                    data: data,
                    dataType: 'html',
                    success: function (data) {
                        jQuery('.loading').removeClass('show');
                        tjq('.load-more-career').show();
                        if (page === 1) {
                            tjq('.render-career').html(data)
                        } else {
                            tjq('.render-career').append(data);
                        }
                        if (tjq(document).find('.pagination-career').length > 0) {
                            if (tjq(document).find('.pagination-career').attr('data-nextpage') !== '') {
                                page = tjq(document).find('.pagination-career').attr('data-nextpage');
                            } else {
                                page = null
                            }
                        } else {
                            page = null
                        }
                        tjq(document).find('.pagination-career').remove();
                        if (page === null) {
                            tjq('.load-more-career').remove();
                        }
                    },
                    errors:function (err) {
                        jQuery('.loading').removeClass('show');
                    }
                })
            } else {
                tjq('.load-more-career').remove();
                jQuery('.loading').removeClass('show');
            }
        }

        tjq(document).on('click', '.btn-load-more', function () {
            render();
        })
    </script>
@stop
