@extends('dashboard.company.base_layout')


@section('title', __('sidebar_provider.withdrawal'))
@section('additionalStyle')
<link href="{{ asset('materialize/js/plugins/dropify/css/dropify.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
<link href="{{ asset('materialize/js/plugins/sweetalert/dist/sweetalert.css') }}" type="text/css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{asset('css/dataTablesEdit.css')}}">
{{--<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
<style type="text/css">
  .daypick{
    margin-right: 15px;
  }
  .error {
    color: red !important
  }
  .autocomplete-suggestions {
    padding: 10px;
    background-color: white
  }
  .gm-style-mtc{
    display: none;
  }
  .pac-card {
    margin: 10px 10px 0 0;
    border-radius: 2px 0 0 2px;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    outline: none;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    background-color: #fff;
    font-family: Roboto;
  }
  #pac-container {
    padding-bottom: 12px;
    margin-right: 12px;
  }
  .pac-controls {
    display: inline-block;
    padding: 5px 11px;
  }
  .pac-controls label {
    font-family: Roboto;
    font-size: 13px;
    font-weight: 300;
  }
  #pac-input {
    background-color: #fff;
    position: sticky;
    z-index: 2;
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
    margin-left: 40px;
    padding: 0 11px 0 13px;
    text-overflow: ellipsis;
    width: 400px;
  }
  #pac-input:focus {
    border-color: #4d90fe;
  }
  #title {
    color: #fff;
    background-color: #4d90fe;
    font-size: 25px;
    font-weight: 500;
    padding: 6px 12px;
  }
  #target {
    width: 345px;
  }
  .btn-success.btn-sm.btn-add.modal-trigger{
    margin-top:40px;
    text-transform: uppercase;
    border-radius: 2px;
  }
  .modal-footer .btn-sm {
    border-radius: 2px;
  }
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

@section('indicator_withdraw')
  active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('withdraw_provider.withdrawal_request') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('withdraw_provider.withdrawal_request') }}</span>
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

    <form action="#" class="form-group-no-margin">
          <!-- Product Availbility -->
        <div class="widget card" id="order-guest">
          <div class="widget-header">
            <h3>{{ trans('withdraw_provider.withdrawal') }}</h3>
          </div>
          <div class="widget-content">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <div class="form-group">
                  <label for="saldo">{{ trans('withdraw_provider.saldo') }}</label>
                  <input type="text" class="form-control" disabled placeholder="IDR {{ number_format($total_balance,0) }}"/>
                </div>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
                <button type="button" class="btn btn-primary btn-sm btn-add modal-trigger" data-toggle="modal" data-target="#modalProduct">{{ trans('withdraw_provider.request_withdrawal') }}</button>
              </div>
            </div>
          </div>
        </div>
          <!-- <hr class="full-hr"> -->
        <div class="row d-none">
          <!-- start date -->
          <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="start-date">Start Date</label>
              <input type="string" name="start_date[]" id="schedule"  data-large-mode="true" data-large-default="true" class="datedrop form-control" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018" value="{{ date('m/1/Y') }}"/>
            </div>
          </div>
          <!-- end date -->
          <div class="col-sm-12 col-md-12 col-lg-3 col-xl-3">
            <div class="form-group">
              <label for="end-date">End Date</label>
              <input type="string" name="end_date[]" id="schedule"  data-large-mode="true" data-large-default="true" class="datedrop form-control" data-format="m/d/Y" data-theme="my-style" data-max-year="2025" data-min-year="2018" value="{{ date('m/t/Y') }}"/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="widget card" id="order">
              <div class="widget-header">
                <h3>{{ trans('withdraw_provider.withdrawal_history') }}</h3>
              </div>
              <div class="widget-content widget-full">
                <div class="widget-table">
                  <div class="responsive-table">
                    <table class="table-border-bottom tablesorter" id="order-table">
                      <thead>
                        <tr>
                          <th class="all">{{ trans('withdraw_provider.date') }}</th>
                          <th class="min-desktop">{{ trans('withdraw_provider.doc_number') }}</th>
                          <th class="min-desktop">{{ trans('withdraw_provider.currency') }}</th>
                          <th class="min-desktop">{{ trans('withdraw_provider.amount') }}</th>
                          <th class="min-desktop">{{ trans('withdraw_provider.status') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                       {{--  @if($d_data->first())
                          @foreach($d_data as $row)
                          <tr>
                            <td>{{ date('M d, Y',strtotime($row->created_at)) }} </td>
                            <td>{{ $row->document_no }}</td>
                            <td class="center-align">{{ $row->currency }}</td>
                            <td class="right-align">{{ number_format($row->amount,0) }}</td>
                            <td class="center-align">{{ $row->status_text }}</td>
                          </tr>
                          @endforeach
                        @else
                          <tr><td colspan="10" class="center">-- No Transaction Yet --</td></tr>
                        @endif --}}
                      
                      </tbody>
                    </table>
                  </div>
                  {{-- <div class="pagination justify-content-end">
                    <ul>
                    {{$d_data->links()}}
                    </ul>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div id="search_result">
    <form class="form-group-no-margin" id="form_ajax" method="POST" action="{{ Route('company.withdraw.store') }}">
    {{ csrf_field() }}
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content col">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-wid">
                  <label for="total-balance">{{ trans('withdraw_provider.your_balance') }}</label>
                  <input type="number" class="form-control" id="total-balance" name="total-balance" disabled placeholder="IDR {{ number_format($total_balance,0) }}"/>
                </div>
              </div>
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="form-wid">
                  <label for="amount">{{ trans('withdraw_provider.request_amount') }}</label>
                  <input type="text" class="form-control number format-money" name="amount" id="amount" maxlength="10"/> 
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="cancel">{{ trans('withdraw_provider.cancel') }}</button>
            <button class="modal-trigger btn btn-sm btn-primary" >{{ trans('withdraw_provider.request_now') }}</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>


@endsection

@section('additionalScript')
<!-- Form Ajax -->
{{--<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}
<script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<script>
  jQuery(document).ready(function(){ 
   jQuery('input.datedrop').dateDropper();
    form_ajax(jQuery('#form_ajax'),function(e){
      if (e.status == 200) {
        toastr.remove()
        swal({
        title: '{{ trans("general.success_withdrawal") }}',
        text: e.message,
        type: "success",
        }).then(function() {
          window.location = '{{ Route("company.withdraw.index") }}';
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

  // Empty value when cancel clicked
  jQuery('#cancel').on('click', function(){
    jQuery('#amount').val('');
  });

  let dt = $('#order-table').dataTable(
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
          "data": "created_at",
          "type": "num",
          render: {
            _: 'display',
            sort: 'timestamp'
          }
        },
        {
          "data": "document_no"
        },
        {
          "data": "currency", "orderable": false
        },
        {
          "data": "amount",
          render: $.fn.dataTable.render.number( ',', '.', 0)
        },
        {
          "data": "status_text"
        }
      ],
      order: [[0, "desc"]],
    }
  );
</script>
@endsection
