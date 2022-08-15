@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit Gomodo Update</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::model($update) !!}
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('type','Type Update') !!}
                        {!! Form::select('type',[
                        'info_promo'=>'Info Promo',
                        'patch_notes'=>'Patch Notes',
                        'upcoming_features'=>'Upcoming Features',
                        'new_features'=>'New Features'
                        ],null,['class'=>'form-control','id'=>'type']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('title','Title (EN)') !!}
                        {!! Form::text('title',null,['class'=>'form-control','id'=>'title']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('content','Content (EN)') !!}
                        {!! Form::textarea('content',null,['class'=>'tinyMCE form-control','id'=>'content']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('title_indonesia','Title (ID)') !!}
                        {!! Form::text('title_indonesia',null,['class'=>'form-control','id'=>'title_indonesia']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('content_indonesia','Content (ID)') !!}
                        {!! Form::textarea('content_indonesia',null,['class'=>'tinyMCE form-control','id'=>'content_indonesia']) !!}
                    </div>
                    <div class="form-group">
                        <div class="text-right">
                            <button class="btn btn-success"><i class="fa fa-save"></i> Save</button>
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
        $(".tinyMCE").summernote({height: 400})

        $(document).on('submit', 'form', function (e) {
            e.preventDefault();
            $('label.error').remove();
            let data = {
                type: $('select[name=type]').val(),
                title: $('input[name=title]').val(),
                title_indonesia: $('input[name=title_indonesia]').val(),
                content: '',
                content_indonesia: '',
            }
            if (!$('textarea[name=content_indonesia]').summernote('isEmpty')) {
                data.content_indonesia = $('textarea[name=content_indonesia]').summernote('code')
            }
            if (!$('textarea[name=content]').summernote('isEmpty')) {
                data.content = $('textarea[name=content]').summernote('code')
            }
            loadingStart();
            let t = $('button.btn.btn-success');
            $.ajax({
                url: '',
                type: 'POST',
                dataType:'json',
                data:data,
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