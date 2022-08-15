@extends('new-backoffice.header')
@section('content')
  <h3 class="mb-3 mt-3">Pengguna</h3>
  <div class="card">
    <div class="card-body">
      <div class="row">
        <div class="col-12 mb-3">
          <a href="{{ route('admin:setting.admin.add') }}"><button class="btn btn-success float-right"><i class="icon-add"></i>&nbsp; Tambah Pengguna</button></a>
        </div>
      </div>
      <table class="table datatable-responsive-column-controlled w-100" id="dt">
        <thead>
        <tr>
{{--          <th></th>--}}
          <th>Admin</th>
          <th>Email</th>
          <th>Sebagai</th>
          <th class="text-center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('additionalScript')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        let dt = $('#dt').dataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "responsive": true,
            "language": {
              "url": '//cdn.datatables.net/plug-ins/1.10.19/i18n/Indonesian.json'
            },
            "ajax": {
                url: "{{ route('admin:setting.admin.data') }}",
                type: "GET",

            },
            "columns": [
                // {
                //     "data": "DT_RowIndex", "orderable": false, "searchable": false
                // },
                {
                    "data": "admin_name", "orderable": true, "searchable": true
                },
                @if (request()->is_klhk)
                {
                    "data": "email", "orderable": true, "searchable": true
                },
                @endif
                {
                    "data": "role.role_name", "orderable": true, "searchable": true
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

        dt.on( 'draw', function () {
          $('[data-popup="tooltip"]').tooltip();
        });

    </script>
@stop
