@extends('back-office.layout.index')

@section('content')
    {!! Form::model(auth('admin')->user(),['style'=>'width;100%','files'=>true]) !!}
    <div class="row">

        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('admin_name') !!}
                {!! Form::text('admin_name',null,['class'=>'form-control']) !!}
                @if($errors->has('admin_name'))
                    <label for="" class="error">{{$errors->first('admin_name')}}</label>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('email') !!}
                {!! Form::text('email',null,['class'=>'form-control']) !!}
                @if($errors->has('email'))
                    <label for="" class="error">{{$errors->first('email')}}</label>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('password') !!}
                {!! Form::password('password',['class'=>'form-control']) !!}
                @if($errors->has('password'))
                    <label for="" class="error">{{$errors->first('password')}}</label>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('new_password') !!}
                {!! Form::password('new_password',['class'=>'form-control']) !!}
                @if($errors->has('new_password'))
                    <label for="" class="error">{{$errors->first('new_password')}}</label>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('new_password_confirmation') !!}
                {!! Form::password('new_password_confirmation',['class'=>'form-control']) !!}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('admin_avatar') !!}
                {!! Form::file('admin_avatar',['class'=>'form-control','onchange'=>'openFile(event)','accept'=>'image/*']) !!}
                @if($errors->has('admin_avatar'))
                    <label for="" class="error">{{$errors->first('admin_avatar')}}</label>
                @endif
            </div>
            <div id="preview">
                <img src="{{asset(auth('admin')->user()->admin_avatar)}}" alt="" class="img-fluid" id="output">
            </div>

        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 text-center">
            <button class="btn btn-success">Save</button>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('scripts')
    <script>
        var openFile = function(event) {
            var input = event.target;

            var reader = new FileReader();
            reader.onload = function(){
                var dataURL = reader.result;
                var output = document.getElementById('output');
                output.src = dataURL;
            };
            reader.readAsDataURL(input.files[0]);
        };
    </script>
@stop