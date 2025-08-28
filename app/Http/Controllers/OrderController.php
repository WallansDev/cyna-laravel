<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StripeController;
use App\Models\Order;
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
        $orders = Order::where('user_id', auth()->id())->get();
        return view('orders.index', compact('orders'));
    }

    public function viewAdmin(Request $request)
    {
        $query = Order::query()->with(['user', 'items', 'billingAddress', 'stripePayment'])->orderByDesc('created_at');

        // Filtres simples
        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($sub) use ($q) {
                $sub->where('id', $q)
                    ->orWhere('total', 'like', "%$q%");
            });
        }
        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            $query->whereHas('stripePayment', function ($sp) use ($status) {
                $sp->where('status', $status);
            });
        }
        if ($request->boolean('has_coupon')) {
            $query->whereHas('stripePayment', function ($sp) {
                $sp->whereNotNull('applied_coupon_code');
            });
        }

        $orders = $query->paginate(25)->appends($request->query());

        return view('orders.indexAdmin', compact('orders'));
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