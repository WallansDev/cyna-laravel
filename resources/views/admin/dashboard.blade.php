@extends('layouts.base')

@section('title', 'Admin Dashboard - ' . $_SOCIETYNAME)

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tableau de bord administrateur</h1>
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('carousel.index') }}" class="btn btn-primary w-100 mb-3">Gérer le carousel</a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('categories.viewAdmin') }}" class="btn btn-primary w-100 mb-3">Catégories (admin)</a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('categories.orderIndex') }}" class="btn btn-primary w-100 mb-3">Ordre des catégories</a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('services.viewAdmin') }}" class="btn btn-primary w-100 mb-3">Services (admin)</a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('services.topProducts') }}" class="btn btn-primary w-100 mb-3">Ordre des services top</a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('users.index') }}" class="btn btn-primary w-100 mb-3">Utilisateurs</a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('accueil') }}" class="btn btn-secondary w-100 mb-3">Page d'accueil admin (ancienne)</a>
        </div>
    </div>
</div>
@endsection
