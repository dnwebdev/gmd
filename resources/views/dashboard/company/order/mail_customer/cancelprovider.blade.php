<!DOCTYPE html>
<html>
<head>
<style type="text/css">
table {
    border-collapse:collapse
}
/*.details tr:nth-child(even) {*/
/*    background-color: rgb(239,247,254);*/
/*}*/
.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
.details .fourth span {
    float: right;
}
/* .provider-logo {
    background-color: rgba(0, 0, 0, 0.021);
    color: rgb(104, 104, 104);
    text-align: center;
    padding: 0;
    width: 25%;
    font-weight: bold;
    font-size: 1.2rem;
} */
.padding-price{
    padding-left:  1.2rem!important;
    width: 75%;
}
</style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; width: 680px; margin: auto;">
    <tbody>
{{--        <tr>--}}
{{--            <td colspan="3">--}}
{{--                <table width="100%">--}}
{{--                    <tr>--}}
{{--                        <td colspan="2" class="provider-logo">--}}
{{--                            <img src="{{ asset('img/gomodo.png') }}" height="50" alt="Logo Gomodo">--}}
{{--                        </td>--}}
{{--                        <td style="width: 25%;"><h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2></td>--}}
{{--                    </tr>--}}
{{--                </table>--}}
{{--            </td>--}}
{{--        </tr>--}}

        <tr>
            <td colspan="3">
                <p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $company->company_name }},</p>
                <p>
                    Your order with an invoice number {{ $order->invoice_no }} has been canceled by the system. We have not been able to process your customer order because the payment has not been completed.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Status :</td>
                            @if($order->status == 5 || $order->status == 6 || $order->status == 7)
                                <td>Canceled</td>
                            @endif
                            <td>Start Date</td>
                            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
                        </tr>
                        @if ($order->order_detail->discount_amount > 0 || $order->external_notes)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Discount Name :</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                            @if($order->external_notes)
                                <td>Notes : </td>
                                <td>{!! $order->external_notes !!}</td>
                            @endif
                        </tr>
                        @endif
                        <tr>
                            <td>Payment Method :</td>
                            @if($order->allow_payment == 1)
                                @if($order->payment)
                                    <td>{{$order->payment->payment_gateway}}</td>
                                @else
                                    <td>Unknown</td>
                                @endif

                            @endif

                            @if($order->allow_payment != 1)
                                <td>Manual Transfer</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">ORDER DETAIL</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                    <thead>
                        <tr style="text-align: center;background-color: rgb(239,247,254);">
                            <th style="padding: 15px; width: 61%;">Product Name</th>
{{--                            <th class="second">Description</th>--}}
{{--                            <th class="third">Price</th>--}}
                            <th style="padding: 15px; width: 30%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 15px; width: 61%;">{{ $order->order_detail->product_name }}</td>
{{--                            <td class="second"></td>--}}
{{--                            <td class="third"></td>--}}
                            <td style="padding: 15px;width: 30%;">
                                <strong>{{ $order->order_detail->currency }}
                                    <span style="float: right;">{{ number_format($order->total_amount,0) }}</span>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table style="width: 100%;float: right;padding-right: 8%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Charge {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card >0)
                            <tr>
                                <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Credit Card Charge</td>
                                <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif
                        {{-- Fee --}}
                        @if ($order->fee > 0)
                            <tr>
                                <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Charge {{ $order->payment->payment_gateway }}</td>
                                <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Discount ({{ $order->order_detail->discount_name }})</td>
                            <td style="padding: 15px;"><strong>IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Voucher Discount ({{ $order->voucher_code }})</td>
                            <td style="padding: 15px;"><strong>IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Total</td>
                        <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 3rem;">
                <p>If any other questions or complaints, please contact our customer support at <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62 812-1111-9655</a>. Thank you for using mygomodo to facilitate your transaction.<br><br>
                    Powered by Gomodo</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Check The Order</a>
            </td>
        </tr>
    </tbody>
</table>

<hr>
<br>

{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $company->company_name }},</p>
                <p>
                    Pesanan Anda dengan nomor invoice {{ $order->invoice_no }} telah dibatalkan oleh sistem. Kami belum dapat memproses pesanan dari customer Anda karena pembayaran belum diselesaikan.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Status :</td>
                            @if($order->status == 5 || $order->status == 6 || $order->status == 7)
                                <td>Cancel</td>
                            @endif
                            <td>Tanggal Mulai</td>
                            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
                        </tr>
                        @if ($order->order_detail->discount_amount > 0 || $order->external_notes)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Nama Diskon :</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                            @if($order->external_notes)
                                <td>Catatan : </td>
                                <td>{!! $order->external_notes !!}</td>
                            @endif
                        </tr>
                        @endif
                        <tr>
                            <td>Metode Pembayaran :</td>
                            @if($order->allow_payment == 1)
                                @if($order->payment)
                                    <td>{{$order->payment->payment_gateway}}</td>
                                @else
                                    <td>Diketahui</td>
                                @endif

                            @endif

                            @if($order->allow_payment != 1)
                                <td>Manual Transfer</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">DETAIL PEMESANAN</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                    <thead>
                        <tr style="text-align: center;background-color: rgb(239,247,254);">
                            <th style="padding: 15px; width: 61%;">Nama Produk</th>
{{--                            <th class="second">Description</th>--}}
{{--                            <th class="third">Price</th>--}}
                            <th style="padding-left:15px; width: 30%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding-left: 3%; width: 61%;">{{ $order->order_detail->product_name }}</td>
{{--                            <td class="second"></td>--}}
{{--                            <td class="third"></td>--}}
                            <td style="padding-left: 15px; width: 30%;">
                                <strong>{{ $order->order_detail->currency }}
                                    <span style="float: right;">{{ number_format($order->total_amount,0) }}</span>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table style="width: 100%;float: right;padding-right: 8%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Biaya {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                            <tr>
                                <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Biaya Kartu Kredit</td>
                                <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        @if($order->fee > 0)
                            <tr>
                                <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Biaya {{ $order->payment->payment_gateway }}</td>
                                <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Diskon ({{ $order->order_detail->discount_name }})</td>
                            <td style="padding: 15px;"><strong>IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Voucher Diskon ({{ $order->voucher_code }})</td>
                            <td style="padding: 15px;"><strong>IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding: 15px;padding-left:1.2rem!important;width: 67%;">Total</td>
                        <td style="padding: 15px;"><strong>IDR <span style="float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 3rem;">
                <p>Jika ada pertanyaan atau keluhan, silahkan menghubungi customer support kami di <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62 812-1111-9655</a>. Terima kasih telah menggunakan mygomodo.com untuk mempermudah transkasi Anda.
                    <br><br>Powered by Gomodo</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Lihat Pesanan</a>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>




