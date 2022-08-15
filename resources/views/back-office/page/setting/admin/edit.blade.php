@extends('back-office.layout.index')

@section('content')
    {!! Form::model($admin,['style'=>'width;100%','files'=>true]) !!}
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
                {!! Form::label('role_id','Role') !!}
                {!! Form::select('role_id',\App\Models\Role::where('role_slug','!=','super-admin')->pluck('role_name','id'),null,['class'=>'form-control']) !!}
                @if($errors->has('role_id'))
                    <label for="" class="error">{{$errors->first('role_id')}}</label>
                @endif
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
                <img src="{{asset($admin->admin_avatar)}}" alt="" class="img-fluid" id="output">
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