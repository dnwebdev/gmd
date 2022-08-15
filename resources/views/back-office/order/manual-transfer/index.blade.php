@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Order Manual</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row">
                <div class="col-12 text-right mb-3">
                    {{--                    <button class="btn btn-outline-primary m-btn m-btn--icon" id="btn-export">--}}
                    {{--                        <span>--}}
                    {{--                            <i class="fa flaticon-download"></i>--}}
                    {{--                            <span>Download</span>--}}
                    {{--                        </span>--}}
                    {{--                    </button>--}}
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
                                    Date
                                </th>
                                <th>
                                    INVOICE NO
                                </th>
                                <th>
                                    Domain
                                </th>
                                <th>
                                    Total
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Status Payment
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

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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
                url: "{{route('admin:master.transaction-manual.data')}}",
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
                    "data": "invoice_no"
                },
                {
                    "data": "company.domain_memoria"
                },
                {
                    "data": "total_amount"
                },
                {
                    "data": "status"
                },
                {
                    "data": "paymentStatus"
                },
                {
                    "data": "action",
                    "class": "text-center",
                    "orderable":false,
                    "searchable":false
                }
            ],
            order: [[1, "desc"]],
        });

        $(document).on('click', '#btn-export', function () {
            $('#modal-export').modal();
        })
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
    </script>
@stop
