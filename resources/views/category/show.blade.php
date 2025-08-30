@extends('layouts.base')

@section('title', 'Catégorie ' . $category->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mb-3 col-11 col-md-7">
        <div class="card service-category-card text-white mb-4">
            <div class="card-body">
                <div class="row gap-0 flex-wrap align-items-start align-items-center">
                    <div class="col-12 col-md-5 p-0 d-flex justify-content-center mb-3 mb-md-0">
                        <img src="{{ asset('storage/categories/' . $category->image_path) }}" alt="{{ $category->name }}" class="img-fluid rounded" style="max-width: 300px;">
                    </div>
                    <div class="col-12 col-md-7 ps-md-2">
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
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-md-4 d-flex justify-content-center mb-2 mb-md-0">
                                    <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}"
                                        class="img-fluid rounded" style="max-width: 100px;">
                                </div>
                                <div class="col-12 col-md-8 d-flex flex-column justify-content-center h-100">
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
