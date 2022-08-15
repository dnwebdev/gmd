@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Detail Provider Manual Transfer</h3>
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
                        {!! Form::label('company','Company Name') !!}
                        <input type="text" class="form-control" value="{{ $list->company->company_name }}" readonly placeholder="Company Name">
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_rekening','Owners name') !!}
                        {!! Form::text('name_rekening',null,['class'=>'form-control','id'=>'name_rekening', 'placeholder'=> 'Owners name', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('no_rekening','Nomor Rekening') !!}
                        {!! Form::number('no_rekening',null,['class'=>'form-control','id'=>'no_rekening', 'placeholder'=> 'Nomor Rekening', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('code_payment','Code Payment') !!}
                        {!! Form::text('code_payment',null,['class'=>'form-control','id'=>'code_payment', 'placeholder'=> 'Code Payment', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                            <div class="col-md-6 mb-7">
                                <div class="card">
                                    <img class="card-img-top"
                                         @if($list->upload_document)
                                         src="{{ asset('uploads/bank_manual/'.$list->upload_document) }}"
                                         @else
                                         src="{{asset('img/no-product-image.png')}}"
                                         @endif
                                         alt="image"
                                         style="width:100%">
                                    <div class="card-body">
                                        <a href="{{ route('admin:master.provider-manual-transfer.download', ['id' => $list->id]) }}" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="" class="form-control m-select2">
                            @foreach (App\Enums\ManualTransferStatus::manualList() as $key => $item)
                            <option value="{{ $key }}" {{ $key == $list->status ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
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
                placeholder: "- Select Status -"
            });
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