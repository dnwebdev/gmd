@extends('klhk.dashboard.company.base_layout')

@section('title', __('sidebar_provider.order'))

@section('additionalStyle')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('klhk-asset/css/dataTablesEdit.css')}}">
    <link href="{{ asset('klhk-asset/dest-operator/css/order_provider_klhk.css') }}" rel="stylesheet">
    <style>
        .dtr-inline.collapsed tbody tr td:first-child:before, .dtr-inline.collapsed tbody tr th:first-child:before, .dtr-column tbody tr td.control:before, .dtr-column tbody tr th.control:before {
            top: unset!important;
            transform: unset!important;
        }
    </style>
@endsection

@section('breadcrumb')
@stop

@section('indicator_order')
    active
@stop

@section('content')
<!-- Page header -->
<div data-template="main_content_header">
    <div class="page-header" style="margin-bottom: 0;">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title">
                <h5>
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('sidebar_provider.order') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('sidebar_provider.order') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page header -->

<!-- main content -->
<div class="content pt-0" dashboard>
    
    <!-- Gamification -->
    <div data-template="gamification-modal">@include('klhk.dashboard.company.gamification-modal')</div>
    <!-- /gamification -->
    <!-- KYC-Gamification -->
    <div data-template="kyc-gamification">@include('klhk.dashboard.company.kyc-gamification')</div>
    <!-- /kyc-gamification -->
    <!-- Banner Sugestion -->
    <div data-template="banner-sugetion"></div>
    <!-- /banner Sugestion -->
    <div data-template="widget">

        <div class="row">
            <div class="col">
                <div class="dashboard-cta" style="display: none">
                    <a href="{{ Route('company.order.create') }}" class="btn btn-primary btn-cta">New Order</a>
                </div>
                {{-- Order Form --}}
                <div class="widget card" id="order">
                    <div class="widget-header">
                        <h3>{{ trans('order_provider.list_of_order') }}</h3>
                        <button class="btn btn-export">{{ trans('order_provider.export') }}</button>
                    </div>
                    <div class="widget-content widget-full">
                        <div class="widget-table">
                            <div class="responsive-table">
                                <table class="table-border-bottom tablesorter" id="order_table">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('order_provider.order_date') }}</th>
                                        <th>{{ trans('order_provider.invoice_no') }}</th>
                                        <th>{{ trans('order_provider.product') }}</th>
                                        <th>{{ trans('order_provider.customer') }}</th>
                                        <th>{{ trans('order_provider.total_price') }}</th>
                                        <th>{{ trans('order_provider.date') }}</th>
                                        <th>{{ trans('order_provider.status') }}</th>
                                        <th>{{ trans('general.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Export Form --}}
                <div class="widget card display-none" id="export-form">
                    <div class="widget-content widget-full">
                        <div class="modal-header">
                            <h3>{{ trans('order_provider.export') }}</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {!! Form::open(['method'=>'get','url'=>route('company.order.export')]) !!}
                            <div class="row">
                                <div class="ml-auto col-xl-auto mb-5">
                                    <label for="format">Format: </label>
                                    <select name="format" id="format" disabled>
                                        <option value="">Ms. Excel</option>
                                    </select>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-xl-auto mr-auto">
                                    {!! Form::hidden('range',null) !!}
                                    <label for="chosedDate">{{ trans('order_provider.filter_date') }} </label>
                                    <div id="exportRange" class="chosedDate col">
                                        <i class="fa fa-calendar"></i>&nbsp;<span></span> <i
                                                class="fa fa-caret-down"></i>
                                    </div>
                                </div>
                                <div class="col-xl-auto">
                                    <label for="status">{{ trans('order_provider.filter_status') }}</label>
                                    <select name="status" id="status">
                                        <option value="">{{ trans('explore-lang.help.all') }}</option>
                                        <option value="1">{{ trans('order_provider.paid') }}</option>
                                        <option value="0">{{ trans('order_provider.not_paid') }}</option>
                                        <option value="7">{{ trans('order_provider.cancel_system') }}</option>
                                        <option value="6">{{ trans('order_provider.cancel_vendor') }}</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="status">Tipe</label>
                                    <select name="type" id="type">
                                        <option value="">{{ trans('explore-lang.help.all') }}</option>
                                        <option value="online">Online Booking</option>
                                        <option value="offline">E-Invoice</option>

                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer mt-4">
                                <button class="btn btn-primary mr-auto ml-auto mb-0">{{ trans('order_provider.download') }}</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- table order list start here -->
    </div>
</div>
<div class="modal fade" id="modalCancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div id="search_result">
        <form class="form-group-no-margin" id="form_ajax_cancel" method="POST" action="{{ route('company.post.cancel-invoice') }}">
            {{ csrf_field() }}
            <div class="modal-dialog modal-dialog-centered modal-xs" role="document">
                {!! Form::hidden('id') !!}
                <div class="modal-content col">
                    <div class="modal-body text-center">
                        {{__('offline_invoice.cancel.modal_desc')}}
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="cancel">{{__('offline_invoice.cancel.no')}}</button>
                        <button class="btn modal-trigger btn-sm btn-success" >{{__('offline_invoice.cancel.yes')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@stop

@section('additionalScript')
    <script src="{{ asset('klhk-asset/dest-operator/lib/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="{{asset('js/daterangepicker.js')}}"></script>

    <!-- Theme JS files -->
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/datatables_responsive.js') }}"></script>

	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/plugins/loaders/progressbar.min.js') }}"></script>

	<script src="{{ asset('klhk-asset/dest-operator/klhk_global_assets/js/demo_pages/components_progress.js') }}"></script>
    <!-- /theme JS files -->
    <script>
        window.$ = window.jQuery;
        let language_url = '{{ asset("json/datatables_english.json") }}';
        @if(app()->getLocale()=='id')
            language_url = '{{ asset("json/datatables_indonesia.json") }}';

                @endif
        let dt = $('#order_table').dataTable(
            {
                "processing": true,
                "serverSide": true,
                "stateSave": true,
                "responsive": true,
                "language": {
                    "url": language_url
                },
                "ajax": {
                    url: "{{Request::fullUrl()}}",
                    type: "GET",
                    // data: function (d) {
                    //     d.status = $('select#status').val();
                    //     d.type = $('select#type').val()
                    // }
                },
                "columns": [
                    {
                        "data": "created_at",'orderable': false
                    },
                    {
                        "data": "invoice_no",'orderable': false
                    },
                    {
                        "data": "order_detail.product.product_name", 'orderable': false,
                    },
                    {
                        "data": "customer_info.first_name", 'orderable': false,
                    },
                    {
                        "data": "amount", "searchable": false,'orderable': false
                    },
                    {
                        "data": "order_detail.schedule_date", "searchable": false,'orderable': false
                    },
                    {
                        "data": "status",'orderable': false,
                        "className": 'data-table-status',
                        render: function(data, type) {
                            if (type === 'display') {
                                let status_class = '';

                                switch(data) {
                                    case "{{ trans('order_provider.paid') }}" : 
                                        status_class = 'paid';
                                        break;
                                    case "{{ trans('order_provider.not_paid') }}" : 
                                        status_class = 'not_paid';
                                        break;
                                    case "{{ trans('order_provider.cancel_system') }}" : 
                                        status_class = 'cancel_system';
                                        break;
                                    case "{{ trans('order_provider.cancel_vendor') }}" : 
                                        status_class = 'cancel_vendor';
                                        break;
                                }
                                return '<span class="badge ' + status_class + '">' + data + '</span>'
                            }
                            return data
                        }
                    },
                    {
                        "data": "action", "searchable": false,'orderable': false
                    },
                ],
                // order: [[3, "desc"]],
            }
            );
        $(document).on('keyup search input paste cut', '#searchbox', function () {
            dt.fnFilter(this.value);
        });
        $(document).on('change', '#status, #type', function () {
            dt.api().ajax.reload(null, false)
        });

        // Daterangepicker
        $(function () {
            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                $('#exportRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('input[name=range]').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'))
            }

            $('#exportRange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    '{{ trans('order_provider.today') }}': [moment(), moment()],
                    '{{ trans('order_provider.yesterday') }}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '{{ trans('order_provider.7_day') }}': [moment().subtract(6, 'days'), moment()],
                    '{{ trans('order_provider.30_day') }}': [moment().subtract(29, 'days'), moment()],
                    '{{ trans('order_provider.month') }}': [moment().startOf('month'), moment().endOf('month')],
                    '{{ trans('order_provider.last_month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: "center",
                locale: {
                    'customRangeLabel': '{{ trans('order_provider.custome_range') }}'
                }
            }, cb);
            cb(start, end);
        });

        // Show/Hide export data form
        $('.btn-export').on('click', function () {
            $('#order').hide();
            $('#export-form').show();
        });
        $('#export-form .close').on('click', function () {
            $('#order').show();
            $('#export-form').hide();
        });

        // Cancel Invoice
        $(document).on('click', '.btn-cancel-invoice', function () {
            let modal = $('#modalCancel');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal();

        })
        $(document).on('submit','form#form_ajax_cancel', function (e) {
            e.preventDefault();
            let modal = $('#modalCancel');
            modal.modal('hide')
            jQuery('.loading').addClass('show');
            let data = {id: $(this).find('input[name=id]').val(),_token:"{{csrf_token()}}"};
            $.ajax({
                type:'POST',
                url:"{{route('company.post.cancel-invoice')}}",
                data,
                dataType:'json',
                success:function (data) {
                    jQuery('.loading').removeClass('show');
                    toastr.success(data.message);
                    setTimeout(function () {
                        location.reload();
                    },1000)
                },
                error:function (e) {
                    jQuery('.loading').removeClass('show');
                    toastr.error(e.responseJSON.message);
                    setTimeout(function () {
                        location.reload();
                    },1000)
                }
            })
        })
    </script>
@stop
