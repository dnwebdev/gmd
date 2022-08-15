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
    <table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
        <tbody>
            <tr>
                <td colspan="3">
                    <table style="border-collapse:collapse;width: 100%;">
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
                    <p style="color: rgb(59,70,80);font-weight: bold;">Dear Customer,</p>
                    <p>
                        Your order with invoice number {{ $order->invoice_no }} has been canceled by
                        {{ $company->company_name }} because your payment could not be verified. Please check again the
                        transfer receipt that you have sent previously. Make sure the picture / photo of the transfer
                        receipt is clearly legible and the paper that captured isn’t folded or torn.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
                        <tbody>
                            <tr>
                                <td>Name :</td>
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
                                <td>Status :</td>
                                <td>Canceled (Reupload)</td>
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
                    <table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="width: 20%; background-color: rgb(239,247,254);padding: 15px;">Product Name
                                </th>
                                <th style="width: 25%; background-color: rgb(239,247,254);padding: 15px;">Unit</th>
                                <th style="width: 15%; background-color: rgb(239,247,254);padding: 15px;">Price</th>
                                <th style="width: 30%; background-color: rgb(239,247,254);padding: 15px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:5px 15px;">{{ $order->order_detail->product_name }}</td>
                                <td style="padding:5px 15px;text-align: center">{{ $order->order_detail->adult }}
                                    {{optional($order->order_detail->unit)->name}}</td>
                                <td style="padding:5px 15px;" align="right">
                                    {{ number_format($order->order_detail->adult_price,0) }}</td>
                                <td style="padding:5px 15px;" align="right">
                                    <strong>IDR
                                        <span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                                    </strong>
                                </td>
                            </tr>
                            @if($order->additional_orders()->whereType('insurance')->count() > 0)
                            @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                            <tr>
                                <td style="padding:5px 15px;">{{ $add->description_en }}</td>
                                <td style="padding:5px 15px;text-align: center">{{ $add->quantity }}</td>
                                <td style="padding:5px 15px;" align="right">{{ number_format($add->price,0) }}</td>
                                <td style="padding:5px 15px;" align="right"><strong>IDR
                                        <span>{{ number_format($add->total,0) }}</span></strong>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="float: right;width:100%;">

                        {{-- Fee Amount --}}
                        @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">
                                Charge {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Credit Card Charge</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->fee_credit_card,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Fee --}}
                        @if ($order->fee > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">
                                Charge {{ $order->payment->payment_gateway }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->fee,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Discount --}}
                        @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Discount
                                ({{ $order->order_detail->discount_name }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>-{{ number_format($order->product_discount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Voucher --}}
                        @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Voucher Discount
                                ({{ $order->voucher_code }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>-{{ number_format($order->voucher_amount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2"><strong>Total</strong></td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->total_amount,0) }}</span></strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{-- @if($order->external_notes)
                <tr>
                    <td colspan="3">
                        <p>Notes : <br> {!! $order->external_notes !!}</p>
                    </td>
                </tr>
                @endif --}}
            <br>
            @if(!empty($order->customer_manual_transfer->note_customer))
                <tr>
                    <td colspan="3">
                        <p style="font-weight: bold">Info Notes From {{ $company->company_name }} :</p>
                        <p>{!! $order->customer_manual_transfer->note_customer !!}</p>
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="3">
                    <p>
                        If you are sure that you have the correct and appropriate receipt, click the button below to
                        re-upload the transfer receipt.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 3rem; padding-bottom: 2rem;text-align: center;">
                    <a target="_blank"
                        href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">
                        Reupload transfer receipt
                    </a>
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
                    <p style="color: rgb(59,70,80);font-weight: bold;">Pelanggan Yth,</p>
                    <p>
                        Pesanan Anda dengan nomor faktur {{ $order->invoice_no }} telah dibatalkan oleh
                        {{ $company->company_name }} karena pembayaran Anda tidak dapat diverifikasi. Silakan periksa
                        ulang bukti transfer yang telah Anda kirim sebelumnya. Pastikan gambar/foto bukti transfer dalam
                        keadaan jelas terbaca, kertas yang difoto tidak dalam keadaan terlipat dan tidak sobek.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table align="center" cellpadding="5" cellspacing="0"
                        style="border-collapse:collapse;width: 680px;">
                        <tbody>
                            <tr>
                                <td>Nama :</td>
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
                                <td>Status :</td>
                                <td>Dibatalkan (Kembali mengunggah)</td>
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
                    <table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
                        <thead>
                            <tr style="text-align: center;">
                                <th style="width: 20%; background-color: rgb(239,247,254);padding: 15px;">Nama Produk
                                </th>
                                <th style="width: 25%; background-color: rgb(239,247,254);padding: 15px;">Unit</th>
                                <th style="width: 15%; background-color: rgb(239,247,254);padding: 15px;">Harga</th>
                                <th style="width: 30%; background-color: rgb(239,247,254);padding: 15px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:5px 15px;">{{ $order->order_detail->product_name }}</td>
                                <td style="padding:5px 15px;text-align: center">{{ $order->order_detail->adult }}
                                    {{optional($order->order_detail->unit)->name}}</td>
                                <td style="padding:5px 15px;" align="right">
                                    {{ number_format($order->order_detail->adult_price,0) }}</td>
                                <td style="padding:5px 15px;" align="right">
                                    <strong>IDR
                                        <span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                                    </strong>
                                </td>
                            </tr>
                            @if($order->additional_orders()->whereType('insurance')->count() > 0)
                            @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                            <tr>
                                <td style="padding:5px 15px;">{{ $add->description_id }}</td>
                                <td style="padding:5px 15px;text-align: center">{{ $add->quantity }}</td>
                                <td style="padding:5px 15px;" align="right">{{ number_format($add->price,0) }}</td>
                                <td style="padding:5px 15px;" align="right"><strong>IDR
                                        <span>{{ number_format($add->total,0) }}</span></strong>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table style="float: right;width:100%;">

                        {{-- Fee Amount --}}
                        @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">
                                Biaya {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Biaya Kartu Kredit</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->fee_credit_card,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Fee --}}
                        @if ($order->fee > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">
                                Biaya {{ $order->payment->payment_gateway }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->fee,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Discount --}}
                        @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Diskon
                                ({{ $order->order_detail->discount_name }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>-{{ number_format($order->product_discount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif

                        {{-- Voucher --}}
                        @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Voucher Diskon
                                ({{ $order->voucher_code }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>-{{ number_format($order->voucher_amount,0) }}</span></strong>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2"><strong>Total</strong></td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR
                                    <span>{{ number_format($order->total_amount,0) }}</span></strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            {{-- @if($order->external_notes)
                <tr>
                    <td colspan="3">
                        <p>Catatan : <br> {!! $order->external_notes !!}</p>
                    </td>
                </tr>
                @endif --}}
            <br>
            @if(!empty($order->customer_manual_transfer->note_customer))
            <tr>
                <td colspan="3">
                    <p style="font-weight: bold">Info catatan dari {{ $company->company_name }} :</p>
                    <p>{!! $order->customer_manual_transfer->note_customer !!}</p>
                </td>
            </tr>
            @endif
            <tr>
                <td colspan="3">
                    <p>
                        Apabila Anda telah yakin memiliki bukti transfer yang benar dan sesuai, klik tombol di bawah ini
                        untuk mengunggah ulang bukti transfer.
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 3rem;text-align: center;">
                    <a target="_blank"
                        href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow"
                        style="padding: 10px 100px;background-color: rgb(4,155,248);border-radius: 5px;color: white;font-size: 15px;position: relative;margin-top: 60px;text-decoration: none;">
                        Unggah ulang bukti transfer
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>