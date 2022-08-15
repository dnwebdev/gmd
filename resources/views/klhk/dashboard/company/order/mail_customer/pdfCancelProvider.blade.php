<!DOCTYPE html>
<html>
<head>
	<title>PDF Cancel Booking Provider</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size: 14px;color: rgba(0, 0, 0, 0.54);">

{{--Language English--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; width: 680px; margin: auto;">
	<tbody>
	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $company->company_name }},</p>
			<p>
				Your order with an invoice number {{ $order->invoice_no }} has been canceled by the system. We have not been able to process your customer order because the payment has not been completed.
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="width: 100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 5 || $order->status == 6 || $order->status == 7)
						<td>Canceled</td>
					@endif
					<td>Tanggal Mulai</td>
					<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
				</tr>
				@if ($order->order_detail->discount_amount > 0 || $order->external_notes)
					<tr>
						@if($order->order_detail->discount_amount > 0)
							<td>Discount Name :</td>
							<td>
								{{ $order->order_detail->discount_name }}
							</td>
						@endif
						@if($order->external_notes)
							<td>Notes : </td>
							<td>{!! $order->external_notes !!}</td>
						@endif
					</tr>
				@endif
				<tr>
					<td>Payment Method :</td>
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
			<table align="center" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="text-align: center;background-color: rgb(239,247,254);">
					<th style="padding: 15px; width: 61%;">Product Name</th>
					<th style="padding: 15px; width: 30%;">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px; width: 61%;">{{ $order->order_detail->product_name }}</td>
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
			<table style="width: 100%;float: right;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Charge {{ $order->order_detail->fee_name }}</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;">123123</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card >0)
						<tr>
							<td style="padding-left:1.2rem!important;width: 73%;">Credit Card Charge</td>
							<td><strong style="display:inline-flex;">IDR <span style="float: right;padding-right: 8%">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif
					{{-- Fee --}}
					@if ($order->fee > 0)
						<tr>
							<td style="padding-left:1.2rem!important;width: 73%;">Charge {{ $order->payment->payment_gateway }}</td>
							<td><strong style="display:inline-flex;">IDR <span style="float: right;padding-right: 8%">{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Discount ({{ $order->order_detail->discount_name }})</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Voucher Discount ({{ $order->voucher_code }})</td><td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);">-ads123</span></strong></td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr>
					<td style="padding-left:1.2rem;width: 67%;">Total</td>
					<td><strong style="display: inline-flex;">IDR <span style="float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 3rem;">
			<p>If any other questions or complaints, please contact our customer support at <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62 812-1111-9655</a>. Thank you for using mygomodo to facilitate your transaction.<br><br>
				Powered by Gomodo</p>
		</td>
	</tr>
	</tbody>
</table>

<hr>
<br>

{{--Language Indonesia--}}
<table cellpadding="5" cellspacing="0" style="border-collapse:collapse; width: 680px; margin: auto;">
	<tbody>
	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Halo {{ $company->company_name }},</p>
			<p>
				Pesanan Anda dengan nomor invoice {{ $order->invoice_no }} telah dibatalkan oleh sistem. Kami belum dapat memproses pesanan dari customer Anda karena pembayaran belum diselesaikan.
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="width: 100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 5 || $order->status == 6 || $order->status == 7)
						<td>Canceled</td>
					@endif
					<td>Start Date</td>
					<td>{{ \Carbon\Carbon::now()->format('d F Y') }}</td>
				</tr>
				@if ($order->order_detail->discount_amount > 0 || $order->external_notes)
					<tr>
						@if($order->order_detail->discount_amount > 0)
							<td>Nama Diskon :</td>
							<td>
								{{ $order->order_detail->discount_name }}
							</td>
						@endif
						@if($order->external_notes)
							<td>Catatan : </td>
							<td>{!! $order->external_notes !!}</td>
						@endif
					</tr>
				@endif
				<tr>
					<td>Metode Pembayaran :</td>
					@if($order->allow_payment == 1)
						@if($order->payment)
							<td>{{$order->payment->payment_gateway}}</td>
						@else
							<td>Tidak Diketahui</td>
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
			<table align="center" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="text-align: center;background-color: rgb(239,247,254);">
					<th style="padding: 15px; width: 61%;"></th>
					<th style="padding: 15px; width: 30%;">Total</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px; width: 61%;">{{ $order->order_detail->product_name }}</td>
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
			<table style="width: 100%;float: right;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Biaya {{ $order->order_detail->fee_name }}</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;">123123</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card > 0)
						<tr>
							<td style="padding: 5px 12px 5px 5px;"></td>
							<td style="padding: 5px;padding-left:1.2rem!important;width: 73%;">Biaya Kartu Kredit</td>
							<td style="padding: 5px;"><strong>IDR <span style="float: right;padding-right: 8%">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee > 0)
						<tr>
							<td style="padding: 5px 12px 5px 5px;"></td>
							<td style="padding: 5px;padding-left:1.2rem!important;width: 73%;">Biaya {{ $order->payment->payment_gateway }}</td>
							<td style="padding: 5px;"><strong>IDR <span style="float: right;padding-right: 8%">{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Diskon ({{ $order->order_detail->discount_name }})</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Diskon Voucher ({{ $order->voucher_code }})</td><td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);">-ads123</span></strong></td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr>
					<td style="padding-left:1.2rem;width: 67%;">Total</td>
					<td><strong style="display: inline-flex;">IDR <span style="float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 3rem;">
			<p>Jika ada pertanyaan atau keluhan, silahkan menghubungi customer support kami di <a href="https://api.whatsapp.com/send?phone=6281211119655&text=&source=&data=" target="_blank" style="font-weight: bold;text-decoration: none;color: rgba(0, 0, 0, 0.54);">+62 812-1111-9655</a>. Terima kasih telah menggunakan mygomodo.com untuk mempermudah transkasi Anda.
				<br><br>Powered by Gomodo</p>
		</td>
	</tr>
	<tr>
	</tbody>
</table>
</body>
</html>




