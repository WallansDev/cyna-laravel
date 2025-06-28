@extends('layouts.base')

@section('title', 'Modification informations personnelles - ' . $_SOCIETYNAME)

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 text-center">Modifier vos informations personnelles</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card purple-header text-white shadow-lg border-0">
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="form-group">
                                    <label class="mb-1" for="surname">Prénom</label>
                                    <input type="text" class="form-control text-white" id="surname" name="surname"
                                        aria-describedby="SurnameHelp" placeholder="Votre prénom"
                                        value="{{ old('surname', $user->surname) }}"
                                        style="background-color: var(--primary-color); border: none;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="name">Nom</label>
                                    <input type="text" class="form-control text-white" id="name" name="name" aria-describedby="NameHelp"
                                        placeholder="Votre nom" value="{{ old('name', $user->name) }}"
                                        style="background-color: var(--primary-color); border: none;">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0 ">
                                <div class="form-group">
                                    <label class="mb-1" for="siret">Siret</label>
                                    <input type="text" class="form-control text-white" id="siret" name="siret"
                                        aria-describedby="SiretHelp" placeholder="Votre SIRET" value="{{ old('siret', $user->siret) }}"
                                        style="background-color: var(--primary-color); border: none;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="mb-1" for="phone">Téléphone</label>
                                    <input type="text" class="form-control text-white" id="phone" name="phone"
                                        aria-describedby="PhoneHelp" placeholder="Votre téléphone"
                                        value="{{ old('phone', $user->phone) }}"
                                        style="background-color: var(--primary-color); border: none;">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="mb-1" for="email">Adresse email</label>
                                    <input type="email" class="form-control text-white" id="email" name="email"
                                        aria-describedby="emailHelp" placeholder="Votre email" value="{{ old('email', $user->email) }}"
                                        style="background-color: var(--primary-color); border: none;">
                                    <small id="emailHelp" class="form-text text-white">Nous ne partagerons jamais votre email.</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary px-5">Modifier</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
