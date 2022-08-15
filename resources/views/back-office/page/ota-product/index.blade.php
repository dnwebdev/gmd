@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">List Product OTA</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('ota_name') !!}
                        {!! Form::select('ota',array_prepend(App\Models\Ota::pluck('ota_name','id')->toArray(),'All',''),null,['class'=>'form-control select2']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('provider') !!}
                        {!! Form::select('provider',array_prepend(App\Models\Company::pluck('company_name','id_company')->toArray(),'All',''),null,['class'=>'form-control select2']) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('status') !!}
                        {!! Form::select('status',[''=>'All','0'=>'Pending','1'=>'Approved','2'=>'Rejected'],null,['class'=>'form-control select2']) !!}
                    </div>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table" id="dt">
                            <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Product Name
                                </th>
                                <th>
                                    Company Name
                                </th>
                                <th>
                                    Action
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
    <div class="modal fade" id="modal-reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:ota.list.update-status'),'style'=>'width:100%' ,'id'=>'form-reject']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Reject Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::hidden('id') !!}
                    {!! Form::hidden('product') !!}
                    {!! Form::hidden('status','reject') !!}
                    <div class="form-group">
                        {!! Form::label('reject_reason','Reject Reason (ID)') !!}
                        {!! Form::textarea('reject_reason',null,['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('reject_reason','Reject Reason (EN)') !!}
                        {!! Form::textarea('reject_reason_en',null,['class'=>'form-control']) !!}
                    </div>
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
        $(document).on('change', 'select', function () {
            dt.api().ajax.reload();
        });
        $(document).find('.select2').select2();
        let dt = $('#dt').dataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "responsive": true,
            "ajax": {
                url: "{{route('admin:ota.list.data')}}",
                type: "GET",
                data: function (d) {
                    d.ota = $('select[name=ota]').val();
                    d.provider = $('select[name=provider]').val();
                    d.status = $('select[name=status]').val()
                }
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "product_name"
                },
                {
                    "data": "company.company_name"
                },
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data": "action",
                    "defaultContent": ''
                },
            ],
            order: [[1, "asc"]],
        });

        function format(d) {
            let tbl = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; width:100%">' +
                '<thead>' +
                '<tr>' +
                '<th>OTA Name</th>' +
                '<th>OTA Status</th>' +
                '<th>Request Status</th>' +
                '<th class="text-center">Action</th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>';
            $.each(d.ota, function (i, e) {
                    tbl += '<tr class="action-request" data-id="' + e.id + '" data-product="' + d.id_product + '">' +
                        '<td>' + e.ota_name + '</td>';
                    if (e.ota_status === 0) {
                        tbl += '<td>Innactive</td>';
                    } else {

                        tbl += '<td>Active</td>';
                    }
                    if (parseInt(e.pivot.status) === 0) {
                        tbl += '<td>Pending</td>';
                        tbl += '<td class="text-center"><button data-action="approve" class="btn btn-sm btn-outline-success">Approve</button> <button data-action="reject" class="btn btn-sm btn-outline-danger">Reject</button></td>' +
                            '</tr>';
                    } else {
                        if (parseInt(e.pivot.status) === 1) {
                            tbl += '<td>Active</td>';
                            tbl += '<td class="text-center"><button data-action="reject" class="btn btn-sm btn-outline-danger">Reject</button></td>' +
                                '</tr>';
                        } else {
                            tbl += '<td>Rejected</td>';
                            tbl += '<td class="text-center"><button data-action="approve" class="btn btn-sm btn-outline-success">Approve</button></td>' +
                                '</tr>';
                        }
                    }


                }
            )
            ;

            tbl += '</tbody></table>';
            return tbl;
        }

        $(document).on('click', 'tr.action-request button.btn-outline-success', function (e) {
            e.preventDefault();
            let id = $(this).closest('tr').data('id');
            let product = $(this).closest('tr').data('product');
            let status = $(this).data('action');
            loadingStart();
            $.ajax({
                url: '{{route('admin:ota.list.update-status')}}',
                data: {id, status, product},
                dataType: 'json',
                success: function (data) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        location.reload();
                    })
                    loadingFinish();
                },
                error: function (e) {
                    toastr.error(data.message);
                    setTimeout(function () {
                        location.reload();
                    })
                    loadingFinish();
                }
            })
        });

        $(document).on('submit', 'form#form-reject', function (e) {
            e.preventDefault();
            let modal = $('#modal-reject');
            let data = {
                id: modal.find('input[name=id]').val(),
                status: modal.find('input[name=status]').val(),
                product: modal.find('input[name=product]').val(),
                reject_reason: modal.find('textarea[name=reject_reason]').val(),
                reject_reason_en: modal.find('textarea[name=reject_reason_en]').val(),
            };
            modal.find('label.error').remove();
            loadingStart();

            $.ajax({
                url: '{{route('admin:ota.list.update-status')}}',
                data,
                dataType: 'json',
                success: function (data) {
                    toastr.success(data.message);
                    setTimeout(function () {
                        location.reload();
                    })
                    loadingFinish();
                },
                error: function (e) {
                    loadingFinish();
                    toastr.error(e.responseJSON.message);
                    if (e.status === 422) {
                        $.each(e.responseJSON.errors, function (i, e) {
                            modal.find('textarea[name=' + i + ']').closest('.form-group').append('<label class="error">' + e[0] + '</label>')
                        })
                    }
                    // setTimeout(function () {
                    //     location.reload();
                    // })

                }
            })
        });

        $(document).on('click', 'tr.action-request button.btn-outline-danger', function (e) {
            e.preventDefault();
            let id = $(this).closest('tr').data('id');
            let product = $(this).closest('tr').data('product');
            let modal = $('#modal-reject');
            modal.find('input[name=id]').val(id);
            modal.find('input[name=product]').val(product);
            modal.find('textarea').val('');
            modal.find('label.error').remove();
            modal.modal();

        });

        $('#dt').on('click', 'td.details-control .btn-detail', function () {
            var tr = $(this).closest('tr');
            var row = dt.api().row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                $(this).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');

                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                $(this).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

    </script>
@stop
