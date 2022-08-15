@extends('public.agents.admiria.base_layout')

@section('additionalStyle')

@endsection

@section('main_content')

<div class="divider"></div>
<div class="container">
  <div class="">
    <div class="mt-4">
      <form id="form_ajax" method="POST" action="{{ $order->payment->first() ? $order->payment->first()->invoice_url : Route('invoice.make_payment',$order->invoice_no) }}">
        {{ csrf_field() }}
        <input type="hidden" name="invoice" value="{{ $order->invoice_no }}" id="invoice" />
        <div class="col-12">

          @if($order->status == 0)
            @if($order->allow_payment)
              <div class="text-danger"><strong><i class="fa fa-info-circle"></i> You need to complete the payment to finish your order</strong></div>
            @else
              <div class="text-warning"><strong><i class="fa fa-info-circle"></i> Please wait for further information, Your order need to be checked due to availability</strong></div>
            @endif
          @elseif($order->status == 1)
          <div class="text-success"><strong><i class="fa fa-check-circle"></i> Booking is complete</strong></div>
          @endif
        </div>


        <div class="col-sm-12 col-lg-4 pull-right m-b-20">
          <div class="subheader">
              <div class="text-center">
                  <h3>Contact</h3>
              </div>
          </div>

          <div class="secondary-color mt-4 container p-b-20 box-shadow p-v-20">
            <div class="text-center">
              <img class="img-responsive2" src="{{ Session::get('company_logo') }}" />
            </div>

            <div class="row mt-4">
              <div class="col-5">Call Center : </div>
              <div class="col-7 text-right">{{ $company->phone_company }}</div>
            </div>

            <div class="row">
              <div class="col-4">Email : </div>
              <div class="col-8 text-right">{{ $company->email_company }}</div>
            </div>

            <div class="row mt-4">
              <div class="col-12">Office : </div>
              <div class="divider"></div>
              <div class="col-12">{{ $company->address_company }}</div>
              @if($company->city)
              <div class="col-12">{{ $company->city->city_name }}</div>
              @else
              <div class="col-12">Jakarta</div>
              @endif

            </div>




          </div>



        </div>



        <div class="col-sm-12 col-lg-8">

            <div class="subheader row margin-0">
                <div class="text-center">
                    <h3>Guest Information</h3>
                </div>
            </div>

            <div class="mt-4">
                <div class="row">
                    <div class="col-6">Invoice Number</div>
                    <div class="col-6 text-right"><strong>{{$order->invoice_no}}</strong></div>
                </div>

                <div class="row">
                  <div class="col-sm-12 col-lg-4 form-group">
                      <label>First Name</label>
                      <div class="form-control input materialize input">{{ $order->customer_info->first_name }}</div>

                  </div>

                  <div class="col-sm-12 col-lg-4 form-group">
                    <label>Last Name</label>
                    <div class="form-control input materialize">{{ $order->customer_info->last_name }}</div>
                  </div>

                  <div class="col-sm-12 col-lg-4 form-group">
                    <label>Country</label>
                    <div class="form-control input materialize">{{ $order->customer_info->city->state->country->country_name }}</div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12 form-group">
                      <label>Address</label>
                      <div class="form-control input materialize">{{ $order->customer_info->address }}</div>

                  </div>


                </div>


                <div class="row">
                  <div class="col-sm-12 col-lg-4 form-group">
                      <label>City</label>
                      <div class="form-control input materialize">{{ $order->customer_info->city->city_name }}</div>

                  </div>

                  <div class="col-sm-12 col-lg-4 form-group">
                    <label>Email</label>
                    <div class="form-control input materialize">{{ $order->customer_info->email }}</div>
                  </div>

                  <div class="col-sm-12 col-lg-4 form-group">
                    <label>Phone Number</label>
                    <div class="form-control input materialize">{{ $order->customer_info->phone }}</div>
                  </div>
                </div>




                <div class="row">
                  <div class="col-sm-12 form-group">
                    <label>Notes</label>
                    <div class="form-control textarea materialize">{!! $order->external_notes !!}</div>

                  </div>


                </div>

                <div class="secondary-color box-shadow">
                    <div class="row">
                      <div class="col-sm-6">
                        <img src="{{ asset('uploads/orders/'.$order->order_detail->main_image) }}" class="img-fluid"/>
                      </div>
                      <div class="col-sm-6">
                        <div class="mt-4"><strong>{{ $order->order_detail->product_name }}</strong></div>
                        <div>{{ $order->order_detail->city ? $order->order_detail->city->city_name : ''}}</div>
                        <div class="row">
                            <div class="col-6">Schedule Date</div>
                            <div class="col-6">{{ date('d M, Y',strtotime($order->order_detail->schedule_date)) }}</div>
                        </div>

                      </div>
                    </div>

                </div>

                <div class="container mt-4 m-b-20">

                    <div class="row">
                        <div class="col-6 offset-2">
                            <label>Send your invoice</label>
                            <input type="text" placeholder="Your email here..." class="form-control round third-color" name="email" id="email" />
                        </div>
                        <div class="col-2">
                            <label>&nbsp;</label>
                            <button type="button" id="send_email" class="btn btn-success round btn-block" url="{{ Route('memoria.send_invoice_mail') }}">Send</button>
                        </div>

                  </div>

                </div>

                @if($order->status == 0)
                  <div class="subheader mt-4">
                      <div class="text-center">
                          <h3>Payment Status</h3>
                      </div>
                  </div>

                  <div class="outline-box mt-4">
                    <div class="container">
                      <div class="row">
                        <div class="col-12">

                          <div class="row">
                              <div class="col-7">Amount : </div>
                              <div class="col-5 text-right">{{ $order->order_detail->currency }} {{ number_format($order->order_detail->product_total_price,0) }}</div>
                          </div>


                          @foreach($order->order_detail->tax as $row)
                            <div class="row">
                              <div class="col-7 text-right">{{ $row->tax_name }} {{ $row->tax_value}}: </div>
                              <div class="col-5 text-right">{{ $order->order_detail->currency }} {{ number_format($row->tax_amount_type ? $row->tax_amount/100*$order->order_detail->product_total_price : $row->tax_amount,0) }}</div>
                            </div>
                          @endforeach

                          @if($order->extra->first())
                            <div class="row">
                              <div class="col-7 "><strong>Extra</strong></div>
                            </div>

                            @foreach($order->extra as $row)
                              <div class="row">
                                <div class="col-7 ">{{ $row->extra_name }} : </div>
                                <div class="col-5 text-right">{{ $order->order_detail->currency }} {{ number_format(($row->amount*$row->qty),0) }}</div>
                              </div>
                            @endforeach
                          @endif

                          @if($order->order_detail->fee_amount > 0)
                          <div class="row">
                            <div class="col-7 text-right">{{ $order->order_detail->fee_name }}: </div>
                            <div class="col-5 text-right">{{ $order->order_detail->currency }} {{ number_format($order->order_detail->fee_amount,0) }}</div>
                          </div>
                          @endif

                          @if($order->order_detail->discount_amount > 0)
                          <div class="row">
                            <div class="col-7 text-right">{{ $order->order_detail->discount_name }}: </div>
                            <div class="col-5 text-right text-success">{{ $order->order_detail->currency }} {{ number_format(($order->order_detail->discount_amount_type==1) ? $order->order_detail->discount_amount/100*$pricing->amount : $order->order_detail->discount_amount,0) }}</div>
                          </div>
                          @endif

                          <div class="row mt-4">
                            <div class="col-sm-12 col-lg-6 form-group">
                              @if($order->voucher_code)
                                Voucher : {{ $order->voucher_code }}
                              @endif

                            </div>

                            <div class="col-4 col-lg-6 form-group text-right text-success" id="total_voucher_amount_bar">
                              @if($order->voucher_code)
                                {{ $order->order_detail->currency }} {{ number_format($order->voucher_amount,0) }}
                              @endif
                            </div>

                          </div>


                        </div>

                        <!--
                        <div class="col-12 col-lg-6">
                          <div class="form-group">
                            <label class="normal-font"><input type="radio" name="payment_type"> Direct Bank Transfer</label>
                          </div>
                          <div class="form-group">
                            <label class="normal-font"><input type="radio" name="payment_type"> Credit Card</label>
                          </div>


                        </div>
                        -->


                      </div>

                      <div class="row mt-4">

                        <div class="col-12 col-lg-6">
                          <div class="container">
                            <div class="row secondary-color p-v-10">
                              <div class="col-4"><strong>Total</strong></div>
                              <div class="col-8 text-right schedule_amount" id="total_amount_column" url="{{ Route('memoria.validate_schedule') }}"><strong>{{ $order->order_detail->currency }} {{ number_format($order->total_amount,0) }}</strong></div>
                            </div>
                          </div>
                        </div>

                        @if($order->status == 0 && $order->allow_payment)
                        <div class="col-12 col-lg-6">
                            <button id="paynow" type="button" class="btn select_product btn-success form-control btn-lg" data-toggle="modal" data-target=".bs-example-modal-sm">Pay Now</button>
                        </div>
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

<div id="myModal" class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-processing">
        <div class="modal-content">
            <a data-dismiss="modal" id="close-link" class="close-modal text-right pointer"><i class="fa fa-window-close-o "></i></a>

            <div class="modal-body">
                <div class="row">
                  <div class="col-5 text-center">
                    <div>
                      <img height="50" src="{{ Session::get('company_logo') }}" alt="logo" />
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


    $(document).ready(function(){
      action = get_param('action');

      $('#send_email').click(function(){
        $.ajax({

          url:$('#send_email').attr('url'),
          type: "POST",
          data: {_token:$('input[name="_token"]').val(),invoice:$('#invoice').val(),email:$('#email').val()},
          dataType :'json',
          success:function(response){
            alert(response.message);

          },
          error:function(e){

            response = JSON.parse(e.responseText);

            $.each(response.errors, function (key, value) {
              $('#email').parent().find('input[id="'+key+'"], input[name="'+key+'"] , select[name="'+key+'"] , textarea[name="'+key+'"]').parent().append('<div class="animated rubberBand error text-right text-danger text-mute">'+value+'</div>');

            });

            $('html, body').animate({
                scrollTop: $("#email").offset().top
            }, 'fast');
          }

        });
      });



      $('#form_ajax').submit(function(e){
        return false;
      });

      $('#myModal').modal({ show: false,
                            backdrop: 'static',
                            keyboard: false
                          });

      $('#myModal').on('hidden.bs.modal', function (e) {
        window.location = "{{ Route('memoria.retrieve_booking') }}?invoice={{ $order->invoice_no }}&schedule={{ date('m/d/Y',strtotime($order->order_detail->schedule_date)) }}&phone={{$order->customer_info->phone}}";
      });

      $('#paynow').click(function(){
        $('.error').remove();
        $('#myModal').modal('show');

        $('#myModal').find('.modal-processing').css({marginTop:'5%'});
        $('#myModal').find('.modal-body').addClass('padding-0');
        invoice_url = $('#form_ajax').attr('action');

        if(invoice_url.indexOf('make_payment') >= 0){

          $.getJSON(invoice_url, function(result){
            console.log(result);
              if(result.status == 200){
                $('#myModal').find('.modal-body').html('<div class="holds-iframe"><iframe width="100%" height="500" src="'+result.data.invoice_url+'">Please wait...</iframe></div>');
              }
          });

        }
        else{
          $('#myModal').find('.modal-body').html('<div class="holds-iframe"><iframe width="100%" height="500" src="'+invoice_url+'">Please wait...</iframe></div>');
        }




      });

      if(action && action == 'paynow'){
        $('#paynow').trigger('click');
      }


      $('#apply_voucher').click(function(){
        get_total_price();
      });


      function get_total_price(){


        $.ajax({
              url: $('#total_amount_column').attr('url'),
              type: "POST",
              data: $('#form_ajax').serialize(),
              dataType :'json',

              success: function (response) {
                  if(response.status){
                    if(response.status == 200){

                      $('#total_amount_column').html('<strong>'+response.data.currency+' '+response.data.total_amount_text+'</strong>');

                      if(response.data.total_voucher_amount){
                        total_voucher_amount = response.data.currency+' '+response.data.total_voucher_amount.toLocaleString();
                        $('#total_voucher_amount_bar').html(total_voucher_amount);
                      }
                      else{
                        $('#total_voucher_amount_bar').html('');
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

                    $.each(response.errors, function (key, value) {
                      Materialize.toast(value,4000,'red');
                    });

                  }


              }


         });
      }



    });

</script>
@endSection
