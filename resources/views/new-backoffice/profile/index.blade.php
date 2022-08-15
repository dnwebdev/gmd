@extends('new-backoffice.header')
@section('content')
  <h3 class="mb-3 mt-3">Profil</h3>
  <div class="card" id="admin_settings">
    <div class="card-body">
      <form method="post" action="" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-lg-6 col-sm-12">
            <div class="form-group form-group-float">
              <label for="name" class="form-group-float-label animate is-visible">Nama</label>
              <input type="text" class="form-control {{ $errors->has('admin_name') ? 'border-danger' : ' '}}" name="admin_name" placeholder="Nama" value="{{ old('admin_name', auth('admin')->user()->admin_name) }}">
              @if($errors->has('admin_name'))
                  <span class="form-text text-danger">{{$errors->first('admin_name')}}</span>
              @endif
            </div>
            <div class="form-group form-group-float">
              <label for="name" class="form-group-float-label animate is-visible">Email</label>
              <input type="email" class="form-control {{ $errors->has('email') ? 'border-danger' : ' '}}" name="email" placeholder="Email" value="{{ old('email', auth('admin')->user()->email) }}">
              @if($errors->has('email'))
                  <span class="form-text text-danger">{{$errors->first('email')}}</span>
              @endif
            </div>
            <div class="form-group form-group-float">
              <label for="name" class="form-group-float-label animate is-visible">Kata Sandi</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control {{ $errors->has('password') ? 'border-danger' : ' '}}" name="password" placeholder="Kata Sandi">
                <div class="input-group-append">
                  <button class="btn btn-secondary show-password" type="button" data-toggle="tooltip" title="Lihat Kata Sandi"><i class="icon-eye"></i></button>
                </div>
              </div>
              @if($errors->has('password'))
                  <span class="form-text text-danger">{{$errors->first('password')}}</span>
              @endif
            </div>
            <div class="form-group form-group-float">
              <label for="new_password" class="form-group-float-label animate is-visible">Kata Sandi Baru</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control {{ $errors->has('new_password') ? 'border-danger' : ' '}}" name="new_password" placeholder="Kata Sandi Baru" id="new_password">
                <div class="input-group-append">
                  <button class="btn btn-secondary show-password" type="button" data-toggle="tooltip" title="Lihat Kata Sandi"><i class="icon-eye"></i></button>
                </div>
              </div>
              @if($errors->has('new_password'))
                  <span class="form-text text-danger">{{$errors->first('new_password')}}</span>
              @endif
            </div>
            <div class="form-group form-group-float">
              <label for="name" class="form-group-float-label animate is-visible">Konfirmasi Kata Sandi</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control {{ $errors->has('new_password_confirmation') ? 'border-danger' : ' '}}" name="new_password_confirmation" placeholder="Konfirmasi Kata Sandi Baru">
                <div class="input-group-append">
                  <button class="btn btn-secondary show-password" type="button" data-toggle="tooltip" title="Lihat Kata Sandi"><i class="icon-eye"></i></button>
                </div>
              </div>
              @if($errors->has('new_password_confirmation'))
                  <span class="form-text text-danger">{{$errors->first('new_password_confirmation')}}</span>
              @endif
            </div>
          </div>
          <div class="col-lg-6 col-sm-12">
            <span class="d-block">Foto Profil</span>
            <img src="{{ auth('admin')->user()->admin_avatar }}" id="image-source" class="image-rounded d-block mt-3 mb-3" alt="dummy image" width="100">
            <div class="custom-file">
              <input type="file" class="custom-file-input" data-fouc id="input-image" name="admin_avatar" accept="image/x-png,image/gif,image/jpeg">
              <label class="custom-file-label" for="input-image">Foto</label>
            </div>
          </div>
        </div>
        <button class="btn btn-success mt-3" type="submit">Simpan</button>
      </form>
    </div>
  </div>

@endsection
@section('addtionalStyle')
  <style>
    .custom-file-input:lang(en) ~ .custom-file-label::after {
      content: "Unggah Gambar";
      background: #4cb050;
      color: white;
      padding: 10px;
      border-radius: 3px;
      line-height: 1.3;
    }
  </style>
@endsection
@section('additionalScript')
  <script src="{{asset('js/function-custom.js')}}"></script>
  <script>
    $(document).on('change', '#input-image', function () {
      readURL(this, '#image-source');
    });

    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })

    $('.show-password').on('click', function () {
      let input = $(this).parent().parent().find('input');
      let type = input.attr('type') == 'password' ? 'text' : 'password';
      input.attr('type', type);
      let icon = 'icon-eye';
      if (type == 'text') {
        icon = icon + '-blocked';
      }
      $(this).find('i').attr('class', icon);
    })
  </script>
@endsection
