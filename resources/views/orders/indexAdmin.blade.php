@extends('layouts.base')
@section('title', 'Toutes les commandes - ' . $_SOCIETYNAME)
@section('content')
    <div class="container py-5">
        <h1 class="text-white">Toutes les commandes</h1>
        <div class="mt-3 mb-3">
            <a href="{{ route('orders.ordersGraph') }}" class="btn btn-info">Afficher en graphique</a>
        </div>

        <form method="GET" class="card p-3 mb-3" style="background:#1b1724; border-color:#5c1d91;">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label text-white-50">Recherche</label>
                    <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                        placeholder="ID, total...">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label text-white-50">Statut paiement</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        @php($statuses = ['succeeded' => 'Réussi', 'processing' => 'En cours', 'requires_payment_method' => 'En attente', 'canceled' => 'Annulé', 'requires_action' => 'Action requise'])
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" value="1" id="hasCoupon" name="has_coupon"
                            {{ request('has_coupon') ? 'checked' : '' }}>
                        <label class="form-check-label text-white-50" for="hasCoupon">Avec coupon</label>
                    </div>
                </div>
                <div class="col-12 col-md-2 text-end">
                    <button class="btn btn-purple w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Utilisateur</th>
                        <th>Total (€)</th>
                        <th>Coupon</th>
                        <th>Remise (€)</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                @if ($order->user)
                                    {{ $order->user->name }}<br>
                                    <span class="text-white-50 small">{{ $order->user->email }}</span>
                                @else
                                    <span class="text-white-50">-</span>
                                @endif
                            </td>
                            <td>{{ number_format($order->total, 2) }}</td>
                            <td>{{ $order->stripePayment->applied_coupon_code ?? '-' }}</td>
                            <td>{{ $order->stripePayment ? number_format($order->stripePayment->discount_amount ?? 0, 2) : '-' }}
                            </td>
                            <td>
                                @php($status = $order->stripePayment->status ?? '—')
                                <span
                                    class="badge {{ $status === 'succeeded' ? 'bg-success' : ($status === 'processing' ? 'bg-warning' : 'bg-secondary') }}">
                                    @if ($status === 'succeeded')
                                        {{ 'Réussi' }}
                                    @elseif ($status === 'processing')
                                        {{ 'En attente' }}
                                    @else
                                        {{ 'Incomplet' }}
                                    @endif
                                </span>
                            </td>
                            <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#o{{ $order->id }}">Détails</button>
                            </td>
                        </tr>
                        <tr class="collapse" id="o{{ $order->id }}">
                            <td colspan="8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="text-white-50">Adresse de facturation</h6>
                                        <div>{{ $order->billingAddress->full_address ?? '-' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-white-50">Stripe</h6>
                                        @if ($order->stripePayment)
                                            <div>ID: {{ $order->stripePayment->payment_intent_id }}</div>
                                            <div>Montant:
                                                {{ number_format(($order->stripePayment->amount ?? 0) / 100, 2) }}
                                                {{ strtoupper($order->stripePayment->currency ?? 'eur') }}</div>
                                        @else
                                            <div class="text-white-50">—</div>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <h6 class="text-white-50">Articles</h6>
                                        @if ($order->items && $order->items->count())
                                            <ul class="list-group">
                                                @foreach ($order->items as $item)
                                                    <li class="list-group-item bg-dark text-white">
                                                        {{ $item->service_name }} × {{ $item->quantity }} —
                                                        {{ number_format($item->price, 2) }} €</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-white-50">—</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-white-50">Aucune commande</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
