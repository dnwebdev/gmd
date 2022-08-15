<!DOCTYPE html>
<html>
<head>
	<title>PDF Unpaid Provider</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin: auto; width: 680px;">
	<tbody>
	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $company->company_name }},</p>
			<p>
				You receive a new order for {{ $order->order_detail->product_name }} Your order status is waiting for payment. Please check  the order details by accessing the button below :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="width: 100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					<td>Waiting for transfer</td>
					<td>Order Date :</td>
					<td>{{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</td>
				</tr>
				@if ($order->order_detail->discount_amount > 0 || $order->invoice_no)
					<tr>
						@if($order->order_detail->discount_amount > 0)
							<td>Discount Name :</td>
							<td>
								{{ $order->order_detail->discount_name }}
							</td>
						@endif
						<td>No Invoice :</td>
						<td>{{ $order->invoice_no }}</td>
					</tr>
				@endif
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
			<table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="background-color: rgb(239,247,254);text-align: center;">
					<th style="padding: 15px; width: 61%;">Product Name</th>
					<th style="padding: 15px;width: 30%;">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px;width: 61%;">{{ $order->order_detail->product_name }}</td>
					<td style="padding: 15px;width: 30%;">
						<strong style="display: inline-flex;">{{ $order->order_detail->currency }}
							<span style="float: right;">{{ number_format($order->total_amount,0) }}</span>
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
						<td style="padding-left:1.2rem!important;width: 69%;">Charge {{ $order->order_detail->fee_name }}</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card >0)
						<tr>
							<td style="padding-left:1.2rem!important;width: 69%;">Credit Card Charge</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee >0)
						<tr>
							<td style="padding-left:1.2rem!important;width: 69%;">Charge {{ $order->payment->payment_gateway }}</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 69%;">Discount</td>
						<td ><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);padding-right: 10px;">-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 69%;">Voucher Discount</td>
						<td ><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);padding-right: 10px;">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr>

					<td style="padding-left:1.2rem!important;width: 69%;">Total</td>
					<td ><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
		@if($order->external_notes)
			<tr>
				<td>
					<p>Notes : <br> {!! $order->external_notes !!}</p>
				</td>
			</tr>
		@endif
	<tr>
		<td colspan="3" style="padding-top: 3rem; ">
			<p>We will provide further notification when we get payment status information.</p>
		</td>
	</tr>
	</tbody>
</table>

<hr>
<br>
{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin: auto; width: 680px;">
	<tbody>
	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $company->company_name }},</p>
			<p>
				Anda menerima pesanan baru yaitu {{ $order->order_detail->product_name }} dengan status menunggu pembayaran. Anda dapat melihat detaill pesanan dengan mengakses tombol dibawah ini :
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="width: 100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					<td>Menunggu untuk di transfer</td>
					<td>Tanggal Pemesanan :</td>
					<td>{{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</td>
				</tr>
				@if ($order->order_detail->discount_amount > 0 || $order->invoice_no)
					<tr>
						@if($order->order_detail->discount_amount > 0)
							<td>Nama Diskon :</td>
							<td>
								{{ $order->order_detail->discount_name }}
							</td>
						@endif
						<td>No Invoice :</td>
						<td>{{ $order->invoice_no }}</td>
					</tr>
				@endif
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
			<table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="background-color: rgb(239,247,254);text-align: center;">
					<th style="padding: 15px; width: 61%;">Nama Produk</th>
					<th style="padding: 15px;width: 30%;">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px;width: 61%;">{{ $order->order_detail->product_name }}</td>
					<td style="padding: 15px;width: 30%;">
						<strong style="display: inline-flex;">{{ $order->order_detail->currency }}
							<span style="float: right;">{{ number_format($order->total_amount,0) }}</span>
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
						<td style="padding-left:1.2rem!important;width: 69%;">Biaya {{ $order->order_detail->fee_name }}</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card >0)
						<tr>
							<td style="padding-left:1.2rem!important;width: 69%;">Biaya Kartu Kredit</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee >0)
						<tr>
							<td style="padding-left:1.2rem!important;width: 69%;">Biaya {{ $order->payment->payment_gateway }}</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 69%;">Diskon</td>
						<td ><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);padding-right: 10px;">-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 69%;">Diskon Voucher</td>
						<td ><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);padding-right: 10px;">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr>

					<td style="padding-left:1.2rem!important;width: 69%;">Total</td>
					<td ><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->external_notes)
		<tr>
			<td>
				<p>Catatan : <br> {!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif
	<tr>
		<td colspan="3" style="padding-top: 3rem; ">
			<p>Kami akan memberikan pembaritahuan selanjutnya ketika kami mendapatkan informasi status pembayaran.</p>
		</td>
	</tr>
	</tbody>
</table>
</body>
</html>