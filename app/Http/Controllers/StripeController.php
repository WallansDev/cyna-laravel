<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\StripePayment;
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
            $metadata = [
                'order_id' => $orderData['id'],
                'user_id' => auth()->id() ?? 'guest',
                'items_count' => count($orderData['items'])
            ];
            if (!empty($orderData['coupon'])) {
                $metadata['coupon_code'] = $orderData['coupon']['code'] ?? null;
                $metadata['promotion_code_id'] = $orderData['coupon']['promotion_code_id'] ?? null;
                $metadata['coupon_id'] = $orderData['coupon']['coupon_id'] ?? null;
                $metadata['discount_amount'] = $orderData['coupon']['discount_amount'] ?? null;
            }

            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => $metadata
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
                    // Paiement validé : créer la commande en BDD, vider le panier et la session
                    $pendingOrder = Session::get('pending_order');

                    if ($pendingOrder && Auth::check()) {
                        // Persister StripePayment
                        $stripePayment = StripePayment::updateOrCreate(
                            ['payment_intent_id' => $paymentIntent->id],
                            [
                                'status' => $paymentIntent->status,
                                'amount' => $paymentIntent->amount,
                                'currency' => $paymentIntent->currency,
                                'metadata' => $paymentIntent->metadata ? $paymentIntent->metadata->toArray() : null,
                                'applied_promotion_code' => $pendingOrder['coupon']['promotion_code_id'] ?? null,
                                'applied_coupon_id' => $pendingOrder['coupon']['coupon_id'] ?? null,
                                'applied_coupon_code' => $pendingOrder['coupon']['code'] ?? null,
                                'discount_amount' => isset($pendingOrder['coupon']['discount_amount']) ? (float)$pendingOrder['coupon']['discount_amount'] : null,
                            ]
                        );
                        $selectedBillingAddressId = Session::get('selected_billing_address_id');
                        $order = Order::create([
                            'user_id' => Auth::id(),
                            'total' => (float)($pendingOrder['total'] ?? 0),
                            'billing_address_id' => $selectedBillingAddressId,
                            'stripe_payment_id' => $stripePayment->id,
                        ]);

                        $items = $pendingOrder['items'] ?? [];
                        foreach ($items as $item) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'service_name' => $item['name'] ?? 'Service',
                                'quantity' => (int)($item['quantity'] ?? 1),
                                'price' => (float)($item['price'] ?? 0),
                            ]);
                        }

                        // Vider le panier en BDD et la session
                        Cart::where('user_id', Auth::id())->delete();
                        Session::forget('pending_order');
                        Session::forget('billing_info');
                        Session::forget('selected_billing_address_id');
                        Session::forget('selected_coupon');
                    }

                    return redirect()->route('orders.index')->with('success', 'Paiement validé, commande créée.');
                }
            }

            return redirect()->route('stripe.checkout')->with('error', 'Paiement non trouvé ou échoué.');

        } catch (Exception $e) {
            dd('Erreur lors de la vérification du paiement: ' . $e->getMessage());
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
     * Crée une commande de test et redirige vers le paiement
     */
    public function createTestOrder(Request $request)
    {
        try {
            // Créer une commande de test
            $testOrder = [
                'id' => 'TEST_ORDER_' . time(),
                'items' => [
                    [
                        'name' => 'Service de test',
                        'price' => 25.00,
                        'quantity' => 1
                    ],
                    [
                        'name' => 'Service premium',
                        'price' => 15.00,
                        'quantity' => 2
                    ]
                ],
                'total' => 55.00, // 25 + (15 * 2)
                'billing_info' => [
                    'billing_name' => 'Test User',
                    'billing_email' => 'test@example.com',
                    'billing_address' => '123 Test Street',
                    'billing_city' => 'Test City',
                    'billing_postal_code' => '12345',
                    'billing_country' => 'France'
                ],
                'created_at' => now(),
            ];

            // Sauvegarder la commande en session
            session(['pending_order' => $testOrder]);

            // Rediriger vers le checkout
            return redirect()->route('stripe.checkout');

        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la commande de test: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la création de la commande de test.');
        }
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
