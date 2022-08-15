@extends('public.memoria.base_layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body" style="padding-top: 90px;">
                  @if($errors && $errors->first())
                    @foreach ($errors->all() as $key=>$error)
                      <div class="text-danger">{{ $error }}</div>
                    @endforeach
                  @else
                  <div class="row mb-2">
                    <span class="help-block">
                      <strong>Congratulations, your account has been activated</strong>
                    </span>
                  </div>
                  <div class="row">
                    <a href="{{ Route('login') }}" class="btn btn-primary">Login Now</a>
                  </div>
                  @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection