@extends('dashboard.company.base_layout')

@section('title', __('sidebar_provider.product'))

@section('additionalStyle')
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
  <style>
    .dataTables_filter {
        display: block;
    }
    .filter-select, .filter-select:focus, .filter-select:active {
        border-color: rgb(243, 243, 243);
        background-color: rgb(243, 243, 243);
        color: black;
        height: 42px;
        font-weight: bold;
        outline: none;
        /* padding-left: 0; */
    }
    [type=search] {
        border: 1px solid rgb(166, 166, 166);
        height: 2rem;
        padding-left: .5rem;
    }
    select {
        height: 2rem;
    }
    /* Gomodo Embed Widget Start */
    .clipboard-generate {
      width: 100%;
    }
    textarea.clipboard-generate {
      color: #212121;
      font-family: monospace;
      resize: none;
      border: none;
      text-align: center;
      background: #ececec;
      border-radius: 10px;
      padding: 10px;
    }
    textarea.clipboard-generate:focus {
      outline: none;
    }
    /*table tr td:last-child {*/
    /*  text-align: center;*/
    /*  line-height: 2.3;*/
    /*}*/
    table .width-span {
      width: max-content;
    }
    i.fa-question {
      line-height: 1.5;
      color: #ffa900;
      border: 2px solid #ffa900;
      border-radius: 50%;
      padding: 0px;
      font-size: 14px;
      width: 20px;
      height: 20px;
    }
    table .width-span .fa-link, table .fa-external-link-square {
      position: absolute;
      left: 2px;
      top: 50%;
      font-size: 14px;
      transform: translateY(-50%);
    }
    .ota-container {
      text-align: center;
      color: #6edf78;
      width: max-content;
      font-size: 13px;
    }
    .widget .widget-table table tr td {
      padding: .75rem .5rem;
      color: rgba(0,0,0,.54);
      white-space: nowrap;
    }
    .widget .widget-table table tr td .tooltipstered {
      vertical-align: middle;
    }
    .modal#generate_widget_modal .product-name {
      color: #3f94cf;
    }
    .modal#generate_widget_modal .close {
      color: white;
      background: #ea5757;
      opacity: 1;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      padding: 0;
      margin: 0;
      font-size: 30px;
      font-weight: 100;
      position: absolute;
      right: 10px;
      top: 10px;
    }
    .modal#generate_widget_modal .close span {
        right: 9px;
        top: -1px;
        position: absolute;
    }
    .modal#generate_widget_modal .modal-content {
      border: none;
      border-radius: 10px;
    }
    .modal#generate_widget_modal .modal-header, .modal#generate_widget_modal .modal-footer {
      border: none;
    }
    .modal#generate_widget_modal .modal-header {
      padding: 1rem 3rem 1rem 1rem;
    }
    .modal#generate_widget_modal #copy-code {
      border-radius: 5px;
      text-transform: none;
      padding: 0 1rem;
      height: 80px;
      line-height: 1;
      margin-bottom: 0;
    }
    .modal#generate_widget_modal #copy-code i {
      margin-right: 5px;
    }
    .modal#generate_widget_modal .modal-body img {
      max-width: 500px;
      margin: auto;
      display: block;
      margin-bottom: 30px;
    }
    .modal#generate_widget_modal .modal-body p, .modal#generate_widget_modal .modal-body h2 {
      text-align: center;
      max-width: 600px;
      margin: 0 auto 10px;
    }

    .modal.show {
      z-index: 99999!important;
    }

    @media (min-width: 576px) {
      .modal#generate_widget_modal .modal-dialog {
          max-width: 800px;
      }
    }
    /* Gomodo Embed Widget End */
    /* custom button */
    table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
      font-family: "FontAwesome";
      content: "\f0fe";
      border: 1px solid #555;
      color: #555;
      box-shadow: none;
      box-sizing: no;
      background: #fff;
      font-size: 14px;
      top: 16px;
      height: 10px;
      width: 10px;
      line-height: auto;
      border: none;
    }
    @media screen and (min-width: 800px) {
      table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
        top: 17px;
      }
    }
    table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th:first-child:before {
      content: "\f146";
    }
    .btn-copy {
      color: #0893cf !important;
      font-weight: 400;
      padding: 0px 4px;
      font-size: .875rem;
      height: 30px;
      line-height: .7;
      margin-bottom: 0;
      background: none;

      box-shadow: none;
      border: 1px solid #0893cf;
      border-radius: 4px;
      background-color: none;
    }
    .widget-table {
      overflow-y: scroll;
    }

    .btn-product-list-unified {
      background: white!important;
      border: 1px solid #3c96f32b!important;
      border-radius: 4px!important;
      height: 30px!important;
      width: 30px!important;
      position: relative!important;
    }

    .btn-product-list-unified:hover {
      box-shadow: 0 5px 11px 0 rgba(0, 0, 0, 0.18), 0 4px 15px 0 rgba(0, 0, 0, 0.15)!important;
    }
    .btn-product-list-unified i {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: .9rem!important;
    }

    .select2-container {
      z-index: 99999!important;
    }
  </style>
  <link href="{{ url('css/component-custom-switch.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}">
@endsection

@section('breadcrumb')
@stop

@section('indicator_inventory')
    active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('sidebar_provider.product') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('sidebar_provider.product') }}</span>
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

        {{-- <div class="dashboard-cta">
            <a href="{{ URL('company/product/create') }}"
                class="btn btn-primary btn-cta">{{ trans('product_provider.new_product') }}</a>
        </div> --}}
        <div class="widget card" id="product">
            <div class="widget-header">
                <div class="list-caption d-flex justify-content-between w-100"><h3>{{ trans('product_provider.list_of_product') }}</h3>
                  <a href="{{ URL('company/product/create') }}" class="btn btn-primary btn-create"><i class="icon-plus2 mr-1"></i>{{ trans('product_provider.new_product') }}</a>
                </div>
            </div>
            <div id="copy-unique"></div>
            <div class="widget-content widget-full">
                <!-- Table Edit START -->
                <div class="widget-table">
                    <select class="form-control mt-2 d-block d-sm-none" name="category"
                            id="type">
                        <option value="" disabled selected
                                hidden>{{ trans('product_provider.type') }}</option>
                        <option value="">{{ trans('general.all') }}</option>
                        @foreach(\App\Models\ProductType::all() as $type)
                            <option value="{{$type->id_tipe_product}}">{{$type->product_type_name}}</option>
                        @endforeach
                    </select>
                    <select class="form-control d-block d-sm-none" name="category"
                            id="status">
                        <option value="" disabled selected
                                hidden>{{ trans('product_provider.status') }}</option>
                        <option value="">{{ trans('general.all') }}</option>
                        <option value="1">{{ trans('general.active') }}</option>
                        <option value="0"> {{ trans('general.in_active') }}</option>
                    </select>

                    <div class="responsive-table">
                        <table id="product_list_table" class="table-border-bottom tablesorter display w-100">
                            <thead>
                            <tr>
                                <th class="all">{{ trans('product_provider.name') }}</th>
                                <th class="min-desktop">{{ trans('product_provider.code_product') }}</th>
                                <th class="min-desktop">
                                    <select class="form-control filter-select col d-none d-md-block type" name="category">
                                        <option value="" disabled selected
                                                hidden>{{ trans('product_provider.type') }}</option>
                                        <option value="">{{ trans('general.all') }}</option>
                                        @foreach(\App\Models\ProductType::all() as $type)
                                            <option value="{{$type->id_tipe_product}}">{{$type->product_type_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="d-inline d-sm-none">{{ trans('product_provider.type') }}</span>
                                </th>
                                <th class="min-desktop">{{ trans('product_provider.location') }}</th>
                                <th class="min-tablet">
                                    <select class="form-control filter-select col d-none d-md-block status" name="category">
                                        <option value="" disabled selected
                                                hidden>{{ trans('product_provider.status') }}</option>
                                        <option value="">{{ trans('general.all') }}</option>
                                        <option value="1">{{ trans('general.active') }}</option> 
                                        <option value="0">{{ trans('general.in_active') }}</option>
                                    </select>
                                    <span class="d-inline d-sm-none">{{ trans('product_provider.status') }}</span>
                                </th>
                                <th class="min-desktop">{{trans('product_provider.booked')}}</th>
                                @if ($haveOta)
                                  <th class="none">{{ trans('product_provider.distribution') }}
                                    {{-- <span class="tooltips" title="{{ trans('product_provider.short_description') }}">
                                      <i class="fa fa-question"></i>
                                    </span> --}}
                                  </th>
                                @endif
                                <th class="none">{{trans('general.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Table Edit END -->
            </div>
        </div>
    @include('dashboard.company.product.ota_modal')
    @include('dashboard.company.product.export_excel_modal')
    @include('dashboard.company.product.order_date')
  </div>
</div>
    <!-- Delete Product Modal -->
    <div class="modal fade" id="modal-delete-product" tabindex="-1" role="dialog" aria-labelledby="DeleteProductModal" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="DeleteProductModal">{{trans('general.delete_confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            {!! Form::open() !!}
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {{trans('general.are_you_sure_wan_to_delete')}}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-close"
                            data-dismiss="modal">{{trans('general.close')}}</button>
                    <button type="button" id="btn-do-delete"
                            class="btn btn-success">{{trans('general.delete')}}</button>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
    <!-- Generate Widget Modal  -->
    <div class="modal fade" id="generate_widget_modal" tabindex="-1" role="dialog" aria-labelledby="GenerateWidgetModal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="GenerateWidgetModal">{{ trans('product_provider.modal_title') }} <a class="product-link" target="_blank"><span class="product-name"></span></a></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <img src="{{ asset('img/product_to_website.svg')}}" alt="Generate Widget" />
              <h2>{{ trans('product_provider.widget_title') }}</h2>
              <p>{!! trans('product_provider.widget_desc') !!}</p>
          </div>
          <div class="modal-footer">
            <textarea name="" id="" cols="30" rows="4" class="clipboard-generate d-block" readonly>
              
            </textarea>
            {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
            <button type="button" id="copy-code" class="btn bg-primary"><i class="fa fa-copy"></i>{{ trans('product_provider.copy') }}</button>
          </div>
        </div>
      </div>
    </div>
    {{-- Tooltip --}}
    <div class="tooltip_templates d-none">
      <span id="tooltipWidgetContent" class="d-block text-center">
        {{ trans('product_provider.widget_button_tooltip') }} <a href="{{ url('/widget-faq') }}" class="text-primary" target="_blank">{{ trans('product_provider.widget_button_tooltip_link') }}</a>
      </span>
      <span id="tooltipDuplicateContent" class="d-block text-center">
        {{ trans('general.duplicate') }}
      </span>
      <span id="tooltipExportContent" class="d-block text-center">
        {{ trans('product_provider.export_order.modal.title') }}
      </span>
      <span id="tooltipOrderDataContent" class="d-block text-center">
        {{ trans('product_provider.order_date_filter.modal.title') }}
      </span>
      <span id="tooltipDeleteContent" class="d-block text-center">
        {{ trans('general.delete') }}
      </span>
    </div>
@stop

@section('additionalScript')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('css/dataTablesEdit.css')}}">
    {{--<script type="text/javascript" src="{{ asset('js/indexjs.js') }}"></script>--}}
    {{--<script src="{{ asset('dest-operator/lib/js/jquery-slim.min.js') }}"></script>--}}
    {{-- <script src="{{ asset('dest-operator/lib/js/bootstrap.min.js') }}"></script> --}}
    {{--<script src="{{ asset('dest-operator/js/operator.js') }}"></script>--}}
    {{--<script src="{{ asset('dest-operator/lib/js/jquery.tablesorter.min.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script>
      window.$ = window.jQuery;
      let language_url = '{{ asset("json/datatables_english.json") }}';
      @if(app()->getLocale()=='id')
          language_url = '{{ asset("json/datatables_indonesia.json") }}';

              @endif
      let dt = $('#product_list_table').dataTable(
          {
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "responsive": true,
            "language": {
              "url": language_url
            },
            "ajax": {
              url: "{{URL::current()}}",
              type: "GET",
              data: function (d) {
                d.status = $('select#status').val();
                d.type = $('select#type').val()
              }
            },
            "columns": [
              {
                "data": "product_name"
              },
              {
                "data": "unique_code", "orderable": false, "searchable": true
              },
              {
                "data": "product_type.product_type_name", "orderable": false, "searchable": false
              },
              {
                "data": "city", "orderable": false, "searchable": false, render: function (data) {
                  if (data) {
                    return data.city_name
                  }
                  return '-';
                }
              },
              {
                "data": "status", 'orderable': false, "searchable": false,
                "className": 'data-table-status',
                render: function(data, type) {
                    if (type === 'display') {
                        let status_class = '', data_table = '';

                        switch(data) {
                            case "1" : 
                                data_table = '{{ trans("general.active") }}';
                                status_class = 'active';
                                break;
                            default : 
                                data_table = '{{ trans("general.in_active") }}';
                                status_class = 'not_active';
                        }
                        return '<span class="badge ' + status_class + '">' + data_table + '</span>'
                    }
                    return data
                }
              },
              {
                "data":"booked","orderable":false,"searchable":false
              },
              @if ($haveOta)
                {
                  "data": "ota_count", "orderable": false, "searchable": false
                },
              @endif
              {
                "data": 'action', "orderable": false, "searchable": false
              }
            ],
            order: [[1, "asc"]],
            // init tooltip after dataTable loaded
            "initComplete": function(settings, json) {
              StartTooltipster();
              hideColumn();
            }
          }
      );

      // $('#product_list_table').on( 'draw.dt', function () {
      //   StartTooltipster();
      // });
      dt.on('responsive-display.dt', function () {
         StartTooltipster();
      });
      $(document).on('keyup search input paste cut', '#searchbox', function () {
        dt.fnFilter(this.value);
      });
      $(document).on('change', '#status, #type', function () {
        dt.api().ajax.reload(null, false)
      });
      $(document).on('click', '.btn-delete, .delete', function () {
        let t = $(this);
        let modal = $('#modal-delete-product'); 
        modal.find('input[name=id]').val(t.data('id'));
        // modal.modal();
      })
      $(document).on('click', '#btn-do-delete', function () {
        let modal = $(document).find('#modal-delete-product');
        $.ajax({
          type: 'POST',
          url: "{{route('company.product.delete')}}",
          data: {
            id: modal.find('input[name=id]').val(),
            '_token': '{{csrf_token()}}'
          },
          success: function (data) {
            toastr.success('Success', data.message);
            dt.api().ajax.reload(null, false);
            modal.find('.btn-close').trigger('click');
          },
          error: function (e) {
            toastr.error(e.responseJSON.message,'{{__('general.whoops')}}');
            dt.api().ajax.reload(null, false);
            modal.find('.btn-close').trigger('click');
          }
        })
      });
      var product_id;
      var ota_modal = $('#ota-modal');
      var selected_ota = [];
      var approved_ota = [];
      $(document).on('click', '.btn-edit-ota', function () {
        ota_modal.modal();
        product_id = $(this).data('id');
        $('.ota-value').prop('checked', false);
        let selected = $(this).data('selected').toString();
        if (!!selected) {
          $.each(selected.split(','), function(index, value) {
            $('#ota-'+value).prop('checked', true);
          });
        }
        let approved = $(this).data('approved').toString();
        let rejected = $(this).data('rejected').toString();
        $('.approved').hide();
        $('.rejected').hide();
        if (!!approved) {
          $.each(approved.split(','), function(index, value) {
            $('.approved-' + value).show();
          });
        }
        if (!!rejected) {
          $.each(rejected.split(','), function(index, value) {
            $('.rejected-' + value).show();
          });
        }
      });
      $(document).on('click', '#ota-modal #btn-submit', function () {
        $.ajax({
          type: 'POST',
          url: "{{route('company.product.update_ota')}}",
          data: {
            id: product_id,
            ota: $('.ota-value:checked').map(function() {
              return $(this).val();
            }).get(),
            '_token': '{{csrf_token()}}'
          },
          success: function (data) {
            toastr.remove();
            toastr.success('Success', data.message);
            dt.api().ajax.reload(null, false);
            // ota_modal.modal('hide')
            ota_modal.find('.btn-close').trigger('click');
          },
          error: function (e) {
            toastr.error('', e.responseJSON.message);
            dt.api().ajax.reload(null, false);
            // ota_modal.modal('hide')
            ota_modal.find('.btn-close').trigger('click');
          }
        })
      })

      // Generate Widget
      $(document).on('click', '.btn-generate', function(){
        let th = $(this);
        let modal = $('.modal#generate_widget_modal');
        let html = '<div><a class="gomodoEmbed" data-url="http{{ request()->secure() ? 's' : ''  }}://'
          html+= th.data('domain');
          html+= '/product/detail/';
          html+= th.data('id');
          html+= '"></a><script src="{{ asset("gomodo-widget.js") }}">';
          html+= '<\/script></div>'
          modal.find('textarea').val(html);
          modal.modal();
        $('.product-name').text(th.closest('tr').find('a.product_name').text());
        $('.product-link').attr('href', 'http{{ request()->secure() ? 's' : ''  }}://' + th.data('domain') + '/product/detail/' + th.data('id'))
      })
      $(document).on('click', '#copy-code', function(){
        let modal = $('.modal#generate_widget_modal');
        let t = modal.find('textarea');
        t.select();
        // copyText.setSelectionRange(0, 99999)
        document.execCommand('copy');
      })
      $(document).on('click', '#copy-code', function(){
        toastr.success('{{ trans("product_provider.copied") }}');
      });

      function detectmob() {
         if(window.innerWidth <= 800) {
           return true;
         } else {
           return false;
         }
      }

      function hideColumn() {
        dt.api().column(6).visible(!detectmob());
        @if ($haveOta)
          dt.api().column(7).visible(!detectmob());
        @endif
      }
      window.onresize = function() {
        hideColumn();
      }

      $(document).on('change', '.status', function() {
        $(document).find('#status').val($(this).val()).change();
      })

      $(document).on('change', '.type', function() {
        $(document).find('#type').val($(this).val()).change();
      })
        $(document).on('click', '.unique', function () {
            let tes = $(this).text();
            let $temp = $("<input>");
            $("#copy-unique").append($temp);
            $temp.val(tes).select();
            document.execCommand('copy');
            $temp.remove();
            toastr.success('{!! trans('product_provider.copied') !!}')
        })

      $(document).on('click', '.btn-export', function() {
        let id = $(this).data('id');
        $('#export-excel-modal input[name=id]').val(id);
      });

      $('.date-picker').datepicker('destroy').datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true,
          todayHighlight: false,
          language: '{{ app()->getLocale() }}'
      });

      $(document).on('click', '.view-order', function(){
        $('input[name=product_id]').val($(this).data('id'));
        $('#order_date').datepicker('setDate', new Date());
      });

    </script>
@stop
