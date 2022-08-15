<table class="striped">
<thead>
  <tr>
    <th data-field="name"></th>
    <th data-field="name"></th>
    <th data-field="name">Extra Name</th>
    <th data-field="name">Extra Type</th>
    <th data-field="name">Currency</th>
    <th data-field="name" class="align-right">Amount</th>
  </tr>
</thead>
<tbody>
@foreach($extra as  $key=>$row)

  <tr>
    <td class="center">
      <p>
        <input type="checkbox" class="selextra" id="selextra_{{$key}}" value="{{ $row->id_extra }}" />
        <label for="selextra_{{$key}}">&nbsp;</label>
      </p>
    </td>
    <td>
      <input type="hidden" class="extraval" name="extra[]" value="{{ $row->id_extra }}">
      @if($row->image)
        <img height="50" class="extra_image" src="{{ asset('uploads/extras/'.$row->image) }}">
      @endif
      <input type="hidden" class="extra_id" value="{{ $row->extra_id }}">
      <input type="hidden" class="extra_amount" value="{{ $row->amount }}">
      <input type="hidden" class="extra_price_type" value="{{ $row->extra_price_type }}">
      
    </td>
    
    <td>
      <a href="{{ Route('company.extra.edit',$row->id_extra) }}" class="extra_name">{{ $row->extra_name }}</a>
    </td>
    <td class="extra_price_type_text">{{ $row->priceTypeText }}</td>
    <td class="center extra_currency">{{ $row->currency }}</td>
    <td class="right-align extra_amount_text">{{ number_format($row->amount,0) }}</td>
    
  </tr>

@endforeach
</tbody>
</table>