@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Edit Promo Code</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body ">
            <div class="row ">
                <div class="col-12">
                   {!! Form::model($voucher) !!}
                    <div class="form-group">
                        {!! Form::label('id_company','Provider') !!}
                        {!! Form::select('id_company',$company,null,['class'=>'form-control select2','id'=>'id_company','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('voucher_code','Voucher Code') !!}
                        {!! Form::text('voucher_code',null,['class'=>'form-control','id'=>'voucher_code','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('voucher_amount_type','Type Voucher Amount') !!}
                        {!! Form::select('voucher_amount_type',['percentage'=>'Percentage','fixed'=>'Fixed Amount'],$voucher->voucher_amount_type=='0'?'fixed':'percentage',['class'=>'form-control','id'=>'voucher_amount_type','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('voucher_amount','Voucher Amount') !!}
                        {!! Form::text('voucher_amount',null,['class'=>'form-control price number','id'=>'voucher_amount','autocomplete'=>'off']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('minimum_amount','Minimum Transaction') !!}
                        {!! Form::text('minimum_amount',null,['class'=>'form-control price number','id'=>'minimum_amount','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('up_to','Up To') !!}
                        {!! Form::text('up_to',null,['class'=>'form-control price number nullable','id'=>'up_to','autocomplete'=>'off']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('max_use','Max Use') !!}
                        {!! Form::text('max_use',null,['class'=>'form-control price number nullable','id'=>'max_use','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('created_at','Created At') !!}
                        {!! Form::text('created_at',null,['class'=>'form-control disabled','id'=>'created_at','autocomplete'=>'off','disabled'=>true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('valid_until','Valid Until') !!}
                        {!! Form::text('valid_end_date',null,['class'=>'form-control disabled','id'=>'valid_end_date','autocomplete'=>'off','disabled'=>true]) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('status','Status') !!}
                        {!! Form::text('status',null,['class'=>'form-control disabled','id'=>'valid_end_date','autocomplete'=>'off','disabled'=>true]) !!}
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
        $(document).on('keypress change search input paste','input[name=voucher_code]', function () {
            $(this).val($(this).val().toUpperCase().replace(/\s/g, ''))
        });
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
        $('select[name=id_company]').select2({
            ajax: {
                url: "{{ route('admin:voucher-gomodo.search-provider') }}",
                dataType: 'json',
                delay: 250,
                type: 'GET',
                data: function (params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                        results: data.items,
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },

            minimumInputLength: 3,
            templateResult: formatRepo2,
            templateSelection: formatRepoSelection2
        })

        function formatRepo2(repo) {
            if (repo.loading) {
                return 'Looking for providers';
            }

            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar' style='display: inline-block'><img style='width: 80px' src=' " + repo.logo+ "'></div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.text + "</div>" +
                "<div class='select2-result-repository__title'>" + repo.domain_memoria + "</div>" +
                "</div>";

            return markup;
        }

        function formatRepoSelection2(repo) {
            if (repo.supplier_name) {
                return repo.supplier_name
            } else {
                return repo.text;
            }

        }
    </script>
@stop