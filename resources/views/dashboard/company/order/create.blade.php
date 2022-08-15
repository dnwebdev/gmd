@extends('dashboard.company.base_layout')


@section('title', 'Create New Internal Order')

@section('additionalStyle')
  <!--dropify-->
  <link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
  <link href="{{ asset('materialize/js/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
  {{--    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
  <style type="text/css">
  .error {
  color: red !important
  }
  .modal{
    max-height: 100% !important;
    height: 90% !important;
    top:5% !important;
  }
  </style>
@stop


@section('breadcrumb')
<!--breadcrumbs start-->
<div class="dashboard-header">
  <div class="container-fluid">
  @include('dashboard.company.partial.language')
    <div class="row">
      <div class="col">
        <div class="dashboard-title">
          <h1>New Internal Order</h1>
          <div class="dashboard-tools">
            <div class="breadcrumbs">
              <ul>
                <li><a href="{{ Route('company.dashboard') }}">Home</a></li>
                <li><a href="{{ Route('company.order.index') }}">Order</a></li>
                <li>New Internal Order</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('indicator_order')
  active
@endsection

@section('content')
<div class="dashboard-content">
  <div class="container-fluid">
    <div class="row">
      <form id="form_ajax" method="POST" action="{{ Route('company.order.store') }}">
      {{ csrf_field() }}
      <div class="col">
        <button class="btn text-white" type="submit" name="action">Submit</button>
        <div class="widget" id="order-guest">
          <div class="widget-header">
            <h3>Booking Details</h3>
          </div>
          <div class="widget-content">
            <div class="form-group-no-margin">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                  <div class="form-group">
                    <label for="email">Booker Email</label>
                    <input type="email" class="form-control" name="email" id="search_customer"  />
                    <input name="customer" id="customer"  type="hidden" url_search_customer="{{ Route('customer.search.email') }}">
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                  <div class="form-group">
                    <label for="first_name">Booker First Name</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" />
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                  <div class="form-group">
                    <label for="last_name">Booker Last Name</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" />
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                  <div class="form-group">
                    <label for="phone">BookerPhone</label>
                    <input type="text" class="form-control" name="phone" id="phone"  />
                  </div>
                </div>
              </div>
              <hr class="full-hr">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-4">
                  <div class="form-group">
                    <label for="email">Product</label>
                    <input type="text" class="form-control" id="products_search"  />
                    <input type="hidden" name="product" id="product"  url_search_product="{{ Route('product.search')}}"/>
                  </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                  <div class="form-group">
                    <label for="email">Total Adult</label>
                    <input type="number" class="form-control" id="adult" name="adult" />
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-3">
                  <div class="form-group">
                    <label for="email">Total Children</label>
                    <input type="number" class="form-control" id="children" name="children"/>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-2">
                  <div class="form-group">
                    <label for="schedule">Schedule</label>
                    <input type="string" name="schedule" id="schedule"  data-large-mode="true" data-large-default="true" class="datedrop form-control" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018"/>
                  </div>
                </div>
              </div>
              <hr class="full-hr">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-3">
                  <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" class="form-control" id="country_search"  />
                    <input type="hidden" name="country" id="country"  url_search_country="{{ Route('countries.search')}}"/>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-3">
                  <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city_search"  />
                    <input type="hidden" name="city" id="city"  url_search_city="{{ Route('cities.search')}}"/>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="5" ></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="widget" id="order-notes">
          <div class="widget-header">
            <h3>Order Notes</h3>
          </div>
          <div class="widget-content">
            <div class="form-group-no-margin">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="country">Notes for the Customer</label>
                    <textarea name="external_notes" class="form-control" ></textarea>
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="country">Internal Notes</label>
                    <textarea name="internal_notes" class="form-control"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="widget" id="order-payment">
          <div class="widget-header">
            <h3>Transaction & Invoice</h3>
          </div>
          <div class="widget-content">
            <div class="form-group-no-margin">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="status">Transaction Status</label>
                    <select name="status" class="form-control">
                      @foreach($list_status as $key=>$row)
                      <option value="{{$key}}">{{$row}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-6">
                  <div class="form-group">
                    <label for="send_invoice">Send Invoice?</label>
                    <select name="send_invoice" class="form-control">
                      <option value="no">No</option>
                      <option value="yes">Yes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('additionalScript')
<!-- Plugin -->
{{--<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
<script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('dest-operator/lib/js/jquery.tablesorter.min.js') }}"></script>
<script src="{{ asset('materialize/js/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js') }}" type="text/javascript"></script>

<!-- Custom  -->
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/order_company.js') }}"></script>
<script type="text/javascript" src="{{ asset('dest-operator/js/operator.js') }}"></script>

<script>
  jQuery(document).ready(function(){
    jQuery('input.datedrop').dateDropper();
    form_ajax(jQuery('#form_ajax'),function(e){
      if (e.status == 200) {
        toastr.remove()
        swal({
        title: "Success",
        text: e.message,
        type: "success",
        }).then(function() {
          window.location.href = e.data.url
        });
      } else {
        toastr.remove()
        swal({
        title: "{{trans('general.whoops')}}",
        text: e.message,
        type: "error",
        }).then(function() {
        });
      }
    });



  });
</script>
@endsection
