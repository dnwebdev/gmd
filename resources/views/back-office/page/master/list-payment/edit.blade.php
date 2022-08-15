@extends('back-office.layout.index')
{{--@section('styles')--}}
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>--}}
{{--@stop--}}
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit List Payment</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body ">
            <div class="row ">
                <div class="col-12">
                    {!! Form::model($list, ['id' => 'f-save-ad', 'files' => true]) !!}
                    <div class="form-group">
                        <label for="category">Category Payment</label>
                        <select name="category_payment_id" class="form-control" id="category_payment_id">
                            <option selected disabled>- Select Category Payment -</option>
                            @foreach ($categorys as $category)
                                <option value="{{ $category->id }}"
                                        @if ($category->id == $list->category_payment_id)
                                        selected
                                        @endif
                                >{{ $category->name_third_party }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_payment','Name Payment Ind') !!}
                        {!! Form::text('name_payment',null,['class'=>'form-control','id'=>'name_payment', 'placeholder'=> 'Name Payment Ind']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_payment_eng','Name Payment Eng') !!}
                        {!! Form::text('name_payment_eng',null,['class'=>'form-control','id'=>'name_payment_eng', 'placeholder'=> 'Name Payment Eng']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('code_payment','Code Payment') !!}
                        {!! Form::text('code_payment',null,['class'=>'form-control','id'=>'code_payment', 'placeholder'=> 'Code Payment']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('settlement_duration','Settlement Duration *') !!}
                        {!! Form::number('settlement_duration',null,['class'=>'form-control','id'=>'settlement_duration', 'placeholder'=> 'Settlement Duration']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('type','Type Pricing Primary') !!}
                        {!! Form::select('type',['fixed'=>'Fixed','percentage'=>'Percentage'],null,['class'=>'form-control','id'=>'type','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pricing_primary','Pricing Primary') !!}
                        {!! Form::number('pricing_primary',null,['class'=>'form-control','id'=>'pricing_primary','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('type_secondary','Type Pricing Secondary') !!}
                        {!! Form::select('type_secondary',['fixed'=>'Fixed','percentage'=>'Percentage'],null,['class'=>'form-control','id'=>'type','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pricing_secondary','Pricing Secondary') !!}
                        {!! Form::text('pricing_secondary',null,['class'=>'form-control price number','id'=>'pricing_secondary','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 mb-5">
                            <div class="card">
                                <img class="card-img-top"
                                     @if($list->image_payment)
                                     src="{{ asset($list->image_payment) }}"
                                     @else
                                     src="{{asset('img/no-product-image.png')}}"
                                     @endif
                                     alt="image"
                                     style="width:100%">
                                <div class="card-body">
                                    <button class="btn btn-primary btn-change-image">Change
                                        Image
                                    </button>
                                    <input type="file" id="image_payment" name="image_payment" accept="image/*"
                                           style="visibility:hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="m-checkbox">
                            Status
                            <input type="checkbox" name="status"
                                   value="{{ $list->status }}" {{ $list->status ? 'checked' : '' }}>
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
        $(document).ready(function () {
            $('.m-select2').select2({
                placeholder: "- Select tag blog -"
            });
        });
        $('#image_payment').change(function () {
            $('#alternative').val(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
        });

        $(document).on('click', '.btn-change-image', function (e) {
            e.preventDefault();
            $(this).parent().find('input[name=image_payment]').trigger('click');
        });

        $(document).on('change', 'select[name=type]', function () {
            let select = $(this);
            if (select.val() === 'fixed') {
                $('#pricing_primary1').hide();
                $('#pricing_primary2').show();
            } else {
                $('#pricing_primary1').show();
                $('#pricing_primary2').hide();
            }
        });

        $(document).on('click', '#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
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