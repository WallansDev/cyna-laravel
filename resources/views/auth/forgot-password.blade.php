@extends('layouts.base')

@section('title', 'Mot de passe oublié - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-2"></div>
            <div class="col-7 mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre adresse électronique et nous vous enverrons un lien de réinitialisation du mot de passe qui vous permettra d\'en choisir un nouveau.') }}
            </div>
        </div>

        @if (session('status'))
            <div class="row mt-3">
                <div class="mx-auto col-7 mt-3">
                    <div class="alert alert-info">
                        {{ 'Un lien de réinitialisation du mot passe à été envoyé.' }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row mt-3">
            <div class="col-3"></div>
            <div class="col-4">

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <label for="email">Email</label>
                    <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                        autofocus />
                    <error :messages="$errors - > get('email')" class="mt-2" />

                    <button class="btn btn-success mt-3" type="submit">Envoyer le lien de réinitialisation</button>
                </form>
            </div>
        </div>
    </div>
@endsection
