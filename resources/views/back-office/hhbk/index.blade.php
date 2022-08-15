@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">HHBK Products</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-6">
                    {!! Form::open(['files'=>true,'url'=>route('admin:hhbk.import')]) !!}
                    <div class="form-group">
                        <label for="upload list product baru">Import From Excell <a href="/example/hhbk.xlsx"> <i>Contoh file</i></a></label>
                        {!! Form::file('upload',['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-primary">Upload</button>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="col-6 align-self-end">
                    <div class="d-flex justify-content-end">
                        <a href="{{route('admin:hhbk.add')}}" class="btn btn-sm btn-primary" type="button"><i class="fa fa-plus"></i> Tambah</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <th>COMPANY</th>
                            <th>CITY</th>
                            <th>DESCRIPTION</th>
                            <th>UPLOADED AT</th>
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
            {!! Form::open(['url'=>route('admin:hhbk.delete'),'style'=>'width:100%','id'=>'form-edit','files'=>true]) !!}
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
                url: "{{route('admin:hhbk.data')}}",
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
                    "data": "product_name"
                },
                {
                    "data": "company.domain_memoria", render: function (data) {
                        if (data) {
                            return data
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    "data": "city"
                },
                {
                    "data": "product_description", render: function (data) {
                        if (data) {
                            return data
                        } else {
                            return '-';
                        }
                    }
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
        $(document).on('click', '.btn-delete', function(){
            let modal = $('#modal-delete');
            let $this = $(this);
            modal.find('input[name=id]').val($this.data('id'));
            modal.modal();
        });
    </script>
@stop