<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<style>
		* { 
                font-family: 'DejaVu Sans', sans-serif !important;
                /* Or use Arial Unicode MS or another Unicode font */
            }
		@page { margin: 1in 1in 1in 1in;}
		.passenger th, .passenger td {
			border: 1px solid #000; /* 1px solid black border */
		}
	</style>
</head>
<body style="background-image: url(data:image/png;base64,{{ $bgBase64 }});background-repeat: no-repeat;background-size: contain;background-position: center center;">
	<div>
		<div style="clear:both; position:relative; margin-left:1%; margin-top:-1%;">
			<div style="position:absolute; left:0pt; width:120pt;">
				<img style="width:200px; margin-top: 10%;" src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
			</div>
			<div style="position:absolute; left:170pt;margin-top: 2.5%;">
				<p style="margin-top: 0%;color:#c00000;font-weight:bold; font-size:30px;">E-TICKET</p>
			</div>
			<div style="float: right; font-weight: bold; font-size:15px;margin-top: 4%;">
				<p>Booking No</p>
				<p>{{ $orderTicket->code }}</p>
			</div>
		</div>
	</div>
	
	<div style="clear:both; text-align: center; padding: 0;">
		<table style="width: 500pt; margin-top: 4%; font-size: 18px;">
			<thead style="background-color: #c00000; border-collapse: collapse;">
				<tr>
					<th colspan="9" style="color:#fff">ITINERARY</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>From:</td>
					<td colspan="3">{{ $orderTicket->fromLocationName }}</td>
					<td colspan="3"></td>
					<td>Class:</td>
				</tr>
				<tr>
					<td>To:</td>
					<td colspan="3">{{ $orderTicket->toLocationName }}</td>
					<td colspan="3"></td>
					<td colspan="3" rowspan="2">{{ $orderTicket->seatClassName }}</td>
				</tr>
				<tr>
					<td>Date:</td>
					<td colspan="3">{{ $orderTicket->departDate }}</td>
				</tr>
				<tr>
					<td>Time:</td>
					<td colspan="3">{{ $orderTicket->departTime }}</td>
				</tr>
			</tbody>
		</table>
		<table class="passenger" style="width: 500pt;border: 1px solid #000;">
			<thead style="background-color: #c00000; border-collapse: collapse;">
				<tr>
					<th colspan="9" style="color:#fff">PASSENGER</th>
				</tr>
			</thead>
			<tbody>
				<tr style="text-align: center; font-weight: bold;">
					<td colspan="6">Information</td>
					<td>Pax</td>
					<td colspan="2">Total</td>
				</tr>
				<tr style="text-align: center;">
					<td>1</td>
					<td colspan="5">{{ $orderTicket->fullname }}(CHILDREN {{ $orderTicket->childrenQuantity }})</td>
					<td>{{ $orderTicket->childrenQuantity + $orderTicket->adultQuantity }}</td>
					<td colspan="2">{{ round($orderTicket->price / $orderTicket->adultQuantity) }}</td>
				</tr>
				<tr style="text-align: center;">
					<td colspan="7">Total</td>
					<td colspan="2">{{ round($orderTicket->price) }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div>
		<div style="clear:both; position:relative; margin-left:1%; margin-top:-1%;">
			<p style="font-weight: bold;">*Please show this E-ticket at our office to get the paper ticket before get on catamaran</p>
			<p style="font-weight: bold;">BOARDING POINT:</p>
			<p>If you travel from {{ $orderTicket->fromLocationName }} to {{ $orderTicket->toLocationName }}, please come to our office to check and get the paper ticket 30 minutes before departure time.</p>
			<p><strong style="font-weight: bold;">{{ $orderTicket->nameOffice }}</strong>: <a>{{ $orderTicket->googleMapUrl }}</a></p>
		</div>
	</div>
</body>
</html>
