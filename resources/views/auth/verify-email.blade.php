@extends('layouts.base')

@section('title', 'Vérifier adresse e-mail - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <div class="row mt-3">
            <div class="mx-auto col-7">
                {{ __('Pour vérifier votre adresse électronique, cliquez sur le lien envoyer par courrier électronique ? Si vous ne l\'avez pas reçu, cliquez sur le bouton') }}
            </div>
        </div>
        <div class="row mt-6">
            <div class="mx-auto col-7 mt-3">
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-info">
                        {{ 'Un nouveau lien de vérification a été envoyé à l\'adresse électronique reliée à votre compte.' }}
                    </div>
                @endif
            </div>
        </div>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <div class="row mt-3">
                <div class="mx-auto col-3">
                    <button type="submit" class="btn btn-secondary">Envoyer email de verification</button>
                </div>
            </div>
        </form>
    </div>
@endsection
