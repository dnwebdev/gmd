@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Gomodo Updates</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    <a id="btn-add" href="{{route('admin:updates.add')}}" class="btn btn-sm btn-brand"><i
                                class="fa fa-plus"></i> Add Updates</a>
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
                                    Title
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Provide By
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
                url: "{{route('admin:updates.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "title", "orderable": true, "searchable": true
                },
                {
                    "data": "type", "orderable": true, "searchable": false
                },
                {
                    "data": "admin.admin_name"
                },
                {
                    "data": "action",
                    "class": "text-center"
                }
            ],
            order: [[1, "asc"]],
        });
    </script>
@stop