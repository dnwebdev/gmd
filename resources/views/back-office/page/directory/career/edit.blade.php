@extends('back-office.layout.index')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
@stop
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit Career</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::model($career,['files'=>true, 'id' => 'f-save-ad']) !!}
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('location','Location') !!}
                        {!! Form::text('location',null,['class'=>'form-control','id'=>'location', 'placeholder'=> 'Location']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('title','Title') !!}
                        {!! Form::text('title',null,['class'=>'form-control','id'=>'title', 'placeholder'=> 'Title']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description','Description') !!}
                        {!! Form::textarea('description',null,['class'=>'tinyMCE form-control','id'=>'description']) !!}
                    </div>
                    <div class="form-group">
                        <label class="m-checkbox">
                            {!! Form::checkbox('active',true,null,[]) !!} Published
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group">
                        <div class="text-right">
                            <button type="button" id="btn-save" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script>

      $(".tinyMCE").summernote({height: 400});
      $('.tinyMCE').summernote('fontSize', 13);

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
