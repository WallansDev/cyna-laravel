@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')

    <div class="container">
        <h1 class="text-white mb-4" style="text-align: center;">Les catégories</h1>
        @foreach ($categories as $category)
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card mb-3 text-white service-category-card">
                        {{-- <img src="{{ asset('storage/services/' . $service->image_path) }}" class="card-img-top"
                            alt="{{ $service->name }}"> --}}
                        <div class="card-body text-white">
                            <h5 class="service-category-title">{{ $category->name }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('storage/categories/' . $category->image_path) }}" alt="{{ $category->name }}"
                                    class="img-fluid rounded" style="max-width: 100px; margin-right:8px;">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <p class="card-text mb-0" style="color:white;">{{ $category->description }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <a href="{{ route('categories.show', $category->id) }}" class="btn btn-afficher mx-2">Afficher la catégorie</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
