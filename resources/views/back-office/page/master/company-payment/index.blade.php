@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Company Payment</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
{{--                <div class="col-12 mb-3 text-right">--}}
{{--                    <a href="{{ route('admin:master.company-payment.create') }}" id="btn-add" class="btn btn-sm btn-brand"><i--}}
{{--                                class="fa fa-plus"></i> Add All Company Payment</a>--}}
{{--                </div>--}}
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table" id="dt">
                            <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Name Company
                                </th>
                                <th>
                                    Name Payment List
                                </th>
                                <th>
                                    Action (Charge To)
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:master.company-payment.delete'),'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::hidden('id') !!}
                    <p>Are You Sure want to delete : <span class="namecompany"></span>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button class="btn btn-primary" id="btn-do-delete">Yes</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
        let dt = $('#dt').dataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "responsive": true,
            "ajax": {
                url: "{{route('admin:master.company-payment.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "company", "orderable": true, "searchable": true
                },
                {
                    "data": "listpayment", "orderable": true, "searchable": true
                },
                {
                    "data": "action",
                    "class": "text-center", "orderable": false, "searchable": false
                }
            ],
            order: [[1, "asc"]],
        });
        $(document).on('click', '.btn-delete', function () {
            let btn = $(this);
            let modal = $('#modal-delete');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('.namecompany').text(btn.data('namecompany'))
            modal.modal();
        });

        $(document).on('click', '.btn-active', function () {
            let btn = $(this);
            $.ajax({
                url: '{{route('admin:master.company-payment.active')}}',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: btn.data('id'),
                    company_id: btn.data('company_id')
                },
                success: function (data) {
                    dt.api().ajax.reload(null, false);
                    toastr.success('Yeay', data.message)
                },
                error: function (e) {
                    dt.api().ajax.reload(null, false);
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                }
            })
        });

        $(document).on('click', '.btn-nonactive', function () {
            let btn = $(this);
            $.ajax({
                url: '{{route('admin:master.company-payment.nonactive')}}',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: btn.data('id'),
                    company_id: btn.data('company_id')
                },
                success: function (data) {
                    dt.api().ajax.reload(null, false);
                    toastr.success('Yeay', data.message)
                },
                error: function (e) {
                    dt.api().ajax.reload(null, false);
                    toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')
                }
            })
        });

    </script>
@stop