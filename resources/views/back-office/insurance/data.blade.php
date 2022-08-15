@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Data Customer Insurance</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row">
                <div class="col-12 text-right mb-3">
                    <div class="card-header row" id="header">
                        <div class="col-12 text-center">
                            <button class="btn btn-success float-right" data-toggle="modal"
                                    data-target=".modal-download" type="button"><i class="icon-download"></i> &nbsp;Download
                            </button>
                        </div>
                    </div>
                    @include('back-office.insurance.download')
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
                                    Order Date
                                </th>
                                <th>
                                    Schedule Date
                                </th>
                                <th>
                                    Invoice No
                                </th>
                                <th>
                                    Company Name
                                </th>
                                <th>
                                    Total Insurance
                                </th>
                                {{--                                <th>--}}
                                {{--                                    Status--}}
                                {{--                                </th>--}}
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

    <div class="modal fade modal-detail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    apa ya..
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


@stop

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@stop

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        let inputDrp = $('input[name="drp"]');
        let dt = $('#dt').dataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "responsive": true,
            "ajax": {
                url: "{{route('admin:insurance.data-customer.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "created_at"
                },
                {
                    "data": "schedule_date", "searchable": false
                },
                {
                    "data": "invoice_no", "orderable": false
                },
                // {
                //     "data": "company.domain_memoria"
                // },
                {
                    "data": "company_name"
                },
                {
                    "data": "product_insurance"
                },
                // {
                //     "data": "status"
                // },
                {
                    "data": "action",
                    "class": "text-center",
                    "orderable": false,
                    "searchable": false
                }
            ],
            order: [[1, "desc"]],
        });

        dt.on('draw', function () {
            $('[data-popup="tooltip"]').tooltip();
        });

        $(document).on('click', '#btn-export', function () {
            $('#modal-export').modal();
        });
        $(function () {
            var start = moment('2019-03-01 00:00:00');
            var end = moment();

            function cb(start, end) {
                $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

        });

        $(document).on('change', 'input[type=checkbox]', function () {
            let t = $(this);
            if (t.is(':checked')) {
                $('.trans').removeClass('d-none')
            } else {
                $('.trans').addClass('d-none')
            }
        });

        inputDrp.daterangepicker({
            opens: 'left'
        });
        $('input[name="status"]').on('change', function () {
            let checked = $('input[name="status"]').is(':checked');
            if (checked) {
                $('#dateRangPickerDiv').removeClass('d-none');
            } else {
                $('#dateRangPickerDiv').addClass('d-none');
            }
        });
    </script>
@stop
