@extends('back-office.layout.index')
@section('subheader')
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title ">Order : {{$order->invoice_no}}</h3>
        </div>

    </div>
@stop
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__body  ">
            <div class="row ">
                <div class="col-12">
                    <div class="m-portlet m-portlet--tabs">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-tools">
                                <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x"
                                    role="tablist">
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link  active show" data-toggle="tab"
                                           href="#m_tabs_6_1" role="tab"
                                           aria-selected="false">
                                            <i class="la la-shopping-cart"></i> Order
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_2" role="tab"
                                           aria-selected="false">
                                            <i class="la la-cart-arrow-down"></i> Detail Order
                                        </a>
                                    </li>
                                    <li class="nav-item m-tabs__item">
                                        <a class="nav-link m-tabs__link" data-toggle="tab"
                                           href="#m_tabs_6_3" role="tab" aria-selected="true">
                                            <i class="la la-user"></i>Customer
                                        </a>
                                    </li>
                                    @if (optional($order->customer_manual_transfer)->status)
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab"
                                                href="#m_tabs_6_4" role="tab" aria-selected="true">
                                                <i class="la la-user"></i>Detail Manual Transfer
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane  active show" id="m_tabs_6_1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-6">Invoice : <span
                                                            class="bold"><strong>{{$order->invoice_no}}</strong></span>
                                                </div>
                                                <div class="col-md-6 text-md-right">
                                                    @if (optional($order->customer_manual_transfer)->status && $order->status == '0')
                                                        <span class="badge-pill pl-3 pr-3 pt-2 pb-2 badge-pill pl-3 pr-3 pt-2 pb-2 badge-warning">{{ $order->listManualTransfer()[$order->customer_manual_transfer->status] }}</span>
                                                    @else
                                                        {!! renderStatusOrder($order) !!}
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-12">
                                            <table class="table">
                                                <tr>
                                                    <th>Amount</th>
                                                    <td class="text-right">{{format_priceID($order->amount)}}</td>
                                                </tr>
                                                @if($order->fee_credit_card>0)
                                                    <tr>
                                                        <th>Fee Credit Card</th>
                                                        <td class="text-right">
                                                            {{format_priceID($order->fee_credit_card)}}</td>
                                                    </tr>
                                                @endif
                                                @if($order->product_discount>0)
                                                    <tr>
                                                        <th>Discount</th>
                                                        <td class="text-right">
                                                            - {{format_priceID($order->product_discount)}}</td>
                                                    </tr>
                                                @endif
                                                @if($order->voucher_amount>0)
                                                    <tr>
                                                        <th>Voucher</th>
                                                        <td class="text-right">
                                                            - {{format_priceID($order->voucher_amount)}}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Grand Total</th>
                                                    <th class="text-right bold">{{format_priceID($order->total_amount)}}</th>
                                                </tr>
                                            </table>
                                            @if ($order->payment)
                                                <small>Payment method: {{$order->payment->payment_gateway}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="m_tabs_6_2" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            @if($order->booking_type =='online')
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Product Name</th>
                                                        <th class="text-right">Qty</th>
                                                        <th class="text-right">Price</th>
                                                        <th class="text-right">Total</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="text-left">{{$order->order_detail->product_name}}</td>
                                                        <td class="text-right">{{$order->order_detail->adult}}</td>
                                                        <td class="text-right">{{format_priceID($order->order_detail->adult_price)}}</td>
                                                        <td class="text-right">{{format_priceID($order->order_detail->adult_price* $order->order_detail->adult)}}</td>
                                                    </tr>
                                                    </tbody>


                                                </table>
                                            @else
                                                <table class="table">
                                                    <tr>
                                                        <th>Detail</th>
                                                        <th class="text-right">Price</th>
                                                        <th class="text-right">Qty</th>
                                                        <th class="text-right">Total</th>
                                                    </tr>
                                                    @foreach($order->invoice_detail as $detail)
                                                        <tr>
                                                            <td class="text-left">{{$detail['description']}}</td>
                                                            <td class="text-right">{{$detail['qty']}}</td>
                                                            <td class="text-right">{{format_priceID($detail['price'])}}</td>
                                                            <td class="text-right">{{format_priceID($detail['qty'] * $detail['price'])}}</td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table">
                                                <tr>
                                                    <th>Customer name</th>
                                                    <td>{{$order->customer_info->first_name}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Customer Email</th>
                                                    <td>{{$order->customer_info->email}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Customer Phone</th>
                                                    <td>{{$order->customer_info->phone_number}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @if (optional($order->customer_manual_transfer)->status)
                                <div class="tab-pane" id="m_tabs_6_4" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Proof of payment</h3>
                                            <form method="POST">
                                                {{ csrf_field() }}
                                            <table class="table">
                                                <tr>
                                                    <th>Account Owner :</th>
                                                    <td>{{ $order->customer_manual_transfer->bank_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Account number :</th>
                                                    <td>{{ $order->customer_manual_transfer->no_rekening }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Note to customer</th>
                                                    <td>{{ $order->customer_manual_transfer->note_customer ? $order->customer_manual_transfer->note_customer : '-'  }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Image proof :</th>
                                                    <td><img src="{{asset($order->customer_manual_transfer->upload_document ? $order->customer_manual_transfer->upload_document : 'img/no-product-image.png')}}" class="img-fluid" alt=""></td>
                                                </tr>
                                                <tr>
                                                    <th>Status :</th>
                                                    <td>
                                                        <select name="status" class="form-control" id="status">
                                                            <option value="" selected>- Pilih -</option>
                                                            @foreach ($order->changeManualTransfer() as $key => $item)
                                                            <option value="{{ $key }}" {{ $key == $order->customer_manual_transfer->status ? 'selected' : ''}}>{{ $item }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                @if (in_array($order->customer_manual_transfer->status, ['need_confirmed','customer_reupload']))
                                                    <tr>
                                                        <th>
                                                            <button type="button" class="btn btn-sm btn-primary" id="changeStatus">Change Status</button>
                                                        </th>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
        $(document).on('click','#changeStatus', function () {
            loadingStart();
            let t = $(this);
            t.closest('form').find('span.error').remove();
            $.ajax({
                url: '{{ route('admin:master.transaction-manual.status_manual_transfer', ['invoice_no' => $order->invoice_no]) }}',
                type:'post',
                dataType:'json',
                data:t.closest('form').serialize(),
                success:function (data) {
                    loadingFinish();
                    toastr.success(data.message,"Yey");
                    location.reload()
                },
                error:function (e) {
                    if (e.status !== undefined && e.status === 422) {
                        let errors = e.responseJSON.errors;
                        $.each(errors, function (i, el) {
                            t.closest('form').find('input[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                            t.closest('form').find('textarea[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                            t.closest('form').find('select[name=' + i + ']').closest('.form-group').append('<span class="error">' + el[0] + '</span>')
                        })

                    }
                    loadingFinish();
                }
            })
        })
</script>
@stop
