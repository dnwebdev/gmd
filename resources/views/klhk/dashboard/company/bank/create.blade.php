@extends('klhk.dashboard.company.base_layout')

@section('title', 'Create New Bank Account')

@section('additionalStyle')
{{--  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
{{-- <link href="{{ asset('dest-operator/lib/css/chosen.min.css') }}" rel="stylesheet"> --}}
{{-- <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}"> --}}
<link href="{{ asset('klhk-asset/dest-operator/css/index_klhk.css') }}" rel="stylesheet">
<link rel="stylesheet" href=" {{ asset('js/cropper.min.css') }} ">
<style type="text/css">
  .error {
  color: red !important
  }
  /* .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #d0d0d0;
    border-radius: 2px;
    height: 48px;
  } */
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #444;
      line-height: 28px;
      margin-top: 10px;
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
    padding-bottom: 3rem;
  }

  /* Select2 height fix */
  .select2-container--default .select2-selection--single {
      height: 35px;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 50%;
      transform: translateY(-50%);
  }
</style>
@stop


@section('breadcrumb') 
@endsection

@section('indicator_bank')
  active
@endsection

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('sidebar_provider.bank_account') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <a href="{{ Route('company.bank.index') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.bank_account') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('bank_provider.create_new') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->
<!-- main content -->
<div class="content pt-0" dashboard>
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('klhk.dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- KYC-Gamification -->
    <div data-template="kyc-gamification">@include('klhk.dashboard.company.kyc-gamification')</div>
    <!-- /kyc-gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">
    <!--Form Advance-->          

        <form id="form_ajax" method="POST" action="{{ Route('company.bank.store') }}">
          {{ csrf_field() }}
          <div class="widget card" id="help">
            <div class="widget-header">
              <h3>{{ trans('bank_provider.new_bank_account') }}</h3>
            </div>
            <div class="widget-content">
              <div class="widget-form">
                  <div class="form-group">
                  <label for="bank">{{ trans('bank_provider.select_bank') }} <span class="text-danger">*</span></label>
                    <select name="bank" class="form-control select2-bank" placeholder="Select Bank">
                      <option selected disabled>Select Bank Account</option>
                        @foreach (\App\Models\CompanyBank::$banks as $key => $value)
                            <option value="{{ $key }}">
                              {{ $value }}
                            </option>
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="bank_account_name">{{ trans('bank_provider.account_name') }} <span class="text-danger">*</span></label>
                    <input name="bank_account_name" type="text" class="form-control" maxlength="50"/>
                  </div>
                  <div class="form-group">
                    <label for="bank_account_number">{{ trans('bank_provider.account_number') }} <span class="text-danger">*</span></label>
                    <input name="bank_account_number" type="text" class="form-control number" autocomplete="off" maxlength="25"/>
                    <span class="error" data-translate="{{ trans('bank_provider.min_10') }}"></span>
                  </div>
                  <div class="form-group">
                    <label for="status">{{ trans('bank_provider.account_status') }} <span class="text-danger">*</span></label>
                    <select name="status" class="form-control" disabled>
                      <option value="1">Active</option>
                      <option value="0">InActive</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="bank_account_document">{{ trans('bank_provider.bank_document') }}</label>
                    <div class="tool-tip text-center">
                      <label><span class="fa fa-info-circle"></span> {{ trans('bank_provider.new_update_document') }} <br>
                      {{-- <input type="file" name="bank_account_document" id="input-file-now" data-default-file="" /> --}}
                    </div>
                    <div class="col-12 text-center">
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
                            @if (!empty($company->bank->bank_account_document))
                                <img src="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}" alt="">
                            @endif
                        </div>
                        {{-- <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input cropping-image" data-allowed-file-extensions="jpg jpeg png"
                                @if($company->bank)
                                data-default-file="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}"
                                @endif
                                data-array="false"
                                data-id="2"
                                data-type="bank_account_document"
                                >
                                <label for="" aria-readonly="true" class="name-result custom-file-label overflow-hidden label-data-name-file-2">{{ trans('product_provider.file_name') }}</label>
                            </div>
                        </div>
                        <div class="result-2 file-result text-center bank-image">
                            @if (!empty($company->bank->bank_account_document))
                                <img src="{{ asset('uploads/bank_document/'.$company->bank->bank_account_document) }}" alt="">
                            @endif
                        </div>
                        <div>
                            <span class="error result-error-crop-image-2 display-none">{{ trans('product_provider.max_file_2mb') }}</span>
                        </div> --}}
                      <br>
                      {{-- <span class="error display-none" id="max-image-size">{{ trans('product_provider.max_file_2mb') }}</span> --}}
                    </div>
                  </div>

                  {{-- @include('dashboard.company.product.modal_cropping_image') --}}
                  {{-- @include('dashboard.company.product.modal_cropping_image') --}}

                  {{-- MODAL CROPING IMAGE --}}

                  {{-- <button type="button" class="btn btn-primary display-none" data-toggle="modal" 
                    data-target="#croppingModalBank" class="d-none" id="launchCropModal" data-backdrop="static" 
                    data-keyboard="false"></button>
                          
                  <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalCropImage"
                  aria-hidden="true" id="croppingModalBank">
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title">Cropping Image</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                              <div class="modal-body">
                                  <canvas id="canvas">
                                      Your browser not support canvas tag
                                  </canvas>
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-success btn-crop-image" data-dismiss="modal">
                                      Crop
                                  </button>
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    {{ trans('offline_invoice.page-2.cancel') }}
                                  </button>
                              </div>
                          </div>
                      </div>
                  </div> --}}
              </div>
            </div>
          </div>
          <div class="padding-button-submit float-right">
            <button id="btn-submit" class="btn btn-primary btn-cta float-right" type="submit" name="action">{{ trans('bank_provider.save') }}</button>
          </div>
        </form>
    </div>
</div>
    

@endsection

@section('additionalScript')
  <!-- Plugin -->
<script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/jquery.autocomplete.min.js') }}"></script>
{{--<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
{{-- <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.3/cropper.js"></script>

<!-- Custom  -->
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('dest-operator/js/operator.js') }}"></script>

<script type="text/javascript">

window.$ = jQuery;
croperProductImage();

$(document).ready(function(){
	// cropingImageBank();
  // jQuery("#account").inputmask({
  //   'alias': 'decimal',
  //   rightAlign: false,
  //   'autoGroup': true,
  //   allowPlus: false,
  //   allowMinus: false,
  // });
  $('.select2-bank').select2({
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
        window.location.href = "{{ Route('company.bank.index') }}"
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
  // Not necessary
  // var canvas = jQuery('#canvas'),
  //     context = canvas.get(0).getContext('2d'),
  //     $result = jQuery('#result-bank-crop'),
  //     modalCropper = $('#croppingModalBank');
  function cropingImageBank() {
    // let canvas = $('')
    jQuery(document).on('change', 'input[name="bank_account_document"]', function () {
      if (this.files[0].size >= 2048000) {
        $('#max-image-size').show();
        return false;
      } else {
        $('#max-image-size').hide();
        if (this.files && this.files[0]) {
          let fileName = this.files[0].name;
          $('#custom-file-label').text(fileName);
          if (this.files[0].type.match(/^image\//)) {
            canvas.cropper('destroy');
            let fileReader = new FileReader();
            fileReader.onload = function (e) {
              let img = new Image();
              $(document).on('shown.bs.modal', '#croppingModalBank', function () {
                var cropper = canvas.cropper({
                  aspectRatio : 16/9,
                  viewMode : 1
                });
              });
              img.onload = function () {
                jQuery('#launchCropModal').click();
                context.canvas.height = img.height;
                context.canvas.width = img.width;
                context.drawImage(img, 0, 0);
                
              }
              img.src = e.target.result;
            } 
            fileReader.readAsDataURL(this.files[0]);
          }
        }
      }
    });
  }

  

  var imageSave = {};

  $('.btn-crop-image').click(function () {
    let croppedImageDataURL = canvas.cropper('getCroppedCanvas');
    croppedImageDataURL.toBlob(function (blob) {
        imageSave[id] = blob;
    });
    $result.html($('<img>').attr('src', croppedImageDataURL.toDataURL('image/png')));
  });

  // $('')
  
  // BCA Must be more than 10
  $(document).on('change', 'input[name=bank_account_number]', function(){
    var this_value = $(this).val();
    var bank = $('select[name=bank]').val();
    var error_label = jQuery(this).next('.error');
    if(bank === 'BCA' && this_value.length < 10){
      error_label.text('');
      error_label.text(error_label.attr('data-translate'))
    } else {
      error_label.text('');
    }
  })
});
</script>

@endsection