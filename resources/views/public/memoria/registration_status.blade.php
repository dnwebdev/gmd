@extends('public.memoria.base_layout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body" style="padding-top: 90px;">
                @if (Session::has('message'))
                <div class="title m-10">
                  {!! session('message') !!}
                </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
