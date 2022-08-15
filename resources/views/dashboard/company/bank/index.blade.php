@extends('dashboard.company.base_layout')

@section('title', __('sidebar_provider.bank_account'))

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
    .error {
            color: red !important
        }
  </style>
  <link href="{{ asset('materialize/js/plugins/sweetalert/dist/sweetalert.css') }}" type="text/css" rel="stylesheet">
@endsection


@section('breadcrumb')
@endsection


@section('indicator_bank')
    active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('bank_provider.bank_account') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('bank_provider.bank_account') }}</span>
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

            @if (auth()->user()->company->bank && auth()->user()->company->bank->checkRequest)
                <div class="alert alert-info text-center" role="alert">
                    <strong>{{ trans('bank_provider.announcement') }}</strong><br>
                    {{ trans('bank_provider.change_account') }}
                </div>
            @endif

            <div class="widget card" id="order">
                <div class="widget-header">
                    <h3>{{ trans('bank_provider.bank_account_list') }}</h3>
                    <div class="widget-tools tools-full">
                        @if ($bank_count < 1)
                            <a href="{{ Route('company.bank.create') }}"
                                class="btn-primary btn-sm">{{ trans('bank_provider.new_bank_account') }}</a>
                        @endif
                    </div>
                </div>
                <div class="widget-content widget-full">
                    <div class="widget-table">
                        <div class="responsive-table">
                            <table class="table-border-bottom tablesorter" id="order-table">
                                <thead>
                                <tr>
                                    <th data-field="name" class="all">{{ trans('bank_provider.bank') }}</th>
                                    <th data-field="name" class="min-desktop">{{ trans('bank_provider.account_name') }}</th>
                                    <th data-field="name" class="min-desktop">{{ trans('bank_provider.account_number') }}</th>
                                    <th data-field="name" class="min-desktop">{{ trans('bank_provider.action') }}</th>
                                    <th data-field="name" class="min-desktop">{{ trans('bank_provider.request_change') }}</th>
                                </tr>
                                </thead>
                                <tbody id="menu_table_data">

                                {{-- @if($bank->toArray())
                                    @foreach($bank as $row)
                                        <tr>
                                            <td>
                                                @if ($row->bank == 'BCA')
                                                    Bank Central Asia (BCA)
                                                @elseif($row->bank == 'DANAMON')
                                                    Bank Danamon
                                                @elseif($row->bank == 'ARTHA')
                                                    Bank Artha Graha International
                                                @elseif($row->bank == 'ANZ')
                                                    Bank ANZ Indonesia
                                                @elseif($row->bank == 'BJB')
                                                    Bank BJB
                                                @elseif($row->bank == 'BJB_SYR')
                                                    Bank BJB Syariah
                                                @elseif($row->bank == 'PERMATA')
                                                    Bank Permata
                                                @elseif($row->bank == 'PANIN')
                                                    Bank Panin
                                                @elseif($row->bank == 'CAPITAL')
                                                    Bank Capital Indonesia
                                                @elseif($row->bank == 'ARTOS')
                                                    Bank Artos Indonesia
                                                @elseif($row->bank == 'BUMI_ARTA')
                                                    Bank Bumi Arta
                                                @elseif($row->bank == 'BNI_SYR')
                                                    Bank BNI Syariah
                                                @elseif($row->bank == 'BUKOPIN')
                                                    Bank Bukopin
                                                @elseif($row->bank == 'AGRONIAGA')
                                                    Bank BRI Agroniaga
                                                @elseif($row->bank == 'MANDIRI')
                                                    Bank Mandiri
                                                @elseif($row->bank == 'AGRIS')
                                                    Bank Agris
                                                @elseif($row->bank == 'CIMB')
                                                    Bank CIMB Niaga
                                                @elseif($row->bank == 'SINARMAS')
                                                    Bank Sinarmas
                                                @elseif($row->bank == 'BANGKOK')
                                                    Bangkok Bank
                                                @elseif($row->bank == 'BISNIS_INTERNASIONAL')
                                                    Bank Bisnis Internasional
                                                @elseif($row->bank == 'ANGLOMAS')
                                                    Anglomas International Bank
                                                @elseif($row->bank == 'ANDARA')
                                                    Bank Andara
                                                @elseif($row->bank == 'BNP_PARIBAS')
                                                    Bank BNP Paribas
                                                @elseif($row->bank == 'COMMONWEALTH')
                                                    Bank Commonwealth
                                                @elseif($row->bank == 'BCA_SYR')
                                                    Bank Central Asia (BCA) Syariah
                                                @elseif($row->bank == 'DANAMON_UUS')
                                                    Bank Danamon UUS
                                                @elseif($row->bank == 'INA_PERDANA')
                                                    Bank Ina Perdania
                                                @elseif($row->bank == 'DKI')
                                                    Bank DKI
                                                @elseif($row->bank == 'GANESHA')
                                                    Bank Ganesha
                                                @elseif($row->bank == 'CHINATRUST')
                                                    Bank Chinatrust Indonesia
                                                @elseif($row->bank == 'HANA')
                                                    Bank Hana
                                                @elseif($row->bank == 'DINAR_INDONESIA')
                                                    Bank Dinar Indonesia
                                                @elseif($row->bank == 'CIMB_UUS')
                                                    Bank CIMB Niaga UUS
                                                @elseif($row->bank == 'DKI_UUS')
                                                    Bank DKI UUS
                                                @elseif($row->bank == 'FAMA')
                                                    Bank Fama International
                                                @elseif($row->bank == 'HIMPUNAN_SAUDARA')
                                                    Bank Himpunan Saudara 1906
                                                @elseif($row->bank == 'ICBC')
                                                    Bank ICBC Indonesia
                                                @elseif($row->bank == 'HARDA_INTERNASIONAL')
                                                    Bank Harda Internasional
                                                @elseif($row->bank == 'DBS')
                                                    Bank DBS Indonesia
                                                @elseif($row->bank == 'INDEX_SELINDO')
                                                    Bank Index Selindo
                                                @elseif($row->bank == 'PANIN_SYR')
                                                    Bank Panin Syariah
                                                @elseif($row->bank == 'JAWA_TENGAH_UUS')
                                                    BPD Jawa Tengah UUS
                                                @elseif($row->bank == 'KALIMANTAN_TIMUR_UUS')
                                                    BPD Kalimantan Timur UUS
                                                @elseif($row->bank == 'BTN_UUS')
                                                    Bank Tabungan Negara (BTN) UUS
                                                @elseif($row->bank == 'ACEH_UUS')
                                                    BPD Aceh UUS
                                                @elseif($row->bank == 'KALIMANTAN_BARAT_UUS')
                                                    BPD Kalimantan Barat UUS
                                                @elseif($row->bank == 'JASA_JAKARTA')
                                                    Bank Jasa Jakarta
                                                @elseif($row->bank == 'DAERAH_ISTIMEWA')
                                                    BPD Daerah Istimewa Yogyakarta (DIY)
                                                @elseif($row->bank == 'KALIMANTAN_BARAT')
                                                    BPD Kalimantan Barat
                                                @elseif($row->bank == 'MASPION')
                                                    Bank Maspion Indonesia
                                                @elseif($row->bank == 'MAYAPADA')
                                                    Bank Mayapada International
                                                @elseif($row->bank == 'BRI_SYR')
                                                    Bank Syariah BRI
                                                @elseif($row->bank == 'TABUNGAN_PENSIUNAN_NASIONAL')
                                                    Bank Tabungan Pensiunan Nasional
                                                @elseif($row->bank == 'VICTORIA_INTERNASIONAL')
                                                    Bank Victoria Internasional
                                                @elseif($row->bank == 'BALI')
                                                    BPD Bali
                                                @elseif($row->bank == 'JAWA_TENGAH')
                                                    BPD Jawa Tengah
                                                @elseif($row->bank == 'KALIMANTAN_SELATAN')
                                                    BPD Kalimantan Selatan
                                                @elseif($row->bank == 'MAYBANK_SYR')
                                                    Bank Maybank Syariah Indonesia
                                                @elseif($row->bank == 'SAHABAT_SAMPOERNA')
                                                    Bank Sahabat Sampoerna
                                                @elseif($row->bank == 'KALIMANTAN_SELATAN_UUS')
                                                    BPD Kalimantan Selatan UUS
                                                @elseif($row->bank == 'KALIMANTAN_TENGAH')
                                                    BPD Kalimantan Tengah
                                                @elseif($row->bank == 'MUAMALAT')
                                                    Bank Muamalat Indonesia
                                                @elseif($row->bank == 'BUKOPIN_SYR')
                                                    Bank Syariah Bukopin
                                                @elseif($row->bank == 'NUSANTARA_PARAHYANGAN')
                                                    Bank Nusantara Parahyangan
                                                @elseif($row->bank == 'JAMBI_UUS')
                                                    BPD Jambi UUS
                                                @elseif($row->bank == 'JAWA_TIMUR')
                                                    BPD Jawa Timur
                                                @elseif($row->bank == 'MEGA')
                                                    Bank Mega
                                                @elseif($row->bank == 'DAERAH_ISTIMEWA_UUS')
                                                    BPD Daerah Istimewa Yogyakarta (DIY) UUS
                                                @elseif($row->bank == 'KALIMANTAN_TIMUR')
                                                    BPD Kalimantan Timur
                                                @elseif($row->bank == 'MULTI_ARTA_SENTOSA')
                                                    Bank Multi Arta Sentosa
                                                @elseif($row->bank == 'OCBC')
                                                    Bank OCBC NISP
                                                @elseif($row->bank == 'NATIONALNOBU')
                                                    Bank Nationalnobu
                                                @elseif($row->bank == 'BOC')
                                                    Bank of China (BOC)
                                                @elseif($row->bank == 'BTN')
                                                    Bank Tabungan Negara (BTN)
                                                @elseif($row->bank == 'BENGKULU')
                                                    BPD Bengkulu
                                                @elseif($row->bank == 'RESONA')
                                                    Bank Resona Perdania
                                                @elseif($row->bank == 'MANDIRI_SYR')
                                                    Bank Syariah Mandiri
                                                @elseif($row->bank == 'WOORI')
                                                    Bank Woori Indonesia
                                                @elseif($row->bank == 'YUDHA_BHAKTI')
                                                    Bank Yudha Bhakti
                                                @elseif($row->bank == 'ACEH')
                                                    BPD Aceh
                                                @elseif($row->bank == 'MAYORA')
                                                    Bank Mayora
                                                @elseif($row->bank == 'BAML')
                                                    Bank of America Merill-Lynch
                                                @elseif($row->bank == 'PERMATA_UUS')
                                                    Bank Permata UUS
                                                @elseif($row->bank == 'KESEJAHTERAAN_EKONOMI')
                                                    Bank Kesejahteraan Ekonomi
                                                @elseif($row->bank == 'MESTIKA_DHARMA')
                                                    Bank Mestika Dharma
                                                @elseif($row->bank == 'OCBC_UUS')
                                                    Bank OCBC NISP UUS
                                                @elseif($row->bank == 'RABOBANK')
                                                    Bank Rabobank International Indonesia
                                                @elseif($row->bank == 'ROYAL')
                                                    Bank Royal Indonesia
                                                @elseif($row->bank == 'MITSUI')
                                                    Bank Sumitomo Mitsui Indonesia
                                                @elseif($row->bank == 'UOB')
                                                    Bank UOB Indonesia
                                                @elseif($row->bank == 'INDIA')
                                                    Bank of India Indonesia
                                                @elseif($row->bank == 'SBI_INDONESIA')
                                                    Bank SBI Indonesia
                                                @elseif($row->bank == 'MEGA_SYR')
                                                    Bank Syariah Mega
                                                @elseif($row->bank == 'JAMBI')
                                                    BPD Jambi
                                                @elseif($row->bank == 'JAWA_TIMUR_UUS')
                                                    BPD Jawa Timur UUS
                                                @elseif($row->bank == 'MIZUHO')
                                                    Bank Mizuho Indonesia
                                                @elseif($row->bank == 'MNC_INTERNASIONAL')
                                                    Bank MNC Internasional
                                                @elseif($row->bank == 'TOKYO')
                                                    Bank of Tokyo Mitsubishi UFJ
                                                @elseif($row->bank == 'VICTORIA_SYR')
                                                    Bank Victoria Syariah
                                                @elseif($row->bank == 'LAMPUNG')
                                                    BPD Lampung
                                                @elseif($row->bank == 'MALUKU')
                                                    BPD Maluku
                                                @elseif($row->bank == 'SUMSEL_DAN_BABEL_UUS')
                                                    BPD Sumsel Dan Babel UUS
                                                @elseif($row->bank == 'MAYBANK')
                                                    Bank Maybank
                                                @elseif($row->bank == 'JPMORGAN')
                                                    JP Morgan Chase Bank
                                                @elseif($row->bank == 'SULSELBAR_UUS')
                                                    BPD Sulselbar UUS
                                                @elseif($row->bank == 'SULAWESI_TENGGARA')
                                                    BPD Sulawesi Tenggara
                                                @elseif($row->bank == 'NUSA_TENGGARA_BARAT')
                                                    BPD Nusa Tenggara Barat
                                                @elseif($row->bank == 'RIAU_DAN_KEPRI_UUS')
                                                    BPD Riau Dan Kepri UUS
                                                @elseif($row->bank == 'SULUT')
                                                    BPD Sulut
                                                @elseif($row->bank == 'SUMUT')
                                                    BPD Sumut
                                                @elseif($row->bank == 'DEUTSCHE')
                                                    Deutsche Bank
                                                @elseif($row->bank == 'STANDARD_CHARTERED')
                                                    Standard Charted Bank
                                                @elseif($row->bank == 'BRI')
                                                    Bank Rakyat Indonesia (BRI)
                                                @elseif($row->bank == 'HSBC')
                                                    HSBC Indonesia (formerly Bank Ekonomi Raharja)
                                                @elseif($row->bank == 'SULSELBAR')
                                                    BPD Sulselbar
                                                @elseif($row->bank == 'SUMATERA_BARAT_UUS')
                                                    BPD Sumatera Barat UUS
                                                @elseif($row->bank == 'NUSA_TENGGARA_BARAT_UUS')
                                                    BPD Nusa Tenggara Barat UUS
                                                @elseif($row->bank == 'HSBC_UUS')
                                                    Hongkong and Shanghai Bank Corporation (HSBC) UUS
                                                @elseif($row->bank == 'PAPUA')
                                                    BPD Papua
                                                @elseif($row->bank == 'SULAWESI')
                                                    BPD Sulawesi Tengah
                                                @elseif($row->bank == 'SUMATERA_BARAT')
                                                    BPD Sumatera Barat
                                                @elseif($row->bank == 'SUMUT_UUS')
                                                    BPD Sumut UUS
                                                @elseif($row->bank == 'BNI')
                                                    Bank Negara Indonesia (BNI)
                                                @elseif($row->bank == 'PRIMA_MASTER')
                                                    Prima Master Bank
                                                @elseif($row->bank == 'MITRA_NIAGA')
                                                    Bank Mitra Niaga
                                                @elseif($row->bank == 'NUSA_TENGGARA_TIMUR')
                                                    BPD Nusa Tenggara Timur
                                                @elseif($row->bank == 'SUMSEL_DAN_BABEL')
                                                    BPD Sumsel Dan Babel
                                                @elseif($row->bank == 'RBS')
                                                    Royal Bank of Scotland (RBS)
                                                @elseif($row->bank == 'ARTA_NIAGA_KENCANA')
                                                    Bank Arta Niaga Kencana
                                                @elseif($row->bank == 'CITIBANK')
                                                    Citibank
                                                @elseif($row->bank == 'RIAU_DAN_KEPRI')
                                                    BPD Riau Dan Kepri
                                                @elseif($row->bank == 'CENTRATAMA')
                                                    Centratama Nasional Bank
                                                @elseif($row->bank == 'OKE')
                                                    Bank Oke Indonesia (formerly Bank Andara)
                                                @elseif($row->bank == 'MANDIRI_ECASH')
                                                    Mandiri E-Cash
                                                @elseif($row->bank == 'AMAR')
                                                    Bank Amar Indonesia (formerly Anglomas International Bank)
                                                @elseif($row->bank == 'GOPAY')
                                                    GoPay
                                                @elseif($row->bank == 'SINARMAS_UUS')
                                                    Bank Sinarmas UUS
                                                @elseif($row->bank == 'OVO')
                                                    OVO
                                                @elseif($row->bank == 'EXIMBANK')
                                                    Indonesia Eximbank (formerly Bank Ekspor Indonesia)
                                                @elseif($row->bank == 'JTRUST')
                                                    Bank JTrust Indonesia (formerly Bank Mutiara)
                                                @elseif($row->bank == 'WOORI_SAUDARA')
                                                    Bank Woori Saudara Indonesia 1906 (formerly Bank Himpunan Saudara and Bank Woori Indonesia)
                                                @elseif($row->bank == 'BTPN_SYARIAH')
                                                    BTPN Syariah (formerly BTPN UUS and Bank Sahabat Purba Danarta)
                                                @elseif($row->bank == 'SHINHAN')
                                                    Bank Shinhan Indonesia (formerly Bank Metro Express)
                                                @elseif($row->bank == 'BANTEN')
                                                    BPD Banten (formerly Bank Pundi Indonesia)
                                                @elseif($row->bank == 'CCB')
                                                    China Construction Bank Indonesia (formerly Bank Antar Daerah and Bank Windu Kentjana International)
                                                @elseif($row->bank == 'MANDIRI_TASPEN')
                                                    Mandiri Taspen Pos (formerly Bank Sinar Harapan Bali)
                                                @elseif($row->bank == 'QNB_INDONESIA')
                                                    Bank QNB Indonesia (formerly Bank QNB Kesawan)
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{ $row->bank_account_name }}
                                            </td>
                                            <td>
                                                {{ $row->bank_account_number }}
                                            </td>
                                            <td>
                                            <!-- <button id="deleteBank.{{ $row->id }}" data-id="{{ $row->id }}" data-token="{{ csrf_token() }}" url="{{ Route('company.bank.delete',$row->id) }}" >Delete Task</button> -->
                                            <!-- <span class="action fa fa-trash" id="deleteBank" data-id="{{ $row->id }}" data-token="{{ csrf_token() }}" url="{{ Route('company.bank.delete',$row->id) }}" ></span> -->
                                                <a href="{{ Route('company.bank.edit',$row->id) }}">{{ trans('bank_provider.view') }}</a>
                                            </td>
                                            <td>
                                                {{$row->request_changes->count()>0?$row->request_changes->count():''}}
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" colspan="4">-- No Bank Account Yet --
                                        </td>
                                    </tr>
                                @endif --}}

                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="pagination justify-content-end">
                            <ul>
                            {{$bank->links()}}
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('additionalScript')
    <script type="text/javascript" src="{{ asset('js/form_ajax.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
{{--    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>--}}


    <script>

      jQuery("#deleteBank").click(function(){
        var id = jQuery(this).data("id");
        var token = jQuery(this).data('token')
        jQuery.ajax(
            {
              url: jQuery("#deleteBank").attr('url'),
              type: 'DELETE',
              dataType: "JSON",
              data: {
                "id": id,
                "_method": 'DELETE',
                "_token": token,
              },
              success: function(response){
                if (response.status) {

                  if (response.status == 200) {
                    toastr.remove()
                    swal({
                      title: "Success",
                      text: response.message,
                      type: "success",
                    }).then(function(){
                      location.reload()
                    });
                  } else {
                    toastr.remove()
                    swal({
                      title: "{{trans('general.whoops')}}",
                      text: response.message,
                      type: "error",
                    }).then(function(){
                    });
                  }
                } else {
                  toastr.remove()
                  swal({
                    title: "{{trans('general.whoops')}}",
                    text: response.message,
                    type: "error",
                  }).then(function(){
                  });
                }

              }
            });
        console.log("tr", rowCount);
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
            "data": "bank"
          },
          {
            "data": "bank_account_name", "orderable": false
          },
          {
            "data": "bank_account_number", "orderable": false
          },
          {
            "data": "action", "orderable": false
          },
          {
            "data": "request_changes_count", "orderable": false
          }
        ],
        "bFilter": false,
        "bLengthChange" : false, //thought this line could hide the LengthMenu
        "bInfo":false,
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
