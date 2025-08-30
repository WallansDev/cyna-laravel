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

    public function downloadInvoice($orderId)
    {
        $order = Order::with(['items', 'billingAddress', 'user'])->findOrFail($orderId);
        $pdf = \PDF::loadView('orders.invoice', compact('order'));
        $filename = 'facture-commande-' . $order->id . '.pdf';
        return $pdf->download($filename);
    }
    
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

    public function ordersGraph()
    {
        // Agrégations de base
        $last30 = now()->subDays(30);
        $ordersLast30 = Order::where('created_at', '>=', $last30)->get();

        $revenueByDay = $ordersLast30
            ->groupBy(fn($o) => $o->created_at->format('Y-m-d'))
            ->map(fn($g) => (float) $g->sum('total'))
            ->toArray();

        $ordersCountByDay = $ordersLast30
            ->groupBy(fn($o) => $o->created_at->format('Y-m-d'))
            ->map(fn($g) => $g->count())
            ->toArray();

        $last12Months = now()->subMonths(12);
        $ordersLast12 = Order::where('created_at', '>=', $last12Months)->get();
        $revenueByMonth = $ordersLast12
            ->groupBy(fn($o) => $o->created_at->format('Y-m'))
            ->map(fn($g) => (float) $g->sum('total'))
            ->toArray();

        $totalRevenue = (float) Order::sum('total');
        $totalOrders = (int) Order::count();
        $avgOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0.0;

        // Normaliser les axes (30 jours)
        $days = collect(range(0, 29))
            ->map(fn($i) => now()->subDays(29 - $i)->format('Y-m-d'))
            ->values();
        $chartRevenueData = $days->map(fn($d) => $revenueByDay[$d] ?? 0)->values();
        $chartOrdersData = $days->map(fn($d) => $ordersCountByDay[$d] ?? 0)->values();

        return view('admin.order.graph', [
            'days' => $days,
            'chartRevenueData' => $chartRevenueData,
            'chartOrdersData' => $chartOrdersData,
            'revenueByMonth' => $revenueByMonth,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'avgOrderValue' => $avgOrderValue,
        ]);
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