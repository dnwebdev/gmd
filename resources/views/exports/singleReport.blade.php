<table>
    <thead>
    <tr>
        @foreach($list->headers as $header)
            <th>{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($list->data as $keys)


            <td>{{$keys}}</td>


        @endforeach
    </tr>
    </tbody>
</table>