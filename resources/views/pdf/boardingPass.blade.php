<!DOCTYPE html>
<html>
    <head>
        <title>PDF View</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            @page { size: 600 550 portrait; }
            *{ font-family: DejaVu Sans !important;}
        </style>
    </head>
    <body style="width: 550px;height: 600px;">
		<div>
            <div style="clear:both; position:relative; margin-left:1%;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <img style="width:180px; margin-top: 12%;" src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
                </div>
                <div style="float: right; font-weight: bold; font-size:18px;">
                    <p style="margin-bottom: 1%;">LEOPARD TRANSPORTATION CO.,LTD</p>
                    <p style="margin-bottom: 1%; margin-top: 1%;">TEL: +666 3125 1421</p>
                    <p style="margin-bottom: 1%; margin-top: 1%;">LINE: @seudamgo</p>
                    <p style="margin-top: 1%;">WEBSITE: www.seudamgo.com</p>
                </div>
            </div>
		</div>

        <div style="clear:both; text-align: center;">
            <h1>BOARDING PASS</h1>
        </div>

		<div style="margin-left:70%;font-weight:bold; margin-top:-2% !important;">
			<h4 style="margin-bottom: 1%; margin-top:0;">Booking no</h4>
			<h3 style="margin-top: 1%;">{{ $orderTicket->code }}</h3>
		</div>

        <div style="text-align: center; margin-top:-3% !important;">
            <h1 style="margin-top: 0;">{{ $orderTicket->fromLocationName }} to {{ $orderTicket->toLocationName }}</h1>
        </div>

        <div>
            <h3 style="margin-left:1%;margin-top:1%;">Customer Information</h3>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h3>Name:</h3>
                </div>
                <div style="margin-left:200pt;">
                    <h3>{{ $orderTicket->fullname }}</h3>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h3>Depart date:</h3>
                </div>
                <div style="margin-left:200pt;">
                    <h3>{{ $orderTicket->departDate }}</h3>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h3>Depart time:</h3>
                </div>
                <div style="margin-left:200pt;">
                    <h3>{{ $orderTicket->departTime }}</h3>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h3>Drop off:</h3>
                </div>
                <div style="margin-left:200pt;">
                    <h3>{{ $orderTicket->dropoff }}</h3>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%; margin-top:-3% !important;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h3>Pickup:</h3>
                </div>
                <div style="margin-left:200pt;">
                    <h3>{{ $orderTicket->pickup }}</h3>
                </div>
            </div>

            <div style="clear:both; position:relative; margin-left:1%;">
                <div style="position:absolute; left:0pt; width:192pt;">
                    <h1>AGENT</h1>
                </div>
                <div style="margin-left:200pt; margin-top: 25pt;">
                    <span>Price <strong>฿{{ $orderTicket->price }}</strong></span>
                </div>
            </div>
        </div>
    </body>
</html>