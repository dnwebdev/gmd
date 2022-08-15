@extends('back-office.layout.index')
{{--@section('styles')--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>--}}
{{--@stop--}}
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit Blog Post</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body ">
            <div class="row ">
                <div class="col-12">
                    {!! Form::model($post, ['id' => 'f-save-ad', 'files' => true]) !!}
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
                        <select name="category_blog_id" class="form-control" id="category">
                            <option selected disabled>- Select Category Blog -</option>
                            @foreach ($categorys as $category)
                                <option value="{{ $category->id }}"
                                        @if ($category->id == $post->category_blog_id)
                                        selected
                                        @endif
                                >{{ $category->category_name_ind }} / {{ $category->category_name_eng }}</option>
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
                                     @if($post->image_blog)
                                     src="{{ asset($post->image_blog) }}"
                                     @else
                                     src="{{asset('img/no-product-image.png')}}"
                                     @endif
                                     alt="image"
                                     style="width:100%">
                                <div class="card-body">
                                    <button class="btn btn-primary btn-change-image">Change
                                        Image
                                    </button>
                                    <input type="file" id="image_blog" name="image_blog" accept="image/*"
                                           style="visibility:hidden" onchange="openFile(event)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('image_blog', 'Alternative Image') !!}
                        {!! Form::text('alternative',null,['class'=>'form-control','id'=>'alternative', 'placeholder'=> 'Alternative Image']) !!}
                    </div>
                    <div class="form-group">
                        <label for="tag"></label>
                        <select name="tag[]" id="tag" class="form-control m-select2" id="m_select2_11" multiple>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}"
                                        @foreach ($post->tagValue as $itemTag)
                                        @if ($itemTag->tag_blog_id == $tag->id)
                                        selected
                                        @endif
                                        @endforeach
                                >{{ app()->getLocale()==='id'?$tag->tag_name_ind:$tag->tag_name_eng}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="m-checkbox">
                            {!! Form::checkbox('is_published',true,false,[]) !!} Published
                            <input type="checkbox" name="is_published"
                                   value="{{ $post->is_published }}" {{ $post->is_published ? 'checked' : '' }}>
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group text-right">
                        <button type="button" id="btn-save" class="btn btn-success">Save</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

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
        $(".tinyMCE").summernote({height: 400, fontSize: 13});
        // $(".tinyMCE").summernote('code');
        $('#image_blog').change(function () {
            $('#alternative').val(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
        });

        $(document).on('click', '.btn-change-image', function (e) {
            e.preventDefault();
            $(this).parent().find('input[name=image_blog]').trigger('click');
        });

        $(document).on('click', '#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
            if (!$('textarea[name=description_ind]').summernote('isEmpty')) {
                formData.append('description_ind', $('textarea[name=description_ind]').summernote('code'));
            }
            if (!$('textarea[name=description_eng]').summernote('isEmpty')) {
                formData.append('description_eng', $('textarea[name=description_eng]').summernote('code'));
            }
            $.ajax({
                url: '',
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

        })
    </script>
@stop