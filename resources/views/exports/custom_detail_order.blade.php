<table>
    <thead>
        <tr>
            <th></th>
            @foreach($detail->unique('participant') as $p)
                <th>{{ $p->participant == 0 ? trans('customer.book.customer'): trans('customer.book.participant').' '.$loop->index }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($detail->groupBy('label_name') as $key => $value)
        <tr>
            <td>{{ $key }}</td>
            @foreach($value as $v)
                <td>
                    @if (is_array($v->value))
                        {{ implode(', ', $v->value) }}
                    @else
                        @if (in_array($v->type_custom, ['photo', 'document']))
                            <a href="{{ $v->value }}">Download</a>
                        @else
                            @switch ($v->type_custom)
                                @case('country')
                                @case('state')
                                @case('city')
                                    {{ $location[$v->type_custom][(int) $v->value] }}
                                    @break
                                @default
                                    {!! nl2br(e($v->value)) !!}
                                    @break
                            @endswitch
                        @endif
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>