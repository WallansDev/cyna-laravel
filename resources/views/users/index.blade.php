@extends('layouts.base')

@section('title', 'Liste des utilisateurs - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 65%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Liste des utilisateurs') }}</span>
                            <a href="{{ route('users.create') }}" class="btn btn-gold btn-sm">
                                {{ __('Nouveau') }}
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-dark">
                                <thead>
                                    <tr>
                                        <th class="align-middle text-center">Nom</th>
                                        <th class="align-middle text-center">Prénom</th>
                                        <th class="align-middle text-center">Email</th>
                                        <th class="align-middle text-center">Rôle</th>
                                        <th class="align-middle text-center">Admin</th>
                                        <th class="align-middle text-center">Créé le</th>
                                        <th class="align-middle text-center">Modifié le</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="align-middle text-center">{{ $user->name }}</td>
                                            <td class="align-middle text-center">{{ $user->surname }}</td>
                                            <td class="align-middle text-center">{{ $user->email }}</td>
                                            <td class="align-middle text-center">{{ $user->role ?? 'Utilisateur' }}</td>
                                            <td class="align-middle text-center">
                                                @if ($user->is_admin)
                                                    <span class="badge bg-danger">Admin</span>
                                                @else
                                                    <span class="badge bg-success">Utilisateur</span>
                                                @endif
                                            </td>
                                             <td class="align-middle text-center">
                                                <small>{{ $user->created_at_formatted }}</small>
                                                @if ($user->isRecentlyCreated())
                                                    <span class="badge bg-success ms-1">Nouveau</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <small>{{ $user->updated_at_formatted }}</small>
                                                @if ($user->isRecentlyUpdated())
                                                    <span class="badge bg-info ms-1">Modifié</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <form class="d-flex flex-row justify-content-center gap-2" action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    <a class="btn btn-sm action-btn view-btn"
                                                       href="{{ route('users.show', $user->id) }}"><i class="bi bi-eye-fill"></i></a>
                                                    <a class="btn btn-sm action-btn edit-btn"
                                                       href="{{ route('users.edit', $user->id) }}"><i class="bi bi-pencil-fill"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm action-btn delete-btn"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
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
