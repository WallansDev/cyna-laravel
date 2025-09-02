<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $customSubject;
    public $customMessage;
    public $welcomeBonus;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $customSubject = null, $customMessage = null, $welcomeBonus = null)
    {
        $this->user = $user;
        $this->customSubject = $customSubject;
        $this->customMessage = $customMessage;
        $this->welcomeBonus = $welcomeBonus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->customSubject ?? 'Bienvenue chez Cyna !';
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'customMessage' => $this->customMessage,
                'welcomeBonus' => $this->welcomeBonus,
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
