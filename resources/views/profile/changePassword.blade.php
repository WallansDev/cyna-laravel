@extends('layouts.base')

@section('title', 'Modification du mot de passe - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif ($errors->updatePassword->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->updatePassword->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password_confirmation">Confirmation</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>

        </form>
    </div>
@endsection
