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
                        {!! Form::label('id_company','Provider') !!}
                        {!! Form::select('id_company',$company,null,['class'=>'form-control select2','id'=>'id_company','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        <label for="listpayment">List Payment</label>
                        <select name="listpayment" class="form-control" id="listpayment">
                            <option selected disabled>- Select List Payment -</option>
                            @foreach ($listPayment as $item)
                                <option value="{{ $item->id }}"
                                    @if ($item->id == $list->payment_id)
                                    selected
                                    @endif
                                >{{ $item->name_payment }}
                                </option>
                            @endforeach
                        </select>
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
            $('.m-select2').select2();
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
        })

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