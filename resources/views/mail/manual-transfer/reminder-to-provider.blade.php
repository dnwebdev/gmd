<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        table {
            border-collapse: collapse
        }

        .details {}

        .details tr:nth-child(odd) {
            background-color: rgb(254, 254, 255);
        }

        .padding td {
            padding: 5px 0;
        }
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
                                <img src="{{asset('landing/img/Logo-Gomodo.png')}}" height="50" alt="Logo Gomodo">
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
                    <p style="color: rgb(59,70,80);font-weight: bold;">Dear Provider,</p>
                    <p>
                        You haven't confirmed transfer receipt from the invoice number {{ $order->invoice_no }} sent on
                        {{ $order->customer_manual_transfer->created_at->format('M d, Y') }}. Order details are as
                        follows:
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
                                <td>Email :</td>
                                <td>{{ $order->customer->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone Number :</td>
                                @if ($order->customer->phone)
                                <td>{{ $order->customer->phone }}</td>
                                @else
                                <td>-</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Product Name</td>
                                <td>: {{ $order->order_detail->product_name }}</td>
                            </tr>
                            <tr>
                                <td>Activity Date</td>
                                <td>: {{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                            </tr>
                            <tr>
                                <td>Total Price</td>
                                <td>: IDR
                                    {{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        Check your account immediately and change the order status to "Paid" if the payment is correct.
                        If the transfer receipt sent is not valid, you can cancel the order and ask the customer to send
                        a new transfer receipt.
                    </p>
                    <p>
                        We suggest following up on the order immediately to support the best customer satisfaction.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                    <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">
                        Check transfer receipt now
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Thank you,
                    <br>
                    Gomodo Team
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
                        Anda belum melakukan konfirmasi untuk bukti transfer dari nomor faktur {{ $order->invoice_no }}
                        yang dikirim pada
                        tanggal {{ $order->customer_manual_transfer->created_at->format('d M Y') }}. Berikut detail
                        pesanan:
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
                                <td>Nomor Faktur</td>
                                <td>: {{ $order->invoice_no }}</td>
                            </tr>
                            <tr>
                                <td>Nama Pelanggan</td>
                                <td>: {{ $order->customer->first_name }}</td>
                            </tr>
                            <tr>
                                <td>Email :</td>
                                <td>{{ $order->customer->email }}</td>
                            </tr>
                            <tr>
                                <td>Nomor Telepon :</td>
                                @if ($order->customer->phone)
                                <td>{{ $order->customer->phone }}</td>
                                @else
                                <td>-</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Nama Produk </td>
                                <td>: {{ $order->order_detail->product_name }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Aktivitas</td>
                                <td>: {{ date('d M, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                            </tr>
                            <tr>
                                <td>Total Harga</td>
                                <td>: IDR
                                    {{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <p>
                        Segera periksa mutasi rekening Anda dan ubah status pesanan menjadi “Dibayar” jika pembayaran
                        telah sesuai. Apabila bukti transfer yang dikirim kurang valid, Anda dapat membatalkan pesanan
                        dan meminta pelanggan mengirimkan bukti transfer baru.
                    </p>
                    <p>
                        Kami menyarankan untuk segera menindaklanjuti pesanan guna mendukung kepuasan pelanggan yang
                        terbaik.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                    <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">
                        Cek bukti transfer transfer sekarang
                    </a>
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
</body>

</html>