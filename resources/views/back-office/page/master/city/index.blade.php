@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">City</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row mt-3 mb-5">
                <div class="col-md-6">
                    {!! Form::select('country',\App\Models\Country::pluck('country_name','id_country'),102,['class'=>'form-control']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::select('state',[],null,['class'=>'form-control']) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table" id="dt">
                            <thead>
                            <tr>
                                <th>
                                    No
                                </th>
                                <th>
                                    City Name (ID)
                                </th>
                                <th>
                                    City Name (EN)
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
    <div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            {!! Form::open(['url'=>route('admin:master.city.update'),'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Addon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::hidden('id_city') !!}
                        {!! Form::label('city_name_en','City Name (EN)') !!}
                        {!! Form::text('city_name_en',null,['class'=>'form-control','id'=>'add_name','autocomplete'=>'off']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('city_name','City Name (ID)') !!}
                        {!! Form::text('city_name',null,['class'=>'form-control','id'=>'add_name_indo','autocomplete'=>'off']) !!}
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
                url: "{{route('admin:master.city.data')}}",
                type: "GET",
                data: function (d) {
                    d.state = $('select[name=state]').val()
                }
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "city_name", "orderable": true, "searchable": true
                },
                {
                    "data": "city_name_en", "orderable": true, "searchable": true
                },
                {
                    "data": "action",
                    "class": "text-center"
                }
            ],
            order: [[1, "asc"]],
        });
        $(document).on('click', '.btn-preview', function () {
            let btn = $(this);
            console.log(btn.data('name'));
            let modal = $('#modal-edit');
            modal.find('input[name=id_city]').val(btn.data('id'));
            modal.find('input[name=city_name_en]').val(btn.data('name'));
            modal.find('input[name=city_name]').val(btn.data('nameindo'));
            modal.modal();
        })
        $(document).on('change', 'select[name=country]', function () {
            let t = $(this);
            $.ajax({
                url: "{{route('admin:master.city.change.country')}}",
                dataType: "json",
                type: "GET",
                data: {country: t.val()},
                success: function (data) {
                    let html = "";
                    $.each(data.result, function (i, e) {
                        html += `<option value="${i}">${e}</option>`;
                    })
                    $('select[name=state]').html(html)
                    dt.api().ajax.reload()
                }
            })
        });
        $(document).find('select[name=country]').trigger('change')

        $(document).on('change', 'select[name=state]', function () {
            dt.api().ajax.reload()
        });
        $('select').select2();
    </script>
@stop