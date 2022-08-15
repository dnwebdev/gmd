@extends('back-office.layout.index')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>
@stop
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit Seo : {{$seo->category->category_page_title}} | {{$seo->section_title}}</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::model($seo,['files'=>true, 'id' => 'f-save-ad']) !!}
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('title','SEO Title') !!}
                        {!! Form::text('title',null,['class'=>'form-control','id'=>'title', 'placeholder'=> 'SEO Title']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('description','Description') !!}
                        {!! Form::textarea('description',null,['class'=>'form-control','id'=>'description', 'placeholder'=> 'Description']) !!}
                    </div>
                    <div class="form-group">
                        <label for="keywords">Keywords <em class="small">separated by comma</em></label>
                        {!! Form::textarea('keywords',null,['class'=>'form-control','id'=>'keywords', 'placeholder'=> 'Keyword Separated by comma']) !!}
                    </div>
                    <div class="form-group">
                        <div class="text-right">
                            <button type="button" id="btn-save" class="btn btn-success"><i class="fa fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script>
      $(document).on('click','#btn-save', function () {
        loadingStart();
        let t = $(this);
        t.closest('form').find('label.error').remove();
        let form = document.getElementById('f-save-ad');
        let formData = new FormData(form);
        $.ajax({
          url:'',
          type:"POST",
          data: formData,
          dataType:'json',
          cache: false,
          contentType: false,
          enctype: 'multipart/form-data',
          processData: false,
          success:function (data) {
            loadingFinish();
            toastr.success(data.message,"Yey");
            setTimeout(function () {
              window.location = data.result.redirect;
            },1000);
          },
          error:function (e) {
            if (e.status !== undefined && e.status === 422) {
              let errors = e.responseJSON.errors;
              $.each(errors, function (i, el) {
                t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
              })

            }
            loadingFinish();
          }
        })

      })
    </script>

@stop
