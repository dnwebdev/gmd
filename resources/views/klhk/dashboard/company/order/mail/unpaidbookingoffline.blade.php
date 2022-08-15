<!DOCTYPE html>
<html>
<head>
<style type="text/css">
body{

}
table {

}
.caption {
    color: rgb(59,70,80);
    font-weight: bold;
}
/*.details tr:nth-child(even) {*/
/*    background-color: rgb(239,247,254);*/
/*}*/
.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
/*.padding td{*/
/*    padding: 5px 0;*/
/*}*/

/* .provider-logo {
    background-color: rgba(0, 0, 0, 0.021);
    color: rgb(104, 104, 104);
    text-align: center;
    padding: 0;
    width: 25%;
    font-weight: bold;
    font-size: 1.2rem;
} */
.button {

}
.padding-price{
    padding-left:  1.2rem!important;
    width: 74%;
}
</style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <table width="100%">
                    <tr>
                        <td colspan="2" class="provider-logo">
                            @if($company->logo)
                                <img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
                            @endif
                        </td>
                        <td style="width: 25%;"><h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <p class="caption">Hello {{ $order->customer->first_name }},</p>
                <p>
                    Thank you for ordering on {{ $company->company_name }}. Your order has been confirmed. Please make payment immediately according to the invoice that we attach to this email.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td>Name :</td>
                            <td>{{ $order->customer->first_name }}</td>
                            <td>Phone Number :</td>
                            @if ($order->customer->phone)
                            <td>{{ $order->customer->phone }}</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Email :</td>
                            <td>{{ $order->customer->email }}</td>
                            <td>Status :</td>
                            <td>UNPAID</td>
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
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label class="caption" for="">ORDER DETAIL</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                    <thead>
                        <tr style="background-color: rgb(239,247,254);text-align: center;">
                            <th style="padding: 15px; width: 20%;">Product Name</th>
                            <th style="padding: 15px; width: 25%">Description</th>
                            <th style="padding: 15px;width: 8%;">Qty</th>
                            <th style="padding: 15px;width: 15%;">Price</th>
                            <th style="padding: 15px;width: 30%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($order->invoice_detail as $item)
                        <tr>
                            <td style="padding: 15px;width: 20%;">{{ $order->order_detail->product_name }}</td>
                            <td style="padding: 15px;width: 25%;">{{$item['description']}}</td>
                            <td style="padding: 15px;width: 8%;">{{ $item['qty'] }}</td>
                            <td style="padding: 15px;width: 15%;">{{ number_format($item['price'],0) }}</td>
                            <td style="padding: 15px;width: 30%;">
                                <strong>IDR
                                    <span style="float: right;">{{ number_format($item['price'] * $item['qty'],0) }}</span>
                                </strong>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table style=" width: 100%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="width: 71%;">Charge {{ $order->order_detail->fee_name }}</td>
                            <td><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif
                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card >0)
                            <tr>
                                <td style="width: 71%;">Credit Card Charge</td>
                                <td><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        @if($order->fee >0)
                            <tr>
                                <td style="width: 71%;">Charge {{ $order->payment->payment_gateway }}</td>
                                <td><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td></td>
                            <td style="width: 71%;">Discount</td>
                            <td ><strong>IDR <span style="padding-right: 8%; float: right;color: rgb(4,155,248)">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td></td>
                            <td style="width: 71%;">Voucher Discount</td>
                            <td ><strong>IDR <span style="padding-right: 8%; float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>
                        <td></td>
                        <td style="width: 71%;">Total</td>
                        <td ><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        @if($order->guides->count() > 0)
        <br>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label class="caption" for="">GUIDE INFORMATION</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table style=" width: 100%;">
                    <thead>
                        <tr align="left">
                            <th style="width: 20%;">No</th>
                            <th style="width: 25%">Guide Name</th>
                            <th style="width: 15%;">Phone Number</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($order->guides as $guide)
                        <tr>
                            <td style="width: 20%;">{{$loop->index+1}}</td>
                            <td style="width: 25%;">{{$guide->guide_name}}</td>
                            <td style="width: 15%;">{{$guide->guide_phone_number}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </td>
        </tr>
        @endif
        <br>
        @if($order->external_notes)
        <tr>
            <td>
                <p><strong>Notes :</strong><br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
        @endif
{{--        <tr>--}}
{{--            <td colspan="3">--}}
{{--                <button class="button">Follow Up Order</button>--}}
{{--            </td>--}}
{{--        </tr>--}}

        @if($order->allow_payment == 1 && $order->status == 0)
        <tr>
            @if($company->color_company)
                <td colspan="3" style="padding-top: 5rem; padding-bottom: 2rem; text-align: center;">
                    <a target="_blank" href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Pay Now</a>
                </td>
            @else
                <td colspan="3" style="padding-top: 5rem;padding-bottom: 2rem; text-align: center;">
                    <a target="_blank" href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Pay Now</a>
                </td>
            @endif
        </tr>
        @endif
    </tbody>
</table>

<hr>
<br>
{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <p class="caption">Halo {{ $order->customer->first_name }},</p>
                <p>
                    Terima kasih sudah memesan di {{ $company->company_name }}. Pemesanan Anda sudah dikonfirmasi. Mohon segera lakukan pembayaran sesuai dengan invoice yang kami lampirkan pada email ini.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                    <tr>
                        <td>Nama :</td>
                        <td>{{ $order->customer->first_name }}</td>
                        <td>No. Telpon :</td>
                        @if ($order->customer->phone)
                            <td>{{ $order->customer->phone }}</td>
                        @else
                            <td>-</td>
                        @endif
                    </tr>
                    <tr>
                        <td>Email :</td>
                        <td>{{ $order->customer->email }}</td>
                        <td>Status :</td>
                        <td>Belum Dibayar</td>
                    </tr>
                    @if ($order->order_detail->discount_amount > 0)
                        <tr>
                            @if($order->order_detail->discount_amount > 0)
                                <td>Nama Diskon:</td>
                                <td>
                                    {{ $order->order_detail->discount_name }}
                                </td>
                            @endif
                        </tr>
                    @endif
                    </tbody>
{{--                    <tbody>--}}
{{--                        <tr>--}}
{{--                            <td>Nomor Telepon</td>--}}
{{--                            <td>{{ $company->phone_company }}</td>--}}
{{--                            <td>Email</td>--}}
{{--                            <td>{{ $company->email_company }}</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Status :</td>--}}
{{--                            <td>Tidak dibayar</td>--}}
{{--                        @if($company->address_company)--}}
{{--                            <td>Alamat :</td>--}}
{{--                            <td>{!! $company->address_company !!}</td>--}}
{{--                        @endif--}}
{{--                        </tr>--}}
{{--                        @if ($order->order_detail->discount_amount > 0)--}}
{{--                        <tr>--}}
{{--                            @if($order->order_detail->discount_amount > 0)--}}
{{--                                <td>Nama Diskon :</td>--}}
{{--                                <td>--}}
{{--                                    {{ $order->order_detail->discount_name }}--}}
{{--                                </td>--}}
{{--                            @endif--}}
{{--                        </tr>--}}
{{--                        @endif--}}
{{--                    </tbody>--}}
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label class="caption" for="">RINCIAN PEMESANAN</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                    <thead>
                        <tr style="background-color: rgb(239,247,254);text-align: center;">
                            <th style="padding: 15px;width: 20%;">Nama Produk</th>
                            <th style="padding: 15px;width: 25%;">Deskripsi</th>
                            <th style="padding: 15px;width: 8%;">Kuantitas</th>
                            <th style="padding: 15px;width: 15%;">Harga</th>
                            <th style="padding: 15px;width: 30%;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($order->invoice_detail as $item)
                        <tr>
                            <td style="padding: 15px;width: 20%;">{{ $order->order_detail->product_name }}</td>
                            <td style="padding: 15px;width: 25%;">{{$item['description']}}</td>
                            <td style="padding: 15px;width: 8%;">{{ $item['qty'] }}</td>
                            <td style="padding: 15px;width: 15%;">{{ number_format($item['price'],0) }}</td>
                            <td style="padding: 15px;width: 30%;">
                                <strong>IDR
                                    <span style="float: right;">{{ number_format($item['price'] * $item['qty'],0) }}</span>
                                </strong>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
           <td colspan="3">
                <table style=" width: 100%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="width: 74%;">Biaya {{ $order->order_detail->fee_name }}</td>
                            <td><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
                        </tr>
                    @endif
                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card >0)
                            <tr>
                                <td style="width: 74%;">Biaya Kartu Kredit</td>
                                <td><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                            </tr>
                        @endif

                        @if($order->fee >0)
                            <tr>
                                <td style="width: 74%;">Biaya {{ $order->payment->payment_gateway }}</td>
                                <td><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->fee,0) }}</span></strong></td>
                            </tr>
                        @endif


                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="width: 74%;">Diskon</td>
                            <td ><strong>IDR <span style="padding-right: 8%; float: right;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td style="width: 74%;">Voucher Diskon</td>
                            <td ><strong>IDR <span style="padding-right: 8%; float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                    @endif

                    <tr>
                        <td style="width: 74%;">Total</td>
                        <td ><strong>IDR <span style="padding-right: 8%; float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
                    </tr>
                </table>
           </td>
        </tr>
        @if($order->guides->count() > 0)
            <br>
            <tr>
                <td colspan="3" style="padding-top: 5%">
                    <label class="caption" for="">INFORMASI PANDUAN</label>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style=" width: 100%;">
                        <thead>
                            <tr align="left">
                                <th style="width: 20%;">No</th>
                                <th style="width: 25%;">Nama Pemandu</th>
                                <th style="width: 15%;">Nomor Telepon</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($order->guides as $guide)
                            <tr>
                                <td style="width: 20%;">{{$loop->index+1}}</td>
                                <td style="width: 25%;">{{$guide->guide_name}}</td>
                                <td style="width: 15%;">{{$guide->guide_phone_number}}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </td>
            </tr>
        @endif
        <br>
        @if($order->external_notes)
        <tr>
            <td>
                <p><strong>Catatan :</strong><br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
        @endif
{{--        <tr>--}}
{{--            <td colspan="3">--}}
{{--                <button class="button">Follow Up Order</button>--}}
{{--            </td>--}}
{{--        </tr>--}}

        @if($order->allow_payment == 1 && $order->status == 0)
        <tr>
            @if($company->color_company)
                <td colspan="3" style="padding-top: 5rem; padding-bottom: 2rem;text-align: center;">
                    <a target="_blank" href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Bayar Sekarang</a>
                </td>
            @else
                <td colspan="3" style="padding-top: 5rem;padding-bottom: 2rem;text-align: center;">
                    <a target="_blank" href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Bayar Sekarang</a>
                </td>
            @endif
        </tr>
        @endif
    </tbody>
</table>

</body>
</html>




