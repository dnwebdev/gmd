@extends('dashboard.company.base_layout')

@section('title', 'Edit Bank Account')

@section('additionalStyle')
{{--  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
{{--<link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">--}}
<style type="text/css">
  .error {
  color: red !important
  }
  .select2-container--default .select2-selection--single {
    background-color: #fff;
    border-bottom: 1px solid #d0d0d0;
    height: 48px;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #444;
      line-height: 28px;
      /* margin-top: 10px; */
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 26px;
    position: absolute;
    top: 12px;
    right: 1px;
    width: 20px;
  }

  .tool-tip label{
    margin-top: 20px;
    font-size: 13px;
  }
  .tool-tip span{
    font-size: 13px;
  }
  .padding-button-submit{
    padding-bottom: 2rem;
  }

  /* Select2 height fix */
  .select2-container--default .select2-selection--single {
      height: 35px;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 50%;
      transform: translateY(-50%);
  }

  @media screen and (max-width: 576px){
    .swal2-container {
      width: 100vw;
    }
  }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('breadcrumb')
@endsection

@section('indicator_bank')
  active
@stop

@section('content')
<!--Form Advance-->
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('bank_provider.details') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <a href="{{ Route('company.bank.index') }}" class="breadcrumb-item">
                        {{ trans('sidebar_provider.bank_account') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('bank_provider.details') }}</span>
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
    {{-- <div data-template="banner-sugetion">@include('klhk.dashboard.company.add-on.banner-sugetion')</div> --}}
    <!-- /banner Sugestion -->
    <div data-template="widget">

          {{-- <button id="deleteBank" class="btn btn-primary btn-cta" data-id="{{ $bank->id }}" data-token="{{ csrf_token() }}" url="{{ Route('company.bank.delete',$bank->id) }}" >Delete</button> --}}
      <form id="form_ajax" method="POST" action="{{ route('company.bank.update',$bank->id) }}">
      {{ csrf_field() }}
      {{ method_field('PUT') }}
      
        @if (auth()->user()->company->bank && auth()->user()->company->bank->checkRequest)
            <div class="alert alert-info text-center" role="alert">
                <strong>{{ trans('bank_provider.announcement') }}</strong><br>
                {{ trans('bank_provider.change_account') }}
            </div>
        @endif

        <div class="widget card" id="help">
          <div class="widget-header">
            <h3>{{ trans('bank_provider.bank_acount_details') }}</h3>
          </div>
          <div class="widget-content">
            <div class="widget-form">
                <div class="form-group">
                  <label for="bank">{{ trans('bank_provider.select_bank') }} <span class="text-danger">*</span></label>
                  <select name="bank" class="form-control select2-bank" placeholder="Select Bank">
                    @foreach (\App\Models\CompanyBank::$banks as $key => $value)
                      <option value="{{ $key }}" {{ $bank->bank == $key ? 'selected' : '' }}>
                        {{ $value }}
                      </option>
                    @endforeach
                  </select>
                  </div>
                <div class="form-group">
                  <label for="bank_account_name">{{ trans('bank_provider.account_name') }} <span class="text-danger">*</span></label>
                  <input name="bank_account_name" type="text" value="{{ $bank->bank_account_name }}" class="form-control" maxlength="50"/>
                </div>
                <div class="form-group">
                  <label for="bank_account_number">{{ trans('bank_provider.account_number') }} <span class="text-danger">*</span></label>
                  <input name="bank_account_number" type="tel" value="{{ $bank->bank_account_number }}" class="form-control" id="account" maxlength="25"/>
                </div>
                <div class="form-group">
                  <label for="status">{{ trans('bank_provider.account_status') }} <span class="text-danger">*</span></label>
                  <select name="status" class="form-control" disabled>
                    <option value="1" {{ $bank->status == 'Active' ? 'selected' : '' }}>{{ trans('bank_provider.active') }}</option> 
                    <!-- <option value="0" {{ $bank->bank == 'InActive' ? 'selected' : '' }}>InActive</option> -->
                  </select>
                </div>
                <div class="form-group display-block">
                  <label for="bank_account_document">{{ trans('bank_provider.bank_document') }} <span class="text-danger">*</span></label><br>
                  {{-- <label for="bank_account_document">{{ $bank->bank_account_document }}</label> --}}
                  <div class="tool-tip text-center">
                    <label><span class="fa fa-info-circle"></span> {{ trans('bank_provider.new_update_document') }} <span class="text-danger">*</span></label> <br>
                    {{-- <input type="file" name="bank_account_document" id="input-file-now" value="{{ $bank->bank_account_document }}" /> --}}
                    {{-- <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input cropping-image" data-allowed-file-extensions="jpg jpeg png"
                            data-array="false"
                            data-id="2"
                            data-type="bank_account_document"
                            >
                            <label for="" aria-readonly="true" class="name-result custom-file-label overflow-hidden label-data-name-file-2">{{ trans('product_provider.file_name') }}</label>
                        </div>
                    </div>
                    <div class="result-2 file-result text-center bank-image">
                        @if (!empty($bank->bank_account_document))
                            <img src="{{ asset('uploads/bank_document/'.$bank->bank_account_document) }}" alt="">
                        @endif
                    </div>
                    <div>
                        <span class="error result-error-crop-image-2 display-none">{{ trans('product_provider.max_file_2mb') }}</span>
                    </div> --}}
                    <label class="custom-input-cropping-image">
                        <input type="file" name="bank_account_document" class="custom-file-input cropping-image" data-allowed-file-extensions="jpg jpeg png"
                        {{-- @if($company->bank)
                        data-default-file="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}"
                        @endif --}}
                        data-array="false"
                        data-id="2"
                        data-type="bank_account_document"
                        data-ratio="1.78"/>
                        <i class="fa fa-cloud-upload"></i>
                        <span>{{ trans('premium.facebook.label.upload') }}</span>
                        <br>
                    </label>
                    <div class="result-2 file-result bank-image">
                        @if (!empty($bank->bank_account_document))
                            <img src="{{ asset('uploads/bank_document/'.$bank->bank_account_document) }}" alt="">
                        @endif
                    </div>
                    {{-- <div class="text-center">
                        <button type="button" class="btn btn-danger btn-remove-image-cropper remove-crop-image-2 {{ empty($bank->bank_account_document) ? ' display-none' : '' }}" data-id="0">
                            {{ trans('product_provider.remove_image') }}
                        </button>
                    </div> --}}
                  </div>


                </div>



            </div>
            {{-- @include('klhk.dashboard.company.product.modal_cropping_image') --}}
          </div>
        </div>
        <div class="padding-button-submit float-right">
          <button id="btn-submit" class="btn btn-primary btn-cta" type="submit" name="action">{{ trans('bank_provider.save') }}</button>
          <a href="{{ Route('company.bank.index') }}" class="btn btn-danger btn-cta mr-1 float-left">{{ trans('bank_provider.cancel') }}</a>
        </div>
      </form>
    </div>
</div>

@endsection

@section('additionalScript')
  <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
{{--  <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
{{--  <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script>--}}
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.2.6/jquery.inputmask.bundle.min.js"></script> --}}
  <script>
    croperProductImage();
  jQuery(document).ready(function(){
    // jQuery("#account").inputmask({
    //   'alias': 'decimal',
    //   rightAlign: false,
    //   'autoGroup': true,
    //   allowPlus: false,
    //   allowMinus: false,
    // });
    jQuery('.select2-bank').select2({
      placeholder: "Select Bank",
      allowClear: false
    });
    form_ajax(jQuery('#form_ajax'),function(e){
      if (e.status == 200) {
        toastr.remove()
        swal({
        title: e.success,
        text: e.message,
        type: "success",
        }).then(function() {
          window.location.href = "{{ Route('company.bank.index') }}" ;
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
  });
  </script>
  <script>

jQuery("#deleteBank").click(function(){
    var id = jQuery(this).data("id");
    var token = jQuery(this).data('token')
    jQuery.ajax(
    {
        url:jQuery("#deleteBank").attr('url'),
        type: 'DELETE',
        dataType: "JSON",
        data: {
            "id": id,
            "_method": 'DELETE',
            "_token": token,
        },
        success: function (response)
        {
            if (response.status)
            {

                if (response.status == 200) {
                  toastr.remove()
                  swal({
                        title: "Success",
                        text: response.message,
                        type: "success",
                        }).then(function() {
                          window.location.href = "{{ Route('company.bank.index') }}" ;
                      });
                }
                else {
                  toastr.remove()
                  swal({
                        title: "{{trans('general.whoops')}}",
                        text: response.message,
                        type: "error",
                        }).then(function() {
                      });
                }
            }
            else {
              toastr.remove()
              swal({
                        title: "{{trans('general.whoops')}}",
                        text: response.message,
                        type: "error",
                        }).then(function() {
                      });
      }

        }
    });
    console.log("tr",rowCount);
});
</script>
@endsection
