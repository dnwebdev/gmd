@extends('new-backoffice.header')
@section('content')
  <h3 class="mb-3 mt-3">Edit Pengguna</h3>
  <div class="card" id="add_admin">
    <div class="card-body">
      <form method="post" action="">
        {{ csrf_field() }}
        <div class="form-group form-group-float">
          <label for="name" class="form-group-float-label animate is-visible">Nama</label>
          <input type="text" name="admin_name" class="form-control" placeholder="Nama" value="{{ old('admin_name', $admin->admin_name) }}">
          @if($errors->has('admin_name'))
              <span class="form-text text-danger">{{$errors->first('admin_name')}}</span>
          @endif
        </div>
        <div class="form-group form-group-float">
          <label for="name" class="form-group-float-label animate is-visible">Email</label>
          <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email', $admin->email) }}">
          @if($errors->has('email'))
              <span class="form-text text-danger">{{$errors->first('email')}}</span>
          @endif
        </div>
        <div class="form-group">
            {!! Form::label('role_id','Role') !!}
            {!! Form::select('role_id',\App\Models\Role::where('role_slug','!=','super-admin')->pluck('role_name','id'),null,['class'=>'form-control']) !!}
            @if($errors->has('role_id'))
                <label for="" class="error">{{$errors->first('role_id')}}</label>
            @endif
        </div>
        <div class="form-group form-group-float">
          <label for="name" class="form-group-float-label animate is-visible">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Password">
          @if($errors->has('password'))
              <span class="form-text text-danger">{{$errors->first('password')}}</span>
          @endif
        </div>
        <div class="form-group form-group-float">
          <label for="name" class="form-group-float-label animate is-visible">Konfirmasi Password</label>
          <input type="password" name="confirmation_password" class="form-control" placeholder="Konfirmasi Password">
          @if($errors->has('password_confirmation'))
              <span class="form-text text-danger">{{$errors->first('password_confirmation')}}</span>
          @endif
        </div>
        <button class="btn btn-success float-right" type="submit">Simpan</button>
      </form>
    </div>
  </div>
@endsection
