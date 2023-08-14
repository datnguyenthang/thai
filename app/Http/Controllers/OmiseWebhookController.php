<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\Component;

class OmiseWebhookController extends Component
{
    public function handleWebhook(Request $request) {
        //if (!$request->data) {
        //    return response()->json(['message' => 'Event not found.'], 404);
        //}

        $eventData = $request->data;

        // Emit a Livewire event to notify the component
        
        $this->emit('webhookEventReceived', $eventData);
    }
}
