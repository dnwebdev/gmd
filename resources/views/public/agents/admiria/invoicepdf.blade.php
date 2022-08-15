<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		.bg-secondary{
			background: #F6CE4E;
		}

		.bb-secondary{
			border-bottom: 1px solid #F6CE4E;	
		}
		.br-secondary{
			border-right: 1px solid #F6CE4E;	
		}

		.bround{
			border-radius: 58px 0px;
		}

		.title{
			font-size: 22px;
			padding: 2px;
		}


		table { border-collapse:collapse }
	</style>
</head>
<body>

<table  align="center" width="680px" cellpadding="5" cellspacing="0">
	<tr>
		<td colspan="3" align="center" class="bg-secondary bround"><h2>Your Voucher is Here</h2></td>
	</tr>
	<tr>
		<td colspan="3" class="bb-secondary">
			<strong>Dear {{ $order->customer_info->first_name }} {{ $order->customer_info->last_name }},</strong>
			<p>Your Order has been successfully confirmed.</p>

		</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<strong>Booking Details</strong>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td rowspan="2" colspan="2">
			<img src="{{ $order->order_detail->main_image_src }}" height="100" />
		</td>
		<td>{{ $order->order_detail->product_name }}</td>	
	</tr>
	<tr>
		<td colspan="2">
			{{ $order->order_detail->city ? $order->order_detail->city->city_name : '' }}
		</td>
		
	</tr>
	
	<tr class="bg-secondary">
		<td colspan="3" align="center" class="bb-secondary">
			Guest Name
		</td>
	</tr>
	<tr class="bg-secondary">
		<td colspan="3" align="center" class="bb-secondary">
			<strong>{{ $order->customer_info->first_name }} {{ $order->customer_info->last_name }}</strong>
		</td>
	</tr>
	<tr class="bg-secondary">
		<td colspan="3" align="center" class="bb-secondary">
			Invoice No
		</td>
	</tr>
	<tr class="bg-secondary">
		<td colspan="3" align="center" class="bb-secondary">
			<strong>{{ $order->invoice_no }}</strong>
		</td>
	</tr>
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Category Type</td>
		<td class="bb-secondary">: {{ $order->order_detail->category->category_name }}</td>
	</tr>
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Schedule Date</td>
		<td class="bb-secondary">: {{ $order->order_detail->schedule_date_formated }}</td>
	</tr>
	<tr class="bg-secondary">
		<td align="right" class="bg-secondary bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bg-secondary bb-secondary br-secondary">Duration</td>
		<td class="bg-secondary bb-secondary">: {{ $order->order_detail->duration }} {{ $order->order_detail->duration_type_text }}</td>
	</tr>
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Destination</td>
		<td class="bb-secondary">: {{ $order->order_detail->city ? $order->order_detail->city->city_name : ''}}</td>
	</tr>

	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Adult</td>
		<td class="bb-secondary">: {{ $order->order_detail->adult }}</td>
	</tr>

	@if($order->order_detail->children)
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Child</td>
		<td class="bb-secondary">: {{ $order->order_detail->children }}</td>
	</tr>
	@endif

	@if($order->order_detail->infant)
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Infant</td>
		<td class="bb-secondary">: {{ $order->order_detail->infant }}</td>
	</tr>
	@endif
	
	<tr>
		<td align="right" class="bb-secondary">
			<img height="50" src="{{ asset('themes/admiria/images/asset1.jpg') }}">
		</td>
		
		<td colspan="2" class="bb-secondary">
			<strong>Manage your bookings conveniently</strong>
			<p>Access your e-ticket with ease, make changes in comfort at any time - all via Retrieve Booking.</p>
		</td>
	</tr>

	<tr>
		<td align="center" colspan="2">
			<img src="{{ asset('uploads/company_logo/'.$order->company->logo) }}" height="50" />
		</td>
		
		<td>
			{{ $company->phone_company }}
		</td>
	</tr>
	<tr>
		<td align="center" colspan="2">
			{{ $company->company_name }}
		</td>
		
		<td>
			{{ $company->email_company }}
		</td>
	</tr>

</table>

</body>
</html>
