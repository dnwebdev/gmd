@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Product Tag</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    <button id="btn-add" type="button" class="btn btn-sm btn-brand"><i class="fa fa-plus"></i> Add Product Tag</button>
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
                                    Tag Name (english)
                                </th>
                                <th>
                                    Tag Name (Ind)
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
            {!! Form::open(['url'=>route('admin:master.product-tag.delete'),'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::hidden('id') !!}
                    <p>Are You Sure want to delete : <span class="name"></span>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button class="btn btn-primary" id="btn-do-delete">Yes</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:master.product-tag.save'),'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add new Product Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    {!! Form::label('name','Name (En)') !!}
                    {!! Form::text('name',null,['class'=>'form-control','id'=>'name','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_ind','Name (Indo)') !!}
                        {!! Form::text('name_ind',null,['class'=>'form-control','id'=>'name_ind','autocomplete'=>'off']) !!}
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="btn-do-delete">Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:master.product-tag.update'),'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Product Tag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {!! Form::label('name','Name (En)') !!}
                        {!! Form::text('name',null,['class'=>'form-control','id'=>'name','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('name_ind','Name (Indo)') !!}
                        {!! Form::text('name_ind',null,['class'=>'form-control','id'=>'name_ind','autocomplete'=>'off']) !!}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="btn-do-delete">Save</button>
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
                url: "{{route('admin:master.product-tag.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "name", "orderable": true, "searchable": true
                },
                {
                    "data": "name_ind", "orderable": true, "searchable": true
                },
                {
                    "data": "action",
                    "class": "text-center"
                }
            ],
            order: [[1, "asc"]],
        });
        $(document).on('click','.btn-delete', function () {
            let btn = $(this);
            let modal = $('#modal-delete');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('.name').text(btn.data('name'))
            modal.modal();
        })
        $(document).on('click','.btn-preview', function () {
            let btn = $(this);
            let modal = $('#modal-edit');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('input[name=name]').val(btn.data('name'))
            modal.find('input[name=name_ind]').val(btn.data('nameindo'))
            modal.modal();
        })
        $(document).on('click','#btn-add', function () {
            let btn = $(this);
            let modal = $('#modal-add');
            modal.modal();
        })
    </script>
@stop