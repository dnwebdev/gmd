@extends('new-backoffice.header')
@section('addtionalStyle')
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
  <h3 class="mt-3 mb-3">{{ $booking_type == 'online' ? 'Pemesanan Online' : 'Transaksi di Lokasi' }} </h3>
  <div id="data-booking-online">
    <div class="card product_list_container" id="data-provider">
      <div class="card-header row" id="header">
        <div class="col-12 text-center">
          <button class="btn btn-success float-right" data-toggle="modal" data-target=".modal-download" type="button"><i class="icon-download"></i> &nbsp;Download Semua</button>
        </div>
      </div>
      @include('new-backoffice.booking.modal-download')
      <table id="dt" class="table datatable-responsive-column-controlled text-center w-100">
        <thead>
        <tr>
{{--          <th>No.</th>--}}
          <th class="all">No. Invoice</th>
          <th class="min-tablet">Tanggal</th>
          <th class="min-tablet">Domain</th>
          <th class="min-tablet">Total</th>
          <th class="min-tablet">Status</th>
          <th class="min-tablet">Status Pembayaran</th>
          <th class="min-tablet">Aksi</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

  </div>

@endsection

@section('additionalScript')
  <script src="{{asset('js/daterangepicker.js')}}"></script>
    <script>
      window.$ = jQuery;
      let inputDrp = $('input[name="drp"]');
      let dt = $('#dt').dataTable({
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "responsive": true,
        "language": {
          "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
        },
        "ajax": {
            url: "{{route('admin:master.transaction.data', ['type' => $booking_type])}}",
            type: "GET",
        },
        "columns": [
          // {
          //   "data": "DT_RowIndex", "orderable": false, "searchable": false
          // },
          {
            "data": "invoice_no"
          },
          {
            "data": "created_at"
          },
          {
            "data": "company.domain_memoria",
            "searchable": true
          },
          {
            "data": "total_amount",
            "searchable": true
          },
          {
            "data": "status",
            "searchable": true
          },
          {
            "data": "paymentStatus",
            "searchable": true
          },
          {
            "data": "action",
            "class": "text-center",
            "orderable":false,
            "searchable":false
          }
        ],
        "pageLength": 10,
        order: [[1, "desc"]],
      });

      dt.on( 'draw', function () {
        $('[data-popup="tooltip"]').tooltip();
      });

      $(document).on('click', '#btn-export', function () {
          $('#modal-export').modal();
      });
      $(function () {
        var start = moment('2019-03-01 00:00:00');
        var end = moment();

        function cb(start, end) {
            $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $('#daterange').daterangepicker({
          startDate: start,
          endDate: end,
          ranges: {
              '{{ trans('order_provider.today') }}': [moment(), moment()],
              '{{ trans('order_provider.yesterday') }}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              '{{ trans('order_provider.7_day') }}': [moment().subtract(6, 'days'), moment()],
              '{{ trans('order_provider.30_day') }}': [moment().subtract(29, 'days'), moment()],
              '{{ trans('order_provider.month') }}': [moment().startOf('month'), moment().endOf('month')],
              '{{ trans('order_provider.last_month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          locale: {
              'customRangeLabel': '{{ trans('order_provider.custome_range') }}'
          }
        }, cb);

        cb(start, end);

      });

      $(document).on('change', 'input[type=checkbox]', function () {
        let t = $(this);
        if (t.is(':checked')) {
            $('.trans').removeClass('d-none')
        } else {
            $('.trans').addClass('d-none')
        }
      });

      inputDrp.daterangepicker({
        opens: 'left'
      });
      $('input[name="status"]').on('change', function () {
        let checked = $('input[name="status"]').is(':checked');
        if(checked){
          $('#dateRangPickerDiv').removeClass('d-none');
        }else{
          $('#dateRangPickerDiv').addClass('d-none');
        }
      });
    </script>
@stop
