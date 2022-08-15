<!DOCTYPE html>
<html>
<head>
	<title>PDF</title>
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
			<table width="100%">
				<tr>
					<td colspan="2">
						@if($company->logo)
							<img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
						@endif
					</td>
					<td style="width: 25%;"><h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $order->customer->first_name }},</p>
			<p>
				Your order with an invoice number {{ $order->invoice_no }} has been canceled by the system. We have not been able to process your order because the payment has not been completed. Please contact your tour agent if you want to continue your order.
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="text-align: center; width: 680px;">
				<tbody>
				<tr>
					<td>Phone Number :</td>
					<td>{{ $company->phone_company }}</td>
					<td>Email :</td>
					<td>{{ $company->email_company }}</td>
				</tr>
				<tr>
					<td>Status :</td>
					<td>{{ $order->status_text }}</td>
					@if($company->address_company)
						<td>Address</td>
						<td>{!! $company->address_company !!}</td>
					@endif
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
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 5%">
			<label style="color: rgb(59,70,80);font-weight: bold;">ORDER DETAIL</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="background-color: rgb(239,247,254);">
					<th style="padding: 15px; width: 20%;">Product Name</th>
					<th style="padding: 15px; width: 25%">Description</th>
					<th style="padding: 15px; width: 8%;">Qty</th>
					<th style="padding: 15px; width: 15%;">Price</th>
					<th style="padding: 15px;width: 30%;">Total</th>
				</tr>
				</thead>
				<tbody>
				@foreach($order->invoice_detail as $item)
					<tr>
						<td style="padding: 15px;width: 20%;">{{ $order->order_detail->product_name }}</td>
						<td style="padding: 15px;width: 25%;">{{$item['description']}}</td>
						<td style="padding: 15px;width: 8%;">{{ $item['qty'] }}</td>
						<td style="padding: 15px;width: 15%;">{{ number_format($item['price'],0) }}</td>
						<td style="padding: 15px;width: 30%;">
							<strong>IDR
								<span style="float: right;">{{ number_format($item['price'] * $item['qty'],0) }}</span>
							</strong>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table class=" padding" style="width: 100%;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding-left:  1.2rem!important;width: 69%;">Charge {{ $order->order_detail->fee_name }}</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card >0)
						<tr>
							<td style="padding-left:  1.2rem!important;width: 69%;">Credit Card Charge</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee >0)
						<tr>
							<td style="padding-left:  1.2rem!important;width: 69%;">Charge {{ $order->payment->payment_gateway }}</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding-left:  1.2rem!important;width: 69%;">Discount</td>
						<td ><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding-left:  1.2rem!important;width: 69%;">Voucher Discount</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr style="padding-bottom: 3rem;">
					<td style="padding-left:  1.2rem!important;width: 69%;">Total</td>
					<td ><strong style="display: inline-flex;">IDR <span style="float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
		@if($order->guides->count() > 0)
			<br>
			<tr>
				<td colspan="3" style="padding-top: 5%">
					<label style="color: rgb(59,70,80);font-weight: bold;">GUIDE INFORMATION</label>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<table class=" padding" style="width: 100%;">
						<thead>
						<tr align="left">
							<th style="width: 20%;">No</th>
							<th style="width: 25%;">Guide Name</th>
							<th style="width: 15%">Phone Number</th>
						</tr>
						</thead>
						<tbody>
						@foreach($order->guides as $guide)
							<tr>
								<td style="width: 20%;">{{$loop->index+1}}</td>
								<td style="width: 25%;">{{$guide->guide_name}}</td>
								<td style="width: 15%">{{$guide->guide_phone_number}}</td>
							</tr>
						@endforeach
						</tbody>

					</table>
				</td>
			</tr>
		@endif
		@if($order->external_notes)
			<tr>
				<td>
					<p>Notes : <br> {!! $order->external_notes !!}</p>
				</td>
			</tr>
		@endif
	<tr>
		<td colspan="3">
			Thank you for ordering to use {{ $company->domain_memoria }}
			<br>
			Gomodo Customer Support
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
			<table width="100%">
				<tr>
					<td colspan="2">
						@if($company->logo)
							<img src="{{ $company->logo_url }}" height="50" alt="Logo"/>
						@endif
					</td>
					<td style="width: 25%;"><h2 style="color:rgb(4,155,248); float: right;">{{ $order->invoice_no }}</h2></td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="3">
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello {{ $order->customer->first_name }},</p>
			<p>
				Pesanan Anda dengan nomor invoice {{ $order->invoice_no }} telah dibatalkan oleh sistem. Kami belum dapat memproses pesanan Anda karena pembayaran belum selesai. Silahkan hubungi agen penyedia jasa Anda jika Anda ingin melanjutkan pesanan.
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="text-align: center; width: 680px;">
				<tbody>
				<tr>
					<td>Nomor Telepon :</td>
					<td>{{ $company->phone_company }}</td>
					<td>Email :</td>
					<td>{{ $company->email_company }}</td>
				</tr>
				<tr>
					<td>Status :</td>
					<td>
						@if ($order->status == 5)
							Dibatalkan oleh user
						@elseif ($order->status == 6)
							Dibatalkan oleh provider
						@elseif ($order->status == 7)
							Dibatalkan oleh sistem
						@endif
					</td>
					@if($company->address_company)
						<td>Alamat :</td>
						<td>{!! $company->address_company !!}</td>
					@endif
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
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding-top: 5%">
			<label style="color: rgb(59,70,80);font-weight: bold;">DETAIL ORDER</label>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table class="details" cellpadding="5" cellspacing="0" style="height: 100px;width: 100%;">
				<thead>
				<tr style="background-color: rgb(239,247,254);">
					<th style="padding: 15px; width: 20%;">Nama Produk</th>
					<th style="padding: 15px; width: 25%">Deskripsi</th>
					<th style="padding: 15px; width: 8%;">Kuantitas</th>
					<th style="padding: 15px; width: 15%;">Harga</th>
					<th style="padding: 15px;width: 30%;">Total</th>
				</tr>
				</thead>
				<tbody>
				@foreach($order->invoice_detail as $item)
					<tr>
						<td style="padding: 15px;width: 20%;">{{ $order->order_detail->product_name }}</td>
						<td style="padding: 15px;width: 25%;">{{$item['description']}}</td>
						<td style="padding: 15px;width: 8%;">{{ $item['qty'] }}</td>
						<td style="padding: 15px;width: 15%;">{{ number_format($item['price'],0) }}</td>
						<td style="padding: 15px;width: 30%;">
							<strong>IDR
								<span style="float: right;">{{ number_format($item['price'] * $item['qty'],0) }}</span>
							</strong>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table class=" padding" style="width: 100%;">

				{{-- Fee Amount --}}
				@if($order->order_detail->fee_amount > 0)
					<tr>
						<td style="padding-left:  1.2rem!important;width: 69%;">Biaya {{ $order->order_detail->fee_name }}</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card >0)
						<tr>
							<td style="padding-left:  1.2rem!important;width: 69%;">Biaya Kartu Kredit</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee >0)
						<tr>
							<td style="padding-left:  1.2rem!important;width: 69%;">Biaya {{ $order->payment->payment_gateway }}</td>
							<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;">{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding-left:  1.2rem!important;width: 69%;">Diskon</td>
						<td ><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;color: rgb(4,155,248);">-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding-left:  1.2rem!important;width: 69%;">Vocher Diskon</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;padding-right: 10px;color: rgb(4,155,248);">-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr style="padding-bottom: 3rem;">
					<td style="padding-left:  1.2rem!important;width: 69%;">Total</td>
					<td ><strong style="display: inline-flex;">IDR <span style="float: right;">{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->guides->count() > 0)
		<br>
		<tr>
			<td colspan="3" style="padding-top: 5%">
				<label style="color: rgb(59,70,80);font-weight: bold;">INFORMASI PANDUAN</label>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<table class=" padding" style="width: 100%;">
					<thead>
					<tr align="left">
						<th style="width: 20%;">No</th>
						<th style="width: 25%;">Nama Pemandu</th>
						<th style="width: 15%">Nomor Telepon</th>
					</tr>
					</thead>
					<tbody>
					@foreach($order->guides as $guide)
						<tr>
							<td style="width: 20%;">{{$loop->index+1}}</td>
							<td style="width: 25%;">{{$guide->guide_name}}</td>
							<td style="width: 15%">{{$guide->guide_phone_number}}</td>
						</tr>
					@endforeach
					</tbody>

				</table>
			</td>
		</tr>
	@endif
	<br>
	@if($order->external_notes)
		<tr>
			<td>
				<p>Catatan : <br> {!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif
	<tr>
		<td colspan="3">
			Terima kasih sudah memesan menggunakan {{ $company->domain_memoria }}
			<br>
			Gomodo Customer Support
		</td>
	</tr>
	</tbody>
</table>

</body>
</html>




