@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Career</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    <a id="btn-add"  href="{{route('admin:directory.career.create')}}" class="btn btn-sm btn-brand"><i class="fa fa-plus"></i> Add Career</a>
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
                                    Location
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Total Applicants
                                </th>
                                <th>
                                    Active
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
            {!! Form::open(['url'=>route('admin:directory.career.delete'),'style'=>'width:100%','files'=>true]) !!}
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
          url: "{{route('admin:directory.career.data')}}",
          type: "GET",
        },
        "columns": [
          {
            "data": "DT_RowIndex", "orderable": false, "searchable": false
          },
          {
            "data": "location", "orderable": true, "searchable": true
          },
          {
            "data": "title", "orderable": true, "searchable": true
          },
          {
            "data": "applicants_count", "orderable": false, "searchable": false
          },
          {
            "data": "active", render: function(data){
              if(data === '1'){
                return '<span class="btn btn-success btn-sm">Publish</span>'
              }
              return '<span class="btn btn-danger btn-sm">Not Publish</span>'

            }
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
        modal.find('.name').text(btn.data('name'));
        modal.modal();
      });

    </script>
@stop
