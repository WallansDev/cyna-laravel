@extends('layouts.base')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Mes adresses de facturation</h1>
                <a href="{{ route('profile.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Retour au profil
                </a>
            </div>

            {{-- @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif --}}

            <!-- Formulaire d'ajout d'adresse -->
            @if ($addresses->count() < 5)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Ajouter une nouvelle adresse</h2>

                    <form action="{{ route('billing-addresses.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="address_line1" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse principale *
                                </label>
                                <input type="text" id="address_line1" name="address_line1"
                                    value="{{ old('address_line1') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address_line1') border-red-500 @enderror"
                                    required>
                                @error('address_line1')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="address_line2" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adresse secondaire
                                </label>
                                <input type="text" id="address_line2" name="address_line2"
                                    value="{{ old('address_line2') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address_line2') border-red-500 @enderror">
                                @error('address_line2')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                    Ville *
                                </label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('city') border-red-500 @enderror"
                                    required>
                                @error('city')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">
                                    Code postal *
                                </label>
                                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('postal_code') border-red-500 @enderror"
                                    required>
                                @error('postal_code')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pays *
                                </label>
                                <input type="text" id="country" name="country" value="{{ old('country') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('country') border-red-500 @enderror"
                                    required>
                                @error('country')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Téléphone *
                                </label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror"
                                    required>
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                Ajouter l'adresse
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Liste des adresses existantes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Adresses enregistrées ({{ $addresses->count() }}/5)</h2>

                @if ($addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($addresses as $address)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="font-semibold text-gray-900">Adresse #{{ $loop->iteration }}</h3>
                                    <form action="{{ route('billing-addresses.destroy', $address->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')"
                                            class="text-red-600 hover:text-red-800 text-sm">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>

                                <div class="space-y-1 text-sm text-gray-600">
                                    <p><strong>Adresse :</strong> {{ $address->address_line1 }}</p>
                                    @if ($address->address_line2)
                                        <p><strong>Complément :</strong> {{ $address->address_line2 }}</p>
                                    @endif
                                    <p><strong>Ville :</strong> {{ $address->city }}</p>
                                    <p><strong>Code postal :</strong> {{ $address->postal_code }}</p>
                                    <p><strong>Pays :</strong> {{ $address->country }}</p>
                                    <p><strong>Téléphone :</strong> {{ $address->phone }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Aucune adresse de facturation enregistrée.</p>
                        <p class="text-sm text-gray-400 mt-2">Ajoutez votre première adresse de facturation ci-dessus.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
