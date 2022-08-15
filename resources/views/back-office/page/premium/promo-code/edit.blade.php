@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Add Promo Code</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body ">
            <div class="row ">
                <div class="col-12">
                   {!! Form::model($promo) !!}
                    <div class="form-group">
                        {!! Form::label('code','Code') !!}
                        {!! Form::text('code',null,['class'=>'form-control','id'=>'code','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('type','Type Promo Amount') !!}
                        {!! Form::select('type',['percentage'=>'Percentage','fixed'=>'Fixed Amount'],null,['class'=>'form-control','id'=>'type','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('amount','Amount') !!}
                        {!! Form::text('amount',null,['class'=>'form-control price number','id'=>'amount','autocomplete'=>'off']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('minimum_transaction','Minimum Transaction') !!}
                        {!! Form::text('minimum_transaction',null,['class'=>'form-control price number','id'=>'minimum_transaction','autocomplete'=>'off']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('max_amount','Max Amount') !!}
                        {!! Form::text('max_amount',null,['class'=>'form-control price number nullable','id'=>'max_amount','autocomplete'=>'off']) !!}
                    </div>

                    <div class="form-group">
                        <label class="m-checkbox">
                            {!! Form::checkbox('is_always_available',true,null,[]) !!} Always Available
                            <span></span>
                        </label>
                    </div>
                    <div class="form-group toggle-hide" style="display: none">
                        {!! Form::label('range','Active Period') !!}
                        {!! Form::text('range',$range,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('provider_max_use','Max Use each Provider') !!}
                        {!! Form::text('provider_max_use',null,['class'=>'form-control number','id'=>'provider_max_use','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('general_max_use','General Max Use') !!}
                        {!! Form::text('general_max_use',null,['class'=>'form-control number','id'=>'general_max_use','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group text-right">
                        <button type="button" id="btn-save-promo" class="btn btn-success">Save</button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')

    <script>
        $('input[name=range]').daterangepicker({
            opens: 'bottom',
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $(document).on('keypress change search input paste','input[name=code]', function () {
            $(this).val($(this).val().toUpperCase().replace(/\s/g, ''))
        });
        $(document).on('change','input[name=is_always_available]', function () {
           if (!$(this).is(':checked')){
               $('.toggle-hide').slideDown();
           }else{
               $('.toggle-hide').slideUp();
           }
        });
        check();
        function check(){
            let t = $('input[name=is_always_available]');
            if (!t.is(':checked')){
                $('.toggle-hide').slideDown();
            }else{
                $('.toggle-hide').slideUp();
            }
        }
        $(document).on('click','#btn-save-promo', function () {
            let t = $(this);
            t.closest('form').find('label.error').remove();
            loadingStart();
            $.ajax({
                url:'',
                type:'post',
                dataType:'json',
                data:t.closest('form').serialize(),
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