@extends('layouts.base')

@section('title', 'Mon Panier - ' . $_SOCIETYNAME)

@section('content')

    <div class="container py-5">
        <h1 class="text-white mb-4" style="text-align: center;">Mon Panier</h1>

        @if ($cartItems->count() > 0)
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden"
                style="background: linear-gradient(135deg, var(--primary-color), #1b1724) !important;">
                <div class="card-body px-4 py-4">
                    @foreach ($cartItems as $item)
                        <div class="d-flex justify-content-between align-items-center py-3 border-bottom"
                            style="border-color: #5c1d91;">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                @if ($item->service->image)
                                    <img src="{{ asset('storage/' . $item->service->image) }}"
                                        alt="{{ $item->service->name }}" class="img-thumbnail rounded"
                                        style="width: 80px; height: 80px; object-fit: cover; border: none;">
                                @endif

                                <div>
                                    <h5 class="mb-1 text-white fw-bold">{{ $item->service->name }}</h5>
                                    <p class="mb-0 text-white-50">
                                        {{ number_format($item->price, 2) }} €
                                        <span class="badge bg-secondary ms-2">
                                            {{ $item->price_type === 'yearly' ? 'Annuel' : 'Mensuel' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                    class="d-flex align-items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                        class="form-control input-qty d-inline-block me-2" style="width: 70px;">
                                    <button type="submit" class="btn btn-purple px-3 py-1"><i
                                            class="bi bi-arrow-clockwise"></i></button>
                                </form>

                                <div class="text-white fw-bold">
                                    {{ number_format($item->subtotal, 2) }} €
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Supprimer ce service du panier ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Total & Actions -->
                    <div class="pt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                            <form action="{{ route('cart.clear') }}" method="POST"
                                onsubmit="return confirm('Vider complètement le panier ?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm px-3 py-1"><i
                                        class="bi bi-trash-fill"></i></button>
                            </form>

                            <div class="ms-auto" style="max-width: 420px; width:100%;">
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="d-flex gap-2 mb-2">
                                    @csrf
                                    <input type="text" name="code" class="form-control" placeholder="Code promo"
                                        value="{{ session('selected_coupon.code') }}" />
                                    <button type="submit" class="btn btn-purple">Appliquer</button>
                                </form>
                                @if (session('selected_coupon'))
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST" class="mb-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-light btn-sm">Retirer le code
                                            promo</button>
                                    </form>
                                @endif

                                <div class="text-end">
                                    <p class="h6 text-white-50 mb-1">Sous-total : <strong>{{ number_format($total, 2) }}
                                            €</strong></p>
                                    @if (session('selected_coupon'))
                                        <p class="h6 text-white-50 mb-1">Remise ({{ session('selected_coupon.code') }}):
                                            <strong>-{{ number_format(session('selected_coupon.discount_amount', 0), 2) }}
                                                €</strong>
                                        </p>
                                        <p class="h5 text-white">Total après remise :
                                            <strong>{{ number_format(max(0, $total - session('selected_coupon.discount_amount', 0)), 2) }}
                                                €</strong>
                                        </p>
                                    @else
                                        <p class="h5 text-white">Total : <strong>{{ number_format($total, 2) }} €</strong>
                                        </p>
                                    @endif
                                    <p class="text-white-50">{{ $itemCount }} article(s)</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <form action="{{ route('cart.checkout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-gold">
                                    <i class="fas fa-credit-card me-2"></i>Passer la commande
                                </button>
                            </form>
                        </div>
                    </div>



                </div>
            </div>
    </div>
@else
    <div class="text-center py-5">
        <p class="text-white fs-4 mb-4">Votre panier est vide</p>
        <a href="{{ route('services.index') }}" class="btn btn-gold btn-sm">Continuer les achats</a>
    </div>
    @endif
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionne tous les formulaires de mise à jour de quantité
            const updateForms = document.querySelectorAll('form[action^="{{ route('cart.update', '') }}"]');

            updateForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const url = form.action;
                    const formData = new FormData(form);

                    fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': form.querySelector('input[name="_token"]')
                                    .value,
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Met à jour le sous-total
                                const subtotalDiv = form.parentElement.querySelector(
                                    '.text-white.fw-bold');
                                if (subtotalDiv) {
                                    subtotalDiv.textContent = data.subtotal.toFixed(2) + ' €';
                                }

                                // Met à jour le total général
                                const totalElem = document.querySelector(
                                    '.text-end p.h5 strong');
                                if (totalElem) {
                                    totalElem.textContent = data.total.toFixed(2) + ' €';
                                }
                            } else {
                                alert('Erreur lors de la mise à jour');
                            }
                        })
                        .catch(() => alert('Erreur réseau'));
                });
            });
        });
    </script>
@endsection
