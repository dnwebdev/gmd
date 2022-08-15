<!DOCTYPE html>
<html>
<head>
    <title>PDF</title>
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
            <table width="100%">
                <tr>
                    <td colspan="2" class="provider-logo">
                        @if($company->logo)
                            <img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
                        @endif
                    </td>
                    <td style="width: 25%;"><h2
                                style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $order->customer->first_name }},</p>
            <p>
                Congratulations! Your payment on order {{ $order->invoice_no }} has been <b>confirmed</b>. Details of your orders can be seen below:
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
                    <td>Paid</td>
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
            <label for="" style="color: rgb(59,70,80);font-weight: bold;">DETAIL ORDER</label>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                <thead>
                <tr style="background-color: rgb(239,247,254);text-align: center">
                    <th style="padding: 15px; width: 20%;">Product Name</th>
                    <th style="padding: 15px; width: 25%">Description</th>
                    <th style="padding: 15px; width: 8%;">Qty</th>
                    <th style="padding: 15px; width: 15%;">Price</th>
                    <th style="padding: 15px;width: 30%;">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->invoice_detail as $item)
                    <tr>
                        <td style="padding: 15px;">{{ $order->order_detail->product_name }}</td>
                        <td style="padding: 15px;">{{$item['description']}}</td>
                        <td style="padding: 15px;text-align: center">{{ $item['qty'] }}</td>
                        <td style="padding: 15px;" align="right">{{ number_format($item['price'],0) }}</td>
                        <td style="padding: 15px;" align="right">
                            <strong>IDR
                                <span>{{ number_format($item['price'] * $item['qty'],0) }}</span>
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
            <table style="width: 100%;">

                {{-- Fee Amount --}}
                @if($order->order_detail->fee_amount > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">
                            Charge {{ $order->order_detail->fee_name }}</td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                        </td>
                    </tr>
                @endif

                {{-- Fee Credit card --}}
                @if($order->fee_credit_card > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">Credit Card Charge</td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                    </tr>
                @endif

                @if($order->fee > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">Charge {{ $order->payment->payment_gateway }}</td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                    </tr>
                @endif
                {{-- Discount --}}
                @if($order->order_detail->discount_amount > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">Discount
                            ({{ $order->order_detail->discount_name }})
                        </td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong>
                        </td>
                    </tr>
                @endif

                {{-- Voucher --}}
                @if($order->voucher_amount > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">Voucher Discount
                            ({{ $order->voucher_code }})
                        </td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding: 5px 15px;" colspan="2">Total</td>
                    <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->total_amount,0) }}</span></strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="height: 100px;"></td>
    </tr>
    @if($order->guides->count() > 0)
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">GUIDE INFORMATION</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table style="width: 100%;">
                    <thead>
                    <tr align="left">
                        <th style="width: 20%;">No</th>
                        <th style="width: 25%;">Guide Name</th>
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
    @if($order->external_notes)
        <tr>
            <td>
                <p><strong>Notes : <br> {!! $order->external_notes !!}</strong></p>
            </td>
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
            <table width="100%">
                <tr>
                    <td colspan="2" class="provider-logo">
                        @if($company->logo)
                            <img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
                        @endif
                    </td>
                    <td style="width: 25%;"><h2
                                style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $order->customer->first_name }},</p>
            <p>
                Selamat! Pembayaran Anda pada pesanan {{ $order->invoice_no }} telah berhasil <b>dikonfirmasi</b>. Rincian pesanan Anda dapat dilihat seperti berikut:
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
                    <td>Nomor Telpon :</td>
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
                    <td>Lunas</td>
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
            <label for="" style="color: rgb(59,70,80);font-weight: bold;">ORDER DETAIL</label>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                <thead>
                <tr style="background-color: rgb(239,247,254);">
                    <th style="padding: 15px; width: 20%;">Nama Produk</th>
                    <th style="padding: 15px; width: 25%">Deskripsi</th>
                    <th style="padding: 15px; width: 8%;">Kuantitas</th>
                    <th style="padding: 15px; width: 15%;">Harga</th>
                    <th style="padding: 15px;width: 30%;">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->invoice_detail as $item)
                    <tr>
                        <td style="padding: 15px;">{{ $order->order_detail->product_name }}</td>
                        <td style="padding: 15px;">{{$item['description']}}</td>
                        <td style="padding: 15px;text-align: center">{{ $item['qty'] }}</td>
                        <td style="padding: 15px;" align="right">{{ number_format($item['price'],0) }}</td>
                        <td style="padding: 15px;" align="right">
                            <strong>IDR
                                <span>{{ number_format($item['price'] * $item['qty'],0) }}</span>
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
            <table style="width: 100%;">

                {{-- Fee Amount --}}
                @if($order->order_detail->fee_amount > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">
                            Biaya {{ $order->order_detail->fee_name }}</td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                        </td>
                    </tr>
                @endif

                    {{-- Fee Credit card --}}
                    @if($order->fee_credit_card > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Biaya Kartu Kredit</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                        </tr>
                    @endif

                    @if($order->fee > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Biaya {{ $order->payment->payment_gateway }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
                        </tr>
                    @endif

                {{-- Discount --}}
                @if($order->order_detail->discount_amount > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">Diskon
                            ({{ $order->order_detail->discount_name }})
                        </td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong>
                        </td>
                    </tr>
                @endif

                {{-- Voucher --}}
                @if($order->voucher_amount > 0)
                    <tr>
                        <td style="padding: 5px 15px;" colspan="2">Diskon Voucher
                            ({{ $order->voucher_code }})
                        </td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding: 5px 15px;" colspan="2">Total</td>
                    <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->total_amount,0) }}</span></strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td style="height: 100px;"></td>
    </tr>
    @if($order->guides->count() > 0)
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">INFORMASI PANDUAN</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table style="width: 100%;">
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
    @if($order->external_notes)
        <tr>
            <td>
                <p><strong>Catatan : <br> {!! $order->external_notes !!}</strong></p>
            </td>
        </tr>
    @endif

    </tbody>
</table>

</body>
</html>




