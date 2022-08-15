<!DOCTYPE html>
<html>
<head>
    <title>PDF Paid Booking</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
    <tbody>
    <tr>
        <td colspan="3" style="border-collapse:collapse;width: 100%;">
            <table>
                <tr>
                    <td colspan="2" style="display:inline-flex;">
                        @if($company->logo)
                            <img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
                        @endif
                        <h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2>
                    </td>
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
            <table cellpadding="5" cellspacing="0" style="width: 100%;">
                <tbody>
                <tr>
                    <td>Name :</td>
                    <td>{{ $order->customer->first_name }}</td>
                    <td>Start Date</td>
                    <td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td>{{ $order->customer->email }}</td>
                    <td>Duration :</td>
                    <td>{{ $order->order_detail->duration }} {{ $order->order_detail->duration_type_text}}</td>
                </tr>
                <tr>
                    <td>Phone Number :</td>
                    @if ($order->customer->phone)
                        <td>{{ $order->customer->phone }}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>Status :</td>
                    <td>Paid</td>
                </tr>
                @if($order->order_detail->city)
                    <tr>
                        <!--<td>Location :</td>
                        <td>{{ $order->order_detail->city->city_name }}</td>!-->
                        <td>Payment Method :</td>
                        @if($order->allow_payment == 1)
                            @if($order->payment)
                                <td>{{$order->payment->payment_gateway}}</td>
                            @else
                                <td>Unknown</td>
                            @endif
                        @endif

                        @if($order->allow_payment != 1)
                            <td>Manual Transfer</td>
                        @endif
                    </tr>
                @endif
                @if($order->order_detail->product && $order->order_detail->product->schedule->count()>1)
                    <tr>
                        <td>Operational Hours</td>
                        <td>{{ $order->order_detail->product->schedule[0]->start_time }}
                            - {{ $order->order_detail->product->schedule[0]->end_time }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <br>
            <table style="width: 640px">
                <tbody>
                @if($company->address_company)
                    <!--<tr>
                        <td style="width: 90px;">Address :</td>
                        <td>{!! $company->address_company !!}</td>
                    </tr>!-->
                @endif
                <tr>
                    <td style="width: 90px;">Description :</td>
                    <td>{{ $order->order_detail->product_description }}</td>
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
            <table cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                <thead>
                    <tr style="background-color: rgb(239,247,254);text-align: center;">
                        <th style="padding: 15px;width: 20%;">Product Name</th>
                        <th style="padding: 15px;width: 25%;">Guest</th>
                        <th style="padding: 15px;width: 15%;">Price</th>
                        <th style="padding: 15px;width: 30%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 15px;">{{ $order->order_detail->product_name }}</td>
                        <td style="padding: 15px;text-align: center">{{ $order->order_detail->adult }} {{optional($order->order_detail->unit)->name}}</td>
                        <td style="padding: 15px;text-align: right">{{ number_format($order->order_detail->adult_price,0) }}</td>
                        <td style="padding: 15px;" align="right">
                            <strong>IDR
                                <span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                            </strong>
                        </td>
                    </tr>
                    @if($order->additional_orders()->whereType('insurance')->count() > 0)
                        @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                            <tr>
                                <td style="padding: 15px;">{{ $add->description_en }}</td>
                                <td style="padding: 15px;text-align: center">{{ $add->quantity }}</td>
                                <td style="padding: 15px;text-align: right">{{ number_format($add->price,0) }}</td>
                                <td style="padding: 15px;" align="right"><strong>IDR <span
                                        >{{ number_format($add->total,0) }}</span></strong>
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
    @if($order->guides->count() > 0)
        <tr>
            <td colspan="3" style="width: 100%;text-align: center;">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">GUIDE INFORMATION</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table style="width: 100%;">
                    <thead>
                    <tr style="text-align: left;">
                        <th style="padding: 15px;">No</th>
                        <th style="padding: 15px;">Guide Name</th>
                        <th style="padding: 15px;">Phone Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->guides as $guide)
                        <tr>
                            <td style="padding: 15px;">{{$loop->index+1}}</td>
                            <td style="padding: 15px;">{{$guide->guide_name}}</td>
                            <td style="padding: 15px;">{{$guide->guide_phone_number}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </td>
        </tr>
    @endif
    @if($order->external_notes)
        <tr>
            <td colspan="3">
                <p>Notes : <br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="5">
            <p style="margin-top: 3rem; margin-bottom: 2rem;">Thank you your order! Please contact us if you have any questions.</p>
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
        <td colspan="3" style="border-collapse:collapse;width: 100%;">
            <table>
                <tr>
                    <td colspan="2" style="display:inline-flex;">
                        @if($company->logo)
                            <img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
                        @endif
                        <h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="3">
            <p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $order->customer->first_name }},</p>
            <p>
                Selamat! Pembayaran Anda pada pesanan {{ $order->invoice_no }} telah berhasil <b>dikonfirmasi</b>. Rincian pesanan Anda dapat dilihat seperti berikut:
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table cellpadding="5" cellspacing="0" style="width: 100%;">
                <tbody>
                <tr>
                    <td>Nama :</td>
                    <td>{{ $order->customer->first_name }}</td>
                    <td>Tanggal Mulai :</td>
                    <td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td>{{ $order->customer->email }}</td>
                    <td>Durasi :</td>
                    <td>{{ $order->order_detail->duration }} {{ $order->order_detail->duration_type_text}}</td>
                </tr>
                <tr>
                    <td>No. Telpon :</td>
                    @if ($order->customer->phone)
                        <td>{{ $order->customer->phone }}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>Status :</td>
                    <td>Lunas</td>
                </tr>
                @if($order->order_detail->city)
                    <tr>
                        <!-- <td>Lokasi :</td>
                        <td>{{ $order->order_detail->city->city_name }}</td> -->
                        <td>Metode Pembayaran :</td>
                        @if($order->allow_payment == 1)
                            @if($order->payment)
                                <td>{{$order->payment->payment_gateway}}</td>
                            @else
                                <td>Tidak Diketahui</td>
                            @endif
                        @endif

                        @if($order->allow_payment != 1)
                            <td>Manual Transfer</td>
                        @endif
                    </tr>
                @endif
                @if($order->order_detail->product && $order->order_detail->product->schedule->count()>1)
                    <tr>
                        <td>Jam Operasional :</td>
                        <td>{{ $order->order_detail->product->schedule[0]->start_time }}
                            - {{ $order->order_detail->product->schedule[0]->end_time }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <table style="width: 640px">
                <tbody>
                @if($company->address_company)
                    <!-- <tr>
                        <td style="width: 90px;">Alamat :</td>
                        <td>{!! $company->address_company !!}</td>
                    </tr> -->
                @endif
                <tr>
                    <td style="width: 90px;">Deskripsi :</td>
                    <td>{{ $order->order_detail->product_description }}</td>
                </tr>
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
            <table cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
                <thead>
                    <tr style="background-color: rgb(239,247,254);text-align: center;">
                        <th style="padding: 15px;width: 20%;">Nama Produk</th>
                        <th style="padding: 15px;width: 25%;">Tamu</th>
                        <th style="padding: 15px;width: 15%;">Harga</th>
                        <th style="padding: 15px;width: 30%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 15px;">{{ $order->order_detail->product_name }}</td>
                        <td style="padding: 15px;text-align: center">{{ $order->order_detail->adult }} {{optional($order->order_detail->unit)->name}}</td>
                        <td style="padding: 15px;text-align: right">{{ number_format($order->order_detail->adult_price,0) }}</td>
                        <td style="padding: 15px;" align="right">
                            <strong>IDR
                                <span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                            </strong>
                        </td>
                    </tr>
                    @if($order->additional_orders()->whereType('insurance')->count() > 0)
                        @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                            <tr>
                                <td style="padding: 15px;">{{ $add->description_id }}</td>
                                <td style="padding: 15px;text-align: center">{{ $add->quantity }}</td>
                                <td style="padding: 15px;text-align: right">{{ number_format($add->price,0) }}</td>
                                <td style="padding: 15px;" align="right"><strong>IDR <span
                                        >{{ number_format($add->total,0) }}</span></strong>
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
    @if($order->guides->count() > 0)
        <tr>
            <td colspan="3" style="width: 100%;text-align: center;">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">INFORMASI PANDUAN</label>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table style="width: 100%;">
                    <thead>
                    <tr style="text-align: left;">
                        <th style="padding: 15px;">No</th>
                        <th style="padding: 15px;">Nama Pemandu</th>
                        <th style="padding: 15px;">No. Telpon</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->guides as $guide)
                        <tr>
                            <td style="padding: 15px;">{{$loop->index+1}}</td>
                            <td style="padding: 15px;">{{$guide->guide_name}}</td>
                            <td style="padding: 15px;">{{$guide->guide_phone_number}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </td>
        </tr>
    @endif
    @if($order->external_notes)
        <tr>
            <td colspan="3">
                <p>Catatan : <br> {!! $order->external_notes !!}</p>
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="5">
            <p style="margin-top: 3rem; margin-bottom: 2rem;">Terima kasih atas kepercayaan Anda menggunakan Mygomodo.
                Kami menunggu pesanan Anda berikutnya.</p>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>



