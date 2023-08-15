<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Livewire\Livewire;
use App\Http\Livewire\Frontend\Homepage\Payment\Promptpay;

class WebhookOmiseController extends Controller
{
    public function handle(Request $request)
    {
        $eventData = $request->data;

		if (isset($eventData['source']['type']) && $eventData['source']['type'] == 'promptpay') {
            Livewire::emit('frontend.homepage.payment.promptpay.webhookEventPromptpayReceived', $eventData);
		}        

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
