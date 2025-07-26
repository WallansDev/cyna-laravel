@extends('layouts.base')

@section('title', 'Mon Panier')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mon Panier</h1>

    @if($cartItems->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border-b pb-4">
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
                            <!-- Formulaire de mise à jour de quantité -->
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                       class="w-16 text-center border rounded">
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded">
                                    Mettre à jour
                                </button>
                            </form>

                            <!-- Sous-total -->
                            <div class="text-right">
                                <p class="font-semibold">{{ number_format($item->subtotal, 2) }} €</p>
                            </div>

                            <!-- Formulaire de suppression -->
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Supprimer ce service du panier ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total et actions -->
            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between items-center">
                    <!-- Formulaire de vidage complet -->
                    <form action="{{ route('cart.clear') }}" method="POST"
                          onsubmit="return confirm('Vider complètement le panier ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                            Vider le panier
                        </button>
                    </form>

                    <div class="text-right">
                        <p class="text-lg">
                            Total: <span class="font-bold">{{ number_format($total, 2) }} €</span>
                        </p>
                        <p class="text-sm text-gray-600">
                            {{ $itemCount }} article(s)
                        </p>
                    </div>
                </div>

                <div class="mt-4 text-right">
                    <a href="{{ route('cart.index') }}" 
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
@endsection
