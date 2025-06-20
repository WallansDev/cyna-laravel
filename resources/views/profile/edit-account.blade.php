@extends('layouts.base')

@section('title', 'Modification informations personnelles - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            <div class="row mb-3">
                <div class="col-1">

                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label class="mb-1" for="surname">Prénom</label>
                        <input type="text" class="form-control" id="surname" name="surname"
                            aria-describedby="SurnameHelp" placeholder="Votre prénom"
                            value="{{ old('surname', $user->surname) }}">
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label class="mb-1" for="name">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" aria-describedby="NameHelp"
                            placeholder="Votre nom" value="{{ old('name', $user->name) }}">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-1"></div>
                <div class="col-5">
                    <div class="form-group">
                        <label class="mb-1" for="siret">Siret</label>
                        <input type="text" class="form-control" id="siret" name="siret"
                            aria-describedby="SiretHelp" placeholder="Votre SIRET" value="{{ old('siret', $user->siret) }}">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-1"></div>
                <div class="col-5">
                    <div class="form-group">
                        <label class="mb-1" for="phone">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            aria-describedby="PhoneHelp" placeholder="Votre téléphone"
                            value="{{ old('phone', $user->phone) }}">
                    </div>
                </div>
                <div class="col-5">
                    <div class="form-group">
                        <label class="mb-1" for="email">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            aria-describedby="emailHelp" placeholder="Votre email" value="{{ old('email', $user->email) }}">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-5"><button type="submit" class="btn btn-primary">Modifier</button></div>
            </div>
        </form>
    </div>
@endsection
