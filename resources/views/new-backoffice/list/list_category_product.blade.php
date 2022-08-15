@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3 mb-3">Kategori Produk</h3>
    <div id="data-booking-online">
    <div class="card product_list_container" id="list_category">
      <div class="card-header row" id="header">
        <div class="col-6">
        </div>
        <div class="col-6 text-center">
          <button class="btn btn-success float-right"><i class="icon-download"></i> &nbsp;Download Semua</button>
        </div>
      </div>
      <table class="table datatable-responsive-column-controlled">
        <thead>
        <tr>
          <th></th>
          <th>Nama Operator</th>
          <th>Produk</th>
          <th class="text-center">Aksi</th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td></td>
          <td>
            Kalibiru yang biru
          </td>
          <td>20 Produk</td>
          <td class=" action-table text-center">
            <a href="#"><i class="icon-pencil3"></i></a>
            <a href="#"><i class="icon-eye"></i></a>
            <a href="#"><i class="icon-trash"></i></a>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

  </div>
@endsection