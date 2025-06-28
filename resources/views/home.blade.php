@extends('layouts.base')

@section('title', 'Carousel services top - ' . $_SOCIETYNAME)

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Bienvenue chez Cyna</h1>
            </div>
        </div>

        @if (count($carousels) > 0)
            <div id="carouselExample" class="carousel slide slide w-80 mx-auto" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($carousels as $index => $carousel)
                        <div class="carousel-item @if ($index == 0) active @endif" style="position: relative;">
                            <!-- Image de fond fixe -->
                            <img src="{{ asset('images/carousel-bg.png') }}" 
                                 style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1;" 
                                 alt="Background" draggable="false">
                            <!-- Image dynamique par-dessus -->
                            <a href="{{ route('services.index') }}" target="_blank" style="position: relative; z-index: 2; display: block;">
                                <img src="{{ asset('storage/services/' . $carousel->image_path) }}" 
                                     class="d-block"
                                     alt="{{ $carousel->title }}" 
                                     style="width: 15em; height: 15em; margin: 3em auto 0 auto; background: transparent; object-fit: contain; display: block;">
                            </a>
                            <!-- Texte sous l'image dynamique -->
                            <div class="carousel-caption d-block" style="background: linear-gradient(to top, rgba(5, 2, 28, 0.8) 10%, rgba(0, 0, 0, 0.0)); z-index: 3; position: static;">
                                <h5>{{ $carousel->name }}</h5>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev"
                    style="z-index: 4;">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next"
                    style="z-index: 4;">
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
            <br><br><h2 class="mb-4">Produits en vedette</h2>
            <div class="row g-4">
                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="{{ asset('images/tests-de-penetration.png') }}" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title text-center">Pentest</h5>
                            <a href="{{ route('services.index') }}" class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Voir l'offre</a>
                        </div>
                    </div>
                </div>

                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="{{ asset('images/audit-de-securite.png') }}" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title text-center">Audit de sécurité</h5>
                            <a href="{{ route('services.index') }}" class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Voir l'offre</a>
                        </div>
                    </div>
                </div>

                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="{{ asset('images/cctv.png') }}" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title text-center">SOC 24/7 pour MSP</h5>
                            <a href="{{ route('services.index') }}" class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Voir l'offre</a>
                        </div>
                    </div>
                </div>

                <!-- Product card -->
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 purple-header" style="border: none;">
                        <img src="{{ asset('images/service-client.png') }}" class="card-img-top" alt="Product image">
                        <div class="card-body">
                            <h5 class="card-title text-center">Réponse à incident</h5>
                            <a href="{{ route('services.index') }}" class="btn btn-primary w-100"
                                style="background-color: var(--primary-color); border: none;">Voir l'offre</a>
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
                    <a href="{{ route('categories.index') }}" style="text-decoration: none;">
                        <div class="card text-center purple-header" style="border: none;">
                            <div class="card-body py-4">
                                <i class="fa-solid fa-laptop mb-3" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title">Matériels</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <a href="{{ route('categories.index') }}" style="text-decoration: none;">
                        <div class="card text-center purple-header" style="border: none;">
                            <div class="card-body py-4">
                                <i class="fa-brands fa-uncharted mb-3" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title">Logiciels</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <a href="{{ route('categories.index') }}" style="text-decoration: none;">
                        <div class="card text-center purple-header" style="border: none;">
                            <div class="card-body py-4">
                                <i class="fa-solid fa-camera mb-3" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title">Sécurité</h5>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Category card -->
                <div class="col-6 col-md-3">
                    <a href="{{ route('categories.index') }}" style="text-decoration: none;">
                        <div class="card text-center purple-header" style="border: none;">
                            <div class="card-body py-4">
                                <i class="fa-solid fa-headset mb-3" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title">Conseil</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <br><br>
        </section>
    </div>
@endsection