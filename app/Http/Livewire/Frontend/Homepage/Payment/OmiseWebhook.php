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
        
        if (isset($eventObject['data']['source']['charge_status']) && 
                    $eventObject['data']['source']['charge_status'] == SUCCESSFUL) {
            $eventType = isset($eventObject['data']['source']['type']) ? $eventObject['data']['source']['type'] : '';
            $eventChargeId = isset($eventObject['data']['id']) ? $eventObject['data']['id'] : '';
            $eventStatus = isset($eventObject['data']['source']['charge_status']) ? $eventObject['data']['source']['charge_status'] : '';

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
