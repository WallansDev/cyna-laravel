@extends('layouts.base')
@section('title', 'Mes commandes - ' . $_SOCIETYNAME)
@section('content')
<div class="container py-5">
    <h1 class="text-white mb-4">Mes commandes</h1>
    @forelse($orders as $order)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <span>
                        Commande #{{ $order->id }} - Total : {{ number_format($order->total, 2) }} € - Passée le {{ $order->created_at->format('d/m/Y H:i') }}
                    </span>
                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}">
                        Voir le récap
                    </button>
                </div>
                <div class="collapse mt-3" id="orderDetails{{ $order->id }}">
                    @if($order->items && count($order->items))
                        <ul class="list-group">
                            @foreach($order->items as $item)
                                <li class="list-group-item bg-dark text-white">
                                    {{ $item->service_name ?? $item->name }} - {{ $item->quantity }} x {{ number_format($item->price, 2) }} €
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-white-50">Aucun détail disponible pour cette commande.</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-white-50">Aucune commande passée.</p>
    @endforelse
</div>
@endsection