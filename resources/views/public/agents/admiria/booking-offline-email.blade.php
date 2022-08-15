<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        .bg-primary {
            background: #76b8ff;
            color: white;
            padding: 10px;
        }

        .bg-secondary {
            background: #76b8ff;
            color: white;
            padding: 10px;
        }

        .bg-secondary td {
            padding: 15px
        }

        .bg-secondary td h4 {
            color: white
        }

        .bb-secondary {
            border-bottom: 1px solid #F6CE4E;
        }

        .br-secondary {
            border-right: 1px solid #F6CE4E;
        }

        .b-primary h3 {
            padding: 10px 0;
            margin: 0
        }

        .b-primary tr td {
            padding: 10px 0;
        }

        .b-primary tr:nth-child(1n + 2) {
            border-top: 1px solid #dcdcdc;
        }

        .b-secondary {

        }

        .title {
            font-size: 22px;
            padding: 2px;
        }

        .btn {
            padding: 10px;
            border-radius: 5px;
            color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}};
            text-decoration: none;
        }

        table {
            border-collapse: collapse
        }

        .em_greeting {
            border-top: 1px solid #dcdcdc;
            border-bottom: 1px solid #dcdcdc;
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .form_val td {
            padding: 10px;
            background: #efefef;
        }

        .payment_detail {
            line-height: 1.4rem;
        }
    </style>
</head>
<body>

<table align="center" width="680px" cellpadding="5" cellspacing="0">
    <tr style="background-color: #ecf0f1">
        <td>
            @if($company->logo)
                <img src="{{ $company->logo_url }}" height="80"/>
            @endif
        </td>
        <td>&nbsp;</td>
        <td align="right" style="padding-right: 5px"><h4>{{ $company->company_name }}</h4>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            @if($order->status == 1 || $order->status == 2 || $order->status == 3)
                <p>Congratulations! This email is to notify you that we have processed your payment and your booking is
                    now <u>confirmed</u>. Below are the details of your booking:</p>
                <p class="em_greeting">Invoice Number : <b>{{ $order->invoice_no }}</b></p>
            @endif

            @if($order->status > 4 && $order->status < 8 )
                <p>
                    Hello, you have received this email because {{$company->company_name}} could not fulfill the
                    requirements for your booking.
                    <br><br>
                    Below is a copy of your inquiry or booking:
                </p>
                <p class="em_greeting">Invoice Number : <b>{{ $order->invoice_no }}</b></p>
            @endif

            @if($order->status == 0)
                <p>
                    Thank you for booking with {{ $company->company_name }}!<br><br>

                    Invoice Number: <b>{{ $order->invoice_no }}</b><br><br>

                    You have received this email because you have made a booking on {{ $company->domain_memoria }}
                    <br><br>
                    However, the booking is not yet confirmed until you have completed payment. Below are the details of
                    your booking:
                </p>
            @endif

            @if($order->status == 8 && $order->allow_payment != 1)
                <h1>Thank you for your inquiry</h1>
                <p>
                    You have received this email because you have enquired about
                    <b> {{ $order->order_detail->product_name }} </b> with <b>{{ $company->company_name }}</b> !<br><br>

                    Invoice Number: <b>{{ $order->invoice_no }}</b><br><br>

                    However, the booking is not yet confirmed until you have completed payment. Below are the details of
                    your booking:
                </p>
            @endif


        </td>
    </tr>
    <tr>
        @if($company->color_company)
            <td colspan="3" class="bround bg-primary"
                style="background-color: #{{$company->color_company}}; color: #{{$company->color_company ? $company->color_company : FFFFFF}}">
        @else
            <td colspan="3" class="bround bg-primary"
                style="background-color: #0893CF; color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}}">
                @endif
                <strong style="color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}};">Description</strong>
            </td>
            <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="3" class="b-primary">
            <table align="center" width="680px" cellpadding="5" cellspacing="0">
                <tr>
                    <td colspan="3">
                        <p style="font-weight: bold">{{ $order->order_detail->product_name }}</p>
                    </td>
                </tr>
                <tr>

            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center">&nbsp;</td>
    </tr>


    <tr>
        <td colspan="3" class="bround bg-primary"
            style="background-color: #0893CF; color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}}">

            <strong style="color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}};">Order
                Details</strong>
        </td>

    </tr>
    <tr>
        <td colspan="3" class="b-secondary">
            <table align="center" width="680px" cellpadding="5" cellspacing="0">
                <tr>
                    <td align="left" style="border: 1px solid #ddd"><b>Description</b></td>
                    <td align="center" style="border: 1px solid #ddd"><b>Price</b></td>
                    <td align="center" style="border: 1px solid #ddd"><b>Total</b></td>
                </tr>

                @foreach($order->invoice_detail as $item)
                    <tr>
                        <td align="left"
                            style="border: 1px solid #ddd">{{ $item['qty'] }} {{$item['description']}}</td>
                        <td align="right"
                            style="border: 1px solid #ddd">{{ number_format($item['price'],0) }}</td>
                        <td align="right"
                            style="border: 1px solid #ddd">{{ number_format($item['price'] * $item['qty'],0) }}</td>
                    </tr>
                @endforeach

                {{-- @if($order->order_detail->children > 0)
                <tr>
                    <td align="left" style="border: 1px solid #ddd">{{ $order->order_detail->children }} Children</td>
                    <td align="right" style="border: 1px solid #ddd">{{ $order->order_detail->children_price>0?number_format($order->order_detail->children_price/$order->order_detail->children,0):0 }}</td>
                    <td align="right" style="border: 1px solid #ddd">{{ number_format($order->order_detail->children_price,0) }}</td>
                </tr>
                @endif

                @if($order->order_detail->infant > 0)
                <tr>
                    <td align="left" style="border: 1px solid #ddd">{{ $order->order_detail->infant }} Infant</td>
                    <td align="right" style="border: 1px solid #ddd">{{ number_format($order->order_detail->infant/$order->order_detail->infant,0) }}</td>
                    <td align="right" style="border: 1px solid #ddd">{{ number_format($order->order_detail->infant,0) }}</td>
                </tr>
                @endif --}}

                @if($order->order_detail->tax->first())
                    <tr>
                        <td align="left" style="border: 1px solid #ddd"><b>Tax Name</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Amount</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Total Tax</b></td>
                    </tr>
                    @foreach($order->order_detail->tax as $tax)
                        <tr>
                            <td align="left" style="border: 1px solid #ddd">{{ $tax->tax_name }}</td>
                            <td align="right"
                                style="border: 1px solid #ddd">{{ $tax->tax_amount }} {{ $tax->tax_amount_type ? '%' : '' }}</td>
                            <td align="right"
                                style="border: 1px solid #ddd">{{ number_format($tax->tax_amount_type ? $order->order_detail->product_total_price * $tax->tax_amount/100 : $tax->tax_amount,0) }}</td>
                        </tr>
                    @endforeach

                @endif

                @if($order->extra->first())
                    <tr>
                        <td align="left" style="border: 1px solid #ddd"><b>Extra Name</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Qty</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Total Amount</b></td>
                    </tr>
                    @foreach($order->extra as $extra)
                        <tr>
                            <td align="left" style="border: 1px solid #ddd">{{ $extra->extra_name }}</td>
                            <td align="right" style="border: 1px solid #ddd">{{ $extra->qty }}</td>
                            <td align="right"
                                style="border: 1px solid #ddd">{{ number_format($extra->amount*$extra->qty,0) }}</td>
                        </tr>
                    @endforeach

                @endif

                @if($order->order_detail->fee_amount > 0)
                    <tr>
                        <td align="left" style="border: 1px solid #ddd" colspan="2"><b>Charge</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Total Amount</b></td>
                    </tr>
                    <tr>
                        <td align="left" style="border: 1px solid #ddd"
                            colspan="2">{{ $order->order_detail->fee_name }}</td>
                        <td align="right"
                            style="border: 1px solid #ddd">{{ number_format($order->order_detail->fee_amount,0) }}</td>
                    </tr>
                @endif
                @if($order->fee_credit_card >0)
                    <tr>
                        <td align="left" style="border: 1px solid #ddd" colspan="2"><b>Credit Card Charge</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Total Amount</b></td>
                    </tr>
                    <tr>
                        <td align="left" style="border: 1px solid #ddd"
                            colspan="2">{{ $order->fee_credit_card }}</td>
                        <td align="right"
                            style="border: 1px solid #ddd">{{ number_format($order->fee_credit_card,0) }}</td>
                    </tr>
                @endif

                @if($order->order_detail->discount_amount > 0 || $order->voucher_amount > 0)
                    <tr>
                        <td align="left" style="border: 1px solid #ddd" colspan="2"><b>Discount</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Total Discount</b></td>
                    </tr>

                    @if($order->order_detail->discount_amount > 0)
                        <tr>
                            <td align="left" style="border: 1px solid #ddd"
                                colspan="2">{{ $order->order_detail->discount_name }}</td>
                            <td align="right"
                                style="border: 1px solid #ddd">{{ number_format($order->product_discount,0) }}</td>
                        </tr>
                    @endif

                    @if($order->voucher_amount > 0)
                        <tr>
                            <td align="left" style="border: 1px solid #ddd" colspan="2">Voucher Discount</td>
                            <td align="right"
                                style="border: 1px solid #ddd">{{ number_format($order->voucher_amount,0) }}</td>
                        </tr>


                    @endif

                @endif


                <tr>
                    <td align="right" colspan="3" style="border: 1px solid #ddd"><b>Total
                            Amount </b>: {{ number_format($order->total_amount,0) }}</td>
                </tr>


                <tr>
                    <td colspan="3"><b>Status </b>: {{ $order->status_text }}</td>

                </tr>

                @if($order->external_notes)
                    <tr>
                        <td colspan="3"><b>Notes </b>: {!! $order->external_notes !!}</td>
                    </tr>
                @endif

                <tr>
                    <td colspan="3" align="center">&nbsp;</td>
                </tr>

{{--                @if($company->bank->first() && $order->allow_payment != 1)--}}
{{--                    @if($order->status == 0 )--}}
{{--                        <tr>--}}
{{--                            <td colspan="3" align="left">Please make payment to:</td>--}}
{{--                        </tr>--}}

{{--                        @foreach($company->bank as $acc)--}}
{{--                            <tr>--}}
{{--                                <td colspan="3" align="left">--}}
{{--                                    <p><b>{{ $acc->bank }}</b></p>--}}
{{--                                    <p><b>{{ $acc->bank_account_number }} ({{ $acc->bank_account_name }})</b></p>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                @endif--}}

                @if($order->allow_payment == 1 && $order->status == 0)

                    <tr>
                        @if($company->color_company)
                            <td colspan="3" align="center"><a target="_blank" class="btn"
                                                              style="background-color: #{{$company->color_company}}; color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}};"
                                                              href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow">Pay
                                    Now</a></td>
                        @else
                            <td colspan="3" align="center"><a target="_blank" class="btn"
                                                              style="background-color: #0893CF; color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}};"
                                                              href="http://{{ $order->company->domain_memoria."/retrieve_booking?no_invoice=".$order->invoice_no}}&action=paynow">Pay
                                    Now</a></td>
                        @endif
                    </tr>

                @endif

                <tr>
                    <td colspan="3" align="center">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    {{--    @if(in_array($order->status,[2,3]) && $order->guides->count()>0)--}}

    @if($order->guides->count() > 0)
        <br>
        <tr>
            <td colspan="3" class="bround bg-primary"
                style="background-color: #0893CF; color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}}">

                <strong style="color: #{{$company->font_color_company ? $company->font_color_company : 'FFFFFF'}};">Guide
                    Information</strong>
            </td>

        </tr>
        <tr>
            <td colspan="3">
                <table align="center" width="680px" cellpadding="5" cellspacing="0">
                    <tr>
                        <td align="left" style="border: 1px solid #ddd"><b>No</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Guide Name</b></td>
                        <td align="center" style="border: 1px solid #ddd"><b>Phone Number</b></td>
                    </tr>
                    @foreach($order->guides as $guide)
                        <tr>
                            <td style="border: 1px solid #ddd">
                                {{$loop->index+1}}
                            </td>
                            <td style="border: 1px solid #ddd">
                                {{$guide->guide_name}}
                            </td>
                            <td style="border: 1px solid #ddd">
                                {{$guide->guide_phone_number}}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">&nbsp;</td>
        </tr>
    @endif

        {{--    @endif--}}

        <tr>
            <td align="center" colspan="3">
                If you have any questions, comments or feedback, feel free to reach out to:
            </td>
        </tr>

        <tr>
            <td align="center" colspan="2" rowspan="2">
                @if($company->logo)
                    <img src="{{ $company->logo_url }}" height="50"/>
                @endif
                <p>{{ $company->company_name }}</p>
            </td>
            <td align="center">
                <p>{{ $company->phone_company }} &nbsp;</p>
                <p>{{ $company->email_company }} &nbsp;</p>
                @if($company->address_company)
                    {!! $company->address_company !!}
                @endif

            </td>

        </tr>

</table>

</body>
</html>
