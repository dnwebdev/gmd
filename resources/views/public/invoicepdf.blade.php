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
		<td colspan="3" align="center" class="bg-secondary bround title">Your Voucher is Here</td>
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
			{{ $order->order_detail->product->city->city_name }}
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
		<td class="bb-secondary">: {{ $order->order_detail->product->category->category_name }}</td>
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
		<td class="bg-secondary bb-secondary">: {{ $order->order_detail->product->duration }} {{ $order->order_detail->product->duration_type_text }}</td>
	</tr>
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Destination</td>
		<td class="bb-secondary">: {{ $order->order_detail->product->city->city_name }}</td>
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
	<tr class="bg-secondary">
		<td align="right" class="bb-secondary br-secondary">
			&nbsp;
		</td>
		<td class="bb-secondary br-secondary">Child</td>
		<td class="bb-secondary">: {{ $order->order_detail->children }}</td>
	</tr>

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
			<img src="{{ Session::get('company_logo') }}" height="50" />
		</td>
		
		<td>
			{{ $company->phone_company }}
		</td>
	</tr>
	<tr style="padding: 0">
		<td align="center" colspan="2">
			{{ $company->company_name }}
		</td>
		
		<td>
			{{ $company->email_company }}
		</td>
	</tr>








	<!--

	<tr>
		<td><h1>INVOICE</h1></td>
		<td align="right">
			<h1>{{ Session::get('company_name') }} <img src="{{ Session::get('company_logo') }}" height="50" /></h1>
		</td>
	</tr>
	<tr>
		<td colspan="2"><strong>{{ $order->invoice_no }}</td>
	</tr>
	<tr>
		<td colspan="2"><strong>{{ $order->order_detail->product_name }}</strong></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="{{ $order->order_detail->main_image_src }}" height="100" />
		</td>
		<td>Booking Details</td>
	</tr>
	<tr>
		<td>Schedule Date</td>
	</tr>
	<tr>
		<td>{{ date('M d Y',strtotime($order->order_detail->schedule_date)) }}</td>
	</tr>
	<tr>
		<td>Guest : {{ $order->customer_info->first_name }} {{ $order->customer_info->last_name }}</td>
	</tr>
	<tr>
		<td>
			Adult : {{ $order->order_detail->adult }}
			@if($order->order_detail->child > 0)
				<br>Children : {{ $order->order_detail->children }}</td>
			@endif
			@if($order->order_detail->infant > 0)
				<br>Infant : {{ $order->order_detail->infant }}</td>
			@endif
		</td>
	</tr>
	-->
</table>

	<!--
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div><h1>INVOICE</h1></div>
				<div><strong>{{ $order->invoice_no }}</strong></div>
			</div>
			<div class="col-sm-6 text-right">
				<h1>
				{{ Session::get('company_name') }} <img src="{{ Session::get('company_logo') }}" height="50" />
				</h1>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12"><strong>{{ $order->order_detail->product_name }}</strong></div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<img src="{{ $order->order_detail->main_image_src }}" height="100" />
			</div>
			<div class="col-sm-8">
				<div>Booking Details</div>
				<div class="row">
					<div class="col-sm-6">Schedule Date</div>
					<div class="col-sm-6">{{ date('M d Y',strtotime($order->order_detail->schedule_date)) }}</div>
				</div>
				<div class="row">
					<div class="col-sm-6">Guest</div>
					<div class="col-sm-6">{{ $order->customer_info->first_name }} {{ $order->customer_info->last_name }}</div>
				</div>

				<div class="row">
					<div class="col-sm-6">Adult</div>
					<div class="col-sm-6 text-right">{{ $order->order_detail->adult }}</div>
				</div>

				@if($order->order_detail->child > 0)
				<div class="row">
					<div class="col-sm-6">Children</div>
					<div class="col-sm-6 text-right">{{ $order->order_detail->children }}</div>
				</div>
				@endif

				@if($order->order_detail->infant > 0)
				<div class="row">
					<div class="col-sm-6">Infant</div>
					<div class="col-sm-6 text-right">{{ $order->order_detail->infant }}</div>
				</div>
				@endif

			</div>
		</div>
	</div>

-->
</body>
</html>
