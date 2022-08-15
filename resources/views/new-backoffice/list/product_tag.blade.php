@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3 mb-3">Tag Produk</h3>
    <div class="header-content-modify" id="list_category">
    <div class="card product_list_container">
{{--      <div class="card-header row" id="header">--}}
{{--        <div class="col-6">--}}
{{--        </div>--}}
{{--        <div class="col-6 text-center">--}}
{{--          <a href="{{url('product/edit')}}">--}}
{{--            <button class="btn btn-success float-right" data-target="#modalProductTag" data-toggle="modal"><i class="icon-plus-circle2"></i> Tambah</button>--}}
{{--          </a>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--      @include('new-backoffice.list.modal_add_tag')--}}
      <div class="mt-5"></div>
      <table id="dt" class="table datatable-responsive-column-controlled">
        <thead>
        <tr>
{{--          <th></th>--}}
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
        url: "{{route('admin:master.product-tag.data')}}",
        type: "GET",
      },
      "columns": [
        // {
        //   "data": "DT_RowIndex", "orderable": false, "searchable": false
        // },
        {
          "data": "name", "orderable": true, "searchable": true
        },
        {
          "data": "products_count", "orderable": true, "searchable": false
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
  </script>
@endsection
