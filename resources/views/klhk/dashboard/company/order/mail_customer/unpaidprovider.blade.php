<!DOCTYPE html>
<html>
<head>
<style type="text/css">
body{

}

.details th {
    background-color: rgb(239,247,254);
}
.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
</style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $company->company_name }},</p>
                <p>
                    You receive a new order for {{ $order->order_detail->product_name }} Your order status is waiting for payment Please check  the order details by accessing the button below :
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table style="border-collapse:collapse;width: 100%;">
                    <tbody>
                        <tr>
                            <td>Status :</td>
                            @if($order->status == 0)
                                <td>Waiting for transfer</td>
                            @endif
                            <td>Order Date :</td>
                            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
                        </tr>
                        @if ($order->order_detail->discount_amount > 0)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Discount Name :</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                        </tr>
                        @endif
                        @if($order->status == 0)
                            <tr>
                                <td>Payment Method :</td>
                                @if($order->allow_payment == 1)
                                    @if($order->payment)
                                        @if ($order->payment->payment_gateway == 'Cash On Delivery')
                                            Cash
                                        @else
                                            <td>{{$order->payment->payment_gateway}}</td>
                                        @endif
                                    @else
                                        <td>Unknown</td>
                                    @endif

                                @endif

                                @if($order->allow_payment != 1)
                                    <td>Manual Transfer</td>
                                @endif
                            </tr>
                        @endif
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
                <table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
                    <thead>
                        <tr>
                            <th style="background-color: rgb(239,247,254); padding: 15px; width: 61%;">Product Name</th>
{{--                            <th class="second">Description</th>--}}
{{--                            <th class="third">Price</th>--}}
                            <th style="background-color: rgb(239,247,254); padding: 15px; width: 30%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="first" style="padding: 15px; width: 61%;">{{ $order->order_detail->product_name }}</td>
{{--                            <td class="second"></td>--}}
{{--                            <td class="third"></td>--}}
                            <td class="fourth" style="padding: 15px;padding-left: 8%; width: 30%">
                                <strong>{{ $order->order_detail->currency }}
                                    <span style="float: right;">{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table style="padding: 5px 0;width: 100%;">
                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 5px 0;"></td>
                            <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Charge {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif
                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                            <tr>
                                <td style="padding: 5px 0;"></td>
                                <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Credit Card Charge</td>
                                <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        @if($order->fee > 0)
                            <tr>
                                <td style="padding: 5px 0;"></td>
                                <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Charge {{ $order->payment->payment_gateway }}</td>
                                <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="padding: 5px 0;"></td>
                            <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Discount</td>
                            <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;color: rgb(4,155,248)">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 5px 0;"></td>
                            <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Voucher Discount</td>
                            <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;color: rgb(4,155,248)">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding: 5px 0;"></td>
                        <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Total</td>
                        <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        <br>
        @if($order->external_notes)
        <tr>
            <td colspan="3">
                <p><strong>Notes : </strong><br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3" style="padding-top: 3rem;">
                <p>We will provide further notification when we get payment status information.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Check The Order</a>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 3rem;">
                <p>If any other questions or complaints, please contact our customer support at <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62 812-1111-9655</a>. Thank you for using mygomodo to facilitate your transaction.<br><br>
                    Powered by Gomodo</p>
            </td>
        </tr>
    </tbody>
</table>

<hr>
<br>

{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
    <tbody>

        <tr>
            <td colspan="3">
                <p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $company->company_name }},</p>
                <p>
                    Anda baru saja menerima pesanan baru yaitu {{ $order->order_detail->product_name }} dengan status menunggu pembayaran. Anda dapat melihat detail pesanan dengan mengakses tombol di bawah ini:
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Status :</td>
                            @if($order->status == 0)
                                <td>Menunggu transfer</td>
                            @endif
                            <td>Tanggal Pemesanan</td>
                            <td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
                        </tr>
                        @if ($order->order_detail->discount_amount > 0)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Nama Diskon :</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                        </tr>
                        @endif
                        @if($order->status == 0)
                            <tr>
                                <td>Metode Pembayaran</td>
                                @if($order->allow_payment == 1)
                                    @if($order->payment)
                                        @if ($order->payment->payment_gateway == 'Cash On Delivery')
                                            Pembyaran Tunai
                                        @else
                                            <td>{{$order->payment->payment_gateway}}</td>
                                        @endif
                                    @else
                                        <td>Tidak Diketahui</td>
                                    @endif

                                @endif

                                @if($order->allow_payment != 1)
                                    <td>Manual Transfer</td>
                                @endif
                            </tr>
                        @endif
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
                <table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
                    <thead>
                        <tr align="left">
                            <th class="first" style="background-color: rgb(239,247,254);padding: 15px;padding-left: 3%; width: 61%;">Nama Produk</th>
{{--                            <th class="second">Description</th>--}}
{{--                            <th class="third">Price</th>--}}
                            <th class="fourth" style="background-color: rgb(239,247,254);padding: 15px;padding-left: 8%; width: 30%">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="first" style="padding: 15px;padding-left: 3%; width: 61%;">{{ $order->order_detail->product_name }}</td>
{{--                            <td class="second"></td>--}}
{{--                            <td class="third"></td>--}}
                            <td class="fourth" style="padding: 15px;padding-left: 8%; width: 30%">
                                <strong>{{ $order->order_detail->currency }}
                                    <span style="float: right;">{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table style="padding: 5px 0;width: 100%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 5px 0;"></td>
                            <td style="padding: 5px 0;">Biaya {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif
                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card >0)
                            <tr>
                                <td style="padding: 5px 0;"></td>
                                <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Biaya Kartu Kredit</td>
                                <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        @if($order->fee >0)
                            <tr>
                                <td style="padding: 5px 0;"></td>
                                <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Biaya {{ $order->payment->payment_gateway }}</td>
                                <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                        {{-- Discount --}}
                        @if($order->order_detail->discount_amount > 0)
                            <tr>
                                <td style="padding: 5px 0;"></td>
                                <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Diskon</td>
                                <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;color: rgb(4,155,248)">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 5px 0;"></td>
                            <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Voucher Diskon</td>
                            <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%;color: rgb(4,155,248)">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>
                        <td style="padding: 5px 0;"></td>
                        <td style="padding: 5px;padding-left:1.2rem!important;width: 75%;">Total</td>
                        <td style="padding: 5px 0;"><strong>IDR <span style="float: right;padding-right: 8%">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        <br>
        @if($order->external_notes)
        <tr>
            <td colspan="3">
                <p><strong>Notes : </strong><br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3" style="padding-top: 3rem;">
                <p>Kami akan memberikan pemberitahuan selanjutnya ketika kami mendapatkan informasi status pembayaran.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Lihat Pesanan</a>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 3rem;">
                <p>Jika ada pertanyaan atau keluhan, silahkan menghubungi customer support kami di <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62 812-1111-9655</a>. Terima kasih telah menggunakan mygomodo.com untuk mempermudah transkasi Anda.
                    <br><br>Powered by Gomodo</p>
            </td>
        </tr>
    </tbody>
</table>
</body>
</html>




