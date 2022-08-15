@if($type =='hasSuccessfulTransaction' || $type=='hasTransaction')
    <table>
        <thead>
        <tr>
            <th>
                Provider Name
            </th>
            <th>
                Domain
            </th>
            <th>
                Total Transaction
            </th>
            <th>
                Total Amount
            </th>

        </tr>
        </thead>
        <tbody>
        @foreach($companies as $invoice)
            <tr>
                <th>
                    {{$invoice->company_name}}
                </th>
                <th>
                    {{$invoice->domain_memoria}}
                </th>
                <th>
                    {{format_priceID($invoice->gmv)}}
                </th>
                <th>
                    {{$invoice->total_transaction}}
                </th>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <table>
        <thead>
        <tr>
            <th>
                Owner Name
            </th>
            <th>
                Email Login
            </th>

            <th>
                Provider Name
            </th>
            <th>
                Domain
            </th>
            <th>
                Email Business
            </th>

        </tr>
        </thead>
        <tbody>
        @foreach($companies as $invoice)
            <tr>
                <th>
                    {{$invoice->agent->first_name.' '.$invoice->agent->last_name}}
                </th>
                <th>
                    {{$invoice->agent->email}}
                </th>
                <th>
                    {{$invoice->company_name}}
                </th>
                <th>
                    {{$invoice->domain_memoria}}
                </th>
                <th>
                    {{$invoice->email_company}}
                </th>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif