@extends('back-office.layout.index')
{{--@section('styles')--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>--}}
{{--@stop--}}
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">{{$title}}</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::open(['files'=>true, 'id' => 'f-save-ad','autocomplete'=>'off']) !!}
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {!! Form::label('title_eng','Title (Eng)') !!}
                        {!! Form::text('title_eng',null,['class'=>'form-control','id'=>'title_eng', 'placeholder'=> 'Title English']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('title_ind','Title (Ind)') !!}
                        {!! Form::text('title_ind',null,['class'=>'form-control','id'=>'title_ind', 'placeholder'=> 'Title Indonesia']) !!}
                    </div>
                    <div class="form-group">
                        <label for="category">Category Blog</label>
                        <select name="category_blog_id" class="form-control m-select2 select2-hidden-accessible" id="m_select2_1" name="param" data-select2-id="m_select2_1" tabindex="-1" aria-hidden="true">
                            <option selected disabled>- Select Category Blog -</option>
                            @foreach ($categorys as $categoryBlog)
                                <option value="{{ $categoryBlog->id }}">{{ $categoryBlog->category_name_ind }}
                                    / {{ $categoryBlog->category_name_eng }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('description_eng','Content (EN)') !!}
                        {!! Form::textarea('description_eng',null,['class'=>'tinyMCE form-control','id'=>'description_eng']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('description_ind','Content (ID)') !!}
                        {!! Form::textarea('description_ind',null,['class'=>'tinyMCE form-control','id'=>'description_ind']) !!}
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 mb-5">
                            <div class="card">
                                <img class="card-img-top" id="img-blg"
                                     src="{{asset('img/no-product-image.png')}}"
                                     alt="image"
                                     style="width:100%">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('image_blog', 'Image Content') !!}
                        {!! Form::file('image_blog', ['class' => 'form-control', 'id' => 'image_blog', 'accept' => 'image/*','onchange'=>'openFile(event)']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('image_blog', 'Alternative Image') !!}
                        {!! Form::text('alternative',null,['class'=>'form-control','id'=>'alternative', 'placeholder'=> 'Alternative Image']) !!}
                    </div>
                    <div class="form-group">
                        <label for="tag"></label>
                        <select name="tag[]" id="tag" class="form-control m-select2" id="m_select2_11" multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->tag_name_ind }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="m-checkbox">
                            {!! Form::checkbox('is_published',true,false,[]) !!} Published
                            <span></span>
                        </label>
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
    <script src="{{ asset('assets/demo/default/custom/crud/forms/widgets/select2.js') }}"></script>
    <script>
        var openFile = function(event) {
            var input = event.target;

            var reader = new FileReader();
            reader.onload = function(){
                var dataURL = reader.result;
                var output = document.getElementById('img-blg');
                output.src = dataURL;
            };
            reader.readAsDataURL(input.files[0]);
        };
        $(document).ready(function () {
            $('.m-select2').select2({
                placeholder: "- Select tag blog -"
            });
        });
        $(".tinyMCE").summernote({
            height: 400,
            fontSize: 13,
        });

        $('#image_blog').change(function () {
            $('#alternative').val(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
        });

        $(document).on('click', '#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
            // formData.append("tinyMCE", $('#description_ind').text());
            // formData.append("tinyMCE", $('#description_eng').text());
            if (!$('textarea[name=description_ind]').summernote('isEmpty')) {
                formData.append('description_ind', $('textarea[name=description_ind]').summernote('code'));
            }
            if (!$('textarea[name=description_eng]').summernote('isEmpty')) {
                formData.append('description_eng', $('textarea[name=description_eng]').summernote('code'));
            }
            $.ajax({
                url: '{{ $url_save }}',
                type: "POST",
                data: formData,
                dataType: 'json',
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                success: function (data) {
                    loadingFinish();
                    toastr.success(data.message, "Yey");
                    setTimeout(function () {
                        window.location = data.result.redirect;
                    }, 1000);
                },
                error: function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')
                            t.closest('form').find('select[name="' + i + '[]"]').closest('.form-group').append('<label class="error">' + el[0] + '</label>')

                        })
                    }
                    loadingFinish();
                }
            })
        });

        {{--$(document).on('submit', 'form', function (e) {--}}
        {{--    e.preventDefault();--}}
        {{--    $('label.error').remove();--}}
        {{--    let data = {--}}
        {{--        title_eng: $('input[name=title_eng]').val(),--}}
        {{--        title_ind: $('input[name=title_ind]').val(),--}}
        {{--        category_blog_id: $("#category option:selected").val(),--}}
        {{--        tag: $("#tag").val(),--}}
        {{--        description_ind: '',--}}
        {{--        description_eng: '',--}}
        {{--    };--}}
        {{--    if (!$('textarea[name=description_ind]').summernote('isEmpty')) {--}}
        {{--        data.description_ind = $('textarea[name=description_ind]').summernote('code')--}}
        {{--    }--}}
        {{--    if (!$('textarea[name=description_eng]').summernote('isEmpty')) {--}}
        {{--        data.description_eng = $('textarea[name=description_eng]').summernote('code')--}}
        {{--    }--}}
        {{--    loadingStart();--}}
        {{--    let t = $('button.btn.btn-success');--}}
        {{--    $.ajax({--}}
        {{--        url: '{{ route('admin:directory.post.save') }}',--}}
        {{--        type: 'POST',--}}
        {{--        dataType:'json',--}}
        {{--        data:data,--}}
        {{--        success:function (data) {--}}
        {{--            loadingFinish();--}}
        {{--            toastr.success(data.message,"Yey");--}}
        {{--            setTimeout(function () {--}}
        {{--                window.location = data.result.redirect;--}}
        {{--            },1000);--}}
        {{--        },--}}
        {{--        error:function (e) {--}}
        {{--            if (e.status !== undefined && e.status === 422) {--}}
        {{--                let errors = e.responseJSON.errors;--}}
        {{--                $.each(errors, function (i, el) {--}}
        {{--                    t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')--}}
        {{--                    t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')--}}
        {{--                    t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<label class="error">' + el[0] + '</label>')--}}
        {{--                })--}}

        {{--            }--}}
        {{--            loadingFinish();--}}
        {{--        }--}}
        {{--    })--}}
        {{--});--}}
    </script>
@stop