<table>
    <thead>
    <tr>
        @foreach($list['headers'] as $header)
            <th>{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>

    @foreach($list['data'] as $keys)
        <tr>
            @foreach($keys as $item=>$value)
                <td>{{$value}}</td>
            @endforeach
        </tr>
    @endforeach

    </tbody>
</table>