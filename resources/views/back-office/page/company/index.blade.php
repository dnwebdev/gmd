@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Provider</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row">
                <div class="col-12 text-right mb-3">
                    <button class="btn btn-outline-primary m-btn m-btn--icon" id="btn-export">
                        <span>
                            <i class="fa flaticon-download"></i>
                            <span>Download</span>
                        </span>
                    </button>
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
                                    Company Name
                                </th>
                                <th>
                                    Domain Gomodo
                                </th>
                                <th>
                                    Ownership Status
                                </th>
                                <th>
                                    Provider Status
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
    <div class="modal fade" id="modal-export" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            {!! Form::open(['url'=>route('admin:providers.export'),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Export Data Provider<span
                                class="name"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group m-form__group row">
                        <label class="col-form-label col-lg-3 col-sm-12">Has Successful Transaction</label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <div class="m-checkbox-list mt-3">
                                <label class="m-checkbox">
                                    <input type="checkbox" name="hasSuccessfulTransaction"> Ya
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row m--margin-top-20 d-none trans">
                        <label class="col-form-label col-lg-3 col-sm-12">Date Successful Transaction</label>
                        <div class="col-lg-6 col-md-9 col-sm-12">
                            <input value="03/01/2019 - {{\Carbon\Carbon::now()->format('m/d/Y')}}" type="text" class="form-control" id="daterange" name="range" readonly="" placeholder="Select time">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-do-submit" id="btn-do-export">Export</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
          url: "{{route('admin:providers.data')}}",
          type: "GET",
        },
        "columns": [
          {
            "data": "DT_RowIndex", "orderable": false, "searchable": false
          },
          {
            "data": "company_name"
          },
          {
            "data": "domain_memoria"
          },
          {
            "data": "ownership_status"
          },
          {
            "data": "status"
          },
          {
            "data": "action",
            "class": "text-center"
          }
        ],
        order: [[1, "asc"]],
      });

      $(document).on('click', '#btn-export', function () {
        $('#modal-export').modal();
      })
      $(function() {

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

      $(document).on('change','input[type=checkbox]', function () {
        let t = $(this);
        if (t.is(':checked')){
          $('.trans').removeClass('d-none')
        }else{
          $('.trans').addClass('d-none')
        }
      });
    </script>
@stop
