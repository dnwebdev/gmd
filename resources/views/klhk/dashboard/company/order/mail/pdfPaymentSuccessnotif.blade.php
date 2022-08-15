<!DOCTYPE html>
<html lang="en">
<head>
	<title>PDF</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">
{{--Mail English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
	<tbody>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					@if($company->logo)
						<img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
					@endif
					<td style="width: 25%;"><h2 style="color:#b51fb5; float: right;">{{ $order->invoice_no }}</h2></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="3">
			<p class="caption">Hello {{ $company->company_name }},</p>
			<p style="text-align: justify">
				We want to inform you that your payment with invoice number: {{ $order->invoice_no }} has been received. Your payment details are as follows:
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<label class="caption" for="">ORDER DETAILS</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="width:680px; text-align: left;">
				<tbody>
				<tr>
					<td style="width: 140px;">Product Name</td>
					<td>: {{ $order->order_detail->product_name }}</td>
				</tr>
				@if ($order->order_detail->city)
					<tr>
						<td style="width: 140px;">City</td>
						<td>: {{ $order->order_detail->city ? '('.$order->order_detail->city->city_name.')' : '' }}</td>
					</tr>
				@endif
				<tr>
					<td style="width: 140px;">Schedule :</td>
					<td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
				</tr>
				<tr>
					<td style="width: 140px;">Guest</td>
					<td>
						: {{ $order->order_detail->adult }} {{ optional($order->order_detail->unit)->name }}
						@if(optional($order->order_detail->unit)->name === '-')
							Pax/Unit
						@endif
						{{ ($order->order_detail->children > 0) ? ', '.$order->order_detail->children.' Children' : '' }} {{ ($order->order_detail->infant > 0) ? ', '.$order->order_detail->infant.' Infant' : '' }}
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<p style="font-style: italic;text-align: center;">
				We are waiting for your next order. Thank you <br>
				Gomodo team
			</p>
		</td>
	</tr>
	</tbody>
</table>

<hr>
<br>

{{--Mail Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px; margin: auto;">
	<tbody>
	<tr>
		<td colspan="3">
			<table width="100%">
				<tr>
					@if($company->logo)
						<img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
					@endif
					<td style="width: 25%;"><h2 style="color:#b51fb5; float: right;">{{ $order->invoice_no }}</h2></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="3">
			<p class="caption">Halo {{ $company->company_name }},</p>
			<p style="text-align: justify">
				Kami ingin memberitahukan bahwa pembayaran dengan nomor invoice : {{ $order->invoice_no }} telah diterima. Detail pembayaran adalah sebagai berikut :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<label class="caption" for="">DETAIL ORDER</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="width:680px; text-align: left;">
				<tbody>
				<tr>
					<td style="width: 140px;">Nama Produk</td>
					<td>: {{ $order->order_detail->product_name }}</td>
				</tr>
				@if ($order->order_detail->city)
					<tr>
						<td style="width: 140px;">Kota</td>
						<td>: {{ $order->order_detail->city ? '('.$order->order_detail->city->city_name.')' : '' }}</td>
					</tr>
				@endif
				<tr>
					<td style="width: 140px;">Jadwal</td>
					<td>: {{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
				</tr>
				<tr>
					<td style="width: 140px;">Tamu</td>
					<td>
						: {{ $order->order_detail->adult }} {{ optional($order->order_detail->unit)->name }}
						@if(optional($order->order_detail->unit)->name === '-')
							Pax/Unit
						@endif
						{{ ($order->order_detail->children > 0) ? ', '.$order->order_detail->children.' Children' : '' }} {{ ($order->order_detail->infant > 0) ? ', '.$order->order_detail->infant.' Infant' : '' }}
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<p style="font-style: italic;text-align: center;">
				Terimakasih atas kami tunggu pesanan anda berikutnya <br>
				Gomodo team
			</p>
		</td>
	</tr>
	</tbody>
</table>
</body>
</html>
