<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReplayEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent; // Change from $message to $messageContent

    /**
     * Create a new message instance.
     */
    public function __construct($messageContent)
    {
        $this->messageContent = $messageContent;
    }

    /**
     * Build the message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('general.ticket_replay'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_replay',
            with: [
                'messageContent' => $this->messageContent,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
