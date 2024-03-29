<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>PDF View</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@700&display=swap" rel="stylesheet">
    <style>
        h5.m {
			font-weight: bold;
            font-family: 'Noto Sans Thai';
        }
        .t {
			font-weight: bold;
            font-family: 'Noto Sans Thai';
        }

        @page { margin: 0.1in 0.1in 0.1in 0.1in;}
    </style>
</head>
<body>
    <div>
        <div style="clear:both; position:relative; margin-left:1%; margin-top:-1%;">
            <div style="position:absolute; left:0pt; width:120pt;">
                <img style="width:140px; margin-top: 10%;" src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo">
            </div>
            <div style="float: right; font-weight: bold; font-size:10px;">
                <p style="margin-bottom: 1%;">LEOPARD TRANSPORTATION CO.,LTD</p>
                <p style="margin-bottom: 1%; margin-top: 0%;">TEL: +666 3125 1421</p>
                <p style="margin-bottom: 1%; margin-top: 0%;">LINE: @seudamgo</p>
                <p style="margin-top: 0%;">WEBSITE: www.seudamgo.com</p>
            </div>
        </div>
    </div>

    <div style="clear:both; text-align: center; padding: 0;">
        <h3 style="padding: 0; margin: 0;">BOARDING PASS</h3>
    </div>

    <div style="margin-left:62%; margin-top:0% !important;">
        <h6 style="margin-bottom: 1%; margin-top:0; font-style: italic; font-weight: bold;">Booking no</h6>
        <h4 style="margin-top: 1%; font-weight:bold; margin-top:0;">{{ $orderTicket->code }}</h4>
    </div>

    <div style="text-align: center; margin-top:-3% !important;">
        <h4 style="margin-top: 0;">{{ $orderTicket->fromLocationName }} to {{ $orderTicket->toLocationName }}</h4>
    </div>

    <div>
        <h5 style="margin-left:1%; margin-top:-3%; font-style: italic; font-weight: bold;">Customer Information</h5>

        <div style="clear:both; position:relative; margin-left: 1%; margin-top: -13% !important;">
            <div style="position:absolute; left:0pt; width:192pt;">
                <h5>Name:</h5>
            </div>
            <div style="margin-left:100pt;">
                <h5 class="m">{{ $orderTicket->fullname }}</h5>
            </div>
        </div>

        <div style="clear:both; position:relative; margin-left:1%; margin-top:-8% !important;">
            <div style="position:absolute; left:0pt; width:192pt;">
                <h5>Depart time:</h5>
            </div>
            <div style="margin-left:100pt;">
                <h4>{{ $orderTicket->departTime }}</h4>
            </div>
        </div>

        <div style="clear:both; position:relative; margin-left:1%; margin-top:-8% !important;">
            <div style="position:absolute; left:0pt; width:192pt;">
                <h5>Depart date:</h5>
            </div>
            <div style="margin-left:100pt;">
                <h4>{{ $orderTicket->departDate }}</h4>
            </div>
        </div>

        <div style="clear:both; position:relative; margin-left:1%; margin-top:-8% !important;">
            <div style="position:absolute; left:0pt; width:192pt;">
                <h5>Drop off:</h5>
            </div>
            <div style="margin-left:100pt;">
                <h5 class="m">{{ $orderTicket->dropoff }}</h5>
            </div>
        </div>

        <div style="clear:both; position:relative; margin-left:1%; margin-top:-8% !important;">
            <div style="position:absolute; left:0pt; width:192pt;">
                <h5>Pickup:</h5>
            </div>
            <div style="margin-left:100pt;">
                <h5 class="m">{{ $orderTicket->pickup }}</h5>
            </div>
        </div>
        @if($orderTicket->agentName)
            <div style="clear:both; position:relative; margin-left:1%; margin-top:-5% !important;">
                <div style="position:absolute; left:20pt; width:192pt;">
                    <h3>AGENT</h3>
                </div>
                <div style="margin-left:170pt; margin-top: 13pt;">
                    <strong style="font-size: 20px;">{{ $orderTicket->agentName }}</strong>
                </div>
            </div>
        @else
            <div style="clear:both; position:relative; margin-left:1%; margin-top:-5% !important;">
                <div style="position:absolute; left:20pt; width:192pt;">
                    <h3>PRICE</h3>
                </div>
                <div style="margin-left:170pt; margin-top: 13pt;">
                    <strong class="t">฿</strong><strong style="font-size: 20px;">{{ round($orderTicket->price) }}</strong>
                </div>
            </div>
        @endif
    </div>
</body>
</html>