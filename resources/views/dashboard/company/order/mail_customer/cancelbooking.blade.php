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
                    Your order with an invoice number {{ $order->invoice_no }} has been canceled by the system.
We have not been able to process your order because the payment has not been completed. Please contact your tour agent if you want to continue your order.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table align="center" cellpadding="5" cellspacing="0" style="width:100%;">
                    <tbody>
                        <tr>
                            <td>Description :</td>
                            <td>{{ $order->order_detail->product_description }}</td>
                            <td>Start Date</td>
                            <td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                        </tr>
                        <tr>
                            <td>Duration :</td>
                            <td>{{ $order->order_detail->duration }} {{ $order->order_detail->duration_type_text}}</td>
                            <td>Status :</td>
                            <td>Canceled</td>
                        </tr>
                        <tr>
                            <td>Phone Number :</td>
                            <td>{{ $company->phone_company }}</td>
                            <td>Email :</td>
                            <td>{{ $company->email_company }}</td>
                        </tr>
                        @if($order->order_detail->city || $order->allow_payment == 1 || $order->allow_payment != 1)
                            <tr>
                               <!-- <td>Location :</td>
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
                        @if($company->address_company)
                            <!--<tr>
                                <td>Address :</td>
                                <td>{!! $company->address_company !!}</td>
                            </tr>!-->
                        @endif
                        @if($order->order_detail->product && $order->order_detail->product->schedule->count()>1)
                        <tr>
                            <td>Operational Hours</td>
                            <td>{{ $order->order_detail->product->schedule[0]->start_time }} - {{ $order->order_detail->product->schedule[0]->end_time }}</td>
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
                    <tr style="text-align: center;">
                        <th style="width: 20%; background-color: rgb(239,247,254);padding: 15px;">Product Name</th>
                        <th style="width: 25%; background-color: rgb(239,247,254);padding: 15px;">Unit</th>
                        <th style="width: 15%; background-color: rgb(239,247,254);padding: 15px;">Price</th>
                        <th style="width: 30%; background-color: rgb(239,247,254);padding: 15px;">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="padding:5px 15px;">{{ $order->order_detail->product_name }}</td>
                        <td style="padding:5px 15px;text-align: center">{{ $order->order_detail->adult }} {{optional($order->order_detail->unit)->name}}</td>
                        <td style="padding:5px 15px;" align="right">{{ number_format($order->order_detail->adult_price,0) }}</td>
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
                                <td style="padding:5px 15px;" align="right"><strong>IDR <span
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
                <table style="float: right;width:100%;">

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
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong>
                            </td>
                        </tr>
                    @endif

                    {{-- Fee --}}
                    @if ($order->fee > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">
                                Charge {{ $order->payment->payment_gateway }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong>
                            </td>
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
                        <td style="padding: 5px 15px;" colspan="2"><strong>Total</strong></td>
                        <td style="padding: 5px 15px;" align="right"><strong>IDR
                                <span>{{ number_format($order->total_amount,0) }}</span></strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @if($order->guides->count() > 0)
        <br>
        <tr>
            <td colspan="3" style="padding-top: 5%">
                <label for="" style="color: rgb(59,70,80);font-weight: bold;">GUIDE INFORMATION</label>
            </td>
        </tr>
            <tr>
                <td colspan="3">
                    <table style="float: right;padding-right: 8%;width:100%;">
                        <thead>
                        <tr>
                            <th style="width: 20%;">No</th>
                            <th style="width: 25%">Guide Name</th>
                            <th style="width: 15%">Phone Number</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->guides as $guide)
                            <tr>
                                <td style="width: 20%;padding: 5px;">{{$loop->index+1}}</td>
                                <td style="width: 25%;padding: 5px;">{{$guide->guide_name}}</td>
                                <td style="width: 15%;padding: 5px;">{{$guide->guide_phone_number}}</td>
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
                <td colspan="3">
                    <p>Notes : <br> {!! $order->external_notes !!}</p>
                </td>
            </tr>
        @endif
{{--        <tr>--}}
{{--            <td colspan="3">--}}
{{--                <button class="button">Follow Up Order</button>--}}
{{--            </td>--}}
{{--        </tr>--}}

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
                    Pesanan Anda dengan nomor invoice {{ $order->invoice_no }} telah dibatalkan oleh sistem. Kami belum dapat memproses pesanan Anda karena pembayaran belum selesai. Silahkan hubungi agen penyedia jasa Anda jika Anda ingin melanjutkan pesanan.
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <table cellpadding="5" cellspacing="0" style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>Deskripsi :</td>
                            <td>{{ $order->order_detail->product_description }}</td>
                            <td>Tanggal dimulai :</td>
                            <td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
                        </tr>
                        <tr>
                            <td>Durasi :</td>
                            <td>{{ $order->order_detail->duration }}
                                @if($order->order_detail->duration_type_text === 'Hours')
                                    Jam
                                    @else
                                    Hari
                                @endif
                            </td>
                            <td>Status :</td>
                            <td>dibatalkan</td>
                        </tr>
                        <tr>
                            <td>Nomor Telepon :</td>
                            <td>{{ $company->phone_company }}</td>
                            <td>Email :</td>
                            <td>{{ $company->email_company }}</td>
                        </tr>
                        @if($order->order_detail->city || $order->allow_payment == 1 || $order->allow_payment != 1)
                            <tr>
                                <!-- <td>Lokasi :</td>
                                <td>{{ $order->order_detail->city->city_name }}</td> -->
                                <td>Metode Pembayaran :</td>
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
                        @if($company->address_company)
                        <!-- <tr>
                            <td>Alamat :</td>
                            <td>{!! $company->address_company !!}</td>
                        </tr> -->
                        @endif
                        @if($order->order_detail->product && $order->order_detail->product->schedule->count()>1)
                            <tr>
                                <td>Jam Operasi</td>
                                <td>{{ $order->order_detail->product->schedule[0]->start_time }} - {{ $order->order_detail->product->schedule[0]->end_time }}</td>
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
                    <tr style="text-align: center;">
                        <th style="width: 20%; background-color: rgb(239,247,254);padding: 15px;">Nama Produk</th>
                        <th style="width: 25%; background-color: rgb(239,247,254);padding: 15px;">Unit</th>
                        <th style="width: 15%; background-color: rgb(239,247,254);padding: 15px;">Harga</th>
                        <th style="width: 30%; background-color: rgb(239,247,254);padding: 15px;">Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="padding:5px 15px;">{{ $order->order_detail->product_name }}</td>
                        <td style="padding:5px 15px;text-align: center">{{ $order->order_detail->adult }} {{optional($order->order_detail->unit)->name}}</td>
                        <td style="padding:5px 15px;" align="right">{{ number_format($order->order_detail->adult_price,0) }}</td>
                        <td style="padding:5px 15px;" align="right">
                            <strong>IDR
                                <span >{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
                            </strong>
                        </td>
                    </tr>
                    @if($order->additional_orders()->whereType('insurance')->count() > 0)
                        @foreach($order->additional_orders()->whereType('insurance')->get() as $add)
                            <tr>
                                <td style="padding:5px 15px;">{{ $add->description_id }}</td>
                                <td style="padding:5px 15px;text-align: center">{{ $add->quantity }}</td>
                                <td style="padding:5px 15px;" align="right">{{ number_format($add->price,0) }}</td>
                                <td style="padding:5px 15px;" align="right"><strong>IDR <span
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
                <table style="float: right;width:100%;">

                    {{-- Fee Amount --}}
                    @if($order->order_detail->fee_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">
                                Biaya {{ $order->order_detail->fee_name }}</td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                            >{{ number_format($order->order_detail->fee_amount,0) }}</span></strong>
                            </td>
                        </tr>
                    @endif

                        {{-- Fee Credit card --}}
                        @if($order->fee_credit_card > 0)
                            <tr>
                                <td style="padding: 5px 15px;" colspan="2">Biaya Kartu Kredit</td>
                                <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                                >{{ number_format($order->fee_credit_card,0) }}</span></strong>
                                </td>
                            </tr>
                        @endif

                        @if($order->fee > 0)
                            <tr>
                                <td style="padding: 5px 15px;" colspan="2">
                                    Biaya {{ $order->payment->payment_gateway }}</td>
                                <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                                style>{{ number_format($order->fee,0) }}</span></strong>
                                </td>
                            </tr>
                        @endif

                    {{-- Discount --}}
                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Diskon
                                ({{ $order->order_detail->discount_name }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                            style="color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong>
                            </td>
                        </tr>
                    @endif

                    {{-- Voucher --}}
                    @if($order->voucher_amount > 0)
                        <tr>
                            <td style="padding: 5px 15px;" colspan="2">Voucher Diskon
                                ({{ $order->voucher_code }})
                            </td>
                            <td style="padding: 5px 15px;" align="right"><strong>IDR <span
                                            style="color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong>
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
        @if($order->guides->count() > 0)
            <br>
            <tr>
                <td colspan="3" style="padding-top: 5%">
                    <label for="" style="color: rgb(59,70,80);font-weight: bold;">INFORMASI PANDUAN</label>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <table class="total padding" style="float: right;padding-right: 8%; width: 100%;">
                        <thead>
                        <tr>
                            <th style="width: 20%;">No</th>
                            <th style="width: 25%">Nama Pemandu</th>
                            <th style="width: 15%">Nomor Telepon</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->guides as $guide)
                            <tr>
                                <td style="width: 20%;">{{$loop->index+1}}</td>
                                <td style="width: 25%">{{$guide->guide_name}}</td>
                                <td style="idth: 15%">{{$guide->guide_phone_number}}</td>
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
                <td colspan="3">
                    <p>Catatan : <br> {!! $order->external_notes !!}</p>
                </td>
            </tr>
        @endif
        <tr>
            <td colspan="3">
                Terima kasih sudah memesan menggunakan {{ $company->domain_memoria }}
                <br>
                Gomodo Customer Support
            </td>
        </tr>
{{--        <tr>--}}
{{--            <td colspan="3">--}}
{{--                <button class="button">Follow Up Order</button>--}}
{{--            </td>--}}
{{--        </tr>--}}

    </tbody>
</table>
</body>
</html>




