<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\BillingAddress;

class OrderController extends Controller
{
    protected $stripeController;

    public function __construct(StripeController $stripeController)
    {
        $this->stripeController = $stripeController;
    }

    /**
     * Affiche la page de finalisation de commande
     */
    // public function checkout(Request $request)
    // {
    //     $cart = Session::get('cart', []);
    //     if (empty($cart)) {
    //         return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
    //     }
    //     $total = $this->calculateCartTotal($cart);
    //     return view('orders.checkout', [
    //         'cart' => $cart,
    //         'total' => $total,
    //         'items_count' => count($cart)
    //     ]);
    // }

    /**
     * Traite la commande et redirige vers Stripe
     */
    // public function processOrder(Request $request)
    // {
    //     $request->validate([
    //         'billing_name' => 'required|string|max:255',
    //         'billing_email' => 'required|email',
    //         'billing_address' => 'required|string',
    //         'billing_city' => 'required|string',
    //         'billing_postal_code' => 'required|string',
    //         'billing_country' => 'required|string',
    //     ]);
    //     $cart = Session::get('cart', []);
    //     if (empty($cart)) {
    //         return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
    //     }
    //     $total = $this->calculateCartTotal($cart);
    //     Session::put('billing_info', $request->only([
    //         'billing_name', 'billing_email', 'billing_address', 
    //         'billing_city', 'billing_postal_code', 'billing_country'
    //     ]));
    //     $orderData = [
    //         'id' => 'ORDER_' . time(),
    //         'items' => $cart,
    //         'total' => $total,
    //         'billing_info' => Session::get('billing_info'),
    //         'created_at' => now(),
    //     ];
    //     Session::put('pending_order', $orderData);
    //     return redirect()->route('stripe.checkout');
    // }

    /**
     * Affiche la confirmation de commande
     */
    // public function confirmation(Request $request)
    // {
    //     $orderData = Session::get('pending_order');
    //     if (!$orderData) {
    //         return redirect()->route('home')->with('error', 'Aucune commande en attente.');
    //     }
    //     Session::forget('cart');
    //     Session::forget('pending_order');
    //     Session::forget('billing_info');
    //     return view('orders.confirmation', [
    //         'order' => $orderData
    //     ]);
    // }

    /**
     * Calcule le total du panier
     */
    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        return $total;
    }

    /**
     * Affiche l'historique des commandes (pour les utilisateurs connectés)
     */
    public function history()
    {
        // À adapter selon ta logique d'enregistrement des commandes
        return view('orders.history', [
            'orders' => []
        ]);
    }

    /**
     * Affiche la liste des commandes pour un utilisateur
     */
    public function index()
    {
        // Exemple : récupère les commandes de l'utilisateur connecté
        $orders = \App\Models\Order::where('user_id', auth()->id())->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Afficher la sélection d'adresse de facturation
     */
    public function selectBillingAddress()
    {
        $addresses = BillingAddress::where('user_id', Auth::id())->orderByDesc('created_at')->get();
        if ($addresses->isEmpty()) {
            return redirect()->route('billing-addresses.index')->with('error', 'Ajoutez d\'abord une adresse de facturation.');
        }
        $selectedId = Session::get('selected_billing_address_id');
        return view('orders.select_billing_address', compact('addresses', 'selectedId'));
    }

    /**
     * Enregistrer l'adresse de facturation sélectionnée
     */
    public function storeSelectedBillingAddress(Request $request)
    {
        $request->validate([
            'billing_address_id' => 'required|integer|exists:billing_addresses,id',
        ],
    [
        'billing_address_id.required' => 'Il faut sélectionner une adresse de facturation.',
    ]);

        $address = BillingAddress::where('user_id', Auth::id())
            ->where('id', $request->billing_address_id)
            ->firstOrFail();

        Session::put('selected_billing_address_id', $address->id);

        return redirect()->route('cart.checkout');
    }
}