@extends('layouts.base')

@section('content')
    <div class="container mx-auto px-4 py-8">

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Email personnalisé -->
        <div class="bg-black p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Email Personnalisé</h2>
            <form action="{{ route('admin.email-test.custom') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Utilisateur</label>
                    <select name="user_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Sujet</label>
                    <input type="text" name="subject" class="w-full border rounded px-3 py-2" required
                        placeholder="Sujet de l'email">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Message</label>
                    <textarea name="message" class="w-full border rounded px-3 py-2" rows="4" required
                        placeholder="Contenu du message..."></textarea>
                </div>
                <button type="submit" class="bg-orange-500 text-black px-4 py-2 rounded hover:bg-orange-600">
                    Envoyer Email Personnalisé
                </button>
            </form>
        </div>
    </div>
    </div>
@endsection
