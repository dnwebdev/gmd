@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Insurance List</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    {{--                    <button id="btn-add" type="button" class="btn btn-sm btn-brand"><i class="fa fa-plus"></i> Add--}}
                    {{--                        Insurance--}}
                    {{--                    </button>--}}
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
                                    Insurance Name (Ind)
                                </th>
                                <th>
                                    Insurance Name (Eng)
                                </th>
                                <th>
                                    Status
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
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>$url_delete,'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::hidden('id') !!}
                    <p>Are You Sure want to delete : <span class="category_name_ind"></span>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button class="btn btn-primary" id="btn-do-delete">Yes</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="modal fade" id="modal-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>$url_update,'style'=>'width:100%','id'=>'form-status']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are You sure want to update insurance status ?</p>
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {!! Form::hidden('status') !!}
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save</button>
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
                url: "{{$url_data}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "insurance_name_id", "orderable": true, "searchable": true
                },
                {
                    "data": "insurance_name_en", "orderable": true, "searchable": true
                },
                {
                    "data": "status", "orderable": true, "searchable": true
                },
                {
                    "data": "action",
                    "class": "text-center"
                }
            ],
            order: [[1, "asc"]],
        });
        $(document).on('click', '.btn-delete', function () {
            let btn = $(this);
            let modal = $('#modal-delete');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('.category_name_ind').text(btn.data('category_name_ind'));
            modal.modal();
        });

        $(document).on('click', '.btn-set-status', function () {
            let btn = $(this);
            let modal = $('#modal-status');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('input[name=status]').val(btn.data('status'));
            modal.modal();
        });

        {{--$(document).on('submit', '#form-status', function (e) {--}}
        {{--    e.preventDefault();--}}
        {{--    let action = $(this).attr('action');--}}
        {{--    let f = $(this);--}}
        {{--    $.ajax({--}}
        {{--        url: action,--}}
        {{--        type: 'post',--}}
        {{--        dataType: 'json',--}}
        {{--        data: {id: f.find('input[name=id]'), status: f.find('input[name=status]')},--}}
        {{--        success: function (data) {--}}
        {{--            dt.api().ajax.reload(null, false);--}}
        {{--            toastr.success('Yeay', data.message)--}}
        {{--        },--}}
        {{--        error: function (e) {--}}
        {{--            dt.api().ajax.reload(null, false);--}}
        {{--            toastr.error(e.responseJSON.message, '{{__('general.whoops')}}')--}}
        {{--        }--}}
        {{--    })--}}
        {{--})--}}
    </script>
@stop