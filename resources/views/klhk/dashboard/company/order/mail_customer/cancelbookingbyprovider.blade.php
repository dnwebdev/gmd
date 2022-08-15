<!DOCTYPE html>
<html>
<head>
<style type="text/css">
table {
    border-collapse:collapse
}
.details {
}
/*.details tr:nth-child(even) {*/
/*    background-color: rgb(239,247,254);*/
/*}*/
.details tr:nth-child(odd) {
    background-color: rgb(254, 254, 255);
}
.padding td{
    padding: 5px 0;
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
</style>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <table style="width: 100%;">
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
                <p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $order->customer->first_name }},</p>
                <p>
                    Sorry, we apologize for having to cancel your booking as detailed below due to some reasons:
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Product </td>
                            <td>: {{ $order->order_detail->product_name }}</td>
                        </tr>
                        <tr>
                            <td>Provider</td>
                            <td>: {{ $company->company_name }}</td>
                        </tr>
                        <tr>
                            <td>Activity Date</td>
                            <td>: {{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                        </tr>
                        <tr>
                            <td>Total Price</td>
                            <td>: IDR {{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="3">
                <p>
                If you have any questions, please contact us.
                Thank you for booking through {{ $company->domain_memoria }}.
                </p>
                <p>
                This email is made automatically by system. Do not reply to this email. For further information, please contact us  {{ $company->phone_company }}.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                Best regards,
                <br>
                {{ $company->company_name }}
            </td>
        </tr>

    </tbody>
</table>

<hr>
<br>
{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
    <tbody>
        <tr>
            <td colspan="3">
                <p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $order->customer->first_name }},</p>
                <p>
                    Maaf, pesanan Anda dengan detail pemesanan sebagai berikut:
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Nama Produk </td>
                            <td>: {{ $order->order_detail->product_name }}</td>
                        </tr>
                        <tr>
                            <td>Nama Provider</td>
                            <td>: {{ $company->company_name }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Aktivitas</td>
                            <td>: {{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                        </tr>
                        <tr>
                            <td>Total Harga</td>
                            <td>: IDR {{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="3">
                <p>
                    Dengan berat hati harus kami batalkan dikarenakan suatu hal. Jika ada pertanyaan, silahkan hubungi kami.
                    Terima kasih telah melakukan pemesanan melalui {{ $company->domain_memoria }}.
                </p>
                <p>
                    Email ini dibuat secara otomatis oleh sistem. Harap tidak membalas email ini. Untuk informasi selengkapnya silahkan hubungi kami melalui {{ $company->phone_company }}.
                </p>
            </td>
        </tr>
       <tr>
           <td colspan="3">
                Salam hangat,
                <br>
                {{ $company->company_name }}
           </td>
       </tr>

    </tbody>
</table>
</body>
</html>




