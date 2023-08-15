<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class OmiseWebhook extends Component
{
    public function handleWebhook(Request $request) {
        $eventData = $request->data;

		if (isset($eventData['source']['type']) && $eventData['source']['type'] == 'promptpay') {
			$this->emitTo('frontend.homepage.payment.promptpay', 'webhookEventPromptpayReceived', $eventData);
			Log::debug('Emited to Promptpay');
		}
    }
}
