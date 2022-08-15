@foreach($lists as $list)
    <table>
        <tbody>
        <tr>
            <th colspan="{{count($list->headers)}}"></th>
        </tr>
        </tbody>
    </table>

    <table>
        <thead>
        <tr>
            @foreach($list->headers as $header)
                <th>{{$header}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($list->data as $keys)
            <tr>
                @foreach($keys as $key=>$value)
                    <th>{{$value}}</th>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
@endforeach