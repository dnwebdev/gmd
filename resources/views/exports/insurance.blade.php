<table>
    <thead>
    <tr>
        <th>No</th>
        <th>
            No Invoice
        </th>
        <th>
            Order Date
        </th>
        <th>
            Schedule Date
        </th>
        <th>
            Domain
        </th>
        <th>
            Total
        </th>
        @foreach($additional->take(1) as $item)
            @forelse($item->insurance_details()->orderBy('purpose_order', 'asc')->orderBy('insurance_form_id', 'asc')->get() as $data)
                <td>
                    {{ $data->label_id }}
                </td>
            @empty
            @endforelse
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($additional as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->invoice_no }}</td>
            <td>{{ \Carbon\Carbon::parse($item->order->created_at)->format('M, d Y h:i:s') }}</td>
            <td>{{ \Carbon\Carbon::parse($item->order->order_detail->schedule)->format('M, d Y') }}</td>
            <td>{{ $item->order->company->domain_memoria }}</td>
            <td>{{ $item->total }}</td>
            @php
                $insuranceData = $item->insurance_details()->orderBy('purpose_order', 'asc')->orderBy('insurance_form_id', 'asc')->get();
            @endphp
            @forelse($insuranceData as $data)
                @if ($loop->index > 1 && $insuranceData[$loop->index]->purpose_order != $insuranceData[$loop->index - 1]->purpose_order)
                    </tr><tr><td colspan="{{ 6 + $item->insurance_details->unique('label_id')->count() }}"></td>
                @endif
                <td>
                    {{ $data->value }}
                </td>
            @empty
            @endforelse
        </tr>
    @endforeach
    </tbody>
</table>
