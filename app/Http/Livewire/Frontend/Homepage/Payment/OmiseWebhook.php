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
        
        if ($eventData['data']['source']['charge_status'] == SUCCESSFUL) {
            $eventType = isset($eventData['data']['source']['type']) ? $eventData['data']['source']['type'] : '';
            $eventChargeId = isset($eventData['data']['id']) ? $eventData['data']['id'] : '';
            $eventStatus = isset($eventData['data']['source']['charge_status']) ? $eventData['data']['source']['charge_status'] : '';

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
