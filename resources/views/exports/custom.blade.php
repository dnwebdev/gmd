<table>
    <thead>
    <tr>
        @foreach($headers as $header)
            <th>{{$header}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($results as $result)
       <tr>
           @foreach($headers as $header)
               <th>{{$result->$header}}</th>
           @endforeach
       </tr>
    @endforeach
    </tbody>


</table>
