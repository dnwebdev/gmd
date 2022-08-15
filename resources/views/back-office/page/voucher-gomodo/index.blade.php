@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Promo Code General</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            last week
                        </div>
                        <div class="card-body">
                            <h2>{{format_priceID($reimburseVoucherlastWeek)}}</h2>
                        </div>
                    </div>

                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            Current Week
                        </div>
                        <div class="card-body">
                            <h2>{{format_priceID($reimburseVoucherthisWeek)}}</h2>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12 mb-3 text-right">
                    <a id="btn-add" href="{{route('admin:voucher-gomodo.add')}}" class="btn btn-sm btn-brand"><i
                                class="fa fa-plus"></i> Add Promo Code</a>
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
                                    Provider
                                </th>
                                <th>
                                    Code
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Used
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
          url: "{{route('admin:voucher-gomodo.data')}}",
          type: "GET",
        },
        "columns": [
          {
            "data": "DT_RowIndex", "orderable": false, "searchable": false
          },
          {
            "data": "company.company_name", "orderable": true, "searchable": true
          },
          {
            "data": "voucher_code", "orderable": true, "searchable": true
          },
          {
            "data": "voucher_amount", "orderable": true, "searchable": true, render: function(data){
              return parseFloat(data).formatMoney(0, '', '.', '', true);
            }
          },
          {
            "data": "voucher_amount_type", "orderable": true, "searchable": false, render: function(data){
              if (data === 0) {
                return 'Fixed Amount'
              }
              return 'Percentage'
            }
          },
          {
            "data": "order_paid_count", "orderable": false, "searchable": false
          },
          {
            "data": "action",
            "class": "text-center"
          }
        ],
        order: [[1, "asc"]],
      });
      $(document).on('click', '.btn-delete', function(){
        let btn = $(this);
        let modal = $('#modal-delete');
        modal.find('input[name=id]').val(btn.data('id'));
        modal.find('.name').text(btn.data('name'))
        modal.modal();
      })
      $(document).on('click', '.btn-enable', function(){
        let btn = $(this);
        $.ajax({
          url: '{{route('admin:voucher-gomodo.enable')}}',
          type: 'POST',
          dataType: 'json',
          data: { id: btn.data('id') },
          success: function(data){
            dt.api().ajax.reload(null, false);
            toastr.success('Yeay', data.message)
          },
          error: function(e){
            dt.api().ajax.reload(null, false);
            toastr.error( e.responseJSON.message,'{{__('general.ops')}}')
          }
        })
      })
      $(document).on('click', '.btn-disable', function(){
        let btn = $(this);
        $.ajax({
          url: '{{route('admin:voucher-gomodo.disable')}}',
          type: 'POST',
          dataType: 'json',
          data: { id: btn.data('id') },
          success: function(data){
            dt.api().ajax.reload(null, false);
            toastr.success('Yeay', data.message)
          },
          error: function(e){
            dt.api().ajax.reload(null, false);
            toastr.error(e.responseJSON.message,'{{__('general.whoops')}}')
          }
        })
      })
    </script>
@stop
