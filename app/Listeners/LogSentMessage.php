<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Mail\Events\MessageSent   $event
     * @return void
     */
    public function handle(MessageSent  $event){
        //$messageId = $event->data['__laravel_notification_id'] ?? Str::uuid();
        Storage::disk('emails')->put(
            sprintf('%s_%s_%s.eml', $event->data['order']['code'], now()->format('Y-m-d H-i-s'), ORDERSTATUS[$event->data['order']['status']]),
            $event->message->toString()
        );
    }
}
