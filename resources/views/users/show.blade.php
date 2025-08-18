@extends('layouts.base')

@section('title', 'Détails de l\'utilisateur - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 50%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Détails de l'utilisateur</span>
                        </div>
                    </div>
                     <div class="card-body pt-3 pb-3" style="margin: auto; width: 95%; background: transparent !important;">
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

                        <div class="col-md-12 mt20 mt-2 text-center">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success">Modifier</a>
                            <a href="{{ route('users.index') }}" class="ms-3 btn btn-primary">Retour à la liste</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
