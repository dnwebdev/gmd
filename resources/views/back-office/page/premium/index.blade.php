@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Premium</h3>
        </div>

    </div>
@stop
@section('styles')
    <style>
        th {
            padding: 5px 40px 5px 5px;
        }
        .comma{
            width: 1px;
        }
    </style>

@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    {{-- <button id="btn-add" type="button" class="btn btn-sm btn-brand"><i class="fa fa-plus"></i> Add Premium</button> --}}
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
                                    Category Ads
                                </th>
                                <th>
                                    Order Date
                                </th>
                                <th>
                                    Company Name
                                </th>
                                <th>
                                    No Invoice
                                </th>
{{--                                <th>--}}
{{--                                    Start Date - End Date--}}
{{--                                </th>--}}
                                <th>
                                    Status
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
            {!! Form::open(['url'=>route('admin:premium.premium.update'),'style'=>'width:100%']) !!}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Premium</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::hidden('id') !!}
                        {!! Form::label('status','Status') !!}
                        {{-- {!! Form::text('status',['class'=>'form-control','id'=>'status','autocomplete'=>'off']) !!} --}}
                        {{-- {!! Form::select('status', array(0 => 'Unpaid', 1 => 'Active', 2 => 'Inactive'), null, ['placeholder' => 'Pilih status', 'class' => 'form-control']) !!} --}}
                        {{-- <select name="status" id="status" class="form-control">
                            <option value="" selected disabled>Pilih Status</option>
                            <option value="0">Unpaid</option>
                            <option value="1">Paid</option>
                            <option value="2">Active</option>
                            <option value="3">Inactive - Extend Now</option>
                            <option value="4">Cancel by System</option>
                        </select> --}}

                        <select name="status" id="status_active" class="form-control">
                            <option value="" selected disabled>Pilih Status</option>
                            {{-- <option value="0">Unpaid</option> --}}
                            <option value="1">Paid</option>
                            <option value="2">Active</option>
                            {{-- <option value="3">Inactive - Extend Now</option> --}}
                            {{-- <option value="4">Cancel by System</option> --}}
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" id="btn-update">Save</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

{{--    <div class="modal fade" id="modal-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"--}}
{{--         aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLongTitle">Detail Premium</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-6">--}}
{{--                            <table cellpadding="5" cellspacing="0">--}}
{{--                                <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>No Invoice</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="no_invoice"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Payment Method</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="payment_method"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Minimum Budget</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="min_budget"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Sub Total</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="amount"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Service Fee</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="service_fee"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Voucher</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="voucher_amount"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Gxp</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="gxp_amount"></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td>Total Price</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td id="total_price"></td>--}}
{{--                                    </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        <div class="col-6">--}}
{{--                            <table cellpadding="5" cellspacing="0">--}}
{{--                                <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>No Invoice</td>--}}
{{--                                        <td class="comma">:</td>--}}
{{--                                        <td>dsadsad</td>--}}
{{--                                    </tr>--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
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
                url: "{{route('admin:premium.premium.data')}}",
                type: "GET",
            },
            "columns": [
                {
                    "data": "DT_RowIndex", "orderable": false, "searchable": false
                },
                {
                    "data": "category_ads", "orderable": false, "searchable": true
                },
                {
                    "data": "created_at", "orderable": false, "searchable": true
                },
                {
                    "data": "company", "orderable": false, "searchable": true
                },
                {
                    "data": "no_invoice", "orderable": false, "searchable": true
                },
                // {
                //     "data": "date", "orderable": false, "searchable": false
                // },
                {
                    "data": "status", "orderable": true, "searchable": true, render: function(data){
                        if(data == 0){
                            return '<span class="btn btn-danger btn-sm">Unpaid</span>'
                        } else if (data == 1) {
                            return '<span class="btn btn-success btn-sm">Paid</span>'
                        } else if (data == 2) {
                            return '<span class="btn btn-info btn-sm">Active</span>'
                        } else if (data == 3) {
                            return '<span class="btn btn-primary btn-sm">Inactive - extend now</span>'
                        } else if (data == 4) {
                            return '<span class="btn btn-danger btn-sm">Cancel By System</span>'
                        } else {
                            return '<span class="btn btn-danger btn-sm">-</span>'
                        }
                    }
                },
                {
                    "data": "action", "orderable": false, "searchable": false,
                    "class": "text-center"
                },
            ],
            order: [[1, "desc"]],
        });
        $(document).on('click', '#btn-preview', function () {
            let btn = $(this);
            let modal = $('#modal-detail');
            // modal.find('input[name=id]').val(btn.data('id'))
            // modal.find('#status_active').val(btn.data('status'))
            // if(btn.data('status') == 1){
            // }
            modal.find('#no_invoice').text(btn.data('no_invoice'))
            modal.find('#payment_method').text(btn.data('payment_method'))
            modal.find('#min_budget').text('IDR ' + btn.data('min_budget'))
            modal.find('#amount').text('IDR ' + btn.data('amount'))
            modal.find('#service_fee').text('IDR ' + btn.data('service_fee'))
            modal.find('#gxp_amount').text('- IDR ' + btn.data('gxp_amount'))
            modal.find('#voucher_amount').text('- IDR ' + btn.data('voucher_amount'))
            modal.find('#total_price').text('IDR ' + btn.data('total_price'))

            modal.modal();
        });

        $(document).on('click', '#btn-edit', function () {
            let btn = $(this);
            let modal = $('#modal-edit');
            modal.find('input[name=id]').val(btn.data('id'))
            modal.find('#status_active').val(btn.data('status'))
            // if(btn.data('status') == 1){
            // }
            modal.modal();
        });

        $(document).on('click', '#btn-update', function () {
            $('.loading').addClass('show');
        });
    </script>
@stop