@extends('layouts.base')

@section('title', "S'enregistrer - " . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row">
                <div class="col-3">
                    <label for="name">Nom</label>
                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-3">
                    <label for="surname">Prénom</label>
                    <div class="input-group mb-3">
                        <input type="text" name="surname" class="form-control" value="{{ old('surname') }}" required>
                    </div>
                    @error('surname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-3">
                    <label for="phone">Téléphone (+33)</label>
                    <div class="input-group mb-3">
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    @error('phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-5">
                    <label for="email">Email</label>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-5">
                    <label for="siret">SIRET</label>
                    <div class="input-group mb-3">
                        <input type="text" name="siret" class="form-control" value="{{ old('siret') }}" required>
                    </div>
                    @error('siret')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-5">
                    <label for="password">Mot de passe</label>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" value="{{ old('password') }}" required>
                    </div>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-5">
                    <label for="password_confirmation">Confirmer mot de passe</label>
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            value="{{ old('password_confirmation') }}" required>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-7"></div>
                <div class="col-3">
                    <a href="{{ route('login') }}">Déjà enregister ?</a>
                    &ensp;
                    <button class="btn btn-primary">S'enregister</button>
                </div>
            </div>
        </form>
    </div>
@endsection
