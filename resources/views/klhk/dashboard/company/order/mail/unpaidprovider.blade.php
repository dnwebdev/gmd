<!DOCTYPE html>
<html>
<head>
<style type="text/css">

.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
</style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; width: 860px; margin: auto;">
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
                    You received a new order for {{ $order->order_detail->product_name }}. Your order status is waiting for payment. Please check  the order details by accessing the button below :
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Status :</td>
                            <td>Waiting for transfer</td>
                            <td>Order Date :</td>
                            <td>{{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</td>
                        </tr>
                        @if ($order->order_detail->discount_amount > 0 || $order->invoice_no)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Discount Name :</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                                <td>Invoice Number :</td>
                                <td>{{ $order->invoice_no }}</td>
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
                <table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                    <thead>
                        <tr style="background-color: rgb(239,247,254);text-align: center;">
                            <th style="padding: 15px; width: 61%;">Product Name</th>
{{--                            <th class="second">Description</th>--}}
{{--                            <th class="third">Price</th>--}}
                            <th style="padding: 15px;width: 30%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 15px;width: 61%;">{{ $order->order_detail->product_name }}</td>
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
                <table style="width: 100%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding-left:1.2rem!important;width: 69%;">Charge {{ $order->order_detail->fee_name }}</td>
                            <td><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                            <tr>
                                <td style="padding-left:1.2rem!important;width: 69%;">Credit Card Charge</td>
                                <td><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        @if($order->fee > 0)
                            <tr>
                                <td style="padding-left:1.2rem!important;width: 69%;">Charge {{ $order->payment->payment_gateway }}</td>
                                <td><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>

                            <td style="padding-left:1.2rem!important;width: 69%;">Discount</td>
                            <td ><strong>IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>

                            <td style="padding-left:1.2rem!important;width: 69%;">Voucher Discount</td>
                            <td ><strong>IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>

                        <td style="padding-left:1.2rem!important;width: 69%;">Total</td>
                        <td ><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        <br>
        @if($order->external_notes)
        <tr>
            <td>
                <p>Notes : <br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3" style="padding-top: 3rem; ">
                <p>We will send a further notification once the payment has been completed succesfully.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                <a target="_blank" href="http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Check The Order</a>
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
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; width: 860px; margin: auto;">
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
                <p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $company->company_name }},</p>
                <p>
                    Anda menerima pesanan baru yaitu {{ $order->order_detail->product_name }} dengan status menunggu pembayaran. Anda dapat melihat detaill pesanan dengan mengakses tombol dibawah ini :
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Status :</td>
                            <td>Menunggu untuk di transfer</td>
                            <td>Tanggal Pemesanan :</td>
                            <td>{{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</td>
                        </tr>
                        @if ($order->order_detail->discount_amount > 0 || $order->invoice_no)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Nama Diskon :</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                                <td>Nomor Invoice :</td>
                                <td>{{ $order->invoice_no }}</td>
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
                <table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                    <thead>
                        <tr style="background-color: rgb(239,247,254);text-align: center;">
                            <th style="padding: 15px;width: 61%;">Nama Produk</th>
{{--                            <th class="second">Description</th>--}}
{{--                            <th class="third">Price</th>--}}
                            <th style="padding: 15px;width: 30%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="padding: 15px;width: 61%;">{{ $order->order_detail->product_name }}</td>
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
                <table style="width: 100%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>

                            <td style="padding-left:1.2rem!important;width: 69%;">Biaya {{ $order->order_detail->fee_name }}</td>
                            <td><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                            <tr>

                                <td style="padding-left:1.2rem!important;width: 69%;">Biaya Kartu Kredit</td>
                                <td><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee > 0)
                            <tr>

                                <td style="padding-left:1.2rem!important;width: 69%;">Biaya {{ $order->payment->payment_gateway }}</td>
                                <td><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>

                            <td style="padding-left:1.2rem!important;width: 69%;">Diskon</td>
                            <td ><strong>IDR <span style="float: right;color: rgb(4,155,248);padding-right: 10px;">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>

                            <td style="padding-left:1.2rem!important;width: 69%;">Voucher Diskon</td>
                            <td ><strong>IDR <span style="float: right;color: rgb(4,155,248);padding-right: 10px;">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>

                        <td style="padding-left:1.2rem!important;width: 69%;">Total</td>
                        <td ><strong>IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        <br>
        @if($order->external_notes)
        <tr>
            <td>
                <p>Catatan : <br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
        @endif
        <tr>
            <td colspan="3"  style="padding-top: 3rem;">
                <p>Kami akan mengirimkan pemberitahuan lebih lanjut setelah pembayaran berhasil dilunasi.</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">
                <a target="_blank" href="http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Lihat Pesanan</a>
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




