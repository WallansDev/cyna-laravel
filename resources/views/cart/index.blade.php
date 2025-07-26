@extends('layouts.base')

@section('title', 'Mon Panier')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mon Panier</h1>

    @if($cartItems->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border-b pb-4" data-cart-item="{{ $item->id }}">
                        <div class="flex items-center space-x-4">
                            @if($item->service->image)
                                <img src="{{ asset('storage/' . $item->service->image) }}" 
                                     alt="{{ $item->service->name }}" 
                                     class="w-16 h-16 object-cover rounded">
                            @endif
                            
                            <div>
                                <h3 class="font-semibold">{{ $item->service->name }}</h3>
                                <p class="text-gray-600">{{ number_format($item->price, 2) }} €</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <!-- Contrôles de quantité -->
                            <div class="flex items-center space-x-2">
                                <button type="button" 
                                        class="quantity-btn bg-gray-200 hover:bg-gray-300 w-8 h-8 rounded-full flex items-center justify-center"
                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                    -
                                </button>
                                
                                <input type="number" 
                                       value="{{ $item->quantity }}" 
                                       min="1"
                                       class="w-16 text-center border rounded"
                                       onchange="updateQuantity({{ $item->id }}, this.value)">
                                
                                <button type="button" 
                                        class="quantity-btn bg-gray-200 hover:bg-gray-300 w-8 h-8 rounded-full flex items-center justify-center"
                                        onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                    +
                                </button>
                            </div>

                            <!-- Sous-total -->
                            <div class="text-right">
                                <p class="font-semibold subtotal">{{ number_format($item->subtotal, 2) }} €</p>
                            </div>

                            <!-- Bouton supprimer -->
                            <button type="button" 
                                    class="text-red-500 hover:text-red-700"
                                    onclick="removeItem({{ $item->id }})">
                                🗑️
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total et actions -->
            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between items-center">
                    <div>
                        <button type="button" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                                onclick="clearCart()">
                            Vider le panier
                        </button>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-lg">
                            Total: <span class="font-bold cart-total">{{ number_format($total, 2) }} €</span>
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $itemCount }} article(s)
                        </p>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <a href="{{ route('checkout') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg inline-block">
                        Passer la commande
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-xl text-gray-600 mb-4">Votre panier est vide</p>
            <a href="{{ route('services.index') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg inline-block">
                Continuer les achats
            </a>
        </div>
    @endif
</div>

<script>
function updateQuantity(itemId, quantity) {
    if (quantity < 1) {
        removeItem(itemId);
        return;
    }

    fetch(`/cart/${itemId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'affichage
            const item = document.querySelector(`[data-cart-item="${itemId}"]`);
            item.querySelector('.subtotal').textContent = data.subtotal + ' €';
            item.querySelector('input[type="number"]').value = quantity;
            document.querySelector('.cart-total').textContent = data.total + ' €';
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function removeItem(itemId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        return;
    }

    fetch(`/cart/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Supprimer l'élément du DOM
            document.querySelector(`[data-cart-item="${itemId}"]`).remove();
            document.querySelector('.cart-total').textContent = data.total + ' €';
            
            // Recharger la page si le panier est vide
            if (data.cart_count === 0) {
                location.reload();
            }
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function clearCart() {
    if (!confirm('Êtes-vous sûr de vouloir vider complètement votre panier ?')) {
        return;
    }

    fetch('/cart', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Erreur:', error));
}
</script>
@endsection