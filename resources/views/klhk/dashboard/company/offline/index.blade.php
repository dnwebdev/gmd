@extends('klhk.dashboard.company.base_layout')

@section('title')
    E-Invoice
@stop

@section('additionalStyle')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="{{asset('klhk-asset/css/dataTablesEdit.css')}}">
    <style>
        .dataTables_filter {
            display: block;
        }

        .filter-select, .filter-select:focus, .filter-select:active {
            border-color: rgb(243, 243, 243);
            background-color: rgb(243, 243, 243);
            color: black;
            height: 42px;
            font-weight: bold;
            outline: none;
            padding-left: 0;
        }

        [type=search] {
            border: 1px solid rgb(166, 166, 166);
            border-radius: 5px;
            height: 2rem;
            padding-left: .5rem;
        }

        select {
            height: 2rem;
        }

        button.btn.btn-sm.btn-table {
            margin: 0 auto;
            color: white;
        }
        .widget .widget-table table tr td {
            padding: .75rem .5rem;
            color: rgba(0,0,0,.54);
            white-space: nowrap;
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
                    {{-- <i class="icon-pushpin mr-2"></i> --}}  {{ trans('order_provider.order_offline') }}
                    {{-- <small class="d-block text-muted">Basic breadcrumb inside page header</small> --}}
                </h5>
            </div>

            <div class="header-elements py-0">
                <div class="breadcrumb">
                    <a href="{{ Route('company.dashboard') }}" class="breadcrumb-item">
                        <i class="icon-home2 mr-2"></i> {{ trans('sidebar_provider.dashboard') }}
                    </a>
                    <span class="breadcrumb-item active">{{ trans('order_provider.order_offline') }}</span>
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

        {{-- <div class="dashboard-cta">
            <a href="{{ route('company.manual.create') }}"
                class="btn btn-primary btn-cta">{{trans('offline_invoice.create_invoice')}}</a>
        </div> --}}
        <div class="widget card" id="order">
            <div class="widget-header">
                <div class="widget-tools tools-full">
                    <div class="widget-search" style="display: none">
                        <form action="#">
                            <button type="submit" class="btn-search"><span class="fa fa-search"></span>
                            </button>
                            <input type="search" id="q" name="q" class="form-control"
                                    placeholder="Search guests or order.."/>
                        </form>
                    </div>
                </div>
                <div class="list-caption d-flex justify-content-between w-100">
                    <h3>{{ trans('order_provider.list_e_invoice') }}</h3>
                    <a href="{{ route('company.manual.create') }}" class="btn btn-primary btn-create"><i class="icon-plus2 mr-1"></i>{{ trans('sidebar_provider.create_e_invoice') }}</a>
                </div>
            </div>
            <div class="widget-content widget-full">
                <div class="widget-table">
                    <div class="responsive-table">
                        <table class="table-border-bottom tablesorter" id="example">
                            <thead>
                            <tr>
                                <th data-priority="1">{{ trans('order_provider.send_date') }}</th>
                                <th data-priority="2">{{ trans('order_provider.invoice_no') }}</th>
                                <th data-priority="3">{{ trans('order_provider.invoice_title') }}</th>
                                <th data-priority="6">{{__('offline_invoice.resend.customer')}}</th>
                                <th data-priority="4">{{ trans('order_provider.total_price') }}</th>
                                <th data-priority="7">{{ trans('order_provider.due_date') }}</th>
                                <th data-priority="5">{{ trans('order_provider.status') }}</th>
                                <th data-priority="8">{{ __('offline_invoice.resend.action') }}</th>
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
</div>
<div class="modal fade" id="modalResend" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div id="search_result">
        <form class="form-group-no-margin" id="form_ajax_resend" method="POST" action="{{ route('company.post.resend-manual-order') }}">
            {{ csrf_field() }}
            <div class="modal-dialog modal-dialog-centered" role="document">
                {!! Form::hidden('id') !!}
                <div class="modal-content col">
                    <div class="modal-body">
                        {{__('offline_invoice.resend.confirmation')}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-sm btn-danger" data-dismiss="modal" id="cancel">{{ trans('withdraw_provider.cancel') }}</button>
                        <button class="modal-trigger btn-sm btn-success" >{{ trans('offline_invoice.resend.button_table') }}</button>
                    </div>
                </div>
            </div>
        </form>
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
    <script src="{{ asset('dest-operator/lib/js/bootstrap.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script>
        window.$ = window.jQuery;
        let language_url = '{{ asset("json/datatables_english.json") }}';
        @if(app()->getLocale()=='id')
            language_url = '{{ asset("json/datatables_indonesia.json") }}';
                @endif
        let dt = $('#example').dataTable(
            {
                "processing": true,
                "serverSide": true,
                "stateSave": true,
                "responsive": true,
                "language": {
                    "url": language_url
                },
                "ajax": {
                    url: "{{route('company.manual.data')}}",
                    type: "GET",
                },
                "columns": [
                    {
                        "data": "created_at", 'orderable': false
                    },
                    {
                        "data": "invoice_no", 'orderable': false
                    },
                    {
                        "data": "order_detail.product.product_name", 'orderable': false,
                    },
                    {
                        "data": "customer_info.first_name", 'orderable': false,
                    },
                    {
                        "data": "amount", "searchable": false, 'orderable': false
                    },
                    {
                        "data": "payment.expiry_date", "searchable": false, 'orderable': false,
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
                        "data": "action", 'orderable': false, 'searchable': false
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

        $(document).on('click', '.btn-resend', function () {
            let modal = $('#modalResend');
            modal.find('input[name=id]').val($(this).data('id'))
            modal.modal();

        })


        $(document).on('submit','form#form_ajax_resend', function (e) {
            e.preventDefault();
            let modal = $('#modalResend');
            modal.modal('hide')
            jQuery('.loading').addClass('show');
            let data = {id: $(this).find('input[name=id]').val(),_token:"{{csrf_token()}}"};
            $.ajax({
                type:'POST',
                url:"{{route('company.post.resend-manual-order')}}",
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
        });

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