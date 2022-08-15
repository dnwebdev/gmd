@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Add Payment List</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::open(['files'=>true, 'id' => 'f-save-ad']) !!}
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="category">Category Payment</label>
                        <select name="category_payment_id" class="form-control m-select2 select2-hidden-accessible"
                                id="m_select2_1" name="param" data-select2-id="m_select2_1" tabindex="-1"
                                aria-hidden="true">
                            <option selected disabled>- Select Category Payment -</option>
                            @foreach ($categorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name_third_party }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_payment','Name Payment Ind *') !!}
                        {!! Form::text('name_payment',null,['class'=>'form-control','id'=>'name_payment', 'placeholder'=> 'Name Payment Ind']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_payment_eng','Name Payment Eng *') !!}
                        {!! Form::text('name_payment_eng',null,['class'=>'form-control','id'=>'name_payment_eng', 'placeholder'=> 'Name Payment Eng']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('code_payment','Code Payment *') !!}
                        {!! Form::text('code_payment',null,['class'=>'form-control','id'=>'code_payment', 'placeholder'=> 'Code Payment']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('settlement_duration','Settlement Duration *') !!}
                        {!! Form::number('settlement_duration',0,['class'=>'form-control','id'=>'settlement_duration', 'placeholder'=> 'Settlement Duration']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('type','Type Pricing Primary *') !!}
                        {!! Form::select('type',['fixed'=>'Fixed','percentage'=>'Percentage'],null,['class'=>'form-control','id'=>'type','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pricing_primary','Pricing Primary *') !!}
                        {!! Form::number('pricing_primary',0,['class'=>'form-control','id'=>'pricing_primary1','autocomplete'=>'off']) !!}
{{--                        <input type="text" name="pricing_primary_fixed" class="form-control pricie number" id="pricing_primary2" autocomplete="off" style="display: none">--}}
                    </div>
                    <div class="form-group">
                        {!! Form::label('type_secondary','Type Pricing Secondary *') !!}
                        {!! Form::select('type_secondary',['fixed'=>'Fixed','percentage'=>'Percentage'],null,['class'=>'form-control','id'=>'type_secondary','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pricing_secondary','Pricing Secondary *') !!}
                        {!! Form::number('pricing_secondary',0,['class'=>'form-control','id'=>'pricing_secondary','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('image_payment', 'Image Payment *') !!}
                        {!! Form::file('image_payment', ['class' => 'form-control', 'id' => 'image_payment', 'accept' => 'image/*']) !!}
                    </div>
                    <div class="form-group">
                        <label class="m-checkbox">
                            {!! Form::checkbox('status',true,false,[]) !!} Status
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
{{--    <script src="{{ asset('assets/demo/default/custom/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>--}}
    <script>
        $(document).ready(function () {
            $('.m-select2').select2({
                placeholder: "- Select tag blog -"
            });
        });
        $(".tinyMCE").summernote({
            height: 400,
            fontSize: 13,
        });
        // $('input[name=settlement_duration]').datepicker({
        //     opens: 'bottom',
        //     locale: {
        //         format: 'DD/MM/YYYY'
        //     }
        // });

        $('#image_payment').change(function () {
            $('#alternative').val(this.files && this.files.length ? this.files[0].name.split('.')[0] : '');
        });

        // $(document).on('change', 'select[name=type]', function () {
        //     let select = $(this);
        //     if (select.val() === 'fixed') {
        //         $('#pricing_primary1').hide();
        //         $('#pricing_primary2').show();
        //     }else{
        //         $('#pricing_primary1').show();
        //         $('#pricing_primary2').hide();
        //     }
        // });

        $(document).on('click', '#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
            $.ajax({
                url: '{{ route('admin:master.list-payment.save') }}',
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

    </script>
@stop