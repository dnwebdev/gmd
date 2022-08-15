@extends('public.agents.admiria.base_layout')

@section('additionalStyle')
    <link href="{{ asset('dest-customer/css/checkout.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dest-customer/lib/css/touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <link href="{{ asset('materialize/css/datedropper.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css"
          rel="stylesheet" media="screen,projection">
    <style>
        .card-activity .activity-image {
            width: 165px;
            height: 127px;
        }

        .card-activity .activity-meta h4:before {
            display: inline-block;
            content: '';
            margin: 0 .5rem;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(0, 0, 0, .54);
            vertical-align: middle;
        }

        @media screen and (max-width: 767px) {
            .card-activity .activity-image {
                height: auto;
                width: 100%;
            }
        }
        a.no-hover:hover{
            text-decoration: none;
        }
    </style>
@endsection

@section('main_content')
    <section id="content">
        <div id="maincontent" class="bg-grey">
            <div class="checkout">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <form id="form_ajax" method="POST" action="{{ Route('memoria.send_inquiry') }}"
                                  style="padding-left: .75rem; padding-right: .75rem;">
                                {{ csrf_field() }}
                                <input type="hidden" name="product" value="{{ $product->unique_code }}">
                                <a href="{{route('memoria.detail',['slug'=>$product->unique_code])}}" class="no-hover">
                                    <div class="card-activity">
                                        <div class="activity-image">

                                            <img src="{{ asset('uploads/products/'.$product->main_image) }}"
                                                 alt="{{ $product->product_name }}" height="128">

                                        </div>
                                        <!-- <span style="margin-right: 5px;margin-top: 5px; display: inline-block">Abseiling</span> -->
                                        <div class="featured-category">
                                            <h2>{{ $product->product_name }}</h2>
                                            <div class="cat-tag mb-3" style="flex-wrap: wrap;">
                                                <!-- <div class="cat-tag"> -->
                                                    @if(count($product->tagValue))
                                                        @foreach($product->tagValue as $tag)
                                                            <span style="margin-right: 5px;margin-top: 5px; display: inline-block; background-color:#0893cf; border-radius:5px;"><p style="color:white; margin: 0.3rem 0.3rem 0.3rem 0.3rem; font-size: 0.9rem;">{{ $tag->tag->name ? $tag->tag->name : "" }}</p></span>
                                                        @endforeach
                                                    @else
                                                        <span style="margin-right: 5px;margin-top: 5px; display: inline-block">Uncategorized</span>
                                                    @endif
                                                <!-- <div> -->
                                            </div>
                                            <div class="activity-info">
                                                <h5><span class="fa fa-bullhorn"></span>{{$product->brief_description}}
                                                </h5>
                                                <h5>
                                                    <span class="fa fa-money"></span> {{__('booking.start_from')}} {{$product->currency}} {{$product->advertised_price}}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="card-form widget">
                                    <h3>{{__('booking.request_booker_details')}}</h3>
                                    <div class="widget-content">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group field">
                                                    <label for="total_guest">Total {{__('booking.guest')}} <span
                                                                class="text-danger">*</span></label>
                                                    <input type="text" value="1" name="total_guest" id="total_guest"
                                                           class="form-control" style="text-align: center;" required/>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group field">
                                                    <label for="schedule">{{__('booking.requested_schedule')}} <span class="text-danger">*</span></label>
                                                    <input type="text" class="datepicker form-control" name="schedule" data-date="" autocomplete="off" id="datepicker">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group field">
                                                    <label for="payment">{{__('booking.preferred_payment')}} </label>
                                                    <select name="payment" id="payment" class="form-control">
                                                        <option value="">{{__('booking.select_payment')}}</option>
                                                        <option value="Online via Website">{{__('booking.online_payment')}}</option>
                                                        <option value="Direct Transfer">{{__('booking.direct_payment')}}</option>
                                                        <option value="On The Spot">{{__('booking.ots_payment')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="name">{{__('booking.name')}} <span
                                                                class="text-danger">*</span></label>
                                                    <input type="text" name="name" id="name" class="form-control"
                                                           required/>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group">
                                                    <label for="email field">Email <span
                                                                class="text-danger">*</span></label>
                                                    <input name="email" type="email" class="form-control"
                                                           aria-describedby="emailHelp" required/>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-4">
                                                <div class="form-group field">
                                                    <label for="phone">{{__('booking.phone')}} <span
                                                                class="text-danger">*</span></label>
                                                    <input name="phone" id="phone" type="phone" class="form-control"
                                                           name="phone" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-form widget">
                                    <h3>{{__('booking.note')}}</h3>
                                    <div class="widget-content">
                                        <div class="form-group">
                                            <label for="notes">{{__('booking.note_detail')}}</label>
                                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                                      placeholder="{{__('booking.type_note')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-book">
                                    <button class="btn btn-primary">{{__('booking.submit')}} {{__('booking.special_request')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('additionalScript')
    <script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
    <script src="{{ asset('dest-customer/lib/js/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        var jBook = jQuery.noConflict();
        jBook(document).ready(function ($) {
            $("input[name='total_guest']").TouchSpin({
                min: 1,
                max: 99
            });

            var now = new Date();
            now.setDate(now.getDate());
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = (month) + "/" + (day) + '/' + now.getFullYear();

            $('#datepicker').datepicker({
                format: 'mm/dd/yyyy',
                todayHighlight: true,
                autoclose: true,
                startDate: today,
            });

            form_ajax(jQuery('#form_ajax'), function (e) {
                if (e.status == 200) {
                    toastr.remove()
                    swal({
                        title: "Success",
                        text: e.message,
                        type: "success",
                    }).then(function () {
                        return window.location = window.location.origin;
                    });
                } else {
                    toastr.remove()
                    swal({
                        title: "{{trans('general.whoops')}}",
                        text: e.message,
                        type: "error",
                    }).then(function () {
                    });
                }
            });
        });
    </script>
@endsection
