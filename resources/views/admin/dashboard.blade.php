@extends('layouts.base')

@section('title', 'Admin Dashboard - ' . $_SOCIETYNAME)

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tableau de bord administrateur</h1>
    <div class="row g-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('categories.viewAdmin') }}" style="text-decoration: none;">
                <div class="card text-center purple-header" style="border: none;">
                    <div class="card-body py-4">
                        <i class="fa-solid fa-pen-to-square mb-3" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title">Modifier les catégories</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('categories.orderIndex') }}" style="text-decoration: none;">
                <div class="card text-center purple-header" style="border: none;">
                    <div class="card-body py-4">
                        <i class="fa-solid fa-arrow-up-wide-short mb-3" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title">Ordre des catégories</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('services.viewAdmin') }}" style="text-decoration: none;">
                <div class="card text-center purple-header" style="border: none;">
                    <div class="card-body py-4">
                        <i class="fa-solid fa-pen-to-square mb-3" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title">Modifier les services</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('services.topProducts') }}" style="text-decoration: none;">
                <div class="card text-center purple-header" style="border: none;">
                    <div class="card-body py-4">
                        <i class="fa-solid fa-arrow-up-wide-short mb-3" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title">Ordre des services</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('users.index') }}" style="text-decoration: none;">
                <div class="card text-center purple-header" style="border: none;">
                    <div class="card-body py-4">
                        <i class="fa-solid fa-users mb-3" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title">Utilisateurs</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="https://analytics.google.com/" style="text-decoration: none;">
                <div class="card text-center purple-header" style="border: none;">
                    <div class="card-body py-4">
                        <i class="fa-solid fa-chart-simple mb-3" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title">Google Analytics</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
