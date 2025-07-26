<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
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
        $request->validate([
            'services_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $service = Service::findOrFail($request->services_id);
        $userId = Auth::id();

        // Vérifier si le service est déjà dans le panier
        $cartItem = Cart::where('user_id', $userId)
            ->where('services_id', $request->services_id)
            ->first();

        if ($cartItem) {
            // Mettre à jour la quantité
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
            
            $message = 'Quantité mise à jour dans le panier';
        } else {
            // Créer un nouvel élément dans le panier
            Cart::create([
                'user_id' => $userId,
                'services_id' => $request->services_id,
                'quantity' => $request->quantity,
                'price' => $service->price
            ]);
            
            $message = 'Service ajouté au panier';
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => Cart::getCartCount($userId)
            ]);
        }

        return redirect()->back()->with('success', $message);
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
}