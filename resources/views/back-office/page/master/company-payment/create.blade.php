@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Add Company Payment</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            {!! Form::open(['files'=>true, 'id' => 'f-save-ad']) !!}
            <div class="row">
                <div class="col-12">
                    {{--                    <div class="form-group">--}}
                    {{--                        <label for="category">Name Company</label>--}}
                    {{--                        <select name="category_payment_id" class="form-control m-select2 select2-hidden-accessible"--}}
                    {{--                                id="m_select2_1" name="param" data-select2-id="m_select2_1" tabindex="-1"--}}
                    {{--                                aria-hidden="true">--}}
                    {{--                            <option selected disabled>- Select Name Company -</option>--}}
                    {{--                            @foreach ($company as $item)--}}
                    {{--                                <option value="{{ $item->id_company }}">{{ $item->company_name }}</option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}
                    <div class="form-group">
                        {!! Form::label('id_company','Provider') !!}
                        {!! Form::select('id_company',[],null,['class'=>'form-control select2','id'=>'id_company','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        <label for="listpayment">List Payment</label>
                        <select name="listpayment" class="form-control m-select2 select2-hidden-accessible"
                                id="m_select2_1" data-select2-id="m_select2_1" tabindex="-1"
                                aria-hidden="true">
                            <option selected disabled>- Select List Payment -</option>
                            @foreach ($listPayment as $item)
                                <option value="{{ $item->id }}">
                                    @if (app()->getLocale() === 'id' )
                                        {{ $item->name_payment }}
                                    @else
                                        {{ $item->name_payment_eng }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
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
    <script>
        $(document).ready(function () {
            $('.m-select2').select2();
        });

        $(document).on('click', '#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
            $.ajax({
                url: '{{ route('admin:master.company-payment.save') }}',
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

        $('select[name=id_company]').select2({
            placeholder: "- Select Company Name -",
            ajax: {
                url: "{{ route('admin:master.company-payment.search-provider') }}",
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
        });

        function formatRepo2(repo) {
            if (repo.loading) {
                return 'Looking for providers';
            }

            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar' style='display: inline-block'><img style='width: 80px' src=' " + repo.logo + "'></div>" +
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