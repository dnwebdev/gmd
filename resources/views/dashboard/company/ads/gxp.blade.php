@extends('dashboard.company.base_layout')
@section('additionalStyle')
<link rel="stylesheet" href="{{ asset('dest-operator/css/index.css') }}">
<link rel="stylesheet" href="{{ asset('materialize/js/plugins/bootrap-datepicker/bootstrap-datepicker3.css') }}">
{{--  <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">--}}
<link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
<link rel="stylesheet" href="{{asset('css/dataTablesEdit.css')}}">
    <style>
        .container-fluid.header-content-gxp{
            padding: 0 0 0 1.5rem;
        }
        @media (max-width: 768px) and (min-width: 320px) {
          .dashboard-gxp {
            margin-left: 0;
            margin-right: 0;
            overflow: visible!important;
          }
          .dashboard-gxp #btn-use-gxp {
              margin-top: 2rem!important;
              max-width: 100%;
          }
        }

        /* Datatable style fix start */
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
        .dataTable tr.child .dtr-data {
          white-space: normal;
        }
        @media screen and (min-width: 800px) {
          table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > th:first-child:before {
            top: 17px;
          }
        }
        table.dataTable.dtr-inline.collapsed > tbody > tr.parent > td:first-child:before, table.dataTable.dtr-inline.collapsed > tbody > tr.parent > th:first-child:before {
          content: "\f146";
        }
        /* Datatable style fix end */
    </style>
@endsection
@section('title', 'GXP')

@section('indicator_order')
  active
@endsection

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
  <div class="page-header" style="margin-bottom: 0;">
      <div class="page-header-content header-elements-md-inline">
          <div class="page-title">
              <h5>
                  {{-- {{-- <i class="icon-pushpin mr-2"></i> --}}  GXP
                  {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
              </h5>
          </div>

          <div class="header-elements py-0">
              <div class="breadcrumb">
                  <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                      <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                  </a>
                  <a href="{{ route('company.premium.index') }}" class="breadcrumb-item">
                      <i class="icon-home2 mr-2"></i> {{trans('premium.title')}}
                  </a>
                  <span class="breadcrumb-item active">GXP</span>
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

      <div class="dashboard-gxp">
        <div class="row">
          <div class="col">
            
            <div class="widget card dashboard-gxp dashboard-gxp-top" id="#header-balance-gxp">
              <div class="container-fluid text-center">
                <div class="form-inline" id="gxp-balance-section" role="form">
                  <img class="img-gxp-icon" src="{{asset('dest-operator/img/icon-gxp.svg')}}">
                  <small>{{trans('premium.gxp.your_gxp')}}<h1 id="balance-gxp">IDR {{ number_format($gxp['gxp'],0) }}</h1></small>
                </div>
                <a href="{{ route('company.premium.index', ['tab=my-feature']) }}" class="btn btn-primary float-right" id="btn-use-gxp"> {{trans('premium.gxp.use_gxp')}}</a>
                
                <!-- <div class="row mb-5">
                  <div class="col-12 col-lg-6 img-header-gxp">
                    <img class="img-gxp-icon" src="../img/icon-gxp.svg">
                  </div>
                  <div class="col-12 col-lg-6">
                    <small>Your GXP</small>
                    <h1 id="balance-gxp">Rp. 542,000</h1>
                  </div>
                </div> -->
              </div>
            </div>

            <div class="widget card dashboard-gxp">
              <div class="container-fluid header-content-gxp">
                <h3 id="history-gxp"><strong>{{trans('premium.gxp.history')}}</strong></h3>
              </div>
              <hr class="m-0"/>
              <div class="widget-table">
                <div class="responsive-table">
                  <table id="gxpTables" cellspacing="0">
                    <thead class="title-table">
                      <tr>
                        <th width="200px">{{trans('premium.gxp.date-details')}}</th>
                        <th>{{trans('premium.gxp.description')}}</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>

                {{-- <div class="row mb-5">
                  @forelse ($gxp_history as $item)
                  <div class="col-12 col-sm-2">
                    <p id="date-history-gxp">{{ $item->created_at->format('d/m/Y') }}</p>
                  </div>
                  <div class="col-12 col-sm-10">
                    <p><strong>{{ $item->description }}</strong></p>
                  </div>
                  @empty
                  Tidak ada data yang tersedia pada tabel ini
                  @endforelse
                </div> --}}
            </div>
            
          </div>
        </div>
      </div>
    </div>
</div>

    @endsection
@section('additionalScript')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
{{-- Datatables GXP --}}
<script type="text/javascript">
  window.$ = window.jQuery;
  let language_url = '{{ asset("json/datatables_english.json") }}';
  @if(app()->getLocale()=='id')
    language_url = '{{ asset("json/datatables_indonesia.json") }}';

  @endif
  let dt = $('#gxpTables').dataTable(
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
        },
        "columns": [
          {
              "data": "created_at",
          },
          {
              "data": "action", "orderable": false, "searchable": false
          },
        ],
        order: [[0, "asc"]],
    }
      );
</script>
  
@endsection