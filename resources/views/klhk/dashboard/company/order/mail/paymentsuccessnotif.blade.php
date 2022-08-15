<!DOCTYPE html>
<html lang="en">
<head>
    <style type="text/css">
        table {
            border-collapse:collapse
        }

    </style>
    <title>Email Success</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0,0,0,0.7);">
{{--Mail English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <table width="100%">
                    <tr>
                        <td colspan="2">
                            @if($company->logo)
                                <img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
                            @endif
                        </td>
{{--                        <td colspan="2" class="provider-logo">--}}
{{--                            <img src="{{ asset('img/gomodo.png') }}" height="50" alt="Logo Gomodo">--}}
{{--                        </td>--}}
                        <td style="width: 25%;"><h2 style="color:#b51fb5; float: right;">{{ $order->invoice_no }}</h2></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <p class="caption">Hello {{ $company->company_name }},</p>
                <p style="text-align: justify">
                    We want to inform you that your payment with invoice number: {{ $order->invoice_no }} has been received. Your payment details are as follows:
                </p>
            </td>
        </tr>
        <tr style="background-color: #f0f8fe;">
            <td colspan="3" style=" padding-top: 14px;">
                <label for="" style="font-weight: bold; margin-left: 5px;">ORDER DETAILS</label>
            </td>
        </tr>
        <tr style="background-color: #f0f8fe;">
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">Product Name
                            <td style="padding: 1px 5px;">: {{ $order->order_detail->product_name }}</td></td>
                        </tr>
                        @if ($order->order_detail->city)
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">City </td>
                            <td style="padding: 1px 5px;">: {{ $order->order_detail->city ? '('.$order->order_detail->city->city_name.')' : '' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">Schedule </td>
                            <td style="padding: 1px 5px;">: {{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">
                                Guest
                            </td>
                            <td style="padding: 1px 5px;">
                                : {{ $order->order_detail->adult }} {{ optional($order->order_detail->unit)->name }}
                                @if(optional($order->order_detail->unit)->name === '-')
                                    Pax/Unit
                                @endif
                                {{ ($order->order_detail->children > 0) ? ', '.$order->order_detail->children.' Children' : '' }} {{ ($order->order_detail->infant > 0) ? ', '.$order->order_detail->infant.' Infant' : '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3" align="center" style="padding-top: 30px;text-align: center;">
                <p style="font-size: 10px; margin-bottom: 25px;">Please check  the order details by accessing the button below: </p>
                <a href="http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">See Order</a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="margin-top: 3rem">
                    We are waiting for your next order. Thank you <br>
                    Gomodo Team
                </p>
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

{{--Mail Indonesia--}}
<table cellpadding="5" cellspacing="0" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <p class="caption">Halo {{ $company->company_name }},</p>
                <p style="text-align: justify">
                    Kami ingin memberitahukan bahwa pembayaran dengan nomor invoice : {{ $order->invoice_no }} telah diterima. Detail pembayaran adalah sebagai berikut :
                </p>
            </td>
        </tr>
        <tr style="background-color: #f0f8fe;">
            <td colspan="3" style="padding-top: 14px;">
                <label for="" style="font-weight: bold; margin-left: 5px;">DETAIL PEMESANAN</label>
            </td>
        </tr>
        <tr style="background-color: #f0f8fe;">
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" width="680px">
                    <tbody>
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">Nama Produk </td>
                            <td style="padding: 1px 5px;">: {{ $order->order_detail->product_name }}</td>
                        </tr>
                        @if ($order->order_detail->city)
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">Kota </td>
                            <td style="padding: 1px 5px;">: {{ $order->order_detail->city ? '('.$order->order_detail->city->city_name.')' : '' }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">Jadwal </td>
                            <td style="padding: 1px 5px;"> : {{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 1px 5px;width: 135px;">Tamu
                            </td>
                            <td style="padding: 1px 5px;">
                                : {{ $order->order_detail->adult }} {{ optional($order->order_detail->unit)->name }}
                                @if(optional($order->order_detail->unit)->name === '-')
                                    Pax/Unit
                                @endif
                                {{ ($order->order_detail->children > 0) ? ', '.$order->order_detail->children.' Children' : '' }} {{ ($order->order_detail->infant > 0) ? ', '.$order->order_detail->infant.' Infant' : '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3" align="center" style="padding-top: 30px;text-align: center;">
                <p style="font-size: 10px; margin-bottom: 25px;">Anda dapat melihat detail pesanan dengan mengakses tombol dibawah ini </p>
                <a href="http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}" style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Lihat Pesanan</a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <p style="margin-top: 3rem">
                    Terimakasih atas kami tunggu pesanan anda berikutnya <br>
                    Gomodo Team
                </p>
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
