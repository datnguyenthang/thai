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
        if ($eventData['source']['charge_status'] == SUCCESSFUL) {
            $eventType = isset($eventData['source']['type']) ? $eventData['source']['type'] : '';
            $eventChargeId = isset($eventData['id']) ? $eventData['id'] : '';
            $eventStatus = isset($eventData['source']['charge_status']) ? $eventData['source']['charge_status'] : '';

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
