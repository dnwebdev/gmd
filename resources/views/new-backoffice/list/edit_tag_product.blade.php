@extends('new-backoffice.header')
@section('addtionalStyle')

@endsection
@section('content')
  <h3 class="mb-3 mt-3">Edit Rincian Produk</h3>
  <div class="card">
    <div class="card-body">
      <ul class="nav nav-tabs nav-tabs-highlight nav-justified">
        <li class="nav-item"><a href="#tab1-product" class="nav-link active" data-toggle="tab">Profil Perusahaan</a></li>
        <li class="nav-item"><a href="#tab2-product" class="nav-link" data-toggle="tab">Login Anggota</a></li>
        <li class="nav-item"><a href="#tab3-product" class="nav-link" data-toggle="tab">Akun Bank</a></li>
        <li class="nav-item"><a href="#tab4-product" class="nav-link" data-toggle="tab">Asosiasi</a></li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="tab1-product">
          <form action="" method="post">
            <div class="row">
              <div class="col-6">
                <div class="form-group form-group-float">
                  <label for="name" class="form-group-float-label animate is-visible">Domain</label>
                  <input type="text" class="form-control" name="domain_memoria" placeholder="Domain">
                </div>
                <div class="form-group form-group-float">
                  <label for="company_name" class="form-group-float-label animate is-visible">Nama Anggota</label>
                  <input type="text" class="form-control" name="company_name" placeholder="Nama Anggota">
                </div>
                <div class="form-group form-group-float">
                  <label for="email_company" class="form-group-float-label animate is-visible">Email Anggota</label>
                  <input type="email" class="form-control" name="email_company" placeholder="Email Anggota">
                </div>
                <div class="form-group form-group-float">
                  <label for="phone_company" class="form-group-float-label animate is-visible">Telepon Anggota</label>
                  <input type="tel" class="form-control" name="phone_company" placeholder="Telepon Anggota">
                </div>
                <div class="form-group form-group-float">
                  <label for="ownership_status" class="form-group-float-label animate is-visible">Status Kepemilikan</label>
                  <select name="ownership_status" id="" class="form-control">
                    <option value="personal">Pribadi</option>
                    <option value="corporate">Korporasi</option>
                  </select>
                </div>
                <div class="form-group form-group-float">
                  <label for="verified_provider" class="form-group-float-label animate is-visible">Anggota Terverifikasi</label>
                  <select name="verified_provider" id="" class="form-control">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group form-group-float">
                  <label for="about_company" class="form-group-float-label animate is-visible">Tentang Anggota</label>
                  <textarea type="text" class="form-control summernote" name="about_company" placeholder="Tentang Anggota"></textarea>
                </div>
                <div class="form-group form-group-float">
                  <label for="ga_code" class="form-group-float-label animate is-visible">Kode Google Analytics</label>
                  <input type="text" class="form-control" name="ga_code" placeholder="Kode Google Analytics">
                </div>
                <div class="form-group form-group-float">
                  <label for="is_klhk" class="form-group-float-label animate is-visible">Anggota KLHK</label>
                  <select name="is_klhk" id="" class="form-control">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                  </select>
                </div>
                <div class="form-group form-group-float">
                  <label for="status" class="form-group-float-label animate is-visible">Status Anggota</label>
                  <select name="status" id="" class="form-control">
                    <option value="0">Banned</option>
                    <option value="1">Aktif</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-12 text-center">
              <button class="btn btn-success" type="submit"><i class="icon-floppy-disk"></i> &nbsp;Simpan</button>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="tab2-product">
          <form action="" method="post">
            <div class="row">
              <div class="col-6">
                <div class="form-group form-group-float">
                  <label for="first_name" class="form-group-float-label animate is-visible">Nama Pertama</label>
                  <input type="text" class="form-control" name="first_name" placeholder="Nama Pertama">
                </div>
                <div class="form-group form-group-float">
                  <label for="last_name" class="form-group-float-label animate is-visible">Nama Terakhir</label>
                  <input type="text" class="form-control" name="last_name" placeholder="Nama Terakhir">
                </div>
                <div class="form-group form-group-float">
                  <label for="email" class="form-group-float-label animate is-visible">Email</label>
                  <input type="text" class="form-control" name="email" placeholder="Email">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group form-group-float">
                  <label for="password" class="form-group-float-label animate is-visible">Password</label>
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </div>
                <div class="form-group form-group-float">
                  <label for="status" class="form-group-float-label animate is-visible">Status</label>
                  <select name="status" id="" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                  </select>
                </div>
              </div>
              <div class="col-12 text-center">
                <button class="btn btn-success" type="button"><i class="icon-floppy-disk"></i> &nbsp;Simpan</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tab-pane fade" id="tab3-product">
          <form action="" method="get">

          </form>
        </div>
        <div class="tab-pane fade" id="tab4-product">
          <div class="row">
            <div class="col-12">
              <button class="btn btn-outline-success float-right" type="button" data-toggle="modal" data-target=".add-association"><i class="icon-plus-circle2"></i> Tambah Asosiasi</button>
            </div>
          </div>
          @include('new-backoffice.list.modal_add_association')
          <form action="" method="get">
            <div class="row">
              <div class="col-3">
                <div class="card">
                  <img src="https://image.shutterstock.com/image-photo/bright-spring-view-cameo-island-260nw-1048185397.jpg" alt="dummy" height="100">
                  <div class="card-body">
                    <span>ASDASD</span>
                  </div>
                  <div class="card-footer">
                    <div class="form-group">
                      <button class="btn btn-danger form-control" type="button">Hapus</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('additionalScript')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>
  <script>
    window.$ = jQuery;

    $('.summernote').summernote();
  </script>
@endsection
