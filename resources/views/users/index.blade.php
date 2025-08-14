@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Liste des utilisateurs</h4>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">Nouvel utilisateur</a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Admin</th>
                                        <th>Créé le</th>
                                        <th>Modifié le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->surname }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role ?? 'Utilisateur' }}</td>
                                            <td>
                                                @if ($user->is_admin)
                                                    <span class="badge bg-danger">Admin</span>
                                                @else
                                                    <span class="badge bg-secondary">Utilisateur</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->created_at_formatted }}</small>
                                                @if ($user->isRecentlyCreated())
                                                    <span class="badge bg-success ms-1">Nouveau</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $user->updated_at_formatted }}</small>
                                                @if ($user->isRecentlyUpdated())
                                                    <span class="badge bg-info ms-1">Modifié</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('users.show', $user->id) }}"
                                                        class="btn btn-sm btn-outline-primary">Voir</a>
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-sm btn-outline-warning">Modifier</a>
                                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
