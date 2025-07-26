@extends('layouts.base')

@section('title', 'Utilisateurs - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('users.create') }}" class="btn btn-primary">Ajouter un utilisateur</a>
        <br><br>
        <h2>Liste des utilisateurs</h2>
        <table class="table table-bordered mt-3">
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Surname</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Is admin</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->surname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->is_admin }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Modifier</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
