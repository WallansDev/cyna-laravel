@extends('layouts.base')

@section('title', $service->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mb-3 col-7">
        <div class="card service-category-card text-white mb-4">
            <div class="card-body">
                <div class="d-flex gap-0">
                    <div class="p-0 d-flex align-items-start align-items-center">
                        <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}"
                            class="img-fluid rounded" style="max-width: 300px;">
                    </div>
                    <div class="ps-2">
                        <h5 class="service-category-title">{{ $service->name }}</h5>
                        <p class="mb-0">{{ $service->description }}</p>
                        <div class="mt-3">
                            <h5 class="category-title-inline" style="font-size: inherit;">Caractéristiques techniques</h5>
                            <p class="mb-0">{{ $service->technical_specifications }}</p>
                        </div>
                        <div class="mt-3">
                            <h5 class="category-title-inline" style="font-size: inherit;">Prix</h5>
                            <p class="mb-0">200 €</p>
                        </div>
                        @if ($service->categories && count($service->categories))
                            <div class="mt-3">
                                @foreach ($service->categories as $category)
                                    <div class="mb-3">
                                        <h5 class="category-title-inline" style="font-size: inherit;">{{ $category->name }}
                                        </h5>
                                        <p class="mb-0">{{ $category->description }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                @if ($service->availbility)
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form mt-4">
                        @csrf
                        <input type="hidden" name="services_id" value="{{ $service->id }}">
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <label for="quantity" class="form-label mb-0" style="font-weight:bold">Quantité :</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1"
                                class="form-control input-qty d-inline-block me-2">
                            <button type="submit" class="btn btn-success mx-2">
                                Ajouter au panier
                            </button>
                        </div>
                    </form>
                @else
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <a class="btn btn-danger mx-2">Temporairement indisponible</a>
                    </div>
                @endif
            </div>

            <h3>Galerie</h3>
            <div class="row">
                @foreach ($service->gallery as $img)
                    <img src="{{ asset('storage/services/gallery/' . $img->image_path) }}" alt="">
                @endforeach

            </div>
        </div>
    </div>
@endsection
