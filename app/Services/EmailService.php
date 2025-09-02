<?php

namespace App\Services;

use App\Mail\OrderConfirmMail;
use App\Mail\WelcomeMail;
use App\Mail\PaymentConfirmationMail;
use App\Models\Order;
use App\Models\User;
use App\Models\StripePayment;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Envoyer un email de confirmation de commande
     */
    public function sendOrderConfirmation(Order $order, User $user, $customSubject = null, $customMessage = null)
    {
        try {
            Mail::to($user->email)->send(new OrderConfirmMail($order, $user, $customSubject, $customMessage));
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation commande: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email de bienvenue
     */
    public function sendWelcome(User $user, $customSubject = null, $customMessage = null, $welcomeBonus = null)
    {
        try {
            Mail::to($user->email)->send(new WelcomeMail($user, $customSubject, $customMessage, $welcomeBonus));
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email bienvenue: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email de confirmation de paiement
     */
    public function sendPaymentConfirmation(Order $order, User $user, StripePayment $payment = null, $customSubject = null, $customMessage = null, $includeReceipt = false)
    {
        try {
            Mail::to($user->email)->send(new PaymentConfirmationMail($order, $user, $payment, $customSubject, $customMessage, $includeReceipt));
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email confirmation paiement: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email personnalisé avec un template spécifique
     */
    public function sendCustomEmail($to, $subject, $view, $data = [])
    {
        try {
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->to($to)->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email personnalisé: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoyer un email de mise à jour de statut de commande
     */
    public function sendOrderStatusUpdate(Order $order, User $user, $newStatus, $customMessage = null)
    {
        $subject = "Mise à jour de votre commande #{$order->id}";
        $message = $customMessage ?? "Le statut de votre commande a été mis à jour vers : {$newStatus}";
        
        return $this->sendOrderConfirmation($order, $user, $subject, $message);
    }

    /**
     * Envoyer un email de rappel de panier abandonné
     */
    public function sendCartReminder(User $user, $cartItems, $customMessage = null)
    {
        $subject = "Votre panier vous attend !";
        $message = $customMessage ?? "Vous avez des articles dans votre panier qui vous attendent.";
        
        return $this->sendCustomEmail($user->email, $subject, 'emails.cart-reminder', [
            'user' => $user,
            'cartItems' => $cartItems,
            'customMessage' => $customMessage
        ]);
    }
}
