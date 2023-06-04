<!DOCTYPE html>
<html>
    <head>
        <title>PDF View</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            *{ font-family: DejaVu Sans !important;}
        </style>
    </head>
    <body>
		<div>
            <div style="clear:both; position:relative; margin-left:1%; margin-top:1%;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <img style="width:180px; margin-top: 12%;" src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
                </div>
                <div style="float: right; font-weight: bold; font-size:15px;">
                    <p style="margin-bottom: 1%;">LEOPARD TRANSPORTATION CO.,LTD</p>
                    <p style="margin-bottom: 1%; margin-top: 1%;">TEL: +666 3125 1421</p>
                    <p style="margin-bottom: 1%; margin-top: 1%;">LINE: @seudamgo</p>
                    <p style="margin-top: 1%;">WEBSITE: www.seudamgo.com</p>
                </div>
            </div>
		</div>

        <div style="clear:both; text-align: center; margin-top: 1%;">
            <h2>BOARDING PASS</h2>
        </div>

		<div style="margin-left:70%;font-weight:bold; margin-top:-2% !important;">
			<h4 style="margin-bottom: 1%; margin-top:0;">Booking no</h4>
			<h3 style="margin-top: 1%;">{{ $orderTicket->code }}</h3>
		</div>

        <div style="text-align: center; margin-top:-3% !important;">
            <h2 style="margin-top: 0;">{{ $orderTicket->fromLocationName }} to {{ $orderTicket->toLocationName }}</h2>
        </div>

        <div>
            <h4 style="margin-left:1%;margin-top:1%;">Customer Information</h4>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h4>Name:</h4>
                </div>
                <div style="margin-left:200pt;">
                    <h4>{{ $orderTicket->fullname }}</h4>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h4>Depart date:</h4>
                </div>
                <div style="margin-left:200pt;">
                    <h4>{{ $orderTicket->departDate }}</h4>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h4>Depart time:</h4>
                </div>
                <div style="margin-left:200pt;">
                    <h4>{{ $orderTicket->departTime }}</h4>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h4>Drop off:</h4>
                </div>
                <div style="margin-left:200pt;">
                    <h4>{{ $order->dropoff }}</h4>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h4>Pickup:</h4>
                </div>
                <div style="margin-left:200pt;">
                    <h4>{{ $order->pickup }}</h4>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h2>AGENT</h2>
                </div>
                <div style="margin-left:200pt; margin-top: 25pt;">
                    <span>Price <strong>฿{{ $orderTicket->price }}</strong></span>
                </div>
            </div>
        </div>
    </body>
</html>