@extends('public.agents.admiria.base_layout')

@section('additionalStyle')
    <link href="{{ asset('dest-customer/css/checkout.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
@endsection

<style>
    .autocomplete-suggestions {
        background: white;
        border: 1px solid #ced4da;
    }
    .autocomplete-suggestions div {
        margin-left: 1rem;
    }
    .searchtop {
        margin-top: 1rem;
    }
</style>

@section('main_content')
    <section id="content">
        <div id="maincontent" class="bg-grey">
            <div class="checkout">
                <div class="container">
                    <div class="row">
                        <div class="col">
                          
                        <form autocomplete="off" id="form_ajax" method="POST"
                              action="{{ Route('memoria.create_order') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="product" value="{{ $product->unique_code }}">
                            <input name="adult" type="hidden" value="{{ $adult }}">
                            <input name="children" type="hidden" value="{{ $children ? $children : 0 }}">
                            <input name="infant" type="hidden" value="{{ $infant ? $infant :0 }}">
                            <input name="schedule" id="schedule" type="hidden" value="{{ $schedule }}">

                            <div class="card-activity">
                                <div class="activity-image">
                                    <img src="{{ asset('uploads/products/'.$product->main_image) }}"
                                         alt="{{ $product->product_name }}" height="128">
                                </div>

                                <div class="activity-detail">
                                    <h2>{{ $product->product_name }}</h2>
                                    <div class="activity-meta" style="flex-wrap: wrap;">
                                        @if(count($product->tagValue))
                                            @foreach($product->tagValue as $tag)
                                                <h4>{{ $tag->tag->name ? $tag->tag->name : "" }}</h4>
                                            @endforeach
                                        @else
                                            <h4>Uncategorized</h4>
                                        @endif
                                    </div>
                                    <div class="activity-info">
                                        <h5>
                                            <span class="fa fa-calendar"></span> {{ date('d M Y',strtotime($schedule)) }}
                                        </h5>
                                        <h5>
                                            <span class="fa fa-check-circle"></span>{{ $adult }} {{ optional($pricing->unit)->name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>

                            <div class="card-form widget">
                                <h3>{{__('booking.guest')}}</h3>
                                <div class="widget-content">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label for="first_name field">{{__('booking.name')}} <span
                                                            class="text-danger">*</span></label>
                                                <input type="text" name="first_name" id="first_name" maxlength="25" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="form-group">
                                                <label for="email field">Email <span
                                                            class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" maxlength="50" aria-describedby="emailHelp" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="form-group field">
                                                <label for="phone">{{__('booking.phone')}} <span
                                                            class="text-danger">*</span></label>
                                                <input type="text" name="phone" id="phone"  class="form-control" maxlength="15" required/>
                                                <span class="text-danger" id="phone_error_message"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="form-group field">
                                                <label for="country_search">{{__('booking.nationality')}} <span
                                                            class="text-danger">*</span></label>
                                                <input autocomplete="nope" type="text" id="country_search"
                                                       placeholder="{{__('booking.search')}} {{__('booking.nationality')}}"
                                                       class="form-control" required/>
                                                <input type="hidden" name="country" id="country"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="full-hr"/>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-3">
                                            <div class="form-group field">
                                                <label for="city_search">{{__('booking.city')}} <span
                                                            class="text-danger">*</span></label>
                                                <input autocomplete="nope"
                                                       placeholder="{{__('booking.search')}} {{__('booking.city')}}"
                                                       id="city_search" type="text" class="form-control" required/>
                                                <input type="hidden" name="city" id="city"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-9">
                                            <div class="form-group field">
                                                <label for="address">{{__('booking.address')}} <span
                                                            class="text-danger">*</span></label>
                                                <input type="text" name="address" id="address" class="form-control" maxlength="100"
                                                       required/>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>

                            <div class="card-form widget">
                                <h3>{{__('booking.note')}}</h3>
                                <div class="widget-content">
                                    <div class="form-group">
                                        <label for="note-to-operator">{{__('booking.note_detail')}}</label>
                                        <textarea name="external_notes" id="external_notes" class="form-control"
                                                  rows="3" placeholder="{{__('booking.type_note')}}"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="card-form widget">
                                <h3>{{__('booking.having_promotion')}}</h3>
                                <div class="widget-content">
                                    <label for="note-to-operator">{{__('booking.get_discount')}}</label>
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="form-group no-margin form-with-icon icon-right">
                                                <input type="text" class="form-control" name="voucher_code"
                                                       id="voucher_code" placeholder="Promo Code">
                                                <span class="form-icon"><span id="voucher_indicator"
                                                                              class="fa fa-check-circle-o"></span></span>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <button type="button" id="apply_voucher" class="no-margin btn btn-primary">
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-form widget">
                                <h3>{{__('booking.price_details')}}</h3>
                                <div class="widget-content">
                                    {{-- {{dd($pricing)}} --}}
                                    @foreach($pricing->price_detail as $guest=>$row)
                                        @if($row > 0)
                                            <div class="row">
                                                <div class="col-7">{{ ucwords(optional($pricing->unit)->name) }}</div>
                                                <div class="col-5 text-right">{{ $pricing->currency }} {{ number_format($row,0) }}</div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @foreach($pricing->tax_detail as $row)
                                        <div class="row">
                                            <div class="col-7">{{ $row->tax_name }} {{ abs($row->tax_value) }}</div>
                                            <div class="col-5 text-right">{{ $pricing->currency }} {{ number_format($row->tax_amount,0) }}</div>
                                        </div>
                                    @endforeach

                                    @if($product->fee_amount > 0)
                                        <div class="row">
                                            <div class="col-7">{{ $product->fee_name }}</div>
                                            <div class="col-5 text-right">{{ $pricing->currency }} {{ number_format($product->fee_amount,0) }}</div>
                                        </div>
                                    @endif

                                    @if($product->discount_amount > 0)
                                        <div class="row">
                                            <div class="col-7">{{ $product->discount_name }}</div>
                                            <div class="col-5 text-right text-success">{{ $pricing->currency }} {{ number_format(($product->discount_amount_type==1) ? $product->discount_amount/100*$pricing->amount : $product->discount_amount,0) }}</div>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-7">Voucher</div>
                                        <div class="col-5 text-right text-success"
                                             id="total_voucher_amount_bar">{{ $pricing->currency }} 0
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-7"><b>{{__('booking.total')}}</b></div>
                                        <div class="col-5 text-right" id="total_amount_column"
                                             url="{{ Route('memoria.validate_schedule') }}">
                                            <b>{{ $product->currency }} {{ number_format($pricing->total_amount,0) }}</b>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="footer-filler"></div>
                            <footer id="checkout-footer">
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex justify-content-end align-items-center">
                                                <div class="checkout-price">
                                                    <h5>{{__('booking.total')}}</h5>
                                                    <h3 id="total_amount_column_footer">{{ $product->currency }} {{ number_format($pricing->total_amount,0) }}</h3>
                                                </div>
                                                <div class="checkout-button">
                                                    <button id="paynow" type="button"
                                                            class="btn btn-checkout form-control">{{ $product->booking_confirmation ? __('booking.pay') : __('booking.book') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </footer>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="myModal" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-processing">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5 text-center">
                            <div>
                                <img height="50"
                                     src="{{ asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo ) }}"
                                     alt="logo"/>
                            </div>
                            <span class="logo-text">{{ Session::get('company_name') }}</span>
                        </div>
                        <div class="col-7 text-center">
                            <div>
                                <img height="50" src="{{ asset('themes/admiria/images/preloader.gif') }}"/>
                            </div>
                            {{__('booking.processing')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-activity .activity-image{
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
            .card-activity .activity-image{
                height: auto;
                width: 100%;
            }
        }
    </style>
@endsection

@section('additionalScript')
    <script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script>
        var jBook = jQuery.noConflict();
        jBook(document).ready(function ($) {
            var error_phone = true;
            $('#phone').keydown(function (event) {
                if (event.keyCode > 47 && event.keyCode < 58 || event.keyCode == 187 || event.keyCode == 16 || event.keyCode == 91 || event.keyCode == 93 || (event.keyCode > 36 && event.keyCode < 41) || event.keyCode == 8 || event.keyCode == 13 || event.keyCode == 27 || event.keyCode == 20 || event.keyCode == 9) return true;
                else return false;
            })
            $('#phone').keyup(function () {
                var phone_pattern = new RegExp(/^\s*(?:\+?(\d{1,3}))?[- (]*(\d{3})[- )]*(\d{3})[- ]*(\d{4})(?: *[x/#]{1}(\d+))?\s*$/);

                if (phone_pattern.test($("#phone").val())) {
                    $("#phone_error_message").hide();
                    error_phone = false;
                }
                else {
                    $("#phone_error_message").html("Should be between 9 to 14 digit");
                    $("#phone_error_message").show();
                    error_phone = true;
                }
            });
            $("#country_search").devbridgeAutocomplete({
                onSearchStart: function () {
                    // $('#country').val('');
                },
                serviceUrl: "{{ Route('countries.search')}}", //tell the script where to send requests
                type: 'GET',
                //callback just to show it's working
                onSelect: function (suggestion) {
                    //$('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
                    $('#country').val(suggestion.data);

                },
                minChars: 2,
                showNoSuggestionNotice: true,
                noSuggestionNotice: 'Sorry, no matching results',


            });

            $("#city_search").devbridgeAutocomplete({
                onSearchStart: function (params) {
                },
                serviceUrl: "{{ Route('cities.search') }}",
                params: {
                    country: function () {
                        return $("#country").val();
                    }
                },
                type: 'GET',
                onSelect: function (suggestion) {
                    $('#city').val(suggestion.data);

                },
                minChars: 2,
                showNoSuggestionNotice: true,
                noSuggestionNotice: 'Sorry, no matching results',
            });

            $('#myModal').modal({
                show: false,
                backdrop: 'static',
                keyboard: false
            });

            $('#paynow').click(function () {
                $('.field input').each(function () {
                    if ($(this).val().length == 0) {
                        empty = true;
                    }
                });
                if (
                    $('input[name="first_name"]').val().length == 0 ||
                    $('input[name="email"]').val().length == 0 ||
                    // $('input[name="phone"]').val().length == 0 ||
                    $('input[name="country"]').val().length == 0 ||
                    $('input[name="city"]').val().length == 0 ||
                    error_phone
                ) {
                    swal("{{__('booking.form_notice')}}")
                } else {
                    $('#myModal').modal('show');

                    setTimeout(function () {
                        $.ajax({
                            url: $('#form_ajax').attr('action'),
                            type: "POST",
                            data: $('#form_ajax').serialize(),
                            dataType: 'json',
                            success: function (response) {
                                if (response.data !== undefined) {
                                    if (response.data.xendit_url !== undefined) {
                                        return window.location = response.data.xendit_url;
                                        $('#myModal').modal('hide');
                                        setTimeout(function () {
                                            $('#main-content').fadeOut('fast', function () {
                                                return window.location = response.data.xendit_url;
                                            });
                                        }, 1000);
                                    } else {
                                        $('#myModal').modal('hide');
                                        toastr.remove()
                                        swal({
                                            title: response.message,
                                            type: "success",
                                            html: "Please note that your booking <u>has not yet been confirmed</u>. You will be notified by email within the next 48 hours regarding the status of your booking"
                                        }).then(function () {
                                            return window.location.href = window.location.origin;
                                        });
                                    }
                                } else {
                                    $('#myModal').modal('hide');
                                    toastr.remove()
                                    swal({
                                        title: response.message,
                                        type: "success",
                                        html: "Please note that your booking <u>has not yet been confirmed</u>. You will be notified by email within the next 48 hours regarding the status of your booking"
                                    }).then(function () {
                                        return window.location.href = window.location.origin;
                                    });
                                }
                            },
                            error: function (e) {
                                console.log(e);
                                $('#myModal').modal('hide');
                                if (e.responseJSON.message !== undefined) {
                                    swal('.. ', e.responseJSON.message, 'error');
                                } else {
                                    swal('Please check your connection');
                                }
                                // if (e.responseText) {
                                //     return window.location = e.responseText.invoice_url;
                                //     $('html, body').animate({
                                //         scrollTop: $("#form_ajax").offset().top
                                //     }, 'fast');
                                // }
                                // if (e.responseText == "") {
                                //     swal({
                                //         title: "Booking Received",
                                //         text: "Please note that your booking has not yet been confirmed. You will be notified within the next 48 hours regarding the status of your booking",
                                //         type: "success"
                                //     }).then(function () {
                                //         return window.location = window.location.origin;
                                //     });
                                // } else {
                                //     if (e.responseJSON.message !== undefined) {
                                //         swal('.. ', e.responseJSON.message, 'error');
                                //     } else {
                                //         swal('Please check your connection');
                                //     }
                                //
                                // }
                            }
                        });
                    }, 5000);
                }
            });

            $('#apply_voucher').click(function () {
                get_total_price();
            });

            function get_total_price() {
                $.ajax({
                    url: $('#total_amount_column').attr('url'),
                    type: "POST",
                    data: $('#form_ajax').serialize(),
                    dataType: 'json',

                    success: function (response) {
                        if (response.status) {
                            if (response.status == 200) {

                                $('#total_amount_column').html('<strong>' + response.data.currency + ' ' + response.data.total_amount_text + '</strong>');
                                $('#total_amount_column_footer').html(response.data.currency + ' ' + response.data.total_amount_text);

                                if (response.data.total_voucher_amount) {
                                    total_voucher_amount = response.data.currency + ' ' + response.data.total_voucher_amount.toLocaleString();
                                    $('#total_voucher_amount_bar').html(total_voucher_amount);
                                    $("#voucher_indicator").css("color", "green");
                                } else {
                                    $('#total_voucher_amount_bar').html('');
                                }

                            } else {
                                swal("Error", response.message, "warning");
                            }
                        } else {
                            swal("Error", 'Invalid Response', "warning");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#voucher_indicator").css("color", "red");
                        response = JSON.parse(jqXHR.responseText);
                        if (response.errors) {
                            swal("Error", response.errors.voucher_code[0], "warning");
                            $.each(response.errors, function (key, value) {
                            });
                        }
                    }
                });
            }
        });
    </script>
@endsection
