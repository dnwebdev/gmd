@extends('new-backoffice.header')
@section('content')
  <div class="header-content-modify" id="blogpost">
    <h3 class="mt-3 mb-3">Post</h3>
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-6 d-inline-flex">
            <span>Tampilkan</span>
            <select name="" id="" class="form-control">
              <option value="0">10</option>
            </select>
          </div>
          <div class="col-6">
            <a href="#"><button type="button" class="btn btn-primary float-right"><i class="icon-add"></i> &nbsp;Tambah Postingan</button></a>
          </div>
        </div>
        <table class="table datatable-responsive-column-controlled">
          <thead>
          <tr>
            <th></th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th class="text-center">Aksi</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td></td>
            <td>Jalan jalan ke jogja</td>
            <td>Artikel</td>
            <td>23/06/96</td>
            <td class="text-center action-table">
              <a class="mr-3" href="#"><i class="icon-pencil"></i></a>
              <a href="#"><i class="icon-eye"></i></a>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection