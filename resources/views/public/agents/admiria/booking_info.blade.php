@extends('public.agents.admiria.base_layout')

@section('additionalStyle')
<link href="{{ asset('dest-customer/css/product.css') }}" rel="stylesheet" />
@endsection

@section('main_content')
<section id="content">
  <div id="maincontent">
    <div class="product-detail">
      <div class="container">
        <form id="form_ajax" method="POST" action="{{ $order->payment->first() ? $order->payment->first()->invoice_url : Route('invoice.make_payment',$order->invoice_no) }}">
        {{ csrf_field() }}
        <input type="hidden" name="invoice" value="{{ $order->invoice_no }}" id="invoice" />  
        <div class="dashboard-header">
        @if($order->status == 0)
            @if($order->allow_payment)
            <p class="text-danger" style="font-size: 1.3rem; padding: 0;">
              <i class="fa fa-info-circle" style="padding-right: .4rem"></i>
              {{ trans('retrieve_booking.you_need') }}
            </p>
            @else
            <p class="text-warning" style="font-size: 1.3rem; padding: 0;">
              <i class="fa fa-info-circle" style="padding-right: .4rem"></i>
              {{ trans('retrieve_booking.please_wait') }}
            </p>
            @endif
            @elseif($order->status == 1)
            <p class="text-success" style="font-size: 1.3rem; padding: 0;">
              <i class="fa fa-check-circle" style="padding-right: .4rem"></i>
              {{ trans('retrieve_booking.booking_is_complete') }}
            </p>
            @endif
          </div>
          <div class="row">
            <div class="col-sm-12 col-md-7 col-lg-8">
              <div class="widget retrieve_booking" id="product-detail">
                <div class="widget-header">
                  <h3>{{ trans('retrieve_booking.booking_information') }}</h3>
                </div>
                <div class="widget-content">
                  <div class="widget-form">
                    <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                      <div class="form-group">
                        <label for="pname">{{ trans('retrieve_booking.invoice_number') }}</label>
                        <p class="re_title">{{$order->invoice_no}}</p> 
                        </div>
                      </div>
                    </div>
                    <hr class="full-hr">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="form-group">
                          <label for="pname">{{ trans('retrieve_booking.first_name') }}</label>
                          <input type="text" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->first_name }}" disabled/>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label for="pname">{{ trans('retrieve_booking.email') }}</label>
                          <input type="text" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->email }}" disabled/>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="form-group">
                          <label for="pname">{{ trans('retrieve_booking.phone_number') }}</label>
                          <input type="number" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->phone }}" disabled/>
                        </div>
                      </div>
                      {{-- <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="form-group">
                          <label for="pname">{{ trans('retrieve_booking.last_name') }}</label>
                          <input type="text" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->last_name }}" disabled/>
                        </div>
                      </div> --}}
                    </div>

                    <hr class="full-hr">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="form-group">
                          <label for="pname">Country / Negara</label>
                          <input type="text" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->city->state->country->country_name }}" disabled/>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="form-group">
                          <label for="pname">State / Provinsi</label>
                          <input type="text" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->city->state->state_name }}" disabled/>
                        </div>
                      </div>
                      <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <div class="form-group">
                          <label for="pname">City / Kota</label>
                          <input type="text" id="pname" name="pname" class="form-control" value="{{ $order->customer_info->city->city_name }}" disabled/>
                        </div>
                      </div>
                    </div>
                    
                    <hr class="full-hr">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-12">
                          <label for="pname">{{ trans('retrieve_booking.address') }}</label>
                          <textarea class="form-control" rows="4" cols="100" disabled>{{ $order->customer_info->address }}</textarea>
                      </div>
                    </div>
                    <hr class="full-hr">
                    <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-12">
                          <label for="pname">{{ trans('retrieve_booking.note') }}</label>
                          <textarea class="form-control" rows="4" cols="100" disabled>{!! $order->external_notes !!}</textarea>
                      </div>
                    </div>
                    @if($order->status == 0)
                    <hr class="full-hr">
                      <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                          <label for="amount">{{ trans('retrieve_booking.total') }}</label>
                          <input url="{{ Route('memoria.validate_schedule') }}" type="text" id="amount" name="amount" class="form-control" value="{{ $order->order_detail->currency }} {{ number_format($order->total_amount,0) }}" disabled/>
                      </div>
                    </div>
                      @if($order->status == 0 && $order->allow_payment)
                    <hr class="full-hr">
                      <div class="row">
                      <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <button id="paynow" data-target=".bs-example-modal-sm" type="submit" class="btn-success btn-lg btn-block">{{ trans('retrieve_booking.pay_now') }}</button>
                      </div>
                    </div>
                      @endif
                    @endif
                    <hr class="full-hr" style="display: none">
                    <div class="row" style="display: none">
                      <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                          <label for="pname">Send your invoice</label>
                          <input type="email" id="email" name="email" class="form-control" placeholder="Your email here"/>
                      </div>
                      <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                        <button id="send_email" type="button" class="btn btn-lg btn_re_submit btn-block" url="{{ Route('memoria.send_invoice_mail') }}">Submit</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-12 col-md-5 col-lg-4">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12">
                  <img src="{{ asset('uploads/orders/'.$order->order_detail->main_image) }}" style="max-height: 300 !important"/>
              </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <h4 class="re_product_title">{{ $order->order_detail->product_name }}</h4>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="product-demography">
                    <h3><span class="fa fa-map-marker"></span> {{ $order->order_detail->city ? $order->order_detail->city->city_name : 'Indonesia'}}</h3>
                    @if($order->order_detail->duration)
                    <h3>
                      <span class="fa fa-hourglass"></span> {{$order->order_detail->duration}}
                      @if ($order->order_detail->duration_type == 0)
                        {{ trans('retrieve_booking.hours') }}
                      @elseif($order->order_detail->duration_type == 1)
                        {{ trans('retrieve_booking.day') }}
                      @else
                      @endif
                    </h3>
                    @endif
                    <h3 class="featured-time"><span class="fa fa-calendar"></span> {{ date('d M, Y',strtotime($order->order_detail->schedule_date)) }}</h3>
                    <h3 class="featured-time"><span class="fa fa-clock-o"></span> {{ $order->order_detail->product->schedule[0]->start_time }} - {{ $order->order_detail->product->schedule[0]->end_time }}</h3>
                    {{-- <h3><span class="fa fa-male"></span> {{$order->order_detail->adult}} Adult @if($order->order_detail->children)and {{$order->order_detail->children}} Children @endif</h3> --}}
                    <h3><span class="fa fa-male"></span> {{$order->order_detail->adult}} {{ optional($order->order_detail->unit)->name }}</h3>
                  </div>
                </div>
            </div>
            @if($company->phone_company || $company->email_company || $company->address_company || $company->city->city_name)
            <hr class="full-hr">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <h4>{{ trans('retrieve_booking.provider_contact') }}</h4>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="product-demography">
                    @if($company->phone_company)
                    <h3><span class="fa fa-phone"></span>{{ $company->phone_company }}</h3>
                    @endif
                    @if($company->email_company)
                    <h3><span class="fa fa-envelope"></span>{{ $company->email_company }}</h3>
                    @endif
                    @if($company->address_company)
                    <h3 class="featured-time"><span class="fa fa-map-marker"></span>{{ $company->address_company }}</h3>
                    @endif
                    @if($company->city)
                    <h3 class="featured-time"><span class="fa fa-map-marker"></span>{{ $company->city->city_name }}</h3>
                    @endif
                  </div>
                </div>
            </div>
            @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<div id="myModal" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-processing">
    <div class="modal-content">
      <a data-dismiss="modal" id="close-link" class="close-modal text-right pointer"><i class="fa fa-window-close-o "></i></a>

      <div class="modal-body">
        <div class="row">
          <div class="col-5 text-center">
            <div>
              <img height="50" src="{{asset(strpos($company->logo, 'dest-operator') !== false ? $company->logo : 'uploads/company_logo/'.$company->logo )}}" alt="logo" />
            </div>
            <span class="logo-text">{{ Session::get('company_name') }}</span>
          </div>
          <div class="col-7 text-center">
            <div>
              <img height="50" src="{{ asset('themes/admiria/images/preloader.gif') }}"  />
            </div>
            Processing your Booking
          </div>
        </div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endSection

@section('additionalScript')
<script>
    function get_param(name) {
        return (location.search.split(name + '=')[1] || '').split('&')[0];
    }


    jQuery(document).ready(function(){
      action = get_param('action');

      jQuery('#send_email').click(function(){
        jQuery.ajax({

          url:jQuery('#send_email').attr('url'),
          type: "POST",
          data: {_token:jQuery('input[name="_token"]').val(),invoice:jQuery('#invoice').val(),email:jQuery('#email').val()},
          dataType :'json',
          success:function(response){
            alert(response.message);

          },
          error:function(e){

            response = JSON.parse(e.responseText);

            jQuery.each(response.errors, function (key, value) {
              jQuery('#email').parent().find('input[id="'+key+'"], input[name="'+key+'"] , select[name="'+key+'"] , textarea[name="'+key+'"]').parent().append('<div class="animated rubberBand error text-right text-danger text-mute">'+value+'</div>');

            });

            jQuery('html, body').animate({
                scrollTop: jQuery("#email").offset().top
            }, 'fast');
          }

        });
      });



      jQuery('#form_ajax').submit(function(e){
        return false;
      });

      jQuery('#myModal').modal({ show: false,
                            backdrop: 'static',
                            keyboard: false
                          });

      jQuery('#myModal').on('hidden.bs.modal', function (e) {
        window.location = "{{ Route('memoria.retrieve_booking') }}?invoice={{ $order->invoice_no }}&schedule={{ date('m/d/Y',strtotime($order->order_detail->schedule_date)) }}&phone={{$order->customer_info->phone}}";
      });

      jQuery('#paynow').click(function(){
        jQuery('.error').remove();
        jQuery('#myModal').modal('show');

        jQuery('#myModal').find('.modal-processing').css({marginTop:'5%'});
        jQuery('#myModal').find('.modal-body').addClass('padding-0');
        invoice_url = jQuery('#form_ajax').attr('action');

        if(invoice_url.indexOf('make_payment') >= 0){

          jQuery.getJSON(invoice_url, function(result){
            console.log(result);
              if(result.status == 200){
                jQuery('#myModal').find('.modal-body').html('<div class="holds-iframe"><iframe width="100%" height="500" src="'+result.data.invoice_url+'">Please wait...</iframe></div>');
              }
          });

        }
        else{
          jQuery('#myModal').find('.modal-body').html('<div class="holds-iframe"><iframe width="100%" height="500" src="'+invoice_url+'">Please wait...</iframe></div>');
        }




      });

      if(action && action == 'paynow'){
        jQuery('#paynow').trigger('click');
      }


      jQuery('#apply_voucher').click(function(){
        get_total_price();
      });


      function get_total_price(){


        jQuery.ajax({
              url: jQuery('#total_amount_column').attr('url'),
              type: "POST",
              data: jQuery('#form_ajax').serialize(),
              dataType :'json',

              success: function (response) {
                  if(response.status){
                    if(response.status == 200){

                      jQuery('#total_amount_column').html('<strong>'+response.data.currency+' '+response.data.total_amount_text+'</strong>');

                      if(response.data.total_voucher_amount){
                        total_voucher_amount = response.data.currency+' '+response.data.total_voucher_amount.toLocaleString();
                        jQuery('#total_voucher_amount_bar').html(total_voucher_amount);
                      }
                      else{
                        jQuery('#total_voucher_amount_bar').html('');
                      }

                   }
                   else{
                      Materialize.toast(response.message,4000,'amber');
                   }
                  }
                  else{
                   alert('Invalid Reponse');
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  //alert('jancl');
                  //alert(jqXHR.message);
                  response = JSON.parse(jqXHR.responseText);
                  if(response.errors){

                    jQuery.each(response.errors, function (key, value) {
                      Materialize.toast(value,4000,'red');
                    });

                  }


              }


         });
      }



    });

</script>
@endSection
