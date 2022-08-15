<table>
    <thead>
    <tr>
        <th>
            Nomor Invoice
        </th>
        @if(!$provider)
            <th>
                Nama Provider
            </th>
            <th>
                Domain
            </th>
        @else
            <th>
                Nama Pemesan
            </th>
            <th>
                Email Pemesan
            </th>
        @endif
        <th>
            Tipe Invoice
        </th>
        <th>
            Nama Product / Nama E-Invoice
        </th>
        <th>
            Tanggal Aktivitas
        </th>
        <th>
            Jumlah
        </th>
        <th>
            Nominal Diskon
        </th>
        <th>
            Nominal Promo
        </th>
        <th>
            Kode Promo
        </th>
        <th>
            Nominal Keseluruhan
        </th>
        <th>
            Tanggal
        </th>
        <th>
            Status
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoices as $invoice)
        <tr>
            <th>
                {{$invoice->invoice_no}}
            </th>
            @if(!$provider)
                <th>
                    {{$invoice->company->company_name}}
                </th>
                <th>
                    {{$invoice->company->domain_memoria}}
                </th>
            @else
                <th>
                    {{$invoice->customer_info->first_name.' '.$invoice->customer_info->last_name}}
                </th>
                <th>
                    {{$invoice->customer_info->email}}
                </th>
            @endif
            <th>
                {{$invoice->booking_type =='offline'?'E-Invoice':'Online Booking'}}
            </th>
            <th>
                {{$invoice->order_detail->product->product_name}}
            </th>
            <th>
                {{$invoice->booking_type =='offline'?'-':$invoice->order_detail->schedule_date}}
            </th>
            <th>
                {{$invoice->booking_type =='offline'?'-':$invoice->order_detail->adult}}
            </th>
            <th>
                {{$invoice->product_discount}}
            </th>
            <th>
                {{$invoice->voucher_amount}}
            </th>
            <th>
                {{$invoice->voucher_code}}
            </th>
            <th>
                {{format_priceID($invoice->total_amount)}}
            </th>
            <th>
                {{$invoice->created_at}}
            </th>
            <th>
                {{$invoice->status_text}}
            </th>
        </tr>
    @endforeach
    </tbody>


</table>