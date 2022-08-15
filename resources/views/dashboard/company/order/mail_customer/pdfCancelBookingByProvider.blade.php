<!DOCTYPE html>
<html>

<head>
    <title>PDF Cancel Booking</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
                                <img src="{{ $company->logo_url }}" height="50" alt="Logo" />
                                @endif
                            </td>
                            <td style="width: 25%;">
                                <h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <p style="color: rgb(59,70,80);font-weight: bold;">Dear customer,</p>
                    <p>
                        Your order at {{ $company->company_name }} has been canceled. Order details are as follows:
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 5%">
                    <label for="" style="color: rgb(59,70,80);font-weight: bold;">DETAIL PEMESANAN</label>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table cellpadding="5" cellspacing="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>Number Invoice</td>
                                <td>: {{ $order->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td>Customer Name</td>
                                <td>: {{ $order->customer->first_name }}</td>
                            </tr>
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
                                <td>: IDR {{ number_format($order->total_amount,0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    This e-mail was generated automatically by the system. Please do not reply to this email. If you
                    have questions or need more information about this cancellation, please contact us via
                    {{ $company->phone_company }}.
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Terimakasih,
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
                    <p style="color: rgb(59,70,80);font-weight: bold;">Pelanggan Yth,</p>
                    <p>
                        Pesanan Anda di {{ $company->company_name }} telah dibatalkan. Detail pesanan dapat Anda lihat
                        di bawah ini:
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 5%">
                    <label for="" style="color: rgb(59,70,80);font-weight: bold;">DETAIL PEMESANAN</label>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table cellpadding="5" cellspacing="0" style="width: 100%;">
                        <tbody>
                            <tr>
                                <td>No Faktur</td>
                                <td>: {{ $order->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td>Nama Pelanggan</td>
                                <td>: {{ $order->customer->first_name }}</td>
                            </tr>
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
                                <td>: IDR {{ number_format($order->total_amount,0) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Email ini dibuat secara otomatis oleh sistem. Harap tidak membalas email ini. Apabila Anda memiliki
                    pertanyaan atau memerlukan informasi selengkapnya tentang pembatalan ini, silakan hubungi kami
                    melalui {{ $company->phone_company }}.
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Terimakasih,
                    <br>
                    {{ $company->company_name }}
                </td>
            </tr>

        </tbody>
    </table>
    </table>
</body>

</html>