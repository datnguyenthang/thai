<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\Component;

class OmiseWebhookController extends Component
{
    public function handleWebhook(Request $request) {
        $eventData = $request->data;

		if (isset($eventData['source']['type']) && $eventData['source']['type'] == 'promptpay') {
			$this->emitTo('promptpay', 'webhookEventReceived', $eventData);
			//$this->dispatchBrowserEvent('promptpay-update', ['eventData' => $eventData]);
		}
    }
}
