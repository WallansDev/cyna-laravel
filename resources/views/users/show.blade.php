@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Détails de l'utilisateur</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Informations personnelles</h5>
                                <p><strong>Nom :</strong> {{ $user->name }}</p>
                                <p><strong>Prénom :</strong> {{ $user->surname }}</p>
                                <p><strong>Email :</strong> {{ $user->email }}</p>
                                <p><strong>Téléphone :</strong> {{ $user->phone ?? 'Non renseigné' }}</p>
                                <p><strong>Rôle :</strong> {{ $user->role ?? 'Utilisateur' }}</p>
                                <p><strong>Admin :</strong> {{ $user->is_admin ? 'Oui' : 'Non' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Informations temporelles</h5>
                                <p><strong>Date de création :</strong> {{ $user->created_at_formatted }}</p>
                                <p><strong>Dernière modification :</strong> {{ $user->updated_at_formatted }}</p>

                                @if ($user->isRecentlyCreated())
                                    <span class="badge bg-success">Nouvel utilisateur</span>
                                @endif

                                @if ($user->isRecentlyUpdated())
                                    <span class="badge bg-info">Récemment modifié</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Modifier</a>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Retour à la liste</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
