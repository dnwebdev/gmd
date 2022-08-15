<!DOCTYPE html>
<html>
<head>
	<title>PDF Unpaid Booking Provider</title>
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
				You receive a new order for {{ $order->order_detail->product_name }} Your order status is waiting for payment. Here is the detail :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table style="border-collapse:collapse;width: 100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 0)
						<td>Waiting for transfer</td>
					@endif
					<td>Order Date :</td>
					<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
				</tr>
				@if ($order->order_detail->discount_amount > 0)
					<tr>
						@if($order->order_detail->discount_amount > 0)
							<td>Discount Name :</td>
							<td>
								{{ $order->order_detail->discount_name }}
							</td>
						@endif
					</tr>
				@endif
				@if($order->status == 0)
					<tr>
						<td>Payment Method :</td>
						@if($order->allow_payment == 1)
							@if($order->payment)
								@if ($order->payment->payment_gateway == 'Cash On Delivery')
									Cash
								@else
									<td>{{$order->payment->payment_gateway}}</td>
								@endif
							@else
								<td>Unknown</td>
							@endif

						@endif

						@if($order->allow_payment != 1)
							<td>Manual Transfer</td>
						@endif
					</tr>
				@endif
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align: center;width: 100%;">
			<label for="" style="color: rgb(59,70,80);font-weight: bold;">ORDER DETAIL</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
				<thead style="text-align: center">
					<tr>
						<th style="background-color: rgb(239,247,254); padding: 15px; width: 61%;">Product Name</th>
						<th style="background-color: rgb(239,247,254); padding: 15px; width: 30%">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="padding: 15px">{{ $order->order_detail->product_name }}</td>
						<td style="padding: 15px" align="right">
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
			<table style="padding: 5px 0;width: 100%;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Charge {{ $order->order_detail->fee_name }}</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Fee Credit card --}}
				@if($order->fee_credit_card >0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Credit Card Charge</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
					</tr>
				@endif

				@if($order->fee > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Charge {{ $order->payment->payment_gateway }}</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Discount</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Voucher Discount</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif
				@if($order->additional_orders()->whereType('insurance')->count() > 0)
					@foreach($order->additional_orders()->whereType('insurance')->get() as $add)
						<tr>
							<td style="padding: 5px 15px;" colspan="2">{{ $add->description_en }}
								({{ $add->quantity .' * IDR '. number_format($add->price,0) }})
							</td>
							<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($add->total,0) }}</span></strong>
							</td>
						</tr>
					@endforeach
				@endif
				<tr>
					<td style="padding: 5px 15px;" colspan="2">Total</td>
					<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->external_notes)
		<tr>
			<td colspan="3">
				<p><strong>Notes : </strong><br>{!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif
	<tr>
		<td colspan="3" style="padding-top: 3rem;">
			<p>We will provide further notification when we get payment status information.</p>
		</td>
	</tr>
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
				Anda menerima pesanan baru yaitu {{ $order->order_detail->product_name }} dengan status menunggu pembayaran. Berikut ini detailnya :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table style="border-collapse:collapse;width: 100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 0)
						<td>Menunggu di transfer</td>
					@endif
					<td>Tanggal Pemesanan :</td>
					<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
				</tr>
				@if ($order->order_detail->discount_amount > 0)
					<tr>
						@if($order->order_detail->discount_amount > 0)
							<td>Nama Diskon :</td>
							<td>
								{{ $order->order_detail->discount_name }}
							</td>
						@endif
					</tr>
				@endif
				@if($order->status == 0)
					<tr>
						<td>Metode Pembayaran :</td>
						@if($order->allow_payment == 1)
							@if($order->payment)
								@if ($order->payment->payment_gateway == 'Cash On Delivery')
									Pembayaran Tunai
								@else
									<td>{{$order->payment->payment_gateway}}</td>
								@endif
							@else
								<td>Tidak Diketahui</td>
							@endif

						@endif

						@if($order->allow_payment != 1)
							<td>Manual Transfer</td>
						@endif
					</tr>
				@endif
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="text-align: center;width: 100%;">
			<label for="" style="color: rgb(59,70,80);font-weight: bold;">ORDER DETAIL</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
				<thead style="text-align: center">
					<tr>
						<th style="background-color: rgb(239,247,254); padding: 15px; width: 61%;">Nama Produk</th>
						<th style="background-color: rgb(239,247,254); padding: 15px; width: 30%">Total</th>
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
			<table style="padding: 5px 0;width: 100%;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Biaya {{ $order->order_detail->fee_name }}</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card >0)
						<tr>
							<td style="padding: 5px 15px" colspan="2">Biaya Kartu Kredit</td>
							<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee >0)
						<tr>
							<td style="padding: 5px 15px" colspan="2">Biaya {{ $order->payment->payment_gateway }}</td>
							<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Discount</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Diskon</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding: 5px 15px" colspan="2">Diskon Voucher</td>
						<td style="padding: 5px 15px" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
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
			<td colspan="3">
				<p><strong>Catatan : </strong>{{ $order->external_notes }}</p>
			</td>
		</tr>
	@endif
	<tr>
		<td colspan="3" style="padding-top: 3rem;">
			<p>Kami akan memberikan pemberitahuan selanjutnya ketika kami mendapatkan informasi status pembayaran.</p>
		</td>
	</tr>
	</tbody>
</table>
</body>
</html>



