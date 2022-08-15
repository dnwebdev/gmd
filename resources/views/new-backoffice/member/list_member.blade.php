@extends('new-backoffice.header')
@section('content')
  <h5 class="mt-3 mb-3">KUPS</h5>
  <div id="data-booking-online" class="mt-3">
    <div class="card product_list_container">
      <div class="card-header row" id="header">
        <div class="col-12 text-right">
          <button type="button" class="btn btn-success" data-toggle="modal" data-target=".modal-add-category">
            <i class="icon-download"></i> &nbsp;Download
          </button>

        </div>
      </div>
      <table id="dt" class="table datatable-responsive-column-controlled w-100">
        <thead>
        <tr>
{{--          <th></th>--}}
          <th>Nama KUPS</th>
          <th>Domain</th>
          <th>Status</th>
          <th class="text-center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    @include('new-backoffice.member.add_category_modal')
    @include('new-backoffice.member.edit_category_modal')

  </div>
@endsection

@section('addtionalStyle')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('additionalScript')
  <script>
    let inputDrp = $('input[name="range"]');
    let dt = $('#dt').dataTable({
      "processing": true,
      "serverSide": true,
      "stateSave": true,
      "responsive": true,
      "language": {
        "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
      },
      "ajax": {
        url: "{{route('admin:providers.data')}}",
        type: "GET",
      },
      "pageLength": 10,
      "columns": [
        // {
        //   "data": "DT_RowIndex", "orderable": false, "searchable": false
        // },
        {
          "data": "company_name"
        },
        {
          "data": "domain_memoria"
        },
        // {
        //   "data": "ownership_status"
        // },
        {
          "data": "status"
        },
        {
          "data": "action",
          "class": "text-center",
          "orderable": false,
          "searchable": false
        }
      ],
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
  </script>
@endsection
