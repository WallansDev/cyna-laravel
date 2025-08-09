@extends('layouts.base')

@section('title', 'Catégorie ' . $category->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mb-3 col-7">
        <div class="card service-category-card text-white mb-4">
            <div class="card-body">
                <div class="d-flex gap-0">
                    <div class="p-0 d-flex align-items-start align-items-center">
                        <img src="{{ asset('storage/categories/' . $category->image_path) }}" alt="{{ $category->name }}" class="img-fluid rounded" style="max-width: 300px;">
                    </div>
                    <div class="ps-2">
                        <h5 class="service-category-title">{{ $category->name }}</h5>
                        <p class="mb-0">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <h5 style="text-align: center;">Services liés à la catégorie {{ $category->name }}</h5>
        @foreach ($category->services as $service)
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card mb-3 text-white service-category-card">
                        {{-- <img src="{{ asset('storage/services/' . $service->image_path) }}" class="card-img-top"
                            alt="{{ $service->name }}"> --}}
                        <div class="card-body text-white">
                            <h5 class="service-category-title">{{ $service->name }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}"
                                    class="img-fluid rounded" style="max-width: 100px; margin-right:8px;">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <p class="card-text mb-0" style="color:white;">{{ $service->description }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                @if ($service->availbility)
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-afficher mx-2">Afficher
                                        le service</a>
                                @else
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-danger mx-2">Temporairement
                                        indisponible</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
