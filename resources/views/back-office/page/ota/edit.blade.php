@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit OTA | {{$ota->ota_name}}</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        {!! Form::model($ota,['files'=>true]) !!}
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('ota_name') !!}
                        {!! Form::text('ota_name',null,['id'=>'ota_name','class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ota_slug') !!}
                        {!! Form::text('', $ota->ota_slug,['id'=>'ota_slug','class'=>'form-control readonly','disabled']) !!}
                        {!! Form::hidden('ota_slug', $ota->ota_slug, ['id' => 'ota_slug_hidden']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ota_original_markup') !!}
                        {!! Form::number('ota_original_markup',null,['id'=>'ota_original_markup','class'=>'form-control number','min'=>1,'max'=>50]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ota_gomodo_markup') !!}
                        {!! Form::number('ota_gomodo_markup',null,['id'=>'ota_gomodo_markup','class'=>'form-control number','min'=>1,'max'=>50]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ota_status') !!}
                        {!! Form::select('ota_status',['0'=>'Inactive','1'=>'Active'],null,['id'=>'ota_status','class'=>'form-control readonly','readonly']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('ota_icon') !!}
                        {!! Form::file('ota_icon',['id'=>'ota_icon','class'=>'form-control','accept'=>'image/*','onchange'=>'openFile(event)']) !!}
                    </div>
                    <div class="preview">
                        <img id="output" src="{{asset($ota->ota_icon)}}" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="col-12 mt-5 text-center">
                    <button class="btn btn-primary">Submit</button>
                </div>

            </div>
        </div>
        {!! Form::close() !!}
    </div>

@stop



@section('scripts')
    <script src="{{asset('assets/vendors/custom/slug/slugify.min.js')}}"></script>
    <script>
        $(".number").on('keydown change',function (e) {
            // Allow: backspace, delete, tab, escape, enter and .(190)
            if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode == 187 && (e.shiftKey === true || e.metaKey === true)) ||
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode == 189 && (e.shiftKey === false || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                // let it happen, don't do anything

                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 109) || (e.keyCode == 106 || e.keyCode == 110)) {
                e.preventDefault();
            }
            if (parseInt($(this).val())>50){
                $(this).val(50);
                e.preventDefault();
            }
        });
        $('#ota_slug').slugify('#ota_name');
        $('#ota_slug_hidden').slugify('#ota_name');

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

        $(document).on('change keyup','input[name=ota_name]', function () {
           $('input[name=ota_slug]').closest('.form-group').find('label.error').remove();
        });

        $(document).on('submit','form', function (e) {
            e.preventDefault();
            let data = new FormData(this);
            $(document).find('label.error').remove();
            $.ajax({
                url:'',
                type:"POST",
                dataType:'json',
                data:data,
                processData: false,
                contentType: false,
                cache: false,
                success:function (data) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        location.href = "{{route('admin:ota.index')}}";
                    },1000);
                },
                error:function (e) {
                    if (e.status===422){
                        let err = e.responseJSON;
                        $.each(err.errors, function (i,e) {
                            $(document).find('input[name='+i+']').closest('.form-group').append('<label class="error">'+e[0]+'</label>');
                        })
                    }

                }

            })
        })
    </script>
@stop
