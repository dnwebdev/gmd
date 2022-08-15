@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Association</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    <button id="btn-add" type="button" class="btn btn-sm btn-brand"><i class="fa fa-plus"></i> Add Association</button>
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
                                    Association Name
                                </th>
                                <th>
                                   Association Logo
                                </th>
                                <th>
                                    Total Member
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
            {!! Form::open(['url'=>route('admin:master.association.delete'),'style'=>'width:100%','files'=>true]) !!}
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

    <div class="modal fade" id="modal-add-provider"  role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::open(['url'=>route('admin:master.association.save-provider'),'style'=>'width:100%','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Assign to Association : <span class="name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {!! Form::label('search','Search Provider') !!}
                        {!! Form::select('provider_id',[],null,['class'=>'form-control','id'=>'provider_id','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {!! Form::label('membership_id','Membership Id') !!}
                        {!! Form::text('membership_id',null,['class'=>'form-control','id'=>'membership_id','autocomplete'=>'off']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button class="btn btn-primary" id="btn-do-add-provider">Yes</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal fade" id="modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:master.association.save'),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add new Association</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                    {!! Form::label('association_name','Association Name') !!}
                    {!! Form::text('association_name',null,['class'=>'form-control','id'=>'association_name','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('association_description','Association Description') !!}
                        {!! Form::textarea('association_desc',null,['class'=>'form-control','id'=>'association_desc','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('association_logo','Association Logo') !!}
                        {!! Form::file('association_logo',['class'=>'form-control','id'=>'association_logo','autocomplete'=>'off','accept'=>'image/*']) !!}
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
            {!! Form::open(['url'=>route('admin:master.association.update'),'style'=>'width:100%','id'=>'form-edit','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Addon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {!! Form::label('association_name','Association Name') !!}
                        {!! Form::text('association_name',null,['class'=>'form-control','id'=>'association_name','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('association_desc','Association Description') !!}
                        {!! Form::textarea('association_desc',null,['class'=>'form-control','id'=>'association_desc','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('association_logo','Association Logo') !!}
                        {!! Form::file('association_logo',['class'=>'form-control','id'=>'association_logo','autocomplete'=>'off','accept'=>'image/*']) !!}
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
                url: "{{route('admin:master.association.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "association_name", "orderable": true, "searchable": true
                },
                {
                    "data": "association_logo", "orderable": false, "searchable": false
                },
                {
                    "data": "companies_count", "orderable": false, "searchable": false
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
        });

        $(document).on('click','.btn-add-provider', function () {
            let btn = $(this);
            let modal = $('#modal-add-provider');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('.name').text(btn.data('name'))
            initSelect2(btn.data('id'))
            modal.modal();
        });
        $(document).on('click','.btn-preview', function () {
            let btn = $(this);
            console.log(btn.data('name'))
            let modal = $('#modal-edit');
            modal.find('input[name=id]').val(btn.data('id'));
            modal.find('input[name=association_name]').val(btn.data('name'))
            modal.find('textarea[name=association_desc]').val(btn.data('desc'))
            $('form#form-edit').validate({
                rules:{
                    association_name:{
                        required:true
                    },
                    errorElement: "label"
                }
            });
            modal.modal();
        });
        $(document).on('click','#btn-add', function () {
            let btn = $(this);
            let modal = $('#modal-add');
            $('form#form-add').validate({
                rules:{
                    association_name:{
                        required:true
                    },
                    association_logo:{
                        required:true
                    },
                    errorElement: "label"
                }
            })
            modal.modal();
        })

        function initSelect2(id){
            let url = "{{ route('admin:master.association.search-provider') }}"+'?id='+id;
            console.log(url);
            $('select[name=provider_id]').select2({
                width:'100%',
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    type: 'GET',
                    data: function (params) {
                        console.log(params)
                        return {
                            q: params.term,
                            id:$('#modal-add-provider').find('input[name=id]').val(),
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.items,
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                },

                minimumInputLength: 3,
                templateResult: formatRepo2,
                templateSelection: formatRepoSelection2
            })
        }


        function formatRepo2(repo) {
            if (repo.loading) {
                return 'Mencari data provider';
            }

            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__avatar'>" + repo.id + "</div>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.text + "</div>" +
                "</div>";

            return markup;
        }

        function formatRepoSelection2(repo) {
            if (repo.company_name) {
                return repo.company_name
            } else {
                return repo.text;
            }

        }

    </script>
@stop