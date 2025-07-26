@extends('layouts.base')

@section('title', $service->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <h5>{{ $service->name }}</h5>
        <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}">
        <p>{{ $service->description }}</p>

        @if ($service->availbility)
            <a href="#" class="btn btn-success" style="float: right">Acheter</a>
        @else
            <a href="#" class="btn btn-danger" style="float: right" onclick="return false;">Temporairement
                indisponible</a>
        @endif

        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="services_id" value="{{ $service->id }}">
            
            <div class="flex items-center space-x-4 mb-4">
                <label for="quantity" class="font-medium">Quantité:</label>
                <input type="number" 
                    name="quantity" 
                    id="quantity" 
                    value="1" 
                    min="1" 
                    class="w-20 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                Ajouter au panier
            </button>
        </form>

    </div>
@endsection
