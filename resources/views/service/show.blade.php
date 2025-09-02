@extends('layouts.base')

@section('title', $service->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mb-3 col-11 col-md-7">
        <div class="card service-category-card text-white mb-4">
            <div class="card-body">
                <div class="row gap-0 flex-wrap align-items-start align-items-center">
                    <div class="col-12 col-md-5 p-0 d-flex justify-content-center mb-3 mb-md-0">
                        <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}"
                            class="img-fluid rounded" style="max-width: 300px;">
                    </div>
                    <div class="col-12 col-md-7 ps-md-2">
                        <h5 class="service-category-title">{{ $service->name }}</h5>
                        <p class="mb-0">{{ $service->description }}</p>
                        <div class="mt-3">
                            <h5 class="category-title-inline" style="font-size: inherit;">Caractéristiques techniques</h5>
                            <p class="mb-0">{{ $service->technical_specifications }}</p>
                        </div>
                        <div class="mt-3">
                            <h5 class="category-title-inline" style="font-size: inherit;">Prix</h5>
                            @if ($service->availbility)
                                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form mt-4">
                            @endif
                            @csrf
                            <input type="hidden" name="services_id" value="{{ $service->id }}">
                            <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                                <select name="price_type" id="price_type" class="form-select d-inline-block me-2"
                                    style="width: 180px; background: #2d2252; color: #fff; border: 1px solid #6c4bb6; border-radius: 8px; font-weight: bold;">
                                    <option value="monthly" selected>
                                        Mensuel : {{ number_format($service->price_monthly, 2, ',', ' ') }} €
                                    </option>
                                    <option value="yearly">
                                        Annuel : {{ number_format($service->price_yearly, 2, ',', ' ') }} €
                                    </option>
                                </select>
                                <label for="quantity" class="form-label mb-0" style="font-weight:bold">Quantité
                                    :</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1"
                                    class="form-control input-qty d-inline-block me-2" style="width: 80px;">
                                @if (!$service->availbility)
                                    <a class="btn btn-danger mx-2">Temporairement
                                        indisponible</a>
                                @else
                                    <button type="submit" class="btn btn-success mx-2">
                                        Ajouter au panier
                                    </button>
                                @endif
                            </div>
                            </form>
                        </div>
                        @if ($service->categories && count($service->categories))
                            <div class="mt-3">
                                @foreach ($service->categories as $category)
                                    <div class="mb-3">
                                        <h5 class="category-title-inline" style="font-size: inherit;"><a
                                                href="{{ route('categories.show', $category->id) }}"
                                                style="color:white;text-decoration:none;">{{ $category->name }}</a>
                                        </h5>
                                        <p class="mb-0">{{ $category->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <h5 class="service-category-title">Galerie</h5>
                <br>
                <div class="row">
                    @foreach ($service->gallery as $index => $img)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 gallery-card p-0 border-0" style="cursor:pointer; overflow:hidden;"
                                data-bs-toggle="modal" data-bs-target="#galleryModal"
                                data-img="{{ asset('storage/services/gallery/' . $img->image_path) }}">
                                <img src="{{ asset('storage/services/gallery/' . $img->image_path) }}"
                                    alt="Image galerie {{ $index + 1 }}"
                                    style="width:100%; height:100%; object-fit:cover; display:block;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Modal -->
                <div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content bg-dark">
                            <div class="modal-header border-0">
                                <h5 class="modal-title text-white" id="galleryModalLabel">Image</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex justify-content-center align-items-center">
                                <img id="galleryModalImg" src="" class="img-fluid rounded"
                                    alt="Image galerie agrandie">
                            </div>
                        </div>
                    </div>
                </div>

                @push('scripts')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var galleryCards = document.querySelectorAll('.gallery-card');
                            var modalImg = document.getElementById('galleryModalImg');
                            galleryCards.forEach(function(card) {
                                card.addEventListener('click', function() {
                                    var imgSrc = card.getAttribute('data-img');
                                    modalImg.setAttribute('src', imgSrc);
                                });
                            });
                        });
                    </script>
                @endpush
            </div>
        </div>
    </div>
@endsection
