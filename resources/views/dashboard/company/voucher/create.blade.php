@extends('dashboard.company.base_layout')

@section('title', __('voucher_provider.menu.create_voucher'))

@section('additionalStyle')
  <link href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
{{--  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
  <link href="{{ asset('materialize/js/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" />
  <style type="text/css">
  .error {
    color: red !important
  }
  .modal.modal-fixed-footer{
    height: 100% !important;
  }
</style>
@endsection

@section('breadcrumb')
@endsection

@section('indicator_voucher')
  active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
  <div class="page-header" style="margin-bottom: 0;">
      <div class="page-header-content header-elements-md-inline">
          <div class="page-title">
              <h5>
                  {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('voucher_provider.create.create_new_voucher') }}
                  {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
              </h5>
          </div>

          <div class="header-elements py-0">
              <div class="breadcrumb">
                  <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                      <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                  </a>
                  <a href="{{ Route('company.voucher.index') }}" class="breadcrumb-item">
                      <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.voucher') }}
                  </a>
                  <span class="breadcrumb-item active">{{ trans('voucher_provider.create.create_new_voucher') }}</span>
              </div>
          </div>
      </div>
  </div>
</div>
<!-- /page header -->
<!-- main content -->
<div class="content pt-0" dashboard>
  
  <!-- Gamification -->
  <div data-template="gamification-modal">@include('dashboard.company.gamification-modal')</div>
  <!-- /gamification -->
  <!-- KYC-Gamification -->
  <div data-template="kyc-gamification">@include('dashboard.company.kyc-gamification')</div>
  <!-- /kyc-gamification -->
  <!-- Banner Sugestion -->
  <div data-template="banner-sugetion"></div>
  <!-- /banner Sugestion -->
  <div data-template="widget">

    <form id="form_ajax" method="POST" id="form_ajax" method="POST" action="{{ Route('company.voucher.store') }}">
      {{ csrf_field() }}
      <input name="voucher_type" id="voucher_type" value="1" type="hidden"/>
      <input name="id_company" value="{{ $company }}" type="hidden"/>

      <div class="widget card">
        <div class="widget-header">
          <h3>{{ trans('voucher_provider.create.voucher_inforation') }}</h3>
        </div>

        <div class="widget-content">
          <div class="form-group-no-margin">
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="voucher_code">{{ trans('voucher_provider.create.voucher_code') }} <span class="text-danger">*</span></label>
                  <input name="voucher_code" class="form-control" type="text" value="" maxlength="15">
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="currency">{{ trans('voucher_provider.create.voucher_currency') }} <span class="text-danger">*</span></label>
                  <select name="currency" class="form-control">
                    <option value='' disabled>{{ trans('voucher_provider.create.voucher_currency_empty') }}</option>
                    @foreach($list_currency as $key=>$row)
                    <option value="{{ $key }}">{{ $row }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="voucher_amount_type">{{ trans('voucher_provider.create.voucher_ammount_type') }} <span class="text-danger">*</span></label>
                  <select name="voucher_amount_type" class="form-control">
                    <option value='' disabled>{{ trans('voucher_provider.create.voucher_ammount_type_empty') }}</option>
                    <option value='0'>{{ trans('voucher_provider.create.fix_amount') }}</option>
                    <option value='1'>{{ trans('voucher_provider.create.percentage') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="voucher_amount">{{ trans('voucher_provider.create.voucher_ammount') }} <span class="text-danger">*</span></label>
                  <input type="text" name="voucher_amount" class="right-align form-control number format-money" value="" maxlength="10"/>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="widget-content">
          <div class="form-group-no-margin">
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="min_people">{{ trans('voucher_provider.create.min_people') }} <span class="text-danger">*</span></label>
                  <input type="number" name="min_people" class="right-align form-control" value="1"/>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="max_people">{{ trans('voucher_provider.create.max_people') }}</label>
                  <input type="number" name="max_people" class="right-align form-control" value="" min="1"/>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="valid_start_date">{{ trans('voucher_provider.create.valid_start_date') }} <span class="text-danger">*</span></label>
                  <input type="text" name="valid_start_date" class="datepicker form-control" value="" />
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="valid_end_date">{{ trans('voucher_provider.create.valid_end_date') }}</label>
                  <input type="text" name="valid_end_date" class="datepicker form-control" value=""/>
                </div>
              </div>
            </div>
          </div>  
        </div>

        <div class="widget-content">
          <div class="form-group-no-margin">
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="minimum_amount">{{ trans('voucher_provider.create.minimum_transaction_ammount') }} <span class="text-danger">*</span></label>
                  <input type="text" name="minimum_amount" class="right-align form-control number format-money" value="" maxlength="10"/>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="max_use">{{ trans('voucher_provider.create.max_use') }} <span class="text-danger">*</span></label>
                  <input type="number" name="max_use" class="right-align form-control max-3" value="" min="1"/>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="status">{{ trans('voucher_provider.create.voucher_status') }} <span class="text-danger">*</span></label>
                  <select name="status" class="form-control">
                    <option value="1">{{ trans('voucher_provider.create.active') }}</option>
                    <option value="0">{{ trans('voucher_provider.create.not_active') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="products">{{ trans('voucher_provider.create.product') }}</label>
                  <select name="products[]" class="form-control select" multiple="multiple" data-fouc>
                    @foreach ($products as $product)
                      <option value="{{ $product->id_product }}">{{ $product->product_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>  
        </div>

        <div class="widget-content">
          <div class="form-group-no-margin">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="voucher_description">{{ trans('voucher_provider.create.voucher_description') }} <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="voucher_description" value="" maxlength="255">
                </div>
              </div>
            </div>
          </div>  
        </div>
      </div>

      {{-- Voucher Validity --}}
      {{-- <div class="widget">
        <div class="widget-header">
          <h3>{{ trans('voucher_provider.create.voucher_validity') }}</h3>
        </div>
        <div class="widget-content">
          <div class="form-group-no-margin">
            <div class="row">
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="valid_start_date">{{ trans('voucher_provider.create.valid_start_date') }}</label>
                  <input type="text" name="valid_start_date" class="datepicker form-control" value="" />
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="valid_end_date">{{ trans('voucher_provider.create.valid_end_date') }}</label>
                  <input type="text" name="valid_end_date" class="datepicker form-control" value=""/>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="start_date">{{ trans('voucher_provider.create.schedule_start_date') }}</label>
                  <input type="text" name="start_date" class="datepicker form-control" value=""/>
                </div>
              </div>
              <div class="col-sm-12 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="end_date">{{ trans('voucher_provider.create.schedule_end_date') }}</label>
                  <input type="text" name="end_date" class="datepicker form-control" value="" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}

      <div class="dashboard-cta float-right">
        <button id="btn-submit" class="btn btn-primary btn-cta" type="submit" name="action"  id="save">{{ trans('voucher_provider.create.submit') }}</button>
      </div>
    </form>
  </div>
</div>
@stop


@section('additionalScript')
  <!-- Autocomplete -->
  <script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
{{--  <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
  <script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
  <script>
  $('input[name="voucher_code"]').on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
    },
    change: function() {
      this.value = this.value.replace(/\s/g, "");
    }
  });

  $(document).ready(function(){
    form_ajax($('#form_ajax'),function(e){
      if (e.status == 200) {
      toastr.remove()
      swal({
      title: e.success,
      text: e.message,
      type: "success",
      }).then(function() {
        window.location = "{{ Route('company.voucher.index') }}";
      });
    } else {
      toastr.remove()
      swal({
      title: e.oops,
      text: e.message,
      type: "error",
      }).then(function() {
      });
    }
    });

    $('.datepicker').datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight: true,
      autoclose: true,
      startDate: new Date(),
    });

    $('input[name=valid_start_date]').datepicker('setDate', new Date());

    $(document).find('input[name=valid_start_date]').on('changeDate', function(e) {
      $('input[name=valid_end_date]').datepicker('setStartDate', new Date(e.date.valueOf()));
    })

    $("#product_category").change(function(){
      $('#product,#product_name').val('');
    });

    //Search Category
    $("#product_type").change(function(){
      $('#product,#product_name').val('');
      $.ajax({
        url:"{{ Route('category.search') }}",
        type:'GET',
        data:{product_type:$('#product_type').val()},
        dataType:'json',
        success:function(res){
          $('#product_category').html('<option value="">-- All Category --</option>');
          res = res.suggestions;
          for(i=0;i<res.length;i++){
            
            $('#product_category').append('<option value="'+res[i].data+'">'+res[i].value+'</option>');
          }
          $("#product_category").material_select();
        }
      });
    });

    //Search Product
    $("#product_name").devbridgeAutocomplete({
      onSearchStart:function(){
        $('#product').val('');
      },
      serviceUrl:"{{ Route('product.search')}}", //tell the script where to send requests
      type:'GET',
      paramName:'product_name',
      params: {
              category: function(){
                return $("#product_category").val();
              }
      },
      //callback just to show it's working
      onSelect: function (suggestion) {
        //$('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        $('#product').val(suggestion.data);
      },
      minChars:2,
      showNoSuggestionNotice: true,
      noSuggestionNotice: 'Sorry, no matching results',
    });

    $("#search_customer").devbridgeAutocomplete({
      onSearchStart:function(){
        $('#customer').val('');
      },
      serviceUrl:"{{ Route('customer.search.email') }}", //tell the script where to send requests
        type:'GET',
        //callback just to show it's working
        onSelect: function (suggestion) {
          $('#customer').val(suggestion.data);
        },
      minChars:2,
      noSuggestionNotice: 'Sorry, no matching results',
    });

  });
  </script>
@endsection
