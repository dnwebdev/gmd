<style>
    table, td, th {
      border: 1px solid black;
    }
    th {
        font-size: 14px;
    }
</style>
<table style="border: 1px solid #000;">
    <thead>
        <tr>
            <th>@lang('product_provider.export_order.excel.invoice_no')</th>
            <th>@lang('product_provider.export_order.excel.customer_name')</th>
            <th>@lang('product_provider.export_order.excel.order_date')</th>
            <th>@lang('product_provider.export_order.excel.total_participant')</th>
            <th>@lang('product_provider.export_order.excel.status')</th>
            <th>@lang('product_provider.export_order.excel.activity_date')</th>
            <th>@lang('product_provider.export_order.excel.price')</th>
            <th>@lang('product_provider.export_order.excel.payment_type')</th>
            <th>@lang('product_provider.export_order.excel.email')</th>
            <th>@lang('product_provider.export_order.excel.phone_number')</th>
            @if ($customSchema->isNotEmpty())
                <th colspan="{{ $customSchema->count() + 1 }}" style="text-align: center;">@lang('product_provider.export_order.excel.additional_info')</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr {!! $loop->iteration % 2 == 0 ? 'style="background-color: #eee"' : '' !!}>
                <td>{{ $order->invoice_no }}</td>
                <td>{{ $order->customer_info->first_name }} {{ $order->customer_info->last_name }}</td>
                <td>{{ $order->created_at }}</td>
                <td>{{ $order->order_detail->adult }}</td>
                <td>{{ $order->status_text }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_detail->schedule_date)->format('M d, Y') }}</td>
                <td>{{ $order->total_amount_text }}</td>
                <td>{{ $order->booking_type == 'online' ? optional($order->payment)->payment_gateway  ?? 'Online' : $order->booking_type }} </td>
                <td>{{ $order->customer_info->email }}</td>
                <td>{{ $order->customer_info->phone ?? '-' }}</td>

                @if ($customSchema->isNotEmpty() && $order->order_detail->customDetail->isNotEmpty())
                    <td>Data</td>
                    @foreach ($customSchema as $schema)
                        <td>{{ $schema->label_name }}</td>
                    @endforeach
                @endif
            </tr>
            @if ($customSchema->isNotEmpty() && $order->order_detail->customDetail->isNotEmpty())
                @foreach ($order->order_detail->customDetail->groupBy('participant') as $group)
                    <tr {!! $loop->parent->iteration % 2 == 0 ? 'style="background-color: #eee"' : '' !!}>
                        <td colspan="10"></td>
                        <td>{{ $loop->index == 0 ? trans('customer.book.customer') : trans('customer.book.participant').' '.$loop->index }}</td>
                        @foreach ($group as $v)
                            @if ($v->participant == 0)
                                <td>{!! valueCustomInfo($v, $location) !!}</td>
                            @else
                                @foreach ($allParticipant as $exists)
                                    <td>{!! $exists ? valueCustomInfo($v, $location) : '-' !!}</td>
                                @endforeach
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>

@php
function valueCustomInfo($v, $location) {
    if (is_array($v->value)) {
        return implode(', ', $v->value);
    } else {
        if (in_array($v->type_custom, ['photo', 'document'])) {
            return '<a href="'.$v->value.'">Download</a>';
        } else {
            switch ($v->type_custom) {
                case 'country':
                case 'state':
                case 'city':
                    return $location[$v->type_custom][(int) $v->value];
                    break;
                default:
                    return nl2br(e($v->value));
                    break;
            }
        }
    }
}
@endphp
