@extends('layouts.base')

@section('title', 'Dashboard - ' . $_SOCIETYNAME)

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4 text-center">Bienvenue sur votre espace compte</h1>
        </div>
    </div>
    <div class="row justify-content-center g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 purple-header text-center" style="border: none;">
                <div class="card-body py-4">
                    <i class="fa-solid fa-user-pen mb-3" style="font-size: 2.5rem;"></i>
                    <h5 class="card-title mb-3">Modifier vos informations</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-success w-100">Modifier informations du compte</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 purple-header text-center" style="border: none;">
                <div class="card-body py-4">
                    <i class="fa-solid fa-key mb-3" style="font-size: 2.5rem;"></i>
                    <h5 class="card-title mb-3">Changer votre mot de passe</h5>
                    <a href="{{ route('password.edit') }}" class="btn btn-success w-100">Modifier mot de passe</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
