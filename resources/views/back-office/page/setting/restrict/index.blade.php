@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Restrict Sub Domain</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row">
                <div class="col-12 text-right mb-3">
                    <button class="btn btn-sm btn-primary" id="btn-add"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
            <div class="row ">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table" id="dt">
                            <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    Sub Domain
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
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            {!! Form::open(['url'=>route('admin:setting.restrict.save'),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Restrict Subdomain<span
                                class="name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="form-group m-form__group row m--margin-top-20">
                            <label>Subdomain</label>
                            {!! Form::text('subdomain',null,['class'=>'form-control']) !!}                        </div>
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
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            {!! Form::open(['url'=>route('admin:setting.restrict.update'),'style'=>'width:100%','id'=>'form-edit','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Restrict Subdomain<span
                                class="name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="form-group m-form__group row m--margin-top-20">
                            <label>Subdomain</label>
                            {!! Form::hidden('id',null,['class'=>'form-control']) !!}
                            {!! Form::text('subdomain',null,['class'=>'form-control']) !!}
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
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            {!! Form::open(['url'=>route('admin:setting.restrict.delete'),'style'=>'width:100%','id'=>'form-edit','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Delete Restrict Subdomain<span
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
      let dt = $('#dt').dataTable({
        "processing": true,
        "serverSide": true,
        "stateSave": true,
        "responsive": true,
        "ajax": {
          url: "{{route('admin:setting.restrict.data')}}",
          type: "GET",
        },
        "columns": [
          {
            "data": "DT_RowIndex", "orderable": false, "searchable": false
          },
          {
            "data": "subdomain"
          },
          {
            "data": "action",
            "class": "text-center"
          }
        ],
        order: [[1, "asc"]],
      });
      $(document).on('click', '#btn-add', function(){
        let modal = $('#modal-add');
        modal.modal();
      })
      $(document).on('click', '.btn-delete', function(){
        let modal = $('#modal-delete');
        let $this = $(this);
        modal.find('input[name=id]').val($this.data('id'));
        modal.modal();
      });
      $(document).on('click', '.btn-preview', function(){
        let modal = $('#modal-edit');
        let $this = $(this);
        modal.find('input[name=id]').val($this.data('id'));
        modal.find('input[name=subdomain]').val($this.data('name'));
        modal.modal();
      });

      $(document).on('submit', '#form-add, #form-edit,#form-delete', function(e){
        e.preventDefault();
        let $this = $(this);
        $this.find('label.error').remove()
        let data = $(this).serialize();
        $.ajax({
          url: $this.attr('action'),
          type: "POST",
          data: data,
          success: function(data){
            location.reload();
          },
          error: function(err){
            if (err.status === 422) {
              $this.find('.form-group.m-form__group').append('<label class="error">' + err.responseJSON.errors.subdomain[0] + '</label>')
            } else {
              $this.find('.form-group.m-form__group').append('<label class="error">' + err.responseJSON.message + '</label>')
            }
          }
        })
      })
    </script>
@stop
