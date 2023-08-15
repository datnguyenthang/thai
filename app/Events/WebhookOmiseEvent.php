<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class WebhookOmiseEvent
{
    use Dispatchable;

    public $eventData;

    public function __construct($eventData)
    {
        $this->eventData = $eventData;
    }
}
