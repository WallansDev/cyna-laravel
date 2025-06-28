@extends('layouts.base')

@section('title', 'Admin Dashboard - ' . $_SOCIETYNAME)

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tableau de bord administrateur</h1>
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('categories.viewAdmin') }}" class="text-decoration-none">
                <div class="card purple-header text-white text-center mb-3">
                    <div class="card-body">
                        Modifier les catégories
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('categories.orderIndex') }}" class="text-decoration-none">
                <div class="card purple-header text-white text-center mb-3">
                    <div class="card-body">
                        Ordre des catégories
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('services.viewAdmin') }}" class="text-decoration-none">
                <div class="card purple-header text-white text-center mb-3">
                    <div class="card-body">
                        Modifier les services
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('services.topProducts') }}" class="text-decoration-none">
                <div class="card purple-header text-white text-center mb-3">
                    <div class="card-body">
                        Ordre des services
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            <a href="{{ route('users.index') }}" class="text-decoration-none">
                <div class="card purple-header text-white text-center mb-3">
                    <div class="card-body">
                        Utilisateurs
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
