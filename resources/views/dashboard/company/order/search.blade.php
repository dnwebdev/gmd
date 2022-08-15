@if($order->first())
  @foreach($order as $row)
  <tr class="zoomIn animated">
    <td>{{ $row->created_at }} </td>
    <td><a href="{{Route('company.order.edit',$row->invoice_no)}}">{{ $row->invoice_no }}</a></td>
    <td><a href="{{Route('company.product.edit',$row->order_detail->product->id_product)}}" target="_blank">{{ $row->order_detail->product->product_name }}</a></td>
    <td>{{ $row->voucher_code }}</td>
    <td class="right-align">{{ $row->order_detail->product->currency }} {{ number_format($row->total_amount,0) }}</td>
    <td class="center">{{ $row->status_text }}</td>
  </tr>
  @endforeach

@endif