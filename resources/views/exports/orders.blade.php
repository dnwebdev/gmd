<table>
    <thead>
    <tr>
        <th>No</th>
        <th>
            No Invoice
        </th>
        <th>
            Tanggal
        </th>
        <th>
            Domain
        </th>
        <th>
            Total
        </th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ $order->invoice_no }}</th>
            <th>{{ $order->created_at }}</th>
            <th>{{ $order->company->domain_memoria }}</th>
            <th>{{ $order->total_amount }}</th>
            <th>
                {{ $order->status_text }}
            </th>
        </tr>
    @endforeach
    </tbody>
</table>
