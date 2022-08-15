<!DOCTYPE html>
<html>
<head>
	<title>PDF Unpaid Booking</title>
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
			<table style="border-collapse:collapse;width: 100%;">
				<tr>
					<td colspan="2" class="provider-logo">
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
				Thank you for ordering on {{ $company->company_name }}. Your order has been confirmed. Please make payment immediately according to the invoice that we attach to this email.
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
				<tbody>
				<tr>
					<td>Name :</td>
					<td>{{ $order->customer->first_name }}</td>
					<td>Start Date :</td>
					<td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
				</tr>
				<tr>
					<td>Email :</td>
					<td>{{ $order->customer->email }}</td>
					<td>Duration :</td>
					<td>{{ $order->order_detail->duration }} {{ $order->order_detail->duration_type_text}}</td>
				</tr>
				<tr>
					<td>Phone Number :</td>
					@if ($order->customer->phone)
						<td>{{ $order->customer->phone }}</td>
					@else
						<td>-</td>
					@endif
					<td>Status :</td>
					@if (isset($order->customer_manual_transfer) && optional($order->customer_manual_transfer)->status)
                        <td>Waiting in Confirmation</td>
                    @else
						<td>Not Paid</td>
                    @endif
				</tr>
				@if($order->order_detail->city)
					<!--<tr>
						<td>Location :</td>
						<td>{{ $order->order_detail->city->city_name }}</td>
					</tr>!-->
				@endif
				
				@if($order->order_detail->product && $order->order_detail->product->schedule->count()>1)
					<tr>
						<td>Operational Hours :</td>
						<td>{{ $order->order_detail->product->schedule[0]->start_time }} - {{ $order->order_detail->product->schedule[0]->end_time }}</td>
					</tr>
				@endif

				</tbody>
			</table>
			<br>
			<table style="width: 640px">
				<tbody>
				@if($company->address_company)
					<!--<tr>
						<td style="width: 90px;">Address :</td>
						<td>{!! $company->address_company !!}</td>
					</tr>!-->
				@endif
				<tr>
					<td style="width: 90px;">Description :</td>
					<td>{{ $order->order_detail->product_description }}</td>
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
			<table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
				<thead>
					<tr style="background-color: rgb(239,247,254);text-align: center;">
						<th style="padding: 15px;width: 20%;">Product Name</th>
						<th style="padding: 15px;width: 25%;">Unit</th>
						<th style="padding: 15px;width: 15%;">Price</th>
						<th style="padding: 15px;width: 30%;">Total</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px">{{ $order->order_detail->product_name }}</td>
					<td style="padding: 15px;text-align: center">{{ $order->order_detail->adult }} {{optional($order->order_detail->unit)->name}}</td>
					<td style="padding: 15px">{{ number_format($order->order_detail->adult_price,0) }}</td>
					<td style="padding: 15px" align="right">
						<strong>IDR
							<span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
						</strong>
					</td>
				</tr>
				@if($order->additional_orders()->whereType('insurance')->count() > 0)
					@foreach($order->additional_orders()->whereType('insurance')->get() as $add)
						<tr>
							<td style="padding: 15px">{{ $add->description_en}}</td>
							<td style="padding: 15px;text-align: center">{{ $add->quantity }}</td>
							<td style="padding: 15px">{{ number_format($add->price,0) }}</td>
							<td style="padding: 15px" align="right">
								<strong>IDR
									<span>{{ number_format($add->total,0) }}</span>
								</strong>
							</td>
						</tr>
					@endforeach
				@endif
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
				{{-- Fee --}}
				@if ($order->fee > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Charge {{ $order->payment->payment_gateway }}</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Discount ({{ $order->order_detail->discount_name }})</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Voucher Discount ({{ $order->voucher_code }})</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif

				<tr>
					<td style="padding: 5px 15px;" colspan="2">Total</td>
					<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->total_amount,0) }}</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->guides->count() > 0)
		<tr>
			<td colspan="3" style="text-align: center;height: 15px;">
				<label for="" style="color: rgb(59,70,80);font-weight: bold;">GUIDE INFORMATION</label>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<table style="float: right;width:100%;padding-bottom:30px;">
					<thead>
					<tr>
						<th style="width: 20%;">No</th>
						<th style="width: 25%">Guide Name</th>
						<th style="width: 15%">Phone Number</th>
					</tr>
					</thead>
					<tbody>
					@foreach($order->guides as $guide)
						<tr>
							<td style="width: 20%;padding: 5px;">{{$loop->index+1}}</td>
							<td style="width: 25%;padding: 5px;">{{$guide->guide_name}}</td>
							<td style="width: 15%;padding: 5px;">{{$guide->guide_phone_number}}</td>
						</tr>
					@endforeach
					</tbody>

				</table>
			</td>
		</tr>
	@endif
	@if($order->external_notes)
		<tr>
			<td colspan="3">
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
			<table style="border-collapse:collapse;width: 100%;">
				<tr>
					<td colspan="2" class="provider-logo">
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
				Terima kasih sudah memesan di {{ $company->company_name }}. Pemesanan Anda sudah di konfirmasi. Mohon segera lakukan pembayaran sesuai dengan invoice yang kami lampirkan pada email ini.
			</p>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width: 680px;margin: auto;">
				<tbody>
				<tr>
					<td>Nama :</td>
					<td>{{ $order->customer->first_name }}</td>
					<td>Tanggal Mulai :</td>
					<td>{{ date('M d, Y',strtotime($order->order_detail->schedule_date)) }}</td>
				</tr>
				<tr>
					<td>Email :</td>
					<td>{{ $order->customer->email }}</td>
					<td>Durasi :</td>
					<td>{{ $order->order_detail->duration }} {{ $order->order_detail->duration_type_text}}</td>
				</tr>
				<tr>
					<td>No. Telpon :</td>
					@if ($order->customer->phone)
						<td>{{ $order->customer->phone }}</td>
					@else
						<td>-</td>
					@endif
					<td>Status :</td>
					@if (isset($order->customer_manual_transfer) && optional($order->customer_manual_transfer)->status)
                        <td>Menunggu di Konfirmasi</td>
                    @else
                        <td>Belum Dibayarkan</td>
                    @endif
				</tr>
				@if($order->order_detail->city)
					<!-- <tr>
						<td>Lokasi :</td>
						<td>{{ $order->order_detail->city->city_name }}</td>
					</tr> -->
				@endif
				@if($order->order_detail->product && $order->order_detail->product->schedule->count()>1)
					<tr>
						<td>Jam Operasional :</td>
						<td>{{ $order->order_detail->product->schedule[0]->start_time }} - {{ $order->order_detail->product->schedule[0]->end_time }}</td>
					</tr>
				@endif

				</tbody>
			</table>
			<br>
			<table style="width: 640px">
				<tbody>
				@if($company->address_company)
					<!-- <tr>
						<td style="width: 90px;">Alamat :</td>
						<td>{!! $company->address_company !!}</td>
					</tr> -->
				@endif
				<tr>
					<td style="width: 90px;">Deskripsi :</td>
					<td>{{ $order->order_detail->product_description }}</td>
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
			<table cellpadding="5" cellspacing="0" style="height: 100px;width: 680px;">
				<thead>
					<tr style="background-color: rgb(239,247,254);text-align: center;">
						<th style="padding: 15px;width: 20%;">Nama Produk</th>
						<th style="padding: 15px;width: 25%;">Tamu</th>
						<th style="padding: 15px;width: 15%;">Harga</th>
						<th style="padding: 15px;width: 30%;">Total</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<td style="padding: 15px">{{ $order->order_detail->product_name }}</td>
					<td style="padding: 15px;text-align: center">{{ $order->order_detail->adult }} {{optional($order->order_detail->unit)->name}}</td>
					<td style="padding: 15px">{{ number_format($order->order_detail->adult_price,0) }}</td>
					<td style="padding: 15px" align="right">
						<strong>IDR
							<span>{{ number_format($order->order_detail->adult_price * $order->order_detail->adult,0) }}</span>
						</strong>
					</td>
				</tr>
				@if($order->additional_orders()->whereType('insurance')->count() > 0)
					@foreach($order->additional_orders()->whereType('insurance')->get() as $add)
						<tr>
							<td style="padding: 15px">{{ $add->description_id}}</td>
							<td style="padding: 15px;text-align: center">{{ $add->quantity }}</td>
							<td style="padding: 15px">{{ number_format($add->price,0) }}</td>
							<td style="padding: 15px;" align="right">
								<strong>IDR
									<span>{{ number_format($add->total,0) }}</span>
								</strong>
							</td>
						</tr>
					@endforeach
				@endif
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
						<td style="padding: 5px 15px;" colspan="2">Biaya {{ $order->order_detail->fee_name }}</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->order_detail->fee_amount,0) }}</span></strong></td>
					</tr>
				@endif

					{{-- Fee Credit card --}}
					@if($order->fee_credit_card > 0)
						<tr>
							<td style="padding: 5px 15px;" colspan="2">Biaya Kartu Kredit</td>
							<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee_credit_card,0) }}</span></strong></td>
						</tr>
					@endif

					@if($order->fee > 0)
						<tr>
							<td style="padding: 5px 15px;" colspan="2">Biaya {{ $order->payment->payment_gateway }}</td>
							<td style="padding: 5px 15px;" align="right"><strong>IDR <span>{{ number_format($order->fee,0) }}</span></strong></td>
						</tr>
					@endif

				{{-- Discount --}}
				@if($order->order_detail->discount_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Diskon ({{ $order->order_detail->discount_name }})</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->product_discount,0) }}</span></strong></td>
					</tr>
				@endif

				{{-- Voucher --}}
				@if($order->voucher_amount > 0)
					<tr>
						<td style="padding: 5px 15px;" colspan="2">Diskon Voucher ({{ $order->voucher_code }})</td>
						<td style="padding: 5px 15px;" align="right"><strong>IDR <span>-{{ number_format($order->voucher_amount,0) }}</span></strong></td>
					</tr>
				@endif
				<tr>
					<td style="padding: 5px 15px;" colspan="2">Total</td>
					<td style="padding: 5px 15px;" align="right"><strong>IDR
                            <span>{{ number_format($order->total_amount,0) }}</span></strong>
                    </td>
				</tr>
			</table>
		</td>
	</tr>
	@if($order->guides->count() > 0)
		<tr>
			<td colspan="3" style="text-align: center;height: 15px;">
				<label for="" style="color: rgb(59,70,80);font-weight: bold;">INFORMASI PANDUAN</label>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<table style="float: right;width:100%;padding-bottom:30px;">
					<thead>
					<tr>
						<th style="width: 20%;">No</th>
						<th style="width: 25%">No. Panduan</th>
						<th style="width: 15%">No. Telpon</th>
					</tr>
					</thead>
					<tbody>
					@foreach($order->guides as $guide)
						<tr>
							<td style="width: 20%;padding: 5px;">{{$loop->index+1}}</td>
							<td style="width: 25%;padding: 5px;">{{$guide->guide_name}}</td>
							<td style="width: 15%;padding: 5px;">{{$guide->guide_phone_number}}</td>
						</tr>
					@endforeach
					</tbody>

				</table>
			</td>
		</tr>
	@endif
	@if($order->external_notes)
		<tr>
			<td colspan="3">
				<p>Catatan : <br> {!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif
	</tbody>
</table>
</body>
</html>




