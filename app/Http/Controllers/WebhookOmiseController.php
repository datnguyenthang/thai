<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\WebhookOmiseEvent;

class WebhookOmiseController extends Controller
{
    public function handle(Request $request)
    {
        $eventData = $request->data;

        if (isset($eventData['source']['type']) && $eventData['source']['type'] == 'promptpay') {
            event(new WebhookOmiseEvent($eventData)); // Emit the event
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}