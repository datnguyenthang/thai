<?php
namespace App\Listeners;

use App\Events\WebhookOmiseEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WebhookOmisetEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(WebhookOmiseEvent $event)
    {
        $eventData = $event->eventData;

        // Perform any necessary actions with the event data
        Log::info('Webhook event received:', $eventData);
    }
}