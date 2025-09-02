@extends('layouts.base')

@section('title', 'Créer un e-mail personnalisé - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">

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
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 40%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Créer un e-mail personnalisé</span>
                        </div>
                    </div>
                    <div class="card-body pt-3 pb-3" style="margin: auto; width: 95%; background: transparent !important;">
                        <form action="{{ route('admin.email-test.custom') }}" method="POST">
                            @csrf
                            <div class="row padding-1 p-1">
                                <div class="col-md-12">
                                    <div class="form-group mb-2 mb20">
                                        <label class="form-label fw-bold">Utilisateur</label>
                                        <select name="user_id" class="form-control" data-bs-theme="dark" required>
                                            <option value="">Sélectionner un utilisateur...</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}
                                                    ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <br>
                                    <div class="form-group mb-2 mb20">
                                        <label class="form-label fw-bold">Sujet</label>
                                        <input type="text" name="subject" class="form-control" required
                                            placeholder="Sujet de l'email">
                                    </div>
                                    <br>
                                    <div class="form-group mb-2 mb20">
                                        <label class="form-label fw-bold">Message</label>
                                        <textarea name="message" class="form-control" rows="4" required placeholder="Contenu du message..."></textarea>
                                    </div>
                                    <br>
                                    <div class="col-md-12 mt20 mt-2 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Envoyer un e-mail personnalisé
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
