<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use App\Models\BillingAddress;
use Stripe\StripeClient;

class CartController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    /**
     * Afficher le contenu du panier
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('service')
            ->get();

        $total = Cart::getCartTotal(Auth::id());
        $itemCount = Cart::getCartCount(Auth::id());

        return view('cart.index', compact('cartItems', 'total', 'itemCount'));
    }

    /**
     * Ajouter un service au panier
     */
    public function add(Request $request)
    {
        $service = Service::findOrFail($request->input('services_id'));
        $priceType = $request->input('price_type', 'monthly');
        $price = $priceType === 'yearly' ? $service->price_yearly : $service->price_monthly;

        Cart::create([
            'user_id' => Auth::id(),
            'services_id' => $service->id,
            'quantity' => $request->input('quantity', 1),
            'price' => $price,
            'price_type' => $priceType, // <-- doit être 'monthly' ou 'yearly' selon le select
        ]);

        return redirect()->route('cart.index')->with('success', 'Service ajouté au panier !');
    }

    /**
     * Mettre à jour la quantité d'un service dans le panier
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Quantité mise à jour',
                'subtotal' => $cartItem->subtotal,
                'total' => Cart::getCartTotal(Auth::id())
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Quantité mise à jour');
    }

    /**
     * Supprimer un service du panier
     */
    public function remove($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Service supprimé du panier',
                'cart_count' => Cart::getCartCount(Auth::id()),
                'total' => Cart::getCartTotal(Auth::id())
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Service supprimé du panier');
    }

    /**
     * Vider complètement le panier
        */
        public function clear()
        {
            Cart::where('user_id', Auth::id())->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Panier vidé'
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Panier vidé');
        }

    /**
     * Obtenir le nombre d'articles dans le panier (pour l'affichage dans le header)
     */
    public function count()
    {
        return response()->json([
            'count' => Cart::getCartCount(Auth::id())
        ]);
    }

    /**
     * Appliquer un code promo Stripe (Promotion Code)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->input('code')));

        try {
            $promoCodes = $this->stripe->promotionCodes->all([
                'code' => $code,
                'active' => true,
                'limit' => 1,
            ]);

            if (empty($promoCodes->data)) {
                return back()->with('error', 'Code promo invalide ou inactif.');
            }

            $promotion = $promoCodes->data[0];
            $coupon = $promotion->coupon;

            // Vérifications basiques: expiration (Stripe gère côté coupon/promotion), currency via amount_off
            $total = (float) Cart::getCartTotal(Auth::id());
            if ($total <= 0) {
                return back()->with('error', 'Panier vide.');
            }

            $discountAmount = 0.0;
            if (!empty($coupon->percent_off)) {
                $discountAmount = round($total * ((float)$coupon->percent_off / 100), 2);
            } elseif (!empty($coupon->amount_off)) {
                // amount_off est en centimes dans la devise du coupon
                $discountAmount = round(((int)$coupon->amount_off) / 100, 2);
                $discountAmount = min($discountAmount, $total);
            }

            $selectedCoupon = [
                'promotion_code_id' => $promotion->id,
                'coupon_id' => $coupon->id,
                'code' => $code,
                'percent_off' => $coupon->percent_off ?? null,
                'amount_off' => $coupon->amount_off ?? null, // centimes
                'currency' => $coupon->currency ?? 'eur',
                'discount_amount' => $discountAmount,
                'discounted_total' => max(0, $total - $discountAmount),
            ];

            Session::put('selected_coupon', $selectedCoupon);

            return back()->with('success', 'Code promo appliqué.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la vérification du code promo.');
        }
    }

    /**
     * Retirer le code promo
     */
    public function removeCoupon()
    {
        Session::forget('selected_coupon');
        return back()->with('success', 'Code promo retiré.');
    }

    /**
     * Passer une commande
     */
    public function order(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();
        $total = Cart::getCartTotal(Auth::id());

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
        ]);

        OrderItem::createFromCartItems($cartItems, $order);

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('orders.index')->with('success', 'Commande enregistrée !');
    }

    /**
     * Préparer la commande et rediriger vers Stripe Checkout
     */
    public function checkoutToStripe(Request $request)
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->with('service')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $total = Cart::getCartTotal($userId);

        // Adresse de facturation requise
        $billingAddressId = Session::get('selected_billing_address_id');
        if (!$billingAddressId) {
            return redirect()->route('order.select-billing-address')->with('error', 'Sélectionnez une adresse de facturation.');
        }
        $billingAddress = BillingAddress::where('user_id', $userId)->findOrFail($billingAddressId);

        // Coupon Stripe en session
        $selectedCoupon = Session::get('selected_coupon');
        $discountAmount = 0.0;
        $discountedTotal = (float) $total;
        if ($selectedCoupon) {
            $discountAmount = (float) ($selectedCoupon['discount_amount'] ?? 0);
            $discountedTotal = max(0, (float) $total - $discountAmount);
        }

        $items = $cartItems->map(function ($item) {
            return [
                'name' => $item->service ? $item->service->name : 'Service',
                'price' => (float) $item->price,
                'quantity' => (int) $item->quantity,
            ];
        })->toArray();

        $orderData = [
            'id' => 'ORDER_' . time(),
            'items' => $items,
            'total' => (float) $discountedTotal,
            'billing_info' => [
                'address_line1' => $billingAddress->address_line1,
                'address_line2' => $billingAddress->address_line2,
                'city' => $billingAddress->city,
                'postal_code' => $billingAddress->postal_code,
                'country' => $billingAddress->country,
                'phone' => $billingAddress->phone,
            ],
            'coupon' => $selectedCoupon,
            'created_at' => now(),
        ];

        Session::put('pending_order', $orderData);

        return redirect()->route('stripe.checkout');
    }
}