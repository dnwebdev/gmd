@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3 mb-3">Detail Product Sample</h3>
  <div class="card">
    <div class="card-body">
      <form action="" method="post">
        <div class="row">
          <div class="col-6">
            <div class="form-group form-group-float">
              <label for="product" class="form-group-float-label animate is-visible">Nama Produk</label>
              <input type="text" class="form-control" name="product" placeholder="Nama Produk">
            </div>
            <div class="form-group form-group-float">
              <label for="state" class="form-group-float-label animate is-visible">Provinsi</label>
              <select name="state" id="" class="form-control">
                <option value="0">Sumatera</option>
              </select>
            </div>
            <div class="form-group form-group-float">
              <label for="state" class="form-group-float-label animate is-visible">Kota</label>
              <select name="city" id="" class="form-control">
                <option value="0">Jambi</option>
              </select>
            </div>
          </div>
          <div class="col-6">
            <div class="form-group form-group-float">
              <label for="state" class="form-group-float-label animate is-visible">Kota</label>
              <textarea name="brief" id="" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <button class="btn btn-success" type="submit">Simpan</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection