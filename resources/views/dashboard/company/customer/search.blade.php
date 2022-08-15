@if($customer->first())
  @foreach($customer as $row)
  <tr class="animated zoomIn">
    <td>{{ $row->created_at }} </td>
    <td>{{ $row->first_name }} {{ $row->last_name }}</td>
    <td>{{ $row->email }} {{ !$row->email_verified? '(Not Verified)' : ''}}</td>
    <td>{{ $row->phone }}</td>
    <td>{{ $row->status }}</td>
  </tr>
  @endforeach
@endif