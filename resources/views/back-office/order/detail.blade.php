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

                                </ul>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="m_tabs_6_1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="row">
                                                <div class="col-md-6">Invoice : <span
                                                            class="bold"><strong>{{$order->invoice_no}}</strong></span>
                                                </div>
                                                <div class="col-md-6 text-md-right">{!! renderStatusOrder($order) !!}</div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')

@stop
