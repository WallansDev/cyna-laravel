@extends('layouts.base')
@section('title', 'Mes commandes - ' . $_SOCIETYNAME)
@section('content')
    <div class="container mt-5">
        <h1 class="text-white mb-4" style="text-align: center;">Mes commandes</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @forelse($orders as $order)
            <div class="d-flex justify-content-center">
                <div class="row pb-3 pt-3 justify-content-center service-category-card"
                    style="width: 70%;">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <div>
                                    Commande #{{ $order->id }} - Total : {{ number_format($order->total, 2) }} € - Passée
                                    le
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </div>
                                @if ($order->billingAddress)
                                    <div class="text-50 small mt-1"><b>Adresse de facturation :</b>
                                        {{ $order->billingAddress->full_address }}
                                    </div>
                                @endif
                                @if ($order->stripePayment && $order->stripePayment->applied_coupon_code)
                                    <div class="text-50 small"><b>Coupon :</b>
                                        {{ $order->stripePayment->applied_coupon_code }}
                                        (−{{ number_format($order->stripePayment->discount_amount ?? 0, 2) }} €)
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-center align-items-center w-100 mt-2 mt-md-0" style="max-width: 60px;">
                                <button class="btn btn-sm action-btn view-btn d-flex justify-content-center align-items-center mx-auto" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}">
                                    <i class="bi bi-eye-fill text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="collapse mt-3 " id="orderDetails{{ $order->id }}">
                        @if ($order->items && count($order->items))
                            <ul class="list-group">
                                @foreach ($order->items as $item)
                                    <li class="list-group-item bg-dark text-white service-category-card"
                                        style="border: none; background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                                        {{ $item->service_name ?? $item->name }} - {{ $item->quantity }} x
                                        {{ number_format($item->price, 2) }} €
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-white-50">Aucun détail disponible pour cette commande.</p>
                        @endif
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('orders.downloadInvoice', $order->id) }}" class="btn btn-success btn-sm"
                            target="_blank">
                            Télécharger la facture PDF
                        </a>
                    </div>
                </div>
            </div>

    </div>
@empty
    <p class="text-white-50">Aucune commande passée.</p>
    @endforelse
    </div>
@endsection
