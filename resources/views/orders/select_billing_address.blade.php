@extends('layouts.base')
@section('title', 'Adresse de facturation - ' . $_SOCIETYNAME)
@section('content')
    <div class="container py-5">
        <h1 class="text-white mb-4">Choisir une adresse de facturation</h1>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('order.store-billing-address') }}" method="POST" class="card p-4"
            style="background:#1b1724; border-color:#5c1d91;">
            @csrf
            <div class="list-group">
                @foreach ($addresses as $address)
                    <label class="list-group-item" style="background:#241b33; color:#fff; border-color:#5c1d91;">
                        <input class="form-check-input me-1" type="radio" name="billing_address_id"
                            value="{{ $address->id }}" {{ (int) $selectedId === (int) $address->id ? 'checked' : '' }}>
                        <span>{{ $address->full_address }}</span>
                        @if ($address->phone)
                            <span class="text-white-50"> — {{ $address->phone }}</span>
                        @endif
                    </label>
                @endforeach
            </div>

            @error('billing_address_id')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('billing-addresses.index') }}" class="btn btn-secondary">Gérer mes adresses</a>
                <button type="submit" class="btn btn-gold">Continuer vers le paiement</button>
            </div>
        </form>
    </div>
@endsection
