<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        body {}

        .details th {
            background-color: rgb(239, 247, 254);
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
                    <p style="color: rgb(59,70,80);font-weight: bold;">Dear Provider,</p>
                    <p>
                        Transfer receipt for order with invoice number {{ $order->invoice_no }} has been sent by
                        customer. Order details are as follows:
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="border-collapse:collapse;width: 100%;">
                        <tbody>
                            <tr>
                                <td>Order Name :</td>
                                <td>{{ $order->customer->first_name }}</td>
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
                                <td>Payment Method :</td>
                                <td>{{ $order->payment->payment_gateway }}</td>
                            </tr>
                            <tr>
                                <td>Status :</td>
                                @if($order->status == 0)
                                <td>Waiting in Confirmation</td>
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
                                <th style="background-color: rgb(239,247,254); padding: 15px; width: 61%;">Product Name
                                </th>
                                <th style="background-color: rgb(239,247,254); padding: 15px; width: 30%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 5px 15px">{{ $order->order_detail->product_name }}</td>
                                <td align="right" style="padding: 5px 15px">
                                    <strong>{{ $order->order_detail->currency.' ' }}
                                        <span>{{ number_format($order->total_amount,0) }}</span>
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
                            <td style="padding: 5px 15px;" colspan="2">Charge {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif
                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card >0)
                        <tr>

                            <td style="padding: 5px 15px;" colspan="2">Credit Card Charge</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                        </tr>
                        @endif

                        @if($order->fee >0)
                        <tr>

                            <td style="padding: 5px 15px;" colspan="2">Charge {{ $order->payment->payment_gateway }}
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->fee,0) }}</span></strong></td>
                        </tr>
                        @endif

                        {{-- Discount --}}
                        @if($order->order_detail->discount_amount > 0)
                        <tr>

                            <td style="padding: 5px 15px;" align="right">Discount</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                        @endif

                        {{-- Voucher --}}
                        @if($order->voucher_amount > 0)
                        <tr>
                            <td colspan="2" style="padding: 5px 15px;">Voucher Discount</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                        @endif

                        @if($order->additional_orders()->whereType('insurance')->count() > 0)
                        @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                        <tr>
                            <td colspan="2" style="padding: 5px 15px;">{{$add->description_en}}
                                ({{ $add->quantity .' * IDR '. number_format($add->price,0) }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($add->total,0) }}</span></strong>
                            </td>
                        </tr>
                        @endforeach
                        @endif

                        <tr>
                            <td style="padding: 5px 15px;" colspan="2"><strong>Total</strong></td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->total_amount,0) }}</span></strong></td>
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
                    <p>Please check your bank account records and change the order status to "Paid" if the payment is
                        matching with the
                        bill. However, if you feel the transfer receipt sent is invalid, blurry or unreadable, you can
                        cancel the order and
                        ask the customer to resend a new receipt.</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                    @if ($order->booking_type == 'online')
                    <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Check order Status</a>
                    @else
                    <a target="_blank" href="http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Check order Status</a>
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 3rem;">
                    <p>If any other questions or complaints, please contact our customer support at <a
                            href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank"
                            style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62
                            812-1111-9655</a>. Thank you for using mygomodo to facilitate your transaction.<br><br>
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
                    <p style="color: rgb(59,70,80);font-weight: bold;">Provider Yth,</p>
                    <p>
                        Bukti transfer untuk pesanan dengan nomor faktur {{ $order->invoice_no }} telah dikirimkan oleh
                        pelanggan. Berikut detail pesanan :
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table align="center" cellpadding="5" cellspacing="0" width="680px">
                        <tbody>
                            <tr>
                                <td>Nama Pemesan:</td>
                                <td>{{ $order->customer->first_name }}</td>
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
                                <td>Metode Pembayaran</td>
                                <td>{{ $order->payment->payment_gateway }}</td>
                            </tr>
                            <tr>
                                <td>Status :</td>
                                @if($order->status == 0)
                                <td>Menunggu di konfirmasi</td>
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
                            <tr>
                                <th style="background-color: rgb(239,247,254); padding: 15px; width: 61%;">Nama Produk
                                </th>
                                <th style="background-color: rgb(239,247,254); padding: 15px; width: 30%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 5px 15px">{{ $order->order_detail->product_name }}</td>
                                <td align="right" style="padding: 5px 15px">
                                    <strong>{{ $order->order_detail->currency.' ' }}
                                        <span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
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
                            <td style="padding: 5px 15px;" colspan="2">Biaya {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif
                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card >0)
                        <tr>

                            <td style="padding: 5px 15px;" colspan="2">Biaya Kartu Kredit</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                        </tr>
                        @endif

                        @if($order->fee >0)
                        <tr>

                            <td style="padding: 5px 15px;" colspan="2">Biaya {{ $order->payment->payment_gateway }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->fee,0) }}</span></strong></td>
                        </tr>
                        @endif

                        {{-- Discount --}}
                        @if($order->order_detail->discount_amount > 0)
                        <tr>

                            <td style="padding: 5px 15px;" align="right">Diskon</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">-{{ number_format($order->product_discount,0) }}</span></strong></td>
                        </tr>
                        @endif

                        {{-- Voucher --}}
                        @if($order->voucher_amount > 0)
                        <tr>
                            <td colspan="2" style="padding: 5px 15px;">Voucher Diskon</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
                        </tr>
                        @endif
                        @if($order->additional_orders()->whereType('insurance')->count() > 0)
                        @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                        <tr>
                            <td colspan="2" style="padding: 5px 15px;">{{$add->description_id}}
                                ({{ $add->quantity .' * IDR '. number_format($add->price,0) }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($add->total,0) }}</span></strong>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        <tr>

                            <td colspan="2" style="padding: 5px 15px;"><strong>Total</strong></td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                        style="">{{ number_format($order->total_amount,0) }}</span></strong></td>
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
                    <p>Silakan periksa mutasi rekening Anda dan ubah status pesanan menjadi “Dibayar” jika pembayaran
                        telah sesuai dengan
                        tagihan. Namun, jika Anda merasa bukti transfer yang dikirim kurang valid, blur atau tidak
                        terbaca, Anda dapat
                        membatalkan pesanan dan meminta pelanggan mengirimkan bukti transfer baru.</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-bottom: 2rem;text-align: center;">
                    @if ($order->booking_type == 'online')
                    <a target="_blank" href="http://{{env('APP_URL')}}/company/order/{{$order->invoice_no}}/edit"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">
                        Cek Status Pesanan
                    </a>
                    @else
                    <a target="_blank" href="http://{{env('APP_URL')}}/company/manual-order/view/{{$order->invoice_no}}"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">Cek Status Pesanan</a>
                    @endif
                    
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 3rem;">
                    <p>Jika ada pertanyaan atau keluhan, silahkan menghubungi customer support kami di <a
                            href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank"
                            style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62
                            812-1111-9655</a>. Terima kasih telah menggunakan mygomodo.com untuk mempermudah transkasi
                        Anda.
                        <br><br>Powered by Gomodo</p>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>