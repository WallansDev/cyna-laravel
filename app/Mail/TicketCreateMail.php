<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;

class TicketCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $message;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, Message $message, User $user)
    {
        $this->ticket = $ticket;
        $this->message = $message;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Création du ticket #' . $this->ticket->id;
        
        return new Envelope(
            subject: $subject,
            bcc: [env('MAIN_ADMIN_MAIL')],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-create',
            with: [
                'message' => $this->message,
                'user' => $this->user,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
