<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\User;
use App\Models\StripePayment;

class PaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $user;
    public $payment;
    public $customSubject;
    public $customMessage;
    public $includeReceipt;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, User $user, StripePayment $payment = null, $customSubject = null, $customMessage = null, $includeReceipt = false)
    {
        $this->order = $order;
        $this->user = $user;
        $this->payment = $payment;
        $this->customSubject = $customSubject;
        $this->customMessage = $customMessage;
        $this->includeReceipt = $includeReceipt;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->customSubject ?? 'Confirmation de paiement - Commande #' . $this->order->id;
        
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
            view: 'emails.payment-confirmation',
            with: [
                'order' => $this->order,
                'user' => $this->user,
                'payment' => $this->payment,
                'customMessage' => $this->customMessage,
                'includeReceipt' => $this->includeReceipt,
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
        $attachments = [];
        
        if ($this->includeReceipt && $this->payment) {
            // Ici vous pouvez ajouter la logique pour générer et attacher un reçu PDF
            // $attachments[] = Attachment::fromPath($receiptPath);
        }
        
        return $attachments;
    }
}
