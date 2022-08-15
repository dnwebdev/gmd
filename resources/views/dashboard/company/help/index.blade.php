@extends('dashboard.company.base_layout')
@section('additionalStyle')
<link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
{{-- <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
<link rel="stylesheet" href="{{asset('css/whatsapp-help.css')}}">
@endsection

@section('title', __('sidebar_provider.help_and_feedback'))

@section('breadcrumb')
@endsection

@section('indicator_order')
  active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('sidebar_provider.help_and_feedback') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('sidebar_provider.help_and_feedback') }}</span>
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

    <div class="widget card" id="whatsapp">
      <div class="row">
        {{-- <a class="widget-content col-xl-4" onclick="introStartBtn()">
          <div class="container">
            <div class="row">
              <div class="col-auto">
                  <img class="whatsapp" src="{{ asset('dest-operator/img/bulb.png') }}">
              </div>
              <div class="col">
                <h5 class="text-info">{{ trans('help_provider.help_introjs_capt') }}</h5>
                <p>{{ trans('help_provider.help_introjs_desc') }}</p>
              </div>
            </div>
          </div>
        </a> --}}
        <a href="https://api.whatsapp.com/send?phone=6281211119655" target="_blank" class="widget-content col-xl-6 whatsapp-widget-help">
            <div class="container">
              <div class="row">
                <div class="col-auto">
                    <img class="whatsapp" src="{{ asset('dest-operator/img/whatsapp.png') }}">
                </div>
                <div class="col">
                  <h5 class="text-info">{{ trans('help_provider.help_whatsapp_capt') }}</h5>
                  <p>{{ trans('help_provider.help_whatsapp_desc') }}</p>
                </div>
              </div>
            </div>
        </a>
        <a href="https://medium.com/gomodo" target="_blank" class="widget-content col-xl-6">
            <div class="container">
              <div class="row">
                <div class="col-auto">
                    <img class="whatsapp" src="{{ asset('dest-operator/img/medium.png') }}">
                </div>
                <div class="col">
                  <h5 class="text-info">{{ trans('help_provider.help_medium_capt') }}</h5>
                  <p>{{ trans('help_provider.help_medium_desc') }}</p>
                </div>
              </div>
            </div>
        </a>
      </div>
    </div>

    <div class="widget card" id="help">
      <div class="widget-header">
        <h3>{{ trans('help_provider.contact_gomodo_team') }}</h3>
      </div>
      <div class="widget-content">
        <div class="widget-form">
          <form id="form_ajax" method="POST" action="{{ Route('company.help.store') }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label for="title">{{ trans('help_provider.title') }} <span class="text-danger">*</span></label>
              <input type="text" id="title" name="title" placeholder="{{ trans('help_provider.your_needs_or_feedback') }}..." class="form-control" maxlength="100"/>
            </div>
            <div class="form-group">
              <label for="message">{{ trans('help_provider.message') }} <span class="text-danger">*</span></label>
              <textarea id="message" name="message" class="form-control itin_input" rows="4" placeholder="{{ trans('help_provider.tell_the_details') }}..."></textarea>
            </div>
            <div class="form-group">
              <label for="screenshot">{{ trans('help_provider.screenshot') }}</label>
              <div>
                <input type="file" name="screenshot" id="input-file-now" class="dropify"
                accept="image/x-png,image/jpg,image/jpeg"
                data-allowed-file-extensions="jpg jpeg png"
                data-max-file-size="2M"/>
              </div>
            </div>
            <div class="text-right">
              <button id="btn-submit" type="button" class="btn btn-submit btn-primary no-margin">{{ trans('help_provider.submit') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@section('additionalScript')
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script type="text/javascript" src="{{ asset('materialize/js/plugins/dropify/js/dropify.min.js') }}"></script>
{{--<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
<script src="{{ asset('materialize/js/plugins/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('materialize/js/plugins/tinymce/tinymce-charactercount.plugin.js') }}"></script>
<script>
  tinymce.init({
    selector: '.itin_input',
    menubar: false,
    content_style: "p {font-size: 1rem; }",
    plugins: ['charactercount','paste'],
    paste_as_text: true,
    elementpath: false,
    max_chars: 1000, // max. allowed chars
    setup: function (ed) {
        var allowedKeys = [8, 37, 38, 39, 40, 46]; // backspace, delete and cursor keys
        ed.on('keydown', function (e) {
            if (allowedKeys.indexOf(e.keyCode) != -1) return true;
            if (tinymce_getContentLength() + 1 > this.settings.max_chars) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            return true;
        });
        ed.on('keyup', function (e) {
            tinymce_updateCharCounter(this, tinymce_getContentLength());
        });
    },
    init_instance_callback: function () { // initialize counter div
        $('#' + this.id).prev().append('<div class="char_count" style="text-align:right"></div>');
        tinymce_updateCharCounter(this, tinymce_getContentLength());
    },
    paste_preprocess: function (plugin, args) {
        var editor = tinymce.get(tinymce.activeEditor.id);
        var len = editor.contentDocument.body.innerText.length;
        var OriginalString = args.content;
        var text = OriginalString.replace(/(<([^>]+)>)/ig,"");
        if (len + text.length > editor.settings.max_chars) {
            alert('Pasting this exceeds the maximum allowed number of ' + editor.settings.max_chars + ' characters.');
            args.content = text.slice(0, editor.settings.max_chars);
        } else {
            tinymce_updateCharCounter(editor, len + text.length);
        }
    }
  });

  function tinymce_updateCharCounter(el, len) {
      // $('#' + el.id).prev().find('.char_count').text(len + '/' + el.settings.max_chars);
      $('#' + el.id).prev().find('.char_count').text('{!! trans('dashboard_provider.max_char') !!} ' + el.settings.max_chars);
  }
  function tinymce_getContentLength() {
      return tinymce.get(tinymce.activeEditor.id).contentDocument.body.innerText.length;
  }

  $(document).ready(function(){
    $(document).on('click','#btn-submit', function () {
      console.log('click')
      tinymce.triggerSave(true,true);
      $(this).closest('form').submit();

    })
    form_ajax($('#form_ajax'),function(e){
      if (e.status == 200) {
        // Empty value
        $('input[type=text]').val('');
        tinyMCE.activeEditor.setContent('');
        toastr.remove()
        swal({
          title: "Success",
          text: e.message,
          type: "success",
        }).then(function() {
          location.reload()
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
    $('.dropify').dropify({
        messages: {
            'default': "{{ trans('kyc.default1') }} <br><span style='font-size:.8rem'>{{ trans('kyc.default2') }}</span>",
            'error':   "{{ trans('kyc.error') }}"
        }
    });
  });
</script>
@endsection
