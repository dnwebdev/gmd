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
			<p style="color: rgb(59,70,80);font-weight: bold;">Hello asdasd,</p>
			<p>
				Hello Provider, You receive a new order for sadasd Your order status is waiting for payment Please check  the order details by accessing the button below :
			</p>
		</td>
	</tr>
	{{-- <tr>
		<td colspan="3">
			<table cellpadding="5" cellspacing="0" style="border-collapse:collapse;width:100%;">
				<tbody>
				<tr>
					<td>Status :</td>
					@if($order->status == 1)
						<td>Paid</td>
					@endif
					@if($order->status == 3)
						<td>Paid</td>
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
	</tr> --}}
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
					<td style="width: 61%;padding: 15px;">sdfdfs</td>
					<td style="width: 30%;padding: 15px;">
						<strong style="display: inline-flex;">IDR
							<span style="float: right;">{sdfsdf</span>
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
				{{-- @if($order->order_detail->fee_amount > 0) --}}
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Charge sdfdsf</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;">234234</span></strong></td>
					</tr>
				{{-- @endif --}}

				{{-- Fee Credit card --}}
				{{-- @if($order->fee_credit_card >0) --}}
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Credit Card Charge</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;">121231</span></strong></td>
					</tr>
				{{-- @endif --}}

				{{-- Discount --}}
				{{-- @if($order->order_detail->discount_amount > 0) --}}
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Discount (asdasd)</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248)">-asdasd</span></strong></td>
					</tr>
				{{-- @endif --}}

				{{-- Voucher --}}
				{{-- @if($order->voucher_amount > 0) --}}
					<tr>
						<td style="padding-left:1.2rem!important;width: 67%;">Voucher Discount (q2e231)</td>
						<td><strong style="display: inline-flex;">IDR <span style="float: right;color: rgb(4,155,248)">-123123123</span></strong></td>
					</tr>
				{{-- @endif --}}

				<tr>
					{{-- <td style="padding-left:1.2rem!important;width: 67%;">Total</td>
					<td><strong style="display: inline-flex;">IDR <span style="float: right;">123123</span></strong></td> --}}
					<td style="padding-left:1.2rem!important;width: 67%;">Total</td>
					<td><strong style="display: inline-flex;">IDR <span style="float: right;">123123</span></strong></td>
				</tr>
			</table>
		</td>
	</tr>
    <tr>
        <td colspan="3" style="padding-left: 12px;">
            <p>Notes : <br> asdasd</p>
        </td>
    </tr>
	{{-- @if($order->external_notes)
		<tr>
			<td colspan="3">
				<p>Notes : <br> {!! $order->external_notes !!}</p>
			</td>
		</tr>
	@endif --}}
	</tbody>
</table>
</body>
</html>




