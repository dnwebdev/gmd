@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Insurance Request</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  table-responsive">
            <div class="row ">
                <div class="col-12">
                    <table id="myTable">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>PHONE</th>
                            <th>EMAIL</th>
                            <th>STATUS</th>
                            <th>REQUEST AT</th>
                            <th>ACTION</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            {!! Form::open(['url'=>route('admin:hhbk-distribution.delete'),'style'=>'width:100%','id'=>'form-edit','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete HHBK<span
                                class="name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="form-group m-form__group row m--margin-top-20">
                            <label>Are You sure ?</label>
                            {!! Form::hidden('id',null,['class'=>'form-control']) !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-do-submit" id="btn-do-export">Save</button>
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
        let dt = $('#myTable').dataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "responsive": true,
            "ajax": {
                url: "{{route('admin:insurance-request.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "id"
                },
                {
                    "data": "company.company_name"
                },
                {
                    "data": "phone", render: function (data) {
                        if (data) {
                            return data
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    "data": "email"
                },
                {
                    "data": "status"
                },
                {
                    "data": "created_at"
                },

                {
                    "data": "action",
                    "class": "text-center",
                    "orderable": false,
                    "searchable": false
                }
            ],
            order: [[1, "desc"]],
        });
        $(document).on('click', '.btn-delete', function () {
            let modal = $('#modal-delete');
            let $this = $(this);
            modal.find('input[name=id]').val($this.data('id'));
            modal.modal();
        });
    </script>
@stop