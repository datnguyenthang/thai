<div dir="ltr">Dear Customer, 
    <div>A confirmed booking for<br/> 
        @foreach ($order->orderTickets as $orderTicket)
            <b>{{ $orderTicket->fromLocationName }} - {{ $orderTicket->toLocationName }}, {{ date("j F, Y", strtotime($orderTicket->departDate)) }}, {{ $orderTicket->departTime }}</b><br/>
        @endforeach
    <br>
  </div>
  <div>
    <p>Please review the booking details in the pdf file and inform us immediately about any problems:</p>
    <p>*Please note that arrival times are not guaranteed. They are estimated, based on the operator's provisions, experience, and statistics. They may vary. Please do not base your connections on these figures, or at least add enough time till your next departure (we recommend a minimum of 5 hours for connecting flights). We are not responsible if your trip took longer or if you arrived before the time mentioned.*</p>
    <p></p>
    <ul>
      <li style="margin-left:15px">Please come to check in 60 minutes before the departure time.</li>
      <li style="margin-left:15px">Destinations and Routes cannot be changed.</li>
      <li style="margin-left:15px">Infant prices are available for infants aged between 0 – 5 years and will not be charged.</li>
      <li style="margin-left:15px">Departure and arrival times can be subject to change due to weather conditions, SEUDAMGO reserves the right not to be liable for any such delays.</li>
      <li style="margin-left:15px">Smoking is not permitted in the cabins of our boats or on any of our buses.</li>
      <li style="margin-left:15px">No-shows: Customers must check in at least 60 minutes prior to departure. Failure to check-in on time or in the case of missing the boat, the company reserves the right to no refunds.</li>
      <li style="margin-left:15px">Customers can park your car at our office in Trat. The service fee is just 50 THB/day/car.</li>
      <li style="margin-left:15px">Maximum 20KG luggage allowed per passenger, excess luggage will be charged at 20 THB per KG not exceeding 30 KG.</li>
      <li style="margin-left:15px">Water sports equipment (Such as short boards, longboards, skimboards, knee boards, windsurfing boards etc.) will be subject to a fee of 300 THB for travel by boat.</li>
      <li style="margin-left:15px">Bicycles – will be subject to a fee of 500 THB (restricted to folding bicycles only) for transport by boat only. The company does not transport any form of motorcycle.</li>
      <li style="margin-left:15px">Passengers can bring pets on boats by bringing one's own standard cage. Only one pet per cage is allowed or without cage, passengers shall take care and tie chains or leash ropes throughout the trip. Pets shall never be released without a leash. Passengers are responsible for any damage to other passengers or their belongings caused by pets. Please contact our office/pier staff for further instructions and procedures to be followed.</li>
    </ul>
    <p></p>
    <p></p>
    @if(!$orderTicket->agentName)
      <p>Total paid&nbsp; <b>THB {{ round($order->finalPrice) }}</b>
    @endif
    </p>
    <p>Thank you very much for using SEUDAMGO!</p>
    <p>Best regards <br>SEUDAMGO TEAM </p>
    <p>
      <b>
        <br>
      </b>
    </p>
  </div>
</div>