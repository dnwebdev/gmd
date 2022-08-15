@extends('klhk.dashboard.company.base_layout')

@section('title', __('sidebar_provider.voucher'))

@section('additionalStyle')
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
  <link rel="stylesheet" href="{{asset('css/dataTablesEdit.css')}}">
  <style>
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
  </style>
@endsection

@section('breadcrumb')
@endsection

@section('indicator_voucher')
  active
@endsection

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('sidebar_provider.voucher') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('sidebar_provider.voucher') }}</span>
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

      {{-- <div class="dashboard-cta">
        <a href="{{ Route('company.voucher.create') }}" class="btn btn-primary btn-cta">{{ trans('voucher_provider.menu.new_voucher') }}</a>
      </div> --}}

      <div class="widget card" id="product">
        <div class="widget-header">
          <div class="list-caption d-flex justify-content-between w-100">
            <h3>{{ trans('voucher_provider.menu.list_of_voucher') }}</h3>
            <a href="{{ Route('company.voucher.create') }}" class="btn btn-primary btn-create"><i class="icon-plus2 mr-1"></i>{{ trans('sidebar_provider.create_voucher') }}</a>
          </div>
        </div>
        <div class="widget-content widget-full">
          <div class="widget-table">
            <div class="responsive-table">
              <table class="table-border-bottom tablesorter" id="voucher-list">
                <thead>
                  <tr>
                    <th class="all">{{ trans('voucher_provider.menu.voucher_code') }}</th>
                    <th class="min-desktop">{{ trans('voucher_provider.menu.amount') }}</th>
                    <th class="min-desktop">{{ trans('voucher_provider.menu.minimum_transaction') }}</th>
                    <th class="min-desktop">{{ trans('voucher_provider.menu.voucher_used') }}</th>
                    <th class="min-desktop">{{ trans('voucher_provider.menu.description') }}</th>
                    <th class="min-desktop">{{ trans('voucher_provider.menu.status') }}</th>
                  </tr>
                </thead>
                <tbody>
                {{-- @if($voucher->first())
                  @foreach($voucher as $row)
                  <tr>
                    <td><a href="{{Route('company.voucher.edit',$row->id_voucher)}}">{{ $row->voucher_code }}</a></td>
                    <td class="right-align">{{ $row->voucher_amount_type == 0 ? $row->currency : '' }} {{ number_format($row->voucher_amount,0) }} {{ $row->voucher_amount_type == 1? '%' : ''}}</td>
                    <td>{{ number_format($row->minimum_amount,0) }}</td>
                    <td>{{ number_format($row->order_paid_count) }}</td>
                    <td>{{ $row->voucher_description }}</td>
                    <td>{{ $row->status }}</td>
                  </tr>
                  @endforeach
                @else
                  <tr class="text-center"><td colspan="6" class="center">{{ trans('voucher_provider.menu.no_voucher_yet') }}</td></tr>
                @endif --}}
                </tbody>
              </table>
            </div>
            {{-- <div class="pagination justify-content-end">
              <ul>
                {{$voucher->links()}}
              </ul>
            </div> --}}
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

@section('additionalScript')
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <script>
    let dt = $('#voucher-list').dataTable(
      {
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "responsive": true,
        "language": {
          "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/{{ app()->getLocale() == 'id' ? 'Indonesian' : 'English' }}.json'
        },
        "ajax": {
          url: "{{URL::current()}}",
          type: "GET"
        },
        "columns": [
          {
            "data": "voucher_code"
          },
          {
            "data": "amount", "orderable": false, "searchable": false
          },
          {
            "data": "minimum_amount", "orderable": false, "searchable": false
          },
          {
            "data": "order_paid_count", "orderable": false, "searchable": false
          },
          {
            "data": "voucher_description", "orderable": false
          },
          {
            "data": "status",'orderable': false,
            "className": 'data-table-status',
            render: function(data, type) {
                if (type === 'display') {
                    let status_class = '';

                    switch(data) {
                        case "{{ trans('general.active') }}" : 
                            status_class = 'active';
                            break;
                        case "{{ trans('general.not_active') }}" : 
                            status_class = 'not_active';
                            break;
                    }
                    return '<span class="badge ' + status_class + '">' + data + '</span>'
                }
                return data
            }
          },
        ],
        "render" : function( data , type , row ) {    
            if ( type === "display" )
            {
               // format data here
               return data; // This is a formatted data
            }    
            return data; // This is a unformatted data    
        }
      }
  );
  </script>
@endsection
