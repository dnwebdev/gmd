@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3 mb-3">Tag Produk: {{ $tag->name }}</h3>
    <div class="header-content-modify" id="list_category">
    <div class="card product_list_container">
      <div class="card-header row" id="header">
        <div class="col-6">
        </div>
        <div class="col-6 text-center">
          <button class="btn btn-success float-right"><i class="icon-plus-circle2"></i> Tambah</button>
        </div>
      </div>
      <table id="dt" class="table datatable-responsive-column-controlled w-100">
        <thead>
        <tr>
          <th></th>
          <th>Kategori</th>
          <th>Produk</th>
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
        url: "{{route('admin:product.data', ['tag' => $tag->id])}}",
        type: "GET",
      },
     "columns": [
        {
          "data": "DT_RowIndex", "orderable": false, "searchable": false
        },
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
  </script>
@endsection
