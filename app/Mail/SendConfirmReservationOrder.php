<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendConfirmReservationOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $order;

    public function __construct($name, $email, $order)
    {
        $this->name = $name;
        $this->email = $email;
        $this->order  = $order;
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function build()
    {
        return $this->view('emails.sendCancelationUnpaid')
                    ->with(['name' => $this->name, 'email' => $this->email, 'order' => $this->order]);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'SEUDAMGO - CONFIRM RESERVE ORDER',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.sendConfirmReservationOrder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
