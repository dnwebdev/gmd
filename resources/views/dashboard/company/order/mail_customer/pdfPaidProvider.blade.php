<!DOCTYPE html>
<html>
<head>
	<title>PDF Paid Booking Provider</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
	<tbody>
	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $company->company_name }},</p>
			<p>
				Hello Provider, You receive a new order for {{ $order->order_detail->product_name }} Your order status is Paid. Please check  the order details by accessing the button below :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width:100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 1 && optional($order->payment)->status == 'PAID' || $order->status == 3)
						<td>Paid</td>
					@endif
					@if ($order->status == 1 && optional($order->payment)->status == 'PENDING')
						<td>Waiting for settlement</td>
					@endif
					<td>Order Date :</td>
					<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
				</tr>
				<tr>
					<td>Payment Method :</td>\
					@if($order->allow_payment == 1)
						@if($order->payment)
							<td>{{$order->payment->payment_gateway}}</td>
						@else
							<td>Unknown</td>
						@endif

					@endif

					@if($order->allow_payment != 1)
						<td>Manual Transfer</td>
					@endif
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 5%">
			<label for="" style="color: rgb(59,70,80);font-weight: bold;">ORDER DETAIL</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="background-color: rgb(239,247,254);">
					<th style="width: 61%;padding: 15px;">Product Name</th>
					<th style="width: 30%;padding: 15px;">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px;">{{ $order->order_detail->product_name }}</td>
					<td style="padding: 15px;" align="right">
						<strong>{{ $order->order_detail->currency }}
							<span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
						</strong>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table style="width: 100%;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Charge {{ $order->order_detail->fee_name }}</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Fee Credit card --}}
				@if($order->fee_credit_card >0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Credit Card Charge</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
					</tr>
				@endif
				{{-- Fee --}}
				@if ($order->fee > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Charge {{ $order->payment->payment_gateway }}</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Discount ({{ $order->order_detail->discount_name }})</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Voucher Discount ({{ $order->voucher_code }})</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif
				@if($order->additional_orders()->whereType('insurance')->count() > 0)
					@foreach($order->additional_orders()->whereType('insurance')->get() as $add)
						<tr>
							<td style="padding: 5px 15px" colspan="2">{{$add->description_en}}
								({{ $add->quantity .' * IDR '. number_format($add->price,0) }})
							</td>
							<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($add->total,0) }}</span></strong>
							</td>
						</tr>
					@endforeach
				@endif

				<tr>
					<td style="padding: 5px 15px" colspan="2">Total</td>
					<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->external_notes)
		<tr>
			<td colspan="3" style="padding-left: 12px;">
				<p>Notes : <br> {!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif
	</tbody>
</table>

<hr>
<br>

{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
	<tbody>
	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $company->company_name }},</p>
			<p>
				Kami ingin memberitahukan bahwa pembayaran dengan nomor invoice : {{ $order->invoice_no }} telah diterima. Detail pembayaran adalah sebagai berikut :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width:100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 1 && optional($order->payment)->status == 'PAID' || $order->status == 3)
						<td>Sudah di bayar</td>
					@endif
					@if ($order->status == 1 && optional($order->payment)->status == 'PENDING')
						<td>Menunggu Konfirmasi Pembayaran Selesai</td>
					@endif
					<td>Tanggal Pemesanan :</td>
					<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
				</tr>
				<tr>
					<td>Payment Method :</td>\
					@if($order->allow_payment == 1)
						@if($order->payment)
							<td>{{$order->payment->payment_gateway}}</td>
						@else
							<td>Tidak diketahui</td>
						@endif

					@endif

					@if($order->allow_payment != 1)
						<td>Manual Transfer</td>
					@endif
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 5%">
			<label for="" style="color: rgb(59,70,80);font-weight: bold;">DETAIL ORDER</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="background-color: rgb(239,247,254);">
					<th style="width: 61%;padding: 15px;">Nama Produk</th>
					<th style="width: 30%;padding: 15px;">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px">{{ $order->order_detail->product_name }}</td>
					<td style="padding: 15px;" align="right">
						<strong>{{ $order->order_detail->currency }}
							<span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
						</strong>
					</td>
				</tr>
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table style="width: 100%;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Biaya {{ $order->order_detail->fee_name }}</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card > 0)
						<tr>
							<td style="padding: 5px 15px" colspan="2">Biaya Kartu Kredit</td>
							<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee > 0)
						<tr>
							<td style="padding: 5px 15px" colspan="2">Biaya {{ $order->payment->payment_gateway }}</td>
							<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Diskon ({{ $order->order_detail->discount_name }})</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Diskon Voucher ({{ $order->voucher_code }})</td>
						<td style="padding: 5px 15px" colspan="2"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				@if($order->additional_orders()->whereType('insurance')->count() > 0)
					@foreach($order->additional_orders()->whereType('insurance')->get() as $add)
						<tr>
							<td style="padding: 5px 15px" colspan="2">{{$add->description_id}}
								({{ $add->quantity .' * IDR '. number_format($add->price,0) }})
							</td>
							<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($add->total,0) }}</span></strong>
							</td>
						</tr>
					@endforeach
				@endif

				<tr>
					<td style="padding: 5px 15px" colspan="2">Total</td>
					<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->external_notes)
		<tr>
			<td colspan="3" style="padding-left: 12px;">
				<p>Catatan : <br> {!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif
	</tbody>
</table>
{{--<table width="100">--}}
{{--	<tr>--}}
{{--		<td colspan="2"  style="border-collapse:collapse;width: 100%;">--}}
{{--			<img src="{{asset('uploads/orders/'.$order->invoice_no.'.png')}}" alt="">--}}
{{--		</td>--}}
{{--	</tr>--}}
{{--</table>--}}
</body>
</html>




