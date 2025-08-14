<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    /**
     * Affiche la page de paiement
     */
    public function checkout(Request $request)
    {
        try {
            // Récupérer la commande en attente depuis la session
            $orderData = session('pending_order');
            
            if (!$orderData) {
                return redirect()->route('cart.index')->with('error', 'Aucune commande en attente.');
            }

            // Convertir le montant en centimes pour Stripe
            $amount = (int)($orderData['total'] * 100);
            $currency = 'eur';

            // Créer l'intention de paiement
            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => [
                    'order_id' => $orderData['id'],
                    'user_id' => auth()->id() ?? 'guest',
                    'items_count' => count($orderData['items'])
                ]
            ]);

            return view('stripe.checkout', [
                'clientSecret' => $paymentIntent->client_secret,
                'amount' => $orderData['total'], // Montant en euros
                'currency' => strtoupper($currency),
                'stripeKey' => config('stripe.publishable_key'),
                'order' => $orderData
            ]);

        } catch (Exception $e) {
            Log::error('Erreur lors de la création du paiement Stripe: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'initialisation du paiement.');
        }
    }

    /**
     * Page de succès après paiement
     */
    public function success(Request $request)
    {
        try {
            $paymentIntentId = $request->get('payment_intent');
            
            if ($paymentIntentId) {
                $paymentIntent = $this->stripe->paymentIntents->retrieve($paymentIntentId);
                
                if ($paymentIntent->status === 'succeeded') {
                    // Ici vous pouvez ajouter votre logique métier
                    // Par exemple: mettre à jour le statut de la commande
                    
                    return view('stripe.success', [
                        'paymentIntent' => $paymentIntent,
                        'amount' => $paymentIntent->amount / 100
                    ]);
                }
            }

            return redirect()->route('stripe.checkout')->with('error', 'Paiement non trouvé ou échoué.');

        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification du paiement: ' . $e->getMessage());
            return redirect()->route('stripe.checkout')->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    /**
     * Page d'échec du paiement
     */
    public function cancel(Request $request)
    {
        return view('stripe.cancel', [
            'error' => $request->get('error', 'Paiement annulé.')
        ]);
    }

    /**
     * Webhook pour traiter les événements Stripe
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook Stripe: Payload invalide');
            return response('Payload invalide', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook Stripe: Signature invalide');
            return response('Signature invalide', 400);
        }

        // Traiter les événements
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            default:
                Log::info('Événement Stripe non géré: ' . $event->type);
        }

        return response('OK', 200);
    }

    /**
     * Gérer le succès du paiement via webhook
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Paiement réussi: ' . $paymentIntent->id);
        
        // Ici vous pouvez ajouter votre logique métier
        // Par exemple: envoyer un email de confirmation, mettre à jour la base de données, etc.
    }

    /**
     * Gérer l'échec du paiement via webhook
     */
    private function handlePaymentFailed($paymentIntent)
    {
        Log::info('Paiement échoué: ' . $paymentIntent->id);
        
        // Ici vous pouvez ajouter votre logique métier
        // Par exemple: notifier l'utilisateur, mettre à jour le statut de la commande, etc.
    }
}
