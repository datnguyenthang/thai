<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\LiveWire;
use Illuminate\Support\Facades\Log;

class OmiseWebhookController extends Controller
{
    public function handleWebhook(Request $request) {
        Log::debug('Webhook request received', ['payload' => $request->getContent()]);
        $payload = $request->getContent();
        $signature = $request->header('Omise-Signature');
        $secretKey = OMISE_SECRET_KEY;

        // Verify the signature
        $event = \OmiseEvent::retrieve($signature, [], $secretKey);

        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        $eventData = $event->data;

        // Emit a Livewire event to notify the component
        LiveWire::emit('webhookEventReceived', $eventData);

        return response()->json(['message' => 'Webhook event received.']);
    }
}
