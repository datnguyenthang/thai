<?php
namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\Component;
use App\Models\OmiseWebhookEvent;

class OmiseWebhook extends Component
{
    public function handle(Request $request) {

        $eventData = $request->getContent();
        $eventObject = $request->json()->all();

        if (isset($eventObject['data']['status']) && 
                $eventObject['data']['status'] == SUCCESSFUL) {

            // IF CHARGE CARD
            if (isset($eventObject['data']['card'])) $eventType = CARD;

            // IF CHARGE PROMPTPAY
            if (isset($eventObject['data']['source']['type']) && 
                $eventObject['data']['source']['type'] == PROMPTPAY) $eventType = PROMPTPAY;

            $eventChargeId = isset($eventObject['data']['id']) ? $eventObject['data']['id'] : '';
            $eventStatus = isset($eventObject['data']['status']) ? $eventObject['data']['status'] : '';

            // Save the event data into the database
            OmiseWebhookEvent::create([
                'eventType' => $eventType,
                'eventChargeid' => $eventChargeId,
                'eventStatus' => $eventStatus,
                'eventData' => $eventData,
            ]);
        }
    }
}
