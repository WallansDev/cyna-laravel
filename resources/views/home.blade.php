@extends('layouts.base')

@section('title', 'Carousel services top - ' . $_SOCIETYNAME)

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Bienvenue chez Cyna</h1>
            </div>
        </div>
        <h2 class="text-center mb-4">Carousel Services top</h2>

        @if (count($carousels) > 0)
            <div id="carouselExample" class="carousel slide slide w-50 mx-auto" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($carousels as $index => $carousel)
                        <div class="carousel-item @if ($index == 0) active @endif">
                            <img src="{{ asset('storage/services/' . $carousel->image_path) }}" class="d-block w-100"
                                alt="{{ $carousel->title }}">
                            {{-- <img src="{{ asset($carousel->image_path) }}" class="d-block w-100"
                alt="{{ $carousel->title }}"> --}}
                            <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                                <h5>{{ $carousel->name }}</h5>
                                <p>{{ $carousel->description }}</p>
                                <br>
                                <a class="btn btn-success" href="{{ route('services.index') }}"
                                    target="_blank">Consulter</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        @else
            <p class="text-center">Aucune image trouvée.</p>
        @endif
    </div>

    <!-- Main content area -->
    <div class="container">
        <!-- Placeholder content for demonstration -->

        <!-- Featured products section -->
        <section class="mb-5">
            <h2 class="mb-4">Produits en vedette</h2>
            <div class="row g-4">
                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="/api/placeholder/300/300" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title">Produit 1</h5>
                            <p class="card-text">29,99 €</p>
                            <button class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="/api/placeholder/300/300" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title">Produit 2</h5>
                            <p class="card-text">39,99 €</p>
                            <button class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="/api/placeholder/300/300" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title">Produit 3</h5>
                            <p class="card-text">49,99 €</p>
                            <button class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Ajouter au panier</button>
                        </div>
                    </div>
                </div>

                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="/api/placeholder/300/300" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title">Produit 4</h5>
                            <p class="card-text">19,99 €</p>
                            <button class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Ajouter au panier</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories section -->
        <section>
            <h2 class="mb-4">Catégories</h2>
            <div class="row g-4">
                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <div class="card text-center purple-header" style="border: none;">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-laptop mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Matériels</h5>
                        </div>
                    </div>
                </div>

                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <div class="card text-center purple-header" style="border: none;">
                        <div class="card-body py-4">
                            <i class="fa-brands fa-uncharted mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Logiciels</h5>
                        </div>
                    </div>
                </div>

                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <div class="card text-center purple-header" style="border: none;">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-camera mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Sécurité</h5>
                        </div>
                    </div>
                </div>

                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <div class="card text-center purple-header" style="border: none;">
                        <div class="card-body py-4">
                            <i class="fa-solid fa-headset mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="card-title">Conseil</h5>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </section>
    </div>
@endsection
