@extends('back-office.layout.index')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
@stop
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Detail Data Insurance</h3>
        </div>

    </div>
@stop
@section('content')
    <a href="{{ route('admin:insurance.data-customer.index') }}" class="btn btn-sm btn-success" style="margin-bottom: 10px">Back</a>
    <div class="m-portlet">
        <div class="m-portlet__body ">
               {!! Form::model($detailCustomer, ['id' => 'f-save-ad', 'files' => true]) !!}
            <div class="row ">
                <div class="col-6">
                    <div class="form-group">
                        {!! Form::label('invoice','Invoice') !!}
                        {!! Form::text('title', $detailCustomer->invoice_no ,['class'=>'form-control','id'=>'invoice', 'placeholder'=> 'Invoice', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('schedule','Schedule Date') !!}
                        {!! Form::text('title', \Carbon\Carbon::parse($detailCustomer->order->order_detail->schedule_date)->format('d M Y') ,['class'=>'form-control','id'=>'schedule', 'placeholder'=> 'Schedule Date', 'disabled']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('order_date','Order Date') !!}
                        {!! Form::text('title', \Carbon\Carbon::parse($detailCustomer->order->created_at)->format('d M Y, h:i:s') ,['class'=>'form-control','id'=>'order_date', 'placeholder'=> 'Order Date', 'disabled']) !!}
                    </div>
                    <h5>Participants</h5>
                    @forelse($detailCustomer->insurance_details()->orderBy('purpose_order', 'asc')->orderBy('insurance_form_id', 'asc')->get() as $item)
                        @if ($item->purpose == 'participants')
                            <div class="form-group">
                                <label for="Name">{{ $item->label_id  }}</label>
                                <input type="text" value="{{ $item->value }}" class="form-control" disabled>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
                <div class="col-6">
                    <h5>Customer</h5>
                    @forelse($detailCustomer->insurance_details()->orderBy('purpose_order', 'asc')->orderBy('insurance_form_id', 'asc')->get() as $item)
                        @if ($item->purpose == 'customer')
                            <div class="form-group">
                                <label for="Name">{{ $item->label_id  }}</label>
                                <input type="text" value="{{ $item->value }}" class="form-control" disabled>
                            </div>
                        @endif
                    @empty
                    @endforelse
                </div>
            </div>
                {!! Form::close() !!}
        </div>
    </div>

@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.multiple-select').select2();
        });
        $(".tinyMCE").summernote({height: 400});
        $('.tinyMCE').summernote('fontSize', 13);

        $(document).on('click', '.btn-change-image', function (e) {
            e.preventDefault();
            $(this).parent().find('input[name=image_blog]').trigger('click');
        });

        $(document).on('click','#btn-save', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('label.error').remove();
            let form = document.getElementById('f-save-ad');
            let formData = new FormData(form);
            $.ajax({
                url:'',
                type:"POST",
                data: formData,
                dataType:'json',
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
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
