<?php
namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Illuminate\Http\Request;
use Omise\Omise;
use Livewire\Component;
use App\Models\OmiseWebhookEvent;

class OmiseWebhook extends Component
{
    public function handle(Request $request) {
        $eventData = $request->getContent(); // Assuming the data is in JSON format
        $eventType = $request->header('Omise-Event-Type'); // Replace with the actual header name

        // Save the event data into the database
        OmiseWebhookEvent::create([
            'event_type' => $eventType,
            'event_data' => $eventData,
        ]);
    }
}
