<?php
namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\Component;
use App\Models\OmiseWebhookEvent;

class OmiseWebhook extends Component
{
    public function handle(Request $request) {
        $eventData = $request->data;
		$eventType = $request->header('Omise-Event-Type');

		if (isset($eventData['source']['type']) && $eventData['source']['type'] == 'promptpay') {
			OmiseWebhookEvent::create([
										'event_type' => $eventType,
										'event_data' => $eventData,
									]);
		}
    }
}
