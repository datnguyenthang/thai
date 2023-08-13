<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omise\SignatureVerifier;
use Livewire\Facades\LiveWire;
use App\Http\Livewire\OmiseWebhookHandler;

class OmiseWebhookController extends Controller
{
    public function handleWebhook(Request $request) {
        $payload = $request->getContent();
        $signature = $request->header('Omise-Signature');
        $secretKey = OMISE_SECRET_KEY;

        $verifier = new SignatureVerifier($secretKey);
        if (!$verifier->verify($signature, $payload)) {
            return response()->json(['message' => 'Signature verification failed.'], 400);
        }

        $eventData = json_decode($payload, true);

        // Emit a Livewire event to notify the component
        LiveWire::component('omise-webhook-handler', OmiseWebhookHandler::class);
        LiveWire::emit('webhookEventReceived', $eventData);

        return response()->json(['message' => 'Webhook event received.']);
    }
}
