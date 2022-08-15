@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Approved KYC</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
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
                                    Company Name
                                </th>
                                <th>
                                    Company Ownership
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
                url: "",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "company.company_name"
                },
                {
                    "data": "company.ownership_status"
                },
                {
                    "data": "action",
                    "class": "text-center"
                }
            ],
            order: [[1, "asc"]],
        });

        $(document).on('click', '.btn-preview', function () {
            let t = $(this);
            if ($('tr#child-'+t.data('id')).length===0){

                $.ajax({
                    url: "{{URL::route('admin:kyc.detail')}}",
                    data: {id: t.data('id')},
                    dataType: 'json',
                    success: function (data) {
                        let tb = '<tr id="child-' + t.data('id') + '">'
                        tb += '<td colspan="4">';
                        tb += '<table class="table">';
                        tb += '<thead>';
                        tb += '<tr>';
                        tb += '<th>KYC';
                        tb += '</th>';
                        tb += '<th> Value';
                        tb += '</th>';
                        tb += '</th>';
                        tb += '<th class="text-center"> Action';
                        tb += '</th>';
                        tb += '</tr>';
                        tb += '</thead>';
                        $.each(data.result, function (i, e) {
                            tb += '<tr>';
                            tb += '<td>' + e.name;
                            tb += '</td>';
                            if(e.type ==='image'){

                              if (e.origin !== null){

                                tb += '<td width="200"> <img class="img-fluid" src="' + e.url+'">';
                                tb += '</td>';
                              }else{
                                tb += '<td width="200"> -';
                                tb += '</td>';
                              }

                            }else{
                                tb += '<td>' + e.url;
                                tb += '</td>';
                            }


                            if (e.type==='image') {
                              if (e.origin !== null){

                                tb += '<td  class="text-center">' + '<a href="' + "{{route('admin:kyc.download')}}" + '?url=' + e.origin + '"><i class="fa fa-download"></i><a/>';
                                tb += '</td>';
                              }else{
                                tb += '<td  class="text-center">';
                                tb += '</td>';
                              }
                            }else{
                                tb += '<td  class="text-center">';
                                tb += '</td>';
                            }
                            tb += '</td>';
                            tb += '</tr>';
                        })

                        tb += '</table>';
                        tb += '</td>';
                        tb += '</tr>';
                        $(tb).insertAfter(t.closest('tr'));
                        t.find('i.fa').removeClass('flaticon-visible').addClass('flaticon-close')


                    },
                    error: function (e) {

                    }
                })
            }else{
                $('tr#child-'+t.data('id')).remove();
                t.find('i.fa').removeClass('flaticon-close').addClass('flaticon-visible')
            }
        });
    </script>
@stop
