{{--<table>--}}
{{--    <tbody>--}}
{{--    <tr>--}}
{{--        <th colspan="{{count($list->headers)}}">{{$list->title}}</th>--}}
{{--    </tr>--}}
{{--    </tbody>--}}
{{--</table>--}}

<table>
    <thead>
    <tr>
        @foreach($list->headers as $header)
            <th>{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @if(isset($list->data))
        @foreach($list->data as $keys)
            <tr>
                @foreach($keys as $key=>$value)
                    <th>{{$value}}</th>
                @endforeach
            </tr>
        @endforeach
    @endif
    </tbody>
</table>