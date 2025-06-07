@extends('layouts.base')

@section('title', 'Carousel services top - ' . $_SOCIETYNAME)

@section('content')

    <div class="container mt-5">
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
@endsection
