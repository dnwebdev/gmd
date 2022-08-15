@extends('new-backoffice.header')
@section('content')
  <div class="header-content-modify disabled" id="content-operator-category">
    <h3 class="mt-3 mb-3">Kategori Anggota</h3>
{{--    <div class="card product_list_container">--}}
{{--      <div class="card-header row" id="header">--}}
{{--        <div class="col-6">--}}
{{--          <select name="select" id="" class="form-control" disabled>--}}
{{--            <option value="0" selected>10</option>--}}
{{--          </select>--}}
{{--        </div>--}}
{{--        <div class="col-6 text-center">--}}
{{--          <input type="text" placeholder="search" disabled>--}}
{{--          <button class="btn btn-primary" disabled>Download All</button>--}}
{{--        </div>--}}
{{--      </div>--}}
{{--      <table class="table datatable-responsive-column-controlled">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--          <th></th>--}}
{{--          <th>Kategori</th>--}}
{{--          <th>Operator</th>--}}
{{--          <th class="text-center">Aksi</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        <tr>--}}
{{--          <td></td>--}}
{{--          <td>Taman Nasional</td>--}}
{{--          <td>--}}
{{--            20 operator--}}
{{--          </td>--}}
{{--          <td class="text-center action-table">--}}
{{--            <a href="#" class="disabled-something"><i class="icon-pencil"></i></a>--}}
{{--            <a href="#" class="disabled-something"><i class="icon-eye"></i></a>--}}
{{--            <a href="#" class="disabled-something"><i class="icon-trash"></i></a>--}}
{{--          </td>--}}
{{--        </tr>--}}
{{--        </tbody>--}}
{{--      </table>--}}
{{--    </div>--}}
{{--    <div class="header-content-modify" id="coming-soon">--}}
{{--      <h1>COMING SOON</h1>--}}
{{--    </div>--}}

    @include('new-backoffice.partial.coming_soon')

  </div>
@endsection