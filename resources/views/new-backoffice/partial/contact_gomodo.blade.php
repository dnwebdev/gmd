@extends('new-backoffice.header')
@section('content')
  <h3 class="mt-3 mb-3">Tinggalkan Pesan</h3>
  <div class="card">
    <div class="card-body">
      <div class="form-group form-group-float">
        <label for="name" class="form-group-float-label animate is-visible">Nama</label>
        <input type="text" class="form-control" name="name" placeholder="Nama">
      </div>
      <div class="form-group form-group-float">
        <label for="name" class="form-group-float-label animate is-visible">Email</label>
        <input type="text" name="email" class="form-control" placeholder="Email">
      </div>
      <div class="form-group form-group-float">
        <label for="name" class="form-group-float-label animate is-visible">Pesan</label>
        <textarea type="text" class="form-control" placeholder="Pesan"></textarea>
      </div>
      <div class="float-right mt-5">
        <button class="btn btn-success">Kirim</button>
      </div>
    </div>
  </div>
@endsection