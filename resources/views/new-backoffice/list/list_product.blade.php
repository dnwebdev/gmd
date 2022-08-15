@extends('new-backoffice.header')
@section('addtionalStyle')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
  <h3 class="mt-3 mb-3">Rincian Produk</h3>
  <div id="data-booking-online">
    <div class="card product_list_container" id="list_category">
      <div class="card-header row" id="header">
        <div class="col-12 text-center">
          <form action="{{ route('admin:product.export') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="all" />
            <input type="hidden" name="tag_id" value="{{ !empty($tag) ? $tag->id : 'all' }}" />
            <button class="btn btn-success float-right" type="submit"><i class="icon-download"></i> &nbsp;Download Semua</button>
          </form>
        </div>
      </div>
      <table id="dt" class="table datatable-responsive-column-controlled w-100">
        <thead>
        <tr>
{{--          <th>No.</th>--}}
          <th class="min-tablet">Nama Operator</th>
          <th class="min-tablet">Nama Produk</th>
          <th class="min-tablet">Lokasi</th>
          <th class="min-tablet text-center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    @include('new-backoffice.list.modal-download')
  </div>
@endsection
@section('additionalScript')
  <script src="{{asset('js/daterangepicker.js')}}"></script>
  <script>
    window.$ = jQuery;
    let inputDrp = $('input[name="range"]');
    let dt = $('#dt').dataTable({
      "processing": true,
      "serverSide": true,
      "stateSave": true,
      "responsive": true,
      "pageLength": 3,
      "language": {
        "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
      },
      "ajax": {
        url: "{{route('admin:product.data', ['tag' => !empty($tag) ? $tag->id : null])}}",
        type: "GET",
      },
      "columns": [
        // {
        //   // "data": "DT_RowIndex",
        //   "orderable": false, "searchable": false
        // },
        {
          "data": "company.company_name"
        },
        {
          "data": "product_name"
        },
        {
          "data": "city.city_name"
        },
        {
          "data": "action",
          "class": "text-center",
          "orderable": false,
          "searchable": false
        }
      ],
      "pageLength": 10,
      order: [[1, "asc"]],
    });
    inputDrp.daterangepicker({
      opens: 'left'
    });
    $('input[name="hasSuccessfulTransaction"]').on('change', function () {
      let checked = $('input[name="hasSuccessfulTransaction"]').is(':checked');
      if(checked){
        $('#dateRangPickerDiv').removeClass('d-none');
      }else{
        $('#dateRangPickerDiv').addClass('d-none');
      }
    });
    // inputDrp.on('change', function () {
    //   let thisValue = $(this).val();
    //   console.log(thisValue);
    // });
  </script>
@endsection
