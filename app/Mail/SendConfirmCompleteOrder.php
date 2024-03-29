<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendConfirmCompleteOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfFiles;

    public function __construct($order, $pdfFiles)
    {
        $this->order = $order;
        $this->pdfFiles = $pdfFiles;
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function build()
    {
        foreach ($this->pdfFiles as $pdfFile) {
            $this->attachData($pdfFile['content'], $pdfFile['filename']);
        }

        return $this->view('emails.sendConfirmCompleteOrder')
                    ->with(['order' => $this->order]);
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'SEUDAMGO - CONFIRMATION ORDER',
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
            view: 'emails.sendConfirmCompleteOrder',
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
